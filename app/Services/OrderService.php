<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Tạo đơn hàng mới từ giỏ hàng session
     * Bao gồm: tính tổng tiền, lưu order, lưu order_items, cập nhật order_code, cập nhật user info
     */
    public function createFromCart(array $cart, array $info): Order
    {
        DB::beginTransaction();

        try {
            // Tính tổng tiền server-side
            $totalAmount = collect($cart)->sum(fn($d) => $d['price'] * $d['quantity']);

            $order = Order::create([
                'user_id'          => Auth::id(),
                'total'            => $totalAmount,
                'amount'           => $totalAmount,
                'status'           => 'pending',
                'payment_status'   => 'unpaid',
                'payment_method'   => $info['payment_method'],
                'order_code'       => config('sepay.pattern', 'DH') . rand(1000, 9999),
                'receiver_name'    => $info['receiver_name'],
                'customer_name'    => $info['receiver_name'],
                'customer_email'   => Auth::user()->email ?? null,
                'customer_phone'   => $info['phone_number'],
                'phone_number'     => $info['phone_number'],
                'shipping_address' => $info['shipping_address'],
                'note'             => $info['note'] ?? null,
                'notes'            => $info['note'] ?? null,
            ]);

            // Cập nhật order_code chính xác theo ID đơn hàng (dùng cho SePay)
            $order->update([
                'order_code' => config('sepay.pattern', 'DH') . $order->id,
            ]);

            // Lưu từng sản phẩm vào order_items
            foreach ($cart as $id => $details) {
                $exists = Product::where('id', $id)->exists();

                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $exists ? $id : null,
                    'product_name' => $details['name'] ?? ('Sản phẩm #' . $id),
                    'quantity'     => $details['quantity'],
                    'price'        => $details['price'],
                ]);
            }

            // Cập nhật phone / address vào tài khoản user nếu chưa có
            if (Auth::check()) {
                $user = Auth::user();
                $updateData = [];

                if (empty($user->phone) && !empty($info['phone_number'])) {
                    $updateData['phone'] = $info['phone_number'];
                }
                if (empty($user->address) && !empty($info['shipping_address'])) {
                    $updateData['address'] = $info['shipping_address'];
                }

                if (!empty($updateData)) {
                    $user->update($updateData);
                }
            }

            DB::commit();

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Tạo đơn hàng nhanh (dùng cho OrderController::store - không yêu cầu thông tin nhận hàng chi tiết)
     */
    public function createQuick(array $cart, string $paymentMethod = 'COD'): Order
    {
        DB::beginTransaction();

        try {
            $total = collect($cart)->sum(fn($d) => $d['price'] * $d['quantity']);

            $order = Order::create([
                'user_id'        => Auth::id(),
                'total'          => $total,
                'amount'         => $total,
                'status'         => 'processing',
                'payment_method' => $paymentMethod,
                'order_code'     => 'ORD-' . strtoupper(uniqid()),
                'customer_name'  => Auth::user()->name ?? 'Khách Hàng',
                'customer_email' => Auth::user()->email ?? null,
                'customer_phone' => Auth::user()->phone ?? '0988888888',
            ]);

            foreach ($cart as $id => $details) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $id,
                    'quantity'   => $details['quantity'],
                    'price'      => $details['price'],
                ]);
            }

            DB::commit();

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Lấy danh sách đơn hàng của người dùng đang đăng nhập
     */
    public function getOrdersForCurrentUser()
    {
        return Order::where('user_id', Auth::id())
            ->with('items.product')
            ->latest()
            ->get();
    }
}
