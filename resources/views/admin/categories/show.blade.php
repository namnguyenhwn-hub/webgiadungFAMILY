@extends('admin.layouts.app')
@section('title', 'Chi tiết danh mục')

@section('content')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0 fs-5">Chi tiết Danh mục</h4>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">ID:</label>
                    <p class="form-control-plaintext">{{ $category->id }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên danh mục:</label>
                    <p class="form-control-plaintext fs-5 text-primary fw-semibold">{{ $category->name }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Số lượng sản phẩm thuộc danh mục:</label>
                    <p class="form-control-plaintext">{{ $category->products()->count() }} sản phẩm</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Ngày tạo:</label>
                    <p class="form-control-plaintext">{{ $category->created_at ? $category->created_at->format('d/m/Y H:i:s') : 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Cập nhật lần cuối:</label>
                    <p class="form-control-plaintext">{{ $category->updated_at ? $category->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</p>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning">Chỉnh sửa</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
