<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display the Admin Dashboard with comprehensive analytics & reporting
     */
    public function dashboard()
    {
        $users = User::orderBy('id', 'desc')->get();
        $products = Product::with('category')->orderBy('id', 'desc')->get();
        $categories = \App\Models\Category::withCount('products')->get();
        $recentOrders = Order::orderBy('id', 'desc')->get();

        $totalUsers = $users->count();
        $totalCustomers = $users->whereIn('role', ['customer', 'user'])->count();
        $totalAdmins = $users->where('role', 'admin')->count();
        $newUsersToday = $users->filter(function($u) {
            return $u->created_at && $u->created_at >= now()->startOfDay();
        })->count();

        // Valid orders for revenue calculation
        $validOrders = $recentOrders->filter(function($ord) {
            $st = strtolower($ord->status ?? '');
            return !in_array($st, ['đã hủy', 'da huy', 'cancelled', 'canceled']);
        });

        $totalRevenue = $validOrders->sum(function($ord) {
            return (float) ($ord->amount ?: $ord->total ?: 0);
        });

        $revenueToday = $validOrders->filter(function($ord) {
            return $ord->created_at && $ord->created_at >= now()->startOfDay();
        })->sum(function($ord) {
            return (float) ($ord->amount ?: $ord->total ?: 0);
        });

        $revenueThisMonth = $validOrders->filter(function($ord) {
            return $ord->created_at && $ord->created_at >= now()->startOfMonth();
        })->sum(function($ord) {
            return (float) ($ord->amount ?: $ord->total ?: 0);
        });

        $ordersCount = $recentOrders->count();
        $pendingOrders = $recentOrders->filter(function($ord) {
            $st = strtolower($ord->status ?? '');
            return str_contains($st, 'chờ') || str_contains($st, 'pending') || $st === 'processing';
        })->count();

        $shippingOrders = $recentOrders->filter(function($ord) {
            $st = strtolower($ord->status ?? '');
            return str_contains($st, 'đang giao') || str_contains($st, 'shipping');
        })->count();

        $completedOrders = $recentOrders->filter(function($ord) {
            $st = strtolower($ord->status ?? '');
            return str_contains($st, 'hoàn thành') || str_contains($st, 'thành công') || str_contains($st, 'completed');
        })->count();

        $cancelledOrders = $recentOrders->filter(function($ord) {
            $st = strtolower($ord->status ?? '');
            return str_contains($st, 'hủy') || str_contains($st, 'cancel');
        })->count();

        // Inventory metrics
        $totalProducts = $products->count();
        $lowStockProducts = $products->filter(function($p) {
            $qty = $p->quantity ?? $p->stock ?? 0;
            return $qty > 0 && $qty <= 5;
        })->count();
        $outOfStockProducts = $products->filter(function($p) {
            $qty = $p->quantity ?? $p->stock ?? 0;
            return $qty <= 0;
        })->count();

        // 7-day revenue & order trends for Chart.js
        $chartLabels = [];
        $chartRevenueData = [];
        $chartOrderData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('d/m');
            $dayOrders = $validOrders->filter(function($ord) use ($date) {
                return $ord->created_at && $ord->created_at->format('Y-m-d') === $date->format('Y-m-d');
            });
            $chartRevenueData[] = (float) $dayOrders->sum(function($ord) { return $ord->amount ?: $ord->total ?: 0; });
            $chartOrderData[] = $dayOrders->count();
        }

        // Category breakdown for Chart.js
        $catLabels = [];
        $catCounts = [];
        foreach ($categories as $cat) {
            $catLabels[] = $cat->name;
            $catCounts[] = $cat->products_count;
        }

        // Comprehensive business & reporting metrics
        $stats = [
            'revenue_total' => number_format($totalRevenue, 0, ',', '.') . ' ₫',
            'revenue_today' => number_format($revenueToday, 0, ',', '.') . ' ₫',
            'revenue_month' => number_format($revenueThisMonth, 0, ',', '.') . ' ₫',
            'avg_order_value' => $ordersCount > 0 ? number_format(round($totalRevenue / $ordersCount), 0, ',', '.') . ' ₫' : '0 ₫',
            'orders_count' => $ordersCount,
            'orders_pending' => $pendingOrders,
            'orders_shipping' => $shippingOrders,
            'orders_completed' => $completedOrders,
            'orders_cancelled' => $cancelledOrders,
            'orders_completion_rate' => $ordersCount > 0 ? round(($completedOrders / $ordersCount) * 100, 1) : 100,
            'products_count' => $totalProducts,
            'products_low_stock' => $lowStockProducts,
            'products_out_of_stock' => $outOfStockProducts,
            'total_users' => $totalUsers,
            'customers_count' => $totalCustomers,
            'admins_count' => $totalAdmins,
            'new_users_today' => $newUsersToday,
            'chart_labels' => $chartLabels,
            'chart_revenue' => $chartRevenueData,
            'chart_orders' => $chartOrderData,
            'cat_labels' => $catLabels,
            'cat_counts' => $catCounts,
        ];

        return view('admin.dashboard', compact('stats', 'users', 'recentOrders', 'products', 'categories'));
    }

    /* =========================================================================
       1. USER MANAGEMENT & EDITING
       ========================================================================= */

    /**
     * Update user details (Name, Email, Phone, Role, Status)
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:admin,user,customer'],
            'status' => ['required', 'in:active,inactive,blocked'],
        ], [
            'name.required' => 'Họ và tên không được để trống.',
            'email.required' => 'Địa chỉ email không được để trống.',
            'email.unique' => 'Email này đã có người sử dụng.',
            'role.required' => 'Vui lòng chọn vai trò hợp lệ.',
        ]);

        // Prevent self-demotion or self-deactivation
        if ($user->id === Auth::id()) {
            if ($validated['role'] !== 'admin') {
                return back()->with('error', 'Bạn không thể tự hạ quyền Quản trị viên của chính mình.');
            }
            if ($validated['status'] !== 'active') {
                return back()->with('error', 'Bạn không thể tự khóa tài khoản của chính mình.');
            }
        }

        $validated['role'] = ($validated['role'] === 'admin') ? 'admin' : 'customer';
        $user->update($validated);

        return back()->with('success', "Đã cập nhật thông tin tài khoản {$user->name} thành công!");
    }

    /**
     * Quick toggle user role
     */
    public function updateUserRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Bạn không thể tự thay đổi vai trò của chính mình.');
        }

        $request->validate([
            'role' => ['required', 'in:admin,user,customer'],
        ]);

        $targetRole = ($request->role === 'admin') ? 'admin' : 'customer';
        $user->role = $targetRole;
        $user->save();

        $roleText = $targetRole === 'admin' ? 'Quản trị viên (Admin)' : 'Khách hàng (User)';
        return back()->with('success', "Đã cập nhật vai trò của {$user->name} thành {$roleText}.");
    }

    /**
     * Delete user account
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Bạn không thể tự xóa tài khoản của chính mình.');
        }

        $userName = $user->name;
        $user->delete();

        return back()->with('success', "Đã xóa tài khoản {$userName} thành công.");
    }

    /* =========================================================================
       2. PRODUCT INVENTORY MANAGEMENT & EDITING
       ========================================================================= */

    /**
     * Store new appliance product
     */
    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:255'],
            'origin' => ['nullable', 'string', 'max:255'],
            'material' => ['nullable', 'string', 'max:255'],
            'usage' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ], [
            'name.required' => 'Vui lòng nhập tên thiết bị gia dụng.',
            'category.required' => 'Vui lòng chọn danh mục thiết bị.',
            'price.required' => 'Vui lòng nhập đơn giá sản phẩm.',
            'stock.required' => 'Vui lòng nhập số lượng tồn kho.',
        ]);

        Product::create($validated);

        return back()->with('success', "Đã thêm mới thiết bị '{$validated['name']}' vào kho hàng thành công!");
    }

    /**
     * Update appliance product details
     */
    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:255'],
            'origin' => ['nullable', 'string', 'max:255'],
            'material' => ['nullable', 'string', 'max:255'],
            'usage' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ], [
            'name.required' => 'Tên thiết bị không được để trống.',
            'price.required' => 'Đơn giá không hợp lệ.',
            'stock.required' => 'Số lượng tồn kho không hợp lệ.',
        ]);

        $product->update($validated);

        return back()->with('success', "Đã cập nhật thông tin thiết bị '{$product->name}' thành công!");
    }

    /**
     * Delete appliance product
     */
    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $name = $product->name;
        $product->delete();

        return back()->with('success', "Đã xóa thiết bị '{$name}' khỏi kho hàng.");
    }

    /* =========================================================================
       3. ORDER MANAGEMENT & EDITING
       ========================================================================= */

    /**
     * Update order status & notes
     */
    public function updateOrder(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:Chờ xác nhận,Đang giao hàng,Đã hoàn thành,Đã hủy'],
            'notes' => ['nullable', 'string'],
        ], [
            'status.required' => 'Vui lòng chọn trạng thái đơn hàng hợp lệ.',
        ]);

        if ($validated['status'] === 'Đã hoàn thành') {
            $validated['payment_status'] = 'paid';
        }

        $order->update($validated);

        return back()->with('success', "Đã cập nhật trạng thái đơn hàng #{$order->order_code} thành [{$order->status}]!");
    }

    /**
     * Delete order
     */
    public function deleteOrder($id)
    {
        $order = Order::findOrFail($id);
        $code = $order->order_code;
        $order->delete();

        return back()->with('success', "Đã xóa đơn hàng #{$code} thành công.");
    }

    public function uploadBanner(Request $request)
    {
        $urls = [];
        $hasAnyFile = false;
        
        for ($i = 1; $i <= 3; $i++) {
            $key = 'banner_' . $i;
            if ($request->hasFile($key)) {
                $hasAnyFile = true;
                $file = $request->file($key);
                $filename = 'banner_home_' . $i . '.jpg';
                $file->move(public_path('images'), $filename);
                $urls[] = asset('images/' . $filename) . '?v=' . time();
            }
        }

        if ($hasAnyFile) {
            return response()->json(['success' => true, 'urls' => $urls]);
        }
        
        return response()->json(['success' => false, 'message' => 'Vui lòng chọn ít nhất 1 ảnh hợp lệ.']);
    }

    public function uploadLogo(Request $request)
    {
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            // Save as PNG to support transparency
            $file->move(public_path('images'), 'logo_web.png');
            return response()->json(['success' => true, 'url' => asset('images/logo_web.png') . '?v=' . time()]);
        }
        return response()->json(['success' => false, 'message' => 'Vui lòng chọn ảnh hợp lệ.']);
    }

    public function updatePromo(Request $request)
    {
        $request->validate([
            'promo_value' => 'required|string|max:50',
            'promo_code' => 'required|string|max:50'
        ]);
        $settingsPath = storage_path('app/settings.json');
        $settings = file_exists($settingsPath) ? json_decode(file_get_contents($settingsPath), true) : [];
        $settings['promo_value'] = $request->promo_value;
        $settings['promo_code'] = $request->promo_code;
        file_put_contents($settingsPath, json_encode($settings, JSON_PRETTY_PRINT));
        return back()->with('success', 'Đã cập nhật cấu hình mã giảm giá thành công!');
    }
}
