@extends('layouts.app') 

@section('title', 'Đơn hàng của bạn - FAMILY LUXE') 

@section('content') 
@php 
    $prefix = request()->is('webgiadung*') ? '/webgiadung' : ''; 
@endphp
<div class="container my-5" style="max-width: 1100px; padding: 40px 20px;"> 
    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom" style="border-color: #EAE2D5 !important;">
        <h2 class="fw-bold mb-0" style="color: #1C1917; font-family: 'Playfair Display', serif;">
            <i class="fa-solid fa-receipt" style="color: #C5A059; margin-right: 10px;"></i>Đơn Hàng Của Bạn
        </h2>
        <a href="{{ $prefix . route('welcome', [], false) }}" class="btn btn-sm" style="border: 1px solid #EAE2D5; background: #FFFFFF; color: #574F47; border-radius: 9999px; padding: 8px 18px; font-weight: 600; text-decoration: none;">
            <i class="fa-solid fa-arrow-left me-1"></i> Tiếp tục mua sắm
        </a>
    </div>

    @if(session('success')) 
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="background: #F0FDF4; border: 1px solid #BBF7D0; color: #166534; border-radius: 12px;"> 
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }} 
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> 
        </div> 
    @endif 

    @if(session('error')) 
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; border-radius: 12px;"> 
            <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }} 
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> 
        </div> 
    @endif 

    @if(isset($orders) && count($orders) > 0) 
        <div class="card shadow-sm border-0" style="border-radius: 20px; overflow: hidden; background: #FFFFFF; border: 1px solid #EAE2D5 !important;">
            <div class="table-responsive"> 
                <table class="table align-middle mb-0" style="border-collapse: separate;"> 
                    <thead style="background: #F5EFE6; color: #1C1917;"> 
                        <tr> 
                            <th class="ps-4 py-3" style="font-weight: 700;">Mã đơn</th> 
                            <th class="py-3" style="font-weight: 700;">Sản phẩm</th> 
                            <th class="py-3" style="font-weight: 700;">Tổng tiền</th> 
                            <th class="py-3" style="font-weight: 700;">Phương thức</th> 
                            <th class="py-3" style="font-weight: 700;">Trạng thái</th> 
                            <th class="py-3" style="font-weight: 700;">Ngày tạo</th> 
                        </tr> 
                    </thead> 
                    <tbody> 
                        @foreach($orders as $order) 
                            <tr style="border-bottom: 1px solid #F2ECE1;"> 
                                <td class="ps-4 py-3 fw-bold" style="color: #1C1917;">
                                    #{{ $order->id }}
                                    @if(!empty($order->order_code))
                                        <br><small style="color: #78716C; font-weight: normal;">{{ $order->order_code }}</small>
                                    @endif
                                </td> 
                                <td class="py-3">
                                    @if($order->items && count($order->items) > 0)
                                        <ul class="list-unstyled mb-0" style="font-size: 0.875rem;">
                                            @foreach($order->items as $item)
                                                <li class="mb-1" style="color: #44403C;">
                                                    <i class="fa-solid fa-box text-muted me-1"></i>
                                                    <strong>{{ $item->product ? $item->product->name : ($item->product_name ?? 'Sản phẩm') }}</strong>
                                                    <span style="color: #78716C;">x {{ $item->quantity }}</span>
                                                    <span style="color: #B8934A;">({{ number_format($item->price, 0, ',', '.') }} đ)</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span style="color: #78716C;">{{ $order->product_name ?? 'Đơn hàng tiêu chuẩn' }}</span>
                                    @endif
                                </td>
                                <td class="fw-bold" style="color: #B8934A; font-size: 1rem;">
                                    {{ number_format($order->total ?: $order->amount, 0, ',', '.') }} đ
                                </td> 
                                <td> 
                                    @if(strtoupper($order->payment_method) === 'COD')
                                        <span class="badge" style="background: #F1F5F9; color: #475569; border: 1px solid #CBD5E1; padding: 6px 12px; border-radius: 9999px;">
                                            <i class="fa-solid fa-truck me-1"></i>COD
                                        </span>
                                    @elseif(str_contains(strtolower($order->payment_method), 'bidv'))
                                        <span class="badge" style="background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; padding: 6px 12px; border-radius: 9999px;">
                                            <i class="fa-solid fa-qrcode me-1"></i>VietQR BIDV
                                        </span>
                                    @else
                                        <span class="badge" style="background: #FAF5EB; color: #916F29; border: 1px solid #DFC591; padding: 6px 12px; border-radius: 9999px;">
                                            <i class="fa-solid fa-credit-card me-1"></i>{{ $order->payment_method }}
                                        </span>
                                    @endif
                                </td> 
                                <td> 
                                    @php
                                        $status = strtolower($order->status);
                                    @endphp
                                    @if($status === 'paid' || str_contains($status, 'đã thanh toán') || str_contains($status, 'hoàn thành'))
                                        <span class="badge" style="background: #F0FDF4; color: #166534; border: 1px solid #BBF7D0; padding: 6px 12px; border-radius: 9999px;">
                                            <i class="fa-solid fa-check me-1"></i>{{ ucfirst($order->status) }}
                                        </span>
                                    @elseif($status === 'cancelled' || str_contains($status, 'hủy') || str_contains($status, 'không thành công'))
                                        <span class="badge" style="background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; padding: 6px 12px; border-radius: 9999px;">
                                            <i class="fa-solid fa-xmark me-1"></i>{{ ucfirst($order->status) }}
                                        </span>
                                    @else
                                        <span class="badge" style="background: #FEFCE8; color: #854D0E; border: 1px solid #FEF08A; padding: 6px 12px; border-radius: 9999px;">
                                            <i class="fa-solid fa-clock me-1"></i>{{ ucfirst($order->status) }}
                                        </span>
                                    @endif
                                </td> 
                                <td style="color: #78716C; font-size: 0.85rem;">
                                    {{ $order->created_at ? $order->created_at->format('d/m/Y') : 'Mới tạo' }}
                                </td> 
                            </tr> 
                        @endforeach 
                    </tbody> 
                </table> 
            </div> 
        </div>
    @else 
        <div class="text-center py-5 rounded border-0 shadow-sm" style="background: #FFFFFF; border-radius: 20px; border: 1px solid #EAE2D5 !important; padding: 60px 20px;"> 
            <i class="fa-regular fa-folder-open mb-3" style="font-size: 3.5rem; color: #DFC591;"></i>
            <h4 class="fw-bold" style="color: #1C1917;">Bạn chưa có đơn hàng nào</h4> 
            <p class="mb-4" style="color: #78716C;">Các đơn hàng sau khi đặt thành công sẽ được lưu trữ và theo dõi chi tiết tại đây.</p> 
            <a href="{{ $prefix . route('welcome', [], false) }}" class="btn py-2 px-4 fw-bold" style="background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; border-radius: 9999px; text-decoration: none;">
                <i class="fa-solid fa-gem me-1"></i> Mua sắm ngay
            </a> 
        </div> 
    @endif 
@endsection

@push('scripts')
<script>
    try {
        localStorage.removeItem('aura_cart');
        const badge = document.getElementById('navCartBadge');
        if (badge) badge.style.display = 'none';
    } catch(e) {}
</script>
@endpush
