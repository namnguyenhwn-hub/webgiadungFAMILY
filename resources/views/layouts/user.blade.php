<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="only light">
    <meta name="supported-color-schemes" content="light">
    <meta name="darkreader-lock" content="true">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FAMILY LUXE - Gia Dụng Gia Đình Cao Cấp')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            color-scheme: only light !important;
            forced-color-adjust: none !important;
            --bg-cream: #FFFFFF;
            --surface-cream: #FFFFFF;
            --primary-gold: #B8860B;
            --gold-hover: #996F07;
            --gold-light: #FBF6EC;
            --text-main: #111827;
            --text-muted: #6B7280;
            --border-cream: #E5E7EB;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-cream);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-custom {
            background-color: #FFFFFF !important;
            border-bottom: 2px solid var(--primary-gold);
            padding: 14px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        .navbar-custom .navbar-brand {
            color: #111827 !important;
            font-weight: 700;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-custom .navbar-brand span {
            color: var(--primary-gold);
        }

        .navbar-custom .nav-link {
            color: #374151 !important;
            font-weight: 500;
            padding: 8px 16px !important;
            transition: all 0.2s ease;
            border-radius: 8px;
        }

        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {
            color: var(--primary-gold) !important;
            background-color: rgba(184, 134, 11, 0.08);
        }

        .cart-badge {
            background-color: var(--primary-gold);
            color: #FFF;
            font-size: 0.72rem;
            padding: 3px 7px;
            border-radius: 20px;
            margin-left: 4px;
        }

        .btn-gold {
            background: linear-gradient(135deg, #B8860B, #8B6508);
            border: none;
            color: #FFF !important;
            font-weight: 600;
            padding: 8px 18px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-gold:hover {
            background: linear-gradient(135deg, #996F07, #705206);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(184, 134, 11, 0.25);
        }

        .btn-outline-gold {
            border: 1px solid var(--primary-gold);
            color: var(--primary-gold) !important;
            border-radius: 10px;
            padding: 7px 16px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-outline-gold:hover {
            background-color: var(--primary-gold);
            color: #FFF !important;
        }

        .dropdown-menu {
            border: 1px solid var(--border-cream);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            background: #FFF;
        }

        .dropdown-item {
            padding: 10px 18px;
            font-weight: 500;
            color: var(--text-main);
        }

        .dropdown-item:hover {
            background-color: var(--gold-light);
            color: var(--primary-gold);
        }

        .card-custom {
            background: #FFF;
            border: 1px solid var(--border-cream);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(45, 36, 30, 0.03);
        }

        .card-custom:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(184, 134, 11, 0.12);
            border-color: rgba(184, 134, 11, 0.3);
        }

        footer {
            background-color: #F9FAFB;
            color: #4B5563;
            margin-top: auto;
            border-top: 1px solid #E5E7EB;
        }
    </style>
    @yield('styles')
</head>
<body>

<!-- === THANH ĐIỀU HƯỚNG NAVBAR (LAB 3) === -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <!-- Logo / Tên trang -->
        <a class="navbar-brand" href="{{ route('welcome') }}">
            <i class="bi bi-shield-check text-warning"></i>
            <span>FAMILY</span> LUXE
        </a>

        <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Menu bên trái -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}" href="{{ route('welcome') }}">
                        <i class="bi bi-house me-1"></i> Trang Chủ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                        <i class="bi bi-grid me-1"></i> Danh sách sản phẩm
                    </a>
                </li>
                @auth
                    @if (Auth::user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link text-warning fw-bold" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i> Trang Quản Trị
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>

            <!-- Menu bên phải (Giỏ hàng, Lịch sử đơn hàng, Tài khoản) -->
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <!-- Liên kết Giỏ hàng -->
                <li class="nav-item">
                    <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                        <i class="bi bi-cart3 me-1"></i> Giỏ hàng
                        @php
                            $cartCount = count((array) session('cart', []));
                        @endphp
                        @if ($cartCount > 0)
                            <span class="cart-badge">{{ $cartCount }}</span>
                        @endif
                    </a>
                </li>

                <!-- Liên kết Lịch sử đơn hàng -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('orders.index') }}">
                        <i class="bi bi-clock-history me-1"></i> Lịch sử đơn hàng
                    </a>
                </li>

                @auth
                    <!-- Tên người dùng và nút Đăng xuất -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white fw-semibold" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1 text-warning"></i>
                            {{ Auth::user()->name }}
                            @if (Auth::user()->role === 'admin')
                                <span class="badge bg-warning text-dark ms-1">Admin</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                            <li class="px-3 py-2 border-bottom">
                                <div class="fw-bold">{{ Auth::user()->name }}</div>
                                <small class="text-muted">{{ Auth::user()->email }}</small>
                            </li>
                            @if (Auth::user()->role === 'admin')
                                <li>
                                    <a class="dropdown-item text-primary" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i> Bảng điều khiển Admin
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.products.index') }}">
                                        <i class="bi bi-box-seam me-2"></i> Quản lý sản phẩm
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ route('orders.index') }}">
                                    <i class="bi bi-bag-check me-2"></i> Đơn hàng của tôi
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger fw-semibold">
                                        <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <!-- Khách chưa đăng nhập -->
                    <li class="nav-item">
                        <a class="btn btn-outline-gold btn-sm me-1" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-gold btn-sm" href="{{ route('register') }}">
                            <i class="bi bi-person-plus me-1"></i> Đăng ký
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- === KHU VỰC HIỂN THỊ THÔNG BÁO (SESSION SUCCESS / ERROR) === -->
<div class="container mt-3">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('status'))
        <div class="alert alert-warning alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert">
            <i class="bi bi-bell-fill me-2"></i> {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>

<!-- === PHẦN NỘI DUNG CHÍNH (MAIN CONTENT) === -->
<main class="py-4">
    @yield('content')
</main>

<!-- Footer -->
<footer class="py-4">
    <div class="container text-center">
        <p class="mb-1 text-white fw-bold">FAMILY &bull; Hotline: <a href="tel:0886543518" class="text-warning text-decoration-none">0886.543.518</a></p>
        <small class="text-muted">&copy; 2026 Hệ Thống Bán Lẻ Thiết Bị Gia Dụng Cao Cấp. All Rights Reserved.</small>
    </div>
</footer>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
