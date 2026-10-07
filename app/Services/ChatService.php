<?php

namespace App\Services;

use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;

class ChatService
{
    /**
     * Lấy toàn bộ tin nhắn của một phiên chat theo session_id
     */
    public function getMessages(string $sessionId): \Illuminate\Support\Collection
    {
        return ChatMessage::where('session_id', $sessionId)
            ->orderBy('id', 'asc')
            ->get()
            ->map(fn($m) => $this->formatMessage($m));
    }

    /**
     * Gửi tin nhắn mới (khách hàng hoặc admin)
     */
    public function sendMessage(array $data): ChatMessage
    {
        $sessionId   = $data['session_id'];
        $messageText = trim($data['message']);
        $senderType  = $data['sender_type'] ?? 'customer';

        // Kiểm tra quyền Admin
        $authUser = Auth::user();
        if ($authUser && $authUser->isAdmin() && $senderType === 'admin') {
            $senderType = 'admin';
        }

        // Lấy thông tin khách hàng
        $customerName  = $data['customer_name']  ?? null;
        $customerEmail = $data['customer_email'] ?? null;
        $customerPhone = $data['customer_phone'] ?? null;
        $userId        = null;

        if ($authUser && !$authUser->isAdmin()) {
            $customerName  = $customerName  ?: $authUser->name;
            $customerEmail = $customerEmail ?: $authUser->email;
            $customerPhone = $customerPhone ?: $authUser->phone;
            $userId        = $authUser->id;
        }

        // Bổ sung thông tin từ tin nhắn trước của cùng session nếu thiếu
        if (!$customerName || !$customerEmail) {
            $previous = ChatMessage::where('session_id', $sessionId)
                ->whereNotNull('customer_name')
                ->latest()
                ->first();

            if ($previous) {
                $customerName  = $customerName  ?: $previous->customer_name;
                $customerEmail = $customerEmail ?: $previous->customer_email;
                $customerPhone = $customerPhone ?: $previous->customer_phone;
                $userId        = $userId        ?: $previous->user_id;
            }
        }

        $customerName = $customerName ?: ('Khách hàng #' . substr(md5($sessionId), 0, 5));

        return ChatMessage::create([
            'session_id'     => $sessionId,
            'customer_name'  => $customerName,
            'customer_email' => $customerEmail,
            'customer_phone' => $customerPhone,
            'sender_type'    => $senderType,
            'user_id'        => $userId,
            'message'        => $messageText,
            'is_read'        => false,
        ]);
    }

    /**
     * Lấy danh sách tất cả cuộc hội thoại (dành cho Admin)
     * Mỗi phần tử chứa: session_id, thông tin khách, tin nhắn mới nhất, số tin chưa đọc
     */
    public function getConversations(): array
    {
        $sessions       = ChatMessage::select('session_id')->distinct()->pluck('session_id');
        $conversations  = [];
        $totalUnread    = 0;

        foreach ($sessions as $sid) {
            $latestMsg = ChatMessage::where('session_id', $sid)->latest('id')->first();
            if (!$latestMsg) {
                continue;
            }

            $unreadCount = ChatMessage::where('session_id', $sid)
                ->where('sender_type', 'customer')
                ->where('is_read', false)
                ->count();

            $totalUnread += $unreadCount;

            $custInfo  = ChatMessage::where('session_id', $sid)
                ->whereNotNull('customer_name')
                ->where('customer_name', '!=', '')
                ->first();

            $custName  = $custInfo ? $custInfo->customer_name  : $latestMsg->customer_name;
            $custEmail = $custInfo ? $custInfo->customer_email : $latestMsg->customer_email;
            $custPhone = $custInfo ? $custInfo->customer_phone : $latestMsg->customer_phone;

            $conversations[] = [
                'session_id'     => $sid,
                'customer_name'  => $custName  ?: ('Khách #' . substr(md5($sid), 0, 5)),
                'customer_email' => $custEmail ?: 'Chưa cung cấp email',
                'customer_phone' => $custPhone ?: 'Chưa cung cấp SĐT',
                'last_message'   => $latestMsg->message,
                'last_sender'    => $latestMsg->sender_type,
                'unread_count'   => $unreadCount,
                'updated_at'     => $latestMsg->created_at ? $latestMsg->created_at->format('H:i d/m/Y') : '',
                'timestamp'      => $latestMsg->created_at ? $latestMsg->created_at->timestamp : 0,
            ];
        }

        // Sắp xếp theo tin nhắn mới nhất lên đầu
        usort($conversations, fn($a, $b) => $b['timestamp'] - $a['timestamp']);

        return [
            'conversations' => $conversations,
            'total_unread'  => $totalUnread,
        ];
    }

    /**
     * Đánh dấu tin nhắn đã đọc theo session_id và người đọc (admin/customer)
     */
    public function markAsRead(string $sessionId, string $reader = 'admin'): void
    {
        $senderType = ($reader === 'admin') ? 'customer' : 'admin';

        ChatMessage::where('session_id', $sessionId)
            ->where('sender_type', $senderType)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * Xóa toàn bộ một cuộc hội thoại theo session_id (dành cho Admin)
     */
    public function deleteConversation(string $sessionId): void
    {
        ChatMessage::where('session_id', $sessionId)->delete();
    }

    /**
     * Format một tin nhắn thành mảng trả về cho API
     */
    private function formatMessage(ChatMessage $m): array
    {
        return [
            'id'             => $m->id,
            'session_id'     => $m->session_id,
            'sender_type'    => $m->sender_type,
            'customer_name'  => $m->customer_name,
            'customer_email' => $m->customer_email,
            'customer_phone' => $m->customer_phone,
            'message'        => $m->message,
            'is_read'        => (bool)$m->is_read,
            'created_at'     => $m->created_at ? $m->created_at->format('H:i d/m/Y') : '',
            'time_short'     => $m->created_at ? $m->created_at->format('H:i') : '',
        ];
    }

    /**
     * Format một tin nhắn vừa gửi thành mảng trả về cho API
     */
    public function formatSentMessage(ChatMessage $chat): array
    {
        return $this->formatMessage($chat);
    }
}
