<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="only light">
    <meta name="supported-color-schemes" content="light">
    <meta name="darkreader-lock" content="true">
    <!-- CSRF Token bắt buộc của Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'FAMILY')</title>

    <!-- Google Fonts: Inter & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;1,500&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        :root {
            color-scheme: only light !important;
            forced-color-adjust: none !important;
            --gold-primary: #C5A059;
            --gold-light: #DFC591;
            --gold-dark: #9A7B38;
            --gold-gradient: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%);
            --gold-gradient-hover: linear-gradient(135deg, #ebd08f 0%, #d4af66 50%, #af8939 100%);
            --gold-glow: 0 4px 16px rgba(197, 160, 89, 0.28);
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #F8FAFC !important;
            color: #374151;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }
        main {
            flex: 1;
        }
        .font-serif {
            font-family: 'Playfair Display', Georgia, serif;
        }
        .text-gold-gradient {
            background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .text-gold {
            color: #9A7B38 !important;
        }
        .btn-gold {
            background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%);
            color: #FFFFFF !important;
            font-weight: 600;
            border: none;
            border-radius: 9999px;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 15px rgba(197, 160, 89, 0.28);
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #ebd08f 0%, #d4af66 50%, #af8939 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(197, 160, 89, 0.38);
            color: #FFFFFF !important;
        }
        .btn-outline-gold {
            border: 1.5px solid #C5A059;
            color: #9A7B38 !important;
            background: #FFFFFF;
            font-weight: 600;
            border-radius: 9999px;
            transition: all 0.25s ease;
        }
        .btn-outline-gold:hover {
            background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%) !important;
            color: #FFFFFF !important;
            border-color: transparent !important;
            box-shadow: 0 4px 15px rgba(197, 160, 89, 0.28) !important;
            transform: translateY(-1px);
        }
        .btn-outline-gold:hover i,
        .btn-outline-gold:hover span {
            color: #FFFFFF !important;
        }
        .custom-navbar {
            background: #FFFFFF !important;
            border-bottom: 1px solid #E5E7EB;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        }
        .custom-navbar .nav-link {
            color: #4B5563 !important;
            font-weight: 500;
            font-size: 0.9375rem;
            padding: 0.5rem 0.85rem;
            transition: color 0.2s;
        }
        .custom-navbar .nav-link:hover,
        .custom-navbar .nav-link.active {
            color: #9A7B38 !important;
        }
        .card {
            border: 1px solid #E5E7EB;
            border-radius: 16px;
        }
    </style>

    <!-- Stack để nhúng CSS riêng cho từng trang (nếu có) -->
    @stack('styles')
</head>
<body class="bg-light">
    @php 
        $prefix = request()->is('webgiadung*') ? '/webgiadung' : ''; 
    @endphp
    <!-- Thanh điều hướng Navbar Trắng Tinh Khiết & Champagne Gold -->
    <nav class="navbar navbar-expand-lg custom-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ $prefix . route('welcome', [], false) }}">
                @php
                    $logoPath = public_path('images/logo_web.png');
                    $logoUrl = asset('images/logo_web.png') . '?v=' . (file_exists($logoPath) ? filemtime($logoPath) : time());
                    $hasLogo = file_exists($logoPath);
                @endphp
                @if($hasLogo)
                    <img src="{{ $logoUrl }}" style="max-height: 48px; object-fit: contain;" alt="FAMILY">
                @else
                    <span class="text-gold-gradient font-serif" style="font-size: 1.6rem; letter-spacing: 0.05em; font-weight: 800;">FAMILY</span>
                @endif
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-2 text-dark"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Menu bên trái -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('/') || request()->is('webgiadung')) ? 'active fw-bold' : '' }}" href="{{ $prefix . route('welcome', [], false) }}">
                            <i class="bi bi-house me-1"></i> Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->routeIs('products.index') || request()->routeIs('products.show')) ? 'active fw-bold' : '' }}" href="{{ $prefix . route('products.index', [], false) }}">
                            <i class="bi bi-grid me-1"></i> Sản phẩm
                        </a>
                    </li>
                    @auth
                    @if(Auth::user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active fw-bold' : '' }}" href="{{ $prefix . route('admin.products.index', [], false) }}">
                            Sản phẩm (Admin)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active fw-bold' : '' }}" href="{{ $prefix . route('admin.categories.index', [], false) }}">
                            Danh mục (Admin)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active fw-bold' : '' }}" href="{{ $prefix . route('admin.dashboard', [], false) }}">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                    @endif
                    @endauth
                </ul>
                <!-- Menu bên phải (Xác thực tài khoản & Giỏ hàng) -->
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item d-none d-md-block">
                        <a href="tel:0886543518" class="btn btn-sm btn-outline-gold rounded-pill d-inline-flex align-items-center gap-1.5 px-3 py-1 fw-semibold" style="font-size: 0.825rem;" title="Gọi ngay Hotline tư vấn miễn phí">
                            <i class="bi bi-telephone-fill"></i>
                            <span>Hotline: 0886.543.518</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative px-2" href="{{ $prefix . route('cart.index', [], false) }}" title="Giỏ hàng">
                            <i class="bi bi-bag fs-5" style="color: #111827;"></i>
                            <span id="navCartBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background: linear-gradient(135deg, #DFC07A, #C5A059); color: #fff; font-size: 0.7rem; font-weight: 700; padding: 3px 6px; {{ (session('cart') && count(session('cart')) > 0) ? '' : 'display: none;' }}">
                                {{ (session('cart') && count(session('cart')) > 0) ? count(session('cart')) : 0 }}
                            </span>
                        </a>
                    </li>

                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $prefix . route('login', [], false) }}" style="color: #374151; font-weight: 600;">
                            Đăng nhập
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-outline-gold px-3 py-1.5" href="{{ $prefix . route('register', [], false) }}">
                            Đăng ký
                        </a>
                    </li>
                    @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 active fw-semibold" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="rounded-circle d-inline-flex align-items-center justify-content-center text-white" style="width: 28px; height: 28px; background: linear-gradient(135deg, #DFC07A, #C5A059); font-size: 0.75rem;">
                                <i class="bi bi-person-fill"></i>
                            </span>
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 14px; padding: 8px; border: 1px solid #EAE2D5 !important;" aria-labelledby="navbarDropdown">
                            @if(Auth::user()->role === 'admin')
                                <li>
                                    <a class="dropdown-item py-2 rounded-2" href="{{ $prefix . route('admin.dashboard', [], false) }}" style="font-weight: 600; color: #9A7B38;">
                                        <i class="bi bi-speedometer2 me-2"></i> Bảng quản trị
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                            @endif
                            <li>
                                <a class="dropdown-item py-2 rounded-2" href="{{ $prefix . route('orders.index', [], false) }}">
                                    <i class="bi bi-receipt me-2 text-muted"></i> Đơn hàng của tôi
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 rounded-2 text-danger" href="{{ $prefix . route('logout', [], false) }}" onclick="event.preventDefault(); try{localStorage.removeItem('auraluxe_user');}catch(e){} document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                                </a>
                                <form id="logout-form" action="{{ $prefix . route('logout', [], false) }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Nội dung chính của từng trang -->
    <main class="container py-4">
        <!-- Hiển thị thông báo thành công toàn cục -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" style="background: #F0FDF4; border: 1px solid #BBF7D0 !important; color: #166534; border-radius: 12px;" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Hiển thị thông báo lỗi toàn cục -->
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" style="background: #FEF2F2; border: 1px solid #FECACA !important; color: #991B1B; border-radius: 12px;" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-top py-4 text-center text-muted small mt-auto" style="border-color: #E5E7EB !important;">
        <div class="container d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2">
            <div>
                &copy; 2026 <strong class="text-dark font-serif">FAMILY</strong> - Hệ thống cửa hàng gia dụng chính hãng.
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ url('/') }}" class="text-muted text-decoration-none">Trang chủ</a>
                <span>•</span>
                <a href="{{ route('products.index') }}" class="text-muted text-decoration-none">Sản phẩm</a>
                <span>•</span>
                <a href="tel:0886543518" class="text-gold fw-semibold text-decoration-none d-inline-flex align-items-center gap-1">
                    <i class="bi bi-telephone-fill small"></i> Hotline: 0886.543.518
                </a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    (function() {
        @if(session('cart_cleared'))
        try {
            localStorage.removeItem('aura_cart');
            const badge = document.getElementById('navCartBadge');
            if (badge) badge.style.display = 'none';
        } catch(e) {}
        @else
        try {
            const badge = document.getElementById('navCartBadge');
            const local = localStorage.getItem('aura_cart');
            if (local && badge) {
                const items = JSON.parse(local);
                if (Array.isArray(items) && items.length > 0) {
                    badge.textContent = items.length;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            }
        } catch(e) {}
        @endif

        @auth
        try {
            const u = {
                id: @json(Auth::id()),
                name: @json(Auth::user()->name),
                email: @json(Auth::user()->email),
                role: @json(Auth::user()->role),
                phone: @json(Auth::user()->phone ?? '')
            };
            localStorage.setItem('auraluxe_user', JSON.stringify(u));
        } catch(e) {}
        @endauth
    })();
    </script>
    <!-- Hệ Thống Chat Trực Tuyến Khách Hàng & Tư Vấn Viên -->
    <script src="{{ asset('js/chat-widget.js') }}"></script>
    <!-- Stack để nhúng JS riêng cho từng trang (nếu có) -->
    @stack('scripts')
</body>
</html>
