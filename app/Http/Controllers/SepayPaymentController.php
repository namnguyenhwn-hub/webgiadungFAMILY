<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class SepayPaymentController extends Controller
{
    /**
     * 1. Hiển thị Trang Thanh Toán Quét Mã QR SePay
     */
    public function showPaymentPage($order_id)
    {
        $order = Order::findOrFail($order_id);

        $bankName = config('sepay.bank_name', 'MBBank');
        $accountNo = config('sepay.account_no', '0988888888');
        $accountName = config('sepay.account_name', 'FAMILY LUXE');
        $pattern = config('sepay.pattern', 'DH');
        $qrTemplate = config('sepay.qr_template', 'compact2');

        // Tạo cú pháp chuyển khoản chuẩn cho SePay nhận diện: ví dụ DH15 hoặc DH28
        $transferContent = $pattern . $order->id;
        $amount = (int) ($order->total > 0 ? $order->total : ($order->amount ?? 0));

        // 1. URL tạo mã QR trực tiếp từ Cổng SePay
        $sepayQrUrl = "https://qr.sepay.vn/img?bank={$bankName}&acc={$accountNo}&template={$qrTemplate}&amount={$amount}&des=" . urlencode($transferContent);

        // 2. URL dự phòng chuẩn VietQR NAPAS
        $vietQrUrl = "https://img.vietqr.io/image/{$bankName}-{$accountNo}-compact2.png?amount={$amount}&addInfo=" . urlencode($transferContent) . "&accountName=" . urlencode($accountName);

        // Webhook URL để hiển thị cho chủ web copy vào SePay Dashboard
        $webhookUrl = url('/webgiadung/api/sepay/webhook');

        $hasApiKey = !empty(config('sepay.api_key') ?: env('SEPAY_API_KEY'));

        return view('checkout.sepay', compact(
            'order',
            'bankName',
            'accountNo',
            'accountName',
            'transferContent',
            'amount',
            'sepayQrUrl',
            'vietQrUrl',
            'webhookUrl',
            'hasApiKey'
        ));
    }

    /**
     * 2. API Kiểm tra trạng thái thanh toán Realtime (AJAX Polling)
     * Trình duyệt của khách hàng sẽ gọi định kỳ 2s/lần để phát hiện khi nhận được tiền
     */
    public function checkStatus($order_id)
    {
        $order = Order::find($order_id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng'
            ], 404);
        }

        $isPaid = in_array(strtolower($order->status ?? ''), ['completed', 'paid', 'đã thanh toán']) 
                  || in_array(strtolower($order->payment_status ?? ''), ['paid', 'completed']);

        $apiKey = config('sepay.api_key') ?: env('SEPAY_API_KEY');

        // Tự động kiểm tra trực tiếp qua SePay API (Hỗ trợ cả v2 và v1)
        if (!$isPaid && !empty($apiKey)) {
            try {
                $pattern = config('sepay.pattern', 'DH');
                $transferContent = $pattern . $order->id;
                $orderTotal = (int) ($order->total > 0 ? $order->total : ($order->amount ?? 0));

                $transactions = [];

                // 1. Thử gọi SePay API v2 (Bearer token)
                try {
                    $response = \Illuminate\Support\Facades\Http::timeout(4)
                        ->withToken($apiKey)
                        ->get('https://userapi.sepay.vn/v2/transactions', [
                            'per_page' => 25,
                        ]);

                    if ($response->successful()) {
                        $transactions = $response->json('data') ?? [];
                    }
                } catch (\Throwable $e2) {
                    Log::debug('SePay v2 call exception: ' . $e2->getMessage());
                }

                // 2. Dự phòng gọi SePay API v1 (Apikey token)
                if (empty($transactions)) {
                    try {
                        $responseV1 = \Illuminate\Support\Facades\Http::timeout(4)
                            ->withHeaders(['Authorization' => 'Apikey ' . $apiKey])
                            ->get('https://my.sepay.vn/userapi/transactions/list', [
                                'limit' => 25,
                            ]);
                        if ($responseV1->successful()) {
                            $transactions = $responseV1->json('transactions') ?? [];
                        }
                    } catch (\Throwable $e1) {
                        Log::debug('SePay v1 call exception: ' . $e1->getMessage());
                    }
                }

                // 3. Quét danh sách giao dịch
                foreach ($transactions as $tx) {
                    $txContent = $tx['transaction_content'] ?? '';
                    $amountIn = (int) ($tx['amount_in'] ?? 0);
                    $txId = $tx['id'] ?? uniqid('SEP-');
                    $refNo = $tx['reference_number'] ?? '';
                    $bankBrand = $tx['bank_brand_name'] ?? config('sepay.bank_name');
                    $accNum = $tx['account_number'] ?? config('sepay.account_no');

                    $upperContent = strtoupper($txContent);
                    $regex = '/(' . preg_quote($pattern, '/') . '\s*' . $order->id . '\b|' . preg_quote($pattern, '/') . $order->id . '\b|\bORD' . $order->id . '\b)/i';

                    $isMatched = preg_match($regex, $txContent)
                                 || str_contains($upperContent, strtoupper($transferContent))
                                 || str_contains($upperContent, 'DH' . $order->id);

                    if ($isMatched && ($amountIn >= $orderTotal || (config('app.debug') && $amountIn >= 1000))) {
                        $updateData = [
                            'status' => 'completed',
                            'payment_status' => 'paid',
                            'payment_method' => 'sepay_online',
                            'sepay_transaction_id' => (string) $txId,
                            'sepay_reference_code' => (string) $refNo,
                            'sepay_bank_account' => $bankBrand . ' (' . $accNum . ')',
                            'paid_at' => now(),
                        ];

                        if (\Schema::hasColumn('orders', 'note')) {
                            $updateData['note'] = ($order->note ?? '') . " [SePay Auto-Sync API] Nhận đủ " . number_format($amountIn) . "đ. Mã GD: " . $txId;
                        }

                        $order->update($updateData);
                        $isPaid = true;
                        Log::info("SePay Auto-Sync: Khớp thành công đơn #{$order->id} với GD #{$txId}");
                        break;
                    }
                }
            } catch (\Exception $e) {
                Log::debug('SePay auto-sync check error: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'order_code' => $order->order_code ?? ('DH' . $order->id),
            'status' => $order->status,
            'is_paid' => $isPaid,
            'has_api_key' => !empty($apiKey),
            'payment_method' => $order->payment_method ?? null,
            'transaction_id' => $order->sepay_transaction_id ?? null,
            'paid_at' => isset($order->paid_at) ? (is_string($order->paid_at) ? $order->paid_at : $order->paid_at->format('H:i:s d/m/Y')) : null,
            'amount' => $order->total ?? $order->amount ?? 0,
        ]);
    }

    /**
     * 3. Webhook tiếp nhận thông báo biến động số dư từ SePay (POST)
     * Khi có tiền vào tài khoản ngân hàng, SePay sẽ tự động gọi API này để kích hoạt đơn hàng
     */
    public function webhook(Request $request)
    {
        try {
            // Phản hồi kiểm tra trạng thái (GET / Ping) từ SePay hoặc trình duyệt
            if ($request->isMethod('get')) {
                return response()->json([
                    'success' => true,
                    'status' => 'active',
                    'message' => 'SePay Webhook Endpoint is ready and listening!',
                    'timestamp' => now()->toIso8601String()
                ], 200);
            }

            Log::info('SePay Webhook Received Payload:', $request->all());

            // Trích xuất dữ liệu từ SePay Webhook
            $data = $request->all();
            if (empty($data)) {
                $rawJson = json_decode($request->getContent(), true);
                if (is_array($rawJson)) {
                    $data = $rawJson;
                }
            }

            $content = $data['content'] ?? $data['description'] ?? '';
            $transferAmount = (int) ($data['transferAmount'] ?? $data['amount'] ?? 0);
            $gateway = $data['gateway'] ?? $data['bank'] ?? config('sepay.bank_name', 'Bank');
            $transactionId = $data['id'] ?? $data['transaction_id'] ?? uniqid('SEP-');
            $referenceCode = $data['referenceCode'] ?? $data['reference_code'] ?? ('REF' . time());
            $accountNumber = $data['accountNumber'] ?? $data['account_number'] ?? '';

            // Nếu đây là Test Webhook gửi thử từ SePay Dashboard (Gửi thử / SePay Test Call)
            $isTestCall = (isset($data['id']) && (int)$data['id'] === 0)
                || (($data['code'] ?? '') === 'SEPAYTEST')
                || str_contains(strtoupper($content), 'SEPAY TEST')
                || (empty($content) && $transferAmount == 0);

            if ($isTestCall) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kết nối Webhook thành công! (SePay Test Call Received)'
                ], 200);
            }

            // Bóc tách mã đơn hàng từ nội dung chuyển khoản (Ví dụ: DH28, DH 28, ORD28,...)
            $pattern = config('sepay.pattern', 'DH');
            $order = null;

            // Cách 1: Tìm theo cú pháp DH{id}
            if (preg_match('/' . preg_quote($pattern, '/') . '\s*(\d+)/i', $content, $matches)) {
                $orderId = (int) $matches[1];
                $order = Order::find($orderId);
            }

            // Cách 2: Tìm theo chuỗi số ID trong nội dung
            if (!$order && preg_match('/\b(\d{1,8})\b/', $content, $matches)) {
                $order = Order::find((int) $matches[1]);
            }

            // Cách 3: Tìm theo order_code
            if (!$order && \Schema::hasColumn('orders', 'order_code')) {
                $order = Order::where('order_code', 'like', '%' . trim($content) . '%')->first();
            }

            if (!$order) {
                Log::warning("SePay Webhook: Không tìm thấy đơn hàng khớp với nội dung: '{$content}'");
                return response()->json([
                    'success' => false,
                    'message' => "Không tìm thấy đơn hàng khớp với nội dung: {$content}"
                ], 200); // Trả về HTTP 200 để SePay ghi nhận nhận tin thành công
            }

            // Cập nhật trạng thái đơn hàng
            $updateData = [
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'sepay_online',
                'sepay_transaction_id' => (string) $transactionId,
                'sepay_reference_code' => (string) $referenceCode,
                'sepay_bank_account' => $gateway . ' (' . $accountNumber . ')',
                'paid_at' => now(),
            ];

            if (\Schema::hasColumn('orders', 'note')) {
                $updateData['note'] = ($order->note ?? '') . " [SePay] Nhận " . number_format($transferAmount) . "đ. Mã GD: {$transactionId}.";
            } elseif (\Schema::hasColumn('orders', 'notes')) {
                $updateData['notes'] = ($order->notes ?? '') . " [SePay] Nhận " . number_format($transferAmount) . "đ. Mã GD: {$transactionId}.";
            }

            $order->update($updateData);

            Log::info("SePay Webhook: Đã gạch nợ đơn hàng #{$order->id} thành công!");

            return response()->json([
                'success' => true,
                'message' => 'Xác nhận thanh toán SePay thành công',
                'order_id' => $order->id,
                'transaction_id' => $transactionId
            ], 200);

        } catch (\Throwable $e) {
            Log::error('SePay Webhook Catch Exception: ' . $e->getMessage());
            
            // Trả về 200 để tránh SePay hiển thị lỗi 500 khi test
            return response()->json([
                'success' => false,
                'message' => 'Xảy ra lỗi xử lý Webhook: ' . $e->getMessage()
            ], 200);
        }
    }

    /**
     * Alias tương thích cho webhook
     */
    public function handleWebhook(Request $request)
    {
        return $this->webhook($request);
    }

    /**
     * 4. BỘ GIẢ LẬP THANH TOÁN (SEPAY SANDBOX SIMULATOR)
     */
    public function simulatePayment(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        $simulatedId = 'SIM-' . rand(100000, 999999);
        $simulatedRef = 'FT' . date('ymd') . rand(10000, 99999);
        $bankName = config('sepay.bank_name', 'MBBank');
        $accountNo = config('sepay.account_no', '0988888888');
        $amount = (int) ($order->total > 0 ? $order->total : ($order->amount ?? 0));

        $updateData = [
            'status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'sepay_online (Giả Lập Test)',
            'sepay_transaction_id' => $simulatedId,
            'sepay_reference_code' => $simulatedRef,
            'sepay_bank_account' => $bankName . ' (' . $accountNo . ')',
            'paid_at' => now(),
        ];

        if (\Schema::hasColumn('orders', 'note')) {
            $updateData['note'] = ($order->note ?? '') . " [Giả lập Sandbox] " . number_format($amount) . "đ qua {$bankName}. Mã GD: {$simulatedId}.";
        }

        $order->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Giả lập thanh toán SePay thành công!',
            'data' => [
                'order_id' => $order->id,
                'order_code' => $order->order_code ?? ('DH' . $order->id),
                'amount' => $amount,
                'transaction_id' => $simulatedId,
                'reference_code' => $simulatedRef,
                'gateway' => $bankName,
                'paid_at' => now()->format('H:i:s d/m/Y')
            ]
        ]);
    }

    /**
     * 5. LƯU VÀ KÍCH HOẠT SEPAY API TOKEN TRỰC TIẾP
     */
    public function saveApiKey(Request $request)
    {
        $apiKey = trim($request->input('api_key', ''));
        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập API Token từ tài khoản SePay của bạn'
            ], 422);
        }

        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            if (preg_match('/^SEPAY_API_KEY=.*$/m', $envContent)) {
                $envContent = preg_replace('/^SEPAY_API_KEY=.*$/m', 'SEPAY_API_KEY=' . $apiKey, $envContent);
            } else {
                $envContent .= "\nSEPAY_API_KEY=" . $apiKey;
            }
            file_put_contents($envPath, $envContent);
        }

        config(['sepay.api_key' => $apiKey]);

        $testResult = $this->testSepayApiConnection($apiKey);

        return response()->json([
            'success' => true,
            'message' => 'Đã lưu và kích hoạt SePay API Token thành công!',
            'api_test' => $testResult
        ]);
    }

    /**
     * Helper kiểm tra kết nối SePay
     */
    private function testSepayApiConnection($apiKey)
    {
        try {
            $resV2 = \Illuminate\Support\Facades\Http::timeout(5)
                ->withToken($apiKey)
                ->get('https://userapi.sepay.vn/v2/transactions', ['per_page' => 1]);

            if ($resV2->successful()) {
                return ['connected' => true, 'version' => 'v2', 'data' => $resV2->json()];
            }

            $resV1 = \Illuminate\Support\Facades\Http::timeout(5)
                ->withHeaders(['Authorization' => 'Apikey ' . $apiKey])
                ->get('https://my.sepay.vn/userapi/transactions/list', ['limit' => 1]);

            if ($resV1->successful()) {
                return ['connected' => true, 'version' => 'v1', 'data' => $resV1->json()];
            }

            return ['connected' => false, 'error' => $resV2->body() ?: $resV1->body()];
        } catch (\Throwable $e) {
            return ['connected' => false, 'error' => $e->getMessage()];
        }
    }
}