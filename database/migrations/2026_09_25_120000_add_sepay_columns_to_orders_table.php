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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'order_code')) {
                $table->string('order_code')->nullable()->unique();
            }
            if (!Schema::hasColumn('orders', 'sepay_transaction_id')) {
                $table->string('sepay_transaction_id')->nullable();
            }
            if (!Schema::hasColumn('orders', 'sepay_reference_code')) {
                $table->string('sepay_reference_code')->nullable();
            }
            if (!Schema::hasColumn('orders', 'sepay_bank_account')) {
                $table->string('sepay_bank_account')->nullable();
            }
            if (!Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['order_code', 'sepay_transaction_id', 'sepay_reference_code', 'sepay_bank_account', 'paid_at'] as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
