@extends('admin.layouts.app')
@section('title', 'Quản lý danh mục')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card border">
            <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom py-3">
                <h4 class="mb-0 fs-5 fw-bold text-dark"><i class="bi bi-tags me-2 text-primary"></i>Danh sách Danh mục</h4>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm fw-semibold">
                    <i class="bi bi-plus-lg me-1"></i> Thêm mới
                </a>
            </div>
            <div class="card-body p-0">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;" class="text-center">STT</th>
                                <th>Tên danh mục</th>
                                <th style="width: 150px;" class="text-center">Số sản phẩm</th>
                                <th style="width: 200px;" class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $key => $category)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td><strong>{{ $category->name }}</strong></td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $category->products_count ?? $category->products()->count() }} sản phẩm</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-info btn-sm text-white">Xem</a>
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Không có dữ liệu danh mục.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(method_exists($categories, 'links'))
            <div class="card-footer bg-white d-flex justify-content-center py-3">
                {{ $categories->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
