<?php 

namespace App\Models; 

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model; 

class OrderItem extends Model 
{ 
    use HasFactory; 

    // Cho phép gán dữ liệu hàng loạt 
    protected $fillable = [ 
        'order_id',  
        'product_id',  
        'quantity',  
        'price' 
    ]; 

    // Quan hệ: Chi tiết đơn hàng thuộc về 1 Đơn hàng 
    public function order() 
    { 
        return $this->belongsTo(Order::class); 
    } 

    // Quan hệ: Chi tiết đơn hàng liên kết tới 1 Sản phẩm 
    public function product() 
    { 
        return $this->belongsTo(Product::class); 
    } 
}
