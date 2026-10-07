@extends('layouts.app')

@section('title', 'Tất cả sản phẩm gia dụng cao cấp')
@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom gap-3">
        <div>
            <h2 class="fw-bold mb-1 d-flex align-items-center gap-2">
                <span>Thiết bị gia dụng thông minh</span>
                <span class="badge bg-gold text-white fs-6 py-1 px-2.5 rounded-pill" style="background: linear-gradient(135deg, #DFC07A, #C5A059);">Chính hãng</span>
            </h2>
            <p class="text-muted mb-0">Khám phá các thiết bị thông minh với đầy đủ thông tin xuất xứ, thương hiệu, chất liệu & công dụng</p>
        </div>

        <div>
            <a href="{{ route('products.compare') }}" class="btn btn-outline-gold rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" id="btnGoCompareTop">
                <i class="fa-solid fa-code-compare"></i>
                <span>Xem bảng so sánh</span>
                <span class="badge rounded-pill bg-danger" id="badgeTopCompareCount" style="display: none;">0</span>
            </a>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-4">
    @forelse ($products as $product)
        @php
            $img = $product->image ?: 'public/images/products/air_fryer.jpg';
            if (!str_starts_with($img, 'http')) {
                if (str_starts_with($img, 'products/')) $img = 'storage/' . $img;
                elseif (str_starts_with($img, 'public/')) $img = preg_replace('#^public/#', '', $img);
                $img = asset($img);
            }
        @endphp
        <div class="col">
            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden position-relative product-card-hover" style="transition: transform 0.2s, box-shadow 0.2s;">
                <!-- Image container with badges -->
                <div class="position-relative bg-light text-center p-3" style="height: 220px; border-bottom: 1px solid #F1F5F9;">
                    <a href="{{ route('products.show', $product) }}" class="d-block h-100">
                        <img src="{{ $img }}" class="img-fluid h-100" alt="{{ $product->name }}" style="object-fit: contain;">
                    </a>
                    <span class="badge bg-white text-dark border position-absolute top-0 start-0 m-2 px-2.5 py-1 rounded-pill small fw-semibold shadow-sm">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $product->origin ?: 'Việt Nam' }}
                    </span>
                    <span class="badge bg-gold text-white position-absolute top-0 end-0 m-2 px-2.5 py-1 rounded-pill small fw-bold" style="background: linear-gradient(135deg, #DFC07A, #C5A059);">
                        {{ $product->brand ?: 'Chính hãng' }}
                    </span>
                </div>

                <div class="card-body d-flex flex-column p-3.5">
                    <div class="mb-1">
                        <span class="badge bg-light text-muted border small">
                            {{ \App\Http\Controllers\ProductController::formatSentenceCase($product->category->name ?? ($product->category ?: 'Gia dụng')) }}
                        </span>
                    </div>

                    <h5 class="card-title fw-bold fs-6 mb-1 text-truncate" title="{{ \App\Http\Controllers\ProductController::formatSentenceCase($product->name) }}">
                        <a href="{{ route('products.show', $product) }}" class="text-dark text-decoration-none">
                            {{ \App\Http\Controllers\ProductController::formatSentenceCase($product->name) }}
                        </a>
                    </h5>

                    <!-- Thông số vắn tắt: Chất liệu & Công dụng -->
                    <div class="small text-muted mb-2 text-truncate-2" style="font-size: 0.8rem; min-height: 38px;">
                        @if($product->material)
                            <div><i class="fa-solid fa-gem text-gold me-1"></i><strong>Chất liệu:</strong> {{ \Illuminate\Support\Str::limit($product->material, 45, '...') }}</div>
                        @endif
                        @if($product->usage)
                            <div><i class="fa-solid fa-bolt text-gold me-1"></i><strong>Công dụng:</strong> {{ \Illuminate\Support\Str::limit($product->usage, 45, '...') }}</div>
                        @elseif($product->description)
                            <div>{{ \Illuminate\Support\Str::limit($product->description, 55, '...') }}</div>
                        @endif
                    </div>

                    <div class="d-flex align-items-baseline justify-content-between my-2">
                        <div class="text-danger fw-bold fs-5">
                            {{ number_format($product->price, 0, ',', '.') }} ₫
                        </div>
                        <small class="text-muted" style="font-size: 0.8rem;">
                            Kho: <strong class="{{ ($product->quantity ?? $product->stock) > 0 ? 'text-success' : 'text-danger' }}">{{ $product->quantity ?? $product->stock }}</strong>
                        </small>
                    </div>

                    <div class="d-flex gap-2 mt-auto pt-2 border-top">
                        <a href="{{ route('products.show', $product) }}" class="btn btn-outline-dark btn-sm flex-grow-1 py-1.5 fw-semibold rounded-pill">
                            Xem chi tiết
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-gold rounded-pill px-2.5 py-1.5 d-flex align-items-center gap-1 btn-compare-toggle" data-id="{{ $product->id }}" onclick="toggleCompare({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ $img }}')">
                            <i class="fa-solid fa-scale-balanced me-1"></i>
                            <span class="compare-btn-text">So sánh</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <p class="fs-4 text-muted">Hiện chưa có sản phẩm nào trong kho.</p>
        </div>
    @endforelse
    </div>

    <div class="products-pagination-container my-4">
        {{ $products->links() }}
    </div>
</div>

<!-- =========================================================================
     THANH SO SÁNH NỔI DƯỚI ĐÁY MÀN HÌNH (FLOATING COMPARISON BAR)
     ========================================================================= -->
<div id="floatingCompareBar" class="position-fixed bottom-0 start-50 translate-middle-x bg-white shadow-lg border rounded-top-4 p-3 z-3" style="display: none; width: min(92%, 800px); box-shadow: 0 -8px 30px rgba(0,0,0,0.15) !important;">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <div class="p-2 rounded-circle text-gold bg-light">
                <i class="fa-solid fa-code-compare fs-5"></i>
            </div>
            <div>
                <strong class="text-dark d-block">So sánh sản phẩm (<span id="compareCountLabel">0</span>/4)</strong>
                <small class="text-muted">Chọn từ 2 đến 4 thiết bị để so sánh thông số</small>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2" id="compareThumbnails">
            <!-- Thumbnails inserted by JS -->
        </div>

        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-gold btn-sm px-3 py-2 fw-bold rounded-pill" onclick="goToComparePage()">
                <i class="fa-solid fa-scale-balanced me-1"></i> So Sánh Ngay
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm px-2.5 py-2 rounded-pill" onclick="clearAllCompareFromBar()" title="Hủy so sánh">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', updateCompareUi);

function getCompareList() {
    try {
        return JSON.parse(localStorage.getItem('family_compare_ids') || '[]');
    } catch(e) {
        return [];
    }
}

function saveCompareList(list) {
    localStorage.setItem('family_compare_ids', JSON.stringify(list));
    updateCompareUi();
}

function toggleCompare(id, name, img) {
    id = parseInt(id);
    let list = getCompareList();
    const idx = list.indexOf(id);
    if (idx > -1) {
        list.splice(idx, 1);
    } else {
        if (list.length >= 4) {
            alert('Tối đa so sánh cùng lúc 4 sản phẩm. Đã thay thế sản phẩm trước đó!');
            list.shift();
        }
        list.push(id);
    }
    saveCompareList(list);
}

function updateCompareUi() {
    const list = getCompareList();
    const count = list.length;

    // Cập nhật nút top
    const badgeTop = document.getElementById('badgeTopCompareCount');
    if (badgeTop) {
        if (count > 0) {
            badgeTop.innerText = count;
            badgeTop.style.display = 'inline-block';
        } else {
            badgeTop.style.display = 'none';
        }
    }

    // Cập nhật trạng thái các nút trên card
    document.querySelectorAll('.btn-compare-toggle').forEach(btn => {
        const id = parseInt(btn.dataset.id);
        const textSpan = btn.querySelector('.compare-btn-text');
        if (list.includes(id)) {
            btn.classList.remove('btn-outline-gold');
            btn.classList.add('btn-gold');
            if (textSpan) textSpan.innerText = 'Đã chọn';
        } else {
            btn.classList.remove('btn-gold');
            btn.classList.add('btn-outline-gold');
            if (textSpan) textSpan.innerText = 'So sánh';
        }
    });

    // Cập nhật thanh nổi floating bar
    const bar = document.getElementById('floatingCompareBar');
    const countLabel = document.getElementById('compareCountLabel');
    if (bar && countLabel) {
        if (count > 0) {
            bar.style.display = 'block';
            countLabel.innerText = count;
        } else {
            bar.style.display = 'none';
        }
    }
}

function goToComparePage() {
    const list = getCompareList();
    if (list.length === 0) {
        alert('Vui lòng chọn ít nhất 1 sản phẩm để so sánh!');
        return;
    }
    window.location.href = "{{ route('products.compare') }}?ids=" + list.join(',');
}

function clearAllCompareFromBar() {
    saveCompareList([]);
}
</script>

<style>
.product-card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.08) !important;
}
.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
