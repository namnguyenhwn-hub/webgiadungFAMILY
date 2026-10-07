<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SepayPaymentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ReviewController;

/*
|--------------------------------------------------------------------------
| Web Routes - LAB 3 (Xác Thực & Phân Quyền) + LAB 05B (Giỏ Hàng & VietQR)
|--------------------------------------------------------------------------
*/

// 1. Trang chủ (Đồng bộ giao diện FAMILY Luxury mới cho cả php artisan serve & XAMPP)
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/home', function () {
    return redirect()->route('welcome');
})->name('home');

Route::get('/index.html', function () {
    return redirect()->route('welcome');
});
Route::get('/webgiadung/index.html', function () {
    return redirect('/webgiadung');
});

Route::get('/admin.html', function () {
    if (file_exists(base_path('admin.html'))) {
        $html = file_get_contents(base_path('admin.html'));
        if (!request()->is('webgiadung*')) {
            $html = str_replace('href="public/css/', 'href="/css/', $html);
            $html = str_replace('src="public/images/', 'src="/images/', $html);
            $html = str_replace('src="public/js/', 'src="/js/', $html);
        }
        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }
    return redirect()->route('admin.dashboard');
});

// 2. Route xác thực dành cho KHÁCH CHƯA ĐĂNG NHẬP (guest - Lab 3)
Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register.post');
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
});

// 3. Đăng xuất (Chỉ dành cho người ĐÃ ĐĂNG NHẬP - Lab 3)
Route::post('logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// 4. Các Route xử lý Xác thực Email (Email Verification)
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('welcome')->with('success', 'Cảm ơn bạn đã xác thực email thành công!');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// 5. Route dành cho ADMIN (Yêu cầu đăng nhập + quyền admin - Lab 3)
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Quản lý sản phẩm & danh mục trong Admin (Resource Controllers - Lab 3)
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);

    // Quản lý người dùng & Đơn hàng trong Admin Panel
    Route::post('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::post('/orders/{id}', [AdminController::class, 'updateOrder'])->name('orders.update');
    Route::delete('/orders/{id}', [AdminController::class, 'deleteOrder'])->name('orders.delete');
    Route::post('/banner/upload', [AdminController::class, 'uploadBanner'])->name('banner.upload');
    Route::post('/logo/upload', [AdminController::class, 'uploadLogo'])->name('logo.upload');
    Route::post('/settings/promo', [AdminController::class, 'updatePromo'])->name('settings.promo');
});

Route::post('/webgiadung/api/admin/banner/upload', [AdminController::class, 'uploadBanner']);
Route::post('/webgiadung/api/admin/logo/upload', [AdminController::class, 'uploadLogo']);

// Alias routes cho Admin (hỗ trợ cả XAMPP subfolder /webgiadung và index.php)
Route::middleware(['admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard']);
    Route::get('/webgiadung/admin', [AdminController::class, 'dashboard']);
    Route::get('/webgiadung/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/index.php/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/index.php/admin', [AdminController::class, 'dashboard']);
    Route::get('/webgiadung/index.php/admin/dashboard', [AdminController::class, 'dashboard']);
});

// Auth aliases for XAMPP subfolder
Route::middleware('guest')->group(function () {
    Route::get('/webgiadung/login', [AuthController::class, 'showLoginForm']);
    Route::post('/webgiadung/login', [AuthController::class, 'login']);
    Route::get('/webgiadung/register', [AuthController::class, 'showRegistrationForm']);
    Route::post('/webgiadung/register', [AuthController::class, 'register']);
});
Route::post('/webgiadung/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/webgiadung/admin.html', function () {
    return redirect('/admin.html');
});

// Chức năng So Sánh Sản Phẩm (Side-by-side Product Comparison - Tự do truy cập cho cả khách & thành viên)
Route::get('/so-sanh', [ProductController::class, 'compare'])->name('products.compare');
Route::get('/compare', [ProductController::class, 'compare']);
Route::get('/products/compare', [ProductController::class, 'compare']);
Route::get('/api/products/compare-data', [ProductController::class, 'apiCompareData'])->name('api.products.compareData');
Route::get('/webgiadung/so-sanh', [ProductController::class, 'compare']);
Route::get('/webgiadung/api/products/compare-data', [ProductController::class, 'apiCompareData']);

// 6. Route dành cho NGƯỜI DÙNG BÌNH THƯỜNG (Lab 3)
Route::get('/products', [ProductController::class, 'userIndex'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show_normal'])->name('products.show');
Route::post('/products/{product}/reviews', [ProductController::class, 'storeReview'])->name('products.reviews.store');
Route::get('/webgiadung/products', [ProductController::class, 'userIndex']);
Route::get('/webgiadung/products/{product}', [ProductController::class, 'show_normal']);
Route::post('/webgiadung/products/{product}/reviews', [ProductController::class, 'storeReview']);

Route::middleware(['auth'])->group(function () {
    // Khu vực xem Danh mục của User thường
    Route::get('/categories', [CategoryController::class, 'userIndex'])->name('categories.index');
    Route::get('/categories/{category}', [CategoryController::class, 'showNormal'])->name('categories.show');
});

// 7. Quản lý Giỏ Hàng & Mua Hàng (Lab 05B)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{id}', [CartController::class, 'add'])->name('add');
    Route::post('/buy-now/{id}', [CartController::class, 'buyNow'])->name('buyNow');
    Route::patch('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'destroy'])->name('destroy');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
});

// Alias routes cho giỏ hàng khi chạy trên XAMPP subfolder /webgiadung
Route::prefix('webgiadung/cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/add/{id}', [CartController::class, 'add']);
    Route::post('/buy-now/{id}', [CartController::class, 'buyNow']);
    Route::patch('/update/{id}', [CartController::class, 'update']);
    Route::delete('/remove/{id}', [CartController::class, 'destroy']);
    Route::post('/clear', [CartController::class, 'clear']);
});

// API Đồng Bộ Giỏ Hàng từ Frontend Tĩnh (index.html / localStorage)
Route::match(['get', 'post'], '/api/cart/sync', [CartController::class, 'syncCart'])->name('cart.sync');
Route::match(['get', 'post'], '/api/cart/add', [CartController::class, 'syncCart'])->name('cart.apiAdd');
Route::match(['get', 'post'], '/webgiadung/api/cart/sync', [CartController::class, 'syncCart']);
Route::match(['get', 'post'], '/webgiadung/api/cart/add', [CartController::class, 'syncCart']);

// API Quản Lý & Đồng Bộ Thiết Bị Gia Dụng Toàn Hệ Thống (index.html & admin.html)
Route::options('/api/products', function() {
    return response('', 200, [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept'
    ]);
});
Route::options('/api/products/{id}', function() {
    return response('', 200, [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept'
    ]);
});
Route::options('/webgiadung/api/products', function() {
    return response('', 200, [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept'
    ]);
});
Route::options('/webgiadung/api/products/{id}', function() {
    return response('', 200, [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept'
    ]);
});

Route::get('/api/products', [ProductController::class, 'apiIndex'])->name('api.products.index');
Route::post('/api/products', [ProductController::class, 'apiStore'])->name('api.products.store');
Route::match(['post', 'put', 'patch'], '/api/products/{id}', [ProductController::class, 'apiUpdate'])->name('api.products.update');
Route::delete('/api/products/{id}', [ProductController::class, 'apiDestroy'])->name('api.products.destroy');

Route::get('/webgiadung/api/products', [ProductController::class, 'apiIndex']);
Route::post('/webgiadung/api/products', [ProductController::class, 'apiStore']);
Route::match(['post', 'put', 'patch'], '/webgiadung/api/products/{id}', [ProductController::class, 'apiUpdate']);
Route::delete('/webgiadung/api/products/{id}', [ProductController::class, 'apiDestroy']);

// API Đánh Giá Khách Hàng (Customer Reviews & Testimonials API)
Route::options('/api/reviews', function() {
    return response('', 200, [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept'
    ]);
});
Route::options('/webgiadung/api/reviews', function() {
    return response('', 200, [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept'
    ]);
});
Route::get('/api/reviews', [ReviewController::class, 'apiIndex'])->name('api.reviews.index');
Route::post('/api/reviews', [ReviewController::class, 'apiStore'])->name('api.reviews.store');
Route::match(['post', 'put', 'patch'], '/api/reviews/{id}', [ReviewController::class, 'apiUpdate'])->name('api.reviews.update');
Route::delete('/api/reviews/{id}', [ReviewController::class, 'apiDestroy'])->name('api.reviews.destroy');

Route::get('/webgiadung/api/reviews', [ReviewController::class, 'apiIndex']);
Route::post('/webgiadung/api/reviews', [ReviewController::class, 'apiStore']);
Route::match(['post', 'put', 'patch'], '/webgiadung/api/reviews/{id}', [ReviewController::class, 'apiUpdate']);
Route::delete('/webgiadung/api/reviews/{id}', [ReviewController::class, 'apiDestroy']);

// API Đồng Bộ Đăng Nhập / Đăng Ký / Đăng Xuất từ Modal trang chủ index.html sang Laravel Session
Route::get('/api/auth/me', [AuthController::class, 'me'])->name('auth.me');
Route::get('/webgiadung/api/auth/me', [AuthController::class, 'me']);
Route::match(['get', 'post'], '/api/auth/sync-login', [AuthController::class, 'syncLogin'])->name('auth.syncLogin');
Route::match(['get', 'post'], '/api/auth/sync-register', [AuthController::class, 'syncRegister'])->name('auth.syncRegister');
Route::match(['get', 'post'], '/api/auth/sync-logout', [AuthController::class, 'syncLogout'])->name('auth.syncLogout');
Route::match(['get', 'post'], '/webgiadung/api/auth/sync-login', [AuthController::class, 'syncLogin']);
Route::match(['get', 'post'], '/webgiadung/api/auth/sync-register', [AuthController::class, 'syncRegister']);
Route::match(['get', 'post'], '/webgiadung/api/auth/sync-logout', [AuthController::class, 'syncLogout']);

// 8. Khu Vực Đặt Hàng & Thanh Toán (Lab 05B - Mua hàng trực tiếp, không ép buộc đăng nhập lại)
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::get('/checkout/form', [CheckoutController::class, 'index'])->name('checkout.form');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/bank', [CheckoutController::class, 'showBankQr'])->name('checkout.bank');
Route::get('/checkout/bidv', [CheckoutController::class, 'showBankQr'])->name('checkout.bidv');
Route::get('/checkout/chuyen-khoan-vpbank', [CheckoutController::class, 'showBankQr'])->name('checkout.chuyển khoản ngân hàng VPBank');

Route::get('/webgiadung/checkout', [CheckoutController::class, 'index']);
Route::get('/webgiadung/checkout/form', [CheckoutController::class, 'index']);
Route::post('/webgiadung/checkout/process', [CheckoutController::class, 'process']);
Route::get('/webgiadung/checkout/bank', [CheckoutController::class, 'showBankQr']);
Route::get('/webgiadung/checkout/bidv', [CheckoutController::class, 'showBankQr']);
Route::get('/webgiadung/checkout/chuyen-khoan-vpbank', [CheckoutController::class, 'showBankQr']);

// Alias điều hướng tiện lợi tránh 404 cho mọi liên kết mua hàng
Route::get('/mua-hang', function() { return redirect(route('cart.index') . '#checkoutOrderForm'); });
Route::get('/webgiadung/mua-hang', function() { return redirect('/webgiadung/cart#checkoutOrderForm'); });
Route::get('/dat-hang', function() { return redirect(route('cart.index') . '#checkoutOrderForm'); });
Route::get('/webgiadung/dat-hang', function() { return redirect('/webgiadung/cart#checkoutOrderForm'); });

// Lịch sử Đơn Hàng (Order Management - Yêu cầu đăng nhập khi xem danh sách đơn hàng)
Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/webgiadung/orders', [OrderController::class, 'index']);
    Route::post('/webgiadung/orders', [OrderController::class, 'store']);
});

// 9. Cổng Thanh Toán Ngân Hàng Tự Động SePay (sepay.vn)
Route::get('/checkout/sepay/{order_id}', [SepayPaymentController::class, 'showPaymentPage'])->name('sepay.payment');
Route::get('/webgiadung/checkout/sepay/{order_id}', [SepayPaymentController::class, 'showPaymentPage']);
Route::get('/webgiadung/public/checkout/sepay/{order_id}', [SepayPaymentController::class, 'showPaymentPage']);
Route::get('/public/checkout/sepay/{order_id}', [SepayPaymentController::class, 'showPaymentPage']);

Route::get('/api/sepay/check-status/{order_id}', [SepayPaymentController::class, 'checkStatus'])->name('sepay.checkStatus');
Route::get('/webgiadung/api/sepay/check-status/{order_id}', [SepayPaymentController::class, 'checkStatus']);
Route::get('/webgiadung/public/api/sepay/check-status/{order_id}', [SepayPaymentController::class, 'checkStatus']);
Route::get('/public/api/sepay/check-status/{order_id}', [SepayPaymentController::class, 'checkStatus']);

Route::post('/api/sepay/simulate/{order_id}', [SepayPaymentController::class, 'simulatePayment'])->name('sepay.simulate');
Route::post('/webgiadung/api/sepay/simulate/{order_id}', [SepayPaymentController::class, 'simulatePayment']);
Route::post('/webgiadung/public/api/sepay/simulate/{order_id}', [SepayPaymentController::class, 'simulatePayment']);
Route::post('/public/api/sepay/simulate/{order_id}', [SepayPaymentController::class, 'simulatePayment']);

Route::post('/api/sepay/save-key', [SepayPaymentController::class, 'saveApiKey'])->name('sepay.saveKey');
Route::post('/webgiadung/api/sepay/save-key', [SepayPaymentController::class, 'saveApiKey']);
Route::post('/webgiadung/public/api/sepay/save-key', [SepayPaymentController::class, 'saveApiKey']);
Route::post('/public/api/sepay/save-key', [SepayPaymentController::class, 'saveApiKey']);

// Webhook từ bên thứ 3 SePay (Dành cho SePay gọi vào khi có tiền về)
Route::match(['get', 'post'], '/api/sepay/webhook', [SepayPaymentController::class, 'webhook'])->name('sepay.webhook');
Route::match(['get', 'post'], '/sepay/webhook', [SepayPaymentController::class, 'webhook'])->name('sepay.webhook.alias');
Route::match(['get', 'post'], '/webgiadung/api/sepay/webhook', [SepayPaymentController::class, 'webhook']);
Route::match(['get', 'post'], '/webgiadung/sepay/webhook', [SepayPaymentController::class, 'webhook']);
Route::match(['get', 'post'], '/webgiadung/public/api/sepay/webhook', [SepayPaymentController::class, 'webhook']);
Route::match(['get', 'post'], '/webgiadung/public/sepay/webhook', [SepayPaymentController::class, 'webhook']);
Route::match(['get', 'post'], '/public/api/sepay/webhook', [SepayPaymentController::class, 'webhook']);
Route::match(['get', 'post'], '/public/sepay/webhook', [SepayPaymentController::class, 'webhook']);

// 10. Hệ Thống Tin Nhắn Trực Tuyến Khách Hàng & Quản Trị Viên (Live Chat Support)
Route::get('/api/chat/messages', [ChatController::class, 'getMessages'])->name('api.chat.messages');
Route::post('/api/chat/send', [ChatController::class, 'sendMessage'])->name('api.chat.send');
Route::get('/api/chat/conversations', [ChatController::class, 'getConversations'])->name('api.chat.conversations');
Route::post('/api/chat/read', [ChatController::class, 'markAsRead'])->name('api.chat.read');
Route::post('/api/chat/delete', [ChatController::class, 'deleteConversation'])->name('api.chat.delete');

// Hỗ trợ alias khi truy cập qua subfolder XAMPP /webgiadung
Route::get('/webgiadung/api/chat/messages', [ChatController::class, 'getMessages']);
Route::post('/webgiadung/api/chat/send', [ChatController::class, 'sendMessage']);
Route::get('/webgiadung/api/chat/conversations', [ChatController::class, 'getConversations']);
Route::post('/webgiadung/api/chat/read', [ChatController::class, 'markAsRead']);
Route::post('/webgiadung/api/chat/delete', [ChatController::class, 'deleteConversation']);

// 11. Hệ Thống Đánh Giá Sản Phẩm (Reviews & Testimonials - Khách Hàng & Quản Trị Viên)
Route::get('/api/reviews', [ReviewController::class, 'apiIndex'])->name('api.reviews.index');
Route::post('/api/reviews', [ReviewController::class, 'apiStore'])->name('api.reviews.store');
Route::match(['put', 'patch'], '/api/reviews/{id}', [ReviewController::class, 'apiUpdate'])->name('api.reviews.update');
Route::delete('/api/reviews/{id}', [ReviewController::class, 'apiDestroy'])->name('api.reviews.destroy');
Route::options('/api/reviews', function() { return response('', 200, ['Access-Control-Allow-Origin' => '*', 'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS', 'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept']); });
Route::options('/api/reviews/{id}', function() { return response('', 200, ['Access-Control-Allow-Origin' => '*', 'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS', 'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept']); });

Route::get('/webgiadung/api/reviews', [ReviewController::class, 'apiIndex']);
Route::post('/webgiadung/api/reviews', [ReviewController::class, 'apiStore']);
Route::match(['put', 'patch'], '/webgiadung/api/reviews/{id}', [ReviewController::class, 'apiUpdate']);
Route::delete('/webgiadung/api/reviews/{id}', [ReviewController::class, 'apiDestroy']);
Route::options('/webgiadung/api/reviews', function() { return response('', 200, ['Access-Control-Allow-Origin' => '*', 'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS', 'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept']); });
Route::options('/webgiadung/api/reviews/{id}', function() { return response('', 200, ['Access-Control-Allow-Origin' => '*', 'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS', 'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept']); });

