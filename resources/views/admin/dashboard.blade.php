@extends('admin.layouts.app')
@section('title', 'Admin Dashboard - Báo Cáo & Thống Kê Tổng Quan')

@section('content')
<div class="mb-4">
    <!-- Breadcrumb & Tiêu Đề -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bi bi-house me-1"></i>Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Quản trị viên</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Báo Cáo Thống Kê</li>
                </ol>
            <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <span>Báo Cáo & Thống Kê Toàn Hệ Thống</span>
            </h1>
        </div>

    </div>
</div>

<!-- Bảng Điều Khiển Nhanh -->
<div class="card border-0 shadow-sm mb-4 overflow-hidden rounded-3">
    <div class="card-body p-0">
        <div class="row g-0">
            <!-- Cột trái: Hàng dọc các chức năng -->
            <div class="col-md-3 bg-light p-3 border-end">
                <h6 class="fw-bold mb-3 text-uppercase text-secondary" style="font-size: 0.8rem; letter-spacing: 0.5px;">Trung Tâm Quản Lý</h6>
                <div class="nav flex-column nav-pills gap-1" id="v-pills-tab" role="tablist" aria-orientation="vertical">

                    <button class="nav-link active text-start px-3 py-2" id="v-pills-dashboard-tab" data-bs-toggle="pill" data-bs-target="#v-pills-dashboard" type="button" role="tab" aria-controls="v-pills-dashboard" aria-selected="true">
                        <i class="bi bi-graph-up-arrow me-2"></i>Báo Cáo Thống Kê
                    </button>

                    
                    <button class="nav-link text-start px-3 py-2" id="v-pills-product-tab" data-bs-toggle="pill" data-bs-target="#v-pills-product" type="button" role="tab" aria-controls="v-pills-product" aria-selected="false">
                        <i class="bi bi-box-seam me-2"></i>Quản Lý Sản Phẩm
                    </button>
                    
                    <button class="nav-link text-start px-3 py-2" id="v-pills-category-tab" data-bs-toggle="pill" data-bs-target="#v-pills-category" type="button" role="tab" aria-controls="v-pills-category" aria-selected="false">
                        <i class="bi bi-tags me-2"></i>Quản Lý Danh Mục
                    </button>

                    <button class="nav-link text-start px-3 py-2" id="v-pills-order-tab" data-bs-toggle="pill" data-bs-target="#v-pills-order" type="button" role="tab" aria-controls="v-pills-order" aria-selected="false">
                        <i class="bi bi-receipt me-2"></i>Đơn Hàng Khách
                    </button>

                    <button class="nav-link text-start px-3 py-2" id="v-pills-user-tab" data-bs-toggle="pill" data-bs-target="#v-pills-user" type="button" role="tab" aria-controls="v-pills-user" aria-selected="false">
                        <i class="bi bi-people me-2"></i>Tài Khoản Người Dùng
                    </button>

                    <button class="nav-link text-start px-3 py-2" id="v-pills-chat-tab" data-bs-toggle="pill" data-bs-target="#v-pills-chat" type="button" role="tab" aria-controls="v-pills-chat" aria-selected="false">
                        <i class="bi bi-chat-dots me-2"></i>Tin Nhắn & Hỗ Trợ
                    </button>

                    <hr class="my-1 text-secondary opacity-25">

                    <button class="nav-link text-start px-3 py-2" id="v-pills-banner-tab" data-bs-toggle="pill" data-bs-target="#v-pills-banner" type="button" role="tab" aria-controls="v-pills-banner" aria-selected="false">
                        <i class="bi bi-images me-2"></i>Sửa Ảnh Banner
                    </button>
                    
                    <button class="nav-link text-start px-3 py-2" id="v-pills-logo-tab" data-bs-toggle="pill" data-bs-target="#v-pills-logo" type="button" role="tab" aria-controls="v-pills-logo" aria-selected="false">
                        <i class="bi bi-camera me-2"></i>Sửa Logo Web
                    </button>
                    
                    <button class="nav-link text-start px-3 py-2" id="v-pills-print-tab" data-bs-toggle="pill" data-bs-target="#v-pills-print" type="button" role="tab" aria-controls="v-pills-print" aria-selected="false">
                        <i class="bi bi-printer me-2"></i>In / Xuất Báo Cáo
                    </button>

                    <hr class="my-1 text-secondary opacity-25">

                    <button class="nav-link text-start px-3 py-2" id="v-pills-promo-tab" data-bs-toggle="pill" data-bs-target="#v-pills-promo" type="button" role="tab" aria-controls="v-pills-promo" aria-selected="false">
                        <i class="bi bi-ticket-perforated me-2"></i>Cài Đặt Mã Giảm Giá
                    </button>
                </div>
            </div>
            
            <!-- Cột phải: Công dụng hiện ra -->
            <div class="col-md-9 p-4 bg-white">
                <div class="tab-content" id="v-pills-tabContent">

                    <!-- Báo Cáo Thống Kê -->
                    <div class="tab-pane fade show active" id="v-pills-dashboard" role="tabpanel" aria-labelledby="v-pills-dashboard-tab">
                        <h5 class="fw-bold text-dark mb-2"><i class="bi bi-graph-up-arrow text-primary me-2"></i>Báo Cáo & Thống Kê Doanh Thu</h5>
                        <p class="text-muted small mb-4">Tổng quan về doanh thu, tình trạng đơn hàng, và phân bổ danh mục sản phẩm.</p>
                        
                        <!-- =========================================================================
     1. HÀNG 4 THẺ CHỈ SỐ KPI TÀI CHÍNH & VẬN HÀNH (METRIC CARDS)
     ========================================================================= -->
<div class="row g-3 mb-4">
    <!-- KPI 1: Doanh Thu -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 p-3 border">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Tổng Doanh Thu</span>
                <div class="p-2 rounded-circle" style="background: rgba(197, 160, 89, 0.15); color: #9A7B38;">
                    <i class="fa-solid fa-coins fs-5"></i>
                </div>
            </div>
            <div class="fs-4 fw-bold text-dark mb-1">{{ $stats['revenue_total'] }}</div>
            <div class="d-flex align-items-center justify-content-between text-muted small mt-auto pt-2 border-top">
                <span>Hôm nay: <strong class="text-success">{{ $stats['revenue_today'] }}</strong></span>
            </div>
        </div>
    </div>

    <!-- KPI 2: Đơn Hàng -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 p-3 border">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Tổng Đơn Hàng</span>
                <div class="p-2 rounded-circle" style="background: rgba(59, 130, 246, 0.12); color: #2563EB;">
                    <i class="fa-solid fa-bag-shopping fs-5"></i>
                </div>
            </div>
            <div class="fs-4 fw-bold text-dark mb-1">{{ $stats['orders_count'] }} <span class="fs-6 fw-normal text-muted">đơn</span></div>
            <div class="d-flex align-items-center justify-content-between text-muted small mt-auto pt-2 border-top">
                <span>Chờ xử lý: <strong class="text-warning">{{ $stats['orders_pending'] }}</strong></span>
                <span class="badge bg-primary-subtle text-primary">Hoàn thành: {{ $stats['orders_completed'] }}</span>
            </div>
        </div>
    </div>

    <!-- KPI 3: Sản Phẩm & Kho -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 p-3 border">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Sản Phẩm Trong Kho</span>
                <div class="p-2 rounded-circle" style="background: rgba(139, 92, 246, 0.12); color: #7C3AED;">
                    <i class="fa-solid fa-boxes-stacked fs-5"></i>
                </div>
            </div>
            <div class="fs-4 fw-bold text-dark mb-1">{{ $stats['products_count'] }} <span class="fs-6 fw-normal text-muted">mặt hàng</span></div>
            <div class="d-flex align-items-center justify-content-between text-muted small mt-auto pt-2 border-top">
                <span>Sắp hết: <strong class="text-danger">{{ $stats['products_low_stock'] }}</strong></span>
                <span class="badge bg-secondary-subtle text-secondary">{{ count($categories) }} danh mục</span>
            </div>
        </div>
    </div>

    <!-- KPI 4: Người Dùng & Khách Hàng -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 p-3 border">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Khách Hàng & Thành Viên</span>
                <div class="p-2 rounded-circle" style="background: rgba(16, 185, 129, 0.12); color: #059669;">
                    <i class="fa-solid fa-users fs-5"></i>
                </div>
            </div>
            <div class="fs-4 fw-bold text-dark mb-1">{{ $stats['total_users'] }} <span class="fs-6 fw-normal text-muted">tài khoản</span></div>
            <div class="d-flex align-items-center justify-content-between text-muted small mt-auto pt-2 border-top">
                <span>Khách hàng: <strong class="text-dark">{{ $stats['customers_count'] }}</strong></span>
                <span class="badge bg-success-subtle text-success">+{{ $stats['new_users_today'] }} mới hôm nay</span>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================================
     2. KHU VỰC BIỂU ĐỒ BÁO CÁO THỐNG KÊ TRỰC QUAN (CHART.JS)
     ========================================================================= -->
<div class="row g-3 mb-4">
    <!-- Biểu đồ 1: Doanh Thu 7 Ngày Gần Nhất -->
    <div class="col-12 col-lg-8">
        <div class="card h-100 border">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title mb-0 fw-bold fs-6">
                        <i class="bi bi-graph-up-arrow me-2 text-primary"></i>Biểu Đồ Doanh Thu & Lượng Đơn 7 Ngày Gần Nhất
                    </h5>
                    <small class="text-muted">Xu hướng biến động doanh số bán hàng trong tuần</small>
                </div>
                <span class="badge bg-light text-dark border">Cập nhật tự động</span>
            </div>
            <div class="card-body p-3">
                <div style="height: 300px; position: relative;">
                    <canvas id="revenueTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ 2: Cơ Cấu Trạng Thái Đơn Hàng -->
    <div class="col-12 col-lg-4">
        <div class="card h-100 border">
            <div class="card-header py-3">
                <h5 class="card-title mb-0 fw-bold fs-6">
                    <i class="bi bi-pie-chart me-2 text-warning"></i>Tỷ Lệ Trạng Thái Đơn Hàng
                </h5>
                <small class="text-muted">Tỷ lệ hoàn tất: <strong>{{ $stats['orders_completion_rate'] }}%</strong></small>
            </div>
            <div class="card-body p-3 d-flex flex-column justify-content-center">
                <div style="height: 220px; position: relative;" class="mb-3">
                    <canvas id="orderStatusDoughnut"></canvas>
                </div>
                <div class="row g-2 text-center small mt-auto border-top pt-2">
                    <div class="col-6">
                        <span class="d-inline-block rounded-circle me-1" style="width: 8px; height: 8px; background: #10B981;"></span>
                        <span>Hoàn thành: <strong>{{ $stats['orders_completed'] }}</strong></span>
                    </div>
                    <div class="col-6">
                        <span class="d-inline-block rounded-circle me-1" style="width: 8px; height: 8px; background: #F59E0B;"></span>
                        <span>Chờ duyệt: <strong>{{ $stats['orders_pending'] }}</strong></span>
                    </div>
                    <div class="col-6">
                        <span class="d-inline-block rounded-circle me-1" style="width: 8px; height: 8px; background: #3B82F6;"></span>
                        <span>Đang giao: <strong>{{ $stats['orders_shipping'] }}</strong></span>
                    </div>
                    <div class="col-6">
                        <span class="d-inline-block rounded-circle me-1" style="width: 8px; height: 8px; background: #EF4444;"></span>
                        <span>Đã hủy: <strong>{{ $stats['orders_cancelled'] }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================================
     3. BIỂU ĐỒ DANH MỤC & CẢNH BÁO TỒN KHO
     ========================================================================= -->
<div class="row g-3 mb-4">
    <!-- Biểu đồ Phân Bổ Theo Danh Mục -->
    <div class="col-12 col-lg-7">
        <div class="card h-100 border">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title mb-0 fw-bold fs-6">
                        <i class="bi bi-bar-chart-line me-2 text-info"></i>Phân Bổ Sản Phẩm Theo Danh Mục
                    </h5>
                    <small class="text-muted">Số lượng sản phẩm thuộc từng nhóm gia dụng</small>
                </div>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size: 0.78rem;">
                    Xem danh mục →
                </a>
            </div>
            <div class="card-body p-3">
                <div style="height: 260px; position: relative;">
                    <canvas id="categoryBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách cảnh báo hàng tồn kho -->
    <div class="col-12 col-lg-5">
        <div class="card h-100 border">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title mb-0 fw-bold fs-6">
                        <i class="bi bi-exclamation-diamond me-2 text-danger"></i>Cảnh Báo Tồn Kho Cần Nhập
                    </h5>
                    <small class="text-muted">Sản phẩm sắp hết hàng (tồn ≤ 5)</small>
                </div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size: 0.78rem;">
                    Quản lý kho →
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Giá bán</th>
                                <th class="text-center">Tồn</th>
                                <th class="text-end">Tình trạng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products->sortBy('quantity')->take(6) as $p)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($p->image)
                                            @if(str_starts_with($p->image, 'http') || str_starts_with($p->image, 'images/'))
                                                <img src="{{ asset($p->image) }}" class="rounded border" style="width: 34px; height: 34px; object-fit: cover;">
                                            @else
                                                <img src="{{ asset('storage/' . $p->image) }}" class="rounded border" style="width: 34px; height: 34px; object-fit: cover;">
                                            @endif
                                        @else
                                            <div class="rounded bg-light border d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="text-truncate" style="max-width: 140px;" title="{{ $p->name }}">
                                            <strong>{{ $p->name }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ number_format($p->price, 0, ',', '.') }} đ</td>
                                <td class="text-center">
                                    <span class="fw-bold {{ ($p->quantity ?? $p->stock ?? 0) <= 2 ? 'text-danger' : 'text-warning' }}">
                                        {{ $p->quantity ?? $p->stock ?? 0 }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    @if(($p->quantity ?? $p->stock ?? 0) <= 0)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Hết hàng</span>
                                    @elseif(($p->quantity ?? $p->stock ?? 0) <= 5)
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Sắp hết</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Còn hàng</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Chưa có sản phẩm trong hệ thống.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


                    </div>

                    
                    <!-- Quản Lý Sản Phẩm -->
                    <div class="tab-pane fade" id="v-pills-product" role="tabpanel" aria-labelledby="v-pills-product-tab">
                        <h5 class="fw-bold text-dark mb-2"><i class="bi bi-box-seam text-primary me-2"></i>Quản Lý Sản Phẩm Thiết Bị Gia Dụng</h5>
                        <p class="text-muted small mb-4">Xem, thêm mới, hoặc chỉnh sửa thông tin các thiết bị gia dụng đang được kinh doanh trên hệ thống website.</p>
                        <div class="d-flex gap-3 mt-2">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                                <i class="bi bi-list-ul me-1"></i> Xem Tất Cả Sản Phẩm
                            </a>
                            <a href="{{ route('admin.products.create') }}" class="btn btn-outline-primary bg-white btn-sm px-4 shadow-sm">
                                <i class="bi bi-plus-circle me-1"></i> Thêm Sản Phẩm Mới
                            </a>
                        </div>
                    </div>

                    <!-- Quản Lý Danh Mục -->
                    <div class="tab-pane fade" id="v-pills-category" role="tabpanel" aria-labelledby="v-pills-category-tab">
                        <h5 class="fw-bold text-dark mb-2"><i class="bi bi-tags text-primary me-2"></i>Quản Lý Danh Mục Sản Phẩm</h5>
                        <p class="text-muted small mb-4">Phân nhóm các thiết bị (VD: Đồ dùng nhà bếp, máy hút bụi) để giúp khách hàng dễ dàng tìm kiếm hơn.</p>
                        <div class="d-flex gap-3 mt-2">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                                <i class="bi bi-list-ul me-1"></i> Xem Tất Cả Danh Mục
                            </a>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-primary bg-white btn-sm px-4 shadow-sm">
                                <i class="bi bi-folder-plus me-1"></i> Thêm Danh Mục Mới
                            </a>
                        </div>
                    </div>

                    <!-- Đơn Hàng Khách -->
                    <div class="tab-pane fade" id="v-pills-order" role="tabpanel" aria-labelledby="v-pills-order-tab">
                        <h5 class="fw-bold text-dark mb-2"><i class="bi bi-receipt text-danger me-2"></i>Quản Lý Đơn Hàng Khách Mua</h5>
                        <p class="text-muted small mb-4">Theo dõi chi tiết các đơn đặt hàng, kiểm tra thông tin thanh toán, và cập nhật trạng thái vận chuyển.</p>
                        <!-- =========================================================================
     4. BẢNG QUẢN LÝ ĐƠN HÀNG GẦN ĐÂY
     ========================================================================= -->
<div class="card border mb-4" id="section-orders">
    <div class="card-header py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h5 class="card-title mb-0 fw-bold fs-6">
                <i class="bi bi-receipt-cutoff me-2 text-warning"></i>Danh Sách Đơn Hàng Gần Đây
            </h5>
            <small class="text-muted">Quản lý trạng thái và giao dịch của khách hàng</small>
        </div>
        <div class="badge bg-light text-dark border px-3 py-2">
            Tổng cộng: <strong>{{ $recentOrders->count() }}</strong> đơn hàng
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Mã Đơn</th>
                        <th>Khách Hàng</th>
                        <th>Số Tiền</th>
                        <th>Phương Thức</th>
                        <th>Trạng Thái</th>
                        <th>Thanh Toán</th>
                        <th>Ngày Đặt</th>
                        <th class="text-end pe-3">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders->take(10) as $ord)
                    <tr>
                        <td class="ps-3 fw-bold text-dark">
                            #{{ $ord->id }}
                            <small class="text-muted d-block font-monospace">{{ $ord->order_code ?? 'DH'.$ord->id }}</small>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $ord->customer_name ?? $ord->receiver_name ?? 'Khách lẻ' }}</div>
                            <small class="text-muted">{{ $ord->customer_phone ?? $ord->phone_number ?? '---' }}</small>
                        </td>
                        <td>
                            <span class="fw-bold text-danger">{{ number_format($ord->total ?? $ord->amount ?? 0, 0, ',', '.') }} ₫</span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $ord->payment_method ?? 'VietQR' }}
                            </span>
                        </td>
                        <td>
                            @php
                                $statusClass = 'bg-secondary';
                                $st = strtolower($ord->status ?? '');
                                if (str_contains($st, 'hoàn thành') || str_contains($st, 'completed')) $statusClass = 'bg-success';
                                elseif (str_contains($st, 'chờ') || str_contains($st, 'pending')) $statusClass = 'bg-warning text-dark';
                                elseif (str_contains($st, 'đang giao') || str_contains($st, 'shipping')) $statusClass = 'bg-info text-dark';
                                elseif (str_contains($st, 'hủy') || str_contains($st, 'cancel')) $statusClass = 'bg-danger';
                            @endphp
                            <span class="badge {{ $statusClass }}">
                                {{ $ord->status ?? 'Chờ xác nhận' }}
                            </span>
                        </td>
                        <td>
                            @if(($ord->payment_status ?? '') === 'paid')
                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                    <i class="bi bi-check2-circle me-1"></i>Đã thanh toán
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                    <i class="bi bi-hourglass-split me-1"></i>Chưa thanh toán
                                </span>
                            @endif
                        </td>
                        <td class="small text-muted">
                            {{ $ord->created_at ? $ord->created_at->format('d/m/Y H:i') : '---' }}
                        </td>
                        <td class="text-end pe-3">
                            <!-- Nút cập nhật nhanh trạng thái -->
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Cập nhật
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    <li><h6 class="dropdown-header">Đổi trạng thái đơn</h6></li>
                                    <li>
                                        <form action="{{ route('admin.orders.update', $ord->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="Chờ xác nhận">
                                            <button type="submit" class="dropdown-item small">Chờ xác nhận</button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.orders.update', $ord->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="Đang giao hàng">
                                            <button type="submit" class="dropdown-item small">Đang giao hàng</button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.orders.update', $ord->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="Đã hoàn thành">
                                            <button type="submit" class="dropdown-item small text-success">Đã hoàn thành</button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.orders.update', $ord->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="Đã hủy">
                                            <button type="submit" class="dropdown-item small text-danger">Hủy đơn hàng</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Chưa có đơn hàng nào được ghi nhận.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
                    </div>

                    <!-- Người Dùng -->
                    <div class="tab-pane fade" id="v-pills-user" role="tabpanel" aria-labelledby="v-pills-user-tab">
                        <h5 class="fw-bold text-dark mb-2"><i class="bi bi-people text-info me-2"></i>Quản Lý Tài Khoản Người Dùng</h5>
                        <p class="text-muted small mb-4">Kiểm soát danh sách khách hàng đã đăng ký, hỗ trợ cấp quyền, khóa tài khoản hoặc sửa thông tin.</p>
                        <!-- =========================================================================
     5. BẢNG QUẢN LÝ NGƯỜI DÙNG & TÀI KHOẢN
     ========================================================================= -->
<div class="card border mb-4" id="section-users">
    <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-0 fw-bold fs-6">
                <i class="bi bi-people me-2 text-primary"></i>Danh Sách Người Dùng & Phân Quyền
            </h5>
            <small class="text-muted">Quản trị viên và khách hàng đã đăng ký tài khoản</small>
        </div>
        <span class="badge bg-light text-dark border">
            {{ $users->count() }} Tài khoản
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Người dùng</th>
                        <th>Email</th>
                        <th>Số Điện Thoại</th>
                        <th>Vai Trò</th>
                        <th>Trạng Thái</th>
                        <th>Ngày Tham Gia</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem; background: {{ $user->role === 'admin' ? 'linear-gradient(135deg, #DFC07A, #C5A059)' : '#94A3B8' }};">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="fw-semibold text-dark">{{ $user->name }}</span>
                                    @if($user->id === Auth::id())
                                        <span class="badge bg-info-subtle text-info small ms-1">Bạn</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? '---' }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge bg-warning text-dark">
                                    <i class="fa-solid fa-shield-halved me-1"></i> Quản Trị Viên
                                </span>
                            @else
                                <span class="badge bg-light text-secondary border">
                                    <i class="fa-regular fa-user me-1"></i> Khách Hàng
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                Hoạt động
                            </span>
                        </td>
                        <td class="small text-muted">
                            {{ $user->created_at ? $user->created_at->format('d/m/Y') : '---' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
                    </div>

                    <!-- Tin Nhắn -->
                    <div class="tab-pane fade" id="v-pills-chat" role="tabpanel" aria-labelledby="v-pills-chat-tab">
                        <h5 class="fw-bold text-dark mb-2"><i class="bi bi-chat-dots text-success me-2"></i>Hộp Thư Tin Nhắn & Hỗ Trợ Khách Hàng</h5>
                        <p class="text-muted small mb-4">Trung tâm phản hồi và tư vấn nhanh chóng các thắc mắc của khách hàng gửi về qua hộp thoại chat trên web.</p>
                        <!-- =========================================================================
     6. TRUNG TÂM TIN NHẮN TƯ VẤN KHÁCH HÀNG (LIVE CHAT SUPPORT)
     ========================================================================= -->
<div class="card border mb-4" id="section-chat" style="border-radius: 16px; overflow: hidden;">
    <div class="card-header py-3 d-flex flex-wrap align-items-center justify-content-between gap-2" style="background: linear-gradient(135deg, #FFFFFF 0%, #F8FAFC 100%); border-bottom: 1.5px solid #E2E8F0;">
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 rounded-circle" style="background: rgba(197, 160, 89, 0.15); color: #9A7B38;">
                <i class="fa-solid fa-headset fs-5"></i>
            </div>
            <div>
                <h5 class="card-title mb-0 fw-bold fs-6 text-dark d-flex align-items-center gap-2">
                    <span>Trung Tâm Tin Nhắn Khách Hàng (Live Chat)</span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle small fw-normal">Trực tiếp</span>
                </h5>
                <small class="text-muted">Nhận và phản hồi tin nhắn của khách hàng theo thời gian thực</small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-dark border px-3 py-2" id="adminChatTotalLabel">
                <i class="fa-regular fa-bell me-1 text-warning"></i> Tự động đồng bộ mỗi 3 giây
            </span>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="row g-0" style="min-height: 520px;">
            <!-- Cột trái: Danh sách khách hàng đã nhắn tin (35%) -->
            <div class="col-12 col-md-4 border-end" style="background: #FAFBFD; display: flex; flex-direction: column;">
                <div class="p-3 border-bottom bg-white">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="adminChatSearchInput" class="form-control bg-light border-start-0" placeholder="Tìm tên khách, SĐT...">
                    </div>
                </div>
                <div id="adminChatConvList" style="flex: 1; overflow-y: auto; max-height: 480px;">
                    <!-- JS renders conversations here -->
                </div>
            </div>

            <!-- Cột phải: Khung hội thoại & Phản hồi (65%) -->
            <div class="col-12 col-md-8 bg-white" style="display: flex; flex-direction: column; justify-content: space-between;">
                <!-- Header thông tin khách -->
                <div id="adminChatActiveHeader" class="p-3 border-bottom d-flex align-items-center justify-content-between" style="background: #FFFFFF;">
                    <div class="d-flex align-items-center gap-2 text-muted small">
                        <i class="fa-regular fa-comments fs-5 text-warning"></i>
                        <span>Chọn một cuộc hội thoại từ danh sách bên trái để xem và trả lời</span>
                    </div>
                </div>

                <!-- Thân tin nhắn -->
                <div id="adminChatActiveBody" class="p-3" style="flex: 1; overflow-y: auto; max-height: 380px; min-height: 300px; background: #F8FAFC;">
                    <div class="text-center text-muted py-5">
                        <i class="fa-regular fa-message fs-1 opacity-25 mb-2 d-block"></i>
                        Chưa chọn cuộc trò chuyện nào
                    </div>
                </div>

                <!-- Cụm câu trả lời nhanh & Khung gửi tin nhắn -->
                <div id="adminChatActiveFooter" class="p-3 border-top bg-white" style="display: none;">
                    <!-- Quick replies buttons -->
                    <div class="d-flex gap-1 flex-wrap mb-2">
                        <button type="button" class="btn btn-xs btn-light border text-muted small py-1 px-2" style="font-size: 0.75rem;" onclick="window.FamilyAdminChat.quickReply('Dạ FAMILY xin kính chào Quý khách! Em có thể tư vấn sản phẩm nào cho mình ạ?')">👋 Chào hỏi</button>
                        <button type="button" class="btn btn-xs btn-light border text-muted small py-1 px-2" style="font-size: 0.75rem;" onclick="window.FamilyAdminChat.quickReply('Dạ tất cả sản phẩm tại FAMILY đều được bảo hành chính hãng 24 tháng, 1 đổi 1 trong 30 ngày Quý khách nhé!')">🛡️ Bảo hành 24T</button>
                        <button type="button" class="btn btn-xs btn-light border text-muted small py-1 px-2" style="font-size: 0.75rem;" onclick="window.FamilyAdminChat.quickReply('Dạ đơn hàng của Quý khách đã được tiếp nhận và nhân viên sẽ liên hệ đóng gói gửi đi ngay trong ngày ạ.')">📦 Đơn hàng</button>
                        <button type="button" class="btn btn-xs btn-light border text-muted small py-1 px-2" style="font-size: 0.75rem;" onclick="window.FamilyAdminChat.quickReply('Dạ Quý khách có thể chuyển khoản VietQR tự động để nhận ưu đãi miễn phí giao hàng toàn quốc ạ.')">💳 VietQR FreeShip</button>
                    </div>
                    <div class="input-group">
                        <input type="text" id="adminChatInputText" class="form-control" placeholder="Nhập tin nhắn trả lời khách hàng... (Enter để gửi)">
                        <button type="button" id="adminChatSendBtn" class="btn btn-primary px-3 fw-semibold">
                            <i class="fa-solid fa-paper-plane me-1"></i> Trả lời
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                    </div>

                    <!-- Sửa Ảnh Banner -->
                    <div class="tab-pane fade" id="v-pills-banner" role="tabpanel" aria-labelledby="v-pills-banner-tab">
                        <h5 class="fw-bold text-dark mb-2"><i class="bi bi-images text-warning me-2"></i>Quản lý thứ tự Banner Trang Chủ</h5>
                        <p class="text-muted small mb-4">Tải lên tối đa 3 ảnh banner. Bạn có thể cập nhật từng vị trí hoặc cập nhật tất cả cùng lúc. Các vị trí để trống sẽ giữ nguyên banner cũ.</p>
                        <form id="inlineBannerForm" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-secondary">Banner 1 (Đầu tiên)</label>
                                <input type="file" class="form-control form-control-sm" id="inline_banner_1" accept="image/*">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-secondary">Banner 2 (Tiếp theo)</label>
                                <input type="file" class="form-control form-control-sm" id="inline_banner_2" accept="image/*">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-secondary">Banner 3 (Cuối cùng)</label>
                                <input type="file" class="form-control form-control-sm" id="inline_banner_3" accept="image/*">
                            </div>
                            <div class="col-12 mt-4">
                                <button type="button" class="btn btn-warning btn-sm fw-bold px-4 shadow-sm" onclick="submitInlineBanner()">
                                    <i class="bi bi-cloud-upload me-1"></i> Lưu Cập Nhật Banner
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Sửa Logo -->
                    <div class="tab-pane fade" id="v-pills-logo" role="tabpanel" aria-labelledby="v-pills-logo-tab">
                        <h5 class="fw-bold text-dark mb-2"><i class="bi bi-camera text-success me-2"></i>Cập Nhật Logo Website</h5>
                        <p class="text-muted small mb-4">Khuyến nghị sử dụng ảnh định dạng PNG có nền trong suốt (transparent) để đảm bảo độ thẩm mỹ cao nhất trên các nền màu khác nhau.</p>
                        <div class="d-flex align-items-end gap-3 bg-light p-3 rounded border">
                            <div class="flex-grow-1">
                                <label class="form-label fw-bold small mb-2">Chọn tệp Logo từ máy tính:</label>
                                <input type="file" class="form-control form-control-sm" id="inlineLogoInput" accept="image/png, image/jpeg">
                            </div>
                            <button type="button" class="btn btn-success btn-sm fw-bold px-4 shadow-sm" onclick="uploadImage(document.getElementById('inlineLogoInput'), 'logo')">
                                <i class="bi bi-check2-circle me-1"></i> Tải Logo Lên
                            </button>
                        </div>
                    </div>

                    <!-- In Báo Cáo -->
                    <div class="tab-pane fade" id="v-pills-print" role="tabpanel" aria-labelledby="v-pills-print-tab">
                        <h5 class="fw-bold text-dark mb-2"><i class="bi bi-printer text-secondary me-2"></i>Kết Xuất & In Ấn Báo Cáo</h5>
                        <p class="text-muted small mb-4">Chức năng này cho phép bạn xuất toàn bộ nội dung của Bảng điều khiển (Dashboard) thành file PDF lưu trữ hoặc in trực tiếp ra giấy.</p>
                        <button type="button" class="btn btn-dark btn-sm px-4 shadow-sm" onclick="window.print()">
                            <i class="bi bi-printer-fill me-1"></i> Khởi động trình In Báo Cáo (Ctrl+P)
                        </button>
                    </div>

                    <!-- Cài Đặt Khuyến Mãi -->
                    <div class="tab-pane fade" id="v-pills-promo" role="tabpanel" aria-labelledby="v-pills-promo-tab">
                        <h5 class="fw-bold text-dark mb-2"><i class="bi bi-ticket-perforated text-danger me-2"></i>Cài Đặt Mã Khuyến Mãi Trang Chủ</h5>
                        <p class="text-muted small mb-4">Thay đổi mức giá trị mã giảm giá hiển thị trên trang chủ.</p>
                        
                        @php
                            $promoValue = '500K';
                            $promoCode = 'FAMILY500';
                            if(file_exists(storage_path('app/settings.json'))) {
                                $settings = json_decode(file_get_contents(storage_path('app/settings.json')), true);
                                if(isset($settings['promo_value'])) {
                                    $promoValue = $settings['promo_value'];
                                }
                                if(isset($settings['promo_code'])) {
                                    $promoCode = $settings['promo_code'];
                                }
                            }
                        @endphp
                        
                        <form action="{{ route('admin.settings.promo') }}" method="POST" class="bg-light p-3 rounded border" style="max-width: 500px;">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Mức giá mã giảm giá hiển thị (VD: 500K, 200.000đ):</label>
                                <input type="text" class="form-control" name="promo_value" value="{{ $promoValue }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Mã giảm giá thực tế (VD: FAMILY500):</label>
                                <input type="text" class="form-control" name="promo_code" value="{{ $promoCode }}" required>
                            </div>
                            <button type="submit" class="btn btn-danger btn-sm fw-bold px-4 shadow-sm">
                                <i class="bi bi-save me-1"></i> Lưu Cập Nhật
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


</div>
</div>


@push('scripts')
<script>
// Hàm xử lý upload Logo
function uploadImage(input, type) {
    if (!input.files || input.files.length === 0) return;
    const formData = new FormData();
    formData.append(type, input.files[0]);
    formData.append('_token', '{{ csrf_token() }}');
    
    fetch('{{ route("admin.logo.upload") }}', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(`Cập nhật Logo thành công! Hãy tải lại trang để xem thay đổi.`);
            } else {
                alert(`Lỗi: ${data.message || 'Không thể cập nhật Logo'}`);
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Đã xảy ra lỗi khi tải ảnh lên.');
        });
    input.value = '';
}

// Hàm xử lý upload Banner từ Form Inline
function submitInlineBanner() {
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    let hasFile = false;
    
    for (let i = 1; i <= 3; i++) {
        const input = document.getElementById('inline_banner_' + i);
        if (input && input.files.length > 0) {
            formData.append('banner_' + i, input.files[0]);
            hasFile = true;
        }
    }
    
    if (!hasFile) {
        alert("Vui lòng chọn ít nhất 1 banner để cập nhật.");
        return;
    }

    fetch('{{ route("admin.banner.upload") }}', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Cập nhật Banner thành công! Tải lại trang chủ để xem thay đổi.');
                document.getElementById('inlineBannerForm').reset();
            } else {
                alert(`Lỗi: ${data.message || 'Không thể cập nhật Banner'}`);
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Đã xảy ra lỗi khi tải ảnh lên.');
        });
}

document.addEventListener('DOMContentLoaded', function() {
    // 1. Biểu đồ Doanh Thu 7 Ngày (Line/Area Chart)
    const revCtx = document.getElementById('revenueTrendChart');
    if (revCtx) {
        const labels = @json($stats['chart_labels']);
        const revData = @json($stats['chart_revenue']);
        const orderData = @json($stats['chart_orders']);

        new Chart(revCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Doanh thu (VNĐ)',
                        data: revData,
                        borderColor: '#C5A059',
                        backgroundColor: 'rgba(197, 160, 89, 0.12)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.35,
                        yAxisID: 'y',
                        pointBackgroundColor: '#FFFFFF',
                        pointBorderColor: '#C5A059',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    },
                    {
                        label: 'Số đơn hàng',
                        data: orderData,
                        borderColor: '#3B82F6',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        tension: 0.2,
                        yAxisID: 'y1',
                        pointBackgroundColor: '#3B82F6',
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        ticks: {
                            callback: function(value) {
                                return (value / 1000000).toFixed(1) + 'M';
                            }
                        },
                        grid: {
                            color: '#F1F5F9'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 14,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.datasetIndex === 0) {
                                    label += new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' ₫';
                                } else {
                                    label += context.parsed.y + ' đơn';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    // 2. Biểu đồ Doughnut Trạng Thái Đơn Hàng
    const orderCtx = document.getElementById('orderStatusDoughnut');
    if (orderCtx) {
        new Chart(orderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Hoàn thành', 'Chờ xử lý', 'Đang giao', 'Đã hủy'],
                datasets: [{
                    data: [
                        {{ $stats['orders_completed'] }},
                        {{ $stats['orders_pending'] }},
                        {{ $stats['orders_shipping'] }},
                        {{ $stats['orders_cancelled'] }}
                    ],
                    backgroundColor: ['#10B981', '#F59E0B', '#3B82F6', '#EF4444'],
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '70%'
            }
        });
    }

    // 3. Biểu đồ Bar Danh Mục Sản Phẩm
    const catCtx = document.getElementById('categoryBarChart');
    if (catCtx) {
        const catLabels = @json($stats['cat_labels']);
        const catCounts = @json($stats['cat_counts']);

        new Chart(catCtx, {
            type: 'bar',
            data: {
                labels: catLabels,
                datasets: [{
                    label: 'Số lượng sản phẩm',
                    data: catCounts,
                    backgroundColor: 'rgba(197, 160, 89, 0.75)',
                    borderColor: '#C5A059',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        grid: { color: '#F1F5F9' }
                    },
                    x: {
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
});

</script>
@endpush
@endsection
