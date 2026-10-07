<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthService
{
    /**
     * Đăng ký người dùng mới
     */
    public function register(array $data): User
    {
        Log::info('Registering user with email: ' . $data['email']);

        $user = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'password'          => Hash::make($data['password']),
            'role'              => 'customer',
            'email_verified_at' => now(),
        ]);

        Log::info('User registered successfully: ' . $data['email']);

        return $user;
    }

    /**
     * Đăng nhập người dùng (dùng Auth::attempt)
     */
    public function login(array $credentials, $request): bool
    {
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return true;
        }
        return false;
    }

    /**
     * Đăng xuất người dùng
     */
    public function logout($request): void
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    /**
     * Đồng bộ đăng nhập từ Modal trang chủ (index.html) sang Laravel Session
     * Hỗ trợ: kiểm tra mật khẩu hash, demo admin, môi trường local
     */
    public function syncLogin(string $email, string $password, ?string $name, $request): ?User
    {
        $user = User::where('email', $email)->first();

        if ($user) {
            $validPassword = Hash::check($password, $user->password)
                || ($email === 'admin@gmail.com' && in_array($password, ['123456', 'password', 'admin123']))
                || (app()->environment('local') && in_array($password, ['user123', '123456']));

            if (!$validPassword) {
                return null;
            }
        } else {
            // Tự động tạo tài khoản khách hàng mới nếu chưa tồn tại
            $resolvedName = $name ?: explode('@', $email)[0];
            $role = ($email === 'admin@gmail.com') ? 'admin' : 'customer';

            $user = User::create([
                'name'              => $resolvedName,
                'email'             => $email,
                'password'          => Hash::make($password ?: '123456'),
                'role'              => $role,
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return $user;
    }

    /**
     * Đồng bộ đăng ký từ Modal trang chủ (index.html) sang Laravel Session
     * Nếu email đã tồn tại thì đăng nhập luôn thay vì báo lỗi
     */
    public function syncRegister(string $email, string $name, string $password, string $phone, $request): User
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name'              => $name,
                'email'             => $email,
                'phone'             => $phone,
                'password'          => Hash::make($password),
                'role'              => 'customer',
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return $user;
    }

    /**
     * Lấy thông tin user hiện tại (dùng cho API /me)
     */
    public function getCurrentUser(): ?array
    {
        if (!Auth::check()) {
            return null;
        }

        $u = Auth::user();

        return [
            'id'    => $u->id,
            'name'  => $u->name,
            'email' => $u->email,
            'role'  => $u->role,
        ];
    }
}
