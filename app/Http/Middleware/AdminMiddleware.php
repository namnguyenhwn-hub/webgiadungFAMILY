<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request for Admin authorization (Lab 3).
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Nếu chưa đăng nhập, chuyển hướng sang form login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập với tài khoản Quản trị viên để truy cập trang này.');
        }

        // 2. Nếu đã đăng nhập, kiểm tra xem có phải quyền admin hay không
        $user = Auth::user();
        if ($user->role === 'admin') {
            return $next($request);
        }

        // 3. Nếu là tài khoản Khách hàng thông thường (customer / user):
        // Giữ nguyên danh tính tài khoản khách hàng, tuyệt đối KHÔNG tự động chuyển sang tài khoản admin
        // và chuyển hướng người dùng về trang chủ kèm thông báo
        return redirect()->route('welcome')->with('error', 'Tài khoản của bạn không có quyền truy cập vào Bảng Quản Trị.');
    }
}
