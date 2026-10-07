@extends('layouts.app')

@section('title', 'Danh Mục Sản Phẩm')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h2 class="fw-bold mb-1">Danh Mục Sản Phẩm</h2>
        <p class="text-muted mb-0">Các nhóm thiết bị gia dụng tại cửa hàng</p>
    </div>
</div>

<div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
    @forelse($categories as $category)
    <div class="col">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body d-flex flex-column text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-tag-fill text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <h4 class="card-title fw-bold text-dark">{{ $category->name }}</h4>
                <p class="text-muted small flex-grow-1">
                    {{ $category->description ?? 'Bộ sưu tập các thiết bị chất lượng cao thuộc danh mục ' . $category->name }}
                </p>
                <div class="mb-3">
                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2 border">
                        {{ $category->products_count ?? $category->products()->count() }} sản phẩm
                    </span>
                </div>
                <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-primary mt-auto">
                    Xem sản phẩm trong nhóm
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <p class="fs-4 text-muted">Chưa có danh mục nào.</p>
    </div>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $categories->links() }}
</div>
@endsection
