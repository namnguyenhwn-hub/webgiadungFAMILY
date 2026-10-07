<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="only light">
    <meta name="supported-color-schemes" content="light">
    <meta name="darkreader-lock" content="true">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'FAMILY - Quản Trị Hệ Thống')</title>

    <!-- Google Fonts: Inter & Playfair Display (Đồng bộ trang chủ) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;1,500&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Chart.js CDN for Analytics & Reporting -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Ngăn chặn tuyệt đối Dark Mode & Ép giao diện Trắng Tinh Khiết */
        :root, html, body {
            color-scheme: only light !important;
            forced-color-adjust: none !important;
            -webkit-font-smoothing: antialiased;
            background-color: #F8FAFC !important;
            background: #F8FAFC !important;
            color: #1E293B !important;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        /* Pure White Luxury Navbar */
        .admin-header {
            background: #FFFFFF !important;
            border-bottom: 1px solid #E5E7EB !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04) !important;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .admin-brand {
            font-family: 'Playfair Display', Georgia, serif;
            font-weight: 800;
            font-size: 1.55rem;
            letter-spacing: 0.04em;
            background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .admin-tag {
            background: rgba(197, 160, 89, 0.12);
            color: #9A7B38;
            border: 1px solid rgba(197, 160, 89, 0.3);
            font-size: 0.72rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 9999px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .admin-nav .nav-link {
            color: #4B5563 !important;
            font-weight: 600;
            font-size: 0.88rem;
            padding: 8px 14px !important;
            border-radius: 8px;
            transition: all 0.15s ease;
        }

        .admin-nav .nav-link:hover {
            color: #111827 !important;
            background: #F3F4F6 !important;
        }

        .admin-nav .nav-link.active {
            color: #9A7B38 !important;
            background: rgba(197, 160, 89, 0.1) !important;
            font-weight: 700;
        }

        .btn-back-home {
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            color: #374151;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 6px 14px;
            border-radius: 9999px;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-back-home:hover {
            border-color: #C5A059;
            color: #9A7B38;
            background: rgba(197, 160, 89, 0.05);
        }

        .admin-user-pill {
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 9999px;
            padding: 4px 12px 4px 6px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #111827;
            cursor: pointer;
            text-decoration: none;
        }

        .admin-user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #DFC07A 0%, #C5A059 100%);
            color: #FFFFFF;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
        }

        /* Card and Table Styling chuẩn Trắng Sang Trọng */
        .card {
            background-color: #FFFFFF !important;
            border: 1px solid #E5E7EB !important;
            border-radius: 14px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04) !important;
        }

        .card-header {
            background-color: #FFFFFF !important;
            border-bottom: 1px solid #F1F5F9 !important;
            border-top-left-radius: 14px !important;
            border-top-right-radius: 14px !important;
        }

        .table {
            color: #1E293B !important;
        }

        .table-light, thead.table-light th {
            background-color: #F8FAFC !important;
            color: #475569 !important;
            border-bottom: 1px solid #E5E7EB !important;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .table > :not(caption) > * > * {
            background-color: transparent !important;
            border-bottom-color: #F1F5F9 !important;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
        }

        .breadcrumb-item a {
            color: #64748B;
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb-item.active {
            color: #1E293B;
            font-weight: 600;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Thanh điều hướng Header Trắng Tinh Khiết & Champagne Gold -->
    <header class="admin-header py-2">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between">
                <!-- Brand & Logo -->
                <div class="d-flex align-items-center gap-3">
                    <a class="admin-brand" href="{{ route('admin.dashboard') }}">
                        <i class="fa-solid fa-gem" style="font-size: 1.25rem;"></i>
                        <span>FAMILY</span>
                    </a>
                    <span class="admin-tag d-none d-sm-inline-block">
                        <i class="fa-solid fa-shield-halved me-1"></i> Quản Trị Hệ Thống
                    </span>
                </div>

                <!-- Navigation Links (Moved to Dashboard Sidebar) -->
                <nav class="d-none d-lg-flex align-items-center gap-1 admin-nav">
                    <!-- Nav items moved to quick dashboard menu -->
                </nav>

                <!-- Actions Right -->
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ url('/') }}" target="_blank" class="btn-back-home" title="Mở trang chủ bán hàng">
                        <i class="bi bi-shop"></i>
                        <span class="d-none d-md-inline">Trang Chủ Bán Hàng</span>
                    </a>

                    @auth
                    <!-- User Menu Dropdown -->
                    <div class="dropdown">
                        <button class="admin-user-pill dropdown-toggle border-0" type="button" id="adminUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="admin-user-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="d-none d-sm-inline text-truncate" style="max-width: 140px;">
                                {{ Auth::user()->name }}
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border mt-2" aria-labelledby="adminUserDropdown" style="border-radius: 12px; min-width: 220px; padding: 8px;">
                            <li class="px-3 py-2 border-bottom mb-2">
                                <div class="fw-bold text-dark fs-6">{{ Auth::user()->name }}</div>
                                <div class="text-muted small">{{ Auth::user()->email }}</div>
                                <span class="badge bg-warning text-dark mt-1" style="font-size: 0.68rem;">Quản Trị Viên</span>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 rounded-2" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2 text-primary"></i> Dashboard Quản Trị
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 rounded-2" href="{{ url('/') }}" target="_blank">
                                    <i class="bi bi-box-arrow-up-right me-2 text-secondary"></i> Xem Website Khách Hàng
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <a class="dropdown-item py-2 rounded-2 text-danger fw-semibold" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i> Đăng Xuất
                                </a>
                                <form id="logout-form" action="{{ route('logout', [], false) }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Nội dung chính của từng trang -->
    <main class="container-fluid px-4 py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center" role="alert" style="border-radius: 10px;">
                <i class="bi bi-check-circle-fill me-2 fs-5 text-success"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 d-flex align-items-center" role="alert" style="border-radius: 10px;">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5 text-danger"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer Quản Trị Nhẹ Nhàng -->
    <footer class="py-3 text-center text-muted small bg-white border-top mt-auto">
        <div class="container-fluid px-4">
            <span>© 2026 <strong>FAMILY LUXURY APPLIANCES</strong> - Hệ Thống Báo Cáo & Quản Trị Tập Trung. Tất cả các quyền được bảo lưu.</span>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Hệ Thống Chat Trực Tuyến Quản Trị Viên -->
    <script src="{{ asset('js/admin-chat.js') }}"></script>
    @stack('scripts')
</body>
</html>
