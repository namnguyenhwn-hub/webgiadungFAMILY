@extends('admin.layouts.app')
@section('title', 'Thêm Sản phẩm Mới')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white py-3">
        <h2 class="mb-0 fs-5 fw-bold"><i class="bi bi-plus-circle me-2"></i>Thêm Thiết Bị Gia Dụng Mới</h2>
    </div>
    <div class="card-body p-4">
        {{-- Thông báo lỗi chung ở đầu trang --}}
        @if ($errors->any())
        <div class="alert alert-danger mb-4 rounded-3">
            <strong class="d-block mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>Đã có lỗi xảy ra, vui lòng kiểm tra lại dữ liệu:</strong>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <!-- Hàng 1: Thông tin cơ bản (Tên, Danh mục, Giá, Tồn kho) -->
                <div class="col-md-4">
                    <label for="name" class="form-label fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text"
                        id="name"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        placeholder="Ví dụ: Nồi Chiên Không Dầu Cao Cấp FAMILY 12L"
                        required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="category_id" class="form-label fw-semibold">Danh mục <span class="text-danger">*</span></label>
                    <select id="category_id"
                        name="category_id"
                        class="form-select @error('category_id') is-invalid @enderror"
                        required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="price" class="form-label fw-semibold"><i class="bi bi-cash-stack me-1 text-success"></i>Giá bán (VNĐ) <span class="text-danger">*</span></label>
                    <input type="number"
                        step="any"
                        id="price"
                        name="price"
                        class="form-control @error('price') is-invalid @enderror"
                        value="{{ old('price') }}"
                        placeholder="Nhập giá bán (VD: 4890000)"
                        required
                        min="0">
                    @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label for="quantity" class="form-label fw-semibold"><i class="bi bi-box-seam me-1 text-primary"></i>Số lượng tồn kho <span class="text-danger">*</span></label>
                    <input type="number"
                        id="quantity"
                        name="quantity"
                        class="form-control @error('quantity') is-invalid @enderror"
                        value="{{ old('quantity', 15) }}"
                        required
                        min="0">
                    @error('quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" id="is_special" name="is_special" value="1" {{ old('is_special') ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold text-warning" style="cursor: pointer;" for="is_special">
                            <i class="bi bi-star-fill text-warning me-1"></i> Đánh dấu là Sản phẩm Đặc biệt (Khối Bento nổi bật trên Trang chủ)
                        </label>
                    </div>
                </div>

                <!-- HÀNG NGANG GỌN GÀNG: THƯƠNG HIỆU, XUẤT XỨ, CHẤT LIỆU, CÔNG DỤNG, MÔ TẢ (CÙNG NẰM TRÊN 1 HÀNG) -->
                <div class="col-12">
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-primary px-2.5 py-1 text-white fw-bold"><i class="bi bi-layout-three-columns me-1"></i> Hàng Thông Số & Mô Tả Sản Phẩm</span>
                            <small class="text-muted">Các thông số được xếp gọn trên cùng một hàng ngang</small>
                        </div>
                        <div class="row g-2 align-items-end">
                            <!-- 1. Thương hiệu -->
                            <div class="col-lg col-md-4 col-sm-6">
                                <label for="brand" class="form-label fw-semibold small mb-1"><i class="bi bi-tag me-1 text-primary"></i>Thương hiệu</label>
                                <input type="text"
                                    id="brand"
                                    name="brand"
                                    class="form-control form-control-sm @error('brand') is-invalid @enderror"
                                    value="{{ old('brand') }}"
                                    placeholder="Ví dụ: Philips, Sunhouse, FAMILY, Kangaroo...">
                                @error('brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- 2. Xuất xứ -->
                            <div class="col-lg col-md-4 col-sm-6">
                                <label for="origin" class="form-label fw-semibold small mb-1"><i class="bi bi-globe me-1 text-primary"></i>Xuất xứ</label>
                                <input type="text"
                                    id="origin"
                                    name="origin"
                                    class="form-control form-control-sm @error('origin') is-invalid @enderror"
                                    value="{{ old('origin') }}"
                                    placeholder="Ví dụ: Việt Nam, Đức, Nhật Bản, Hà Lan...">
                                @error('origin')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- 3. Chất liệu chế tạo -->
                            <div class="col-lg col-md-4 col-sm-6">
                                <label for="material" class="form-label fw-semibold small mb-1"><i class="bi bi-gem me-1 text-primary"></i>Chất liệu chế tạo</label>
                                <input type="text"
                                    id="material"
                                    name="material"
                                    class="form-control form-control-sm @error('material') is-invalid @enderror"
                                    value="{{ old('material') }}"
                                    placeholder="Ví dụ: Thép không gỉ 304, Lòng nồi tráng men gốm Ceramic chống dính...">
                                @error('material')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- 4. Công dụng & Tính năng chính -->
                            <div class="col-lg col-md-6 col-sm-6">
                                <label for="usage" class="form-label fw-semibold small mb-1"><i class="bi bi-lightning-charge me-1 text-primary"></i>Công dụng & Tính năng chính</label>
                                <input type="text"
                                    id="usage"
                                    name="usage"
                                    class="form-control form-control-sm @error('usage') is-invalid @enderror"
                                    value="{{ old('usage') }}"
                                    placeholder="Ví dụ: Chiên nướng không dầu 360°, rã đông tự động, giảm 85% mỡ...">
                                @error('usage')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- 5. Mô tả chi tiết / Ghi chú -->
                            <div class="col-lg col-md-6 col-sm-12">
                                <label for="description" class="form-label fw-semibold small mb-1"><i class="bi bi-file-text me-1 text-primary"></i>Mô tả chi tiết / Ghi chú</label>
                                <input type="text"
                                    id="description"
                                    name="description"
                                    class="form-control form-control-sm @error('description') is-invalid @enderror"
                                    value="{{ old('description') }}"
                                    placeholder="Nhập thông tin mô tả chi tiết, hướng dẫn hoặc lưu ý bảo hành...">
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hình ảnh sản phẩm -->
                <div class="col-md-12">
                    <label for="image" class="form-label fw-semibold"><i class="bi bi-image me-1 text-primary"></i>Hình ảnh sản phẩm</label>
                    <input type="file"
                        id="image"
                        name="image"
                        class="form-control @error('image') is-invalid @enderror"
                        accept="image/*">
                    <div class="form-text text-muted">Hỗ trợ các định dạng ảnh: JPEG, PNG, JPG, WEBP. Dung lượng tối đa 2MB.</div>
                    @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Các nút thao tác -->
            <div class="mt-4 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-success px-4 fw-semibold">
                    <i class="bi bi-check-circle me-1"></i> Lưu sản phẩm
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary px-4">
                    Quay lại
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
