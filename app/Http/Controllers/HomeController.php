<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Render the landing page with curated product and promotional data.
     */
    public function index(Request $request)
    {
        // 1. Danh mục sản phẩm (Categories)
        $categories = [
            [
                'id' => 'all',
                'name' => 'Tất cả sản phẩm',
                'icon' => 'fa-layer-group',
                'count' => 45,
            ],
            [
                'id' => 'noi-chien',
                'name' => 'Nồi chiên không dầu',
                'icon' => 'fa-fire-burner',
                'count' => 12,
            ],
            [
                'id' => 'bep-dien-tu',
                'name' => 'Bếp điện & Bếp từ',
                'icon' => 'fa-kitchen-set',
                'count' => 18,
            ],
            [
                'id' => 'tu-lanh',
                'name' => 'Tủ lạnh thông minh',
                'icon' => 'fa-snowflake',
                'count' => 9,
            ],
            [
                'id' => 'noi-com-ih',
                'name' => 'Nồi cơm cao tần',
                'icon' => 'fa-bowl-rice',
                'count' => 14,
            ],
            [
                'id' => 'robot-hut-bui',
                'name' => 'Robot hút bụi AI',
                'icon' => 'fa-robot',
                'count' => 8,
            ],
        ];

        // 2. Bento Grid Showcase (Sản phẩm Flagship tiêu biểu)
        $bentoItems = [
            'hero' => [
                'id' => 101,
                'category' => 'noi-chien',
                'badge' => 'FLAGSHIP 2026',
                'name' => 'Nồi Chiên Không Dầu FAMILY Pro OLED 12L',
                'subtitle' => 'Công nghệ gia nhiệt kép Dual-Heat 360° đối lưu & Giảm 95% lượng dầu mỡ thừa',
                'price' => 4890000,
                'original_price' => 6200000,
                'discount' => '-21%',
                'rating' => 4.9,
                'reviews_count' => 384,
                'image' => asset('images/products/air_fryer.jpg'),
                'features' => [
                    'Dung tích lớn 12L nướng gà nguyên con',
                    'Màn hình cảm ứng OLED viền Gold',
                    'Cửa kính trong suốt quan sát món ăn',
                    'Kết nối Wifi điều khiển qua Smartphone',
                ],
            ],
            'fridge' => [
                'id' => 102,
                'category' => 'tu-lanh',
                'badge' => 'CHAMPAGNE LUXE',
                'name' => 'Tủ Lạnh Smart French-Door 4 Cánh 568L',
                'subtitle' => 'Mặt thép phay xước viền kim loại vàng hoàng gia, cấp đông mềm chuẩn -3°C',
                'price' => 32990000,
                'original_price' => 38500000,
                'discount' => '-15%',
                'rating' => 5.0,
                'reviews_count' => 142,
                'image' => asset('images/products/smart_fridge.jpg'),
                'features' => [
                    'Dung tích siêu lớn 568L',
                    'Công nghệ Dual Inverter tiết kiệm điện',
                    'Bảo quản thực phẩm tươi ngon 21 ngày',
                ],
            ],
            'hob' => [
                'id' => 103,
                'category' => 'bep-dien-tu',
                'badge' => 'GERMAN CERAN',
                'name' => 'Bếp Từ Đôi Inverter Booster 4400W Gold',
                'subtitle' => 'Kính gốm Schott Ceran chống trầy viền hợp kim vát cạnh vàng kim',
                'price' => 14490000,
                'original_price' => 18900000,
                'discount' => '-23%',
                'rating' => 4.9,
                'reviews_count' => 210,
                'image' => asset('images/products/induction_hob.jpg'),
                'features' => [
                    'Mâm từ E.G.O nhập khẩu Đức',
                    'Slider trượt cảm ứng 9 mức nhiệt',
                    'Cảm biến chống trào tự ngắt AI',
                ],
            ],
            'rice_cooker' => [
                'id' => 104,
                'category' => 'noi-com-ih',
                'badge' => 'JAPANESE TECH',
                'name' => 'Nồi Cơm Áp Suất Cao Tần IH FAMILY 1.8L',
                'subtitle' => 'Lòng nồi niêu gang 8 lớp phủ kim cương vàng, giữ ấm 48h trọn vị',
                'price' => 6850000,
                'original_price' => 8500000,
                'discount' => '-19%',
                'rating' => 4.8,
                'reviews_count' => 176,
                'image' => asset('images/products/rice_cooker.jpg'),
                'features' => [
                    'Cao tần IH 1300W nhiệt lượng 360°',
                    '18 chế độ nấu cơm chuẩn nhà hàng',
                ],
            ],
            'vacuum' => [
                'id' => 105,
                'category' => 'robot-hut-bui',
                'badge' => 'AI SMART LIVING',
                'name' => 'Robot Hút Bụi Lau Nhà Thông Minh S9 Pro',
                'subtitle' => 'Laser Lidar 3D, lực hút cực đại 6000Pa tự động giặt sấy giẻ kháng khuẩn',
                'price' => 15900000,
                'original_price' => 19500000,
                'discount' => '-18%',
                'rating' => 4.9,
                'reviews_count' => 295,
                'image' => asset('images/products/robot_vacuum.jpg'),
                'features' => [
                    'Lực hút 6000Pa siêu êm',
                    'Tự động đổ rác & sấy giẻ nóng 55°C',
                ],
            ],
        ];

        // 3. Danh sách sản phẩm chi tiết cho Lưới Sản Phẩm (Product Catalog Grid)
        $products = [
            [
                'id' => 1,
                'name' => 'Nồi Chiên Không Dầu FAMILY Pro OLED 12L',
                'category' => 'noi-chien',
                'category_name' => 'Nồi chiên',
                'badge' => 'BÁN CHẠY',
                'badge_type' => 'hot',
                'image' => asset('images/products/air_fryer.jpg'),
                'price' => 4890000,
                'original_price' => 6200000,
                'discount' => '-21%',
                'rating' => 4.9,
                'sold' => 842,
                'specs' => '12 Lít | 2000W | Kính OLED',
            ],
            [
                'id' => 2,
                'name' => 'Tủ Lạnh Smart French-Door 4 Cánh 568L',
                'category' => 'tu-lanh',
                'category_name' => 'Tủ lạnh',
                'badge' => 'VIP LUXURY',
                'badge_type' => 'vip',
                'image' => asset('images/products/smart_fridge.jpg'),
                'price' => 32990000,
                'original_price' => 38500000,
                'discount' => '-15%',
                'rating' => 5.0,
                'sold' => 219,
                'specs' => '568L | Cấp đông mềm | Dual Inverter',
            ],
            [
                'id' => 3,
                'name' => 'Bếp Từ Đôi Inverter Booster 4400W Gold',
                'category' => 'bep-dien-tu',
                'category_name' => 'Bếp điện từ',
                'badge' => 'ĐỨC CHÍNH HÃNG',
                'badge_type' => 'premium',
                'image' => asset('images/products/induction_hob.jpg'),
                'price' => 14490000,
                'original_price' => 18900000,
                'discount' => '-23%',
                'rating' => 4.9,
                'sold' => 430,
                'specs' => '4400W | Schott Ceran | Slider 9 cấp',
            ],
            [
                'id' => 4,
                'name' => 'Nồi Cơm Áp Suất Cao Tần IH FAMILY 1.8L',
                'category' => 'noi-com-ih',
                'category_name' => 'Nồi cơm điện',
                'badge' => 'CÔNG NGHỆ NHẬT',
                'badge_type' => 'new',
                'image' => asset('images/products/rice_cooker.jpg'),
                'price' => 6850000,
                'original_price' => 8500000,
                'discount' => '-19%',
                'rating' => 4.8,
                'sold' => 512,
                'specs' => '1.8L | Cao tần IH | Lòng gang 8 lớp',
            ],
            [
                'id' => 5,
                'name' => 'Robot Hút Bụi Lau Nhà Tự Động FAMILY S9 Pro',
                'category' => 'robot-hut-bui',
                'category_name' => 'Robot hút bụi',
                'badge' => 'AI SMART',
                'badge_type' => 'hot',
                'image' => asset('images/products/robot_vacuum.jpg'),
                'price' => 15900000,
                'original_price' => 19500000,
                'discount' => '-18%',
                'rating' => 4.9,
                'sold' => 380,
                'specs' => '6000Pa | Lidar 3D | Tự giặt sấy',
            ],
            [
                'id' => 6,
                'name' => 'Nồi Chiên Không Dầu Điện Tử 8.5L Compact Gold',
                'category' => 'noi-chien',
                'category_name' => 'Nồi chiên',
                'badge' => 'TIẾT KIỆM',
                'badge_type' => 'sale',
                'image' => asset('images/products/air_fryer.jpg'),
                'price' => 2950000,
                'original_price' => 3990000,
                'discount' => '-26%',
                'rating' => 4.7,
                'sold' => 670,
                'specs' => '8.5L | 1800W | Vỏ nhôm vàng champagne',
            ],
            [
                'id' => 7,
                'name' => 'Bếp Điện Từ Đơn Cảm Ứng Ultra-Slim FAMILY 2200W',
                'category' => 'bep-dien-tu',
                'category_name' => 'Bếp điện từ',
                'badge' => 'MỚI VỀ',
                'badge_type' => 'new',
                'image' => asset('images/products/induction_hob.jpg'),
                'price' => 3450000,
                'original_price' => 4200000,
                'discount' => '-18%',
                'rating' => 4.8,
                'sold' => 194,
                'specs' => '2200W | Mặt kính Crystal | Bo viền vàng',
            ],
            [
                'id' => 8,
                'name' => 'Tủ Lạnh Mini Side-Bar Gold Edition 120L',
                'category' => 'tu-lanh',
                'category_name' => 'Tủ lạnh',
                'badge' => 'LUXURY BAR',
                'badge_type' => 'vip',
                'image' => asset('images/products/smart_fridge.jpg'),
                'price' => 11200000,
                'original_price' => 13500000,
                'discount' => '-17%',
                'rating' => 4.9,
                'sold' => 115,
                'specs' => '120L | Cửa kính cảm ứng | Khử mùi than hoạt tính',
            ],
        ];

        // 4. Cam kết thương hiệu & Đặc quyền dịch vụ
        $commitments = [
            [
                'icon' => 'fa-shield-halved',
                'title' => 'Bảo Hành 24 Tháng',
                'desc' => 'Kích hoạt điện tử nhanh chóng, hỗ trợ kỹ thuật viên sửa chữa tận nhà trong 24h.',
            ],
            [
                'icon' => 'fa-arrow-rotate-left',
                'title' => '1 Đổi 1 Trong 30 Ngày',
                'desc' => 'Đổi mới sản phẩm hoàn toàn miễn phí nếu phát sinh lỗi từ nhà sản xuất.',
            ],
            [
                'icon' => 'fa-truck-fast',
                'title' => 'Giao Hàng & Lắp Đặt 2H',
                'desc' => 'Miễn phí giao hàng toàn quốc. Lắp đặt và hướng dẫn tận tình bởi kỹ sư FAMILY.',
            ],
            [
                'icon' => 'fa-gem',
                'title' => 'Chính Hãng 100% Hoàn Tiền',
                'desc' => 'Cam kết 100% sản phẩm có nguồn gốc chuẩn châu Âu & Nhật Bản, chứng từ CO/CQ đầy đủ.',
            ],
        ];

        // 5. Đánh giá của khách hàng (Reviews / Testimonials)
        $reviews = [
            [
                'name' => 'Nguyễn Thu Huyền',
                'role' => 'Chủ căn hộ Vinhomes Metropolis, Hà Nội',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
                'rating' => 5,
                'product' => 'Nồi Chiên Không Dầu FAMILY Pro OLED 12L',
                'comment' => 'Thiết kế màu đen viền vàng sang trọng xuất sắc, đặt vào căn bếp phong cách tân cổ điển cực kỳ hợp. Nồi 12L nướng cả con gà chín vàng đều mà không bị khô. Rất hài lòng với dịch vụ!',
                'date' => '3 ngày trước',
            ],
            [
                'name' => 'Trần Quang Dũng',
                'role' => 'Kiến trúc sư nội thất, TP. Hồ Chí Minh',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80',
                'rating' => 5,
                'product' => 'Bếp Từ Đôi Inverter Booster 4400W',
                'comment' => 'Mặt kính Schott Ceran vát cạnh kim loại vàng cực kỳ tinh tế. Khách hàng của mình ai ghé thăm cũng khen căn bếp sang trọng như resort. Đun sôi 1 lít nước chỉ mất chưa tới 2 phút.',
                'date' => '1 tuần trước',
            ],
            [
                'name' => 'Lê Mai Phương',
                'role' => 'Blogger Ẩm Thực Gia Đình, Đà Nẵng',
                'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=150&q=80',
                'rating' => 5,
                'product' => 'Tủ Lạnh Smart French-Door 568L',
                'comment' => 'Ngăn đông mềm -3°C là cứu tinh cho mẹ bận rộn như mình, thịt cá lấy ra nấu ngay không cần rã đông. Cửa kính gõ 2 lần sáng đèn rất hiện đại. Đóng gói và giao hàng 10/10!',
                'date' => '2 tuần trước',
            ],
        ];

        // Xử lý tìm kiếm sản phẩm (Search Query)
        $searchQuery = trim($request->query('search', $request->query('q', '')));
        if ($searchQuery !== '') {
            $cleanSearch = $this->removeAccents($searchQuery);
            $products = array_values(array_filter($products, function($p) use ($searchQuery, $cleanSearch) {
                $name = $this->removeAccents($p['name']);
                $cat = $this->removeAccents($p['category_name']);
                $specs = $this->removeAccents($p['specs'] ?? '');
                return str_contains($name, $cleanSearch) 
                    || str_contains($cat, $cleanSearch) 
                    || str_contains($specs, $cleanSearch);
            }));
        }

        return view('home', compact(
            'categories',
            'bentoItems',
            'products',
            'commitments',
            'reviews',
            'searchQuery'
        ));
    }

    /**
     * Remove Vietnamese accents for tolerant search matching
     */
    private function removeAccents(string $str): string
    {
        $accents = [
            'a' => ['à','á','ạ','ả','ã','â','ầ','ấ','ậ','ẩ','ẫ','ă','ằ','ắ','ặ','ẳ','ẵ'],
            'e' => ['è','é','ẹ','ẻ','ẽ','ê','ề','ế','ệ','ể','ễ'],
            'i' => ['ì','í','ị','ỉ','ĩ'],
            'o' => ['ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ','ơ','ờ','ớ','ợ','ở','ỡ'],
            'u' => ['ù','ú','ụ','ủ','ũ','ư','ừ','ứ','ự','ử','ữ'],
            'y' => ['ỳ','ý','ỵ','ỷ','ỹ'],
            'd' => ['đ'],
            'A' => ['À','Á','Ạ','Ả','Ã','Â','Ầ','Ấ','Ậ','Ẩ','Ẫ','Ă','Ằ','Ắ','Ặ','Ẳ','Ẵ'],
            'E' => ['È','É','Ẹ','Ẻ','Ẽ','Ê','Ề','Ế','Ệ','Ể','Ễ'],
            'I' => ['Ì','Í','Ị','Ỉ','Ĩ'],
            'O' => ['Ò','Ó','Ọ','Ỏ','Õ','Ô','Ồ','Ố','Ộ','Ổ','Ỗ','Ơ','Ờ','Ớ','Ợ','Ở','Ỡ'],
            'U' => ['Ù','Ú','Ụ','Ủ','Ũ','Ư','Ừ','Ứ','Ự','Ử','Ữ'],
            'Y' => ['Ỳ','Ý','Ỵ','Ỷ','Ỹ'],
            'D' => ['Đ']
        ];
        foreach ($accents as $nonAccent => $accentChars) {
            $str = str_replace($accentChars, $nonAccent, $str);
        }
        return mb_strtolower($str, 'UTF-8');
    }
}
