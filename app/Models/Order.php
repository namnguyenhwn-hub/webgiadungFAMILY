<?php 

namespace App\Models; 

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model; 

class Order extends Model 
{ 
    use HasFactory; 

    // Cho phép gán dữ liệu hàng loạt vào các cột này 
    protected $fillable = [ 
        'user_id',  
        'total',  
        'status',  
        'payment_method',
        'payment_status',
        'order_code',
        'receiver_name',
        'customer_name',
        'customer_email',
        'customer_phone',
        'phone_number',
        'shipping_address',
        'product_name',
        'amount',
        'note',
        'notes',
        'sepay_transaction_id',
        'sepay_reference_code',
        'sepay_bank_account',
        'paid_at'
    ]; 

    // Quan hệ: Một Đơn hàng (Order) có nhiều Chi tiết đơn hàng (OrderItem) 
    public function items() 
    { 
        return $this->hasMany(OrderItem::class); 
    } 

    // Quan hệ: Một Đơn hàng thuộc về một Người dùng (User) 
    public function user() 
    { 
        return $this->belongsTo(User::class); 
    } 
}
