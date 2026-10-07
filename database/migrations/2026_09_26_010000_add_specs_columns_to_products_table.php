<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'brand')) {
                $table->string('brand', 255)->nullable()->after('category');
            }
            if (!Schema::hasColumn('products', 'origin')) {
                $table->string('origin', 255)->nullable()->after('brand');
            }
            if (!Schema::hasColumn('products', 'material')) {
                $table->string('material', 255)->nullable()->after('origin');
            }
            if (!Schema::hasColumn('products', 'usage')) {
                $table->text('usage')->nullable()->after('material');
            }
        });

        // Cập nhật dữ liệu thông số cho các sản phẩm hiện có
        $products = DB::table('products')->get();
        foreach ($products as $p) {
            $updates = [];
            $desc = $p->description ?? '';
            $nameLower = mb_strtolower($p->name, 'UTF-8');

            // 1. Phân tích nội dung mô tả hiện tại nếu có
            if (preg_match('/Thương hiệu\s*:\s*([^\r\n]+)/iu', $desc, $m)) {
                $updates['brand'] = trim($m[1]);
            }
            if (preg_match('/Xuất xứ\s*:\s*([^\r\n]+)/iu', $desc, $m)) {
                $updates['origin'] = trim($m[1]);
            }

            // 2. Điền bổ sung theo tên thiết bị nếu còn trống
            if (empty($updates['brand'])) {
                if (str_contains($nameLower, 'philips')) $updates['brand'] = 'Philips';
                elseif (str_contains($nameLower, 'sunhouse')) $updates['brand'] = 'Sunhouse';
                elseif (str_contains($nameLower, 'kangaroo')) $updates['brand'] = 'Kangaroo';
                elseif (str_contains($nameLower, 'elmix') || str_contains($nameLower, 'elimix')) $updates['brand'] = 'Elimix';
                elseif (str_contains($nameLower, 'family') || str_contains($nameLower, 'aura')) $updates['brand'] = 'FAMILY Luxury';
                else $updates['brand'] = 'Chính hãng';
            }

            if (empty($updates['origin'])) {
                if (str_contains($nameLower, 'philips')) $updates['origin'] = 'Hà Lan / Trung Quốc';
                elseif (str_contains($nameLower, 'sunhouse')) $updates['origin'] = 'Việt Nam';
                elseif (str_contains($nameLower, 'kangaroo')) $updates['origin'] = 'Việt Nam';
                elseif (str_contains($nameLower, 'elmix') || str_contains($nameLower, 'elimix')) $updates['origin'] = 'Nigeria';
                elseif (str_contains($nameLower, 'tủ lạnh')) $updates['origin'] = 'Nhật Bản';
                elseif (str_contains($nameLower, 'bếp từ')) $updates['origin'] = 'Đức';
                elseif (str_contains($nameLower, 'nồi cơm')) $updates['origin'] = 'Nhật Bản';
                elseif (str_contains($nameLower, 'robot')) $updates['origin'] = 'Hàn Quốc';
                else $updates['origin'] = 'Việt Nam';
            }

            if (empty($updates['material'])) {
                if (str_contains($nameLower, 'chiên')) {
                    $updates['material'] = 'Thép không gỉ 304, lòng nồi tráng men chống dính Ceramic';
                } elseif (str_contains($nameLower, 'bếp') || str_contains($nameLower, 'từ')) {
                    $updates['material'] = 'Mặt kính cường lực Schott Ceran, viền hợp kim nhôm cách điện';
                } elseif (str_contains($nameLower, 'tủ lạnh')) {
                    $updates['material'] = 'Mặt kính tráng gương đen, thân thép không gỉ tĩnh điện';
                } elseif (str_contains($nameLower, 'cơm')) {
                    $updates['material'] = 'Lòng nồi hợp kim 8 lớp phủ kim cương Kamado, vỏ nhựa cách nhiệt';
                } elseif (str_contains($nameLower, 'robot')) {
                    $updates['material'] = 'Nhựa ABS nguyên sinh chống va đập, radar hợp kim';
                } else {
                    $updates['material'] = 'Hợp kim cao cấp, nhựa ABS an toàn cho sức khỏe';
                }
            }

            if (empty($updates['usage'])) {
                if (str_contains($nameLower, 'chiên')) {
                    $updates['usage'] = 'Chiên, nướng, quay thực phẩm không dầu; giảm 85% mỡ thừa, rã đông tự động';
                } elseif (str_contains($nameLower, 'bếp') || str_contains($nameLower, 'từ')) {
                    $updates['usage'] = 'Nấu ăn gia đình siêu tốc, điều khiển cảm ứng, hẹn giờ và chống tràn an toàn';
                } elseif (str_contains($nameLower, 'tủ lạnh')) {
                    $updates['usage'] = 'Bảo quản rau củ tươi ngon, cấp đông mềm không cần rã đông, làm đá tự động';
                } elseif (str_contains($nameLower, 'cơm')) {
                    $updates['usage'] = 'Nấu cơm cao tần dẻo thơm chuẩn vị Nhật, nấu cháo, ninh xương dinh dưỡng';
                } elseif (str_contains($nameLower, 'robot')) {
                    $updates['usage'] = 'Tự động quét nhà, hút bụi 6000Pa và lau sàn rung siêu âm, tự giặt sấy giẻ';
                } else {
                    $updates['usage'] = 'Thiết bị gia dụng phục vụ nấu nướng và tiện ích thông minh cho gia đình';
                }
            }

            if (!empty($updates)) {
                DB::table('products')->where('id', $p->id)->update($updates);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $cols = [];
            foreach (['brand', 'origin', 'material', 'usage'] as $col) {
                if (Schema::hasColumn('products', $col)) {
                    $cols[] = $col;
                }
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
