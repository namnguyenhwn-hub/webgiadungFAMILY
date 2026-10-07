@extends('layouts.app') 

@section('title', 'Thanh Toán Chuyển Khoản Ngân Hàng VietQR (BIDV) - FAMILY LUXE') 

@section('content') 
@php 
    $prefix = request()->is('webgiadung*') ? '/webgiadung' : ''; 
@endphp
<div class="container my-5" style="max-width: 800px; padding: 40px 20px;"> 
    <div class="row justify-content-center"> 
        <div class="col-md-9 text-center"> 
            <div class="card shadow-sm border-0 p-4 p-md-5 mx-auto" style="border-radius: 24px; background: #FFFFFF; border: 1px solid #EAE2D5 !important;"> 
                
                <!-- BIDV Header Logo Badge -->
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 mx-auto" style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 9999px;">
                    <i class="fa-solid fa-building-columns text-primary"></i>
                    <span style="font-size: 0.85rem; font-weight: 700; color: #1D4ED8;">CỔNG THANH TOÁN VIETQR - NGÂN HÀNG BIDV</span>
                </div>

                <h3 class="fw-bold mb-2" style="color: #1C1917; font-family: 'Playfair Display', serif;">
                    Quét Mã QR Thanh Toán
                </h3> 
                <p class="mb-3" style="color: #78716C; font-size: 0.95rem;">
                    Mở ứng dụng <strong>SmartBanking BIDV</strong> hoặc bất kỳ App ngân hàng nào hỗ trợ VietQR để quét mã
                </p> 

                <!-- Total Amount Banner -->
                <div class="p-3 mb-4 rounded" style="background: #F9FAFB; border: 1px dashed #DFC591;">
                    <span style="color: #6B7280; font-size: 0.9rem;">Số tiền cần thanh toán:</span>
                    <h2 class="fw-bold mb-0 mt-1" style="color: #B8934A; font-size: 2rem;">
                        {{ number_format($totalAmount, 0, ',', '.') }} đ
                    </h2> 
                </div>

                <!-- VietQR Image Box -->
                <div class="my-2 p-3 d-inline-block mx-auto rounded" style="background: #FFFFFF; border: 2px solid #E5E7EB; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);"> 
                    <img src="{{ $qrCodeUrl }}" alt="VietQR BIDV Payment QR" class="img-fluid rounded" style="max-width: 280px; width: 100%; display: block;"> 
                </div> 

                <!-- Bank Account Details Card -->
                <div class="card p-3 my-4 border-0 text-start" style="background: #F9FAFB; border-radius: 16px; border: 1px solid #E5E7EB !important; font-size: 0.9rem;">
                    <div class="d-flex justify-content-between py-2 border-bottom" style="border-color: #EAE2D5 !important;">
                        <span style="color: #78716C;">Ngân hàng:</span>
                        <strong style="color: #1C1917;">{{ $bankFullName ?? 'BIDV - TMCP Đầu tư & Phát triển VN' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="border-color: #EAE2D5 !important;">
                        <span style="color: #78716C;">Số tài khoản:</span>
                        <div class="d-flex align-items-center gap-2">
                            <strong style="color: #1D4ED8; font-size: 1.05rem;" id="stkValue">{{ $accountNo ?? '1234567890' }}</strong>
                            <button type="button" class="btn btn-sm btn-light border py-0 px-2" onclick="navigator.clipboard.writeText('{{ $accountNo ?? '' }}'); alert('Đã sao chép số tài khoản: {{ $accountNo ?? '' }}');" title="Sao chép STK">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom" style="border-color: #EAE2D5 !important;">
                        <span style="color: #78716C;">Chủ tài khoản:</span>
                        <strong style="color: #1C1917; text-transform: uppercase;">{{ $accountName ?? 'NGUYEN VAN A' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span style="color: #78716C;">Nội dung chuyển khoản:</span>
                        <div class="d-flex align-items-center gap-2">
                            <strong style="color: #B8934A;">{{ $orderInfo ?? 'Thanh toan don hang' }}</strong>
                            <button type="button" class="btn btn-sm btn-light border py-0 px-2" onclick="navigator.clipboard.writeText('{{ $orderInfo ?? 'Thanh toan don hang' }}'); alert('Đã sao chép nội dung: {{ $orderInfo ?? 'Thanh toan don hang' }}');" title="Sao chép nội dung">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-3 mt-2"> 
                    <a href="{{ $prefix . route('cart.index', [], false) }}" class="btn py-2 px-3" style="border: 1px solid #EAE2D5; background: #FFFFFF; color: #574F47; border-radius: 9999px; font-weight: 600; text-decoration: none;">
                        &laquo; Quay lại giỏ hàng
                    </a> 
                    <a href="{{ $prefix . route('orders.index', [], false) }}" class="btn py-2 px-4 fw-bold" style="background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; border: none; border-radius: 9999px; text-decoration: none; box-shadow: 0 6px 20px rgba(197, 160, 89, 0.3);">
                        <i class="fa-solid fa-receipt me-1"></i> Hoàn tất / Xem đơn hàng
                    </a> 
                </div> 

                <small class="mt-4 text-muted d-block" style="font-size: 0.8rem;">
                    <i class="fa-solid fa-lock text-success me-1"></i>Hệ thống tự động liên kết VietQR chuẩn NAPAS 24/7. Bạn có thể thay đổi số tài khoản BIDV trong file <code>.env</code>.
                </small>
            </div> 
        </div> 
    </div> 
</div> 
@endsection
