<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'stock')) {
                $table->integer('stock')->default(10)->after('quantity');
            }
            if (!Schema::hasColumn('products', 'sold')) {
                $table->integer('sold')->default(0)->after('stock');
            }
            if (!Schema::hasColumn('products', 'status')) {
                $table->string('status', 50)->default('Còn hàng')->after('sold');
            }
            if (!Schema::hasColumn('products', 'category')) {
                $table->string('category', 255)->nullable()->after('category_id');
            }
        });

        // Đồng bộ dữ liệu hiện có
        // Không chạy câu lệnh UPDATE này vì cột quantity không tồn tại
        // \Illuminate\Support\Facades\DB::statement("
        //     UPDATE products 
        //     SET stock = quantity 
        //     WHERE stock IS NULL OR stock = 0
        // ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['stock', 'sold', 'status', 'category'] as $col) {
                if (Schema::hasColumn('products', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
