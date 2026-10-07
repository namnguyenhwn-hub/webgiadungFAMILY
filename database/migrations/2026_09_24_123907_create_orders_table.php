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
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                $table->decimal('total', 15, 2)->default(0);
                $table->string('order_code')->nullable()->unique();
                $table->string('receiver_name')->nullable();
                $table->string('customer_name')->nullable();
                $table->string('customer_email')->nullable();
                $table->string('customer_phone')->nullable();
                $table->string('phone_number')->nullable();
                $table->text('shipping_address')->nullable();
                $table->string('product_name')->nullable();
                $table->bigInteger('amount')->nullable();
                $table->string('payment_method')->default('COD');
                $table->string('status')->default('processing');
                $table->string('payment_status')->default('unpaid');
                $table->text('notes')->nullable();
                $table->text('note')->nullable();
                $table->string('sepay_transaction_id')->nullable();
                $table->string('sepay_reference_code')->nullable();
                $table->string('sepay_bank_account')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
