<?php 

namespace App\Http\Controllers; 

use Illuminate\Http\Request; 
use App\Models\Order; 
use App\Models\OrderItem; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB; 

class CheckoutController extends Controller 
{ 
    /**
     * 1. Hiển thị trang giỏ hàng & thanh toán
     */
    public function index(Request $request) 
    { 
        $isWebgiadung = $request->is('webgiadung*');
        $prefix = $isWebgiadung ? '/webgiadung' : '';

        if (!Auth::check()) {
            return redirect()->to($prefix . '/login?redirect=' . urlencode($prefix . '/cart#checkoutOrderForm'))->with('error', 'Vui lòng đăng nhập để tiếp tục thanh toán.');
        }

        // Nếu có tham số buy_now_product_id từ nút mua ngay
        if ($request->has('buy_now_product_id')) {
            $productId = $request->query('buy_now_product_id');
            return app(CartController::class)->buyNow($request, $productId);
        }

        return redirect()->to($prefix . route('cart.index', [], false)); 
    } 

    /**
     * 2. Xử lý đặt hàng: lưu thông tin người nhận (Tên, SĐT, Địa chỉ, Ghi chú) và phương thức thanh toán
     */
    public function process(Request $request) 
    { 
        $isWebgiadung = $request->is('webgiadung*');
        $prefix = $isWebgiadung ? '/webgiadung' : '';

        if (!Auth::check()) {
            return redirect()->to($prefix . '/login?redirect=' . urlencode($prefix . '/cart#checkoutOrderForm'))->with('error', 'Vui lòng đăng nhập để tiếp tục thanh toán.');
        }

        $cart = session()->get('cart', []); 
        if (empty($cart)) { 
            return redirect()->to($prefix . route('cart.index', [], false))->with('error', 'Giỏ hàng của bạn đang trống.');  
        } 

        // Xác thực dữ liệu đầu vào: Tên, Số điện thoại, Địa chỉ giao hàng
        $validated = $request->validate([
            'receiver_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'note' => 'nullable|string|max:1000',
            'payment_method' => 'required|string',
        ], [
            'receiver_name.required' => 'Vui lòng nhập họ và tên người nhận hàng.',
            'phone_number.required' => 'Vui lòng nhập số điện thoại nhận hàng.',
            'shipping_address.required' => 'Vui lòng nhập địa chỉ giao hàng chi tiết.',
            'payment_method.required' => 'Vui lòng chọn hình thức thanh toán.',
        ]);

        $paymentMethod = $request->input('payment_method'); 

        try { 
            DB::beginTransaction(); 

            // Tính tổng tiền thực tế từ giỏ hàng server-side 
            $totalAmount = collect($cart)->sum(function ($details) { 
                return $details['price'] * $details['quantity']; 
            }); 

            $customerName = trim($request->input('receiver_name'));
            $phoneNumber = trim($request->input('phone_number'));
            $shippingAddress = trim($request->input('shipping_address'));
            $note = $request->input('note');

            // Tạo bản ghi đơn hàng mới vào DB (tương thích mọi phiên bản schema)
            $order = Order::create([ 
                'user_id' => Auth::id(), 
                'total' => $totalAmount, 
                'amount' => $totalAmount,
                'status' => 'pending', 
                'payment_status' => 'unpaid',
                'payment_method' => $paymentMethod, 
                'order_code' => config('sepay.pattern', 'DH') . rand(1000, 9999),
                'receiver_name' => $customerName,
                'customer_name' => $customerName,
                'customer_email' => Auth::user()->email ?? null,
                'customer_phone' => $phoneNumber,
                'phone_number' => $phoneNumber,
                'shipping_address' => $shippingAddress,
                'note' => $note,
                'notes' => $note,
            ]); 

            // Cập nhật lại order_code có ID chính xác để SePay khớp nội dung chuyển khoản: ví dụ DH15
            $order->update([
                'order_code' => config('sepay.pattern', 'DH') . $order->id
            ]);

            // Lưu chi tiết từng sản phẩm vào order_items (đảm bảo an toàn ràng buộc khóa ngoại)
            foreach ($cart as $id => $details) { 
                $exists = \App\Models\Product::where('id', $id)->exists();
                OrderItem::create([ 
                    'order_id' => $order->id, 
                    'product_id' => $exists ? $id : null, 
                    'product_name' => $details['name'] ?? ('Sản phẩm #' . $id),
                    'quantity' => $details['quantity'], 
                    'price' => $details['price'], 
                ]); 
            } 

            // Cập nhật thông tin địa chỉ, SĐT vào tài khoản user nếu chưa có
            if (Auth::check()) {
                $user = Auth::user();
                $updateUserData = [];
                if (empty($user->phone) && !empty($phoneNumber)) {
                    $updateUserData['phone'] = $phoneNumber;
                }
                // (Bỏ qua cập nhật address vì bảng users không có cột address trong CSDL)
                if (!empty($updateUserData)) {
                    $user->update($updateUserData);
                }
            }

            // Xóa giỏ hàng sau khi đặt thành công 
            session()->forget('cart'); 
            session()->flash('cart_cleared', true);
            session()->save();

            DB::commit(); 

            // Phân nhánh chuyển hướng theo phương thức thanh toán 
            if (strtoupper($paymentMethod) === 'COD') { 
                if (Auth::check()) {
                    return redirect()->to($prefix . route('orders.index', [], false))->with('success', "Cảm ơn bạn! Đơn hàng #{$order->id} ({$order->order_code}) thanh toán tiền mặt khi nhận hàng (COD) đã được ghi nhận.");  
                } else {
                    return redirect()->to($prefix . route('welcome', [], false))->with('success', "Cảm ơn bạn! Đơn hàng #{$order->id} ({$order->order_code}) thanh toán tiền mặt khi nhận hàng (COD) đã được ghi nhận thành công.");
                }
            } else { 
                // Chuyển hướng sang trang Thanh Toán Tự Động SePay 24/7 (hỗ trợ BIDV, VietQR, MoMo)
                return redirect()->to($prefix . route('sepay.payment', ['order_id' => $order->id], false)); 
            } 
        } catch (\Exception $e) { 
            DB::rollBack(); 
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xử lý đơn hàng: ' . $e->getMessage())->withInput();  
        } 
    } 

    /**
     * 3. Hiển thị trang QR Chuyển khoản Ngân hàng VietQR (BIDV)
     */
    public function showBankQr() 
    { 
        $totalAmount = session()->get('bank_total', session()->get('chuyển khoản ngân hàng VPBank_total', 0));   
        $orderId = session()->get('bank_order_id', null);

        $bankId = env('VIETQR_BANK_ID', 'BIDV'); 
        $accountNo = env('VIETQR_ACCOUNT_NO', '3300793572'); 
        $accountName = env('VIETQR_ACCOUNT_NAME', 'NGUYEN VAN NAM'); 
        $bankFullName = 'BIDV - Ngân hàng TMCP Đầu tư & Phát triển Việt Nam';
        $orderInfo = $orderId ? "Thanh toan don hang {$orderId}" : "Thanh toan don hang";

        $qrCodeUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-compact2.png?amount={$totalAmount}&addInfo=" . urlencode($orderInfo) . "&accountName=" . urlencode($accountName); 

        return view('checkout.bank-qr', compact('totalAmount', 'qrCodeUrl', 'bankId', 'accountNo', 'accountName', 'bankFullName', 'orderInfo')); 
    } 

    public function showMomoQr()
    {
        return $this->showBankQr();
    }
}
