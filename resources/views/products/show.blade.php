@extends('layouts.app')

@section('title', \App\Http\Controllers\ProductController::formatSentenceCase($product->name) . ' - Thông tin chi tiết & thông số kỹ thuật')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent p-0 mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted"><i class="bi bi-house me-1"></i>Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none text-muted">Sản phẩm</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">{{ \App\Http\Controllers\ProductController::formatSentenceCase($product->name) }}</li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-5">
        <div class="card-body p-4 p-lg-5">
            <div class="row g-4 g-lg-5">
                <!-- CỘT TRÁI: HÌNH ẢNH & CAM KẾT -->
                <div class="col-lg-5 text-center">
                    <div class="position-relative p-4 rounded-4 bg-light d-flex align-items-center justify-content-center mb-3" style="min-height: 380px; border: 1px solid #EAE2D5;">
                        <span class="badge bg-danger position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill fw-bold fs-7 shadow-sm">
                            <i class="bi bi-shield-check me-1"></i> Chính hãng 100%
                        </span>

                        @php
                            $imgSrc = $product->image ?: 'public/images/products/air_fryer.jpg';
                            if (!str_starts_with($imgSrc, 'http')) {
                                if (str_starts_with($imgSrc, 'products/')) {
                                    $imgSrc = 'storage/' . $imgSrc;
                                } elseif (str_starts_with($imgSrc, 'public/')) {
                                    $imgSrc = preg_replace('#^public/#', '', $imgSrc);
                                }
                                $imgSrc = asset($imgSrc);
                            }
                        @endphp
                        <img src="{{ $imgSrc }}" class="img-fluid rounded-3" alt="{{ $product->name }}" style="max-height: 340px; object-fit: contain; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    </div>

                    <!-- Cam kết dịch vụ VIP -->
                    <div class="row g-2 mt-2 text-start">
                        <div class="col-6">
                            <div class="p-2.5 rounded-3 bg-white border d-flex align-items-center gap-2">
                                <i class="fa-solid fa-truck-fast text-gold fs-5"></i>
                                <div>
                                    <div class="fw-bold small">Giao hỏa tốc</div>
                                    <small class="text-muted" style="font-size: 0.75rem;">Miễn phí nội thành</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2.5 rounded-3 bg-white border d-flex align-items-center gap-2">
                                <i class="fa-solid fa-arrows-rotate text-gold fs-5"></i>
                                <div>
                                    <div class="fw-bold small">Đổi trả 7 ngày</div>
                                    <small class="text-muted" style="font-size: 0.75rem;">Nếu lỗi từ nhà sản xuất</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CỘT PHẢI: THÔNG TIN SẢN PHẨM & CÁC CỘT THÔNG SỐ (XUẤT XỨ, GIÁ, CÔNG DỤNG, CHẤT LIỆU, THƯƠNG HIỆU) -->
                <div class="col-lg-7">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <span class="badge bg-secondary-subtle text-secondary border px-3 py-1.5 rounded-pill fw-semibold">
                            <i class="bi bi-folder2-open me-1"></i> {{ \App\Http\Controllers\ProductController::formatSentenceCase($product->category->name ?? ($product->category ?: 'Thiết bị gia dụng')) }}
                        </span>
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1.5 rounded-pill fw-semibold">
                            <i class="bi bi-patch-check me-1"></i> Thương hiệu: {{ $product->brand ?: 'Chính hãng' }}
                        </span>
                    </div>

                    <h1 class="h3 fw-bold text-dark mb-2">{{ \App\Http\Controllers\ProductController::formatSentenceCase($product->name) }}</h1>

                    <!-- Rating & Lượt bán -->
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <a href="#danh-gia-khach-hang" class="text-decoration-none text-warning small d-flex align-items-center gap-1" title="Xem đánh giá của khách hàng">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star-half-stroke"></i>
                            <span class="text-dark fw-bold ms-1" id="topRatingLabel">{{ $product->average_rating }}/5</span>
                        </a>
                        <span class="text-muted">|</span>
                        <a href="#danh-gia-khach-hang" class="text-decoration-none text-muted small">
                            <strong class="text-dark" id="topReviewsCountLabel">{{ $product->reviews_count }}</strong> đánh giá
                        </a>
                        <span class="text-muted">|</span>
                        <span class="text-muted small">Đã bán: <strong class="text-dark">{{ $product->sold ?: 86 }}</strong> sp</span>
                        <span class="text-muted">|</span>
                        <span class="text-muted small">Tình trạng: 
                            <strong class="{{ ($product->quantity ?? $product->stock) > 0 ? 'text-success' : 'text-danger' }}">
                                {{ ($product->quantity ?? $product->stock) > 0 ? 'Còn hàng (' . ($product->quantity ?? $product->stock) . ' sp)' : 'Tạm hết hàng' }}
                            </strong>
                        </span>
                    </div>

                    <!-- Bảng Giá bán nổi bật -->
                    <div class="p-3.5 p-md-4 rounded-4 mb-4" style="background: linear-gradient(135deg, rgba(197, 160, 89, 0.08) 0%, rgba(223, 192, 122, 0.16) 100%); border: 1px solid #DFC07A;">
                        <div class="d-flex flex-wrap align-items-baseline gap-3">
                            <span class="text-muted small">Giá bán chính thức:</span>
                            <span class="text-danger fw-bold fs-2" style="letter-spacing: -0.5px;">
                                {{ number_format($product->price, 0, ',', '.') }} ₫
                            </span>
                            @if($product->price > 0)
                                <span class="text-muted text-decoration-line-through fs-6">
                                    {{ number_format(round($product->price * 1.2 / 10000) * 10000, 0, ',', '.') }} ₫
                                </span>
                                <span class="badge bg-danger rounded-pill px-2.5 py-1 fw-bold">-18% Tiết kiệm</span>
                            @endif
                        </div>
                        <div class="text-muted small mt-1">
                            <i class="bi bi-check2-circle text-success me-1"></i> Giá đã bao gồm thuế VAT & bảo hành chính hãng 24 tháng tận nhà.
                        </div>
                    </div>

                    <!-- ==============================================================
                         BẢNG CỘT THÔNG SỐ CHI TIẾT SẢN PHẨM (XUẤT XỨ, THƯƠNG HIỆU, CÔNG DỤNG, CHẤT LIỆU, GIÁ BÁN)
                         ============================================================== -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark d-flex align-items-center gap-2 mb-3">
                            <i class="fa-solid fa-list-check text-gold"></i>
                            <span>Thông số & đặc tính sản phẩm</span>
                        </h5>
                        <div class="table-responsive rounded-3 border overflow-hidden">
                            <table class="table table-sm table-striped table-hover mb-0 align-middle">
                                <tbody>
                                    <tr>
                                        <td class="table-light fw-semibold text-muted px-3 py-2.5" style="width: 32%;">
                                            <i class="fa-solid fa-award text-gold me-2"></i>Thương hiệu:
                                        </td>
                                        <td class="px-3 py-2.5 fw-bold text-dark">
                                            {{ $product->brand ?: 'Chính hãng' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="table-light fw-semibold text-muted px-3 py-2.5">
                                            <i class="fa-solid fa-earth-americas text-gold me-2"></i>Xuất xứ:
                                        </td>
                                        <td class="px-3 py-2.5 fw-bold text-dark">
                                            <span class="badge bg-light text-dark border px-2.5 py-1">
                                                <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $product->origin ?: 'Việt Nam' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="table-light fw-semibold text-muted px-3 py-2.5">
                                            <i class="fa-solid fa-gem text-gold me-2"></i>Chất liệu chế tạo:
                                        </td>
                                        <td class="px-3 py-2.5 text-secondary">
                                            {{ $product->material ?: 'Hợp kim cao cấp & Nhựa ABS nguyên sinh' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="table-light fw-semibold text-muted px-3 py-2.5">
                                            <i class="fa-solid fa-bolt-lightning text-gold me-2"></i>Công dụng chính:
                                        </td>
                                        <td class="px-3 py-2.5 text-secondary">
                                            {{ $product->usage ?: ($product->description ?: 'Thiết bị gia dụng phục vụ nấu nướng và tiện ích gia đình') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="table-light fw-semibold text-muted px-3 py-2.5">
                                            <i class="fa-solid fa-tags text-gold me-2"></i>Giá bán niêm yết:
                                        </td>
                                        <td class="px-3 py-2.5 fw-bold text-danger">
                                            {{ number_format($product->price, 0, ',', '.') }} ₫
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="table-light fw-semibold text-muted px-3 py-2.5">
                                            <i class="fa-solid fa-box text-gold me-2"></i>Tình trạng kho:
                                        </td>
                                        <td class="px-3 py-2.5">
                                            <span class="badge {{ ($product->quantity ?? $product->stock) > 0 ? 'bg-success' : 'bg-danger' }}">
                                                {{ ($product->quantity ?? $product->stock) > 0 ? 'Còn hàng (' . ($product->quantity ?? $product->stock) . ' sp)' : 'Hết hàng' }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Cụm Nút Thao Tác (Mua ngay, Thêm giỏ hàng, So sánh sản phẩm) -->
                    <div class="d-flex flex-column flex-sm-row gap-2.5 pt-2 align-items-stretch">
                        <!-- Mua ngay -->
                        <form action="{{ route('cart.buyNow', $product->id, false) }}" method="POST" class="flex-grow-1 mb-0">
                            @csrf
                            <button type="submit" class="btn btn-gold w-100 py-3 fw-bold fs-6 d-flex align-items-center justify-content-center gap-2 shadow-sm rounded-pill" {{ ($product->quantity ?? $product->stock) <= 0 ? 'disabled' : '' }}>
                                <i class="fa-solid fa-bolt"></i> Mua ngay
                            </button>
                        </form>

                        <!-- Thêm giỏ hàng -->
                        <form action="{{ route('cart.add', $product->id, false) }}" method="POST" class="flex-grow-1 mb-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-dark w-100 py-3 fw-semibold fs-6 d-flex align-items-center justify-content-center gap-2 rounded-pill" style="border-color: #CBD5E1;" {{ ($product->quantity ?? $product->stock) <= 0 ? 'disabled' : '' }}>
                                <i class="fa-solid fa-cart-plus text-gold"></i> Thêm giỏ hàng
                            </button>
                        </form>

                        <!-- Nút SO SÁNH SẢN PHẨM THEO YÊU CẦU (Đồng bộ chuẩn tông màu Champagne Gold & White) -->
                        <button type="button" class="btn btn-outline-gold py-3 px-4 fw-semibold fs-6 d-flex align-items-center justify-content-center gap-2 shadow-sm rounded-pill flex-shrink-0" onclick="addAndOpenCompare({{ $product->id }})" title="So sánh sản phẩm này với các mẫu khác">
                            <i class="fa-solid fa-scale-balanced text-gold"></i>
                            <span>So sánh</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- TAB MÔ TẢ CHI TIẾT SẢN PHẨM -->
            <div class="mt-5 pt-4 border-top">
                <ul class="nav nav-tabs border-bottom" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold text-dark px-4 py-2.5" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button" role="tab">
                            <i class="bi bi-file-text me-1 text-gold"></i> Mô tả chi tiết
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold text-dark px-4 py-2.5" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs-pane" type="button" role="tab">
                            <i class="bi bi-sliders me-1 text-gold"></i> Thông số kỹ thuật đầy đủ
                        </button>
                    </li>
                </ul>
                <div class="tab-content p-4 bg-white border border-top-0 rounded-bottom-4 shadow-sm" id="productTabsContent">
                    <div class="tab-pane fade show active" id="desc-pane" role="tabpanel">
                        <div class="text-secondary lh-lg fs-6" style="white-space: pre-line;">
                            {{ $product->description ?: 'Hiện chưa có nội dung bài viết chi tiết cho thiết bị này.' }}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="specs-pane" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 bg-light border">
                                    <div class="text-muted small">Thương hiệu sản xuất</div>
                                    <div class="fw-bold fs-6">{{ $product->brand ?: 'Chính hãng' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 bg-light border">
                                    <div class="text-muted small">Nước sản xuất / Xuất xứ</div>
                                    <div class="fw-bold fs-6">{{ $product->origin ?: 'Việt Nam' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 bg-light border">
                                    <div class="text-muted small">Chất liệu cấu tạo</div>
                                    <div class="fw-bold fs-6">{{ $product->material ?: 'Hợp kim & Nhựa ABS' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 bg-light border">
                                    <div class="text-muted small">Công dụng & Tính năng</div>
                                    <div class="fw-bold fs-6">{{ $product->usage ?: 'Gia dụng thông minh' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         1. PHẦN CAM KẾT KHÁCH HÀNG (CUSTOMER COMMITMENTS & PRIVILEGES)
         ========================================================================= -->
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-5" style="border: 1px solid #EAE2D5 !important;">
        <div class="card-body p-4 p-lg-5 bg-white">
            <div class="text-center max-w-700 mx-auto mb-4 pb-2">
                <span class="badge px-3 py-1.5 rounded-pill fw-semibold mb-2" style="background: rgba(197, 160, 89, 0.12); color: #9A7B38; border: 1px solid rgba(197, 160, 89, 0.3); font-size: 0.8rem;">
                    <i class="fa-solid fa-crown me-1 text-gold"></i> Đặc quyền khách hàng FAMILY
                </span>
                <h3 class="fw-bold text-dark fs-4 mb-2">Cam kết chất lượng & dịch vụ vượt trội</h3>
                <p class="text-muted small mb-0">Mỗi thiết bị trao đến tay khách hàng đều đi kèm trọn bộ bảo chứng chất lượng và dịch vụ chuẩn 5 sao từ FAMILY HOME APPLIANCES</p>
            </div>

            <div class="row g-3 g-lg-4">
                <!-- Cam kết 1: Bảo hành -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="p-3.5 h-100 rounded-3 border bg-light d-flex flex-column align-items-center text-center gap-2" style="transition: transform 0.2s, box-shadow 0.2s; border-color: #F1F5F9 !important;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.06)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 54px; height: 54px; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); font-size: 1.35rem;">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h5 class="fw-bold fs-6 mb-1 text-dark">Bảo hành 24 tháng</h5>
                        <p class="text-muted small mb-0" style="font-size: 0.825rem; line-height: 1.5;">Kích hoạt bảo hành điện tử nhanh chóng, kỹ thuật viên hỗ trợ kiểm tra và sửa chữa tận nhà trong 24h.</p>
                    </div>
                </div>

                <!-- Cam kết 2: Đổi mới -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="p-3.5 h-100 rounded-3 border bg-light d-flex flex-column align-items-center text-center gap-2" style="transition: transform 0.2s, box-shadow 0.2s; border-color: #F1F5F9 !important;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.06)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 54px; height: 54px; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); font-size: 1.35rem;">
                            <i class="fa-solid fa-arrow-rotate-left"></i>
                        </div>
                        <h5 class="fw-bold fs-6 mb-1 text-dark">1 đổi 1 trong 30 ngày</h5>
                        <p class="text-muted small mb-0" style="font-size: 0.825rem; line-height: 1.5;">Đổi máy mới 100% nguyên seal hoàn toàn miễn phí nếu phát sinh bất kỳ lỗi kỹ thuật nào từ nhà sản xuất.</p>
                    </div>
                </div>

                <!-- Cam kết 3: Vận chuyển -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="p-3.5 h-100 rounded-3 border bg-light d-flex flex-column align-items-center text-center gap-2" style="transition: transform 0.2s, box-shadow 0.2s; border-color: #F1F5F9 !important;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.06)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 54px; height: 54px; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); font-size: 1.35rem;">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                        <h5 class="fw-bold fs-6 mb-1 text-dark">Giao hàng & lắp đặt 2h</h5>
                        <p class="text-muted small mb-0" style="font-size: 0.825rem; line-height: 1.5;">Giao hỏa tốc nội thành 2h, miễn phí giao hàng toàn quốc. Lắp đặt và hướng dẫn tận tình bởi kỹ sư FAMILY.</p>
                    </div>
                </div>

                <!-- Cam kết 4: Xuất xứ chính hãng -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="p-3.5 h-100 rounded-3 border bg-light d-flex flex-column align-items-center text-center gap-2" style="transition: transform 0.2s, box-shadow 0.2s; border-color: #F1F5F9 !important;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.06)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 54px; height: 54px; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); font-size: 1.35rem;">
                            <i class="fa-solid fa-gem"></i>
                        </div>
                        <h5 class="fw-bold fs-6 mb-1 text-dark">Chính hãng 100% hoàn tiền</h5>
                        <p class="text-muted small mb-0" style="font-size: 0.825rem; line-height: 1.5;">Cam kết 100% sản phẩm có nguồn gốc chuẩn châu Âu & Nhật Bản, chứng từ CO/CQ đầy đủ; hoàn tiền 200% nếu hàng giả.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         2. PHẦN ĐÁNH GIÁ SẢN PHẨM CÓ THỂ ĐÁNH GIÁ ĐƯỢC (REVIEWS & RATING ENGINE)
         ========================================================================= -->
    <div id="danh-gia-khach-hang" class="card shadow-sm border-0 rounded-4 overflow-hidden mb-5" style="border: 1px solid #EAE2D5 !important;">
        <div class="card-body p-4 p-lg-5 bg-white">
            <!-- Tiêu đề phần đánh giá -->
            <div class="d-flex flex-wrap align-items-center justify-content-between border-bottom pb-4 mb-4 gap-3">
                <div>
                    <span class="badge px-3 py-1.5 rounded-pill fw-semibold mb-2" style="background: rgba(197, 160, 89, 0.12); color: #9A7B38; border: 1px solid rgba(197, 160, 89, 0.3); font-size: 0.8rem;">
                        <i class="fa-solid fa-star me-1 text-gold"></i> Đánh giá từ khách hàng
                    </span>
                    <h3 class="fw-bold text-dark fs-4 mb-1">Đánh giá & nhận xét của khách hàng</h3>
                    <p class="text-muted small mb-0">Chia sẻ trải nghiệm thực tế để giúp cộng đồng mua sắm đưa ra lựa chọn hoàn hảo nhất</p>
                </div>
                <div>
                    <button type="button" class="btn btn-gold px-4 py-2.5 fw-bold rounded-pill shadow-sm d-inline-flex align-items-center gap-2" onclick="focusReviewForm()">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Viết đánh giá của bạn</span>
                    </button>
                </div>
            </div>

            <!-- Tổng quan điểm số đánh giá (Rating Summary Box) -->
            <div class="p-4 rounded-4 mb-4" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                <div class="row align-items-center g-3">
                    <div class="col-12 col-md-4 text-center border-md-end">
                        <div class="fw-bold text-dark display-5 mb-1" id="summaryScoreNum">{{ $product->average_rating }}</div>
                        <div class="text-warning fs-5 mb-1" id="summaryScoreStars">
                            @php
                                $rScore = round($product->average_rating);
                            @endphp
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fa-{{ $i <= $rScore ? 'solid' : 'regular' }} fa-star"></i>
                            @endfor
                        </div>
                        <div class="text-muted small">Dựa trên <strong class="text-dark" id="summaryCountText">{{ $product->reviews_count }}</strong> lượt đánh giá thực tế</div>
                    </div>
                    <div class="col-12 col-md-8 ps-md-4">
                        <div class="d-flex align-items-center gap-2 small mb-1.5">
                            <span style="width: 50px;">5 sao</span>
                            <div class="progress flex-grow-1" style="height: 8px;">
                                <div class="progress-bar" style="width: 85%; background: linear-gradient(135deg, #DFC07A, #C5A059);"></div>
                            </div>
                            <span class="text-muted" style="width: 35px; text-align: right;">85%</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 small mb-1.5">
                            <span style="width: 50px;">4 sao</span>
                            <div class="progress flex-grow-1" style="height: 8px;">
                                <div class="progress-bar" style="width: 15%; background: linear-gradient(135deg, #DFC07A, #C5A059);"></div>
                            </div>
                            <span class="text-muted" style="width: 35px; text-align: right;">15%</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 small mb-1.5">
                            <span style="width: 50px;">3 sao</span>
                            <div class="progress flex-grow-1" style="height: 8px;">
                                <div class="progress-bar bg-secondary-subtle" style="width: 0%;"></div>
                            </div>
                            <span class="text-muted" style="width: 35px; text-align: right;">0%</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 small mb-1.5">
                            <span style="width: 50px;">2 sao</span>
                            <div class="progress flex-grow-1" style="height: 8px;">
                                <div class="progress-bar bg-secondary-subtle" style="width: 0%;"></div>
                            </div>
                            <span class="text-muted" style="width: 35px; text-align: right;">0%</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 small">
                            <span style="width: 50px;">1 sao</span>
                            <div class="progress flex-grow-1" style="height: 8px;">
                                <div class="progress-bar bg-secondary-subtle" style="width: 0%;"></div>
                            </div>
                            <span class="text-muted" style="width: 35px; text-align: right;">0%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FORM GỬI ĐÁNH GIÁ MỚI -->
            <div class="p-4 p-md-4.5 rounded-4 mb-5" id="reviewFormCard" style="background: #FFFFFF; border: 1.5px solid #DFC07A; box-shadow: 0 4px 20px rgba(197, 160, 89, 0.1);">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="p-2 rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: linear-gradient(135deg, #DFC07A, #C5A059);">
                        <i class="fa-solid fa-pen-nib small"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-0 fs-6">Gửi đánh giá của bạn cho sản phẩm này</h5>
                        <small class="text-muted">Cảm nhận của bạn là động lực để FAMILY không ngừng nâng cao chất lượng phục vụ</small>
                    </div>
                </div>

                <!-- Alert Thông Báo Thành Công -->
                <div class="alert alert-success border-0 rounded-3 mb-3 d-flex align-items-center gap-2" id="reviewSuccessAlert" style="display: none; background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0 !important;">
                    <i class="fa-solid fa-circle-check fs-5 text-success"></i>
                    <div>
                        <strong>Gửi đánh giá thành công!</strong> Cảm ơn bạn đã đóng góp nhận xét chân thực cho thiết bị này.
                    </div>
                </div>

                @if(session('review_success'))
                <div class="alert alert-success border-0 rounded-3 mb-3 d-flex align-items-center gap-2" style="background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0 !important;">
                    <i class="fa-solid fa-circle-check fs-5 text-success"></i>
                    <div>
                        <strong>Thành công!</strong> {{ session('review_success') }}
                    </div>
                </div>
                @endif

                @if ($errors->any())
                <div class="alert alert-danger border-0 rounded-3 mb-3 d-flex align-items-center gap-2" style="background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA !important;">
                    <i class="fa-solid fa-triangle-exclamation fs-5 text-danger"></i>
                    <div>
                        <strong>Có lỗi xảy ra:</strong>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <form action="{{ route('products.reviews.store', $product->id) }}" method="POST" id="productReviewForm">
                    @csrf
                    
                    <!-- Chọn số sao tương tác -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark d-block">1. Mức độ hài lòng của bạn: <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <div class="star-rating-box d-flex gap-1.5 fs-2" style="cursor: pointer;" title="Nhấp chuột để chọn số sao">
                                <i class="fa-solid fa-star star-item text-warning" data-val="1"></i>
                                <i class="fa-solid fa-star star-item text-warning" data-val="2"></i>
                                <i class="fa-solid fa-star star-item text-warning" data-val="3"></i>
                                <i class="fa-solid fa-star star-item text-warning" data-val="4"></i>
                                <i class="fa-solid fa-star star-item text-warning" data-val="5"></i>
                            </div>
                            <span class="badge rounded-pill fw-semibold px-3 py-1.5 ms-md-2" id="ratingTextLabel" style="background: rgba(197, 160, 89, 0.15); color: #9A7B38; font-size: 0.85rem;">
                                Tuyệt vời (5/5 sao)
                            </span>
                            <input type="hidden" name="rating" id="reviewRatingInput" value="5">
                        </div>
                    </div>

                    <!-- Thông tin người gửi -->
                    <div class="row g-3 mb-3">
                        @auth
                        <div class="col-12">
                            <div class="p-2.5 rounded-3 bg-light border d-flex align-items-center gap-2 small">
                                <i class="fa-solid fa-user-check text-gold fs-5"></i>
                                <span>Đang đánh giá với tư cách: <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->email }})</span>
                            </div>
                            <input type="hidden" name="user_name" value="{{ Auth::user()->name }}">
                            <input type="hidden" name="user_email" value="{{ Auth::user()->email }}">
                        </div>
                        @else
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-bold small text-dark">Họ và tên của bạn: <span class="text-muted fw-normal">(Không bắt buộc)</span></label>
                            <input type="text" name="user_name" class="form-control rounded-3" placeholder="Nhập họ và tên (hoặc để trống)" style="border-color: #E2E8F0;">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-bold small text-dark">Email hoặc SĐT: <span class="text-muted fw-normal">(Tùy chọn)</span></label>
                            <input type="text" name="user_email" class="form-control rounded-3" placeholder="Nhập email hoặc số điện thoại..." style="border-color: #E2E8F0;">
                        </div>
                        @endauth
                    </div>

                    <!-- Nhận xét chi tiết -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark d-block">2. Chia sẻ nhận xét của bạn:</label>
                        <textarea name="comment" id="reviewCommentInput" rows="3" class="form-control rounded-3 p-3" placeholder="Chia sẻ cảm nhận của bạn về sản phẩm, chất lượng, độ bền hoặc dịch vụ giao hàng..." style="border-color: #E2E8F0; resize: vertical;"></textarea>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-gold px-4 py-2.5 fw-bold rounded-pill shadow-sm d-inline-flex align-items-center gap-2" id="btnSubmitReview">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Gửi đánh giá ngay</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- DANH SÁCH CÁC ĐÁNH GIÁ THỰC TẾ -->
            <div>
                <h5 class="fw-bold text-dark fs-6 mb-3 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-comments text-gold"></i>
                    <span>Tất cả đánh giá (<span id="reviewsCountBadge">{{ $product->reviews->count() }}</span>)</span>
                </h5>

                <div id="reviewsContainer">
                    @forelse($product->reviews as $rev)
                    <div class="p-3.5 p-md-4 rounded-3 border bg-white mb-3 shadow-sm" style="border-color: #F1F5F9 !important; transition: border-color 0.2s;" onmouseover="this.style.borderColor='#DFC07A'" onmouseout="this.style.borderColor='#F1F5F9'">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 gap-2">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white shadow-sm" style="width: 42px; height: 42px; background: linear-gradient(135deg, #DFC07A, #9B782F); font-size: 1rem;">
                                    {{ mb_strtoupper(mb_substr($rev->user_name, 0, 1, 'UTF-8'), 'UTF-8') }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark fs-6 d-flex align-items-center gap-2">
                                        {{ $rev->user_name }}
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill small" style="font-size: 0.7rem;">
                                            <i class="fa-solid fa-circle-check me-1"></i>Đã mua hàng
                                        </span>
                                    </div>
                                    <div class="text-muted small" style="font-size: 0.75rem;">
                                        {{ $rev->created_at ? $rev->created_at->diffForHumans() : 'Gần đây' }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-warning small d-flex gap-1">
                                @for($s = 1; $s <= 5; $s++)
                                    <i class="fa-{{ $s <= $rev->rating ? 'solid' : 'regular' }} fa-star"></i>
                                @endfor
                            </div>
                        </div>
                        <div class="text-secondary lh-base" style="font-size: 0.925rem; white-space: pre-line;">
                            {{ $rev->comment }}
                        </div>
                    </div>
                    @empty
                    <div class="p-5 text-center text-muted bg-light rounded-4 border" id="emptyReviewsMessage">
                        <i class="fa-regular fa-comment-dots display-6 mb-2 text-gold d-block"></i>
                        <p class="mb-0">Hiện chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên để lại cảm nhận!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Chức năng so sánh sản phẩm
function addAndOpenCompare(productId) {
    try {
        let compareIds = JSON.parse(localStorage.getItem('family_compare_ids') || '[]');
        if (!compareIds.includes(productId)) {
            if (compareIds.length >= 4) {
                compareIds.shift(); // Giữ tối đa 4 sản phẩm
            }
            compareIds.push(productId);
            localStorage.setItem('family_compare_ids', JSON.stringify(compareIds));
        }
        window.location.href = "{{ route('products.compare') }}?ids=" + compareIds.join(',');
    } catch(e) {
        window.location.href = "{{ route('products.compare') }}?ids=" + productId;
    }
}

// Cuộn mượt đến Form Đánh Giá
function focusReviewForm() {
    const card = document.getElementById('reviewFormCard');
    const commentInput = document.getElementById('reviewCommentInput');
    if (card) {
        card.scrollIntoView({ behavior: 'smooth', block: 'center' });
        setTimeout(() => {
            if (commentInput) commentInput.focus();
        }, 500);
    }
}

// Trình chọn số sao tương tác (Interactive 5-star Rating Picker)
document.addEventListener('DOMContentLoaded', function() {
    const starItems = document.querySelectorAll('.star-item');
    const ratingInput = document.getElementById('reviewRatingInput');
    const ratingText = document.getElementById('ratingTextLabel');
    const labels = {
        1: 'Chưa hài lòng (1/5 sao)',
        2: 'Tạm được (2/5 sao)',
        3: 'Bình thường (3/5 sao)',
        4: 'Hài lòng (4/5 sao)',
        5: 'Tuyệt vời (5/5 sao)'
    };

    function setStars(val) {
        starItems.forEach(s => {
            const sv = parseInt(s.dataset.val);
            if (sv <= val) {
                s.className = 'fa-solid fa-star star-item text-warning';
            } else {
                s.className = 'fa-regular fa-star star-item text-secondary opacity-50';
            }
        });
        if (ratingInput) ratingInput.value = val;
        if (ratingText && labels[val]) ratingText.innerText = labels[val];
    }

    starItems.forEach(s => {
        s.addEventListener('click', function() {
            const val = parseInt(this.dataset.val);
            setStars(val);
        });
        s.addEventListener('mouseenter', function() {
            const val = parseInt(this.dataset.val);
            starItems.forEach(item => {
                if (parseInt(item.dataset.val) <= val) {
                    item.classList.add('text-warning');
                    item.classList.remove('opacity-50', 'text-secondary');
                } else {
                    item.classList.remove('text-warning');
                    item.classList.add('opacity-50', 'text-secondary');
                }
            });
        });
    });

    const starBox = document.querySelector('.star-rating-box');
    if (starBox) {
        starBox.addEventListener('mouseleave', function() {
            const current = parseInt(ratingInput?.value || 5);
            setStars(current);
        });
    }

    // Xử lý gửi Form Đánh Giá thông minh qua AJAX (Zero Page Flickering)
    const reviewForm = document.getElementById('productReviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const submitBtn = document.getElementById('btnSubmitReview');
            const originalBtnHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Đang gửi...';

            try {
                const formData = new FormData(reviewForm);
                const res = await fetch(reviewForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    // Hiển thị thông báo thành công
                    const alertBox = document.getElementById('reviewSuccessAlert');
                    if (alertBox) {
                        alertBox.style.display = 'flex';
                        alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }

                    // Chèn đánh giá mới lên đầu danh sách ngay lập tức
                    const list = document.getElementById('reviewsContainer');
                    if (list && data.review) {
                        const emptyMsg = document.getElementById('emptyReviewsMessage');
                        if (emptyMsg) emptyMsg.remove();

                        let starsHtml = '';
                        for (let i = 1; i <= 5; i++) {
                            starsHtml += `<i class="fa-${i <= data.review.rating ? 'solid' : 'regular'} fa-star"></i>`;
                        }

                        const initialChar = data.review.user_name.charAt(0).toUpperCase();
                        const newCard = document.createElement('div');
                        newCard.className = 'p-3.5 p-md-4 rounded-3 border bg-white mb-3 shadow-sm';
                        newCard.style.borderColor = '#DFC07A';
                        newCard.style.animation = 'fadeIn 0.5s ease';
                        newCard.innerHTML = `
                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 gap-2">
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white shadow-sm" style="width: 42px; height: 42px; background: linear-gradient(135deg, #DFC07A, #9B782F); font-size: 1rem;">
                                        ${initialChar}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark fs-6 d-flex align-items-center gap-2">
                                            ${data.review.user_name}
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill small" style="font-size: 0.7rem;">
                                                <i class="fa-solid fa-circle-check me-1"></i>Đã mua hàng
                                            </span>
                                        </div>
                                        <div class="text-muted small" style="font-size: 0.75rem;">${data.review.created_at_human}</div>
                                    </div>
                                </div>
                                <div class="text-warning small d-flex gap-1">${starsHtml}</div>
                            </div>
                            <div class="text-secondary lh-base" style="font-size: 0.925rem;">
                                ${data.review.comment.replace(/</g, '&lt;').replace(/>/g, '&gt;')}
                            </div>
                        `;
                        list.prepend(newCard);
                    }

                    // Cập nhật số đếm trên giao diện
                    if (data.new_count) {
                        const countBadge = document.getElementById('reviewsCountBadge');
                        const topCount = document.getElementById('topReviewsCountLabel');
                        const summaryCount = document.getElementById('summaryCountText');
                        if (countBadge) countBadge.innerText = data.new_count;
                        if (topCount) topCount.innerText = data.new_count;
                        if (summaryCount) summaryCount.innerText = data.new_count;
                    }
                    if (data.new_average) {
                        const topRating = document.getElementById('topRatingLabel');
                        const summaryScore = document.getElementById('summaryScoreNum');
                        if (topRating) topRating.innerText = data.new_average + '/5';
                        if (summaryScore) summaryScore.innerText = data.new_average;
                    }

                    // Reset form
                    const commentInput = reviewForm.querySelector('textarea[name="comment"]');
                    if (commentInput) commentInput.value = '';
                    setStars(5);
                } else {
                    alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại.');
                }
            } catch(err) {
                reviewForm.submit();
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }
        });
    }
});
</script>
@endsection
