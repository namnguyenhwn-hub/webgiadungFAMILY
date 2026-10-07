<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Administrator (Mặc định: admin@gmail.com / 123456)
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Quản Trị Viên (Admin)',
                'password' => bcrypt('123456'),
                'role' => 'admin',
                'phone' => '0909888999',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // Seed Standard Customer
        User::updateOrCreate(
            ['email' => 'khachhang@gmail.com'],
            [
                'name' => 'Nguyễn Hoàng Long',
                'password' => bcrypt('user123'),
                'role' => 'user',
                'phone' => '0912345678',
                'status' => 'active',
            ]
        );

        // Seed Initial Categories
        $categories = [
            ['name' => 'Thiết bị nấu nướng', 'sort_order' => 1, 'slug' => 'thiet-bi-nau-nuong'],
            ['name' => 'Bảo quản & Làm mát', 'sort_order' => 2, 'slug' => 'bao-quan-lam-mat'],
            ['name' => 'Làm sạch thông minh', 'sort_order' => 3, 'slug' => 'lam-sach-thong-minh'],
            ['name' => 'Gia dụng thông minh', 'sort_order' => 4, 'slug' => 'gia-dung-thong-minh'],
            ['name' => 'Chăm sóc cá nhân', 'sort_order' => 5, 'slug' => 'cham-soc-ca-nhan'],
            ['name' => 'Đồ dùng nhà bếp', 'sort_order' => 6, 'slug' => 'do-dung-nha-bep'],
            ['name' => 'Điện máy lớn', 'sort_order' => 7, 'slug' => 'dien-may-lon'],
            ['name' => 'Phụ kiện', 'sort_order' => 8, 'slug' => 'phu-kien'],
        ];

        foreach ($categories as $cat) {
            \App\Models\Category::updateOrCreate(['name' => $cat['name']], $cat);
        }

        // Seed Initial Products
        $products = [
            [
                'name' => 'Nồi chiên không dầu OLED Master Pro 7.5L',
                'category' => 'Thiết bị nấu nướng',
                'price' => 4890000,
                'stock' => 32,
                'sold' => 148,
                'status' => 'Còn hàng',
                'image' => 'air_fryer.jpg',
                'description' => 'Công nghệ nhiệt đối lưu Dual-Heat 360 độ, màn hình cảm ứng OLED viền kim loại vàng.',
            ],
            [
                'name' => 'Tủ lạnh thông minh French-Door AI Pure 620L',
                'category' => 'Bảo quản & Làm mát',
                'price' => 48900000,
                'stock' => 9,
                'sold' => 26,
                'status' => 'Còn hàng',
                'image' => 'smart_fridge.jpg',
                'description' => 'Cửa 4 cánh độc lập, màn hình cảm ứng kết nối WiFi, làm lạnh khử khuẩn Ion Plasma.',
            ],
            [
                'name' => 'Bếp từ đôi cảm ứng Inverter Germany 4400W',
                'category' => 'Thiết bị nấu nướng',
                'price' => 18500000,
                'stock' => 16,
                'sold' => 64,
                'status' => 'Còn hàng',
                'image' => 'induction_hob.jpg',
                'description' => 'Mặt kính Schott Ceran vát cạnh mạ vàng, công nghệ Booster nấu siêu tốc.',
            ],
            [
                'name' => 'Robot hút bụi lau nhà Laser Omni AI Tự Giặt Giẻ',
                'category' => 'Làm sạch thông minh',
                'price' => 15990000,
                'stock' => 24,
                'sold' => 89,
                'status' => 'Còn hàng',
                'image' => 'robot_vacuum.jpg',
                'description' => 'Hệ thống radar LiDAR 3D, lực hút cực đại 8000Pa, trạm sạc tự giặt sấy khí nóng.',
            ],
            [
                'name' => 'Nồi cơm điện cao tần IH Lòng Niêu Kim Cương 1.8L',
                'category' => 'Thiết bị nấu nướng',
                'price' => 8200000,
                'stock' => 20,
                'sold' => 112,
                'status' => 'Còn hàng',
                'image' => 'rice_cooker.jpg',
                'description' => 'Gia nhiệt đa chiều IH cảm ứng từ, lòng nồi niêu 8 lớp phủ kim cương nhân tạo.',
            ],
            [
                'name' => 'Máy rửa bát độc lập sấy khí nóng Zeolite FAMILY 16 bộ',
                'category' => 'Làm sạch thông minh',
                'price' => 24890000,
                'stock' => 15,
                'sold' => 45,
                'status' => 'Còn hàng',
                'image' => 'air_fryer.jpg',
                'description' => 'Dung tích 16 bộ chuẩn Châu Âu, công nghệ sấy Zeolite tự nhiên, khử khuẩn tia UV-C 99.9%.',
            ],
            [
                'name' => 'Lò nướng hấp đa năng âm tủ FAMILY Steam-Pro 72L',
                'category' => 'Thiết bị nấu nướng',
                'price' => 19990000,
                'stock' => 12,
                'sold' => 38,
                'status' => 'Còn hàng',
                'image' => 'induction_hob.jpg',
                'description' => 'Dung tích cực lớn 72L, kết hợp nướng đối lưu và hấp siêu nhiệt 120°C, cửa kính cách nhiệt 4 lớp.',
            ],
            [
                'name' => 'Máy lọc nước điện giải ion kiềm Hydrogen FAMILY Hydro-Gold',
                'category' => 'Bảo quản & Làm mát',
                'price' => 28500000,
                'stock' => 18,
                'sold' => 52,
                'status' => 'Còn hàng',
                'image' => 'smart_fridge.jpg',
                'description' => '9 tấm điện cực Titanium phủ Platinum nguyên khối, tạo nước kiềm giàu Hydro chống oxy hóa pH 3.5 - 10.5.',
            ],
        ];

        foreach ($products as $p) {
            \App\Models\Product::updateOrCreate(['name' => $p['name']], $p);
        }

        // Seed Initial Orders
        $orders = [
            [
                'order_code' => 'AL-9842',
                'customer_name' => 'Nguyễn Hoàng Long',
                'customer_email' => 'khachhang@gmail.com',
                'customer_phone' => '0912 345 678',
                'product_name' => 'Nồi chiên không dầu OLED Master Pro 7.5L',
                'amount' => 4890000,
                'payment_method' => 'Chuyển khoản VNPAY',
                'status' => 'Đang giao hàng',
                'notes' => 'Giao hàng giờ hành chính tại Cầu Giấy',
            ],
            [
                'order_code' => 'AL-9841',
                'customer_name' => 'Trần Minh Tâm',
                'customer_email' => 'minhtam.tran@gmail.com',
                'customer_phone' => '0988 776 554',
                'product_name' => 'Tủ lạnh thông minh French-Door AI Pure 620L',
                'amount' => 48900000,
                'payment_method' => 'Thẻ tín dụng Visa',
                'status' => 'Đã hoàn thành',
                'notes' => 'Đã hỗ trợ lắp đặt tại Royal City',
            ],
            [
                'order_code' => 'AL-9840',
                'customer_name' => 'Lê Thu Trang',
                'customer_email' => 'thutrang.le@outlook.com',
                'customer_phone' => '0903 112 233',
                'product_name' => 'Bếp từ đôi cảm ứng Inverter Germany 4400W',
                'amount' => 18500000,
                'payment_method' => 'COD (Thanh toán khi nhận)',
                'status' => 'Chờ xác nhận',
                'notes' => 'Gọi trước khi giao 30 phút',
            ],
            [
                'order_code' => 'AL-9839',
                'customer_name' => 'Đặng Quang Huy',
                'customer_email' => 'quanghuy.dang@fpt.com.vn',
                'customer_phone' => '0977 889 900',
                'product_name' => 'Robot hút bụi lau nhà Laser Omni AI Tự Giặt Giẻ',
                'amount' => 15990000,
                'payment_method' => 'Chuyển khoản ngân hàng',
                'status' => 'Đang giao hàng',
                'notes' => 'Kiểm tra kỹ phụ kiện kèm theo',
            ],
        ];

        foreach ($orders as $o) {
            \App\Models\Order::updateOrCreate(['order_code' => $o['order_code']], $o);
        }
    }
}
