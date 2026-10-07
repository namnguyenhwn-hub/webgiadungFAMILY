@extends('layouts.app')

@section('title', 'FAMILY LUXE - Thiết bị gia dụng thông minh & đẳng cấp hoàng gia')

@section('content')

    <!-- ==========================================================================
         1. HERO BANNER SECTION (TRANG CHỦ)
         ========================================================================== -->
    <section class="hero-section" id="trang-chu" style="padding: 20px 0;">
        <div class="container">
            <!-- Full Width Visual Banner -->
            <div style="width: 100%; border-radius: 24px; overflow: hidden; box-shadow: 0 24px 48px rgba(0,0,0,0.12); position: relative; display: block;">
                @php
                    $banners = [];
                    for ($i = 1; $i <= 3; $i++) {
                        $path = public_path('images/banner_home_' . $i . '.jpg');
                        if (file_exists($path)) {
                            $banners[] = asset('images/banner_home_' . $i . '.jpg') . '?v=' . filemtime($path);
                        }
                    }
                    if (empty($banners)) {
                        $banners[] = asset('images/products/air_fryer.jpg');
                    }
                @endphp
                <div style="padding-top: 40%; /* Tương đương tỷ lệ 2.5:1 */"></div>
                <div id="heroBannerCarousel" class="carousel slide" data-bs-ride="carousel" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                    <div class="carousel-indicators" style="margin-bottom: 0;">
                        @foreach($banners as $index => $bannerUrl)
                            <button type="button" data-bs-target="#heroBannerCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner" style="height: 100%;">
                        @foreach($banners as $index => $bannerUrl)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" style="height: 100%;">
                                @if($index === 0)
                                    <a href="#san-pham" style="display: block; width: 100%; height: 100%;">
                                        <img src="{{ $bannerUrl }}" class="d-block w-100 h-100" style="object-fit: cover;" alt="Banner {{ $index + 1 }}">
                                    </a>
                                @else
                                    <img src="{{ $bannerUrl }}" class="d-block w-100 h-100" style="object-fit: cover;" alt="Banner {{ $index + 1 }}">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================================================
         2. CATEGORY PILLS FILTER BAR
         ========================================================================== -->
    <section class="categories-section" id="danh-muc">
        <div class="container">
            <div class="category-pills-wrap">
                @foreach ($categories as $cat)
                    <button class="cat-pill {{ $cat['id'] === 'all' ? 'active' : '' }}" data-category="{{ $cat['id'] }}">
                        <i class="fa-solid {{ $cat['icon'] }}"></i>
                        <span>{{ $cat['name'] }}</span>
                        <span class="count">{{ $cat['count'] }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ==========================================================================
         3. BENTO GRID SHOWCASE (FLAGSHIP SHOWCASE)
         ========================================================================== -->
    <section class="bento-section" id="bento-showcase">
        <div class="container">
            <div class="section-header">
                <span class="section-subtitle">Kiến trúc thiết kế Bento Box</span>
                <h2 class="section-title">Tuyệt tác gia dụng <span class="text-gold-gradient font-serif">FAMILY Collection</span></h2>
                <p class="section-desc">
                    Mỗi khối thiết kế đại diện cho một sản phẩm đột phá công nghệ, hoàn thiện với kim loại cao cấp và viền vàng Gold vương giả.
                </p>
            </div>

            <div class="bento-grid">
                <!-- Bento 1: Main Flagship Hero Card (Span 7) -->
                <div class="bento-card bento-hero" data-product-id="101" data-product-name="{{ $bentoItems['hero']['name'] }}" data-product-price="{{ $bentoItems['hero']['price'] }}" data-product-img="{{ $bentoItems['hero']['image'] }}">
                    <div class="bento-inner">
                        <div>
                            <span class="bento-badge-gold">{{ $bentoItems['hero']['badge'] }}</span>
                            <h3 class="bento-card-title">{{ $bentoItems['hero']['name'] }}</h3>
                            <p class="bento-card-desc">{{ $bentoItems['hero']['subtitle'] }}</p>

                            <ul class="bento-features-list">
                                @foreach ($bentoItems['hero']['features'] as $feat)
                                    <li><i class="fa-solid fa-circle-check"></i> {{ $feat }}</li>
                                @endforeach
                            </ul>

                            <div class="bento-price-wrap">
                                <span class="bento-price">{{ number_format($bentoItems['hero']['price'], 0, ',', '.') }} ₫</span>
                                <span class="bento-price-old">{{ number_format($bentoItems['hero']['original_price'], 0, ',', '.') }} ₫</span>
                                <span class="bento-discount-tag">{{ $bentoItems['hero']['discount'] }}</span>
                            </div>

                            <div style="display: flex; gap: 10px; margin-top: 16px;">
                                <a href="{{ route('checkout.form', ['buy_now_product_id' => 101], false) }}" class="btn-buy-now btn-bento-buy" style="padding: 10px 20px; font-size: 0.875rem; font-weight: 700; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; border: none; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(197, 160, 89, 0.25); text-decoration: none;">
                                    <i class="fa-solid fa-bolt-lightning"></i> Mua hàng
                                </a>
                                <form action="{{ route('cart.add', ['id' => 101]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-outline-gold btn-bento-cart" style="padding: 10px 18px; font-size: 0.875rem; font-weight: 600; background: #FFFFFF; color: #8C6A24; border: 1px solid #EAE2D5; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                                        <i class="fa-solid fa-cart-plus"></i> Giỏ
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="bento-hero-img-box">
                            <img src="{{ $bentoItems['hero']['image'] }}" alt="{{ $bentoItems['hero']['name'] }}">
                        </div>
                    </div>
                </div>

                <!-- Bento 2: Fridge French-Door (Span 5) -->
                <div class="bento-card bento-fridge" data-product-id="102" data-product-name="{{ $bentoItems['fridge']['name'] }}" data-product-price="{{ $bentoItems['fridge']['price'] }}" data-product-img="{{ $bentoItems['fridge']['image'] }}">
                    <div>
                        <span class="bento-badge-gold">{{ $bentoItems['fridge']['badge'] }}</span>
                        <h3 class="bento-card-title">{{ $bentoItems['fridge']['name'] }}</h3>
                        <p class="bento-card-desc">{{ $bentoItems['fridge']['subtitle'] }}</p>
                    </div>

                    <div class="bento-media-split">
                        <img src="{{ $bentoItems['fridge']['image'] }}" alt="{{ $bentoItems['fridge']['name'] }}">
                        <div>
                            <ul class="bento-features-list" style="margin-bottom: 12px;">
                                @foreach ($bentoItems['fridge']['features'] as $feat)
                                    <li style="font-size: 0.8rem;"><i class="fa-solid fa-check" style="color:var(--gold-primary);"></i> {{ $feat }}</li>
                                @endforeach
                            </ul>
                            <div class="bento-price" style="font-size: 1.35rem; margin-bottom: 10px;">
                                {{ number_format($bentoItems['fridge']['price'], 0, ',', '.') }} ₫
                            </div>
                            <div style="display: flex; gap: 8px; margin-top: 4px;">
                                <a href="{{ route('checkout.form', ['buy_now_product_id' => 102], false) }}" class="btn-buy-now btn-bento-buy" style="flex: 1.2; padding: 10px 12px; font-size: 0.825rem; font-weight: 700; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; border: none; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 5px; box-shadow: 0 4px 12px rgba(197, 160, 89, 0.25); text-decoration: none;">
                                    <i class="fa-solid fa-bolt-lightning"></i> Mua hàng
                                </a>
                                <form action="{{ route('cart.add', ['id' => 102]) }}" method="POST" style="flex: 1; display:flex;">
                                    @csrf
                                    <button type="submit" class="btn-outline-gold btn-bento-cart" style="width: 100%; padding: 10px 10px; font-size: 0.825rem; font-weight: 600; background: #FFFFFF; color: #8C6A24; border: 1px solid #EAE2D5; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 5px;">
                                        <i class="fa-solid fa-cart-plus"></i> Giỏ
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bento 3: Dual Induction Hob (Span 4) -->
                <div class="bento-card bento-span-4" data-product-id="103" data-product-name="{{ $bentoItems['hob']['name'] }}" data-product-price="{{ $bentoItems['hob']['price'] }}" data-product-img="{{ $bentoItems['hob']['image'] }}">
                    <div class="card-top-content">
                        <span class="bento-badge-gold">{{ $bentoItems['hob']['badge'] }}</span>
                        <h3 class="bento-card-title" style="font-size: 1.2rem;">{{ $bentoItems['hob']['name'] }}</h3>
                        <p class="bento-card-desc" style="font-size: 0.825rem;">{{ $bentoItems['hob']['subtitle'] }}</p>
                    </div>

                    <div class="card-img-container">
                        <img src="{{ $bentoItems['hob']['image'] }}" alt="{{ $bentoItems['hob']['name'] }}">
                    </div>

                    <div class="bento-card-footer" style="display: flex; align-items: flex-end; justify-content: space-between; gap: 8px;">
                        <div>
                            <span style="font-size: 0.7rem; color: var(--text-muted); display: block;">Giá ưu đãi VIP</span>
                            <span class="bento-price" style="font-size: 1.15rem;">{{ number_format($bentoItems['hob']['price'], 0, ',', '.') }} ₫</span>
                        </div>
                        <div style="display: flex; gap: 6px;">
                            <a href="{{ route('checkout.form', ['buy_now_product_id' => 103], false) }}" class="btn-buy-now btn-bento-buy" style="padding: 7px 11px; font-size: 0.78rem; font-weight: 700; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; border: none; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 3px 8px rgba(197, 160, 89, 0.25); text-decoration: none;">
                                <i class="fa-solid fa-bolt-lightning"></i> Mua hàng
                            </a>
                            <form action="{{ route('cart.add', ['id' => 103]) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-outline-gold btn-bento-cart" style="padding: 7px 10px; font-size: 0.78rem; font-weight: 600; background: #FFFFFF; color: #8C6A24; border: 1px solid #EAE2D5; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fa-solid fa-cart-plus"></i> Giỏ
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Bento 4: Japanese IH Rice Cooker (Span 4) -->
                <div class="bento-card bento-span-4" data-product-id="104" data-product-name="{{ $bentoItems['rice_cooker']['name'] }}" data-product-price="{{ $bentoItems['rice_cooker']['price'] }}" data-product-img="{{ $bentoItems['rice_cooker']['image'] }}">
                    <div class="card-top-content">
                        <span class="bento-badge-gold">{{ $bentoItems['rice_cooker']['badge'] }}</span>
                        <h3 class="bento-card-title" style="font-size: 1.2rem;">{{ $bentoItems['rice_cooker']['name'] }}</h3>
                        <p class="bento-card-desc" style="font-size: 0.825rem;">{{ $bentoItems['rice_cooker']['subtitle'] }}</p>
                    </div>

                    <div class="card-img-container">
                        <img src="{{ $bentoItems['rice_cooker']['image'] }}" alt="{{ $bentoItems['rice_cooker']['name'] }}">
                    </div>

                    <div class="bento-card-footer" style="display: flex; align-items: flex-end; justify-content: space-between; gap: 8px;">
                        <div>
                            <span style="font-size: 0.7rem; color: var(--text-muted); display: block;">Giá ưu đãi VIP</span>
                            <span class="bento-price" style="font-size: 1.15rem;">{{ number_format($bentoItems['rice_cooker']['price'], 0, ',', '.') }} ₫</span>
                        </div>
                        <div style="display: flex; gap: 6px;">
                            <a href="{{ route('checkout.form', ['buy_now_product_id' => 104], false) }}" class="btn-buy-now btn-bento-buy" style="padding: 7px 11px; font-size: 0.78rem; font-weight: 700; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; border: none; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 3px 8px rgba(197, 160, 89, 0.25); text-decoration: none;">
                                <i class="fa-solid fa-bolt-lightning"></i> Mua hàng
                            </a>
                            <form action="{{ route('cart.add', ['id' => 104]) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-outline-gold btn-bento-cart" style="padding: 7px 10px; font-size: 0.78rem; font-weight: 600; background: #FFFFFF; color: #8C6A24; border: 1px solid #EAE2D5; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fa-solid fa-cart-plus"></i> Giỏ
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Bento 5: AI Robot Vacuum S9 Pro (Span 4) -->
                <div class="bento-card bento-span-4" data-product-id="105" data-product-name="{{ $bentoItems['vacuum']['name'] }}" data-product-price="{{ $bentoItems['vacuum']['price'] }}" data-product-img="{{ $bentoItems['vacuum']['image'] }}">
                    <div class="card-top-content">
                        <span class="bento-badge-gold">{{ $bentoItems['vacuum']['badge'] }}</span>
                        <h3 class="bento-card-title" style="font-size: 1.2rem;">{{ $bentoItems['vacuum']['name'] }}</h3>
                        <p class="bento-card-desc" style="font-size: 0.825rem;">{{ $bentoItems['vacuum']['subtitle'] }}</p>
                    </div>

                    <div class="card-img-container">
                        <img src="{{ $bentoItems['vacuum']['image'] }}" alt="{{ $bentoItems['vacuum']['name'] }}">
                    </div>

                    <div class="bento-card-footer" style="display: flex; align-items: flex-end; justify-content: space-between; gap: 8px;">
                        <div>
                            <span style="font-size: 0.7rem; color: var(--text-muted); display: block;">Giá ưu đãi VIP</span>
                            <span class="bento-price" style="font-size: 1.15rem;">{{ number_format($bentoItems['vacuum']['price'], 0, ',', '.') }} ₫</span>
                        </div>
                        <div style="display: flex; gap: 6px;">
                            <a href="{{ route('checkout.form', ['buy_now_product_id' => 105], false) }}" class="btn-buy-now btn-bento-buy" style="padding: 7px 11px; font-size: 0.78rem; font-weight: 700; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; border: none; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 3px 8px rgba(197, 160, 89, 0.25); text-decoration: none;">
                                <i class="fa-solid fa-bolt-lightning"></i> Mua hàng
                            </a>
                            <form action="{{ route('cart.add', ['id' => 105]) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-outline-gold btn-bento-cart" style="padding: 7px 10px; font-size: 0.78rem; font-weight: 600; background: #FFFFFF; color: #8C6A24; border: 1px solid #EAE2D5; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fa-solid fa-cart-plus"></i> Giỏ
                                </button>
                            </form>
                        </div>
                    </div>
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
            <div id="activeSearchBanner" style="{{ !empty($searchQuery) ? '' : 'display: none;' }}">
                @if(!empty($searchQuery))
                    <div class="search-active-banner">
                        <span><i class="fa-solid fa-magnifying-glass"></i> Kết quả tìm kiếm cho: "<strong>{{ $searchQuery }}</strong>" ({{ count($products) }} sản phẩm)</span>
                        <a href="{{ route('home') }}#san-pham" class="btn-clear-search"><i class="fa-solid fa-xmark"></i> Xóa lọc</a>
                    </div>
                @endif
            </div>

            <!-- Grid Items -->
            <div class="products-grid">
                @forelse ($products as $prod)
                    <div class="product-card" data-category="{{ $prod['category'] }}" data-product-id="{{ $prod['id'] }}" data-product-name="{{ $prod['name'] }}" data-product-price="{{ $prod['price'] }}" data-product-img="{{ $prod['image'] }}" style="{{ $loop->index >= 8 ? 'display: none; cursor: pointer;' : 'cursor: pointer;' }}">
                        <!-- Badge -->
                        <span class="product-badge-flag badge-{{ $prod['badge_type'] }}">
                            {{ $prod['badge'] }}
                        </span>

                        <!-- Image Box -->
                        <div class="product-img-box">
                            <img src="{{ $prod['image'] }}" alt="{{ $prod['name'] }}" loading="lazy">
                            <div class="card-quick-actions">
                                <button class="quick-action-btn" title="Xem nhanh thông số" onclick="alert('Thông số kỹ thuật: {{ $prod['specs'] }}\nBảo hành chính hãng 24 tháng.')">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                                <button class="quick-action-btn" title="Thêm vào danh sách yêu thích" onclick="showToast('Đã lưu vào danh sách yêu thích!')">
                                    <i class="fa-regular fa-heart"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="product-category-name">{{ $prod['category_name'] }}</div>
                        <h4 class="product-title" title="{{ $prod['name'] }}">{{ $prod['name'] }}</h4>
                        <div class="product-specs">{{ $prod['specs'] }}</div>

                        <!-- Rating & Sold count -->
                        <div class="product-rating-box">
                            <div class="rating-stars">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star-half-stroke"></i>
                            </div>
                            <span class="sold-count">| Đã bán {{ $prod['sold'] }}</span>
                        </div>

                        <!-- Price -->
                        <div class="product-price-row">
                            <div>
                                <span class="price-current">{{ number_format($prod['price'], 0, ',', '.') }} ₫</span>
                                <span class="price-old">{{ number_format($prod['original_price'], 0, ',', '.') }} ₫</span>
                            </div>
                            <span style="font-size: 0.75rem; color: #DC2626; font-weight: 700;">{{ $prod['discount'] }}</span>
                        </div>

                        <!-- Action Buttons: Mua Hàng (Yêu cầu đăng nhập) & Thêm Giỏ (Yêu cầu đăng nhập) -->
                        <div class="product-card-actions" style="display: flex; gap: 8px; margin-top: 12px;">
                            <form action="{{ route('cart.buyNow', $prod['id']) }}" method="POST" style="flex: 1.2;">
                                @csrf
                                <button type="submit" class="btn-buy-now" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 10px 12px; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; font-weight: 700; font-size: 0.85rem; border: none; border-radius: 9999px; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(197, 160, 89, 0.25);" title="Mua hàng ngay">
                                    <i class="fa-solid fa-bolt-lightning"></i> Mua hàng
                                </button>
                            </form>
                            <form action="{{ route('cart.add', $prod['id']) }}" method="POST" style="flex: 1;">
                                @csrf
                                <button type="submit" class="btn-add-cart-outline" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 9px 10px; background: #FFFFFF; color: #8C6A24; border: 1px solid #EAE2D5; font-weight: 600; font-size: 0.825rem; border-radius: 9999px; cursor: pointer; transition: all 0.2s;" title="Thêm vào giỏ">
                                    <i class="fa-solid fa-cart-plus"></i> + Giỏ
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-search-state">
                        <i class="fa-solid fa-box-open"></i>
                        <h3>Không tìm thấy sản phẩm</h3>
                        <p>Không có sản phẩm nào phù hợp với từ khóa "{{ $searchQuery }}".</p>
                        <a href="{{ route('home') }}#san-pham" class="btn-clear-search">Xem tất cả sản phẩm</a>
                    </div>
                @endforelse
            </div>

            <!-- Phân Trang Sang Trọng Chuẩn Thiết Kế -->
            <div class="products-pagination-container" id="productsPagination"></div>
        </div>
    </section>

    <!-- ==========================================================================
         5. CAM KẾT VÀNG & ĐẶC QUYỀN DỊCH VỤ
         ========================================================================== -->
    <section class="commitments-section" id="cam-ket">
        <div class="container">
            <div class="commitments-grid">
                @foreach ($commitments as $item)
                    <div class="commitment-item">
                        <div class="commitment-icon-wrap">
                            <i class="fa-solid {{ $item['icon'] }}"></i>
                        </div>
                        <div class="commitment-info">
                            <h4>{{ $item['title'] }}</h4>
                            <p>{{ $item['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
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
                <h2 class="section-title">Khách hàng nói gì về <span class="text-gold-gradient font-serif">FAMILY LUXE</span></h2>
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
                @foreach ($reviews as $rev)
                    <div class="review-card">
                        <div>
                            <div class="review-top-meta">
                                <div class="review-stars">
                                    @for ($i = 0; $i < $rev['rating']; $i++)
                                        <i class="fa-solid fa-star"></i>
                                    @endfor
                                </div>
                                <span class="review-date">{{ $rev['date'] }}</span>
                            </div>

                            <div class="review-product-tag">
                                <i class="fa-solid fa-check-circle" style="color: #10B981;"></i> Đã mua: {{ $rev['product'] }}
                            </div>

                            <p class="review-body">
                                "{{ $rev['comment'] }}"
                            </p>
                        </div>

                        <div class="review-author">
                            <img src="{{ $rev['avatar'] }}" alt="{{ $rev['name'] }}" class="review-avatar">
                            <div class="review-author-info">
                                <h5>{{ $rev['name'] }}</h5>
                                <span>{{ $rev['role'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
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
                        Nhận thông báo độc quyền về các bộ sưu tập thiết bị gia dụng mới nhất và ưu đãi giảm giá lên tới 35% mỗi tháng.
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

    <!-- Hệ Thống Đánh Giá & Cảm Nhận Khách Hàng -->
    <script src="{{ asset('js/reviews.js') }}"></script>
    <script>
        // Fallback for public path
        if (typeof window.loadHomepageReviews !== 'function') {
            document.write('<script src="public/js/reviews.js"><\/script>');
        }
    </script>
@endsection

