<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Hiển thị form đăng ký (Lab 3)
     */
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('welcome');
        }

        return view('auth.register');
    }

    /**
     * Xử lý đăng ký người dùng (Lab 3)
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Vui lòng nhập tên của bạn.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.unique' => 'Địa chỉ email này đã tồn tại trong hệ thống.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có tối thiểu 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không trùng khớp.',
        ]);

        try {
            Log::info('Registering user with email: ' . $request->email);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'customer',
                'email_verified_at' => now(), // Tự động xác thực email để người dùng đăng nhập ngay theo chuẩn Lab 3
            ]);

            Log::info('User registered successfully: ' . $request->email);

            // Tự động đăng nhập sau khi đăng ký thành công và Ghi nhớ đăng nhập
            Auth::login($user, true);
            $request->session()->regenerate();

            $isWebgiadung = $request->is('webgiadung*');
            $prefix = $isWebgiadung ? '/webgiadung' : '';

            return redirect()->to($prefix . route('welcome', [], false))->with('success', 'Đăng ký tài khoản thành công! Bạn đã được đăng nhập tự động.');
        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            return back()->with('error', 'Registration failed. Please try again.');
        }
    }

    /**
     * Hiển thị form đăng nhập (Lab 3)
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('welcome');
        }

        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập người dùng (Lab 3)
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        if (Auth::attempt($request->only('email', 'password'), true)) {
            $request->session()->regenerate();

            $isWebgiadung = $request->is('webgiadung*');
            $prefix = $isWebgiadung ? '/webgiadung' : '';

            if ($request->filled('redirect')) {
                $redirectTarget = $request->input('redirect');
                if ($redirectTarget === 'cart') {
                    return redirect()->to($prefix . route('cart.index', [], false));
                }
                return redirect($redirectTarget);
            }

            $user = Auth::user();

            // Nếu đúng là Quản trị viên (Admin) thì chuyển vào Bảng Quản Trị
            if ($user->role === 'admin') {
                return redirect()->to($prefix . route('admin.dashboard', [], false));
            }

            // Nếu là Khách hàng (customer / user):
            // Tuyệt đối không để intended URL dẫn khách vào trang Admin
            session()->forget('url.intended');
            return redirect()->to($prefix . route('welcome', [], false));
        }

        return back()->withErrors([
            'email' => 'The provided credentials are incorrect.',
        ])->onlyInput('email');
    }

    /**
     * Lấy thông tin tài khoản hiện tại từ Laravel Session
     */
    public function me()
    {
        if (Auth::check()) {
            $u = Auth::user();
            return response()->json([
                'authenticated' => true,
                'user' => [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'role' => $u->role,
                ]
            ]);
        }
        return response()->json([
            'authenticated' => false,
            'user' => null
        ]);
    }

    /**
     * Xử lý đăng xuất người dùng (Lab 3)
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Bạn đã đăng xuất thành công.');
    }

    /**
     * Đồng bộ đăng nhập từ Modal trang chủ index.html sang Laravel Session
     */
    public function syncLogin(Request $request)
    {
        $email = trim($request->input('email', ''));
        $password = trim($request->input('password', ''));

        if (empty($email)) {
            return response()->json(['success' => false, 'message' => 'Vui lòng nhập email.'], 422);
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            // Kiểm tra mật khẩu (hỗ trợ cả hash lẫn demo admin)
            $isMatch = Hash::check($password, $user->password) 
                || ($email === 'admin@gmail.com' && in_array($password, ['123456', 'password', 'admin123']))
                || (app()->environment('local') && (!empty($password) || in_array($password, ['user123', '123456', 'guest_sync'])));

            if ($isMatch) {
                if (app()->environment('local') && !empty($password) && !Hash::check($password, $user->password)) {
                    $user->update(['password' => Hash::make($password)]);
                }
                Auth::login($user, true);
                $request->session()->regenerate();

                return response()->json([
                    'success' => true,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                    ]
                ]);
            }
            return response()->json(['success' => false, 'message' => 'Mật khẩu không chính xác.'], 401);
        }

        // Nếu chưa có tài khoản, tự động tạo tài khoản khách hàng để đăng nhập ngay mà không làm gián đoạn khách mua hàng
        $name = $request->input('name') ?: explode('@', $email)[0];
        $role = ($email === 'admin@gmail.com') ? 'admin' : 'customer';

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password ?: '123456'),
            'role' => $role,
            'email_verified_at' => now(),
        ]);

        Auth::login($user, true);
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    }

    /**
     * Đồng bộ đăng ký từ Modal trang chủ index.html sang Laravel Session
     */
    public function syncRegister(Request $request)
    {
        $name = trim($request->input('name', 'Khách hàng'));
        $email = trim($request->input('email', ''));
        $password = trim($request->input('password', '123456'));
        $phone = trim($request->input('phone', ''));

        if (empty($email)) {
            return response()->json(['success' => false, 'message' => 'Vui lòng nhập email.'], 422);
        }

        $user = User::where('email', $email)->first();
        if ($user) {
            Auth::login($user, true);
        } else {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => Hash::make($password),
                'role' => 'customer',
                'email_verified_at' => now(),
            ]);
            Auth::login($user, true);
        }

        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    }

    /**
     * Đồng bộ đăng xuất từ trang chủ index.html sang Laravel Session
     */
    public function syncLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['success' => true]);
    }
}
