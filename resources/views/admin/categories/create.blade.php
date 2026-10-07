@extends('admin.layouts.app')
@section('title', 'Thêm mới danh mục')

@section('content')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0 fs-5">Thêm mới Danh mục</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <!-- Tên danh mục -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control @error('name') is-invalid @enderror"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Nhập tên danh mục..."
                            required>
                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <!-- Các nút thao tác -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>
                        <button type="submit" class="btn btn-success">Lưu danh mục</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
