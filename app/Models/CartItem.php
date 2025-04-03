<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    protected $table = 'cart_items';
    public $incrementing = false; // Tắt auto-increment
    protected $keyType = 'string'; // Kiểu dữ liệu của `id`

    protected $fillable = [
        'id','rowId', 'customer_id', 'qty', 'name', 'price', 'options'
    ];

    protected $casts = [
        'options' => 'array', // Tự động decode JSON
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
