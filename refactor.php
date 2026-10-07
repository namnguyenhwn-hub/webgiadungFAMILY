<?php
$html = file_get_contents('index.html.bak');

// 1. Thay thế phần bento-grid
// Trong web.php, họ dùng:
// $bStartTag = '<div class="bento-grid">';
// $sectionEndTag = '</section>';
$bStartTag = '<div class="bento-grid">';
$bStartPos = strpos($html, $bStartTag);
$sectionEndTag = '</section>';
$bEndPos = strpos($html, $sectionEndTag, $bStartPos);

if ($bStartPos !== false && $bEndPos !== false) {
    $bladeBento = <<<BLADE
<div class="bento-grid">
    @foreach(\$specialProducts as \$index => \$sp)
        @php
            \$spId = \$sp->id;
            \$spName = addslashes(htmlspecialchars(\$sp->name, ENT_QUOTES));
            \$spPrice = \$sp->price;
            \$spPriceFmt = number_format(\$sp->price, 0, ',', '.') . ' ₫';
            \$spImg = \$sp->image ?: 'images/products/air_fryer.jpg';
            if (!str_starts_with(\$spImg, 'http')) {
                if (str_starts_with(\$spImg, 'products/')) {
                    \$spImg = 'storage/' . \$spImg;
                }
                \$spImg = preg_replace('#^public/#', '', \$spImg);
                \$spImg = asset(ltrim(\$spImg, '/'));
            }
        @endphp

        @if(\$index == 0)
            <div class="bento-card bento-hero" data-product-id="{{\$spId}}" data-product-name="{{\$spName}}" data-product-price="{{\$spPrice}}" data-product-img="{{\$spImg}}" onclick="goToProductDetail({{\$spId}}, event)" style="cursor: pointer;">
                <div class="bento-inner">
                    <div>
                        <span class="bento-badge-gold">Đặc Biệt</span>
                        <h3 class="bento-card-title"><a href="{{ url('products/'.\$spId) }}" style="color: inherit; text-decoration: none;">{{\$sp->name}}</a></h3>
                        <p class="bento-card-desc">{{\Illuminate\Support\Str::limit(\$sp->description ?? 'Sản phẩm cao cấp từ FAMILY', 60)}}</p>
                        <div class="bento-price-wrap"><span class="bento-price">{{\$spPriceFmt}}</span></div>
                        <div style="display: flex; gap: 10px; margin-top: 16px;" onclick="event.stopPropagation()">
                            <button type="button" class="btn-buy-now btn-bento-buy" style="flex: 1.2; padding: 10px 20px; font-size: 0.875rem; font-weight: 700; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; border: none; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(197, 160, 89, 0.25);" onclick="proceedToRealCheckout({{\$spId}}, '{{\$spName}}', {{\$spPrice}}, '{{\$spImg}}', event)"><i class="fa-solid fa-bolt-lightning"></i> Mua ngay</button>
                            <button type="button" class="btn-outline-gold btn-bento-cart" style="flex: 1; padding: 10px 18px; font-size: 0.875rem; font-weight: 600; background: #FFFFFF; color: #8C6A24; border: 1px solid #EAE2D5; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;" onclick="addToCart({{\$spId}}, '{{\$spName}}', {{\$spPrice}}, '{{\$spImg}}')"><i class="fa-solid fa-cart-plus"></i> Giỏ</button>
                        </div>
                    </div>
                    <div class="bento-hero-img-box"><img src="{{\$spImg}}" alt="{{\$spName}}"></div>
                </div>
            </div>
        @elseif(\$index == 1)
            <div class="bento-card bento-fridge" data-product-id="{{\$spId}}" data-product-name="{{\$spName}}" data-product-price="{{\$spPrice}}" data-product-img="{{\$spImg}}" onclick="goToProductDetail({{\$spId}}, event)" style="cursor: pointer;">
                <div>
                    <span class="bento-badge-gold">Tuyệt Tác</span>
                    <h3 class="bento-card-title"><a href="{{ url('products/'.\$spId) }}" style="color: inherit; text-decoration: none;">{{\$sp->name}}</a></h3>
                    <p class="bento-card-desc">{{\Illuminate\Support\Str::limit(\$sp->description ?? 'Sản phẩm cao cấp', 50)}}</p>
                </div>
                <div class="bento-media-split">
                    <a href="{{ url('products/'.\$spId) }}" style="display: block;"><img src="{{\$spImg}}" alt="{{\$spName}}"></a>
                    <div>
                        <div class="bento-price" style="font-size: 1.35rem; margin-bottom: 10px;">{{\$spPriceFmt}}</div>
                        <div style="display: flex; gap: 8px; margin-top: 4px;" onclick="event.stopPropagation()">
                            <button type="button" class="btn-buy-now btn-bento-buy" style="flex: 1.2; padding: 10px 12px; font-size: 0.825rem; font-weight: 700; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; border: none; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 5px;" onclick="proceedToRealCheckout({{\$spId}}, '{{\$spName}}', {{\$spPrice}}, '{{\$spImg}}', event)"><i class="fa-solid fa-bolt-lightning"></i> Mua ngay</button>
                            <button type="button" class="btn-outline-gold btn-bento-cart" style="flex: 1; padding: 10px 10px; font-size: 0.825rem; font-weight: 600; background: #FFFFFF; color: #8C6A24; border: 1px solid #EAE2D5; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 5px;" onclick="addToCart({{\$spId}}, '{{\$spName}}', {{\$spPrice}}, '{{\$spImg}}')"><i class="fa-solid fa-cart-plus"></i> Giỏ</button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bento-card bento-span-4" data-product-id="{{\$spId}}" data-product-name="{{\$spName}}" data-product-price="{{\$spPrice}}" data-product-img="{{\$spImg}}" onclick="goToProductDetail({{\$spId}}, event)" style="cursor: pointer;">
                <div class="card-top-content">
                    <span class="bento-badge-gold">Sang Trọng</span>
                    <h3 class="bento-card-title" style="font-size: 1.2rem;"><a href="{{ url('products/'.\$spId) }}" style="color: inherit; text-decoration: none;">{{\$sp->name}}</a></h3>
                </div>
                <div class="card-img-container"><a href="{{ url('products/'.\$spId) }}" style="display: block;"><img src="{{\$spImg}}" alt="{{\$spName}}"></a></div>
                <div class="bento-card-footer" style="display: flex; align-items: flex-end; justify-content: space-between; gap: 8px;">
                    <div><span class="bento-price" style="font-size: 1.15rem;">{{\$spPriceFmt}}</span></div>
                    <div style="display: flex; gap: 6px;" onclick="event.stopPropagation()">
                        <button type="button" class="btn-buy-now btn-bento-buy" style="padding: 7px 11px; font-size: 0.78rem; font-weight: 700; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; border: none; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;" onclick="proceedToRealCheckout({{\$spId}}, '{{\$spName}}', {{\$spPrice}}, '{{\$spImg}}', event)"><i class="fa-solid fa-bolt-lightning"></i> Mua ngay</button>
                        <button type="button" class="btn-outline-gold btn-bento-cart" style="padding: 7px 10px; font-size: 0.78rem; font-weight: 600; background: #FFFFFF; color: #8C6A24; border: 1px solid #EAE2D5; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;" onclick="addToCart({{\$spId}}, '{{\$spName}}', {{\$spPrice}}, '{{\$spImg}}')"><i class="fa-solid fa-cart-plus"></i> Giỏ</button>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>
BLADE;
    
    $html = substr($html, 0, $bStartPos) . $bladeBento . "\n        </section>\n" . substr($html, $bEndPos + strlen($sectionEndTag));
}

// 2. Thay thế products-grid
$startTag = '<div class="products-grid">';
$startPos = strpos($html, $startTag);
$endTag = '<!-- ==========================================================================';
$endPos = strpos($html, $endTag, $startPos);

if ($startPos !== false && $endPos !== false) {
    $bladeProducts = <<<BLADE
<div class="products-grid">
    @php
        \$cardIndex = 0;
    @endphp
    @foreach(\$dbProducts as \$p)
        @php
            \$catName = 'Gia dụng thông minh';
            if (is_object(\$p->category) && isset(\$p->category->name)) {
                \$catName = \$p->category->name;
            } elseif (is_string(\$p->category) && !empty(\$p->category)) {
                \$catName = \$p->category;
            } elseif (!empty(\$p->attributes['category'])) {
                \$catName = \$p->attributes['category'];
            }
            \$catName = \App\Http\Controllers\ProductController::formatSentenceCase(\$catName);

            \$lowerText = mb_strtolower(\$catName . ' ' . \$p->name, 'UTF-8');
            \$catSlug = 'all';
            if (str_contains(\$lowerText, 'chiên')) { \$catSlug = 'noi-chien'; }
            elseif (str_contains(\$lowerText, 'bếp') || str_contains(\$lowerText, 'từ')) { \$catSlug = 'bep-dien-tu'; }
            elseif (str_contains(\$lowerText, 'lạnh') || str_contains(\$lowerText, 'mát') || str_contains(\$lowerText, 'tủ')) { \$catSlug = 'tu-lanh'; }
            elseif (str_contains(\$lowerText, 'cơm') || str_contains(\$lowerText, 'cao tần')) { \$catSlug = 'noi-com-ih'; }
            elseif (str_contains(\$lowerText, 'bụi') || str_contains(\$lowerText, 'robot') || str_contains(\$lowerText, 'làm sạch')) { \$catSlug = 'robot-hut-bui'; }

            \$img = \$p->image ?: 'images/products/air_fryer.jpg';
            if (!str_starts_with(\$img, 'http')) {
                if (str_starts_with(\$img, 'products/')) {
                    \$img = 'storage/' . \$img;
                }
                \$img = preg_replace('#^public/#', '', \$img);
                \$img = asset(ltrim(\$img, '/'));
            }
            \$priceFmt = number_format(\$p->price, 0, ',', '.') . ' ₫';
            \$oldPriceFmt = \$p->price > 0 ? number_format(round(\$p->price * 1.2 / 10000) * 10000, 0, ',', '.') . ' ₫' : '';
            \$specs = \$p->description ? \Illuminate\Support\Str::limit(\$p->description, 55, '...') : 'Bảo hành chính hãng 24T';
            \$pId = (int)\$p->id;
            \$pNameEsc = htmlspecialchars(\App\Http\Controllers\ProductController::formatSentenceCase(\$p->name), ENT_QUOTES, 'UTF-8');
            \$stock = \$p->quantity ?? \$p->stock ?? 10;
            \$brandEsc = htmlspecialchars(\$p->brand ?: 'Chính hãng', ENT_QUOTES, 'UTF-8');
            \$originEsc = htmlspecialchars(\$p->origin ?: 'Việt Nam', ENT_QUOTES, 'UTF-8');
            \$materialEsc = htmlspecialchars(\$p->material ?: 'Hợp kim & Nhựa ABS', ENT_QUOTES, 'UTF-8');
            \$usageEsc = htmlspecialchars(\App\Http\Controllers\ProductController::formatSentenceCase(\$p->usage ?: 'Gia dụng thông minh gia đình'), ENT_QUOTES, 'UTF-8');
            \$detailUrl = url('products/' . \$pId);

            \$hideStyle = (\$cardIndex >= 8) ? 'style="display: none; cursor: pointer;"' : 'style="cursor: pointer;"';
            \$cardIndex++;
        @endphp

        <div class="product-card" data-category="{{\$catSlug}}" data-product-id="{{\$pId}}" data-product-name="{{\$pNameEsc}}" data-product-price="{{\$p->price}}" data-product-img="{{\$img}}" data-product-brand="{{\$brandEsc}}" data-product-origin="{{\$originEsc}}" data-product-material="{{\$materialEsc}}" data-product-usage="{{\$usageEsc}}" onclick="goToProductDetail({{\$pId}}, event)" {!! \$hideStyle !!}>
            <span class="product-badge-flag badge-hot">Chính hãng</span>
            <div class="product-img-box">
                <a href="{{\$detailUrl}}" onclick="goToProductDetail({{\$pId}}, event)" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                    <img src="{{\$img}}" alt="{{\$pNameEsc}}" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('images/products/air_fryer.jpg') }}'">
                </a>
                <div class="card-quick-actions" onclick="event.stopPropagation()">
                    <button type="button" class="quick-action-btn" title="Xem thông số kỹ thuật" onclick="openProductQuickSpecModal({{\$pId}})"><i class="fa-regular fa-eye"></i></button>
                    <button type="button" class="quick-action-btn" title="Yêu thích" onclick="showToast('Đã lưu vào danh sách yêu thích!')"><i class="fa-regular fa-heart"></i></button>
                </div>
            </div>
            <div class="product-category-name">{{htmlspecialchars(\$catName, ENT_QUOTES, 'UTF-8')}} • <span style="color: #9B782F; font-weight: 600;">{{\$brandEsc}}</span></div>
            <h4 class="product-title" title="{{\$pNameEsc}}">
                <a href="{{\$detailUrl}}" onclick="goToProductDetail({{\$pId}}, event)" style="color: inherit; text-decoration: none;">{{\$pNameEsc}}</a>
            </h4>
            <div class="product-specs">
                <span><i class="fa-solid fa-earth-americas text-gold" style="font-size: 0.75rem;"></i> {{\$originEsc}}</span>
                <span style="margin: 0 4px; color: #CBD5E1;">|</span>
                <span><i class="fa-solid fa-gem text-gold" style="font-size: 0.75rem;"></i> {{\Illuminate\Support\Str::limit(\$materialEsc, 24, '...')}}</span>
            </div>
            <div class="product-rating-box">
                <div class="rating-stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i></div>
                <span class="sold-count">| Còn lại {{\$stock}} sp</span>
            </div>
            <div class="product-price-row">
                <div>
                    <span class="price-current">{{\$priceFmt}}</span>
                    @if(\$oldPriceFmt) <span class="price-old">{{\$oldPriceFmt}}</span> @endif
                </div>
                <span style="font-size: 0.75rem; color: #DC2626; font-weight: 700;">-18%</span>
            </div>
            <div style="display: flex; gap: 8px; margin-top: 12px;" onclick="event.stopPropagation()">
                <button type="button" class="btn-buy-now" style="flex: 1.2; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 10px 12px; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; font-weight: 700; font-size: 0.85rem; border: none; border-radius: 9999px; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(197, 160, 89, 0.25);" onclick="event.stopPropagation(); if (typeof proceedToRealCheckout === 'function') proceedToRealCheckout({{\$pId}}, '{{addslashes(\$pNameEsc)}}', {{\$p->price}}, '{{\$img}}', event)">
                    <i class="fa-solid fa-bolt-lightning"></i> Mua ngay
                </button>
                <button type="button" class="btn-add-cart" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 9px 10px; background: #FFFFFF; color: #8C6A24; border: 1px solid #EAE2D5; font-weight: 600; font-size: 0.825rem; border-radius: 9999px; cursor: pointer; transition: all 0.2s;" onclick="event.stopPropagation(); if (typeof addToCart === 'function') addToCart({{\$pId}}, '{{addslashes(\$pNameEsc)}}', {{\$p->price}}, '{{\$img}}', event)">
                    <i class="fa-solid fa-cart-plus"></i> Giỏ
                </button>
            </div>
        </div>
    @endforeach
</div>
BLADE;

    $html = substr($html, 0, $startPos) . $bladeProducts . "\n        <div class=\"products-pagination-container\" id=\"productsPagination\"></div>\n        </div>\n    </section>\n\n    " . substr($html, $endPos);
}

// 3. Thay thế count category: 
$categories = ['all', 'noi-chien', 'bep-dien-tu', 'tu-lanh', 'noi-com-ih', 'robot-hut-bui'];
foreach ($categories as $cat) {
    $pattern = '/(data-category="' . preg_quote($cat, '/') . '"[^>]*>.*?<span class="count">)\d+(<\/span>)/s';
    $html = preg_replace($pattern, '${1}{{ $catCounts["' . $cat . '"] ?? 0 }}$2', $html);
}

// 4. Các script auth:
$authScript = <<<BLADE
        @if(\Illuminate\Support\Facades\Auth::check())
            @php
                \$u = \Illuminate\Support\Facades\Auth::user();
                \$userJson = json_encode([
                    'id' => \$u->id,
                    'name' => \$u->name,
                    'email' => \$u->email,
                    'role' => \$u->role,
                ]);
            @endphp
            <script>try { localStorage.setItem('family_user', JSON.stringify({!! \$userJson !!})); localStorage.setItem('auraluxe_user', JSON.stringify({!! \$userJson !!})); } catch(e){} </script>
        @else
            <script>try { localStorage.removeItem('family_user'); localStorage.removeItem('auraluxe_user'); } catch(e){} </script>
        @endif
BLADE;
$html = str_replace('</head>', $authScript . "\n</head>", $html);

// 5. Thay thế public/css, public/images, public/js sang asset
$html = str_replace('href="public/css/', 'href="{{ asset(\'css/', $html);
// We need to append "}}" to CSS links, but this is a bit tricky, let's just use str_replace for specific parts
// Actually in index.html, let's just do a simple replace
$html = preg_replace('/href="public\/css\/([^"]+)"/', 'href="{{ asset(\'css/$1\') }}"', $html);
$html = preg_replace('/src="public\/images\/([^"]+)"/', 'src="{{ asset(\'images/$1\') }}"', $html);
$html = preg_replace('/src="public\/js\/([^"]+)"/', 'src="{{ asset(\'js/$1\') }}"', $html);

file_put_contents('resources/views/welcome.blade.php', $html);
echo "Converted to resources/views/welcome.blade.php\n";
