@extends('layouts.app')

@section('title', 'So sánh sản phẩm gia dụng thông minh')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-1">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted"><i class="bi bi-house me-1"></i>Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none text-muted">Sản phẩm</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">So sánh sản phẩm</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <i class="fa-solid fa-code-compare text-gold"></i>
                <span>Bảng so sánh chi tiết thiết bị gia dụng</span>
            </h1>
            <p class="text-muted small mb-0">Đặt các sản phẩm cạnh nhau để so sánh rõ ràng về thương hiệu, xuất xứ, chất liệu, công dụng và giá bán.</p>
        </div>

        <div class="d-flex gap-2">
            @if(count($compareProducts) > 0)
                <button type="button" class="btn btn-outline-danger btn-sm px-3 rounded-pill" onclick="clearAllCompare()">
                    <i class="bi bi-trash3 me-1"></i> Xóa tất cả so sánh
                </button>
            @endif
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
                <i class="bi bi-grid me-1"></i> Xem danh mục sản phẩm
            </a>
        </div>
    </div>

    @if(count($compareProducts) === 0)
        <!-- Giao diện khi chưa chọn sản phẩm nào để so sánh -->
        <div class="card shadow-sm border-0 rounded-4 text-center py-5 px-3 my-4">
            <div class="mb-3">
                <div class="d-inline-flex p-4 rounded-circle" style="background: rgba(197, 160, 89, 0.12); color: #9A7B38;">
                    <i class="fa-solid fa-scale-balanced fs-1"></i>
                </div>
            </div>
            <h4 class="fw-bold text-dark mb-2">Chưa có sản phẩm nào trong danh sách so sánh</h4>
            <p class="text-muted mx-auto mb-4" style="max-width: 480px;">
                Vui lòng chọn từ 2 đến 4 thiết bị gia dụng để bắt đầu phân tích và so sánh các đặc tính kỹ thuật, chất liệu, xuất xứ và mức giá tốt nhất.
            </p>

            <!-- Bộ gợi ý chọn nhanh 2 sản phẩm mẫu -->
            <div class="row justify-content-center g-3 mb-4" style="max-width: 700px; margin: 0 auto;">
                @foreach($allProducts->take(3) as $p)
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 border p-3 rounded-3 text-center">
                        @php
                            $img = $p->image ?: 'public/images/products/air_fryer.jpg';
                            if (!str_starts_with($img, 'http')) {
                                if (str_starts_with($img, 'products/')) $img = 'storage/' . $img;
                                elseif (str_starts_with($img, 'public/')) $img = preg_replace('#^public/#', '', $img);
                                $img = asset($img);
                            }
                        @endphp
                        <img src="{{ $img }}" alt="{{ $p->name }}" style="height: 100px; object-fit: contain; margin: 0 auto 10px;">
                        <div class="fw-bold small text-truncate" title="{{ $p->name }}">{{ \App\Http\Controllers\ProductController::formatSentenceCase($p->name) }}</div>
                        <div class="text-danger fw-bold small my-1">{{ number_format($p->price, 0, ',', '.') }} ₫</div>
                        <button type="button" class="btn btn-sm btn-outline-gold mt-auto" onclick="addSingleCompare({{ $p->id }})">
                            <i class="fa-solid fa-plus me-1"></i> Chọn so sánh
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <div>
                <a href="{{ route('products.index') }}" class="btn btn-gold px-4 py-2 fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Khám phá sản phẩm ngay
                </a>
            </div>
        </div>
    @else
        <!-- BẢNG MA TRẬN SO SÁNH SIDE-BY-SIDE -->
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0 text-center" style="min-width: 800px;">
                    <!-- HÀNG 1: ẢNH & TÊN & NÚT XÓA -->
                    <thead>
                        <tr class="table-light">
                            <th style="width: 220px; text-align: left;" class="p-3 bg-white">
                                <span class="fw-bold text-dark fs-6">Sản phẩm so sánh ({{ count($compareProducts) }}/4)</span>
                            </th>
                            @foreach($compareProducts as $p)
                                @php
                                    $img = $p->image ?: 'public/images/products/air_fryer.jpg';
                                    if (!str_starts_with($img, 'http')) {
                                        if (str_starts_with($img, 'products/')) $img = 'storage/' . $img;
                                        elseif (str_starts_with($img, 'public/')) $img = preg_replace('#^public/#', '', $img);
                                        $img = asset($img);
                                    }
                                @endphp
                                <th style="width: calc((100% - 220px) / {{ max(count($compareProducts), 1) }});" class="p-3 bg-white position-relative align-top">
                                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2" title="Xóa khỏi so sánh" onclick="removeCompare({{ $p->id }})"></button>
                                    <div class="p-2 mb-2">
                                        <a href="{{ route('products.show', $p) }}">
                                            <img src="{{ $img }}" alt="{{ $p->name }}" style="height: 140px; object-fit: contain;" class="img-fluid rounded">
                                        </a>
                                    </div>
                                    <h6 class="fw-bold mb-1">
                                        <a href="{{ route('products.show', $p) }}" class="text-decoration-none text-dark text-truncate d-block" title="{{ $p->name }}">
                                            {{ \App\Http\Controllers\ProductController::formatSentenceCase($p->name) }}
                                        </a>
                                    </h6>
                                    <span class="badge bg-light text-muted border small">{{ \App\Http\Controllers\ProductController::formatSentenceCase($p->category->name ?? ($p->category ?: 'Gia dụng')) }}</span>
                                </th>
                            @endforeach

                            <!-- CỘT THÊM SẢN PHẨM NẾU CÒN TRỐNG (< 4) -->
                            @if(count($compareProducts) < 4)
                                <th style="width: 220px;" class="p-3 bg-light align-middle">
                                    <div class="p-3 border border-2 border-dashed rounded-3 text-center">
                                        <i class="fa-solid fa-circle-plus fs-2 text-gold mb-2"></i>
                                        <div class="fw-bold small mb-2">Thêm thiết bị so sánh</div>
                                        <select class="form-select form-select-sm" onchange="if(this.value) addSingleCompare(this.value)">
                                            <option value="">-- Chọn thiết bị --</option>
                                            @foreach($allProducts as $ap)
                                                @if(!in_array($ap->id, $productIds))
                                                    <option value="{{ $ap->id }}">{{ \App\Http\Controllers\ProductController::formatSentenceCase($ap->name) }} ({{ number_format($ap->price, 0, ',', '.') }} đ)</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        <!-- HÀNG 2: GIÁ BÁN -->
                        <tr>
                            <td class="fw-bold text-start p-3 table-light">
                                <i class="fa-solid fa-tags text-gold me-2"></i>Giá bán niêm yết
                            </td>
                            @foreach($compareProducts as $p)
                                <td class="p-3">
                                    <div class="fs-4 fw-bold text-danger">
                                        {{ number_format($p->price, 0, ',', '.') }} ₫
                                    </div>
                                    @if($p->price > 0)
                                        <small class="text-muted text-decoration-line-through">
                                            {{ number_format(round($p->price * 1.2 / 10000) * 10000, 0, ',', '.') }} ₫
                                        </small>
                                    @endif
                                </td>
                            @endforeach
                            @if(count($compareProducts) < 4) <td class="bg-light"></td> @endif
                        </tr>

                        <!-- HÀNG 3: THƯƠNG HIỆU -->
                        <tr>
                            <td class="fw-bold text-start p-3 table-light">
                                <i class="fa-solid fa-award text-gold me-2"></i>Thương hiệu
                            </td>
                            @foreach($compareProducts as $p)
                                <td class="p-3">
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1.5 rounded-pill fw-bold fs-7">
                                        {{ $p->brand ?: 'Chính hãng' }}
                                    </span>
                                </td>
                            @endforeach
                            @if(count($compareProducts) < 4) <td class="bg-light"></td> @endif
                        </tr>

                        <!-- HÀNG 4: XUẤT XỨ -->
                        <tr>
                            <td class="fw-bold text-start p-3 table-light">
                                <i class="fa-solid fa-earth-americas text-gold me-2"></i>Xuất xứ
                            </td>
                            @foreach($compareProducts as $p)
                                <td class="p-3">
                                    <span class="badge bg-light text-dark border px-3 py-1.5 fw-semibold">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $p->origin ?: 'Việt Nam' }}
                                    </span>
                                </td>
                            @endforeach
                            @if(count($compareProducts) < 4) <td class="bg-light"></td> @endif
                        </tr>

                        <!-- HÀNG 5: CHẤT LIỆU -->
                        <tr>
                            <td class="fw-bold text-start p-3 table-light">
                                <i class="fa-solid fa-gem text-gold me-2"></i>Chất liệu chế tạo
                            </td>
                            @foreach($compareProducts as $p)
                                <td class="p-3 text-secondary" style="font-size: 0.925rem;">
                                    {{ $p->material ?: 'Hợp kim cao cấp & Nhựa ABS nguyên sinh' }}
                                </td>
                            @endforeach
                            @if(count($compareProducts) < 4) <td class="bg-light"></td> @endif
                        </tr>

                        <!-- HÀNG 6: CÔNG DỤNG & TÍNH NĂNG CHÍNH -->
                        <tr>
                            <td class="fw-bold text-start p-3 table-light">
                                <i class="fa-solid fa-bolt-lightning text-gold me-2"></i>Công dụng & tính năng
                            </td>
                            @foreach($compareProducts as $p)
                                <td class="p-3 text-start text-secondary" style="font-size: 0.925rem;">
                                    <i class="fa-solid fa-check text-success me-1"></i>
                                    {{ $p->usage ?: ($p->description ?: 'Thiết bị gia dụng tiện ích gia đình') }}
                                </td>
                            @endforeach
                            @if(count($compareProducts) < 4) <td class="bg-light"></td> @endif
                        </tr>

                        <!-- HÀNG 7: TÌNH TRẠNG KHO HÀNG -->
                        <tr>
                            <td class="fw-bold text-start p-3 table-light">
                                <i class="fa-solid fa-box text-gold me-2"></i>Tình trạng kho
                            </td>
                            @foreach($compareProducts as $p)
                                <td class="p-3">
                                    <span class="badge {{ ($p->quantity ?? $p->stock) > 0 ? 'bg-success' : 'bg-danger' }} px-3 py-1.5">
                                        {{ ($p->quantity ?? $p->stock) > 0 ? 'Còn hàng (' . ($p->quantity ?? $p->stock) . ' sp)' : 'Hết hàng' }}
                                    </span>
                                </td>
                            @endforeach
                            @if(count($compareProducts) < 4) <td class="bg-light"></td> @endif
                        </tr>

                        <!-- HÀNG 8: BẢO HÀNH & ĐÁNH GIÁ -->
                        <tr>
                            <td class="fw-bold text-start p-3 table-light">
                                <i class="fa-solid fa-shield-halved text-gold me-2"></i>Bảo hành & đánh giá
                            </td>
                            @foreach($compareProducts as $p)
                                <td class="p-3">
                                    <div class="text-warning small mb-1">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star-half-stroke"></i>
                                        <strong class="text-dark ms-1">4.9/5</strong>
                                    </div>
                                    <small class="text-muted d-block">Bảo hành chính hãng 24 tháng</small>
                                </td>
                            @endforeach
                            @if(count($compareProducts) < 4) <td class="bg-light"></td> @endif
                        </tr>

                        <!-- HÀNG 9: THAO TÁC MUA HÀNG -->
                        <tr>
                            <td class="fw-bold text-start p-3 table-light">
                                <i class="fa-solid fa-cart-shopping text-gold me-2"></i>Thao tác
                            </td>
                            @foreach($compareProducts as $p)
                                <td class="p-3">
                                    <div class="d-flex flex-column gap-2">
                                        <form action="{{ route('cart.buyNow', $p->id, false) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-gold w-100 btn-sm py-2 fw-bold" {{ ($p->quantity ?? $p->stock) <= 0 ? 'disabled' : '' }}>
                                                <i class="fa-solid fa-bolt me-1"></i> Mua ngay
                                            </button>
                                        </form>
                                        <form action="{{ route('cart.add', $p->id, false) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-dark w-100 btn-sm py-2" {{ ($p->quantity ?? $p->stock) <= 0 ? 'disabled' : '' }}>
                                                <i class="fa-solid fa-cart-plus me-1 text-gold"></i> Thêm giỏ
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endforeach
                            @if(count($compareProducts) < 4) <td class="bg-light"></td> @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<script>
// Đồng bộ state localStorage với Server URL
document.addEventListener('DOMContentLoaded', function() {
    try {
        const currentIds = @json($productIds);
        if (currentIds && currentIds.length > 0) {
            localStorage.setItem('family_compare_ids', JSON.stringify(currentIds));
        }
    } catch(e) {}
});

function addSingleCompare(id) {
    id = parseInt(id);
    if (!id) return;
    try {
        let compareIds = JSON.parse(localStorage.getItem('family_compare_ids') || '[]');
        if (!compareIds.includes(id)) {
            if (compareIds.length >= 4) {
                alert('Tối đa so sánh cùng lúc 4 sản phẩm. Đã tự động thay thế sản phẩm đầu tiên!');
                compareIds.shift();
            }
            compareIds.push(id);
            localStorage.setItem('family_compare_ids', JSON.stringify(compareIds));
        }
        window.location.href = "{{ route('products.compare') }}?ids=" + compareIds.join(',');
    } catch(e) {
        window.location.href = "{{ route('products.compare') }}?ids=" + id;
    }
}

function removeCompare(id) {
    id = parseInt(id);
    try {
        let compareIds = JSON.parse(localStorage.getItem('family_compare_ids') || '[]');
        compareIds = compareIds.filter(item => item !== id);
        localStorage.setItem('family_compare_ids', JSON.stringify(compareIds));
        window.location.href = "{{ route('products.compare') }}?ids=" + compareIds.join(',');
    } catch(e) {
        window.location.href = "{{ route('products.compare') }}";
    }
}

function clearAllCompare() {
    if (confirm('Bạn có chắc chắn muốn xóa toàn bộ danh sách so sánh?')) {
        localStorage.removeItem('family_compare_ids');
        window.location.href = "{{ route('products.compare') }}";
    }
}
</script>
@endsection
