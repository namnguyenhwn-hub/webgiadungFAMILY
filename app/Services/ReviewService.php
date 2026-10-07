<?php

namespace App\Services;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReviewService
{
    /**
     * Lấy danh sách đánh giá
     * Nếu $all = true: lấy tất cả (kể cả hidden) - dùng cho Admin
     * Nếu $all = false: chỉ lấy approved - dùng cho trang chủ khách hàng
     */
    public function getReviews(bool $all = false): \Illuminate\Support\Collection
    {
        Carbon::setLocale('vi');

        $query = Review::with('product')->orderBy('id', 'desc');

        if (!$all) {
            $query->where('status', '!=', 'hidden');
        }

        return $query->get()->map(function ($r) {
            $prodName = $r->product ? $r->product->name : 'Thiết bị gia dụng cao cấp FAMILY';
            $prodImg  = $r->product ? $r->product->image : 'public/images/products/air_fryer.jpg';

            $avatar = $r->avatar;
            if (!$avatar) {
                $avatar = 'https://ui-avatars.com/api/?name=' . urlencode($r->user_name)
                    . '&background=C5A059&color=fff&size=150&bold=true';
            }

            return [
                'id'               => (int)$r->id,
                'user_name'        => $r->user_name,
                'user_title'       => $r->user_title ?: 'Khách hàng đã trải nghiệm sản phẩm',
                'avatar'           => $avatar,
                'product_id'       => $r->product_id,
                'product_name'     => $prodName,
                'product_image'    => $prodImg,
                'rating'           => (int)$r->rating,
                'comment'          => $r->comment,
                'status'           => $r->status ?: 'approved',
                'created_at_human' => $r->created_at ? $r->created_at->diffForHumans() : 'Vừa xong',
                'created_at'       => $r->created_at ? $r->created_at->format('d/m/Y H:i') : '',
            ];
        });
    }

    /**
     * Tạo đánh giá mới (khách hàng hoặc admin gửi)
     */
    public function createReview(array $data): Review
    {
        // Xử lý tên người đánh giá
        $userName = trim($data['user_name'] ?? '');
        if (!$userName && Auth::check()) {
            $userName = Auth::user()->name;
        }
        if (!$userName) {
            $userName = 'Khách hàng';
        }

        // Xử lý nội dung đánh giá
        $comment = trim($data['comment'] ?? '');
        if (mb_strlen($comment, 'UTF-8') < 3) {
            throw new \InvalidArgumentException('Nội dung đánh giá phải có ít nhất 3 ký tự.');
        }

        // Xử lý rating (1-5)
        $rating = (int)($data['rating'] ?? 5);
        if ($rating < 1 || $rating > 5) {
            $rating = 5;
        }

        // Xử lý product_id (nếu không truyền, lấy sản phẩm đầu tiên)
        $productId = $data['product_id'] ?? null;
        if (!$productId) {
            $firstProduct = Product::first();
            $productId    = $firstProduct ? $firstProduct->id : null;
        }

        // Xử lý user_title
        $userTitle = trim($data['user_title'] ?? '');
        if (!$userTitle) {
            $userTitle = 'Khách hàng mua sắm trực tuyến';
        }

        // Xử lý avatar (random nếu không cung cấp)
        $avatar = trim($data['avatar'] ?? '');
        if (!$avatar) {
            $avatars = [
                'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
                'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80',
                'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=150&q=80',
                'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&q=80',
                'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&q=80',
            ];
            $avatar = $avatars[array_rand($avatars)];
        }

        $status = $data['status'] ?? 'approved';

        return Review::create([
            'product_id' => $productId,
            'user_id'    => Auth::id(),
            'user_name'  => $userName,
            'user_title' => $userTitle,
            'avatar'     => $avatar,
            'user_email' => Auth::check() ? Auth::user()->email : ($data['user_email'] ?? ''),
            'rating'     => $rating,
            'comment'    => $comment,
            'status'     => $status,
        ]);
    }

    /**
     * Cập nhật đánh giá (dành cho Admin)
     */
    public function updateReview(int $id, array $data): Review
    {
        $review = Review::findOrFail($id);

        if (isset($data['user_name']))  $review->user_name  = trim($data['user_name']);
        if (isset($data['user_title'])) $review->user_title = trim($data['user_title']);
        if (isset($data['comment']))    $review->comment    = trim($data['comment']);
        if (isset($data['rating']))     $review->rating     = (int)$data['rating'];
        if (isset($data['status']))     $review->status     = trim($data['status']);
        if (isset($data['product_id'])) $review->product_id = (int)$data['product_id'];

        $review->save();

        return $review;
    }

    /**
     * Xóa đánh giá (dành cho Admin)
     */
    public function deleteReview(int $id): void
    {
        $review = Review::findOrFail($id);
        $review->delete();
    }
}
