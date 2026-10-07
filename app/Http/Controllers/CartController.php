<?php 

namespace App\Http\Controllers; 

use App\Models\Product; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller 
{ 
    /**
     * Hiển thị giỏ hàng
     */
    public function index() 
    { 
        $cart = session()->get('cart', []); 

        // Tự động làm sạch và đồng bộ dữ liệu giỏ hàng chuẩn từ cơ sở dữ liệu MySQL
        if (!empty($cart)) { 
            $productIds = array_keys($cart); 
            $products = Product::with('category')->whereIn('id', $productIds)->get()->keyBy('id'); 
            $cleaned = [];

            foreach ($cart as $id => $item) { 
                $id = (int)$id;
                if (isset($products[$id])) { 
                    $p = $products[$id];
                    $category = $p->category;
                    $categoryName = $category ? (is_object($category) ? ($category->name ?? 'Gia Dụng') : $category) : ($p->category ?? 'Chưa phân loại');

                    $cleaned[$id] = [
                        'name' => $p->name,
                        'price' => (float)$p->price,
                        'quantity' => max(1, (int)($item['quantity'] ?? 1)),
                        'image' => $p->image,
                        'category' => $categoryName,
                    ];
                }
                // Nếu sản phẩm không có trong DB (ví dụ ID ngẫu nhiên, sản phẩm rác), tự động loại bỏ
            } 

            $cart = $cleaned;
            session()->put('cart', $cart); 
            session()->save();
        } 

        $total = 0; 
        foreach ($cart as $item) { 
            $total += $item['price'] * $item['quantity']; 
        } 

        return view('cart.index', compact('cart', 'total')); 
    } 

    /**
     * Thêm sản phẩm vào giỏ hàng (Hỗ trợ cả khách lẫn người dùng đã đăng nhập)
     */
    public function add(Request $request, $id) 
    { 
        $id = (int)$id;
        $product = Product::with('category')->find($id); 
        $isWebgiadung = $request->is('webgiadung*');
        $prefix = $isWebgiadung ? '/webgiadung' : '';

        if (!$product) {
            return redirect()->to($prefix . route('welcome', [], false))->with('error', 'Sản phẩm không tồn tại hoặc đã ngừng kinh doanh.');
        }

        $cart = session()->get('cart', []); 
        $quantity = max(1, (int)$request->input('quantity', 1));

        $category = $product->category;
        $categoryName = $category ? (is_object($category) ? ($category->name ?? 'Gia Dụng') : $category) : ($product->category ?? 'Chưa phân loại'); 

        if (isset($cart[$id])) { 
            $cart[$id]['quantity'] += $quantity; 
            $cart[$id]['name'] = $product->name;
            $cart[$id]['price'] = (float)$product->price;
            $cart[$id]['image'] = $product->image;
            $cart[$id]['category'] = $categoryName; 
        } else { 
            $cart[$id] = [ 
                'name' => $product->name, 
                'price' => (float)$product->price, 
                'quantity' => $quantity, 
                'image' => $product->image, 
                'category' => $categoryName, 
            ]; 
        } 

        session()->put('cart', $cart); 
        session()->save(); 

        // Nếu là thao tác "Mua Ngay", chuyển hướng thẳng tới giỏ hàng để thanh toán
        if ($request->input('buy_now') == '1' || $request->input('action') === 'buy_now') {
            if (!Auth::check()) {
                return redirect()->to($prefix . '/login?redirect=' . urlencode($prefix . '/cart#checkoutOrderForm'))
                                 ->with('error', 'Vui lòng đăng nhập để tiếp tục thanh toán.');
            }
            return redirect()->to($prefix . route('cart.index', [], false) . '#checkoutOrderForm')->with('success', 'Đã thêm sản phẩm vào giỏ hàng. Quý khách vui lòng chọn hình thức thanh toán!');
        }

        return redirect()->to($prefix . route('cart.index', [], false))->with('success', 'Đã thêm sản phẩm vào giỏ hàng thành công!');  
    } 

    /**
     * Mua Hàng Ngay (Mua riêng sản phẩm này - Đưa thẳng tới trang thanh toán, không cộng dồn các sản phẩm cũ)
     */
    public function buyNow(Request $request, $id)
    {
        $id = (int)$id;
        $product = Product::with('category')->find($id);
        $isWebgiadung = $request->is('webgiadung*');
        $prefix = $isWebgiadung ? '/webgiadung' : '';

        if (!$product) {
            return redirect()->to($prefix . route('welcome', [], false))->with('error', 'Sản phẩm không tồn tại hoặc đã ngừng kinh doanh.');
        }

        $quantity = max(1, (int)$request->input('quantity', 1));
        $category = $product->category;
        $categoryName = $category ? (is_object($category) ? ($category->name ?? 'Gia Dụng') : $category) : ($product->category ?? 'Chưa phân loại');

        // Làm mới giỏ hàng để mua RIÊNG duy nhất sản phẩm này
        $cart = [
            $id => [
                'name' => $product->name,
                'price' => (float)$product->price,
                'quantity' => $quantity,
                'image' => $product->image,
                'category' => $categoryName,
            ]
        ];

        session()->put('cart', $cart);
        session()->save();

        if (!Auth::check()) {
            return redirect()->to($prefix . '/login?redirect=' . urlencode($prefix . '/cart#checkoutOrderForm'))
                             ->with('error', 'Vui lòng đăng nhập để tiếp tục mua hàng.');
        }

        return redirect()->to($prefix . route('cart.index', [], false) . '#checkoutOrderForm')->with('success', 'Đang mua riêng sản phẩm "' . $product->name . '". Quý khách vui lòng kiểm tra và điền thông tin nhận hàng bên dưới!');
    } 

    /**
     * API Đồng bộ giỏ hàng từ trang chủ (localStorage) sang PHP Laravel Session
     */
    public function syncCart(Request $request)
    {
        $data = $request->all();
        if (empty($data) && $request->isJson()) {
            $data = $request->json()->all();
        }
        if (empty($data)) {
            $content = $request->getContent();
            $raw = json_decode($content, true, 512, JSON_INVALID_UTF8_IGNORE | JSON_INVALID_UTF8_SUBSTITUTE);
            if (is_array($raw)) {
                $data = $raw;
            } else {
                $clean = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
                $raw2 = json_decode($clean, true);
                if (is_array($raw2)) {
                    $data = $raw2;
                }
            }
        }

        $items = $data['items'] ?? [];
        if (empty($items) && isset($data['id'])) {
            $items = [$data];
        }

        $mode = $data['mode'] ?? 'full';
        $cart = ($mode === 'full') ? [] : session()->get('cart', []);

        if (is_array($items) && count($items) > 0) {
            // Lấy toàn bộ ID sản phẩm hợp lệ
            $reqIds = [];
            foreach ($items as $it) {
                if (is_array($it) && !empty($it['id'])) {
                    $pId = (int)$it['id'];
                    if ($pId > 0) {
                        $reqIds[] = $pId;
                    }
                }
            }

            if (!empty($reqIds)) {
                $dbProducts = Product::with('category')->whereIn('id', $reqIds)->get()->keyBy('id');

                foreach ($items as $item) {
                    if (!is_array($item) || empty($item['id'])) {
                        continue;
                    }

                    $id = (int)$item['id'];
                    if ($id <= 0) {
                        continue;
                    }

                    $qty = max(1, (int)($item['quantity'] ?? 1));

                    // Bắt buộc sản phẩm phải tồn tại trong database MySQL
                    if (isset($dbProducts[$id])) {
                        $p = $dbProducts[$id];
                        $cat = $p->category;
                        $categoryName = $cat ? (is_object($cat) ? ($cat->name ?? 'Gia Dụng') : $cat) : ($p->category ?? 'Gia Dụng');

                        if ($mode === 'append' && isset($cart[$id])) {
                            $cart[$id]['quantity'] += $qty;
                        } else {
                            $cart[$id] = [
                                'name' => $p->name,
                                'price' => (float)$p->price,
                                'quantity' => $qty,
                                'image' => $p->image,
                                'category' => $categoryName,
                            ];
                        }
                    }
                }
            }
        }

        session()->put('cart', $cart);
        session()->save();

        return response()->json([
            'success' => true,
            'cart_count' => count($cart),
            'cart' => $cart,
            'message' => 'Đã đồng bộ giỏ hàng thành công!'
        ]);
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function update(Request $request, $id) 
    { 
        $cart = session()->get('cart', []); 
        if (isset($cart[$id])) { 
            $quantity = max(1, (int)$request->input('quantity', 1)); 
            $cart[$id]['quantity'] = $quantity; 
            session()->put('cart', $cart); 
            session()->save();
        } 

        $isWebgiadung = $request->is('webgiadung*');
        $prefix = $isWebgiadung ? '/webgiadung' : '';
        return redirect()->to($prefix . route('cart.index', [], false))->with('success', 'Cập nhật giỏ hàng thành công!');  
    } 

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function destroy(Request $request, $id) 
    { 
        $cart = session()->get('cart', []); 
        if (isset($cart[$id])) { 
            unset($cart[$id]); 
            session()->put('cart', $cart); 
            if (empty($cart)) {
                session()->flash('cart_cleared', true);
            }
            session()->save();
        } 

        $isWebgiadung = $request->is('webgiadung*');
        $prefix = $isWebgiadung ? '/webgiadung' : '';
        return redirect()->to($prefix . route('cart.index', [], false))->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');  
    } 

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear(Request $request)
    {
        session()->forget('cart');
        session()->flash('cart_cleared', true);
        session()->save();

        $isWebgiadung = $request->is('webgiadung*');
        $prefix = $isWebgiadung ? '/webgiadung' : '';
        return redirect()->to($prefix . route('cart.index', [], false))->with('success', 'Đã xóa toàn bộ giỏ hàng thành công.');
    }
}
