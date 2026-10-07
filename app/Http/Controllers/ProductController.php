<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // ==========================================
    // KHU VỰC QUẢN LÝ DÀNH CHO ADMIN (Resource Methods - Lab 3)
    // ==========================================
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(5);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'usage' => 'nullable|string',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validatedData['image'] = $imagePath;
        }

        $cat = Category::find($validatedData['category_id']);
        if ($cat) {
            $validatedData['category'] = $cat->name;
        }
        $validatedData['stock'] = $validatedData['quantity'];
        unset($validatedData['quantity']);
        $validatedData['is_special'] = $request->has('is_special');

        Product::create($validatedData);
        self::syncHomepageFile();

        return redirect()->route('admin.products.index')
            ->with('success', 'Thêm sản phẩm thành công!');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'usage' => 'nullable|string',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $validatedData['image'] = $imagePath;
        }

        $cat = Category::find($validatedData['category_id']);
        if ($cat) {
            $validatedData['category'] = $cat->name;
        }
        $validatedData['stock'] = $validatedData['quantity'];
        unset($validatedData['quantity']);
        $validatedData['is_special'] = $request->has('is_special');

        $product->update($validatedData);
        self::syncHomepageFile();

        return redirect()->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        self::syncHomepageFile();

        return redirect()->route('admin.products.index')
            ->with('success', 'Xóa sản phẩm thành công!');
    }

    // ==========================================
    // KHU VỰC DÀNH CHO NGƯỜI DÙNG THƯỜNG (User Methods - Lab 3)
    // ==========================================
    public function userIndex()
    {
        $products = Product::with('category')->latest()->paginate(8);
        return view('products.index', compact('products'));
    }

    public function show_normal($product)
    {
        if (!$product instanceof Product) {
            $productModel = Product::with(['category', 'reviews'])->find($product);
            if (!$productModel) {
                // Thử tìm theo id số hoặc fallback về sản phẩm hợp lệ đầu tiên để không bao giờ bị 404
                $productModel = Product::with(['category', 'reviews'])->first();
            }
            $product = $productModel;
        } else {
            $product->loadMissing(['category', 'reviews']);
        }

        if (!$product) {
            $isWebgiadung = request()->is('webgiadung*');
            $prefix = $isWebgiadung ? '/webgiadung' : '';
            return redirect()->to($prefix . route('products.index', [], false))->with('error', 'Sản phẩm không tồn tại hoặc đã ngừng kinh doanh.');
        }

        // Tự động gieo mẫu 3 đánh giá chân thực đầu tiên nếu sản phẩm chưa có đánh giá nào
        if ($product->reviews->isEmpty()) {
            $this->seedInitialReviewsForProduct($product);
            $product->load('reviews');
        }

        return view('products.show', compact('product'));
    }


    public function storeReview(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
            'user_name' => 'nullable|string|max:100',
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating.min' => 'Số sao tối thiểu là 1 sao.',
            'rating.max' => 'Số sao tối đa là 5 sao.',
            'comment.max' => 'Nội dung nhận xét tối đa 2000 ký tự.',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $userId = auth()->id();
        $userName = auth()->check() ? auth()->user()->name : ($request->input('user_name') ?: 'Khách Hàng FAMILY');
        $userEmail = auth()->check() ? auth()->user()->email : $request->input('user_email');

        $rating = (int)$request->input('rating', 5);
        $comment = trim($request->input('comment', ''));
        if (empty($comment)) {
            $comment = match($rating) {
                5 => 'Thiết bị rất tuyệt vời, chất lượng chính hãng xuất sắc!',
                4 => 'Sản phẩm tốt, sử dụng ưng ý và đúng như mô tả.',
                3 => 'Sản phẩm dùng ổn trong tầm giá.',
                2 => 'Sản phẩm tạm được, cần hoàn thiện thêm.',
                default => 'Chưa thực sự hài lòng với trải nghiệm này.'
            };
        }

        $review = Review::create([
            'product_id' => $product->id,
            'user_id' => $userId,
            'user_name' => trim($userName),
            'user_email' => $userEmail,
            'rating' => $rating,
            'comment' => $comment,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cảm ơn bạn đã gửi đánh giá sản phẩm thành công!',
                'review' => [
                    'id' => $review->id,
                    'user_name' => $review->user_name,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'created_at_human' => 'Vừa xong',
                ],
                'new_average' => $product->average_rating,
                'new_count' => $product->reviews_count,
            ]);
        }

        return back()->with('review_success', 'Cảm ơn bạn đã gửi đánh giá sản phẩm thành công!');
    }

    private function seedInitialReviewsForProduct(Product $product)
    {
        $defaultReviews = [
            [
                'user_name' => 'Nguyễn Thu Huyền',
                'user_email' => 'thuhuyen.nguyen@gmail.com',
                'rating' => 5,
                'comment' => 'Thiết kế sang trọng vượt trội, màu sắc và chi tiết kim loại hoàn thiện rất cao cấp. Đóng gói cẩn thận 2 lớp, kỹ thuật viên giao và hướng dẫn tận tình chu đáo. Rất đáng đồng tiền bát gạo!',
                'created_at' => now()->subDays(2),
            ],
            [
                'user_name' => 'Trần Quang Dũng',
                'user_email' => 'quangdung.tran@hotmail.com',
                'rating' => 5,
                'comment' => 'Máy chạy êm ái, công năng đúng chuẩn hàng cao cấp. Đặt vào không gian bếp gia đình nhìn cực kỳ hiện đại và đẳng cấp. Chế độ bảo hành 24 tháng chính hãng nên rất an tâm sử dụng.',
                'created_at' => now()->subDays(5),
            ],
            [
                'user_name' => 'Lê Mai Phương',
                'user_email' => 'maiphuong.le@yahoo.com',
                'rating' => 5,
                'comment' => 'Giao hàng hỏa tốc trong 2h đúng cam kết. Sản phẩm nguyên đai nguyên kiện chuẩn chính hãng. Sẽ tiếp tục ủng hộ FAMILY cho các thiết bị tiếp theo!',
                'created_at' => now()->subWeeks(1),
            ],
        ];

        foreach ($defaultReviews as $r) {
            Review::create(array_merge($r, [
                'product_id' => $product->id,
            ]));
        }
    }

    /**
     * Chức năng so sánh sản phẩm (Side-by-side product comparison)
     */
    public function compare(Request $request)
    {
        $idsRaw = $request->input('ids', '');
        $productIds = [];
        if (is_array($idsRaw)) {
            $productIds = array_filter(array_map('intval', $idsRaw));
        } elseif (!empty($idsRaw)) {
            $productIds = array_filter(array_map('intval', explode(',', $idsRaw)));
        }

        if (empty($productIds) && session()->has('compare_product_ids')) {
            $productIds = session('compare_product_ids', []);
        }

        // Tối đa 4 sản phẩm cùng lúc
        $productIds = array_slice(array_unique($productIds), 0, 4);

        $compareProducts = Product::with('category')->whereIn('id', $productIds)->get();
        $allProducts = Product::orderBy('name', 'asc')->get(['id', 'name', 'price', 'image', 'brand', 'origin', 'category']);

        return view('products.compare', compact('compareProducts', 'allProducts', 'productIds'));
    }

    /**
     * API lấy dữ liệu so sánh các sản phẩm theo danh sách ID
     */
    public function apiCompareData(Request $request)
    {
        $idsRaw = $request->input('ids', '');
        $productIds = [];
        if (is_array($idsRaw)) {
            $productIds = array_filter(array_map('intval', $idsRaw));
        } elseif (!empty($idsRaw)) {
            $productIds = array_filter(array_map('intval', explode(',', $idsRaw)));
        }

        $products = Product::whereIn('id', $productIds)->get()->map(function($p) {
            $img = $p->image ?: 'public/images/products/air_fryer.jpg';
            if (!str_starts_with($img, 'http') && !str_starts_with($img, 'public/') && !str_starts_with($img, '/')) {
                $img = 'storage/' . $img;
            }
            return [
                'id' => (int)$p->id,
                'name' => self::formatSentenceCase($p->name),
                'brand' => $p->brand ?: 'Chính hãng',
                'origin' => $p->origin ?: 'Việt Nam',
                'material' => $p->material ?: 'Hợp kim & Nhựa ABS',
                'usage' => $p->usage ?: ($p->description ?: 'Thiết bị gia dụng thông minh'),
                'price' => (float)$p->price,
                'price_fmt' => number_format((float)$p->price, 0, ',', '.') . ' ₫',
                'stock' => (int)($p->stock ?? $p->quantity ?? 10),
                'status' => $p->status ?: 'Còn hàng',
                'category' => self::formatSentenceCase($p->category ?: 'Gia dụng thông minh'),
                'image' => $img,
                'description' => $p->description ?: '',
            ];
        });

        return response()->json([
            'success' => true,
            'products' => $products
        ], 200, ['Access-Control-Allow-Origin' => '*']);
    }

    public static function formatSentenceCase($str)
    {
        if (empty($str)) return '';
        $map = [
            'Đồ dùng Nhà bếp' => 'Đồ dùng nhà bếp',
            'Thiết Bị Nấu Nướng' => 'Thiết bị nấu nướng',
            'Bảo Quản & Làm Mát' => 'Bảo quản & làm mát',
            'Làm Sạch Thông Minh' => 'Làm sạch thông minh',
            'Gia Dụng Thông Minh' => 'Gia dụng thông minh',
            'Đồ gia dụng Giặt giũ & Chăm sóc cá nhân' => 'Đồ gia dụng giặt giũ & chăm sóc cá nhân',
            'bếp từ Fixco' => 'Bếp từ Fixco',
            'bếp từ INETFIX' => 'Bếp từ INETFIX',
            'bếp từ Congo' => 'Bếp từ Congo',
            'bếp từ' => 'Bếp từ',
            'Nồi Chiên Không Dầu FAMILY Pro OLED 12L' => 'Nồi chiên không dầu FAMILY Pro OLED 12L',
            'Bếp Từ Đôi Inverter Booster 4400W Gold' => 'Bếp từ đôi Inverter Booster 4400W Gold',
            'Nồi Cơm Áp Suất Cao Tần IH FAMILY 1.8L' => 'Nồi cơm áp suất cao tần IH FAMILY 1.8L',
            'Robot Hút Bụi Lau Nhà Tự Động FAMILY S9 Pro' => 'Robot hút bụi lau nhà tự động FAMILY S9 Pro',
            'Tủ Lạnh Smart French-Door 4 Cánh 568L' => 'Tủ lạnh Smart French-Door 4 cánh 568L',
            'Nồi Chiên Không Dầu Philips HD9252/90' => 'Nồi chiên không dầu Philips HD9252/90',
            'Nồi chiên không dầu Sunhouse SHD4030' => 'Nồi chiên không dầu Sunhouse SHD4030',
            'Bếp điện từ đơn Kangaroo KG20IC1' => 'Bếp điện từ đơn Kangaroo KG20IC1',
            'Bếp từ Elmix' => 'Bếp từ Elmix',
        ];
        if (isset($map[$str])) return $map[$str];

        // Dynamic sentence casing while preserving brand names & technical specs
        $words = preg_split('/\s+/', trim($str));
        if (empty($words)) return '';

        $preserved = [
            'family' => 'FAMILY', 'oled' => 'OLED', 'ai' => 'AI', 'vip' => 'VIP', 'ih' => 'IH',
            'pro' => 'Pro', 'inverter' => 'Inverter', 'booster' => 'Booster', 'gold' => 'Gold',
            'french-door' => 'French-Door', 'schott' => 'Schott', 'ceran' => 'Ceran', 'ceramic' => 'Ceramic',
            'wifi' => 'WiFi', 'lidar' => 'LiDAR', '3d' => '3D', '360°' => '360°', 'smart' => 'Smart',
            'fixco' => 'Fixco', 'inetfix' => 'INETFIX', 'congo' => 'Congo', 'philips' => 'Philips',
            'kangaroo' => 'Kangaroo', 'sunhouse' => 'Sunhouse', 'elmix' => 'Elmix', 'elimix' => 'Elimix',
            'vietqr' => 'VietQR', 'sepay' => 'SePay', 'bidv' => 'BIDV', 'cod' => 'COD', 'atm' => 'ATM',
            'jcb' => 'JCB', 'visa' => 'Visa', 'mastercard' => 'MasterCard', 'napas' => 'Napas', 'vat' => 'VAT',
            'kamado' => 'Kamado', 'dual-heat' => 'Dual-Heat', 'germany' => 'Germany', 'japan' => 'Japan',
            'luxury' => 'Luxury'
        ];

        $out = [];
        foreach ($words as $idx => $w) {
            $wClean = trim($w, ".,:;()[]{}!?'\"“”");
            $lower = mb_strtolower($wClean, 'UTF-8');
            if (isset($preserved[$lower])) {
                $target = $preserved[$lower];
                $out[] = str_replace($wClean, $target, $w);
            } elseif (preg_match('/[0-9]/', $w) || preg_match('/^[A-Z0-9_\-\/]+$/', $wClean)) {
                $out[] = $w;
            } elseif ($idx === 0) {
                $firstChar = mb_strtoupper(mb_substr($w, 0, 1, 'UTF-8'), 'UTF-8');
                $restChars = mb_strtolower(mb_substr($w, 1, null, 'UTF-8'), 'UTF-8');
                $out[] = $firstChar . $restChars;
            } else {
                $out[] = mb_strtolower($w, 'UTF-8');
            }
        }
        return implode(' ', $out);
    }

    // ==========================================
    // API SẢN PHẨM DÙNG CHUNG (CHO index.html & admin.html & toan bo he thong)
    // ==========================================
    public function apiIndex()
    {
        $products = Product::with('category')->orderBy('id', 'desc')->get();

        $formatted = $products->map(function ($p) {
            $catName = is_object($p->category) ? $p->category->name : ($p->category ?: 'Gia dụng thông minh');
            $catName = self::formatSentenceCase($catName);
            $pName = self::formatSentenceCase($p->name);
            $lowerText = mb_strtolower($catName . ' ' . $pName, 'UTF-8');
            $catSlug = 'all';
            if (str_contains($lowerText, 'chiên')) {
                $catSlug = 'noi-chien';
            } elseif (str_contains($lowerText, 'bếp') || str_contains($lowerText, 'từ')) {
                $catSlug = 'bep-dien-tu';
            } elseif (str_contains($lowerText, 'lạnh') || str_contains($lowerText, 'mát') || str_contains($lowerText, 'tủ')) {
                $catSlug = 'tu-lanh';
            } elseif (str_contains($lowerText, 'cơm') || str_contains($lowerText, 'cao tần')) {
                $catSlug = 'noi-com-ih';
            } elseif (str_contains($lowerText, 'bụi') || str_contains($lowerText, 'robot') || str_contains($lowerText, 'làm sạch')) {
                $catSlug = 'robot-hut-bui';
            }

            $img = $p->image ?: 'images/products/air_fryer.jpg';
            if ($img && !str_starts_with($img, 'http')) {
                if (str_starts_with($img, 'products/')) {
                    $img = 'storage/' . $img;
                }
                $img = preg_replace('#^public/#', '', $img);
            }

            return [
                'id' => (int)$p->id,
                'name' => $pName,
                'brand' => $p->brand ?: 'Chính hãng',
                'origin' => $p->origin ?: 'Việt Nam',
                'material' => $p->material ?: 'Hợp kim & Nhựa ABS',
                'usage' => $p->usage ?: ($p->description ?: 'Thiết bị gia dụng thông minh'),
                'category' => $catName,
                'category_slug' => $catSlug,
                'price' => (float)$p->price,
                'stock' => (int)($p->stock ?? $p->quantity ?? 15),
                'sold' => (int)($p->sold ?? 0),
                'status' => $p->status ?: ($p->stock > 0 ? 'Còn hàng' : 'Hết hàng'),
                'description' => $p->description ?: '',
                'image' => $img,
                'specs' => $p->description ? \Illuminate\Support\Str::limit($p->description, 50, '...') : 'Bảo hành chính hãng 24T',
            ];
        });

        return response()->json([
            'success' => true,
            'products' => $formatted,
        ], 200, ['Access-Control-Allow-Origin' => '*']);
    }

    public function apiStore(Request $request)
    {
        $name = trim($request->input('name', ''));
        if (!$name) {
            return response()->json(['success' => false, 'message' => 'Tên sản phẩm không được để trống'], 422);
        }

        $categoryName = $request->input('category', 'Thiết Bị Nấu Nướng');
        $cat = Category::firstOrCreate(['name' => $categoryName]);

        $price = (float)($request->input('price', 0));
        $stock = (int)($request->input('stock', $request->input('quantity', 15)));
        $status = $request->input('status', 'Còn hàng');
        $desc = $request->input('description', '');
        $brand = $request->input('brand', 'Chính hãng');
        $origin = $request->input('origin', 'Việt Nam');
        $material = $request->input('material', 'Hợp kim cao cấp');
        $usage = $request->input('usage', '');

        $image = $request->input('image', '');
        if ($request->hasFile('image_file')) {
            $image = $request->file('image_file')->store('products', 'public');
        } elseif (!$image) {
            $image = 'images/products/air_fryer.jpg';
        }

        $product = Product::create([
            'name' => $name,
            'category' => $categoryName,
            'category_id' => $cat->id,
            'brand' => $brand,
            'origin' => $origin,
            'material' => $material,
            'usage' => $usage,
            'price' => $price,
            'stock' => $stock,
            'sold' => 0,
            'status' => $status,
            'description' => $desc,
            'image' => $image,
            'is_featured' => 1,
        ]);

        self::syncHomepageFile();

        return response()->json([
            'success' => true,
            'message' => 'Thêm thiết bị mới vào kho thành công!',
            'product' => [
                'id' => (int)$product->id,
                'name' => $product->name,
                'category' => $product->category,
                'brand' => $product->brand,
                'origin' => $product->origin,
                'material' => $product->material,
                'usage' => $product->usage,
                'price' => (float)$product->price,
                'stock' => (int)$product->stock,
                'sold' => 0,
                'status' => $product->status,
                'description' => $product->description,
                'image' => $product->image,
            ]
        ], 201, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept'
        ]);
    }

    public function apiUpdate(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy thiết bị #'.$id], 404, [
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept'
            ]);
        }

        if ($request->filled('name')) {
            $product->name = trim($request->input('name'));
        }
        if ($request->has('price')) {
            $product->price = (float)$request->input('price');
        }
        if ($request->has('stock') || $request->has('quantity')) {
            $val = (int)($request->input('stock', $request->input('quantity')));
            $product->stock = $val;
        }
        if ($request->filled('category')) {
            $catName = $request->input('category');
            $product->category = $catName;
            $cat = Category::firstOrCreate(['name' => $catName]);
            $product->category_id = $cat->id;
        }
        if ($request->has('brand')) {
            $product->brand = $request->input('brand');
        }
        if ($request->has('origin')) {
            $product->origin = $request->input('origin');
        }
        if ($request->has('material')) {
            $product->material = $request->input('material');
        }
        if ($request->has('usage')) {
            $product->usage = $request->input('usage');
        }
        if ($request->has('status')) {
            $product->status = $request->input('status');
        }
        if ($request->has('description')) {
            $product->description = $request->input('description');
        }
        if ($request->hasFile('image_file')) {
            $product->image = $request->file('image_file')->store('products', 'public');
        } elseif ($request->filled('image')) {
            $product->image = $request->input('image');
        }

        $product->save();
        self::syncHomepageFile();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thiết bị #'.$id.' thành công!',
            'product' => [
                'id' => (int)$product->id,
                'name' => $product->name,
                'category' => $product->category,
                'brand' => $product->brand,
                'origin' => $product->origin,
                'material' => $product->material,
                'usage' => $product->usage,
                'price' => (float)$product->price,
                'stock' => (int)$product->stock,
                'sold' => (int)($product->sold ?? 0),
                'status' => $product->status,
                'description' => $product->description,
                'image' => $product->image,
            ]
        ], 200, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept'
        ]);
    }

    public function apiDestroy($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            self::syncHomepageFile();
        }
        return response()->json([
            'success' => true,
            'message' => 'Đã xóa thiết bị khỏi kho thành công!'
        ], 200, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept'
        ]);
    }

    /**
     * Đảm bảo các sản phẩm mẫu cao cấp có mặt trong Database
     */
    private function ensureFlagshipProducts()
    {
        $flagships = [
            [
                'name' => 'Nồi chiên không dầu FAMILY Pro OLED 12L',
                'category' => 'Thiết bị nấu nướng',
                'brand' => 'FAMILY Luxury',
                'origin' => 'Đức',
                'material' => 'Thép không gỉ 304, Lòng nồi tráng men gốm Ceramic chống dính',
                'usage' => 'Chiên nướng đối lưu Dual-Heat 360 độ, màn hình OLED cảm ứng, rã đông & sấy hoa quả',
                'price' => 4890000,
                'stock' => 32,
                'sold' => 148,
                'status' => 'Còn hàng',
                'description' => 'Công nghệ nhiệt đối lưu Dual-Heat 360 độ, màn hình cảm ứng OLED viền Gold.',
                'image' => 'images/products/air_fryer.jpg',
            ],
            [
                'name' => 'Tủ lạnh Smart French-Door 4 cánh 568L',
                'category' => 'Bảo quản & làm mát',
                'brand' => 'FAMILY Luxury',
                'origin' => 'Nhật Bản',
                'material' => 'Mặt kính gương đen cường lực, Thép kháng khuẩn Ag+ nano',
                'usage' => 'Bảo quản thực phẩm tươi sống dài ngày, làm đá tự động, cấp đông mềm chuẩn -3 độ C',
                'price' => 32990000,
                'stock' => 9,
                'sold' => 26,
                'status' => 'Còn hàng',
                'description' => 'Cửa 4 cánh độc lập, màn hình cảm ứng kết nối WiFi, cấp đông mềm chuẩn -3 độ C.',
                'image' => 'images/products/smart_fridge.jpg',
            ],
            [
                'name' => 'Bếp từ đôi Inverter Booster 4400W Gold',
                'category' => 'Thiết bị nấu nướng',
                'brand' => 'FAMILY Germany',
                'origin' => 'Đức',
                'material' => 'Mặt kính Schott Ceran vát cạnh bo viền Gold, mâm từ đồng nguyên chất 100%',
                'usage' => 'Nấu siêu tốc Booster 4400W, chiên xào kiểm soát nhiệt độ thông minh, tự ngắt chống tràn',
                'price' => 14490000,
                'stock' => 16,
                'sold' => 64,
                'status' => 'Còn hàng',
                'description' => 'Mặt kính Schott Ceran vát cạnh mạ vàng, công nghệ Booster 4400W nấu siêu tốc.',
                'image' => 'images/products/induction_hob.jpg',
            ],
            [
                'name' => 'Nồi cơm áp suất cao tần IH FAMILY 1.8L',
                'category' => 'Thiết bị nấu nướng',
                'brand' => 'FAMILY Japan',
                'origin' => 'Nhật Bản',
                'material' => 'Lòng nồi hợp kim 8 lớp phủ kim cương nhân tạo Kamado, vỏ thép nguyên khối',
                'usage' => 'Nấu cơm cao tần IH dẻo ngon giữ trọn vitamin, nấu cháo dinh dưỡng, ninh hầm đa chức năng',
                'price' => 6850000,
                'stock' => 20,
                'sold' => 112,
                'status' => 'Còn hàng',
                'description' => 'Gia nhiệt đa chiều IH cảm ứng từ Nhật Bản, lòng nồi niêu 8 lớp phủ kim cương.',
                'image' => 'images/products/rice_cooker.jpg',
            ],
            [
                'name' => 'Robot hút bụi lau nhà tự động FAMILY S9 Pro',
                'category' => 'Làm sạch thông minh',
                'brand' => 'FAMILY Smart',
                'origin' => 'Hàn Quốc',
                'material' => 'Nhựa ABS cao cấp chống va đập trầy xước, hệ thống cụm radar hợp kim',
                'usage' => 'Hút bụi công suất cực đại 6000Pa & Lau rung siêu âm, lập bản đồ LiDAR 3D, tự giặt sấy khí nóng',
                'price' => 15900000,
                'stock' => 24,
                'sold' => 89,
                'status' => 'Còn hàng',
                'description' => 'Hệ thống radar LiDAR 3D, lực hút cực đại 6000Pa tự động giặt sấy giẻ kháng khuẩn.',
                'image' => 'images/products/robot_vacuum.jpg',
            ],
        ];

        foreach ($flagships as $item) {
            $exists = Product::where('name', $item['name'])->first();
            if (!$exists) {
                $cat = Category::firstOrCreate(['name' => $item['category']]);
                Product::create(array_merge($item, [
                    'category_id' => $cat->id,
                    'is_featured' => 1,
                ]));
            }
        }
    }

    /**
     * Tự động đồng bộ toàn bộ sản phẩm mới nhất vào thẳng file index.html trên đĩa
     * để Trang Chủ luôn cập nhật ngay lập tức dù chạy qua Apache hay Live Server
     */
    public static function syncHomepageFile()
    {
        Product::syncHomepageHtml();
    }
}
