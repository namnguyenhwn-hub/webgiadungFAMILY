<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="only light">
    <meta name="supported-color-schemes" content="light">
    <meta name="darkreader-lock" content="true">
    <title>Xác thực tài khoản Email - FAMILY LUXE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root, html, body {
            color-scheme: only light !important;
            forced-color-adjust: none !important;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F9FAFB;
            color: #111827;
        }
        .verify-card {
            background: #FFFFFF;
            border: 1px solid rgba(184, 134, 11, 0.15);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(45, 36, 30, 0.06);
            max-width: 520px;
            width: 100%;
        }
        .icon-circle {
            width: 72px;
            height: 72px;
            background: rgba(184, 134, 11, 0.1);
            color: #B8860B;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-bottom: 20px;
        }
        .btn-gold {
            background: linear-gradient(135deg, #B8860B, #8B6508);
            border: none;
            color: #FFF;
            font-weight: 600;
            padding: 12px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #996F07, #705206);
            color: #FFF;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(184, 134, 11, 0.25);
        }
        .btn-outline-custom {
            border: 1px solid #D5C7B7;
            color: #6C5E53;
            border-radius: 12px;
            padding: 11px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .btn-outline-custom:hover {
            background-color: #F5EFEB;
            color: #2D241E;
            border-color: #B8860B;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100 p-3">

<div class="card verify-card p-4 p-md-5">
    <div class="card-body text-center p-0">
        <div class="icon-circle">
            <i class="bi bi-envelope-check"></i>
        </div>

        <h3 class="card-title fw-bold mb-3">Xác thực địa chỉ Email của bạn</h3>
        
        <p class="text-muted mb-4" style="line-height: 1.6;">
            Cảm ơn bạn đã đăng ký! Trước khi bắt đầu trải nghiệm, bạn vui lòng kiểm tra hộp thư đến (hoặc hòm thư rác/Spam) của 
            <strong>{{ Auth::user()->email ?? 'email của bạn' }}</strong> để nhấp vào đường liên kết xác thực email.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success mb-4 rounded-3 text-start" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Một đường liên kết xác thực mới đã được gửi đến địa chỉ email bạn dùng khi đăng ký.
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success mb-4 rounded-3 text-start" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="d-grid gap-2">
            <!-- Form gửi lại email xác thực -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-gold w-100 mb-2">
                    <i class="bi bi-send me-1"></i> Gửi lại email xác thực
                </button>
            </form>

            <!-- Nút về Trang Chủ tạm thời / Hoặc Đăng xuất -->
            <div class="row g-2">
                <div class="col-6">
                    <a href="{{ route('welcome') }}" class="btn btn-outline-custom w-100">
                        <i class="bi bi-house me-1"></i> Trang chủ
                    </a>
                </div>
                <div class="col-6">
                    <!-- Nút Đăng xuất -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-custom w-100 text-danger">
                            <i class="bi bi-box-arrow-right me-1"></i> Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
