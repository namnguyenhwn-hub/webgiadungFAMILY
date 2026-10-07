@extends('layouts.app')
@section('title', 'Đăng nhập tài khoản - FAMILY')

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-md-7 col-lg-5 col-xl-4" style="min-width: 360px; max-width: 460px;">
        <div class="card border-0 shadow-sm" style="border-radius: 24px; background: #FFFFFF; border: 1px solid #EAE2D5 !important; overflow: hidden; box-shadow: 0 16px 40px rgba(197, 160, 89, 0.12), 0 4px 12px rgba(0,0,0,0.03) !important;">
            
            <div class="card-body p-4 p-sm-5">
                <!-- Brand Crest / Header -->
                <div class="text-center mb-4">
                    <div style="width: 58px; height: 58px; border-radius: 50%; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; font-size: 1.5rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 18px rgba(197, 160, 89, 0.32); margin: 0 auto 14px;">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <h2 class="font-serif fw-bold text-dark mb-1" style="font-size: 1.65rem; letter-spacing: -0.01em;">Đăng nhập tài khoản</h2>
                    <p class="text-muted small mb-0">Hệ thống thiết bị gia dụng cao cấp <strong class="text-gold-gradient font-serif">FAMILY</strong></p>
                </div>

                {{-- Hứng thông báo đăng ký thành công từ hàm register chuyển sang --}}
                @if (session('success'))
                <div class="alert border-0 shadow-sm d-flex align-items-center gap-2 mb-3" style="background: #F0FDF4; border: 1px solid #BBF7D0 !important; color: #166534; border-radius: 12px; font-size: 0.875rem;">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div>{{ session('success') }}</div>
                </div>
                @endif

                {{-- Hứng thông báo lỗi chung (nếu có) --}}
                @if (session('error'))
                <div class="alert border-0 shadow-sm d-flex align-items-center gap-2 mb-3" style="background: #FEF2F2; border: 1px solid #FECACA !important; color: #991B1B; border-radius: 12px; font-size: 0.875rem;">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <div>{{ session('error') }}</div>
                </div>
                @endif

                @php
                    $prefix = request()->is('webgiadung*') ? '/webgiadung' : '';
                @endphp

                <form action="{{ $prefix }}{{ route('login.post', [], false) }}" method="POST">
                    @csrf
                    @if(request('redirect'))
                    <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                    @endif

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold text-dark small mb-1.5 d-flex align-items-center gap-1.5">
                            <i class="bi bi-envelope text-muted"></i> Email
                        </label>
                        {{-- Dùng old('email') để giữ lại email nếu nhập sai mật khẩu --}}
                        <input type="email" name="email" id="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" 
                               placeholder="name@example.com" 
                               style="padding: 12px 16px; border: 1.5px solid #E2E8F0; border-radius: 12px; font-size: 0.9375rem; transition: all 0.2s;"
                               onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.2)';"
                               onblur="this.style.borderColor='#E2E8F0'; this.style.boxShadow='none';"
                               required>
                        {{-- Hiển thị lỗi sai email ngay dưới ô input --}}
                        @error('email')
                        <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold text-dark small mb-1.5 d-flex align-items-center gap-1.5">
                            <i class="bi bi-lock text-muted"></i> Mật khẩu
                        </label>
                        <div class="position-relative">
                            <input type="password" name="password" id="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="••••••••" 
                                   style="padding: 12px 44px 12px 16px; border: 1.5px solid #E2E8F0; border-radius: 12px; font-size: 0.9375rem; transition: all 0.2s;"
                                   onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.2)';"
                                   onblur="this.style.borderColor='#E2E8F0'; this.style.boxShadow='none';"
                                   required>
                            <button type="button" class="btn border-0 position-absolute end-0 top-50 translate-middle-y text-muted px-3" 
                                    tabindex="-1"
                                    onclick="togglePassword()" style="background: none;">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        {{-- Hiển thị lỗi sai password ngay dưới ô input --}}
                        @error('password')
                        <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-gold w-100 py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2" style="font-size: 1rem; border-radius: 9999px;">
                        <span>Đăng nhập</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>

                    <div class="text-center mt-4 pt-3 border-top small" style="border-color: #F1F5F9 !important; color: #6B7280;">
                        Chưa có tài khoản? 
                        <a href="{{ $prefix }}{{ route('register', [], false) }}" class="fw-bold" style="color: #9A7B38; text-decoration: none;">Đăng ký ngay</a>
                    </div>

                    <div class="text-center mt-2">
                        <a href="{{ $prefix }}/" class="text-muted small text-decoration-none d-inline-flex align-items-center gap-1" style="font-size: 0.8125rem;">
                            <i class="bi bi-chevron-left"></i> Quay lại trang chủ
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>
@endsection
