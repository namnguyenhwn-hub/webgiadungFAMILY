<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SePay Payment Gateway Configuration
    |--------------------------------------------------------------------------
    | Cấu hình cổng thanh toán ngân hàng tự động SePay (sepay.vn)
    */

    // Khóa API từ SePay Dashboard (my.sepay.vn -> Tích hợp web -> API & Webhooks)
    'api_key' => env('SEPAY_API_KEY', ''),

    // Tên ngân hàng nhận tiền (MBBank, Vietcombank, TPBank, BIDV, VPBank, ACB, Techcombank,...)
    'bank_name' => env('SEPAY_BANK_NAME', 'MBBank'),

    // Số tài khoản ngân hàng liên kết trong SePay
    'account_no' => env('SEPAY_ACCOUNT_NO', '0988888888'),

    // Tên chủ tài khoản
    'account_name' => env('SEPAY_ACCOUNT_NAME', 'FAMILY LUXE'),

    // Tiền tố nội dung chuyển khoản (VD: DH102 để SePay tự động bắt mã đơn hàng)
    'pattern' => env('SEPAY_PATTERN', 'DH'),

    // Mẫu hiển thị QR: 'compact' (nhỏ gọn), 'compact2', hoặc 'qr_only'
    'qr_template' => env('SEPAY_QR_TEMPLATE', 'compact2'),
];
