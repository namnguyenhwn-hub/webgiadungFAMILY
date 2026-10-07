<?php 

namespace App\Http\Controllers; 

use Illuminate\Http\Request; 
use App\Models\Order; 
use App\Models\OrderItem; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB; 

class OrderController extends Controller 
{ 
    /**
     * Hiển thị danh sách đơn hàng của người dùng đang đăng nhập
     */
    public function index() 
    { 
        $orders = Order::where('user_id', Auth::id()) 
            ->with('items.product') 
            ->latest() 
            ->get(); 

        return view('orders.index', compact('orders')); 
    } 

    /**
     * Xử lý lưu đơn hàng từ giỏ hàng (Hỗ trợ COD hoặc Chuyển khoản Ngân hàng VietQR)
     */
    public function store(Request $request) 
    { 
        $cart = session()->get('cart', []); 
        if (empty($cart)) { 
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.'); 
        } 

        try {
            DB::beginTransaction(); 

            // Tự tính tổng tiền từ server để đảm bảo chính xác 
            $total = collect($cart)->sum(function ($details) { 
                return $details['price'] * $details['quantity']; 
            }); 

            // 1. Tạo bản ghi đơn hàng mới (orders) 
            $order = Order::create([ 
                'user_id' => Auth::id(), 
                'total' => $total, 
                'status' => 'processing', 
                'payment_method' => $request->input('payment_method', 'COD'), 
                'order_code' => 'ORD-' . strtoupper(uniqid()),
                'customer_name' => Auth::user()->name ?? 'Khách Hàng',
                'customer_email' => Auth::user()->email ?? null,
                'customer_phone' => Auth::user()->phone ?? '0988888888',
                'amount' => $total,
            ]); 

            // 2. Lưu từng sản phẩm từ giỏ hàng vào bảng chi tiết (order_items) 
            foreach ($cart as $id => $details) { 
                OrderItem::create([ 
                    'order_id' => $order->id, 
                    'product_id' => $id, 
                    'quantity' => $details['quantity'], 
                    'price' => $details['price'], 
                ]); 
            } 

            // 3. Xóa giỏ hàng trong Session sau khi đã lưu thành công 
            session()->forget('cart'); 
            session()->flash('cart_cleared', true);
            session()->save();

            DB::commit(); 

            return redirect()->route('orders.index')->with('success', 'Đặt hàng thành công!'); 
        } catch (\Exception $e) { 
            DB::rollBack(); 
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xử lý đơn hàng. Vui lòng thử lại: ' . $e->getMessage());
        } 
    } 
}
