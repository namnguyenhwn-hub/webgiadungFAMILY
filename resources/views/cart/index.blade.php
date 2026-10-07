@extends('layouts.app') 

@section('title', 'Giỏ hàng & thanh toán - FAMILY') 

@section('content') 
@php 
    $prefix = request()->is('webgiadung*') ? '/webgiadung' : ''; 
@endphp
<div class="container py-4 my-2" style="max-width: 1200px;">
    <!-- Breadcrumb điều hướng cao cấp -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0 py-2 px-3 rounded-pill bg-white border" style="border-color: #E5E7EB !important; display: inline-flex;">
            <li class="breadcrumb-item"><a href="{{ $prefix . route('welcome', [], false) }}" class="text-decoration-none text-muted"><i class="bi bi-house me-1"></i> Trang chủ</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page"><i class="bi bi-cart3 me-1"></i> Giỏ hàng & thanh toán</li>
        </ol>
    </nav>

    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom" style="border-color: #E5E7EB !important;">
        <div>
            <h2 class="fw-bold mb-1" style="color: #111827; font-family: 'Playfair Display', serif;">
                <i class="bi bi-bag-check text-warning me-2"></i> Giỏ hàng & đặt hàng
            </h2>
            <p class="text-muted mb-0 small">Kiểm tra giỏ hàng, điền địa chỉ giao hàng và chọn phương thức thanh toán</p>
        </div>
        <a href="{{ $prefix . route('welcome', [], false) }}" class="btn btn-outline-secondary rounded-pill px-3 py-2 btn-sm fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Tiếp tục mua sắm
        </a>
    </div>

    @if(session('success')) 
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="background: #ECFDF5; color: #065F46; border-radius: 14px;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i><strong>Thành công:</strong> {{ session('success') }} 
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> 
        </div> 
    @endif 

    @if(session('error')) 
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="background: #FEF2F2; color: #991B1B; border-radius: 14px;">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i><strong>Thông báo:</strong> {{ session('error') }} 
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> 
        </div> 
    @endif 

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="background: #FEF2F2; color: #991B1B; border-radius: 14px;">
            <div class="fw-bold mb-1"><i class="bi bi-x-circle-fill me-2"></i>Vui lòng kiểm tra lại thông tin:</div>
            <ul class="mb-0 ps-4 small">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> 
        </div>
    @endif

    @if(isset($cart) && count($cart) > 0) 
        <!-- 1. BẢNG DANH SÁCH SẢN PHẨM TRONG GIỎ HÀNG -->
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px; overflow: hidden; background: #FFFFFF; border: 1px solid #E5E7EB !important;"> 
            <div class="card-header bg-white py-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2" style="border-color: #E5E7EB !important;">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-list-check me-2 text-primary"></i>Danh sách sản phẩm ({{ count($cart) }})
                </h5>
                <div class="d-flex align-items-center gap-2">
                    <form action="{{ $prefix . route('cart.clear', [], false) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa toàn bộ sản phẩm trong giỏ hàng để chọn lại từ đầu?');" class="mb-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 fw-semibold" title="Xóa tất cả để chọn lại">
                            <i class="bi bi-trash3 me-1"></i> Xóa tất cả
                        </button>
                    </form>
                    <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill">
                        Tồn kho sẵn có &bull; Giao hỏa tốc 2H
                    </span>
                </div>
            </div>
            <div class="card-body p-0"> 
                <div class="table-responsive"> 
                    <table class="table align-middle mb-0"> 
                        <thead style="background: #F8FAFC; color: #334155; font-size: 0.875rem;"> 
                            <tr> 
                                <th class="ps-4 py-3" style="font-weight: 700;">Sản phẩm</th> 
                                <th class="py-3" style="font-weight: 700;">Danh mục</th> 
                                <th class="py-3" style="font-weight: 700;">Đơn giá</th> 
                                <th class="py-3 text-center" style="width: 170px; font-weight: 700;">Số lượng</th> 
                                <th class="py-3" style="font-weight: 700;">Thành tiền</th> 
                                <th class="text-center py-3" style="font-weight: 700;">Hành động</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            @php $total = 0; @endphp 
                            @foreach($cart as $id => $details)
                                @php  
                                    $subtotal = $details['price'] * $details['quantity'];  
                                    $total += $subtotal;  
                                @endphp 
                                <tr style="border-bottom: 1px solid #F1F5F9;"> 
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            @if(!empty($details['image']))
                                                @if(str_starts_with($details['image'], 'http') || str_starts_with($details['image'], 'images/'))
                                                    <img src="{{ asset($details['image']) }}" alt="{{ $details['name'] }}" style="width: 56px; height: 56px; object-fit: contain; border-radius: 10px; background: #F8FAFC; padding: 4px; border: 1px solid #E5E7EB;">
                                                @else
                                                    <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}" style="width: 56px; height: 56px; object-fit: contain; border-radius: 10px; background: #F8FAFC; padding: 4px; border: 1px solid #E5E7EB;">
                                                @endif
                                            @else
                                                <div style="width: 56px; height: 56px; border-radius: 10px; background: #F1F5F9; display: flex; align-items: center; justify-content: center; color: #94A3B8;">
                                                    <i class="bi bi-box-seam fs-4"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $details['name'] }}</div>
                                                <small class="text-muted">Mã SP: #{{ $id }}</small>
                                            </div>
                                        </div>
                                    </td> 
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                                            {{ $details['category'] ?? 'Thiết bị gia dụng' }}
                                        </span>
                                    </td> 
                                    <td class="text-dark fw-semibold">{{ number_format($details['price'], 0, ',', '.') }} đ</td> 
                                    <td> 
                                        <form action="{{ $prefix . route('cart.update', $id, false) }}" method="POST" class="d-flex align-items-center justify-content-center gap-1"> 
                                            @csrf 
                                            @method('PATCH') 
                                            <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" class="form-control form-control-sm text-center fw-bold" style="width: 65px; border-radius: 8px;"> 
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px;" title="Cập nhật">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button> 
                                        </form> 
                                    </td> 
                                    <td class="fw-bold text-danger" style="font-size: 1rem;">{{ number_format($subtotal, 0, ',', '.') }} đ</td> 
                                    <td class="text-center"> 
                                        <form action="{{ $prefix . route('cart.destroy', $id, false) }}" method="POST" class="d-inline"> 
                                            @csrf 
                                            @method('DELETE') 
                                            <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 8px; padding: 4px 10px;" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                                <i class="bi bi-trash"></i> Xóa
                                            </button> 
                                        </form> 
                                    </td> 
                                </tr> 
                            @endforeach
                        </tbody> 
                    </table> 
                </div> 
            </div> 
        </div> 

        <!-- 2. FORM ĐIỀN THÔNG TIN GIAO HÀNG & PHƯƠNG THỨC THANH TOÁN -->
        <form action="{{ $prefix . route('checkout.process', [], false) }}" method="POST" id="checkoutOrderForm" onsubmit="return validateCheckoutForm(this);">
            @csrf
            <input type="hidden" name="total" value="{{ $total }}">
            <script>
                function validateCheckoutForm(form) {
                    if (!form.receiver_name.value.trim() || !form.phone_number.value.trim() || !form.shipping_address.value.trim()) {
                        alert('Vui lòng điền đầy đủ Họ tên, Số điện thoại và Địa chỉ nhận hàng chi tiết trước khi thanh toán!');
                        return false;
                    }
                    return true;
                }
            </script>

            <div class="row g-4 align-items-start"> 
                <!-- CỘT TRÁI: THÔNG TIN GIAO HÀNG & PHƯƠNG THỨC THANH TOÁN -->
                <div class="col-lg-7">
                    <!-- KHỐI 1: THÔNG TIN GIAO HÀNG (TÊN, SĐT, ĐỊA CHỈ) -->
                    <div class="card p-4 border-0 shadow-sm mb-4" style="border-radius: 16px; background: #FFFFFF; border: 1px solid #E5E7EB !important;">
                        <h5 class="fw-bold mb-3 text-dark d-flex align-items-center">
                            <span class="badge rounded-circle bg-primary me-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.85rem;">1</span>
                            Thông tin người nhận hàng
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="receiver_name" class="form-label fw-semibold small text-muted">
                                    Họ và tên người nhận <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-secondary"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 @error('receiver_name') is-invalid @enderror" 
                                           id="receiver_name" name="receiver_name" 
                                           value="{{ old('receiver_name', Auth::user()->name ?? '') }}" 
                                           placeholder="Nhập họ và tên..." required>
                                </div>
                                @error('receiver_name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone_number" class="form-label fw-semibold small text-muted">
                                    Số điện thoại liên hệ <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-telephone text-secondary"></i></span>
                                    <input type="tel" class="form-control border-start-0 ps-0 @error('phone_number') is-invalid @enderror" 
                                           id="phone_number" name="phone_number" 
                                           value="{{ old('phone_number', Auth::user()->phone ?? '0334808383') }}" 
                                           placeholder="Nhập số điện thoại..." required>
                                </div>
                                @error('phone_number')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="shipping_address" class="form-label fw-semibold small text-muted">
                                    Địa chỉ nhận hàng chi tiết <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-geo-alt text-secondary"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 @error('shipping_address') is-invalid @enderror" 
                                           id="shipping_address" name="shipping_address" 
                                           value="{{ old('shipping_address', Auth::user()->address ?? '') }}" 
                                           placeholder="Nhập địa chỉ giao hàng..." required>
                                </div>
                                @error('shipping_address')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="note" class="form-label fw-semibold small text-muted">
                                    Ghi chú giao hàng (tùy chọn)
                                </label>
                                <textarea class="form-control" id="note" name="note" rows="2" 
                                          placeholder="Ghi chú đơn hàng (nếu có)...">{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- KHỐI 2: CHỌN HÌNH THỨC THANH TOÁN -->
                    <div class="card p-4 border-0 shadow-sm" style="border-radius: 16px; background: #FFFFFF; border: 1px solid #E5E7EB !important;">
                        <h5 class="fw-bold mb-3 text-dark d-flex align-items-center">
                            <span class="badge rounded-circle bg-primary me-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.85rem;">2</span>
                            Chọn hình thức thanh toán
                        </h5>

                        <div class="d-flex flex-column gap-3">
                            <!-- Phương thức 1: SePay Tự Động 24/7 (Khuyên Dùng) -->
                            <label class="payment-option-card border rounded-3 p-3 d-flex align-items-center gap-3 position-relative cursor-pointer selected-option" for="pm_sepay" style="cursor: pointer; border-color: #C5A059 !important; background: #FFFDF9;">
                                <input type="radio" class="form-check-input mt-0" name="payment_method" id="pm_sepay" value="sepay" checked onchange="highlightPaymentOption(this)">
                                <div class="flex-grow-1 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <strong class="text-dark"><i class="bi bi-lightning-charge-fill text-warning me-1"></i> Chuyển khoản ngân hàng tự động (SePay VietQR 24/7)</strong>
                                    <span class="badge bg-warning text-dark border px-2 py-1 rounded-pill small">Khuyên dùng</span>
                                </div>
                            </label>

                            <!-- Phương thức 2: COD (Thanh toán khi nhận hàng) -->
                            <label class="payment-option-card border rounded-3 p-3 d-flex align-items-center gap-3 position-relative cursor-pointer" for="pm_cod" style="cursor: pointer; border-color: #E5E7EB; background: #FFFFFF;">
                                <input type="radio" class="form-check-input mt-0" name="payment_method" id="pm_cod" value="COD" onchange="highlightPaymentOption(this)">
                                <div class="flex-grow-1 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <strong class="text-dark"><i class="bi bi-cash-stack text-success me-1"></i> Thanh toán tiền mặt khi nhận hàng (COD)</strong>
                                    <span class="badge bg-light text-secondary border px-2 py-1 rounded-pill small">An tâm 100%</span>
                                </div>
                            </label>

                            <!-- Phương thức 3: Ví Điện Tử MoMo / ZaloPay -->
                            <label class="payment-option-card border rounded-3 p-3 d-flex align-items-center gap-3 position-relative cursor-pointer" for="pm_momo" style="cursor: pointer; border-color: #E5E7EB; background: #FFFFFF;">
                                <input type="radio" class="form-check-input mt-0" name="payment_method" id="pm_momo" value="momo" onchange="highlightPaymentOption(this)">
                                <div class="flex-grow-1 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <strong class="text-dark"><i class="bi bi-wallet2 text-danger me-1"></i> Ví điện tử MoMo / ZaloPay / Viettel Money</strong>
                                    <span class="badge bg-danger-subtle text-danger border px-2 py-1 rounded-pill small">Mới &bull; tiện lợi</span>
                                </div>
                            </label>

                            <!-- Phương thức 4: Thẻ Tín Dụng / Ghi Nợ Quốc Tế & ATM -->
                            <label class="payment-option-card border rounded-3 p-3 d-flex align-items-center gap-3 position-relative cursor-pointer" for="pm_card" style="cursor: pointer; border-color: #E5E7EB; background: #FFFFFF;">
                                <input type="radio" class="form-check-input mt-0" name="payment_method" id="pm_card" value="card" onchange="highlightPaymentOption(this)">
                                <div class="flex-grow-1 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <strong class="text-dark"><i class="bi bi-credit-card-2-front text-primary me-1"></i> Thẻ ATM nội địa / thẻ quốc tế (Visa, MasterCard, JCB)</strong>
                                    <span class="badge bg-light text-secondary border px-2 py-1 rounded-pill small">Bảo mật SSL</span>
                                </div>
                            </label>

                            <!-- Phương thức 5: Chuyển khoản VietQR BIDV Thủ Công -->
                            <label class="payment-option-card border rounded-3 p-3 d-flex align-items-center gap-3 position-relative cursor-pointer" for="pm_bidv" style="cursor: pointer; border-color: #E5E7EB; background: #FFFFFF;">
                                <input type="radio" class="form-check-input mt-0" name="payment_method" id="pm_bidv" value="bidv" onchange="highlightPaymentOption(this)">
                                <div class="flex-grow-1 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <strong class="text-dark"><i class="bi bi-bank text-info me-1"></i> Chuyển khoản VietQR ngân hàng BIDV (Mã Napas 24/7)</strong>
                                </div>
                            </label>
                        </div>
                    </div>
                </div> 

                <!-- CỘT PHẢI: TÓM TẮT ĐƠN HÀNG VÀ NÚT ĐẶT HÀNG -->
                <div class="col-lg-5">
                    <div class="card p-4 border-0 shadow-sm sticky-top" style="border-radius: 16px; background: #FFFFFF; border: 1px solid #E5E7EB !important; top: 90px;">
                        <h5 class="fw-bold mb-3 text-dark pb-2 border-bottom" style="border-color: #E5E7EB !important;">
                            <i class="bi bi-receipt-cutoff me-2 text-warning"></i>Tóm tắt đơn hàng
                        </h5>

                        <div class="d-flex justify-content-between py-2 text-muted small">
                            <span>Tạm tính ({{ count($cart) }} sản phẩm):</span>
                            <span class="fw-bold text-dark fs-6">{{ number_format($total, 0, ',', '.') }} đ</span>
                        </div>

                        <div class="d-flex justify-content-between py-2 text-muted small">
                            <span>Phí vận chuyển giao tận nơi:</span>
                            <span class="text-success fw-bold"><i class="bi bi-truck me-1"></i>Miễn phí VIP</span>
                        </div>

                        <div class="d-flex justify-content-between py-2 text-muted small border-bottom pb-3" style="border-color: #E5E7EB !important;">
                            <span>Chính sách bảo hành:</span>
                            <span class="text-primary fw-semibold"><i class="bi bi-shield-check me-1"></i>Chính hãng 24 tháng</span>
                        </div>

                        <div class="pt-3 mb-4">
                            <div class="d-flex justify-content-between align-items-baseline mb-1">
                                <span class="fs-5 fw-bold text-dark">Tổng thanh toán:</span>
                                <h3 class="fw-bold mb-0 text-danger" style="color: #B8860B !important;">
                                    {{ number_format($total, 0, ',', '.') }} đ
                                </h3>
                            </div>
                            <small class="text-muted d-block text-end">(Đã bao gồm VAT &amp; bảo hiểm hàng hóa)</small>
                        </div>

                        @if(Auth::check())
                            <button type="submit" class="btn w-100 py-3 fw-bold shadow-sm" style="background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; border: none; border-radius: 9999px; font-size: 1.05rem; box-shadow: 0 6px 20px rgba(197, 160, 89, 0.35);">
                                <i class="bi bi-check2-circle me-2"></i> Xác nhận &amp; đặt hàng ngay
                            </button>
                        @else
                            <button type="button" onclick="window.location.href='{{ $prefix }}/login?redirect={{ urlencode($prefix . '/cart#checkoutOrderForm') }}'" class="btn w-100 py-3 fw-bold shadow-sm" style="background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; border: none; border-radius: 9999px; font-size: 1.05rem; box-shadow: 0 6px 20px rgba(197, 160, 89, 0.35);">
                                <i class="bi bi-person-lock me-2"></i> Đăng nhập để thanh toán
                            </button>
                        @endif

                        <div class="text-center mt-3 pt-3 border-top" style="border-color: #E5E7EB !important;">
                            <div class="text-muted small mb-2"><i class="bi bi-shield-lock-fill text-success me-1"></i>Giao dịch an toàn &amp; bảo mật thông tin 100%</div>
                            <div class="d-flex justify-content-center align-items-center gap-3 text-muted" style="font-size: 1.3rem;">
                                <i class="bi bi-qr-code-scan" title="VietQR"></i>
                                <i class="bi bi-bank" title="BIDV"></i>
                                <i class="bi bi-credit-card" title="Visa / Master"></i>
                                <i class="bi bi-cash" title="COD"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </form>

        <script>
        // Đồng bộ dữ liệu giỏ hàng chuẩn từ Laravel Session về localStorage
        try {
            const sessionCart = @json($cart);
            const localItems = [];
            for (const [id, item] of Object.entries(sessionCart)) {
                localItems.push({
                    id: parseInt(id, 10),
                    name: item.name,
                    price: item.price,
                    quantity: item.quantity,
                    image: item.image,
                    category: item.category || 'Gia dụng cao cấp'
                });
            }
            if (localItems.length > 0) {
                localStorage.setItem('aura_cart', JSON.stringify(localItems));
                localStorage.setItem('family_cart', JSON.stringify(localItems));
            } else {
                localStorage.removeItem('aura_cart');
                localStorage.removeItem('family_cart');
            }
        } catch(e) {}
        </script>
    @else 
        <!-- TRẠNG THÁI GIỎ HÀNG TRỐNG -->
        <div id="cartEmptyState" class="text-center py-5 rounded border-0 shadow-sm bg-white" style="border-radius: 16px; border: 1px solid #E5E7EB !important; padding: 70px 20px;"> 
            <i class="bi bi-cart-x mb-3 text-warning" style="font-size: 4rem;"></i>
            <h3 class="fw-bold text-dark">Giỏ hàng của bạn đang trống</h3>
            <p class="text-muted mb-4" style="max-width: 480px; margin: 0 auto;">Hãy khám phá các thiết bị gia dụng cao cấp tại FAMILY để chọn mua sản phẩm ưng ý.</p>
            <a href="{{ $prefix . route('welcome', [], false) }}" class="btn py-2 px-4 fw-bold rounded-pill" style="background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; text-decoration: none;">
                <i class="bi bi-bag-plus me-1"></i> Mua sắm sản phẩm ngay
            </a> 
        </div> 

        <script>
        try {
            const localStr = localStorage.getItem('family_cart') || localStorage.getItem('aura_cart');
            if (localStr && !sessionStorage.getItem('cart_resync_attempted')) {
                const localItems = JSON.parse(localStr);
                const validItems = Array.isArray(localItems) ? localItems.filter(it => it && it.id && parseInt(it.id, 10) > 0) : [];
                if (validItems.length > 0) {
                    sessionStorage.setItem('cart_resync_attempted', '1');
                    const isWebgiadung = window.location.pathname.includes('/webgiadung');
                    const syncUrl = isWebgiadung ? '/webgiadung/api/cart/sync' : '/api/cart/sync';
                    fetch(syncUrl, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        body: JSON.stringify({ items: validItems, mode: 'full' })
                    }).then(() => {
                        window.location.reload();
                    }).catch(() => {});
                } else {
                    localStorage.removeItem('aura_cart');
                    localStorage.removeItem('family_cart');
                }
            } else {
                sessionStorage.removeItem('cart_resync_attempted');
                localStorage.removeItem('aura_cart');
                localStorage.removeItem('family_cart');
                const badge = document.getElementById('navCartBadge');
                if (badge) badge.style.display = 'none';
            }
        } catch(e) {}
        </script>
    @endif 
</div> 

<script>
function highlightPaymentOption(radio) {
    document.querySelectorAll('.payment-option-card').forEach(card => {
        card.style.borderColor = '#E5E7EB';
        card.style.background = '#FFFFFF';
    });
    if (radio.checked) {
        const parent = radio.closest('.payment-option-card');
        if (parent) {
            parent.style.borderColor = '#C5A059';
            parent.style.background = '#FFFDF9';
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    try {
        const savedUserStr = localStorage.getItem('auraluxe_user');
        const savedUser = savedUserStr ? JSON.parse(savedUserStr) : null;
        
        const nameInput = document.getElementById('receiver_name');
        if (nameInput && !nameInput.value.trim() && savedUser && savedUser.name) {
            nameInput.value = savedUser.name;
        }
        const phoneInput = document.getElementById('phone_number');
        if (phoneInput && (!phoneInput.value.trim() || phoneInput.value === '0334808383') && savedUser && savedUser.phone) {
            phoneInput.value = savedUser.phone;
        }

        // Tự động đồng bộ session Laravel nếu người dùng đã đăng nhập ở trang chủ
        @if(Auth::guest())
        if (savedUser && savedUser.email) {
            const isWebgiadung = window.location.pathname.includes('/webgiadung');
            const syncLoginUrl = isWebgiadung ? '/webgiadung/api/auth/sync-login' : '/api/auth/sync-login';
            fetch(syncLoginUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ email: savedUser.email, password: 'guest_sync' })
            }).catch(e => console.log('Auth sync notice:', e));
        }
        @endif
    } catch(e) {}

    // Tự động điền thông tin người dùng từ localStorage nếu form đang trống
    const recNameInput = document.getElementById('receiver_name');
    const phoneInput = document.getElementById('phone_number');
    try {
        const savedUserStr = localStorage.getItem('auraluxe_user') || localStorage.getItem('family_user');
        if (savedUserStr) {
            const u = JSON.parse(savedUserStr);
            if (u && u.name && recNameInput && !recNameInput.value) {
                recNameInput.value = u.name;
            }
            if (u && u.phone && phoneInput && (!phoneInput.value || phoneInput.value === '0334808383')) {
                phoneInput.value = u.phone;
            }
        }
    } catch(err) {}
});

</script>
@endsection
