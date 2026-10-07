<?php

namespace App\Services;

use App\Models\Product;

class CartService
{
    /**
     * Lấy giỏ hàng hiện tại từ session (cập nhật danh mục nếu cần)
     */
    public function getCart(): array
    {
        $cart = session()->get('cart', []);

        if (!empty($cart)) {
            $productIds = array_keys($cart);
            $products   = Product::with('category')->whereIn('id', $productIds)->get()->keyBy('id');
            $updated    = false;

            foreach ($cart as $id => &$item) {
                if (isset($products[$id])) {
                    $category     = $products[$id]->category;
                    $categoryName = $category
                        ? (is_object($category) ? ($category->name ?? 'Gia Dụng') : $category)
                        : ($products[$id]->category ?? 'Chưa phân loại');

                    if (!isset($item['category']) || $item['category'] !== $categoryName) {
                        $item['category'] = $categoryName;
                        $updated = true;
                    }
                }
            }

            if ($updated) {
                session()->put('cart', $cart);
            }
        }

        return $cart;
    }

    /**
     * Tính tổng tiền giỏ hàng
     */
    public function getTotal(array $cart): float
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    /**
     * Thêm sản phẩm vào giỏ hàng (có hỗ trợ sản phẩm tùy biến từ trang tĩnh)
     */
    public function addItem(int|string $id, int $quantity, array $fallback = []): void
    {
        $product = Product::with('category')->find($id);
        $cart    = session()->get('cart', []);

        if ($product) {
            $category     = $product->category;
            $categoryName = $category
                ? (is_object($category) ? ($category->name ?? 'Gia Dụng') : $category)
                : ($product->category ?? 'Chưa phân loại');

            if (isset($cart[$id])) {
                $cart[$id]['quantity'] += $quantity;
                $cart[$id]['category']  = $categoryName;
            } else {
                $cart[$id] = [
                    'name'     => $product->name,
                    'price'    => $product->price,
                    'quantity' => $quantity,
                    'image'    => $product->image,
                    'category' => $categoryName,
                ];
            }
        } else {
            // Sản phẩm tùy biến từ trang chủ tĩnh
            if (isset($cart[$id])) {
                $cart[$id]['quantity'] += $quantity;
            } else {
                $cart[$id] = [
                    'name'     => $fallback['name']     ?? ('Thiết Bị Gia Dụng FAMILY #' . $id),
                    'price'    => (float)($fallback['price']    ?? 990000),
                    'quantity' => $quantity,
                    'image'    => $fallback['image']    ?? null,
                    'category' => $fallback['category'] ?? 'Gia Dụng Cao Cấp',
                ];
            }
        }

        session()->put('cart', $cart);
        session()->save();
    }

    /**
     * Mua ngay một sản phẩm (làm mới toàn bộ giỏ, chỉ giữ sản phẩm này)
     */
    public function buyNow(int|string $id, int $quantity, array $fallback = []): string
    {
        $product     = Product::with('category')->find($id);
        $cart        = [];
        $productName = '';

        if ($product) {
            $category     = $product->category;
            $categoryName = $category
                ? (is_object($category) ? ($category->name ?? 'Gia Dụng') : $category)
                : ($product->category ?? 'Chưa phân loại');

            $productName = $product->name;
            $cart[$id]   = [
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => $quantity,
                'image'    => $product->image,
                'category' => $categoryName,
            ];
        } else {
            $productName = $fallback['name'] ?? ('Thiết Bị Gia Dụng FAMILY #' . $id);
            $cart[$id]   = [
                'name'     => $productName,
                'price'    => (float)($fallback['price']    ?? 990000),
                'quantity' => $quantity,
                'image'    => $fallback['image']    ?? null,
                'category' => $fallback['category'] ?? 'Gia Dụng Cao Cấp',
            ];
        }

        session()->put('cart', $cart);
        session()->save();

        return $productName;
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function updateItem(int|string $id, int $quantity): void
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = max(1, $quantity);
            session()->put('cart', $cart);
            session()->save();
        }
    }

    /**
     * Xóa một sản phẩm khỏi giỏ hàng
     */
    public function removeItem(int|string $id): void
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);

            if (empty($cart)) {
                session()->flash('cart_cleared', true);
            }

            session()->save();
        }
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clearCart(): void
    {
        session()->forget('cart');
        session()->flash('cart_cleared', true);
        session()->save();
    }

    /**
     * Đồng bộ giỏ hàng từ trang chủ tĩnh (localStorage) vào Laravel Session
     */
    public function syncFromStatic(array $items, string $mode = 'full'): array
    {
        $cart = ($mode === 'full') ? [] : session()->get('cart', []);

        foreach ($items as $item) {
            $id    = $item['id']       ?? rand(100, 999);
            $qty   = max(1, (int)($item['quantity'] ?? 1));
            $price = (float)($item['price']    ?? 990000);
            $name  = $item['name']     ?? 'Thiết Bị Gia Dụng';
            $image = $item['image']    ?? $item['img'] ?? null;
            $cat   = $item['category'] ?? 'Gia Dụng Cao Cấp';

            // Ưu tiên dữ liệu chuẩn từ DB nếu sản phẩm tồn tại
            $dbProduct = Product::with('category')->find($id);
            if ($dbProduct) {
                $name  = $dbProduct->name;
                $price = (float)$dbProduct->price;
                if (!empty($dbProduct->image)) {
                    $image = $dbProduct->image;
                }
                if ($dbProduct->category) {
                    $cat = is_object($dbProduct->category) ? ($dbProduct->category->name ?? 'Gia Dụng') : $dbProduct->category;
                }
            }

            if ($mode === 'append' && isset($cart[$id])) {
                $cart[$id]['quantity'] += $qty;
            } else {
                $cart[$id] = [
                    'name'     => $name,
                    'price'    => $price,
                    'quantity' => $qty,
                    'image'    => $image,
                    'category' => $cat,
                ];
            }
        }

        session()->put('cart', $cart);
        session()->save();

        return $cart;
    }
}
