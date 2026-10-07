<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Lấy danh sách tin nhắn của 1 phiên chat (dành cho cả Khách hàng & Admin)
     */
    public function getMessages(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return response()->json([
                'success' => false,
                'message' => 'Thiếu session_id',
                'data' => []
            ], 400);
        }

        $messages = ChatMessage::where('session_id', $sessionId)
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'session_id' => $m->session_id,
                    'sender_type' => $m->sender_type,
                    'customer_name' => $m->customer_name,
                    'customer_email' => $m->customer_email,
                    'customer_phone' => $m->customer_phone,
                    'message' => $m->message,
                    'is_read' => (bool)$m->is_read,
                    'created_at' => $m->created_at ? $m->created_at->format('H:i d/m/Y') : '',
                    'time_short' => $m->created_at ? $m->created_at->format('H:i') : '',
                ];
            });

        return response()->json([
            'success' => true,
            'session_id' => $sessionId,
            'data' => $messages,
        ]);
    }

    /**
     * Gửi tin nhắn mới (Khách gửi hoặc Admin trả lời)
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required|string|max:64',
            'message' => 'required|string|max:5000',
            'sender_type' => 'nullable|string|in:customer,admin',
            'customer_name' => 'nullable|string|max:100',
            'customer_email' => 'nullable|email|max:150',
            'customer_phone' => 'nullable|string|max:30',
        ]);

        $sessionId = $validated['session_id'];
        $messageText = trim($validated['message']);
        $senderType = $validated['sender_type'] ?? 'customer';

        // Tự động kiểm tra quyền Admin nếu người dùng đang đăng nhập
        $authUser = Auth::user();
        if ($authUser && $authUser->isAdmin()) {
            if ($senderType === 'admin') {
                $senderType = 'admin';
            }
        }

        // Lấy thông tin khách hàng từ request, session trước đó, hoặc user đăng nhập
        $customerName = $validated['customer_name'] ?? null;
        $customerEmail = $validated['customer_email'] ?? null;
        $customerPhone = $validated['customer_phone'] ?? null;
        $userId = null;

        if ($authUser && !$authUser->isAdmin()) {
            $customerName = $customerName ?: $authUser->name;
            $customerEmail = $customerEmail ?: $authUser->email;
            $customerPhone = $customerPhone ?: $authUser->phone;
            $userId = $authUser->id;
        }

        // Nếu thiếu thông tin, thử lấy từ tin nhắn trước đó của cùng session
        if (!$customerName || !$customerEmail) {
            $previous = ChatMessage::where('session_id', $sessionId)
                ->whereNotNull('customer_name')
                ->latest()
                ->first();
            if ($previous) {
                $customerName = $customerName ?: $previous->customer_name;
                $customerEmail = $customerEmail ?: $previous->customer_email;
                $customerPhone = $customerPhone ?: $previous->customer_phone;
                $userId = $userId ?: $previous->user_id;
            }
        }

        $customerName = $customerName ?: 'Khách hàng #' . substr(md5($sessionId), 0, 5);

        $chat = ChatMessage::create([
            'session_id' => $sessionId,
            'customer_name' => $customerName,
            'customer_email' => $customerEmail,
            'customer_phone' => $customerPhone,
            'sender_type' => $senderType,
            'user_id' => $userId,
            'message' => $messageText,
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gửi tin nhắn thành công',
            'data' => [
                'id' => $chat->id,
                'session_id' => $chat->session_id,
                'sender_type' => $chat->sender_type,
                'customer_name' => $chat->customer_name,
                'customer_email' => $chat->customer_email,
                'customer_phone' => $chat->customer_phone,
                'message' => $chat->message,
                'is_read' => (bool)$chat->is_read,
                'created_at' => $chat->created_at ? $chat->created_at->format('H:i d/m/Y') : '',
                'time_short' => $chat->created_at ? $chat->created_at->format('H:i') : '',
            ]
        ]);
    }

    /**
     * Lấy danh sách tất cả các cuộc trò chuyện của khách hàng (dành cho Admin)
     */
    public function getConversations()
    {
        // Lấy danh sách session_id duy nhất và tin nhắn mới nhất
        $sessions = ChatMessage::select('session_id')
            ->distinct()
            ->pluck('session_id');

        $conversations = [];
        $totalUnreadCount = 0;

        foreach ($sessions as $sid) {
            $latestMsg = ChatMessage::where('session_id', $sid)->latest('id')->first();
            if (!$latestMsg) continue;

            $unreadCount = ChatMessage::where('session_id', $sid)
                ->where('sender_type', 'customer')
                ->where('is_read', false)
                ->count();

            $totalUnreadCount += $unreadCount;

            // Tìm thông tin khách hàng rõ ràng nhất
            $custInfo = ChatMessage::where('session_id', $sid)
                ->whereNotNull('customer_name')
                ->where('customer_name', '!=', '')
                ->first();

            $custName = $custInfo ? $custInfo->customer_name : $latestMsg->customer_name;
            $custEmail = $custInfo ? $custInfo->customer_email : $latestMsg->customer_email;
            $custPhone = $custInfo ? $custInfo->customer_phone : $latestMsg->customer_phone;

            $conversations[] = [
                'session_id' => $sid,
                'customer_name' => $custName ?: ('Khách #' . substr(md5($sid), 0, 5)),
                'customer_email' => $custEmail ?: 'Chưa cung cấp email',
                'customer_phone' => $custPhone ?: 'Chưa cung cấp SĐT',
                'last_message' => $latestMsg->message,
                'last_sender' => $latestMsg->sender_type,
                'unread_count' => $unreadCount,
                'updated_at' => $latestMsg->created_at ? $latestMsg->created_at->format('H:i d/m/Y') : '',
                'timestamp' => $latestMsg->created_at ? $latestMsg->created_at->timestamp : 0,
            ];
        }

        // Sắp xếp theo tin nhắn mới nhất lên đầu
        usort($conversations, function ($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });

        return response()->json([
            'success' => true,
            'total_unread' => $totalUnreadCount,
            'data' => $conversations,
        ]);
    }

    /**
     * Đánh dấu tin nhắn đã đọc
     */
    public function markAsRead(Request $request)
    {
        $sessionId = $request->input('session_id');
        $reader = $request->input('reader', 'admin'); // 'admin' hoặc 'customer'

        if (!$sessionId) {
            return response()->json(['success' => false, 'message' => 'Thiếu session_id'], 400);
        }

        if ($reader === 'admin') {
            ChatMessage::where('session_id', $sessionId)
                ->where('sender_type', 'customer')
                ->where('is_read', false)
                ->update(['is_read' => true]);
        } else {
            ChatMessage::where('session_id', $sessionId)
                ->where('sender_type', 'admin')
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Xóa 1 cuộc hội thoại (dành cho Admin)
     */
    public function deleteConversation(Request $request)
    {
        $sessionId = $request->input('session_id');
        if (!$sessionId) {
            return response()->json(['success' => false, 'message' => 'Thiếu session_id'], 400);
        }

        ChatMessage::where('session_id', $sessionId)->delete();

        return response()->json(['success' => true, 'message' => 'Đã xóa cuộc trò chuyện']);
    }
}
