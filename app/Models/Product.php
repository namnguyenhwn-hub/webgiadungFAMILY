<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'category_id',
        'brand',
        'origin',
        'material',
        'usage',
        'price',
        'stock',
        'sold',
        'is_featured',
        'status',
        'image',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($product) {
            if (!empty($product->category_id) && empty($product->attributes['category'])) {
                $cat = Category::find($product->category_id);
                if ($cat) {
                    $product->attributes['category'] = $cat->name;
                }
            } elseif (!empty($product->attributes['category']) && empty($product->category_id)) {
                $cat = Category::firstOrCreate(['name' => $product->attributes['category']]);
                $product->category_id = $cat->id;
            }
        });

        static::saved(function ($product) {
            static::syncHomepageHtml();
        });

        static::deleted(function ($product) {
            static::syncHomepageHtml();
        });
    }

    /**
     * Tự động đồng bộ toàn bộ sản phẩm từ Database ra trang chủ index.html
     */
    public static function syncHomepageHtml()
    {
        $indexPath = base_path('index.html');
        if (!file_exists($indexPath)) {
            return;
        }

        try {
            $html = file_get_contents($indexPath);
            $products = static::orderBy('id', 'desc')->get();
            if ($products->count() === 0) {
                return;
            }

            $catCounts = [
                'all' => $products->count(),
                'noi-chien' => 0,
                'bep-dien-tu' => 0,
                'tu-lanh' => 0,
                'noi-com-ih' => 0,
                'robot-hut-bui' => 0,
            ];

            $cardsHtml = '';
            $cardIdx = 0;
            foreach ($products as $p) {
                $catName = 'Gia dụng thông minh';
                if (is_object($p->category) && isset($p->category->name)) {
                    $catName = $p->category->name;
                } elseif (is_string($p->category) && !empty($p->category)) {
                    $catName = $p->category;
                } elseif (!empty($p->attributes['category'])) {
                    $catName = $p->attributes['category'];
                }
                $catName = \App\Http\Controllers\ProductController::formatSentenceCase($catName);
                $cleanName = \App\Http\Controllers\ProductController::formatSentenceCase($p->name);

                $lowerText = mb_strtolower($catName . ' ' . $cleanName, 'UTF-8');
                $catSlug = 'all';
                if (str_contains($lowerText, 'chiên')) {
                    $catSlug = 'noi-chien';
                    $catCounts['noi-chien']++;
                } elseif (str_contains($lowerText, 'bếp') || str_contains($lowerText, 'từ')) {
                    $catSlug = 'bep-dien-tu';
                    $catCounts['bep-dien-tu']++;
                } elseif (str_contains($lowerText, 'lạnh') || str_contains($lowerText, 'mát') || str_contains($lowerText, 'tủ')) {
                    $catSlug = 'tu-lanh';
                    $catCounts['tu-lanh']++;
                } elseif (str_contains($lowerText, 'cơm') || str_contains($lowerText, 'cao tần')) {
                    $catSlug = 'noi-com-ih';
                    $catCounts['noi-com-ih']++;
                } elseif (str_contains($lowerText, 'bụi') || str_contains($lowerText, 'robot') || str_contains($lowerText, 'làm sạch')) {
                    $catSlug = 'robot-hut-bui';
                    $catCounts['robot-hut-bui']++;
                }

                $img = $p->image ?: 'public/images/products/air_fryer.jpg';
                if (!str_starts_with($img, 'http')) {
                    if (str_starts_with($img, 'products/')) {
                        $img = 'storage/' . $img;
                    } elseif (!str_starts_with($img, 'public/') && !str_starts_with($img, 'storage/')) {
                        $img = 'public/' . ltrim($img, '/');
                    }
                }

                $priceVal = (float)$p->price;
                $priceFmt = number_format($priceVal, 0, ',', '.') . ' ₫';
                $oldPriceFmt = $priceVal > 0 ? number_format(round($priceVal * 1.2 / 10000) * 10000, 0, ',', '.') . ' ₫' : '';
                $specs = $p->description ? \Illuminate\Support\Str::limit($p->description, 55, '...') : 'Bảo hành chính hãng 24T';
                $stock = (int)($p->quantity ?? $p->stock ?? 10);
                $pNameEsc = htmlspecialchars($cleanName, ENT_QUOTES, 'UTF-8');
                $pId = (int)$p->id;
                $brandEsc = htmlspecialchars($p->brand ?: 'Chính hãng', ENT_QUOTES, 'UTF-8');
                $originEsc = htmlspecialchars($p->origin ?: 'Việt Nam', ENT_QUOTES, 'UTF-8');
                $materialEsc = htmlspecialchars($p->material ?: 'Hợp kim & Nhựa ABS', ENT_QUOTES, 'UTF-8');
                $usageEsc = htmlspecialchars(\App\Http\Controllers\ProductController::formatSentenceCase($p->usage ?: 'Gia dụng thông minh gia đình'), ENT_QUOTES, 'UTF-8');

                $cardClass = ($cardIdx >= 8) ? 'product-card card-hidden' : 'product-card';
                $hideStyle = ($cardIdx >= 8) ? 'style="display: none !important; cursor: pointer;"' : 'style="cursor: pointer;"';
                $cardIdx++;

                $cardsHtml .= '
                <div class="'.$cardClass.'" data-category="'.$catSlug.'" data-product-id="'.$pId.'" data-product-name="'.$pNameEsc.'" data-product-price="'.$priceVal.'" data-product-img="'.$img.'" data-product-brand="'.$brandEsc.'" data-product-origin="'.$originEsc.'" data-product-material="'.$materialEsc.'" data-product-usage="'.$usageEsc.'" onclick="goToProductDetail('.$pId.', event)" '.$hideStyle.'>
                    <span class="product-badge-flag badge-hot">Chính hãng</span>
                    <div class="product-img-box">
                        <img src="'.$img.'" alt="'.$pNameEsc.'" loading="lazy" onerror="this.onerror=null; this.src=\'public/images/products/air_fryer.jpg\'">
                        <div class="card-quick-actions">
                            <button type="button" class="quick-action-btn" title="Xem thông số kỹ thuật" onclick="openProductQuickSpecModal('.$pId.')"><i class="fa-regular fa-eye"></i></button>
                            <button type="button" class="quick-action-btn btn-compare-action" data-compare-id="'.$pId.'" title="So sánh sản phẩm" onclick="toggleCompareProduct('.$pId.', event)"><i class="fa-solid fa-code-compare"></i></button>
                            <button type="button" class="quick-action-btn" title="Yêu thích" onclick="showToast(\'Đã lưu vào danh sách yêu thích!\')"><i class="fa-regular fa-heart"></i></button>
                        </div>
                    </div>
                    <div class="product-category-name">'.htmlspecialchars($catName, ENT_QUOTES, 'UTF-8').' • <span style="color: #9B782F; font-weight: 600;">'.$brandEsc.'</span></div>
                    <h4 class="product-title" title="'.$pNameEsc.'">'.$pNameEsc.'</h4>
                    <div class="product-specs">
                        <span><i class="fa-solid fa-earth-americas text-gold" style="font-size: 0.75rem;"></i> '.$originEsc.'</span>
                        <span style="margin: 0 4px; color: #CBD5E1;">|</span>
                        <span><i class="fa-solid fa-gem text-gold" style="font-size: 0.75rem;"></i> '.\Illuminate\Support\Str::limit($materialEsc, 24, '...').'</span>
                    </div>
                    <div class="product-rating-box">
                        <div class="rating-stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i></div>
                        <span class="sold-count">| Còn lại '.$stock.' sp</span>
                    </div>
                    <div class="product-price-row">
                        <div>
                            <span class="price-current">'.$priceFmt.'</span>
                            '.($oldPriceFmt ? '<span class="price-old">'.$oldPriceFmt.'</span>' : '').'
                        </div>
                        <span style="font-size: 0.75rem; color: #DC2626; font-weight: 700;">-18%</span>
                    </div>
                    <div style="display: flex; gap: 8px; margin-top: 12px;" onclick="event.stopPropagation()">
                        <button type="button" class="btn-buy-now" style="flex: 1.2; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 10px 12px; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; font-weight: 700; font-size: 0.85rem; border: none; border-radius: 9999px; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(197, 160, 89, 0.25);" onclick="proceedToRealCheckout('.$pId.', \''.addslashes($pNameEsc).'\', '.$priceVal.', \''.$img.'\', event)">
                            <i class="fa-solid fa-bolt-lightning"></i> Mua hàng
                        </button>
                        <button type="button" class="btn-add-cart" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 9px 10px; background: #FFFFFF; color: #8C6A24; border: 1px solid #EAE2D5; font-weight: 600; font-size: 0.825rem; border-radius: 9999px; cursor: pointer; transition: all 0.2s;" onclick="addToCart('.$pId.', \''.addslashes($pNameEsc).'\', '.$priceVal.', \''.$img.'\', event)">
                            <i class="fa-solid fa-cart-plus"></i> Giỏ
                        </button>
                    </div>
                </div>';
            }

            // Replace grid
            $startTag = '<div class="products-grid">';
            $startPos = strpos($html, $startTag);
            $endTag = '<!-- ==========================================================================';
            $endPos = strpos($html, $endTag, $startPos);
            if ($startPos !== false && $endPos !== false) {
                $before = substr($html, 0, $startPos + strlen($startTag));
                $after = substr($html, $endPos);
                $html = $before . "\n" . $cardsHtml . "\n            </div>\n\n            <!-- Phân Trang Sang Trọng Chuẩn Thiết Kế -->\n            <div class=\"products-pagination-container\" id=\"productsPagination\"></div>\n        </div>\n    </section>\n\n    " . $after;
            }

            // Update category counts
            foreach ($catCounts as $catKey => $count) {
                $html = preg_replace(
                    '#(data-category="' . preg_quote($catKey, '#') . '"[^>]*>.*?<span class="count">)\d+(</span>)#s',
                    '${1}' . $count . '${2}',
                    $html
                );
            }

            file_put_contents($indexPath, $html);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Error syncing index.html: ' . $e->getMessage());
        }
    }

    public function getStockAttribute()
    {
        return $this->attributes['quantity'] ?? $this->attributes['stock'] ?? 10;
    }

    public function getQuantityAttribute()
    {
        return $this->attributes['quantity'] ?? $this->attributes['stock'] ?? 10;
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->withDefault(function () {
            return new Category([
                'name' => $this->attributes['category'] ?? 'Gia dụng cao cấp'
            ]);
        });
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function getAverageRatingAttribute()
    {
        $avg = $this->reviews()->avg('rating');
        return $avg ? round($avg, 1) : 4.9;
    }

    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }
}
