@extends('layouts.app')

@section('title', 'Danh mục: ' . $category->name)
@section('content')
<div class="mb-4">
    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary btn-sm">
        &laquo; Quay lại danh sách danh mục
    </a>
</div>

<div class="card shadow-sm border-0 mb-5">
    <div class="card-body p-4 text-center">
        <h2 class="fw-bold mb-2">{{ $category->name }}</h2>
        <p class="text-muted mb-0">{{ $category->description ?? 'Danh sách các sản phẩm thuộc danh mục ' . $category->name }}</p>
    </div>
</div>

<h4 class="fw-bold mb-3">Sản phẩm thuộc danh mục ({{ $category->products()->count() }})</h4>

<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 mb-4">
@forelse ($category->products as $product)
    <div class="col">
        <div class="card h-100 shadow-sm border-0">
            @if($product->image)
                @if(str_starts_with($product->image, 'http') || str_starts_with($product->image, 'images/'))
                    <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                @else
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                @endif
            @else
                <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="No Image" style="height: 200px; object-fit: cover;">
            @endif
            <div class="card-body d-flex flex-column text-center">
                <h5 class="card-title text-truncate fw-bold">{{ $product->name }}</h5>
                <p class="card-text text-muted small flex-grow-1">
                    {{ \Illuminate\Support\Str::limit($product->description, 80, '...') }}
                </p>
                <div class="my-3">
                    <div class="text-danger fw-bold fs-5">
                        {{ number_format($product->price, 0, ',', '.') }} đ
                    </div>
                    <small class="text-muted">
                        Còn lại: {{ $product->quantity }} sản phẩm
                    </small>
                </div>
                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary mt-auto w-100">
                    Xem chi tiết
                </a>
            </div>
        </div>
    </div>
@empty
    <div class="col-12 text-center py-5">
        <p class="fs-5 text-muted">Chưa có sản phẩm nào thuộc danh mục này.</p>
    </div>
@endforelse
</div>
@endsection
