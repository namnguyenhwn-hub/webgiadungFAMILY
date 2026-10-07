@extends('admin.layouts.app')
@section('title', 'Danh sách Sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0 fs-3">Danh sách Sản phẩm</h2>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Thêm Sản phẩm
    </a>
</div>

{{-- Hiển thị thông báo thành công sau khi Thêm / Sửa / Xóa --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;" class="text-center">STT</th>
                        <th>Tên Thiết Bị</th>
                        <th>Thương Hiệu</th>
                        <th>Xuất Xứ</th>
                        <th>Danh Mục</th>
                        <th style="width: 90px;" class="text-center">Kho</th>
                        <th style="width: 140px;">Giá Bán</th>
                        <th style="width: 170px;" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                    <tr>
                        <td class="text-center fw-bold">{{ $products->firstItem() + $loop->index }}</td>
                        <td>
                            <strong>{{ $product->name }}</strong>
                            @if($product->material)
                                <div class="text-muted small text-truncate" style="max-width: 250px;">
                                    <i class="bi bi-gem"></i> {{ $product->material }}
                                </div>
                            @endif
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $product->brand ?: 'Chính hãng' }}</span></td>
                        <td><span class="text-secondary small fw-semibold"><i class="bi bi-geo-alt text-danger"></i> {{ $product->origin ?: 'Việt Nam' }}</span></td>
                        <td>
                            <span class="badge bg-info text-dark">
                                {{ $product->category->name ?? ($product->category ?: 'Chưa phân loại') }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge {{ ($product->quantity ?? $product->stock) > 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->quantity ?? $product->stock }}
                            </span>
                        </td>
                        <td class="fw-bold text-danger">{{ number_format($product->price, 0, ',', '.') }} đ</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                {{-- Nút Xem --}}
                                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-info btn-sm text-white">Xem</a>
                                {{-- Nút Sửa --}}
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning btn-sm">Sửa</a>
                                {{-- Form Xóa --}}
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Chưa có sản phẩm nào trong hệ thống.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Phân trang -->
<div class="d-flex justify-content-center mt-3">
    {{ $products->links() }}
</div>
@endsection
