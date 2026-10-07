@extends('layouts.app')

@section('title', 'Thanh toán chuyển khoản ngân hàng tự động (SePay) - FAMILY LUXE')

@section('content')
@php 
    $prefix = request()->is('webgiadung*') ? '/webgiadung' : ''; 
@endphp
<div class="container my-5" style="max-width: 860px; padding: 20px 15px;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 p-4 p-md-5" style="border-radius: 24px; background: #FFFFFF; border: 1px solid #E5E7EB !important; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05) !important;">
                
                <!-- Header -->
                <div class="text-center mb-4">
                    <h2 class="fw-bold mb-1" style="color: #111827; font-size: 1.85rem;">Quét mã QR chuyển khoản</h2>
                </div>

                <div class="row g-4 align-items-center mb-4">
                    <!-- Left: QR Code Box -->
                    <div class="col-md-5 text-center">
                        <div class="p-3 d-inline-block rounded-4 position-relative" style="background: #FFFFFF; border: 2px solid #E5E7EB; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);">
                            <img id="sepayQrImage" src="{{ $sepayQrUrl }}" 
                                 onerror="this.onerror=null; this.src='{{ $vietQrUrl }}';" 
                                 alt="SePay VietQR Payment" 
                                 class="img-fluid rounded" 
                                 style="max-width: 250px; width: 100%; display: block;">
                            
                            <div class="mt-2 pt-2 border-top text-center" style="border-color: #F3F4F6 !important;">
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    <i class="fa-solid fa-qrcode me-1 text-primary"></i> Quét được bằng mọi app ngân hàng
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Bank Transfer Details with 1-click Copy -->
                    <div class="col-md-7">
                        <div class="card p-3 border-0" style="background: #F9FAFB; border-radius: 18px; border: 1px solid #E5E7EB !important; font-size: 0.9rem;">
                            <!-- Ngân hàng -->
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="border-color: #E5E7EB !important;">
                                <span style="color: #6B7280;"><i class="fa-solid fa-building-columns me-2 text-primary"></i>Ngân hàng:</span>
                                <strong style="color: #111827;">{{ $bankName }}</strong>
                            </div>

                            <!-- Số tài khoản -->
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="border-color: #E5E7EB !important;">
                                <span style="color: #6B7280;"><i class="fa-regular fa-credit-card me-2 text-info"></i>Số tài khoản:</span>
                                <div class="d-flex align-items-center gap-2">
                                    <strong style="color: #1D4ED8; font-size: 1.1rem; letter-spacing: 0.5px;">{{ $accountNo }}</strong>
                                    <button type="button" class="btn btn-sm btn-light border py-0 px-2" onclick="copyText('{{ $accountNo }}', 'số tài khoản')" title="Sao chép">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Chủ tài khoản -->
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="border-color: #E5E7EB !important;">
                                <span style="color: #6B7280;"><i class="fa-regular fa-user me-2 text-secondary"></i>Chủ tài khoản:</span>
                                <strong style="color: #111827; text-transform: uppercase;">{{ $accountName }}</strong>
                            </div>

                            <!-- Số tiền -->
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="border-color: #E5E7EB !important;">
                                <span style="color: #6B7280;"><i class="fa-solid fa-money-bill-wave me-2 text-success"></i>Số tiền:</span>
                                <div class="d-flex align-items-center gap-2">
                                    <strong style="color: #B8934A; font-size: 1.05rem;">{{ number_format($amount, 0, ',', '.') }} đ</strong>
                                    <button type="button" class="btn btn-sm btn-light border py-0 px-2" onclick="copyText('{{ $amount }}', 'số tiền')" title="Sao chép">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Cú pháp chuyển khoản BẮT BUỘC -->
                            <div class="d-flex justify-content-between align-items-center py-2" style="background: #FEF3C7; margin: 6px -6px -6px; padding: 10px !important; border-radius: 12px; border: 1px solid #FDE68A;">
                                <div>
                                    <span class="d-block fw-bold" style="color: #92400E; font-size: 0.8rem;">
                                        <i class="fa-solid fa-circle-exclamation me-1"></i>Nội dung chuyển khoản (bắt buộc):
                                    </span>
                                    <strong class="fs-5" style="color: #B45309; letter-spacing: 1px;">{{ $transferContent }}</strong>
                                </div>
                                <button type="button" class="btn btn-sm btn-warning text-dark fw-bold py-1 px-3" onclick="copyText('{{ $transferContent }}', 'nội dung chuyển khoản')" title="Sao chép nội dung">
                                    <i class="fa-regular fa-copy me-1"></i> Copy
                                </button>
                            </div>
                        </div>

                        <div id="waitingStatusBox" style="display: none;"></div>
                    </div>
                </div>



                <!-- ================================================================= -->
                <!-- KHU VỰC GIẢ LẬP THANH TOÁN (SEPAY SANDBOX SIMULATOR)              -->
                <!-- ================================================================= -->
                <div class="p-3 mb-4 rounded-4" style="background: #FFFBEB; border: 1px dashed #F59E0B;">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <span class="badge bg-warning text-dark mb-1">
                                <i class="fa-solid fa-flask me-1"></i> Chế độ kiểm thử & giả lập
                            </span>
                            <div class="fw-bold" style="color: #92400E; font-size: 0.95rem;">
                                Bạn muốn thử nghiệm nhận tiền ngay mà không cần chuyển tiền thật?
                            </div>
                            <small class="text-muted">Bấm nút bên cạnh để kích hoạt webhook giả lập SePay, thử âm thanh Ting Ting và xem thông báo thành công.</small>
                        </div>
                        <button type="button" id="btnSimulatePayment" class="btn btn-warning fw-bold px-4 py-2" onclick="simulateSepayPayment()" style="border-radius: 9999px; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);">
                            <i class="fa-solid fa-bolt me-1"></i> Giả lập khách đã chuyển tiền
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2 border-top" style="border-color: #E5E7EB !important;">
                    <a href="{{ route('cart.index') }}" class="btn py-2 px-3" style="border: 1px solid #E5E7EB; background: #FFFFFF; color: #4B5563; border-radius: 9999px; font-weight: 600; text-decoration: none;">
                        &laquo; Quay lại giỏ hàng
                    </a>
                    
                    <button type="button" class="btn btn-outline-secondary py-2 px-3 small" onclick="toggleSetupModal()" style="border-radius: 9999px;">
                        <i class="fa-solid fa-gear me-1"></i> Hướng dẫn liên kết tài khoản SePay
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ================================================================= -->
<!-- MODAL THÔNG BÁO NHẬN TIỀN THÀNH CÔNG RÕ RÀNG (CONFIRMATION MODAL) -->
<!-- ================================================================= -->
<div id="paymentSuccessModal" class="custom-modal-backdrop" style="display: none;">
    <div class="custom-modal-card text-center">
        <!-- Animated Success Icon -->
        <div class="success-icon-wrap mb-3">
            <i class="fa-solid fa-circle-check text-success" style="font-size: 4rem;"></i>
        </div>

        <span class="badge px-3 py-2 mb-2" style="background: #DCFCE7; color: #15803D; font-weight: 700; border-radius: 9999px;">
            <i class="fa-solid fa-circle-check me-1"></i> Giao dịch thành công
        </span>

        <h2 class="fw-bold mb-2" style="color: #111827;">Đã nhận tiền thành công!</h2>
        <p class="text-muted mb-4" style="font-size: 0.95rem;">
            Cảm ơn quý khách! Hệ thống SePay đã ghi nhận số tiền thanh toán cho đơn hàng <strong>#{{ $order->id }}</strong>.
        </p>

        <!-- Transaction Details Card -->
        <div class="p-3 rounded-3 text-start mb-4" style="background: #F9FAFB; border: 1px solid #E5E7EB; font-size: 0.875rem;">
            <div class="d-flex justify-content-between py-1 border-bottom" style="border-color: #F3F4F6 !important;">
                <span class="text-muted">Số tiền đã nhận:</span>
                <strong class="text-success fs-6" id="modalPaidAmount">{{ number_format($amount, 0, ',', '.') }} ₫</strong>
            </div>
            <div class="d-flex justify-content-between py-1 border-bottom" style="border-color: #F3F4F6 !important;">
                <span class="text-muted">Mã giao dịch SePay:</span>
                <strong class="text-primary" id="modalTransactionId">---</strong>
            </div>
            <div class="d-flex justify-content-between py-1 border-bottom" style="border-color: #F3F4F6 !important;">
                <span class="text-muted">Ngân hàng thụ hưởng:</span>
                <strong class="text-dark">{{ $bankName }}</strong>
            </div>
            <div class="d-flex justify-content-between py-1">
                <span class="text-muted">Thời gian nhận:</span>
                <strong class="text-dark" id="modalPaidTime">Vừa xong</strong>
            </div>
        </div>

        <!-- Buttons -->
        <div class="d-grid gap-2">
            <a href="{{ $prefix . route('orders.index', [], false) }}" class="btn py-3 fw-bold text-white" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); border-radius: 9999px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.35);">
                <i class="fa-solid fa-receipt me-1"></i> Xem chi tiết đơn hàng của bạn
            </a>
            <a href="{{ $prefix . route('welcome', [], false) }}" class="btn btn-light py-2 text-muted" style="border-radius: 9999px;">
                Về trang chủ FAMILY LUXE
            </a>
        </div>
    </div>
</div>

<!-- ================================================================= -->
<!-- MODAL HƯỚNG DẪN LIÊN KẾT TÀI KHOẢN SEPAY                          -->
<!-- ================================================================= -->
<div id="sepaySetupModal" class="custom-modal-backdrop" style="display: none;">
    <div class="custom-modal-card text-start" style="max-width: 600px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0 text-dark">
                <i class="fa-solid fa-link text-primary me-2"></i>Hướng dẫn liên kết SePay vào website
            </h5>
            <button type="button" class="btn-close" onclick="toggleSetupModal()"></button>
        </div>

        <div class="alert alert-warning small p-2 mb-3" style="font-size: 0.8rem; border-radius: 10px;">
            <i class="fa-solid fa-triangle-exclamation me-1"></i>
            <strong>Lưu ý về lỗi "Tên miền phải phân giải được DNS và không được là IP Private":</strong>
            <br>SePay nằm trên máy chủ Internet nên <strong>không thể gửi Webhook trực tiếp vào địa chỉ <code>localhost</code> hoặc <code>127.0.0.1</code></strong> trên máy tính của bạn.
        </div>

        <h6 class="fw-bold text-dark mb-2" style="font-size: 0.9rem;">
            <i class="fa-solid fa-star text-warning me-1"></i> Giải pháp 1: Dùng API Token (Khuyên dùng - Không cần tạo Webhook)
        </h6>
        <p class="small text-muted mb-2">
            Bạn <strong>không cần thêm Webhook trên SePay</strong> nữa! Chỉ cần lấy <strong>API Token</strong>:
        </p>
        <ol class="small text-muted mb-3" style="line-height: 1.6;">
            <li>Vào <a href="https://my.sepay.vn" target="_blank" class="fw-bold text-primary">my.sepay.vn</a> &rarr; <strong>"Tích hợp web"</strong> &rarr; <strong>"API & Webhooks"</strong>.</li>
            <li>Sao chép <strong>API Token</strong> của bạn.</li>
            <li>Dán vào file <code>.env</code> trong thư mục website:
                <pre class="bg-dark text-white p-2 rounded mt-1 mb-0" style="font-size: 0.75rem;">SEPAY_API_KEY=ma_api_token_tu_sepay
SEPAY_BANK_NAME={{ $bankName }}
SEPAY_ACCOUNT_NO={{ $accountNo }}
SEPAY_ACCOUNT_NAME="{{ $accountName }}"</pre>
            </li>
            <li>Website sẽ <strong>tự động gọi API SePay kiểm tra tiền về và Ting Ting ngay trên localhost</strong>!</li>
        </ol>

        <h6 class="fw-bold text-dark mb-2" style="font-size: 0.9rem;">
            <i class="fa-solid fa-globe text-primary me-1"></i> Giải pháp 2: Dùng Cloudflare Tunnel / Ngrok (Nếu muốn dùng Webhook)
        </h6>
        <p class="small text-muted mb-3" style="line-height: 1.6;">
            Mở terminal chạy <code>cloudflared tunnel --url http://localhost:80</code> (hoặc <code>ngrok http 80</code>).
            <br>Lấy đường link công khai (ví dụ <code>https://ten-ngau-nhien.trycloudflare.com</code>) rồi điền vào SePay Webhook URL:
            <code class="d-block p-1 bg-light border rounded mt-1 text-primary">https://ten-cua-ban.trycloudflare.com/api/sepay/webhook</code>
        </p>

        <div class="text-end">
            <button type="button" class="btn btn-secondary btn-sm px-4" onclick="toggleSetupModal()">Đóng</button>
        </div>
    </div>
</div>

<!-- Audio for Ting Ting Sound Effect via Web Audio API -->
<script>
    try {
        localStorage.removeItem('aura_cart');
    } catch(e) {}

    // 1. Hàm tạo âm thanh "Ting Ting" nhận tiền chuyển khoản bằng Web Audio Synthesizer
    function playCashRegisterSound() {
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            const ctx = new AudioContext();

            // Nốt 1 (High bell ding)
            const osc1 = ctx.createOscillator();
            const gain1 = ctx.createGain();
            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(987.77, ctx.currentTime); // B5
            osc1.frequency.exponentialRampToValueAtTime(1318.51, ctx.currentTime + 0.1); // E6
            gain1.gain.setValueAtTime(0.3, ctx.currentTime);
            gain1.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.8);
            osc1.connect(gain1);
            gain1.connect(ctx.destination);
            osc1.start();
            osc1.stop(ctx.currentTime + 0.8);

            // Nốt 2 (Higher chime)
            setTimeout(() => {
                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.type = 'triangle';
                osc2.frequency.setValueAtTime(1567.98, ctx.currentTime); // G6
                gain2.gain.setValueAtTime(0.4, ctx.currentTime);
                gain2.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 1.2);
                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.start();
                osc2.stop(ctx.currentTime + 1.2);
            }, 120);
        } catch (e) {
            console.log('Web Audio not supported or blocked:', e);
        }
    }

    // 2. Hàm Copy Text
    function copyText(text, label) {
        navigator.clipboard.writeText(text).then(() => {
            alert('✅ Đã sao chép ' + label + ': ' + text);
        }).catch(err => {
            prompt('Nhấn Ctrl+C để copy ' + label + ':', text);
        });
    }

    // 3. Modal Controls
    function toggleSetupModal() {
        const modal = document.getElementById('sepaySetupModal');
        modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
    }

    function showPaymentSuccess(data) {
        // Phát âm thanh nhận tiền Ting Ting
        playCashRegisterSound();

        // Cập nhật thông tin Modal
        if (data.transaction_id) {
            document.getElementById('modalTransactionId').innerText = '#' + data.transaction_id;
        }
        if (data.paid_at) {
            document.getElementById('modalPaidTime').innerText = data.paid_at;
        }
        if (data.amount) {
            document.getElementById('modalPaidAmount').innerText = new Intl.NumberFormat('vi-VN').format(data.amount) + ' ₫';
        }

        // Đổi trạng thái box chờ thành "Đã nhận tiền"
        const waitingBox = document.getElementById('waitingStatusBox');
        if (waitingBox) {
            waitingBox.className = 'mt-3 p-3 text-center rounded-3 bg-success text-white shadow-sm';
            waitingBox.innerHTML = '<i class="fa-solid fa-check-circle me-1"></i> <strong>ĐÃ NHẬN TIỀN THÀNH CÔNG QUA SEPAY!</strong>';
        }

        // Hiển thị Modal popup chúc mừng
        const successModal = document.getElementById('paymentSuccessModal');
        successModal.style.display = 'flex';

        // Tự động bắn pháo hoa confetti nếu có thư viện
        if (typeof confetti === 'function') {
            confetti({ particleCount: 100, spread: 70, origin: { y: 0.6 } });
        }
    }

    // 4. BỘ GIẢ LẬP THANH TOÁN (SEPAY SANDBOX SIMULATOR)
    function simulateSepayPayment() {
        const btn = document.getElementById('btnSimulatePayment');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Đang giả lập SePay...';

        fetch('{{ $prefix . route("sepay.simulate", $order->id, false) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-bolt me-1"></i> Giả Lập Khách Đã Chuyển Tiền';
            if (res.success) {
                showPaymentSuccess(res.data);
            } else {
                alert('Có lỗi khi giả lập: ' + (res.message || 'Không rõ'));
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-bolt me-1"></i> Giả Lập Khách Đã Chuyển Tiền';
            alert('Lỗi kết nối máy chủ giả lập: ' + err);
        });
    }

    // 5. Hàm lưu API Key trực tiếp từ giao diện
    function saveSepayApiKey() {
        const input = document.getElementById('sepayApiKeyInput');
        const btn = document.getElementById('btnSaveApiKey');
        const key = input ? input.value.trim() : '';

        if (!key) {
            alert('Vui lòng dán SePay API Token vào ô trước khi bấm Kích hoạt!');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Đang kết nối SePay...';

        fetch('{{ $prefix . route("sepay.saveKey", [], false) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ api_key: key })
        })
        .then(res => res.json())
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-check me-1"></i> Kích Hoạt Tự Động';

            if (res.success) {
                alert('🎉 ' + res.message + '\nHệ thống đang quét giao dịch chuyển khoản...');
                const promptBox = document.getElementById('sepayApiKeyPromptBox');
                if (promptBox) promptBox.style.display = 'none';
                checkStatusNow();
            } else {
                alert('❌ ' + (res.message || 'Không thể lưu API Key'));
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-check me-1"></i> Kích Hoạt Tự Động';
            alert('Lỗi kết nối máy chủ: ' + err);
        });
    }

    // 6. Hàm Quét trạng thái thanh toán ngay lập tức
    function checkStatusNow() {
        fetch('{{ $prefix . route("sepay.checkStatus", $order->id, false) }}')
            .then(res => res.json())
            .then(res => {
                if (res.is_paid) {
                    clearInterval(pollingInterval);
                    showPaymentSuccess(res);
                }
            })
            .catch(err => console.log('Check status error:', err));
    }

    // 7. AJAX Polling kiểm tra trạng thái thanh toán Realtime 2s/lần
    let pollingInterval = setInterval(() => {
        fetch('{{ $prefix . route("sepay.checkStatus", $order->id, false) }}')
            .then(res => res.json())
            .then(res => {
                if (res.is_paid) {
                    clearInterval(pollingInterval);
                    showPaymentSuccess(res);
                }
            })
            .catch(err => console.log('Polling check error:', err));
    }, 2000);
</script>

<style>
    .pulse-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #22C55E;
        display: inline-block;
        animation: pulseAnimation 1.5s infinite;
    }
    @keyframes pulseAnimation {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }

    /* Modal Backdrop */
    .custom-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(17, 24, 39, 0.65);
        backdrop-filter: blur(6px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        padding: 20px;
        animation: fadeInModal 0.25s ease-out;
    }

    .custom-modal-card {
        background: #FFFFFF;
        border-radius: 24px;
        max-width: 480px;
        width: 100%;
        padding: 36px 28px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 1px solid #E5E7EB;
        animation: scaleUpModal 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes fadeInModal {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes scaleUpModal {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
</style>
@endsection
