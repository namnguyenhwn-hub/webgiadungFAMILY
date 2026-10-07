<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReviewController extends Controller
{
    /**
     * API Lấy danh sách đánh giá (Cho Trang Chủ index.html & Quản Trị admin.html)
     */
    public function apiIndex(Request $request)
    {
        Carbon::setLocale('vi');

        $query = Review::with('product')->orderBy('id', 'desc');

        // Nếu gọi từ trang chủ khách hàng và không có flag all, chỉ lấy approved
        if (!$request->has('all')) {
            $query->where('status', '!=', 'hidden');
        }

        $reviews = $query->get()->map(function ($r) {
            $prodName = $r->product ? $r->product->name : 'Thiết bị gia dụng cao cấp FAMILY';
            $prodImg = $r->product ? $r->product->image : 'public/images/products/air_fryer.jpg';
            
            $avatar = $r->avatar;
            if (!$avatar) {
                // Avatar mặc định sang trọng
                $avatar = 'https://ui-avatars.com/api/?name=' . urlencode($r->user_name) . '&background=C5A059&color=fff&size=150&bold=true';
            }

            return [
                'id' => (int)$r->id,
                'user_name' => $r->user_name,
                'user_title' => $r->user_title ?: 'Khách hàng đã trải nghiệm sản phẩm',
                'avatar' => $avatar,
                'product_id' => $r->product_id,
                'product_name' => $prodName,
                'product_image' => $prodImg,
                'rating' => (int)$r->rating,
                'comment' => $r->comment,
                'status' => $r->status ?: 'approved',
                'created_at_human' => $r->created_at ? $r->created_at->diffForHumans() : 'Vừa xong',
                'created_at' => $r->created_at ? $r->created_at->format('d/m/Y H:i') : '',
            ];
        });

        return response()->json([
            'success' => true,
            'count' => $reviews->count(),
            'reviews' => $reviews,
        ], 200, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept'
        ]);
    }

    /**
     * API Gửi Đánh Giá Mới (Dành cho cả Khách Hàng tại Trang Chủ & Quản Trị Viên)
     */
    public function apiStore(Request $request)
    {
        $userName = trim($request->input('user_name', ''));
        if (!$userName && Auth::check()) {
            $userName = Auth::user()->name;
        }
        if (!$userName) {
            $userName = 'Khách hàng';
        }

        $comment = trim($request->input('comment', ''));
        if (mb_strlen($comment, 'UTF-8') < 3) {
            return response()->json([
                'success' => false,
                'message' => 'Nội dung đánh giá phải có ít nhất 3 ký tự.',
            ], 422, ['Access-Control-Allow-Origin' => '*']);
        }

        $rating = (int)$request->input('rating', 5);
        if ($rating < 1 || $rating > 5) {
            $rating = 5;
        }

        $productId = $request->input('product_id');
        if (!$productId) {
            $firstProduct = Product::first();
            $productId = $firstProduct ? $firstProduct->id : null;
        }

        $userTitle = trim($request->input('user_title', ''));
        if (!$userTitle) {
            $userTitle = 'Khách hàng mua sắm trực tuyến';
        }

        $avatar = trim($request->input('avatar', ''));
        if (!$avatar) {
            $avatars = [
                'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
                'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80',
                'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=150&q=80',
                'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&q=80',
                'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&q=80'
            ];
            $avatar = $avatars[array_rand($avatars)];
        }

        $status = $request->input('status', 'approved');

        $review = Review::create([
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'user_name' => $userName,
            'user_title' => $userTitle,
            'avatar' => $avatar,
            'user_email' => Auth::check() ? Auth::user()->email : $request->input('user_email', ''),
            'rating' => $rating,
            'comment' => $comment,
            'status' => $status,
        ]);

        $prod = Product::find($productId);
        $prodName = $prod ? $prod->name : 'Thiết bị gia dụng cao cấp FAMILY';

        return response()->json([
            'success' => true,
            'message' => 'Cảm ơn bạn đã gửi đánh giá chân thực về sản phẩm!',
            'review' => [
                'id' => (int)$review->id,
                'user_name' => $review->user_name,
                'user_title' => $review->user_title,
                'avatar' => $review->avatar,
                'product_id' => $review->product_id,
                'product_name' => $prodName,
                'rating' => (int)$review->rating,
                'comment' => $review->comment,
                'status' => $review->status,
                'created_at_human' => 'Vừa xong',
                'created_at' => $review->created_at->format('d/m/Y H:i'),
            ]
        ], 201, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept'
        ]);
    }

    /**
     * API Cập nhật đánh giá (Dành cho Quản Trị Viên)
     */
    public function apiUpdate(Request $request, $id)
    {
        $review = Review::find($id);
        if (!$review) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy đánh giá #' . $id], 404, [
                'Access-Control-Allow-Origin' => '*'
            ]);
        }

        if ($request->has('user_name')) $review->user_name = trim($request->input('user_name'));
        if ($request->has('user_title')) $review->user_title = trim($request->input('user_title'));
        if ($request->has('comment')) $review->comment = trim($request->input('comment'));
        if ($request->has('rating')) $review->rating = (int)$request->input('rating');
        if ($request->has('status')) $review->status = trim($request->input('status'));
        if ($request->has('product_id')) $review->product_id = (int)$request->input('product_id');

        $review->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật đánh giá #' . $id . ' thành công!',
            'review' => $review
        ], 200, ['Access-Control-Allow-Origin' => '*']);
    }

    /**
     * API Xóa đánh giá (Dành cho Quản Trị Viên)
     */
    public function apiDestroy($id)
    {
        $review = Review::find($id);
        if (!$review) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy đánh giá #' . $id], 404, [
                'Access-Control-Allow-Origin' => '*'
            ]);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa đánh giá #' . $id . ' thành công!'
        ], 200, ['Access-Control-Allow-Origin' => '*']);
    }
}
