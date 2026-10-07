<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="color-scheme" content="only light">
    <meta name="supported-color-schemes" content="light">
    <meta name="darkreader-lock" content="true">

    <!-- Primary SEO Meta Tags -->
    <title>FAMILY - Thiết Bị Gia Dụng Thông Minh & Đẳng Cấp</title>
    <meta name="title" content="FAMILY - Thiết Bị Gia Dụng Thông Minh & Đẳng Cấp">
    <meta name="description"
        content="Khám phá bộ sưu tập thiết bị gia dụng cao cấp FAMILY: Nồi chiên không dầu OLED, Tủ lạnh thông minh French-Door, Bếp từ đôi Inverter Đức. Phong cách Minimalism & Premium Gold sang trọng.">
    <meta name="keywords"
        content="đồ gia dụng cao cấp, nồi chiên không dầu, bếp từ đôi, tủ lạnh thông minh, nồi cơm cao tần, robot hút bụi AI, family">

    <!-- Google Fonts: Inter & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;1,500&display=swap"
        rel="stylesheet">

    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Custom CSS (Giao Diện Trắng Tinh Khiết & Champagne Gold) -->
    <link rel="stylesheet" href="{{ asset('css/style.css?v=2.2') }}">
    <style>
        /* Ngăn chặn tuyệt đối trình duyệt hoặc tiện ích mở rộng tự đảo màu sang Dark Mode */
        :root,
        html,
        body {
            color-scheme: only light !important;
            forced-color-adjust: none !important;
            -webkit-font-smoothing: antialiased;
        }

        /* Giao diện chuẩn Tone TRẮNG TINH KHIẾT & THƯỢNG LƯU (PURE WHITE & CHAMPAGNE GOLD) */
        html,
        body {
            background-color: #FFFFFF !important;
            background: #FFFFFF !important;
            color: #374151 !important;
        }

        .top-bar {
            background: #F9FAFB !important;
            color: #4B5563 !important;
            border-bottom: 1px solid #E5E7EB !important;
        }

        .site-header {
            background: rgba(255, 255, 255, 0.98) !important;
            border-bottom: 1px solid #E5E7EB !important;
        }

        .hero-section {
            background: #FFFFFF !important;
            border-bottom: 1px solid #E5E7EB !important;
        }

        .hero-title,
        .bento-card-title,
        .section-title,
        .product-title,
        .brand-logo {
            color: #111827 !important;
        }

        .hero-desc {
            color: #4B5563 !important;
        }

        .hero-tag {
            background: #FFFFFF !important;
            border-color: #E5E7EB !important;
            color: #9A7B38 !important;
        }

        .hero-main-card,
        .bento-card,
        .product-card,
        .review-card,
        .commitment-item {
            background: #FFFFFF !important;
            border: 1px solid #E5E7EB !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05) !important;
        }

        .hero-img-wrap,
        .bento-hero-img-box,
        .product-img-box,
        .card-img-container {
            background: #F8FAFC !important;
            border: 1px solid #F1F5F9 !important;
        }

        .floating-badge {
            background: #FFFFFF !important;
            border: 1px solid #E5E7EB !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
        }

        .floating-badge .badge-icon {
            background: #F8FAFC !important;
            color: #C5A059 !important;
            border: 1px solid #E5E7EB !important;
        }

        .floating-badge .badge-text strong {
            color: #111827 !important;
        }

        .floating-badge .badge-text span {
            color: #6B7280 !important;
        }

        .cat-pill {
            background: #FFFFFF !important;
            border-color: #E5E7EB !important;
            color: #374151 !important;
        }

        .cat-pill:hover,
        .cat-pill.active {
            background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%) !important;
            border-color: transparent !important;
            color: #FFFFFF !important;
        }

        .categories-section,
        .product-grid-section,
        .reviews-section {
            background: #FFFFFF !important;
        }

        .bento-section,
        .commitments-section {
            background: #F9FAFB !important;
            border-color: #E5E7EB !important;
            color: #374151 !important;
        }

        .site-footer {
            background: #F3F4F6 !important;
            border-color: #E5E7EB !important;
            color: #4B5563 !important;
        }

        .footer-col-title,
        .newsletter-title {
            color: #111827 !important;
        }

        .newsletter-section {
            background: #F9FAFB !important;
            border-color: #E5E7EB !important;
        }

        .search-active-banner {
            background: #FFFFFF !important;
            border-color: rgba(197, 160, 89, 0.4) !important;
        }

        .empty-search-state {
            background: #FFFFFF !important;
        }

        /* Khắc phục triệt để không bị xuống dòng các mục: Trang chủ, Danh mục, Bộ sưu tập, Sản phẩm, Đánh giá, Liên hệ */
        .header-inner {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            height: 80px !important;
            gap: 16px !important;
            flex-wrap: nowrap !important;
        }

        .brand-logo {
            flex-shrink: 0 !important;
            white-space: nowrap !important;
        }

        .search-container {
            flex: 0 1 240px !important;
            max-width: 280px !important;
            min-width: 140px !important;
        }

        .header-nav {
            display: flex !important;
            align-items: center !important;
            gap: clamp(10px, 1.4vw, 22px) !important;
            flex-wrap: nowrap !important;
            white-space: nowrap !important;
            flex-shrink: 0 !important;
        }

        .nav-link {
            font-weight: 500 !important;
            font-size: 0.9rem !important;
            white-space: nowrap !important;
            flex-shrink: 0 !important;
            word-break: keep-all !important;
            display: inline-flex !important;
            align-items: center !important;
            padding: 6px 2px !important;
        }

        .nav-link.has-badge {
            display: inline-flex !important;
            align-items: center !important;
            gap: 5px !important;
            white-space: nowrap !important;
        }

        .nav-badge-gold {
            white-space: nowrap !important;
            flex-shrink: 0 !important;
            display: inline-block !important;
        }

        .header-actions {
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            flex-shrink: 0 !important;
            white-space: nowrap !important;
        }

        .action-btn {
            white-space: nowrap !important;
            flex-shrink: 0 !important;
        }

        @media (max-width: 1040px) {
            .search-container {
                display: none !important;
            }
        }

        @media (max-width: 880px) {
            .header-nav {
                display: none !important;
            }

            .mobile-menu-btn {
                display: block !important;
            }

            .luxury-nav-dropdown {
                position: static !important;
                transform: none !important;
                box-shadow: none !important;
                border: 1px solid #EAE2D5 !important;
                margin-top: 8px !important;
                width: 100% !important;
                min-width: unset !important;
                display: none !important;
                opacity: 1 !important;
                visibility: visible !important;
                pointer-events: auto !important;
            }

            .luxury-nav-dropdown::before,
            .luxury-nav-dropdown::after {
                display: none !important;
            }

            .nav-dropdown-wrapper.active .luxury-nav-dropdown {
                display: block !important;
            }
        }

        /* =========================================================================
           LUXURY NAVIGATION DROPDOWN MENUS (DANH MỤC & SẢN PHẨM)
           ========================================================================= */
        .nav-dropdown-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
        }

        .nav-dropdown-toggle {
            cursor: pointer;
            display: inline-flex !important;
            align-items: center !important;
            gap: 5px !important;
            text-decoration: none !important;
        }

        .nav-dropdown-arrow {
            font-size: 0.65rem !important;
            color: #9A7B38 !important;
            transition: transform 0.25s ease !important;
            margin-top: 1px;
        }

        .nav-dropdown-wrapper:hover .nav-dropdown-arrow,
        .nav-dropdown-wrapper.active .nav-dropdown-arrow {
            transform: rotate(180deg) !important;
            color: #C5A059 !important;
        }

        .luxury-nav-dropdown {
            position: absolute;
            top: calc(100% + 14px);
            left: 50%;
            transform: translateX(-50%) translateY(10px);
            background: #FFFFFF !important;
            border: 1px solid rgba(197, 160, 89, 0.35) !important;
            border-radius: 18px !important;
            box-shadow: 0 16px 45px rgba(0, 0, 0, 0.08), 0 4px 20px rgba(197, 160, 89, 0.15) !important;
            min-width: 330px;
            padding: 10px !important;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
            z-index: 1000 !important;
        }

        .luxury-nav-dropdown::before {
            content: '';
            position: absolute;
            top: -6px;
            left: 50%;
            transform: translateX(-50%) rotate(45deg);
            width: 12px;
            height: 12px;
            background: #FFFFFF;
            border-left: 1px solid rgba(197, 160, 89, 0.35);
            border-top: 1px solid rgba(197, 160, 89, 0.35);
            z-index: 1;
        }

        .luxury-nav-dropdown::after {
            content: '';
            position: absolute;
            top: -18px;
            left: 0;
            right: 0;
            height: 18px;
        }

        .nav-dropdown-wrapper:hover .luxury-nav-dropdown,
        .nav-dropdown-wrapper.active .luxury-nav-dropdown,
        .luxury-nav-dropdown.show {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
            transform: translateX(-50%) translateY(0) !important;
        }

        .dropdown-group-header {
            font-size: 0.72rem !important;
            font-weight: 700 !important;
            color: #9A7B38 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.06em !important;
            padding: 6px 12px 8px 12px !important;
            border-bottom: 1px solid #F1F5F9 !important;
            margin-bottom: 6px !important;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-dropdown-item {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            padding: 9px 12px !important;
            border-radius: 12px !important;
            text-decoration: none !important;
            color: #374151 !important;
            transition: all 0.2s ease !important;
            cursor: pointer !important;
        }

        .nav-dropdown-item:hover {
            background: linear-gradient(135deg, rgba(223, 192, 122, 0.12) 0%, rgba(197, 160, 89, 0.18) 100%) !important;
            transform: translateX(3px) !important;
        }

        .nav-dropdown-item-icon {
            width: 36px !important;
            height: 36px !important;
            border-radius: 10px !important;
            background: #FAF7F2 !important;
            border: 1px solid #EAE2D5 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: #C5A059 !important;
            font-size: 0.95rem !important;
            flex-shrink: 0 !important;
            transition: all 0.2s ease !important;
        }

        .nav-dropdown-item:hover .nav-dropdown-item-icon {
            background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%) !important;
            color: #FFFFFF !important;
            border-color: transparent !important;
            box-shadow: 0 4px 10px rgba(197, 160, 89, 0.3) !important;
        }

        .nav-dropdown-item-info {
            flex: 1 !important;
            min-width: 0 !important;
        }

        .nav-dropdown-item-title {
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            color: #1E293B !important;
            line-height: 1.3 !important;
            white-space: nowrap !important;
        }

        .nav-dropdown-item:hover .nav-dropdown-item-title {
            color: #9B782F !important;
        }

        .nav-dropdown-item-desc {
            font-size: 0.72rem !important;
            color: #94A3B8 !important;
            margin-top: 2px !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        .nav-dropdown-item-arrow {
            font-size: 0.75rem !important;
            color: #CBD5E1 !important;
            transition: all 0.2s ease !important;
            margin-left: auto;
        }

        .nav-dropdown-item:hover .nav-dropdown-item-arrow {
            color: #9B782F !important;
            transform: translateX(2px) !important;
        }

        .nav-dropdown-badge-hot {
            font-size: 0.65rem !important;
            font-weight: 700 !important;
            padding: 2px 7px !important;
            border-radius: 9999px !important;
            background: #DC2626 !important;
            color: #FFFFFF !important;
            margin-left: auto;
        }

        /* =========================================================================
         CHUẨN HÓA CÂN LỀ & CỘT LƯỚI SẢN PHẨM TRANG CHỦ (PERFECT BALANCED GRID)
         ========================================================================= */
        .product-grid-section {
            padding: 60px 0 80px 0 !important;
            background: #FFFFFF !important;
            width: 100% !important;
        }

        .product-grid-section .container {
            width: 100% !important;
            max-width: 1320px !important;
            margin: 0 auto !important;
            padding: 0 24px !important;
            box-sizing: border-box !important;
        }

        .section-header {
            text-align: center !important;
            max-width: 680px !important;
            margin: 0 auto 28px auto !important;
        }

        .grid-filter-tabs {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            gap: 10px !important;
            margin: 0 auto 36px auto !important;
            flex-wrap: wrap !important;
            max-width: 100% !important;
        }

        .filter-tab-btn {
            padding: 8px 18px !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            border-radius: 9999px !important;
            background: #FFFFFF !important;
            border: 1px solid #E5E7EB !important;
            color: #4B5563 !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
        }

        .filter-tab-btn.active,
        .filter-tab-btn:hover {
            background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%) !important;
            color: #FFFFFF !important;
            border-color: transparent !important;
            box-shadow: 0 4px 14px rgba(197, 160, 89, 0.25) !important;
        }

        .products-grid {
            display: grid !important;
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
            gap: 24px !important;
            width: 100% !important;
            margin: 0 auto !important;
            box-sizing: border-box !important;
            justify-content: center !important;
        }

        @media (max-width: 1260px) {
            .products-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
                gap: 20px !important;
            }
        }

        @media (max-width: 860px) {
            .products-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 16px !important;
            }
        }

        @media (max-width: 520px) {
            .products-grid {
                grid-template-columns: minmax(0, 1fr) !important;
                gap: 16px !important;
            }
        }

        .product-card {
            background: #FFFFFF !important;
            border: 1px solid #E5E7EB !important;
            border-radius: 16px !important;
            padding: 16px !important;
            position: relative !important;
            display: flex;
            flex-direction: column !important;
            justify-content: space-between !important;
            height: 100% !important;
            min-width: 0 !important;
            box-sizing: border-box !important;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04) !important;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease !important;
        }

        .product-card.card-hidden {
            display: none !important;
        }

        .product-card:hover {
            transform: translateY(-5px) !important;
            border-color: #C5A059 !important;
            box-shadow: 0 12px 30px rgba(197, 160, 89, 0.16) !important;
        }

        .product-badge-flag {
            position: absolute !important;
            top: 14px !important;
            left: 14px !important;
            font-size: 0.68rem !important;
            font-weight: 700 !important;
            padding: 3px 8px !important;
            border-radius: 6px !important;
            letter-spacing: 0.04em !important;
            z-index: 5 !important;
        }

        .product-img-box {
            width: 100% !important;
            height: 200px !important;
            aspect-ratio: auto !important;
            background: #F8FAFC !important;
            border-radius: 12px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            overflow: hidden !important;
            margin-bottom: 12px !important;
            position: relative !important;
            border: 1px solid #F1F5F9 !important;
        }

        .product-img-box a {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 100% !important;
            height: 100% !important;
        }

        .product-img-box img {
            max-width: 88% !important;
            max-height: 170px !important;
            width: auto !important;
            height: auto !important;
            object-fit: contain !important;
            transition: transform 0.3s ease !important;
        }

        .product-card:hover .product-img-box img {
            transform: scale(1.06) !important;
        }

        .product-category-name {
            font-size: 0.72rem !important;
            font-weight: 600 !important;
            color: #64748B !important;
            margin-bottom: 4px !important;
            text-transform: none !important;
            letter-spacing: 0.04em !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        .product-title {
            font-size: 0.925rem !important;
            font-weight: 700 !important;
            line-height: 1.4 !important;
            min-height: 2.8em !important;
            max-height: 2.8em !important;
            overflow: hidden !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 2 !important;
            -webkit-box-orient: vertical !important;
            margin: 0 0 6px 0 !important;
            color: #111827 !important;
        }

        .product-title a {
            color: inherit !important;
            text-decoration: none !important;
        }

        .product-title a:hover {
            color: #9B782F !important;
        }

        .product-specs {
            font-size: 0.775rem !important;
            color: #64748B !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            margin-bottom: 8px !important;
        }

        .product-rating-box {
            display: flex !important;
            align-items: center !important;
            gap: 5px !important;
            margin-bottom: 10px !important;
        }

        .rating-stars {
            color: #F59E0B !important;
            font-size: 0.72rem !important;
        }

        .sold-count {
            font-size: 0.75rem !important;
            color: #94A3B8 !important;
        }

        .product-price-row {
            display: flex !important;
            align-items: baseline !important;
            justify-content: space-between !important;
            margin-top: auto !important;
            padding-top: 10px !important;
            border-top: 1px dashed #E2E8F0 !important;
        }

        .price-current {
            font-size: 1.05rem !important;
            font-weight: 800 !important;
            color: #9B782F !important;
        }

        .price-old {
            font-size: 0.78rem !important;
            color: #94A3B8 !important;
            text-decoration: line-through !important;
            margin-left: 6px !important;
        }
    </style>
        @if(\Illuminate\Support\Facades\Auth::check())
            @php
                $u = \Illuminate\Support\Facades\Auth::user();
                $userJson = json_encode([
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'role' => $u->role,
                ]);
            @endphp
            <script>try { localStorage.setItem('family_user', JSON.stringify({!! $userJson !!})); localStorage.setItem('auraluxe_user', JSON.stringify({!! $userJson !!})); } catch(e){} </script>
        @else
            <script>try { localStorage.removeItem('family_user'); localStorage.removeItem('auraluxe_user'); } catch(e){} </script>
        @endif
</head>

<body>


    <!-- ==========================================================================
         MAIN HEADER & NAVIGATION
         ========================================================================== -->
    <header class="site-header">
        <div class="container">
            <div class="header-inner">
                <!-- Brand Logo -->
                <a href="index.html" class="brand-logo" id="mainBrandLogo">
                    <img id="dynamicSiteLogo" src="{{ asset('images/logo_web.png') }}" style="max-height: 48px; object-fit: contain; display: none;" alt="Logo FAMILY">
                    <div id="fallbackSiteLogo" style="display: flex; align-items: center; gap: 8px;">
                        <div class="logo-crest">
                            <i class="fa-solid fa-gem"></i>
                        </div>
                        <div class="logo-text">
                            <span class="text-gold-gradient font-serif"
                                style="display:inline; font-size: 1.6rem; letter-spacing:0.04em; font-weight: 800;">FAMILY</span>
                            <span>HOME APPLIANCES</span>
                        </div>
                    </div>
                    <script>
                        (function() {
                            const img = document.getElementById('dynamicSiteLogo');
                            const fallback = document.getElementById('fallbackSiteLogo');
                            const tempImg = new Image();
                            const basePath = window.location.pathname.includes('/webgiadung') ? 'public/images/' : '/images/';
                            tempImg.onload = function() {
                                img.src = tempImg.src;
                                img.style.display = 'block';
                                fallback.style.display = 'none';
                            };
                            tempImg.src = basePath + 'logo_web.png?v=2.2';
                        })();
                    </script>
                </a>

                <!-- Search Bar (Minimalist & Clean with Live Search) -->
                <div class="search-container">
                    <form class="search-bar" id="mainSearchForm" onsubmit="handleSearchSubmit(event)" role="search">
                        <button type="submit" class="search-icon-btn" aria-label="Tìm kiếm" title="Tìm kiếm">
                            <i class="fa-solid fa-magnifying-glass search-icon"></i>
                        </button>
                        <input type="text" id="mainSearchInput" name="search" autocomplete="off"
                            aria-label="Nhập từ khóa tìm kiếm">
                        <button type="button" id="clearSearchBtn" class="search-clear-btn" style="display:none;"
                            onclick="clearSearchInput()" aria-label="Xóa">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </form>
                    <!-- Live Search Results Dropdown -->
                    <div class="live-search-dropdown" id="liveSearchDropdown" style="display:none;"></div>
                </div>

                <!-- Navigation Links -->
                <nav class="header-nav">
                    <a href="#trang-chu" class="nav-link">Trang chủ</a>

                    <!-- 1. Dropdown Nút "Danh mục" -->
                    <div class="nav-dropdown-wrapper" id="navCategoryDropdownWrapper">
                        <a href="#danh-muc" class="nav-link nav-dropdown-toggle" id="navCategoryDropdownToggle" onclick="handleNavCategoryClick(event)">
                            <span>Danh mục</span>
                            <i class="fa-solid fa-chevron-down nav-dropdown-arrow"></i>
                        </a>
                        <div class="luxury-nav-dropdown" id="navCategoryDropdownMenu">
                            <div class="dropdown-group-header">
                                <span><i class="fa-solid fa-layer-group text-gold me-1"></i> Danh mục thiết bị</span>
                                <span style="font-size: 0.65rem; color: #94A3B8; text-transform: none;">Bấm để lọc</span>
                            </div>
                            <a href="#danh-muc" class="nav-dropdown-item" onclick="selectNavCategory('all', event)">
                                <div class="nav-dropdown-item-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
                                <div class="nav-dropdown-item-info">
                                    <div class="nav-dropdown-item-title">Tất cả danh mục</div>
                                    <div class="nav-dropdown-item-desc">Xem toàn bộ thiết bị gia dụng</div>
                                </div>
                                <i class="fa-solid fa-chevron-right nav-dropdown-item-arrow"></i>
                            </a>
                            @foreach($dbCategories as $cat)
                            <a href="#san-pham" class="nav-dropdown-item" onclick="selectNavCategory('{{ $cat->slug }}', event)">
                                <div class="nav-dropdown-item-icon">
                                    @if(str_contains(strtolower($cat->slug), 'chien') || str_contains(strtolower($cat->name), 'chiên'))
                                        <i class="fa-solid fa-fire-burner"></i>
                                    @elseif(str_contains(strtolower($cat->slug), 'bep') || str_contains(strtolower($cat->name), 'bếp'))
                                        <i class="fa-solid fa-kitchen-set"></i>
                                    @elseif(str_contains(strtolower($cat->slug), 'lanh') || str_contains(strtolower($cat->name), 'lạnh'))
                                        <i class="fa-solid fa-snowflake"></i>
                                    @elseif(str_contains(strtolower($cat->slug), 'com') || str_contains(strtolower($cat->name), 'cơm'))
                                        <i class="fa-solid fa-bowl-rice"></i>
                                    @elseif(str_contains(strtolower($cat->slug), 'bui') || str_contains(strtolower($cat->name), 'bụi') || str_contains(strtolower($cat->name), 'robot'))
                                        <i class="fa-solid fa-robot"></i>
                                    @else
                                        <i class="fa-solid fa-tag"></i>
                                    @endif
                                </div>
                                <div class="nav-dropdown-item-info">
                                    <div class="nav-dropdown-item-title">{{ $cat->name }}</div>
                                    <div class="nav-dropdown-item-desc">{{ \Illuminate\Support\Str::limit($cat->description ?? 'Khám phá ngay thiết bị gia dụng cao cấp.', 40) }}</div>
                                </div>
                                <i class="fa-solid fa-chevron-right nav-dropdown-item-arrow"></i>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <a href="#bento-showcase" class="nav-link has-badge">
                        Bộ sưu tập <span class="nav-badge-gold">Bento</span>
                    </a>

                    <!-- 2. Dropdown Nút "Sản phẩm" -->
                    <div class="nav-dropdown-wrapper" id="navProductDropdownWrapper">
                        <a href="#san-pham" class="nav-link nav-dropdown-toggle" id="navProductDropdownToggle" onclick="handleNavProductClick(event)">
                            <span>Sản phẩm</span>
                            <i class="fa-solid fa-chevron-down nav-dropdown-arrow"></i>
                        </a>
                        <div class="luxury-nav-dropdown" id="navProductDropdownMenu">
                            <div class="dropdown-group-header">
                                <span><i class="fa-solid fa-gem text-gold me-1"></i> Các loại sản phẩm</span>
                                <span class="nav-dropdown-badge-hot">HOT</span>
                            </div>
                            <a href="#san-pham" class="nav-dropdown-item" onclick="selectNavProductType('all', event)">
                                <div class="nav-dropdown-item-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
                                <div class="nav-dropdown-item-info">
                                    <div class="nav-dropdown-item-title">Tất cả sản phẩm FAMILY</div>
                                    <div class="nav-dropdown-item-desc">Bộ sưu tập thiết bị gia dụng mới nhất 2026</div>
                                </div>
                                <i class="fa-solid fa-chevron-right nav-dropdown-item-arrow"></i>
                            </a>
                            <a href="#san-pham" class="nav-dropdown-item" onclick="selectNavProductType('noi-chien', event)">
                                <div class="nav-dropdown-item-icon"><i class="fa-solid fa-temperature-arrow-up"></i></div>
                                <div class="nav-dropdown-item-info">
                                    <div class="nav-dropdown-item-title">Các loại nồi chiên không dầu</div>
                                    <div class="nav-dropdown-item-desc">FAMILY Pro OLED, Philips, Sunhouse...</div>
                                </div>
                                <i class="fa-solid fa-chevron-right nav-dropdown-item-arrow"></i>
                            </a>
                            <a href="#san-pham" class="nav-dropdown-item" onclick="selectNavProductType('bep-dien-tu', event)">
                                <div class="nav-dropdown-item-icon"><i class="fa-solid fa-bolt"></i></div>
                                <div class="nav-dropdown-item-info">
                                    <div class="nav-dropdown-item-title">Các loại bếp điện &amp; bếp từ</div>
                                    <div class="nav-dropdown-item-desc">Bếp đôi Inverter Booster, Fixco, Elmix...</div>
                                </div>
                                <i class="fa-solid fa-chevron-right nav-dropdown-item-arrow"></i>
                            </a>
                            <a href="#san-pham" class="nav-dropdown-item" onclick="selectNavProductType('tu-lanh', event)">
                                <div class="nav-dropdown-item-icon"><i class="fa-solid fa-icicles"></i></div>
                                <div class="nav-dropdown-item-info">
                                    <div class="nav-dropdown-item-title">Các dòng tủ lạnh thông minh</div>
                                    <div class="nav-dropdown-item-desc">Smart French-Door 4 cánh 568L...</div>
                                </div>
                                <i class="fa-solid fa-chevron-right nav-dropdown-item-arrow"></i>
                            </a>
                            <a href="#san-pham" class="nav-dropdown-item" onclick="selectNavProductType('noi-com-ih', event)">
                                <div class="nav-dropdown-item-icon"><i class="fa-solid fa-utensils"></i></div>
                                <div class="nav-dropdown-item-info">
                                    <div class="nav-dropdown-item-title">Dòng nồi cơm áp suất IH</div>
                                    <div class="nav-dropdown-item-desc">Nồi cao tần đa năng 1.8L dẻo ngon</div>
                                </div>
                                <i class="fa-solid fa-chevron-right nav-dropdown-item-arrow"></i>
                            </a>
                            <a href="#san-pham" class="nav-dropdown-item" onclick="selectNavProductType('robot-hut-bui', event)">
                                <div class="nav-dropdown-item-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                                <div class="nav-dropdown-item-info">
                                    <div class="nav-dropdown-item-title">Dòng robot hút bụi tự động</div>
                                    <div class="nav-dropdown-item-desc">FAMILY S9 Pro AI lau quét thông minh</div>
                                </div>
                                <i class="fa-solid fa-chevron-right nav-dropdown-item-arrow"></i>
                            </a>
                        </div>
                    </div>

                    <a href="#danh-gia" class="nav-link">Đánh giá</a>
                    <a href="#lien-he" class="nav-link">Liên hệ</a>
                </nav>

                <!-- Header Actions -->
                <div class="header-actions">
                    <!-- Cổng Đăng nhập / Đăng ký / Admin -->
                    <div id="authHeaderContainer">
                        <button type="button" class="action-btn" id="openAuthModalBtn"
                            title="Đăng nhập / Đăng ký tài khoản" onclick="openLoginModal('login')"
                            style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                            <i class="fa-regular fa-circle-user"></i>
                            <span style="font-size: 0.85rem;">Đăng nhập</span>
                        </button>
                    </div>


                    <!-- Giỏ hàng (Cart Drawer Trigger) -->
                    <button class="action-btn cart-trigger" id="cartTriggerBtn" title="Xem giỏ hàng">
                        <i class="fa-solid fa-bag-shopping" style="color: var(--gold-dark);"></i>
                        <span style="font-size: 0.85rem;">Giỏ hàng</span>
                        <span class="cart-counter">0</span>
                    </button>

                    <!-- Mobile Menu Hamburger -->
                    <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Mở Menu">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- ==========================================================================
         1. HERO BANNER SECTION (TRANG CHỦ)
         ========================================================================== -->
    <section class="hero-section" id="trang-chu" style="padding: 20px 0;">
        <div class="container">
            <!-- Full Width Visual Banner -->
            <div class="banner-wrapper" style="width: 100%; border-radius: 24px; overflow: hidden; box-shadow: 0 24px 48px rgba(0,0,0,0.12); position: relative; display: block;">
                <!-- Tỷ lệ khung hình ngang phổ biến cho banner (ví dụ 16:9 hoặc 21:9) -->
                <div style="padding-top: 40%; /* Tương đương tỷ lệ 2.5:1 */"></div>
                <style>
                    .banner-carousel {
                        display: flex;
                        overflow-x: auto;
                        scroll-snap-type: x mandatory;
                        -webkit-overflow-scrolling: touch;
                        width: 100%;
                        height: 100%;
                        position: absolute;
                        top: 0; left: 0;
                    }
                    .banner-carousel::-webkit-scrollbar {
                        display: none;
                    }
                    .banner-slide {
                        flex: 0 0 100%;
                        width: 100%;
                        height: 100%;
                        scroll-snap-align: start;
                        position: relative;
                    }
                    .banner-slide img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        display: block;
                    }
                    .banner-nav-btn {
                        position: absolute;
                        top: 50%;
                        transform: translateY(-50%);
                        background: rgba(255, 255, 255, 0.7);
                        color: #111827;
                        border: none;
                        border-radius: 50%;
                        width: 48px;
                        height: 48px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 1.25rem;
                        cursor: pointer;
                        z-index: 10;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                        transition: all 0.2s;
                        opacity: 0;
                    }
                    .banner-wrapper:hover .banner-nav-btn {
                        opacity: 1;
                    }
                    .banner-nav-btn:hover {
                        background: #FFFFFF;
                        color: #C5A059;
                    }
                    .banner-prev { left: 24px; }
                    .banner-next { right: 24px; }
                    @media (max-width: 768px) {
                        .banner-nav-btn {
                            width: 36px; height: 36px; font-size: 1rem;
                            opacity: 1; /* Always show on mobile */
                        }
                        .banner-prev { left: 12px; }
                        .banner-next { right: 12px; }
                    }
                </style>
                <div class="banner-carousel" id="heroBannerCarousel">
                    <div class="banner-slide" id="slide1"><a href="#san-pham" style="display: block; width: 100%; height: 100%;"><img id="heroBannerImg1" src="" alt="Banner 1"></a></div>
                    <div class="banner-slide" id="slide2" style="display: none;"><img id="heroBannerImg2" src="" alt="Banner 2"></div>
                    <div class="banner-slide" id="slide3" style="display: none;"><img id="heroBannerImg3" src="" alt="Banner 3"></div>
                </div>
                <button type="button" class="banner-nav-btn banner-prev" onclick="window.prevBannerSlide()" aria-label="Previous slide">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button type="button" class="banner-nav-btn banner-next" onclick="window.nextBannerSlide()" aria-label="Next slide">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
                <script>
                    (function() {
                        const basePath = window.location.pathname.includes('/webgiadung') ? 'public/images/' : '/images/';
                        const ts = '2.2';
                        
                        let slideCount = 1;

                        // Setup image 1 (has fallback)
                        const img1 = document.getElementById('heroBannerImg1');
                        img1.onerror = function() {
                            this.onerror = null;
                            this.src = basePath + 'products/air_fryer.jpg';
                        };
                        img1.src = basePath + 'banner_home_1.jpg?v=' + ts;
                        
                        // Setup image 2 (hides if not found)
                        const img2 = document.getElementById('heroBannerImg2');
                        img2.onload = function() { 
                            document.getElementById('slide2').style.display = 'block'; 
                            slideCount = Math.max(slideCount, 2);
                        };
                        img2.src = basePath + 'banner_home_2.jpg?v=' + ts;

                        // Setup image 3 (hides if not found)
                        const img3 = document.getElementById('heroBannerImg3');
                        img3.onload = function() { 
                            document.getElementById('slide3').style.display = 'block'; 
                            slideCount = Math.max(slideCount, 3);
                        };
                        img3.src = basePath + 'banner_home_3.jpg?v=' + ts;

                        // Tự động cuộn lặp vòng sau 4 giây (chỉ cuộn khi người dùng đang xem trang)
                        const carousel = document.getElementById('heroBannerCarousel');
                        let currentSlide = 0;
                        let autoScrollInterval = setInterval(() => {
                            if (slideCount <= 1 || document.hidden) return;
                            currentSlide = (currentSlide + 1) % slideCount;
                            carousel.scrollTo({ left: currentSlide * carousel.offsetWidth, behavior: 'smooth' });
                        }, 4000);

                        window.prevBannerSlide = function() {
                            if (slideCount <= 1) return;
                            clearInterval(autoScrollInterval); // Tạm dừng cuộn tự động khi bấm tay
                            currentSlide = (currentSlide - 1 + slideCount) % slideCount;
                            carousel.scrollTo({ left: currentSlide * carousel.offsetWidth, behavior: 'smooth' });
                        };

                        window.nextBannerSlide = function() {
                            if (slideCount <= 1) return;
                            clearInterval(autoScrollInterval);
                            currentSlide = (currentSlide + 1) % slideCount;
                            carousel.scrollTo({ left: currentSlide * carousel.offsetWidth, behavior: 'smooth' });
                        };

                        // Lắng nghe sự kiện scroll thủ công để đồng bộ currentSlide
                        carousel.addEventListener('scroll', () => {
                            clearTimeout(carousel.scrollTimeout);
                            carousel.scrollTimeout = setTimeout(() => {
                                currentSlide = Math.round(carousel.scrollLeft / carousel.offsetWidth);
                            }, 100);
                        });
                    })();
                </script>
        </div>
    </section>


    <!-- ==========================================================================
         3. BENTO GRID SHOWCASE (FLAGSHIP SHOWCASE)
         ========================================================================== -->
    <section class="bento-section" id="bento-showcase">
        <div class="container">
            <div class="section-header">
                <span class="section-subtitle">Kiến trúc thiết kế Bento Box</span>
                <h2 class="section-title">Tuyệt tác gia dụng <span class="text-gold-gradient font-serif">FAMILY
                        Collection</span></h2>
                <p class="section-desc">
                    Mỗi khối thiết kế đại diện cho một sản phẩm đột phá công nghệ, hoàn thiện với kim loại cao cấp và
                    viền vàng Gold vương giả.
                </p>
            </div>

            <div class="bento-grid">
    @foreach($specialProducts as $index => $sp)
        @php
            $spId = $sp->id;
            $spName = addslashes(htmlspecialchars($sp->name, ENT_QUOTES));
            $spPrice = $sp->price;
            $spPriceFmt = number_format($sp->price, 0, ',', '.') . ' ₫';
            $spImg = $sp->image ?: 'images/products/air_fryer.jpg';
            if (!str_starts_with($spImg, 'http')) {
                if (str_starts_with($spImg, 'products/')) {
                    $spImg = 'storage/' . $spImg;
                }
                $spImg = preg_replace('#^public/#', '', $spImg);
                $spImg = asset(ltrim($spImg, '/'));
            }
        @endphp

        @if($index == 0)
            <div class="bento-card bento-hero" data-product-id="{{$spId}}" data-product-name="{{$spName}}" data-product-price="{{$spPrice}}" data-product-img="{{$spImg}}" onclick="goToProductDetail({{$spId}}, event)" style="cursor: pointer;">
                <div class="bento-inner">
                    <div>
                        <span class="bento-badge-gold">Đặc Biệt</span>
                        <h3 class="bento-card-title"><a href="{{ url('products/'.$spId) }}" style="color: inherit; text-decoration: none;">{{$sp->name}}</a></h3>
                        <p class="bento-card-desc">{{\Illuminate\Support\Str::limit($sp->description ?? 'Sản phẩm cao cấp từ FAMILY', 60)}}</p>
                        <div class="bento-price-wrap"><span class="bento-price">{{$spPriceFmt}}</span></div>
                        <div style="display: flex; gap: 10px; margin-top: 16px;" onclick="event.stopPropagation()">
                            <button type="button" class="btn-buy-now btn-bento-buy" style="flex: 1.2; padding: 10px 20px; font-size: 0.875rem; font-weight: 700; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; border: none; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(197, 160, 89, 0.25);" onclick="proceedToRealCheckout({{$spId}}, '{{$spName}}', {{$spPrice}}, '{{$spImg}}', event)"><i class="fa-solid fa-bolt-lightning"></i> Mua ngay</button>
                            <button type="button" class="btn-outline-gold btn-bento-cart" style="flex: 1; padding: 10px 18px; font-size: 0.875rem; font-weight: 600; background: #FFFFFF; color: #8C6A24; border: 1px solid #EAE2D5; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;" onclick="addToCart({{$spId}}, '{{$spName}}', {{$spPrice}}, '{{$spImg}}')"><i class="fa-solid fa-cart-plus"></i> Giỏ</button>
                        </div>
                    </div>
                    <div class="bento-hero-img-box"><img src="{{$spImg}}" alt="{{$spName}}"></div>
                </div>
            </div>
        @elseif($index == 1)
            <div class="bento-card bento-fridge" data-product-id="{{$spId}}" data-product-name="{{$spName}}" data-product-price="{{$spPrice}}" data-product-img="{{$spImg}}" onclick="goToProductDetail({{$spId}}, event)" style="cursor: pointer;">
                <div>
                    <span class="bento-badge-gold">Tuyệt Tác</span>
                    <h3 class="bento-card-title"><a href="{{ url('products/'.$spId) }}" style="color: inherit; text-decoration: none;">{{$sp->name}}</a></h3>
                    <p class="bento-card-desc">{{\Illuminate\Support\Str::limit($sp->description ?? 'Sản phẩm cao cấp', 50)}}</p>
                </div>
                <div class="bento-media-split">
                    <a href="{{ url('products/'.$spId) }}" style="display: block;"><img src="{{$spImg}}" alt="{{$spName}}"></a>
                    <div>
                        <div class="bento-price" style="font-size: 1.35rem; margin-bottom: 10px;">{{$spPriceFmt}}</div>
                        <div style="display: flex; gap: 8px; margin-top: 4px;" onclick="event.stopPropagation()">
                            <button type="button" class="btn-buy-now btn-bento-buy" style="flex: 1.2; padding: 10px 12px; font-size: 0.825rem; font-weight: 700; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; border: none; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 5px;" onclick="proceedToRealCheckout({{$spId}}, '{{$spName}}', {{$spPrice}}, '{{$spImg}}', event)"><i class="fa-solid fa-bolt-lightning"></i> Mua ngay</button>
                            <button type="button" class="btn-outline-gold btn-bento-cart" style="flex: 1; padding: 10px 10px; font-size: 0.825rem; font-weight: 600; background: #FFFFFF; color: #8C6A24; border: 1px solid #EAE2D5; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 5px;" onclick="addToCart({{$spId}}, '{{$spName}}', {{$spPrice}}, '{{$spImg}}')"><i class="fa-solid fa-cart-plus"></i> Giỏ</button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bento-card bento-span-4" data-product-id="{{$spId}}" data-product-name="{{$spName}}" data-product-price="{{$spPrice}}" data-product-img="{{$spImg}}" onclick="goToProductDetail({{$spId}}, event)" style="cursor: pointer;">
                <div class="card-top-content">
                    <span class="bento-badge-gold">Sang Trọng</span>
                    <h3 class="bento-card-title" style="font-size: 1.2rem;"><a href="{{ url('products/'.$spId) }}" style="color: inherit; text-decoration: none;">{{$sp->name}}</a></h3>
                </div>
                <div class="card-img-container"><a href="{{ url('products/'.$spId) }}" style="display: block;"><img src="{{$spImg}}" alt="{{$spName}}"></a></div>
                <div class="bento-card-footer" style="display: flex; align-items: flex-end; justify-content: space-between; gap: 8px;">
                    <div><span class="bento-price" style="font-size: 1.15rem;">{{$spPriceFmt}}</span></div>
                    <div style="display: flex; gap: 6px;" onclick="event.stopPropagation()">
                        <button type="button" class="btn-buy-now btn-bento-buy" style="padding: 7px 11px; font-size: 0.78rem; font-weight: 700; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; border: none; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;" onclick="proceedToRealCheckout({{$spId}}, '{{$spName}}', {{$spPrice}}, '{{$spImg}}', event)"><i class="fa-solid fa-bolt-lightning"></i> Mua ngay</button>
                        <button type="button" class="btn-outline-gold btn-bento-cart" style="padding: 7px 10px; font-size: 0.78rem; font-weight: 600; background: #FFFFFF; color: #8C6A24; border: 1px solid #EAE2D5; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;" onclick="addToCart({{$spId}}, '{{$spName}}', {{$spPrice}}, '{{$spImg}}')"><i class="fa-solid fa-cart-plus"></i> Giỏ</button>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

        @php
            $promoValue = '500K';
            $promoCode = 'FAMILY500';
            if(file_exists(storage_path('app/settings.json'))) {
                $settings = json_decode(file_get_contents(storage_path('app/settings.json')), true);
                if(isset($settings['promo_value'])) {
                    $promoValue = $settings['promo_value'];
                }
                if(isset($settings['promo_code'])) {
                    $promoCode = $settings['promo_code'];
                }
            }
        @endphp
        <!-- BANNER KHUYẾN MÃI LẤY MÃ GIẢM GIÁ (ĐIỀN VÀO CHỖ TRỐNG 8 COLUMN) -->
        <style>
            .bento-promo {
                grid-column: span 8;
                background: linear-gradient(135deg, #FFFFFF 0%, #FFFDF5 50%, #FEF5DF 100%) !important;
                color: #1C1917;
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                padding: 40px !important;
                border: 1px solid rgba(197, 160, 89, 0.3) !important;
                border-radius: 20px;
                box-shadow: 0 8px 30px rgba(197, 160, 89, 0.08);
            }
            .bento-promo-content {
                flex: 1;
                padding-right: 30px;
            }
            .bento-promo-visual {
                flex: 0 0 30%;
                display: flex;
                justify-content: center;
                align-items: center;
                position: relative;
            }
            @media (max-width: 991px) {
                .bento-promo {
                    grid-column: span 12;
                    flex-direction: column;
                    text-align: center;
                    padding: 30px !important;
                }
                .bento-promo-content {
                    padding-right: 0;
                    margin-bottom: 24px;
                }
            }
        </style>
        <div class="bento-card bento-promo">
            <div class="bento-promo-content">
                <span class="bento-badge-gold" style="margin-bottom: 16px;">Ưu đãi độc quyền</span>
                <p style="color: #57534E; font-size: 0.95rem; margin-bottom: 24px; line-height: 1.6;">Voucher giảm giá cho tất cả khách hàng tham gia vào ngôi nhà FAMILY gia dụng của chúng tôi</p>
                
                <form id="promoForm" onsubmit="event.preventDefault(); document.getElementById('promoForm').style.display='none'; document.getElementById('promoCodeResult').style.display='block';" style="display: flex; gap: 8px; align-items: center; background: #FDFBF7; padding: 6px; border-radius: 9999px; border: 1px solid rgba(197, 160, 89, 0.3);">
                    <input type="email" required placeholder="Nhập email của bạn..." style="flex: 1; background: transparent; border: none; outline: none; color: #1C1917; padding: 10px 16px; font-size: 0.95rem;" />
                    <button type="submit" style="background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: white; border: none; padding: 10px 24px; border-radius: 9999px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s; box-shadow: 0 4px 15px rgba(197, 160, 89, 0.3);">
                        <i class="fa-solid fa-paper-plane"></i> Nhận mã
                    </button>
                </form>

                <div id="promoCodeResult" style="display: none; background: rgba(197, 160, 89, 0.1); border: 1px dashed #C5A059; padding: 15px 20px; border-radius: 12px; margin-top: 15px;">
                    <div style="color: #8C6A24; font-size: 0.9rem; margin-bottom: 5px;"><i class="fa-solid fa-circle-check me-1"></i> Đăng ký thành công! Mã giảm giá của bạn là:</div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 1.5rem; font-weight: 900; color: #8C6A24; letter-spacing: 2px;">{{ $promoCode }}</span>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ $promoCode }}'); this.innerHTML='<i class=\'fa-solid fa-check\'></i> Đã chép'; this.style.color='#8C6A24';" style="background: transparent; border: none; color: #9B782F; font-size: 0.9rem; cursor: pointer; padding: 5px;"><i class="fa-regular fa-copy"></i> Copy</button>
                    </div>
                </div>
            </div>
            <div class="bento-promo-visual">
                <i class="fa-solid fa-ticket" style="font-size: 9rem; color: rgba(197, 160, 89, 0.15); transform: rotate(-15deg);"></i>
            </div>
        </div>

</div>
        </section>


    <!-- ==========================================================================
         4. LƯỚI SẢN PHẨM TOÀN DIỆN (PRODUCT GRID)
         ========================================================================== -->
    <section class="product-grid-section" id="san-pham">
        <div class="container">


            <!-- Filter Tabs -->
            <div class="grid-filter-tabs">
                <button class="filter-tab-btn active" data-category="all">Tất cả sản phẩm</button>
                <button class="filter-tab-btn" data-category="noi-chien">Nồi chiên không dầu</button>
                <button class="filter-tab-btn" data-category="bep-dien-tu">Bếp điện từ</button>
                <button class="filter-tab-btn" data-category="tu-lanh">Tủ lạnh smart</button>
                <button class="filter-tab-btn" data-category="noi-com-ih">Nồi cơm cao tần</button>
                <button class="filter-tab-btn" data-category="robot-hut-bui">Robot hút bụi AI</button>
            </div>

            <!-- Active Search Filter Banner -->
            <div id="activeSearchBanner" style="display: none;"></div>

            <!-- Grid Items -->
            <div class="products-grid">
    @php
        $cardIndex = 0;
    @endphp
    @foreach($dbProducts as $p)
        @php
            $catName = 'Gia dụng thông minh';
            if (is_object($p->category) && isset($p->category->name)) {
                $catName = $p->category->name;
            } elseif (is_string($p->category) && !empty($p->category)) {
                $catName = $p->category;
            } elseif (!empty($p->attributes['category'])) {
                $catName = $p->attributes['category'];
            }
            $catName = \App\Http\Controllers\ProductController::formatSentenceCase($catName);

            $lowerText = mb_strtolower($catName . ' ' . $p->name, 'UTF-8');
            $catSlug = 'all';
            if (str_contains($lowerText, 'chiên')) { $catSlug = 'noi-chien'; }
            elseif (str_contains($lowerText, 'bếp') || str_contains($lowerText, 'từ')) { $catSlug = 'bep-dien-tu'; }
            elseif (str_contains($lowerText, 'lạnh') || str_contains($lowerText, 'mát') || str_contains($lowerText, 'tủ')) { $catSlug = 'tu-lanh'; }
            elseif (str_contains($lowerText, 'cơm') || str_contains($lowerText, 'cao tần')) { $catSlug = 'noi-com-ih'; }
            elseif (str_contains($lowerText, 'bụi') || str_contains($lowerText, 'robot') || str_contains($lowerText, 'làm sạch')) { $catSlug = 'robot-hut-bui'; }

            $img = $p->image ?: 'images/products/air_fryer.jpg';
            if (!str_starts_with($img, 'http')) {
                if (str_starts_with($img, 'products/')) {
                    $img = 'storage/' . $img;
                }
                $img = preg_replace('#^public/#', '', $img);
                $img = asset(ltrim($img, '/'));
            }
            $priceFmt = number_format($p->price, 0, ',', '.') . ' ₫';
            $oldPriceFmt = $p->price > 0 ? number_format(round($p->price * 1.2 / 10000) * 10000, 0, ',', '.') . ' ₫' : '';
            $specs = $p->description ? \Illuminate\Support\Str::limit($p->description, 55, '...') : 'Bảo hành chính hãng 24T';
            $pId = (int)$p->id;
            $pNameEsc = htmlspecialchars(\App\Http\Controllers\ProductController::formatSentenceCase($p->name), ENT_QUOTES, 'UTF-8');
            $stock = $p->quantity ?? $p->stock ?? 10;
            $brandEsc = htmlspecialchars($p->brand ?: 'Chính hãng', ENT_QUOTES, 'UTF-8');
            $originEsc = htmlspecialchars($p->origin ?: 'Việt Nam', ENT_QUOTES, 'UTF-8');
            $materialEsc = htmlspecialchars($p->material ?: 'Hợp kim & Nhựa ABS', ENT_QUOTES, 'UTF-8');
            $usageEsc = htmlspecialchars(\App\Http\Controllers\ProductController::formatSentenceCase($p->usage ?: 'Gia dụng thông minh gia đình'), ENT_QUOTES, 'UTF-8');
            $detailUrl = url('products/' . $pId);

            $hideStyle = ($cardIndex >= 8) ? 'style="display: none; cursor: pointer;"' : 'style="cursor: pointer;"';
            $cardIndex++;
        @endphp

        <div class="product-card" data-category="{{$catSlug}}" data-product-id="{{$pId}}" data-product-name="{{$pNameEsc}}" data-product-price="{{$p->price}}" data-product-img="{{$img}}" data-product-brand="{{$brandEsc}}" data-product-origin="{{$originEsc}}" data-product-material="{{$materialEsc}}" data-product-usage="{{$usageEsc}}" onclick="goToProductDetail({{$pId}}, event)" {!! $hideStyle !!}>
            <span class="product-badge-flag badge-hot">Chính hãng</span>
            <div class="product-img-box">
                <a href="{{$detailUrl}}" onclick="goToProductDetail({{$pId}}, event)" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                    <img src="{{$img}}" alt="{{$pNameEsc}}" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('images/products/air_fryer.jpg') }}'">
                </a>
                <div class="card-quick-actions" onclick="event.stopPropagation()">
                    <button type="button" class="quick-action-btn" title="Xem thông số kỹ thuật" onclick="openProductQuickSpecModal({{$pId}})"><i class="fa-regular fa-eye"></i></button>
                    <button type="button" class="quick-action-btn" title="Yêu thích" onclick="showToast('Đã lưu vào danh sách yêu thích!')"><i class="fa-regular fa-heart"></i></button>
                </div>
            </div>
            <div class="product-category-name">{{htmlspecialchars($catName, ENT_QUOTES, 'UTF-8')}} • <span style="color: #9B782F; font-weight: 600;">{{$brandEsc}}</span></div>
            <h4 class="product-title" title="{{$pNameEsc}}">
                <a href="{{$detailUrl}}" onclick="goToProductDetail({{$pId}}, event)" style="color: inherit; text-decoration: none;">{{$pNameEsc}}</a>
            </h4>
            <div class="product-specs">
                <span><i class="fa-solid fa-earth-americas text-gold" style="font-size: 0.75rem;"></i> {{$originEsc}}</span>
                <span style="margin: 0 4px; color: #CBD5E1;">|</span>
                <span><i class="fa-solid fa-gem text-gold" style="font-size: 0.75rem;"></i> {{\Illuminate\Support\Str::limit($materialEsc, 24, '...')}}</span>
            </div>
            <div class="product-rating-box">
                <div class="rating-stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i></div>
                <span class="sold-count">| Còn lại {{$stock}} sp</span>
            </div>
            <div class="product-price-row">
                <div>
                    <span class="price-current">{{$priceFmt}}</span>
                    @if($oldPriceFmt) <span class="price-old">{{$oldPriceFmt}}</span> @endif
                </div>
                <span style="font-size: 0.75rem; color: #DC2626; font-weight: 700;">-18%</span>
            </div>
            <div style="display: flex; gap: 8px; margin-top: 12px;" onclick="event.stopPropagation()">
                <button type="button" class="btn-buy-now" style="flex: 1.2; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 10px 12px; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; font-weight: 700; font-size: 0.85rem; border: none; border-radius: 9999px; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(197, 160, 89, 0.25);" onclick="event.stopPropagation(); if (typeof proceedToRealCheckout === 'function') proceedToRealCheckout({{$pId}}, '{{addslashes($pNameEsc)}}', {{$p->price}}, '{{$img}}', event)">
                    <i class="fa-solid fa-bolt-lightning"></i> Mua ngay
                </button>
                <button type="button" class="btn-add-cart" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 9px 10px; background: #FFFFFF; color: #8C6A24; border: 1px solid #EAE2D5; font-weight: 600; font-size: 0.825rem; border-radius: 9999px; cursor: pointer; transition: all 0.2s;" onclick="event.stopPropagation(); if (typeof addToCart === 'function') addToCart({{$pId}}, '{{addslashes($pNameEsc)}}', {{$p->price}}, '{{$img}}', event)">
                    <i class="fa-solid fa-cart-plus"></i> Giỏ
                </button>
            </div>
        </div>
    @endforeach
</div>
        <div class="products-pagination-container" id="productsPagination"></div>
        </div>
    </section>

    <!-- ==========================================================================
         5. CAM KẾT VÀNG & ĐẶC QUYỀN DỊCH VỤ
         ========================================================================== -->
    <section class="commitments-section" id="cam-ket">
        <div class="container">
            <div class="commitments-grid">
                <div class="commitment-item">
                    <div class="commitment-icon-wrap"><i class="fa-solid fa-shield-halved"></i></div>
                    <div class="commitment-info">
                        <h4>Bảo hành 24 tháng</h4>
                        <p>Kích hoạt điện tử nhanh chóng, hỗ trợ kỹ thuật viên sửa chữa tận nhà trong 24h.</p>
                    </div>
                </div>
                <div class="commitment-item">
                    <div class="commitment-icon-wrap"><i class="fa-solid fa-arrow-rotate-left"></i></div>
                    <div class="commitment-info">
                        <h4>1 đổi 1 trong 30 ngày</h4>
                        <p>Đổi mới sản phẩm hoàn toàn miễn phí nếu phát sinh lỗi từ nhà sản xuất.</p>
                    </div>
                </div>
                <div class="commitment-item">
                    <div class="commitment-icon-wrap"><i class="fa-solid fa-truck-fast"></i></div>
                    <div class="commitment-info">
                        <h4>Giao hàng &amp; lắp đặt 2h</h4>
                        <p>Miễn phí giao hàng toàn quốc. Lắp đặt và hướng dẫn tận tình bởi kỹ sư FAMILY.</p>
                    </div>
                </div>
                <div class="commitment-item">
                    <div class="commitment-icon-wrap"><i class="fa-solid fa-gem"></i></div>
                    <div class="commitment-info">
                        <h4>Chính hãng 100% hoàn tiền</h4>
                        <p>Cam kết 100% sản phẩm có nguồn gốc chuẩn châu Âu & Nhật Bản, chứng từ CO/CQ đầy đủ.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================================================
         6. ĐÁNH GIÁ CỦA KHÁCH HÀNG (TESTIMONIALS)
         ========================================================================== -->
    <section class="reviews-section" id="danh-gia">
        <div class="container">
            <div class="section-header" style="position: relative;">
                <span class="section-subtitle">Đánh giá chân thực</span>
                <h2 class="section-title">Khách hàng nói gì về <span class="text-gold-gradient font-serif">FAMILY
                        LUXE</span></h2>
                <p class="section-desc">
                    Hơn 15.000 gia đình và căn hộ cao cấp trên cả nước đã tin tưởng lựa chọn sản phẩm của chúng tôi.
                </p>
            </div>

            <!-- Khung Viết Đánh Giá Tinh Gọn (Chỉ Đánh Giá Sao & Nội Dung Bình Luận) -->
            <div class="compact-review-box" id="compactReviewBox" style="max-width: 680px; margin: 0 auto 30px; background: #FFFFFF; border: 1px solid #EAE2D5; border-radius: 16px; padding: 18px 22px; box-shadow: 0 4px 18px rgba(0,0,0,0.03);">
                <form id="compactReviewForm" onsubmit="submitCompactReview(event)">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 0.85rem; font-weight: 700; color: #1E293B;">Đánh giá:</span>
                            <div class="compact-star-picker" id="compactStarPicker" style="display: flex; gap: 4px; font-size: 1.25rem; color: #F59E0B; cursor: pointer;">
                                <i class="fa-solid fa-star" data-val="1" onclick="setCompactStars(1)"></i>
                                <i class="fa-solid fa-star" data-val="2" onclick="setCompactStars(2)"></i>
                                <i class="fa-solid fa-star" data-val="3" onclick="setCompactStars(3)"></i>
                                <i class="fa-solid fa-star" data-val="4" onclick="setCompactStars(4)"></i>
                                <i class="fa-solid fa-star" data-val="5" onclick="setCompactStars(5)"></i>
                            </div>
                            <span id="compactStarLabel" style="font-size: 0.8rem; font-weight: 700; color: #B45309; background: #FFFBEB; padding: 2px 10px; border-radius: 9999px; border: 1px solid #FDE68A;">5/5 sao (Xuất sắc)</span>
                        </div>
                        <button type="submit" id="btnSubmitCompactReview" class="btn btn-gold" style="padding: 7px 20px; font-size: 0.85rem; font-weight: 700; border-radius: 9999px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(197, 160, 89, 0.25);">
                            <i class="fa-solid fa-paper-plane"></i> Gửi bình luận
                        </button>
                    </div>
                    <div>
                        <textarea id="compactReviewComment" required minlength="3" rows="2" placeholder="Nhập nội dung bình luận của bạn tại đây... (Tối thiểu 3 ký tự)" style="width: 100%; box-sizing: border-box; padding: 10px 14px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 0.875rem; outline: none; resize: none; font-family: inherit; line-height: 1.5;"></textarea>
                    </div>
                </form>
            </div>



            <div class="reviews-grid" id="reviewsGrid">
                <div class="review-card">
                    <div>
                        <div class="review-top-meta">
                            <div class="review-stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i></div>
                            <span class="review-date">3 ngày trước</span>
                        </div>
                        <div class="review-product-tag"><i class="fa-solid fa-check-circle" style="color: #10B981;"></i>
                            Đã mua: Nồi Chiên Không Dầu FAMILY Pro OLED 12L</div>
                        <p class="review-body">"Thiết kế màu đen viền vàng sang trọng xuất sắc, đặt vào căn bếp phong
                            cách tân cổ điển cực kỳ hợp. Nồi 12L nướng cả con gà chín vàng đều mà không bị khô. Rất hài
                            lòng với dịch vụ!"</p>
                    </div>
                    <div class="review-author">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80"
                            alt="Nguyễn Thu Huyền" class="review-avatar">
                        <div class="review-author-info">
                            <h5>Nguyễn Thu Huyền</h5>
                            <span>Chủ căn hộ Vinhomes Metropolis, Hà Nội</span>
                        </div>
                    </div>
                </div>

                <div class="review-card">
                    <div>
                        <div class="review-top-meta">
                            <div class="review-stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i></div>
                            <span class="review-date">1 tuần trước</span>
                        </div>
                        <div class="review-product-tag"><i class="fa-solid fa-check-circle" style="color: #10B981;"></i>
                            Đã mua: Bếp Từ Đôi Inverter Booster 4400W</div>
                        <p class="review-body">"Mặt kính Schott Ceran vát cạnh kim loại vàng cực kỳ tinh tế. Khách hàng
                            của mình ai ghé thăm cũng khen căn bếp sang trọng như resort. Đun sôi 1 lít nước chỉ mất
                            chưa tới 2 phút."</p>
                    </div>
                    <div class="review-author">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80"
                            alt="Trần Quang Dũng" class="review-avatar">
                        <div class="review-author-info">
                            <h5>Trần Quang Dũng</h5>
                            <span>Kiến trúc sư nội thất, TP. Hồ Chí Minh</span>
                        </div>
                    </div>
                </div>

                <div class="review-card">
                    <div>
                        <div class="review-top-meta">
                            <div class="review-stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i></div>
                            <span class="review-date">2 tuần trước</span>
                        </div>
                        <div class="review-product-tag"><i class="fa-solid fa-check-circle" style="color: #10B981;"></i>
                            Đã mua: Tủ Lạnh Smart French-Door 568L</div>
                        <p class="review-body">"Ngăn đông mềm -3°C là cứu tinh cho mẹ bận rộn như mình, thịt cá lấy ra
                            nấu ngay không cần rã đông. Cửa kính gõ 2 lần sáng đèn rất hiện đại. Đóng gói và giao hàng
                            10/10!"</p>
                    </div>
                    <div class="review-author">
                        <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=150&q=80"
                            alt="Lê Mai Phương" class="review-avatar">
                        <div class="review-author-info">
                            <h5>Lê Mai Phương</h5>
                            <span>Blogger Ẩm Thực Gia Đình, Đà Nẵng</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================================================
         7. VIP NEWSLETTER & VOUCHER 500.000Đ
         ========================================================================== -->
    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter-box">
                <div class="newsletter-text">
                    <span class="newsletter-badge">
                        <i class="fa-solid fa-gift"></i> Đặc quyền thành viên mới
                    </span>
                    <h3 class="newsletter-title font-serif">
                        Đăng ký nhận ngay <span class="text-gold-gradient">voucher 500.000 ₫</span>
                    </h3>
                    <p class="newsletter-desc">
                        Nhận thông báo độc quyền về các bộ sưu tập thiết bị gia dụng mới nhất và ưu đãi giảm giá lên tới
                        35% mỗi tháng.
                    </p>
                </div>

                <form class="newsletter-form" id="newsletterForm">
                    <input type="email" placeholder="Nhập địa chỉ email của quý khách..." required>
                    <button type="submit" class="btn-gold" style="padding: 12px 24px; font-size: 0.9rem;">
                        Nhận mã VIP
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- ==========================================================================
         FOOTER SECTION
         ========================================================================== -->
    <footer class="site-footer" id="lien-he">
        <div class="container">
            <div class="footer-grid">
                <!-- Col 1: Brand & Sứ mệnh -->
                <div class="footer-col-brand">
                    <a href="index.html" class="brand-logo" style="margin-bottom: 16px;">
                        <div class="logo-crest">
                            <i class="fa-solid fa-gem"></i>
                        </div>
                        <div class="logo-text">
                            FAMILY<span class="text-gold-gradient font-serif"
                                style="display:inline; font-size: 1.5rem;">LUXE</span>
                            <span style="color: var(--text-muted);">EST. 2026</span>
                        </div>
                    </a>
                    <p class="footer-desc">
                        FAMILY LUXE là thương hiệu tiên phong phân phối các dòng thiết bị gia dụng thông minh cao cấp với
                        thiết kế Minimalism kết hợp ánh vàng Gold vương giả, kiến tạo không gian sống đẳng cấp cho mọi
                        gia đình Việt.
                    </p>
                    <div class="footer-social-links">
                        <a href="#" class="social-icon-btn" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="social-icon-btn" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="social-icon-btn" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
                        <a href="#" class="social-icon-btn" title="TikTok"><i class="fa-brands fa-tiktok"></i></a>
                    </div>
                </div>

                <!-- Col 2: Hệ thống chính sách -->
                <div class="footer-col">
                    <h4 class="footer-col-title">Chính sách &amp; hỗ trợ</h4>
                    <ul class="footer-links-list">
                        <li><a href="#chinh-sach"><i class="fa-solid fa-angle-right"
                                    style="font-size:0.7rem; color:var(--gold-primary);"></i> Chính sách bảo hành 24
                                tháng</a></li>
                        <li><a href="#chinh-sach"><i class="fa-solid fa-angle-right"
                                    style="font-size:0.7rem; color:var(--gold-primary);"></i> Chính sách 1 đổi 1 trong
                                30 ngày</a></li>
                        <li><a href="#chinh-sach"><i class="fa-solid fa-angle-right"
                                    style="font-size:0.7rem; color:var(--gold-primary);"></i> Vận chuyển &amp; lắp đặt tận
                                nhà</a></li>
                        <li><a href="#chinh-sach"><i class="fa-solid fa-angle-right"
                                    style="font-size:0.7rem; color:var(--gold-primary);"></i> Hướng dẫn kích hoạt bảo
                                hành điện tử</a></li>
                        <li><a href="#chinh-sach"><i class="fa-solid fa-angle-right"
                                    style="font-size:0.7rem; color:var(--gold-primary);"></i> Cam kết bảo mật thông
                                tin</a></li>
                    </ul>
                </div>

                <!-- Col 3: Danh mục nổi bật -->
                <div class="footer-col">
                    <h4 class="footer-col-title">Thiết bị nổi bật</h4>
                    <ul class="footer-links-list">
                        <li><a href="#san-pham"><i class="fa-solid fa-angle-right"
                                    style="font-size:0.7rem; color:var(--gold-primary);"></i> Nồi chiên không dầu OLED
                                12L</a></li>
                        <li><a href="#san-pham"><i class="fa-solid fa-angle-right"
                                    style="font-size:0.7rem; color:var(--gold-primary);"></i> Bếp từ đôi Inverter Schott
                                Ceran</a></li>
                        <li><a href="#san-pham"><i class="fa-solid fa-angle-right"
                                    style="font-size:0.7rem; color:var(--gold-primary);"></i> Tủ lạnh French-Door 4 cánh
                                Smart</a></li>
                        <li><a href="#san-pham"><i class="fa-solid fa-angle-right"
                                    style="font-size:0.7rem; color:var(--gold-primary);"></i> Nồi cơm điện áp suất cao
                                tần IH</a></li>
                        <li><a href="#san-pham"><i class="fa-solid fa-angle-right"
                                    style="font-size:0.7rem; color:var(--gold-primary);"></i> Robot hút bụi lau nhà AI
                                tự giặt sấy</a></li>
                    </ul>
                </div>

                <!-- Col 4: Liên hệ & Showroom -->
                <div class="footer-col">
                    <h4 class="footer-col-title">Showroom &amp; liên hệ</h4>
                    <div class="footer-contact-item">
                        <i class="fa-solid fa-headset"></i>
                        <div>
                            <strong>Tổng đài tư vấn VIP 24/7:</strong><br>
                            <span style="color: var(--gold-dark); font-size: 1.1rem; font-weight: 700;"><a
                                     href="tel:0886543518"
                                    style="color: inherit; text-decoration: none;">0886.543.518</a></span> (Miễn phí)
                        </div>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fa-solid fa-envelope"></i>
                        <div>
                            <strong>Email hỗ trợ:</strong><br>
                            <span>contact@family.vn</span>
                        </div>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fa-solid fa-shop"></i>
                        <div>
                            <strong>Showroom Hà Nội:</strong><br>
                            <span>Tòa nhà Vincom Center, 54A Nguyễn Chí Thanh, Q. Đống Đa</span>
                        </div>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fa-solid fa-shop"></i>
                        <div>
                            <strong>Showroom TP. Hồ Chí Minh:</strong><br>
                            <span>Landmark 81, 720A Điện Biên Phủ, P. 22, Q. Bình Thạnh</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom Bar -->
            <div class="footer-bottom-bar">
                <div style="font-size: 0.8125rem;">
                    © 2026 <strong>FAMILY APPLIANCES</strong>. All Rights Reserved. Bản quyền thuộc về thương hiệu gia
                    dụng cao cấp FAMILY.
                </div>
                <div class="payment-badges">
                    <i class="fa-brands fa-cc-visa" title="Visa"></i>
                    <i class="fa-brands fa-cc-mastercard" title="Mastercard"></i>
                    <i class="fa-solid fa-credit-card" title="VNPay / Thẻ ATM"></i>
                    <i class="fa-solid fa-wallet" title="Ví MoMo / ZaloPay"></i>
                    <i class="fa-solid fa-hand-holding-dollar" title="Trả góp 0%"></i>
                </div>
            </div>
        </div>
    </footer>

    <!-- ==========================================================================
         CART DRAWER MODAL
         ========================================================================== -->
    <div class="cart-drawer-backdrop" id="cartBackdrop"></div>
    <div class="cart-drawer" id="cartDrawer">
        <div class="cart-drawer-header">
            <h3><i class="fa-solid fa-bag-shopping" style="color: var(--gold-dark); margin-right: 8px;"></i> Giỏ hàng
                của bạn</h3>
            <button class="cart-close-btn" id="cartCloseBtn" aria-label="Đóng giỏ hàng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="cart-items-body" id="cartItemsContainer">
            <div class="cart-empty-message">
                <i class="fa-solid fa-basket-shopping"></i>
                <p>Giỏ hàng của quý khách hiện đang trống.</p>
                <span style="font-size: 0.8rem; color: #9CA3AF; margin-top: 6px; display: block;">Hãy chọn những thiết
                    bị gia dụng đẳng cấp để nhận ưu đãi!</span>
            </div>
        </div>

        <div class="cart-drawer-footer">
            <div class="cart-subtotal-row">
                <span>Tạm tính:</span>
                <span class="cart-subtotal-price" id="cartSubtotal">0 ₫</span>
            </div>
            <button class="btn-gold"
                style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px;"
                onclick="syncCartAndGoToCheckout()">
                <i class="fa-solid fa-credit-card"></i> Tiến hành đặt hàng &amp; thanh toán
            </button>
            <a href="javascript:void(0)" onclick="syncCartAndGoToCheckout()"
                style="display: block; text-align: center; margin-top: 10px; font-size: 0.85rem; color: #8C6A24; text-decoration: underline; font-weight: 600; cursor: pointer;">
                <i class="fa-solid fa-bag-shopping"></i> Mở trang chi tiết giỏ hàng
            </a>
        </div>
    </div>

    <!-- TOAST CONTAINER -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- ==========================================================================
         AUTHENTICATION MODAL (DUAL TABS: LOGIN & REGISTER)
         ========================================================================== -->
    <div class="cart-drawer-backdrop" id="loginModalBackdrop"
        style="display: none; align-items: center; justify-content: center; z-index: 1000;"
        onclick="closeLoginModal(event)">
        <div class="auth-card" id="loginModalCard"
            style="max-width: 480px; width: 92%; position: relative; margin: 20px; animation: modalFadeIn 0.3s ease; max-height: 90vh; overflow-y: auto;"
            onclick="event.stopPropagation()">
            <button type="button" class="cart-close-btn" onclick="closeLoginModal()"
                style="position: absolute; right: 20px; top: 20px; background: none; border: none; font-size: 1.25rem; cursor: pointer; color: #64748B;">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <!-- Hộp cảnh báo yêu cầu đăng nhập khi ấn Mua Hàng -->
            <div id="authModalNotice"
                style="display: none; padding: 12px 16px; margin-bottom: 20px; background: #FFFBEB; border: 1.5px solid #F59E0B; border-radius: 12px; color: #92400E; font-size: 0.875rem; font-weight: 700; text-align: center; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.15);">
                <i class="fa-solid fa-lock" style="margin-right: 6px; color: #D97706;"></i>
                <span id="authModalNoticeText">Quý khách vui lòng đăng nhập tài khoản để tiến hành mua hàng!</span>
            </div>

            <!-- Dual Tab Selector -->
            <div
                style="display: flex; background: #F1F5F9; border-radius: 12px; padding: 4px; margin-bottom: 24px; gap: 4px;">
                <button type="button" id="tabLoginBtn" onclick="switchAuthTab('login')"
                    style="flex: 1; padding: 10px; border-radius: 8px; border: none; font-weight: 700; font-size: 0.875rem; cursor: pointer; transition: all 0.2s ease; background: #FFFFFF; color: #0F172A; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
                    <i class="fa-solid fa-right-to-bracket"></i> Đăng nhập
                </button>
                <button type="button" id="tabRegisterBtn" onclick="switchAuthTab('register')"
                    style="flex: 1; padding: 10px; border-radius: 8px; border: none; font-weight: 700; font-size: 0.875rem; cursor: pointer; transition: all 0.2s ease; background: transparent; color: #64748B;">
                    <i class="fa-solid fa-user-plus"></i> Đăng ký
                </button>
            </div>

            <!-- TAB 1: LOGIN CONTENT -->
            <div id="loginTabPanel">
                <!-- Brand Crest -->
                <div class="auth-header" style="margin-bottom: 22px;">
                    <div class="auth-logo-badge"
                        style="width: 52px; height: 52px; font-size: 1.3rem; margin-bottom: 12px;">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h2 class="auth-title" style="font-size: 1.45rem; margin-bottom: 0;">Đăng nhập tài khoản</h2>
                </div>

                <!-- Login Form -->
                <form onsubmit="handleStaticLogin(event)" class="auth-form">
                    <div class="form-group" style="margin-bottom: 14px;">
                        <label for="staticEmail" class="form-label" style="font-size: 0.8125rem;">
                            <i class="fa-regular fa-envelope"></i> Email
                        </label>
                        <input type="email" id="staticEmail" class="form-control" placeholder="name@example.com"
                            required style="padding: 10px 14px; font-size: 0.9rem;">
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <div class="form-label-row">
                            <label for="staticPassword" class="form-label" style="font-size: 0.8125rem;">
                                <i class="fa-solid fa-lock"></i> Mật khẩu
                            </label>
                        </div>
                        <div class="password-wrap">
                            <input type="password" id="staticPassword" class="form-control" placeholder="••••••••"
                                required style="padding: 10px 14px; font-size: 0.9rem;">
                            <button type="button" class="toggle-password-btn"
                                onclick="toggleStaticPassword('staticPassword', 'staticEyeIcon')">
                                <i class="fa-regular fa-eye" id="staticEyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-gold btn-block auth-submit-btn"
                        style="padding: 12px; font-size: 0.95rem;">
                        <span>Đăng nhập ngay</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

                <div class="auth-card-footer" style="margin-top: 18px; padding-top: 14px; font-size: 0.85rem;">
                    <p style="margin-bottom: 0;">Chưa có tài khoản? <a href="javascript:void(0)"
                            onclick="switchAuthTab('register')" class="text-gold-bold">Đăng ký tài khoản ngay</a></p>
                </div>
            </div>

            <!-- TAB 2: REGISTER CONTENT -->
            <div id="registerTabPanel" style="display: none;">
                <!-- Brand Crest -->
                <div class="auth-header" style="margin-bottom: 18px;">
                    <div class="auth-logo-badge"
                        style="width: 48px; height: 48px; font-size: 1.2rem; margin-bottom: 10px;">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <h2 class="auth-title" style="font-size: 1.35rem; margin-bottom: 4px;">Đăng ký tài khoản</h2>
                    <p class="auth-subtitle" style="font-size: 0.8125rem;">Tạo tài khoản để mua sắm và quản lý đơn hàng
                    </p>
                </div>

                <!-- Registration Form -->
                <form onsubmit="handleStaticRegister(event)" class="auth-form" id="staticRegisterForm">
                    <div class="form-group" style="margin-bottom: 12px;">
                        <label for="regName" class="form-label" style="font-size: 0.8125rem;">
                            <i class="fa-regular fa-user"></i> Họ và tên
                        </label>
                        <input type="text" id="regName" class="form-control" placeholder="Nhập họ và tên..." required
                            style="padding: 10px 14px; font-size: 0.9rem;">
                    </div>

                    <div class="form-grid-2" style="margin-bottom: 12px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="regPhone" class="form-label" style="font-size: 0.8125rem;">
                                <i class="fa-solid fa-phone"></i> Số điện thoại
                            </label>
                            <input type="tel" id="regPhone" class="form-control" placeholder="Nhập số điện thoại..." required
                                style="padding: 10px 14px; font-size: 0.9rem;">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="regEmail" class="form-label" style="font-size: 0.8125rem;">
                                <i class="fa-regular fa-envelope"></i> Địa chỉ Email
                            </label>
                            <input type="email" id="regEmail" class="form-control" placeholder="Nhập địa chỉ email..." required
                                style="padding: 10px 14px; font-size: 0.9rem;">
                        </div>
                    </div>

                    <div class="form-grid-2" style="margin-bottom: 14px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="regPassword" class="form-label" style="font-size: 0.8125rem;">
                                <i class="fa-solid fa-lock"></i> Mật khẩu (≥ 6 ký tự)
                            </label>
                            <input type="password" id="regPassword" class="form-control" placeholder="••••••••" required
                                minlength="6" style="padding: 10px 14px; font-size: 0.9rem;">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="regPasswordConfirm" class="form-label" style="font-size: 0.8125rem;">
                                <i class="fa-solid fa-shield-halved"></i> Xác nhận lại
                            </label>
                            <input type="password" id="regPasswordConfirm" class="form-control" placeholder="••••••••"
                                required minlength="6" style="padding: 10px 14px; font-size: 0.9rem;">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-gold btn-block auth-submit-btn"
                        style="padding: 12px; font-size: 0.95rem; margin-top: 6px;">
                        <span>Đăng ký tài khoản</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

                <div class="auth-card-footer" style="margin-top: 16px; padding-top: 12px; font-size: 0.825rem;">
                    <p>Đã có tài khoản? <a href="javascript:void(0)" onclick="switchAuthTab('login')"
                            class="text-gold-bold">Đăng nhập tại đây</a></p>
                </div>
                <!-- =========================================================================
         MODAL 1: XEM CHI TIẾT THÔNG SỐ SẢN PHẨM (XUẤT XỨ, GIÁ, CÔNG DỤNG, CHẤT LIỆU, THƯƠNG HIỆU)
         ========================================================================= -->
                <div class="cart-drawer-backdrop" id="quickSpecModalBackdrop"
                    style="display: none; align-items: center; justify-content: center; z-index: 1050;"
                    onclick="closeProductQuickSpecModal(event)">
                    <div class="auth-card" id="quickSpecModalCard"
                        style="max-width: 620px; width: 92%; position: relative; margin: 20px; animation: modalFadeIn 0.3s ease; max-height: 90vh; overflow-y: auto; border-radius: 20px; padding: 26px;"
                        onclick="event.stopPropagation()">
                        <button type="button" class="cart-close-btn" onclick="closeProductQuickSpecModal()"
                            style="position: absolute; right: 20px; top: 20px; background: none; border: none; font-size: 1.25rem; cursor: pointer; color: #64748B;">
                            <i class="fa-solid fa-xmark"></i>
                        </button>

                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                            <span class="badge"
                                style="background: linear-gradient(135deg, #DFC07A, #C5A059); color: #fff; padding: 4px 10px; border-radius: 9999px; font-weight: 700; font-size: 0.75rem;">Chính
                                hãng</span>
                            <span id="quickSpecCategory"
                                style="font-size: 0.85rem; color: #64748B; font-weight: 600;">Gia dụng thông minh</span>
                        </div>

                        <h3 id="quickSpecTitle"
                            style="font-size: 1.3rem; font-weight: 800; color: #0F172A; margin-bottom: 16px; line-height: 1.3;">
                            Tên sản phẩm</h3>

                        <div
                            style="display: grid; grid-template-columns: 1fr 1.3fr; gap: 18px; margin-bottom: 20px; align-items: center;">
                            <div
                                style="background: #F8FAFC; border-radius: 14px; padding: 14px; display: flex; align-items: center; justify-content: center; border: 1px solid #E2E8F0; min-height: 160px;">
                                <img id="quickSpecImage" src="" alt="Product"
                                    style="max-height: 150px; max-width: 100%; object-fit: contain;">
                            </div>
                            <div>
                                <div
                                    style="background: #FEF3C7; padding: 10px 14px; border-radius: 12px; margin-bottom: 10px; border: 1px solid #FDE68A;">
                                    <span
                                        style="font-size: 0.8rem; color: #92400E; display: block; font-weight: 600;">Giá
                                        bán ưu đãi:</span>
                                    <div style="display: flex; align-items: baseline; gap: 8px;">
                                        <span id="quickSpecPrice"
                                            style="font-size: 1.45rem; font-weight: 800; color: #DC2626;">0 ₫</span>
                                        <span id="quickSpecOldPrice"
                                            style="font-size: 0.85rem; color: #94A3B8; text-decoration: line-through;"></span>
                                    </div>
                                </div>
                                <div style="font-size: 0.85rem; color: #16A34A; font-weight: 700; margin-bottom: 4px;">
                                    <i class="fa-solid fa-circle-check"></i> <span id="quickSpecStockStatus">Còn hàng
                                        trong kho</span>
                                </div>
                                <div style="font-size: 0.785rem; color: #64748B;">
                                    <i class="fa-solid fa-shield-halved text-gold"></i> Bảo hành chính hãng 24 tháng tận
                                    nhà
                                </div>
                            </div>
                        </div>

                        <!-- BẢNG CỘT THÔNG SỐ SẢN PHẨM -->
                        <div style="margin-bottom: 22px;">
                            <div
                                style="font-weight: 700; font-size: 0.95rem; color: #0F172A; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-list-check" style="color: #C5A059;"></i>
                                <span>Thông số & đặc tính sản phẩm</span>
                            </div>
                            <div style="border: 1px solid #E2E8F0; border-radius: 12px; overflow: hidden;">
                                <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                                    <tbody>
                                        <tr style="border-bottom: 1px solid #F1F5F9;">
                                            <td
                                                style="padding: 9px 12px; background: #F8FAFC; width: 34%; font-weight: 600; color: #475569;">
                                                <i class="fa-solid fa-award text-gold"
                                                    style="margin-right: 6px;"></i>Thương hiệu:
                                            </td>
                                            <td style="padding: 9px 12px; font-weight: 700; color: #0F172A;"
                                                id="quickSpecBrand">Chính hãng</td>
                                        </tr>
                                        <tr style="border-bottom: 1px solid #F1F5F9;">
                                            <td
                                                style="padding: 9px 12px; background: #F8FAFC; font-weight: 600; color: #475569;">
                                                <i class="fa-solid fa-earth-americas text-gold"
                                                    style="margin-right: 6px;"></i>Xuất xứ:
                                            </td>
                                            <td style="padding: 9px 12px; font-weight: 700; color: #0F172A;"
                                                id="quickSpecOrigin">Việt Nam</td>
                                        </tr>
                                        <tr style="border-bottom: 1px solid #F1F5F9;">
                                            <td
                                                style="padding: 9px 12px; background: #F8FAFC; font-weight: 600; color: #475569;">
                                                <i class="fa-solid fa-gem text-gold" style="margin-right: 6px;"></i>Chất
                                                liệu:
                                            </td>
                                            <td style="padding: 9px 12px; color: #334155;" id="quickSpecMaterial">Hợp
                                                kim & Nhựa ABS</td>
                                        </tr>
                                        <tr style="border-bottom: 1px solid #F1F5F9;">
                                            <td
                                                style="padding: 9px 12px; background: #F8FAFC; font-weight: 600; color: #475569;">
                                                <i class="fa-solid fa-bolt text-gold"
                                                    style="margin-right: 6px;"></i>Công dụng:
                                            </td>
                                            <td style="padding: 9px 12px; color: #334155;" id="quickSpecUsage">Nấu nướng
                                                & tiện ích gia đình</td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="padding: 9px 12px; background: #F8FAFC; font-weight: 600; color: #475569;">
                                                <i class="fa-solid fa-tags text-gold" style="margin-right: 6px;"></i>Giá
                                                niêm yết:
                                            </td>
                                            <td style="padding: 9px 12px; color: #DC2626; font-weight: 700;"
                                                id="quickSpecPriceRow">0 ₫</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Nút hành động -->
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <button type="button" id="quickSpecBuyNowBtn" class="btn btn-gold"
                                style="flex: 1.2; padding: 12px 14px; font-size: 0.925rem; font-weight: 700; border-radius: 9999px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                <i class="fa-solid fa-bolt"></i> Mua ngay
                            </button>
                            <button type="button" id="quickSpecAddCartBtn" class="btn"
                                style="flex: 1; padding: 12px 14px; font-size: 0.875rem; font-weight: 600; border-radius: 9999px; border: 1.5px solid #CBD5E1; background: #fff; color: #334155; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                <i class="fa-solid fa-cart-plus text-gold"></i> Giỏ hàng
                            </button>
                            <a id="quickSpecViewDetailBtn" href="#" class="btn"
                                style="width: 100%; margin-top: 6px; padding: 11px 16px; font-size: 0.875rem; font-weight: 700; border-radius: 9999px; border: 1.5px solid #0F172A; background: #0F172A; color: #FFFFFF; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; transition: all 0.2s;">
                                <i class="fa-solid fa-arrow-up-right-from-square text-gold"></i> Xem trang chi tiết sản
                                phẩm đầy đủ
                            </a>
                        </div>
                    </div>
                </div>

                <!-- =========================================================================
                     MODAL: ĐÁNH GIÁ SẢN PHẨM TỪ KHÁCH HÀNG (CUSTOMER REVIEW MODAL)
                     ========================================================================= -->
                <div class="cart-drawer-backdrop" id="customerReviewModalBackdrop"
                    style="display: none; align-items: center; justify-content: center; z-index: 1055; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px);"
                    onclick="closeCustomerReviewModal(event)">
                    <div class="auth-card" id="customerReviewModalCard"
                        style="max-width: 580px; width: 92%; position: relative; margin: 20px; animation: modalFadeIn 0.3s ease; max-height: 90vh; overflow-y: auto; border-radius: 20px; padding: 28px; background: #FFFFFF; box-shadow: 0 25px 60px rgba(0,0,0,0.3);"
                        onclick="event.stopPropagation()">

                        <button type="button" class="cart-close-btn" onclick="closeCustomerReviewModal()"
                            style="position: absolute; right: 20px; top: 20px; background: none; border: none; font-size: 1.25rem; cursor: pointer; color: #64748B;">
                            <i class="fa-solid fa-xmark"></i>
                        </button>

                        <div class="auth-header" style="text-align: center; margin-bottom: 20px;">
                            <div class="auth-logo-badge"
                                style="width: 52px; height: 52px; font-size: 1.3rem; margin: 0 auto 10px auto; background: linear-gradient(135deg, #DFC07A, #C5A059); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(197, 160, 89, 0.35);">
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <h3 style="font-size: 1.4rem; font-weight: 800; color: #0F172A; margin-bottom: 4px;">Gửi
                                đánh giá của bạn</h3>
                            <p style="font-size: 0.85rem; color: #64748B; margin-bottom: 0;">
                                Chia sẻ trải nghiệm thực tế với thiết bị gia dụng FAMILY LUXE để nhận voucher ưu đãi
                                500.000đ
                            </p>
                        </div>

                        <form id="customerReviewForm" onsubmit="submitCustomerReview(event)">
                            <!-- 1. Interactive Star Rating -->
                            <div
                                style="text-align: center; margin-bottom: 18px; background: #FAF5EB; padding: 14px; border-radius: 14px; border: 1px solid #F3E8D2;">
                                <label
                                    style="display: block; font-size: 0.85rem; font-weight: 700; color: #8C6A24; margin-bottom: 8px;">
                                    Mức độ hài lòng của quý khách
                                </label>
                                <div class="star-rating-picker" id="starRatingPicker"
                                    style="display: flex; justify-content: center; gap: 8px; font-size: 1.9rem; cursor: pointer; color: #D1D5DB;">
                                    <i class="fa-solid fa-star" data-val="1" onclick="setReviewStars(1)"></i>
                                    <i class="fa-solid fa-star" data-val="2" onclick="setReviewStars(2)"></i>
                                    <i class="fa-solid fa-star" data-val="3" onclick="setReviewStars(3)"></i>
                                    <i class="fa-solid fa-star" data-val="4" onclick="setReviewStars(4)"></i>
                                    <i class="fa-solid fa-star" data-val="5" onclick="setReviewStars(5)"></i>
                                </div>
                                <input type="hidden" id="reviewRatingInput" value="5">
                                <span id="starRatingLabel"
                                    style="display: block; font-size: 0.85rem; font-weight: 700; color: #D97706; margin-top: 6px;">5/5
                                    - Tuyệt vời &amp; rất hài lòng</span>
                            </div>

                            <!-- 2. Customer Name & Location/Title -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                                <div>
                                    <label
                                        style="font-size: 0.8125rem; font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">
                                        <i class="fa-regular fa-user text-gold"></i> Họ và tên <span
                                            style="color: #DC2626;">*</span>
                                    </label>
                                    <input type="text" id="reviewUserName" required placeholder="Nhập họ và tên..."
                                        style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid #CBD5E1; font-size: 0.9rem; box-sizing: border-box; outline: none;">
                                </div>
                                <div>
                                    <label
                                        style="font-size: 0.8125rem; font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">
                                        <i class="fa-solid fa-location-dot text-gold"></i> Nơi ở / Chức danh
                                    </label>
                                    <input type="text" id="reviewUserTitle" placeholder="Nhập nơi ở / chức danh..."
                                        style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid #CBD5E1; font-size: 0.9rem; box-sizing: border-box; outline: none;">
                                </div>
                            </div>

                            <!-- 3. Product Selection -->
                            <div style="margin-bottom: 14px;">
                                <label
                                    style="font-size: 0.8125rem; font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">
                                    <i class="fa-solid fa-box text-gold"></i> Thiết bị gia dụng đã mua
                                </label>
                                <select id="reviewProductId"
                                    style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid #CBD5E1; font-size: 0.9rem; background: #fff; box-sizing: border-box; outline: none;">
                                    <!-- Populated dynamically via JS -->
                                </select>
                            </div>

                            <!-- 4. Review Comment Textarea -->
                            <div style="margin-bottom: 18px;">
                                <label
                                    style="font-size: 0.8125rem; font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">
                                    <i class="fa-regular fa-comment-dots text-gold"></i> Cảm nhận chi tiết <span
                                        style="color: #DC2626;">*</span>
                                 </label>
                                <textarea id="reviewComment" rows="4" required minlength="3"
                                    placeholder="Chia sẻ cảm nhận của bạn về chất lượng máy, tốc độ nấu, độ bền, dịch vụ giao hàng & lắp đặt..."
                                    style="width: 100%; padding: 12px 14px; border-radius: 8px; border: 1px solid #CBD5E1; font-size: 0.9rem; font-family: inherit; resize: vertical; box-sizing: border-box; outline: none;"></textarea>
                                <small style="color: #64748B; font-size: 0.75rem; margin-top: 4px; display: block;">Tối
                                    thiểu 3 ký tự. Đánh giá sẽ được cập nhật trực tiếp lên trang chủ.</small>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" id="btnSubmitReview"
                                style="width: 100%; padding: 13px; font-weight: 700; font-size: 0.95rem; border-radius: 9999px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #fff; box-shadow: 0 4px 14px rgba(197, 160, 89, 0.35); transition: all 0.2s;">
                                <i class="fa-solid fa-paper-plane"></i> Gửi đánh giá ngay
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Frontend Interactive Script -->
                <script src="{{ asset('js/app.js?v=' . (file_exists(public_path('js/app.js')) ? filemtime(public_path('js/app.js')) : time())) }}"></script>
                <script>
                    // ==========================================
                    // THÔNG SỐ SẢN PHẨM & SO SÁNH SẢN PHẨM (INDEX SPEC & COMPARE ENGINE)
                    // ==========================================
                    function getCompareIds() {
                        try {
                            return JSON.parse(localStorage.getItem('family_compare_ids') || '[]');
                        } catch (e) {
                            return [];
                        }
                    }

                    function saveCompareIds(ids) {
                        localStorage.setItem('family_compare_ids', JSON.stringify(ids));
                        updateIndexCompareUi();
                    }

                    function findProductData(id) {
                        id = parseInt(id);
                        // 1. Thử tìm từ thẻ HTML trên trang
                        const card = document.querySelector(`.product-card[data-product-id="${id}"]`);
                        if (card) {
                            return {
                                id: id,
                                name: card.getAttribute('data-product-name') || '',
                                price: parseFloat(card.getAttribute('data-product-price')) || 0,
                                img: card.getAttribute('data-product-img') || 'public/images/products/air_fryer.jpg',
                                brand: card.getAttribute('data-product-brand') || 'Chính hãng',
                                origin: card.getAttribute('data-product-origin') || 'Việt Nam',
                                material: card.getAttribute('data-product-material') || 'Hợp kim & Nhựa ABS',
                                usage: card.getAttribute('data-product-usage') || '',
                                category: card.querySelector('.product-category-name') ? card.querySelector('.product-category-name').innerText.split('•')[0].trim() : 'Gia Dụng',
                                stock: card.querySelector('.sold-count') ? card.querySelector('.sold-count').innerText.replace('|', '').trim() : 'Còn hàng'
                            };
                        }
                        // 2. Thử tìm từ localStorage synced
                        try {
                            const stored = JSON.parse(localStorage.getItem('family_synced_products') || '[]');
                            const found = stored.find(item => item.id === id);
                            if (found) {
                                return {
                                    id: found.id,
                                    name: found.name,
                                    price: parseFloat(found.price) || 0,
                                    img: found.image || 'public/images/products/air_fryer.jpg',
                                    brand: found.brand || 'Chính hãng',
                                    origin: found.origin || 'Việt Nam',
                                    material: found.material || 'Hợp kim & Nhựa ABS',
                                    usage: found.usage || found.description || '',
                                    category: found.category || 'Gia Dụng',
                                    stock: (found.stock || 10) + ' sp'
                                };
                            }
                        } catch (e) { }
                        return null;
                    }

                    function openProductQuickSpecModal(productId) {
                        const p = findProductData(productId);
                        if (!p) return;

                        document.getElementById('quickSpecCategory').innerText = p.category;
                        document.getElementById('quickSpecTitle').innerText = p.name;
                        document.getElementById('quickSpecImage').src = p.img;
                        document.getElementById('quickSpecPrice').innerText = new Intl.NumberFormat('vi-VN').format(p.price) + ' ₫';
                        document.getElementById('quickSpecPriceRow').innerText = new Intl.NumberFormat('vi-VN').format(p.price) + ' ₫';
                        document.getElementById('quickSpecOldPrice').innerText = p.price > 0 ? new Intl.NumberFormat('vi-VN').format(Math.round(p.price * 1.2 / 10000) * 10000) + ' ₫' : '';
                        document.getElementById('quickSpecBrand').innerText = p.brand;
                        document.getElementById('quickSpecOrigin').innerText = p.origin;
                        document.getElementById('quickSpecMaterial').innerText = p.material;
                        document.getElementById('quickSpecUsage').innerText = p.usage || 'Thiết bị gia dụng phục vụ nấu nướng & sinh hoạt tiện lợi cho gia đình.';
                        document.getElementById('quickSpecStockStatus').innerText = 'Còn hàng (' + p.stock + ')';

                        // Gán sự kiện cho các nút trong Modal
                        document.getElementById('quickSpecBuyNowBtn').onclick = function () {
                            closeProductQuickSpecModal();
                            proceedToRealCheckout(p.id, p.name, p.price, p.img);
                        };
                        document.getElementById('quickSpecAddCartBtn').onclick = function () {
                            closeProductQuickSpecModal();
                            addToCart(p.id, p.name, p.price, p.img);
                        };
                        const isWebgiadung = window.location.pathname.includes('/webgiadung');
                        const targetDetailUrl = (isWebgiadung ? '/webgiadung/products/' : '/products/') + p.id;
                        const viewDetailBtn = document.getElementById('quickSpecViewDetailBtn');
                        if (viewDetailBtn) {
                            viewDetailBtn.href = targetDetailUrl;
                            viewDetailBtn.onclick = function (e) {
                                window.location.href = targetDetailUrl;
                            };
                        }

                        const backdrop = document.getElementById('quickSpecModalBackdrop');
                        if (backdrop) backdrop.style.display = 'flex';
                    }

                    function goToProductDetail(productId, event) {
                        if (event) {
                            // Ngăn chặn chuyển hướng nếu bấm trúng nút Mua hoặc nút Giỏ
                            if (event.target && (event.target.closest('.btn-buy-now') || event.target.closest('.btn-add-cart') || event.target.closest('button') || event.target.closest('.card-quick-actions'))) {
                                return;
                            }
                        }
                        const isWebgiadung = window.location.pathname.includes('/webgiadung');
                        const targetUrl = (isWebgiadung ? '/webgiadung/products/' : '/products/') + productId;
                        window.location.href = targetUrl;
                    }
                    window.goToProductDetail = goToProductDetail;

                    function closeProductQuickSpecModal(e) {
                        if (e && e.target !== e.currentTarget && !e.target.closest('.cart-close-btn')) return;
                        const backdrop = document.getElementById('quickSpecModalBackdrop');
                        if (backdrop) backdrop.style.display = 'none';
                    }

                    // Safe stubs in case external scripts call compare
                    function toggleCompareProduct(productId, event) {
                        if (event) event.stopPropagation();
                        window.location.href = (window.location.pathname.includes('/webgiadung') ? '/webgiadung/so-sanh' : '/so-sanh');
                    }
                    function updateIndexCompareUi() { }
                    function openProductCompareModal() { }
                    function closeProductCompareModal() { }

                    // Modal functions
                    function openLoginModal(tab = 'login', notice = null) {
                        const backdrop = document.getElementById('loginModalBackdrop');
                        if (backdrop) {
                            backdrop.style.display = 'flex';
                            backdrop.style.opacity = '1';
                            backdrop.style.visibility = 'visible';
                        }
                        switchAuthTab(tab);

                        const noticeEl = document.getElementById('authModalNotice');
                        const noticeText = document.getElementById('authModalNoticeText');
                        if (noticeEl && noticeText) {
                            if (notice) {
                                noticeText.innerHTML = notice;
                                noticeEl.style.display = 'block';
                            } else {
                                noticeEl.style.display = 'none';
                            }
                        }
                    }

                    function closeLoginModal() {
                        const backdrop = document.getElementById('loginModalBackdrop');
                        if (backdrop) backdrop.style.display = 'none';
                        const noticeEl = document.getElementById('authModalNotice');
                        if (noticeEl) noticeEl.style.display = 'none';
                    }

                    function switchAuthTab(tab) {
                        const loginBtn = document.getElementById('tabLoginBtn');
                        const registerBtn = document.getElementById('tabRegisterBtn');
                        const loginPanel = document.getElementById('loginTabPanel');
                        const registerPanel = document.getElementById('registerTabPanel');

                        if (tab === 'register') {
                            registerPanel.style.display = 'block';
                            loginPanel.style.display = 'none';
                            registerBtn.style.background = '#FFFFFF';
                            registerBtn.style.color = '#0F172A';
                            registerBtn.style.boxShadow = '0 2px 6px rgba(0,0,0,0.06)';
                            loginBtn.style.background = 'transparent';
                            loginBtn.style.color = '#64748B';
                            loginBtn.style.boxShadow = 'none';
                        } else {
                            loginPanel.style.display = 'block';
                            registerPanel.style.display = 'none';
                            loginBtn.style.background = '#FFFFFF';
                            loginBtn.style.color = '#0F172A';
                            loginBtn.style.boxShadow = '0 2px 6px rgba(0,0,0,0.06)';
                            registerBtn.style.background = 'transparent';
                            registerBtn.style.color = '#64748B';
                            registerBtn.style.boxShadow = 'none';
                        }
                    }

                    function fillStaticCredentials(email, password) {
                        document.getElementById('staticEmail').value = email;
                        document.getElementById('staticPassword').value = password;
                    }

                    function getAdminTargetUrl() {
                        const isLiveServer = window.location.port === '5500' || (window.location.hostname === '127.0.0.1' && window.location.port !== '8000' && window.location.port !== '');
                        if (isLiveServer) {
                            return '/admin.html';
                        }
                        const isWebgiadung = window.location.pathname.includes('/webgiadung');
                        return isWebgiadung ? '/webgiadung/admin/dashboard' : '/admin/dashboard';
                    }

                    function fastLoginAsAdmin() {
                        fillStaticCredentials('admin@gmail.com', '123456');
                        const isWebgiadung = window.location.pathname.includes('/webgiadung');
                        const syncLoginUrl = isWebgiadung ? '/webgiadung/api/auth/sync-login' : '/api/auth/sync-login';
                        const adminUrl = getAdminTargetUrl();

                        window.pendingCheckoutItem = null;
                        const adminUser = { id: 3, name: 'Quản Trị Viên (Admin)', email: 'admin@gmail.com', role: 'admin' };
                        localStorage.setItem('auraluxe_user', JSON.stringify(adminUser));

                        if (typeof showToast === 'function') {
                            showToast('👑 Đang đăng nhập Quản Trị Viên và chuyển hướng đến Bảng Quản Trị...');
                        }

                        fetch(syncLoginUrl, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            body: JSON.stringify({ email: 'admin@gmail.com', password: '123456' })
                        }).finally(() => {
                            window.location.href = adminUrl;
                        });
                    }

                    function toggleStaticPassword(inputId = 'staticPassword', iconId = 'staticEyeIcon') {
                        const input = document.getElementById(inputId);
                        const icon = document.getElementById(iconId);
                        if (input.type === 'password') {
                            input.type = 'text';
                            icon.classList.remove('fa-eye');
                            icon.classList.add('fa-eye-slash');
                        } else {
                            input.type = 'password';
                            icon.classList.remove('fa-eye-slash');
                            icon.classList.add('fa-eye');
                        }
                    }

                    function handleStaticLogin(e) {
                        e.preventDefault();
                        const email = document.getElementById('staticEmail').value.trim();
                        const password = document.getElementById('staticPassword').value.trim();

                        const isWebgiadung = window.location.pathname.includes('/webgiadung');
                        const syncLoginUrl = isWebgiadung ? '/webgiadung/api/auth/sync-login' : '/api/auth/sync-login';

                        // Đồng bộ đăng nhập thẳng vào Laravel Session qua Cookie
                        fetch(syncLoginUrl, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({ email: email, password: password })
                        }).then(r => r.json()).then(data => {
                            let user = null;
                            if (data && data.success && data.user) {
                                user = data.user;
                            } else {
                                user = { name: email.split('@')[0], email: email, role: (email === 'admin@gmail.com') ? 'admin' : 'customer' };
                            }

                            // NẾU LÀ TÀI KHOẢN ADMIN: Mở Bảng Quản Trị
                            if (user.role === 'admin' || email === 'admin@gmail.com') {
                                window.pendingCheckoutItem = null;
                                user.role = 'admin';
                                user.name = user.name || 'Quản Trị Viên (Admin)';
                                localStorage.setItem('auraluxe_user', JSON.stringify(user));
                                closeLoginModal();
                                updateAuthHeader(user);
                                if (typeof showToast === 'function') {
                                    showToast('👑 Đăng nhập Quản Trị Viên thành công! Đang mở Bảng Quản Trị...');
                                }
                                setTimeout(() => {
                                    window.location.href = getAdminTargetUrl();
                                }, 350);
                                return;
                            }

                            user.role = 'customer';
                            localStorage.setItem('auraluxe_user', JSON.stringify(user));
                            closeLoginModal();
                            updateAuthHeader(user);

                            // NẾU ĐANG CHỜ MUA HÀNG (cho khách): Tiếp tục mua và thanh toán ngay lập tức
                            if (window.pendingCheckoutItem) {
                                const pending = window.pendingCheckoutItem;
                                window.pendingCheckoutItem = null;
                                if (typeof showToast === 'function') {
                                    showToast('🎉 Đăng nhập thành công! Đang chuyển đến đơn hàng của bạn...');
                                }
                                setTimeout(() => {
                                    proceedToRealCheckout(pending.id, pending.name, pending.price, pending.img);
                                }, 400);
                                return;
                            }

                            if (typeof showToast === 'function') {
                                showToast('Đăng nhập thành công! Chào mừng ' + user.name);
                            }
                        }).catch(err => {
                            // Fallback nếu kết nối lỗi
                            const user = { name: email.split('@')[0], email: email, role: email.toLowerCase().includes('admin') ? 'admin' : 'customer' };
                            if (user.role === 'admin' || email.toLowerCase().includes('admin')) {
                                window.pendingCheckoutItem = null;
                                user.role = 'admin';
                                user.name = 'Quản Trị Viên (Admin)';
                                localStorage.setItem('auraluxe_user', JSON.stringify(user));
                                closeLoginModal();
                                updateAuthHeader(user);
                                setTimeout(() => {
                                    window.location.href = getAdminTargetUrl();
                                }, 350);
                                return;
                            }

                            localStorage.setItem('auraluxe_user', JSON.stringify(user));
                            closeLoginModal();
                            updateAuthHeader(user);

                            if (window.pendingCheckoutItem) {
                                const pending = window.pendingCheckoutItem;
                                window.pendingCheckoutItem = null;
                                if (typeof showToast === 'function') {
                                    showToast('🎉 Đăng nhập thành công! Đang chuyển đến đơn hàng của bạn...');
                                }
                                setTimeout(() => {
                                    proceedToRealCheckout(pending.id, pending.name, pending.price, pending.img);
                                }, 400);
                                return;
                            }

                            if (typeof showToast === 'function') {
                                showToast('Đăng nhập thành công! Chào mừng ' + user.name);
                            }
                        });
                    }

                    function handleStaticRegister(e) {
                        e.preventDefault();
                        const name = document.getElementById('regName').value.trim();
                        const phone = document.getElementById('regPhone').value.trim();
                        const email = document.getElementById('regEmail').value.trim();
                        const password = document.getElementById('regPassword').value;
                        const passwordConfirm = document.getElementById('regPasswordConfirm').value;

                        if (password !== passwordConfirm) {
                            alert('Mật khẩu xác nhận không trùng khớp. Vui lòng kiểm tra lại!');
                            return;
                        }

                        const isWebgiadung = window.location.pathname.includes('/webgiadung');
                        const syncRegUrl = isWebgiadung ? '/webgiadung/api/auth/sync-register' : '/api/auth/sync-register';

                        // Đồng bộ đăng ký thẳng vào database và đăng nhập Laravel session
                        fetch(syncRegUrl, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({ name: name, phone: phone, email: email, password: password })
                        }).then(r => r.json()).then(data => {
                            const user = (data && data.user) ? data.user : { name, email, phone, role: 'customer' };
                            localStorage.setItem('auraluxe_user', JSON.stringify(user));
                            closeLoginModal();
                            updateAuthHeader(user);

                            if (window.pendingCheckoutItem) {
                                const pending = window.pendingCheckoutItem;
                                window.pendingCheckoutItem = null;
                                if (typeof showToast === 'function') {
                                    showToast(`🎉 Chúc mừng ${name}! Đang chuyển đến đơn hàng của bạn...`);
                                }
                                setTimeout(() => {
                                    proceedToRealCheckout(pending.id, pending.name, pending.price, pending.img);
                                }, 400);
                                return;
                            }

                            if (typeof showToast === 'function') {
                                showToast(`🎉 Chúc mừng ${name} đã đăng ký tài khoản thành công!`);
                            } else {
                                alert(`Chúc mừng ${name} đã đăng ký tài khoản thành công!`);
                            }
                        }).catch(err => {
                            const user = { name, email, phone, role: 'customer' };
                            localStorage.setItem('auraluxe_user', JSON.stringify(user));
                            closeLoginModal();
                            updateAuthHeader(user);

                            if (window.pendingCheckoutItem) {
                                const pending = window.pendingCheckoutItem;
                                window.pendingCheckoutItem = null;
                                setTimeout(() => {
                                    proceedToRealCheckout(pending.id, pending.name, pending.price, pending.img);
                                }, 400);
                                return;
                            }

                            if (typeof showToast === 'function') {
                                showToast(`🎉 Chúc mừng ${name} đã đăng ký tài khoản thành công!`);
                            }
                        });
                    }

                    function updateAuthHeader(user) {
                        const container = document.getElementById('authHeaderContainer');
                        if (!container) return;

                        if (user) {
                            const isAdmin = user.role === 'admin' || user.email === 'admin@gmail.com';
                            const isWebgiadung = window.location.pathname.includes('/webgiadung');
                            const isLiveServer = window.location.port === '5500' || (window.location.hostname === '127.0.0.1' && window.location.port !== '8000' && window.location.port !== '');
                            const adminDashboardUrl = isLiveServer ? '/admin.html' : (isWebgiadung ? '/webgiadung/admin/dashboard' : '/admin/dashboard');
                            const adminHtmlUrl = isLiveServer ? '/admin.html' : (isWebgiadung ? '/webgiadung/admin.html' : '/admin.html');

                            if (isAdmin) {
                                container.innerHTML = `
                        <div style="position: relative; display: inline-flex; align-items: center; gap: 4px; background: rgba(197, 160, 89, 0.1); padding: 4px 10px; border-radius: 9999px; border: 1.5px solid rgba(197, 160, 89, 0.4);">
                            <!-- Bấm trực tiếp vào Nick Quản Trị Viên sẽ đi thẳng vào Bảng Quản Trị -->
                            <a href="${adminDashboardUrl}" title="Nhấp để mở ngay Bảng quản trị hệ thống" style="display: flex; align-items: center; gap: 8px; text-decoration: none; color: inherit; cursor: pointer;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--gold-gradient); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: 700; box-shadow: 0 2px 8px rgba(197, 160, 89, 0.4);">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                <div style="display: flex; flex-direction: column; text-align: left;">
                                    <span style="font-size: 0.825rem; font-weight: 700; color: #916F29; line-height: 1.2;">Quản trị viên (Admin)</span>
                                    <span style="font-size: 0.7rem; color: #10B981; font-weight: 600;"><i class="fa-solid fa-circle" style="font-size: 0.45rem;"></i> Mở bảng quản trị →</span>
                                </div>
                            </a>
                            <button type="button" onclick="toggleHeaderMenu()" title="Tùy chọn Quản trị viên" style="background: none; border: none; cursor: pointer; color: #8C6A24; padding: 6px 4px; display: flex; align-items: center;">
                                <i class="fa-solid fa-chevron-down" style="font-size: 0.75rem;"></i>
                            </button>
                            <div id="staticHeaderDropdown" style="display: none; position: absolute; right: 0; top: 125%; width: 250px; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.12); padding: 12px; z-index: 200;">
                                <div style="padding: 6px 10px 10px; border-bottom: 1px solid #F1F5F9; margin-bottom: 8px;">
                                    <div style="font-weight: 700; font-size: 0.875rem; color: #0F172A;">Quản trị viên (Admin)</div>
                                    <div style="font-size: 0.75rem; color: #64748B;">admin@gmail.com</div>
                                    <span style="display: inline-block; margin-top: 6px; padding: 2px 8px; border-radius: 9999px; background: rgba(197,160,89,0.15); color: #916F29; font-size: 0.7rem; font-weight: 700;">
                                        <i class="fa-solid fa-shield-halved"></i> Toàn quyền hệ thống
                                    </span>
                                </div>
                                <a href="${adminDashboardUrl}" style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; font-size: 0.85rem; font-weight: 700; color: #916F29; text-decoration: none; border-radius: 8px; background: rgba(197,160,89,0.12); margin-bottom: 6px; border: 1px solid rgba(197,160,89,0.25);">
                                    <i class="fa-solid fa-gauge-high"></i> Bảng quản trị (Thống kê)
                                </a>
                                <a href="${adminHtmlUrl}" style="display: flex; align-items: center; gap: 10px; padding: 9px 12px; font-size: 0.85rem; font-weight: 600; color: #374151; text-decoration: none; border-radius: 8px; margin-bottom: 6px;" onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='none'">
                                    <i class="fa-solid fa-laptop-code"></i> Giao diện quản trị trắng
                                </a>
                                <button type="button" onclick="handleStaticLogout()" style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 10px 12px; font-size: 0.85rem; font-weight: 600; color: #DC2626; background: none; border: none; border-radius: 8px; cursor: pointer; text-align: left;" onmouseover="this.style.background='#FEF2F2'" onmouseout="this.style.background='none'">
                                    <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                                </button>
                            </div>
                        </div>
                    `;
                            } else {
                                // Khách hàng thông thường
                                container.innerHTML = `
                        <div style="position: relative; display: inline-flex; align-items: center; gap: 8px;">
                            <button type="button" class="action-btn" onclick="toggleHeaderMenu()" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--gold-gradient); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: 700;">
                                    ${user.name.charAt(0).toUpperCase()}
                                </div>
                                <span style="font-size: 0.85rem; font-weight: 600; color: #111827;">${user.name}</span>
                                <i class="fa-solid fa-chevron-down" style="font-size: 0.65rem; color: #64748B;"></i>
                            </button>
                            <div id="staticHeaderDropdown" style="display: none; position: absolute; right: 0; top: 120%; width: 230px; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); padding: 12px; z-index: 200;">
                                <div style="padding: 6px 12px 10px; border-bottom: 1px solid #F1F5F9; margin-bottom: 8px;">
                                    <div style="font-weight: 700; font-size: 0.875rem; color: #0F172A;">${user.name}</div>
                                    <div style="font-size: 0.75rem; color: #64748B;">${user.email}</div>
                                    <span style="display: inline-block; margin-top: 6px; padding: 2px 8px; border-radius: 9999px; background: #F1F5F9; color: #475569; font-size: 0.7rem; font-weight: 700;">
                                        <i class="fa-regular fa-user"></i> Khách hàng
                                    </span>
                                </div>
                                <a href="${isWebgiadung ? '/webgiadung/cart' : '/cart'}" style="display: flex; align-items: center; gap: 10px; padding: 9px 12px; font-size: 0.85rem; font-weight: 600; color: #374151; text-decoration: none; border-radius: 8px; margin-bottom: 6px;" onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='none'">
                                    <i class="fa-solid fa-cart-shopping"></i> Giỏ hàng của tôi
                                </a>
                                <button type="button" onclick="handleStaticLogout()" style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 10px 12px; font-size: 0.85rem; font-weight: 600; color: #DC2626; background: none; border: none; border-radius: 8px; cursor: pointer; text-align: left;" onmouseover="this.style.background='#FEF2F2'" onmouseout="this.style.background='none'">
                                    <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                                </button>
                            </div>
                        </div>
                    `;
                            }
                        } else {
                            container.innerHTML = `
                    <button type="button" class="action-btn" id="openAuthModalBtn" title="Đăng nhập / Đăng ký" onclick="openLoginModal('login')" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-regular fa-circle-user"></i>
                        <span style="font-size: 0.85rem;">Đăng nhập</span>
                    </button>
                `;
                        }
                    }

                    function toggleHeaderMenu() {
                        const menu = document.getElementById('staticHeaderDropdown');
                        if (menu) {
                            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
                        }
                    }

                    function handleStaticLogout() {
                        localStorage.removeItem('auraluxe_user');
                        localStorage.removeItem('family_user');
                        const isWebgiadung = window.location.pathname.includes('/webgiadung');
                        const logoutUrl = isWebgiadung ? '/webgiadung/api/auth/sync-logout' : '/api/auth/sync-logout';
                        fetch(logoutUrl, { method: 'POST', credentials: 'same-origin' }).then(() => {
                            if (typeof showToast === 'function') {
                                showToast('Bạn đã đăng xuất tài khoản thành công.');
                            }
                            setTimeout(() => { window.location.reload(); }, 800);
                        }).catch(() => { 
                            window.location.reload();
                        });
                    }

                    // Close dropdown when clicking outside
                    document.addEventListener('click', function (e) {
                        const dropdown = document.getElementById('staticHeaderDropdown');
                        const container = document.getElementById('authHeaderContainer');
                        if (dropdown && container && !container.contains(e.target)) {
                            dropdown.style.display = 'none';
                        }
                    });

                    // Luôn đồng bộ danh tính thực tế từ Laravel Session về giao diện
                    (function () {
                        const isWebgiadung = window.location.pathname.includes('/webgiadung');
                        const meUrl = isWebgiadung ? '/webgiadung/api/auth/me' : '/api/auth/me';
                        fetch(meUrl, { credentials: 'same-origin' })
                            .then(r => r.json())
                            .then(data => {
                                if (data && data.authenticated && data.user) {
                                    localStorage.setItem('auraluxe_user', JSON.stringify(data.user));
                                    updateAuthHeader(data.user);
                                } else {
                                    localStorage.removeItem('auraluxe_user');
                                    updateAuthHeader(null);
                                }
                            })
                            .catch(() => {
                                // Fallback sang localStorage nếu mạng lỗi
                                try {
                                    const saved = localStorage.getItem('auraluxe_user');
                                    if (saved) {
                                        let u = JSON.parse(saved);
                                        updateAuthHeader(u);
                                    }
                                } catch (e) { }
                            });
                    })();

                    // Bắt buộc Đăng Nhập khi Mua Hàng hoặc Thêm Giỏ Hàng
                    function requireAuthForPurchase(action = 'mua_hang', pendingItem = null) {
                        let user = null;
                        try {
                            const saved = localStorage.getItem('auraluxe_user');
                            if (saved) user = JSON.parse(saved);
                        } catch (e) { }

                        if (!user || !user.email) {
                            if (pendingItem) {
                                window.pendingCheckoutItem = pendingItem;
                            }
                            const msg = action === 'mua_hang'
                                ? '⚠️ Quý khách vui lòng đăng nhập tài khoản để tiến hành mua hàng!'
                                : '⚠️ Quý khách vui lòng đăng nhập tài khoản để thêm sản phẩm vào giỏ hàng!';

                            if (typeof showToast === 'function') {
                                showToast(msg);
                            }
                            openLoginModal('login', msg);
                            return false;
                        }
                        return true;
                    }

                    // Tự động gắn cụm nút "Mua Hàng" và kiểm tra đăng nhập cho tất cả sản phẩm
                    document.addEventListener('DOMContentLoaded', function () {
                        document.querySelectorAll('.btn-add-cart').forEach(btn => {
                            const parent = btn.parentElement;
                            if (!parent.querySelector('.btn-buy-now')) {
                                const buyBtn = document.createElement('button');
                                buyBtn.className = 'btn-buy-now';
                                buyBtn.innerHTML = '<i class="fa-solid fa-bolt-lightning"></i> Mua hàng';
                                buyBtn.style.cssText = 'flex: 1.2; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 10px 12px; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; font-weight: 700; font-size: 0.85rem; border: none; border-radius: 9999px; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(197, 160, 89, 0.25);';

                                buyBtn.onclick = function (e) {
                                    e.preventDefault();
                                    const card = buyBtn.closest('[data-product-id]') || buyBtn.closest('.product-card');
                                    const id = card ? card.getAttribute('data-product-id') : 4;
                                    const name = card ? card.getAttribute('data-product-name') : 'Bếp từ Elmix';
                                    const price = card ? card.getAttribute('data-product-price') : 990000;
                                    const img = card ? card.getAttribute('data-product-img') : '';

                                    proceedToRealCheckout(id, name, price, img);
                                };

                                btn.style.cssText = 'flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 9px 10px; background: #FFFFFF; color: #8C6A24; border: 1px solid #EAE2D5; font-weight: 600; font-size: 0.825rem; border-radius: 9999px; cursor: pointer; transition: all 0.2s;';
                                btn.innerHTML = '<i class="fa-solid fa-cart-plus"></i> Giỏ';

                                const actionWrap = document.createElement('div');
                                actionWrap.style.cssText = 'display: flex; gap: 8px; margin-top: 12px;';
                                parent.insertBefore(actionWrap, btn);
                                actionWrap.appendChild(buyBtn);
                                actionWrap.appendChild(btn);

                                btn.onclick = function (e) {
                                    e.preventDefault();
                                    const card = btn.closest('[data-product-id]') || btn.closest('.product-card');
                                    const id = card ? card.getAttribute('data-product-id') : 4;
                                    const name = card ? card.getAttribute('data-product-name') : 'Bếp từ Elmix';
                                    const price = card ? card.getAttribute('data-product-price') : 990000;
                                    const img = card ? card.getAttribute('data-product-img') : '';

                                    if (typeof window.addToCart === 'function') {
                                        window.addToCart(id, name, price, img);
                                    }
                                };
                            }
                        });
                    });

                    // Đồng bộ toàn bộ giỏ hàng lên server và chuyển đến trang thanh toán
                    function syncCartAndGoToCheckout() {
                        const isWebgiadung = window.location.pathname.includes('/webgiadung');
                        const isLiveServer = window.location.port === '5500';
                        const backendBase = isLiveServer ? 'http://127.0.0.1:8000' : '';
                        const syncUrl = backendBase + (isWebgiadung ? '/webgiadung/api/cart/sync' : '/api/cart/sync');
                        const cartUrl = backendBase + (isWebgiadung ? '/webgiadung/cart#checkoutOrderForm' : '/cart#checkoutOrderForm');
                        const loginUrl = backendBase + (isWebgiadung ? '/webgiadung/login?redirect=' + encodeURIComponent('/webgiadung/cart#checkoutOrderForm') : '/login?redirect=' + encodeURIComponent('/cart#checkoutOrderForm'));
                        const meUrl = backendBase + (isWebgiadung ? '/webgiadung/api/auth/me' : '/api/auth/me');

                        // Lấy giỏ hàng từ memory hoặc localStorage
                        let currentCart = [];
                        try {
                            const raw = localStorage.getItem('family_cart') || localStorage.getItem('aura_cart');
                            if (raw) {
                                const parsed = JSON.parse(raw);
                                if (Array.isArray(parsed)) currentCart = parsed;
                            }
                        } catch(e) {}

                        // Lọc các item hợp lệ có id số nguyên dương
                        const validItems = currentCart
                            .filter(item => item && item.id && !isNaN(parseInt(item.id, 10)) && parseInt(item.id, 10) > 0)
                            .map(item => ({
                                id: parseInt(item.id, 10),
                                name: item.name || '',
                                price: Number(item.price) || 0,
                                image: item.image || item.img || '',
                                quantity: Math.max(1, parseInt(item.quantity, 10) || 1)
                            }));

                        fetch(meUrl, { credentials: 'same-origin' })
                            .then(res => res.json())
                            .then(data => {
                                const isLoggedIn = data && data.authenticated === true;
                                const localUser = localStorage.getItem('family_user') || localStorage.getItem('auraluxe_user');
                                if (!isLoggedIn && !localUser) {
                                    window.location.href = loginUrl;
                                    return null;
                                }
                                if (validItems.length > 0) {
                                    return fetch(syncUrl, {
                                        method: 'POST',
                                        credentials: 'same-origin',
                                        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                                        body: JSON.stringify({ items: validItems, mode: 'full' })
                                    });
                                }
                                return true;
                            })
                            .then(res => { if (res) window.location.href = cartUrl; })
                            .catch(() => {
                                if (validItems.length > 0) {
                                    fetch(syncUrl, {
                                        method: 'POST',
                                        credentials: 'same-origin',
                                        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                                        body: JSON.stringify({ items: validItems, mode: 'full' })
                                    }).finally(() => { window.location.href = cartUrl; });
                                } else {
                                    window.location.href = cartUrl;
                                }
                            });
                    }
                    window.syncCartAndGoToCheckout = syncCartAndGoToCheckout;

                    function proceedToRealCheckout(id, name, price, img, event) {
                        if (event) {
                            try { event.stopPropagation(); event.preventDefault(); } catch(e){}
                        }

                        // Nếu không truyền ID hợp lệ, coi như người dùng muốn thanh toán giỏ hàng hiện có
                        if (!id || isNaN(parseInt(id, 10)) || parseInt(id, 10) <= 0) {
                            return syncCartAndGoToCheckout();
                        }

                        // Ép kiểu id về số nguyên để server tìm đúng sản phẩm trong DB
                        const productId = parseInt(id, 10);
                        const isWebgiadung = window.location.pathname.includes('/webgiadung');
                        const isLiveServer = window.location.port === '5500';
                        const backendBase = isLiveServer ? 'http://127.0.0.1:8000' : '';

                        const syncUrl = backendBase + (isWebgiadung ? '/webgiadung/api/cart/sync' : '/api/cart/sync');
                        const cartUrl = backendBase + (isWebgiadung ? '/webgiadung/cart#checkoutOrderForm' : '/cart#checkoutOrderForm');
                        const loginUrl = backendBase + (isWebgiadung ? '/webgiadung/login?redirect=' + encodeURIComponent('/webgiadung/cart#checkoutOrderForm') : '/login?redirect=' + encodeURIComponent('/cart#checkoutOrderForm'));
                        const meUrl = backendBase + (isWebgiadung ? '/webgiadung/api/auth/me' : '/api/auth/me');

                        const singleItem = [{ id: productId, name: name || '', price: Number(price) || 0, image: img || '', quantity: 1 }];
                        try {
                            localStorage.setItem('aura_cart', JSON.stringify(singleItem));
                            localStorage.setItem('family_cart', JSON.stringify(singleItem));
                        } catch (e) { }

                        // Kiểm tra đăng nhập qua Laravel session (ưu tiên) hoặc localStorage
                        fetch(meUrl, { credentials: 'same-origin' })
                            .then(res => res.json())
                            .then(data => {
                                const isLoggedIn = data && data.authenticated === true;
                                const localUser = localStorage.getItem('family_user') || localStorage.getItem('auraluxe_user');
                                if (!isLoggedIn && !localUser) {
                                    window.location.href = loginUrl;
                                    return;
                                }
                                return fetch(syncUrl, {
                                    method: 'POST',
                                    credentials: 'same-origin',
                                    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                                    body: JSON.stringify({ items: singleItem, mode: 'full' })
                                });
                            })
                            .then(res => { if (res) window.location.href = cartUrl; })
                            .catch(() => {
                                const localUser = localStorage.getItem('family_user') || localStorage.getItem('auraluxe_user');
                                const nextUrl = localUser ? cartUrl : loginUrl;
                                fetch(syncUrl, {
                                    method: 'POST',
                                    credentials: 'same-origin',
                                    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                                    body: JSON.stringify({ items: singleItem, mode: 'full' })
                                }).finally(() => { window.location.href = nextUrl; });
                            });
                    }
                    window.proceedToRealCheckout = proceedToRealCheckout;
                </script>

                <!-- =========================================================================
         REAL-TIME PRODUCT SYNCHRONIZATION ENGINE FOR HOMEPAGE
         Đảm bảo mọi chỉnh sửa trong Admin (Tên, Giá, Ảnh, Tồn kho, Mô tả)
         ngay lập tức cập nhật lên Trang Chủ theo thời gian thực (Zero-Delay)
         ========================================================================= -->
                <script>
                    (function () {
                        function formatVnd(val) {
                            return new Intl.NumberFormat('vi-VN').format(Number(val) || 0) + ' ₫';
                        }

                        function getCatSlug(catName, prodName) {
                            const str = ((catName || '') + ' ' + (prodName || '')).toLowerCase();
                            if (str.includes('chiên') || str.includes('nồi chiên')) return 'noi-chien';
                            if (str.includes('bếp') || str.includes('từ') || str.includes('hồng ngoại')) return 'bep-dien-tu';
                            if (str.includes('lạnh') || str.includes('mát') || str.includes('tủ')) return 'tu-lanh';
                            if (str.includes('cơm') || str.includes('cao tần') || str.includes('ih')) return 'noi-com-ih';
                            if (str.includes('bụi') || str.includes('robot') || str.includes('làm sạch')) return 'robot-hut-bui';
                            return 'all';
                        }

                        function normalizeImgUrl(img) {
                            const isWebgiadung = window.location.pathname.includes('/webgiadung');
                            const isLiveServer = window.location.port === '5500';
                            const backendBase = isLiveServer ? 'http://127.0.0.1:8000' : '';
                            const base = backendBase + (isWebgiadung ? '/webgiadung/' : '/');
                            
                            if (!img) return base + 'images/products/air_fryer.jpg';
                            if (img.startsWith('http://') || img.startsWith('https://')) return img;
                            
                            let clean = img.replace(/^public\//, '').replace(/^\//, '');
                            if (clean.startsWith('products/')) {
                                clean = 'storage/' + clean;
                            }
                            // If the image doesn't have storage/ or public/ prefix, assume it's public
                            if (!clean.startsWith('storage/') && !clean.startsWith('images/') && !clean.startsWith('public/')) {
                                // Fallback
                            }
                            return base + clean;
                        }

                        function createProductCardHtml(p) {
                            const isWebgiadung = window.location.pathname.includes('/webgiadung');
                            const base = isWebgiadung ? '/webgiadung/' : '/';
                            const catSlug = p.category_slug || getCatSlug(p.category, p.name);
                            const img = normalizeImgUrl(p.image);
                            const priceFmt = formatVnd(p.price);
                            const oldPrice = p.price > 0 ? formatVnd(Math.round(p.price * 1.2 / 10000) * 10000) : '';
                            const specs = p.description ? (p.description.length > 55 ? p.description.substring(0, 55) + '...' : p.description) : 'Bảo hành chính hãng 24T';
                            const stock = p.stock || p.quantity || 10;
                            const pNameEsc = (p.name || '').replace(/"/g, '&quot;');
                            const pNameSl = (p.name || '').replace(/'/g, "\\'");
                            const detailUrl = base + 'products/' + p.id;

                            return `
                <div class="product-card" data-category="${catSlug}" data-product-id="${p.id}" data-product-name="${pNameEsc}" data-product-price="${p.price}" data-product-img="${img}" onclick="goToProductDetail(${p.id}, event)" style="cursor: pointer;">
                    <span class="product-badge-flag badge-hot">Chính hãng</span>
                    <div class="product-img-box">
                        <a href="${detailUrl}" onclick="goToProductDetail(${p.id}, event)" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                            <img src="${img}" alt="${pNameEsc}" loading="lazy" onerror="this.onerror=null; this.src='${base}images/products/air_fryer.jpg'">
                        </a>
                        <div class="card-quick-actions" onclick="event.stopPropagation()">
                            <button type="button" class="quick-action-btn" title="Xem thông số kỹ thuật" onclick="openProductQuickSpecModal(${p.id})"><i class="fa-regular fa-eye"></i></button>
                            <button type="button" class="quick-action-btn btn-compare-action" data-compare-id="${p.id}" title="So sánh sản phẩm" onclick="toggleCompareProduct(${p.id}, event)"><i class="fa-solid fa-code-compare"></i></button>
                            <button type="button" class="quick-action-btn" title="Yêu thích" onclick="showToast('Đã lưu vào danh sách yêu thích!')"><i class="fa-regular fa-heart"></i></button>
                        </div>
                    </div>
                    <div class="product-category-name">${p.category || 'Gia dụng thông minh'}</div>
                    <h4 class="product-title" title="${pNameEsc}">
                        <a href="${detailUrl}" onclick="goToProductDetail(${p.id}, event)" style="color: inherit; text-decoration: none;">${pNameEsc}</a>
                    </h4>
                    <div class="product-specs">${specs}</div>
                    <div class="product-rating-box">
                        <div class="rating-stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i></div>
                        <span class="sold-count">| Còn lại ${stock} sp</span>
                    </div>
                    <div class="product-price-row">
                        <div>
                            <span class="price-current">${priceFmt}</span>
                            ${oldPrice ? `<span class="price-old">${oldPrice}</span>` : ''}
                        </div>
                        <span style="font-size: 0.75rem; color: #DC2626; font-weight: 700;">-18%</span>
                    </div>
                    <div style="display: flex; gap: 8px; margin-top: 12px;">
                        <button type="button" class="btn-buy-now" style="flex: 1.2; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 10px 12px; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; font-weight: 700; font-size: 0.85rem; border: none; border-radius: 9999px; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(197, 160, 89, 0.25);" onclick="proceedToRealCheckout(${p.id}, '${pNameSl}', ${p.price}, '${img}', event)">
                            <i class="fa-solid fa-bolt-lightning"></i> Mua ngay
                        </button>
                        <button type="button" class="btn-add-cart" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 9px 10px; background: #FFFFFF; color: #8C6A24; border: 1px solid #EAE2D5; font-weight: 600; font-size: 0.825rem; border-radius: 9999px; cursor: pointer; transition: all 0.2s;" onclick="addToCart(${p.id}, '${pNameSl}', ${p.price}, '${img}')">
                            <i class="fa-solid fa-cart-plus"></i> Giỏ
                        </button>
                    </div>
                </div>
            `;
                        }

                        window.renderLiveHomepageProducts = function (products) {
                            if (!Array.isArray(products) || products.length === 0) return;

                            const grid = document.querySelector('.products-grid');
                            if (!grid) return;

                            const counts = { 'all': products.length };

                            // Update existing cards or create HTML for all
                            const incomingIds = new Set(products.map(p => String(p.id)));

                            // Remove any card deleted - only if incoming list is complete (length >= 8)
                            const currentCards = grid.querySelectorAll('.product-card');
                            if (products.length >= 8 || currentCards.length <= products.length) {
                                currentCards.forEach(card => {
                                    const cid = card.getAttribute('data-product-id');
                                    if (cid && !incomingIds.has(String(cid))) {
                                        card.remove();
                                    }
                                });
                            }

                            // Update or Insert cards
                            products.forEach(p => {
                                const catSlug = p.category_slug || getCatSlug(p.category, p.name);
                                counts[catSlug] = (counts[catSlug] || 0) + 1;

                                const img = normalizeImgUrl(p.image);
                                const priceFmt = formatVnd(p.price);
                                const oldPrice = p.price > 0 ? formatVnd(Math.round(p.price * 1.2 / 10000) * 10000) : '';
                                const specs = p.description ? (p.description.length > 55 ? p.description.substring(0, 55) + '...' : p.description) : 'Bảo hành chính hãng 24T';
                                const stock = p.stock || p.quantity || 10;
                                const pNameEsc = (p.name || '').replace(/"/g, '&quot;');
                                const pNameSl = (p.name || '').replace(/'/g, "\\'");
                                const isWebgiadung = window.location.pathname.includes('/webgiadung');
                                const detailUrl = (isWebgiadung ? '/webgiadung/products/' : '/products/') + p.id;

                                let card = grid.querySelector(`.product-card[data-product-id="${p.id}"]`);
                                if (card) {
                                    // Detect if anything changed
                                    const prevPrice = card.getAttribute('data-product-price');
                                    const prevName = card.getAttribute('data-product-name');
                                    const prevImg = card.getAttribute('data-product-img');
                                    const hasChanged = prevPrice != p.price || prevName != p.name || prevImg != img;

                                    card.setAttribute('data-category', catSlug);
                                    card.setAttribute('data-product-name', p.name);
                                    card.setAttribute('data-product-price', p.price);
                                    card.setAttribute('data-product-img', img);
                                    card.onclick = function (e) { goToProductDetail(p.id, e); };
                                    card.style.cursor = 'pointer';

                                    const titleEl = card.querySelector('.product-title');
                                    if (titleEl) {
                                        titleEl.innerHTML = `<a href="${detailUrl}" onclick="goToProductDetail(${p.id}, event)" style="color: inherit; text-decoration: none;">${p.name}</a>`;
                                        titleEl.title = p.name;
                                    }

                                    const catEl = card.querySelector('.product-category-name');
                                    if (catEl) catEl.textContent = p.category || 'Gia Dụng';

                                    const priceEl = card.querySelector('.price-current');
                                    if (priceEl) priceEl.textContent = priceFmt;

                                    const oldPriceEl = card.querySelector('.price-old');
                                    if (oldPriceEl && oldPrice) oldPriceEl.textContent = oldPrice;

                                    const specsEl = card.querySelector('.product-specs');
                                    if (specsEl) specsEl.textContent = specs;

                                    const soldEl = card.querySelector('.sold-count');
                                    if (soldEl) soldEl.textContent = `| Còn lại ${stock} sp`;

                                    const imgEl = card.querySelector('.product-img-box img');
                                    if (imgEl && imgEl.getAttribute('src') !== img) {
                                        imgEl.src = img;
                                        imgEl.alt = p.name;
                                    }

                                    // Update action buttons onclick
                                    const buyBtn = card.querySelector('.btn-buy-now');
                                    if (buyBtn) {
                                        buyBtn.onclick = function (e) { proceedToRealCheckout(p.id, p.name, p.price, img, e); };
                                    }
                                    const addBtn = card.querySelector('.btn-add-cart');
                                    if (addBtn) {
                                        addBtn.onclick = function (e) { addToCart(p.id, p.name, p.price, img, e); };
                                    }

                                    if (hasChanged) {
                                        card.style.transition = 'box-shadow 0.4s ease, transform 0.3s ease';
                                        card.style.boxShadow = '0 0 25px rgba(197, 160, 89, 0.6)';
                                        card.style.transform = 'scale(1.02)';
                                        setTimeout(() => {
                                            card.style.boxShadow = '';
                                            card.style.transform = '';
                                        }, 1800);
                                    }
                                    grid.appendChild(card);
                                } else {
                                    // Create new card and append
                                    const temp = document.createElement('div');
                                    temp.innerHTML = createProductCardHtml(p).trim();
                                    const newCard = temp.firstElementChild;
                                    grid.appendChild(newCard);
                                }

                                // Also update Bento Showcase if matching
                                const bentoCard = document.querySelector(`.bento-card[data-product-id="${p.id}"]`);
                                if (bentoCard) {
                                    bentoCard.setAttribute('data-product-name', p.name);
                                    bentoCard.setAttribute('data-product-price', p.price);
                                    bentoCard.setAttribute('data-product-img', img);
                                    const bTitle = bentoCard.querySelector('.bento-card-title');
                                    if (bTitle) bTitle.textContent = p.name;
                                    const bPrice = bentoCard.querySelector('.bento-price');
                                    if (bPrice) bPrice.textContent = priceFmt;
                                    const bImg = bentoCard.querySelector('img');
                                    if (bImg && img) bImg.src = img;
                                    const bBuy = bentoCard.querySelector('.btn-bento-buy');
                                    if (bBuy) bBuy.onclick = function (e) { proceedToRealCheckout(p.id, p.name, p.price, img, e); };
                                    const bCart = bentoCard.querySelector('.btn-bento-cart');
                                    if (bCart) bCart.onclick = function (e) { addToCart(p.id, p.name, p.price, img, e); };
                                }
                            });

                            // Update category pills counters
                            for (const [k, count] of Object.entries(counts)) {
                                const pillCount = document.querySelector(`.cat-pill[data-category="${k}"] .count`);
                                if (pillCount) pillCount.textContent = count;
                            }

                            // Luôn đồng bộ phân trang tối đa 2 hàng (8 sản phẩm / trang)
                            if (typeof window.updateProductsDisplayAndPagination === 'function') {
                                window.updateProductsDisplayAndPagination();
                            }
                        };

                        window.syncHomepageProducts = async function (productsOverride) {
                            if (Array.isArray(productsOverride) && productsOverride.length > 0) {
                                renderLiveHomepageProducts(productsOverride);
                                try {
                                    localStorage.setItem('family_synced_products', JSON.stringify(productsOverride));
                                } catch (e) { }
                                return;
                            }

                            const isWebgiadung = window.location.pathname.includes('/webgiadung');
                            const url = isWebgiadung ? '/webgiadung/api/products' : '/api/products';

                            try {
                                const controller = new AbortController();
                                const timeoutId = setTimeout(() => controller.abort(), 3000);
                                const res = await fetch(url, { signal: controller.signal, headers: { 'Accept': 'application/json' } });
                                clearTimeout(timeoutId);
                                if (res.ok) {
                                    const data = await res.json();
                                    if (data && data.success && Array.isArray(data.products) && data.products.length > 0) {
                                        renderLiveHomepageProducts(data.products);
                                        try {
                                            localStorage.setItem('family_synced_products', JSON.stringify(data.products));
                                        } catch (e) { }
                                    }
                                }
                            } catch (err) { }
                        };

                        // Lắng nghe sự kiện đồng bộ khi Admin cập nhật sản phẩm ở tab khác
                        window.addEventListener('storage', function (e) {
                            if (e.key === 'family_synced_products' || e.key === 'family_products_last_update') {
                                try {
                                    if (e.newValue) {
                                        if (e.key === 'family_synced_products') {
                                            const parsed = JSON.parse(e.newValue);
                                            renderLiveHomepageProducts(parsed);
                                        } else {
                                            syncHomepageProducts();
                                        }
                                    }
                                } catch (err) { }
                            }
                        });

                        // Lắng nghe qua BroadcastChannel khi Admin cập nhật
                        if (window.BroadcastChannel) {
                            const bc = new BroadcastChannel('family_products_channel');
                            bc.onmessage = function (e) {
                                if (e.data && e.data.products) {
                                    renderLiveHomepageProducts(e.data.products);
                                    try {
                                        localStorage.setItem('family_synced_products', JSON.stringify(e.data.products));
                                    } catch (err) { }
                                }
                            };
                        }
                    })();
                </script>

                <!-- Hệ Thống Chat Trực Tuyến Khách Hàng & Tư Vấn Viên -->
                <script src="{{ asset('js/chat-widget.js') }}"></script>

                <!-- Hệ Thống Đánh Giá & Cảm Nhận Khách Hàng -->
                <script src="{{ asset('js/reviews.js') }}"></script>
</body>

</html>
