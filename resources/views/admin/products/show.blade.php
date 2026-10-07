@extends('admin.layouts.app')
@section('title', 'Chi tiết Sản phẩm')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
        <h2 class="mb-0 fs-5 fw-bold"><i class="bi bi-info-circle me-2"></i>Chi tiết Thiết Bị: {{ $product->name }}</h2>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
        </a>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <tr>
                    <th style="width: 220px;" class="table-light"><i class="bi bi-hash me-1"></i>Mã sản phẩm (ID)</th>
                    <td>#{{ $product->id }}</td>
                </tr>
                <tr>
                    <th class="table-light"><i class="bi bi-tag me-1"></i>Tên sản phẩm</th>
                    <td><strong class="fs-6 text-dark">{{ $product->name }}</strong></td>
                </tr>
                <tr>
                    <th class="table-light"><i class="bi bi-folder me-1"></i>Danh mục</th>
                    <td>
                        <span class="badge bg-primary">
                            {{ $product->category->name ?? ($product->category ?: 'Chưa phân loại') }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th class="table-light"><i class="bi bi-award me-1"></i>Thương hiệu</th>
                    <td><span class="badge bg-light text-dark border fs-6">{{ $product->brand ?: 'Chính hãng' }}</span></td>
                </tr>
                <tr>
                    <th class="table-light"><i class="bi bi-globe me-1"></i>Xuất xứ</th>
                    <td><i class="bi bi-geo-alt-fill text-danger me-1"></i><strong>{{ $product->origin ?: 'Việt Nam' }}</strong></td>
                </tr>
                <tr>
                    <th class="table-light"><i class="bi bi-gem me-1"></i>Chất liệu chế tạo</th>
                    <td>{{ $product->material ?: 'Hợp kim & Nhựa ABS cao cấp' }}</td>
                </tr>
                <tr>
                    <th class="table-light"><i class="bi bi-lightning-charge me-1"></i>Công dụng & Tính năng</th>
                    <td class="text-secondary">{{ $product->usage ?: 'Gia dụng tiện ích cho gia đình' }}</td>
                </tr>
                <tr>
                    <th class="table-light"><i class="bi bi-cash-stack me-1"></i>Giá bán niêm yết</th>
                    <td><span class="text-danger fw-bold fs-5">{{ number_format($product->price, 0, ',', '.') }} đ</span></td>
                </tr>
                <tr>
                    <th class="table-light"><i class="bi bi-box-seam me-1"></i>Số lượng tồn kho</th>
                    <td>
                        <span class="badge {{ ($product->quantity ?? $product->stock) > 0 ? 'bg-success' : 'bg-danger' }} fs-6">
                            {{ $product->quantity ?? $product->stock }} sản phẩm
                        </span>
                    </td>
                </tr>
                <tr>
                    <th class="table-light"><i class="bi bi-image me-1"></i>Hình ảnh</th>
                    <td>
                        @if($product->image)
                            @if(str_starts_with($product->image, 'http') || str_starts_with($product->image, 'images/'))
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="max-height: 140px; border-radius: 8px; object-fit: contain;">
                            @else
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="max-height: 140px; border-radius: 8px; object-fit: contain;">
                            @endif
                        @else
                            <span class="text-muted">Chưa có ảnh</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="table-light"><i class="bi bi-file-text me-1"></i>Mô tả chi tiết</th>
                    <td style="white-space: pre-line;" class="text-secondary">{{ $product->description ?? 'Không có mô tả' }}</td>
                </tr>
                <tr>
                    <th class="table-light"><i class="bi bi-calendar me-1"></i>Thời gian cập nhật</th>
                    <td>{{ $product->updated_at ? $product->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="mt-4 d-flex gap-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning px-4 fw-semibold">
                <i class="bi bi-pencil me-1"></i> Sửa sản phẩm
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary px-4">
                Quay lại
            </a>
        </div>
    </div>
</div>
@endsection
