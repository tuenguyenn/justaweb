<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\QueriesScope;

class Order extends Model
{
    use HasFactory,QueriesScope;
    protected $guarded = [];
    protected $casts= [
        'cart'=> 'json',
        'promotion' => 'json',

    ];
    public function products(){
        return $this->belongsToMany(Product::class, 'order_product','order_id','product_id')
        ->withPivot('uuid',
                    'name',
                    'qty',
                    'price',
                    'priceOriginal',
                    'promotion',
                    'option');
    } 
    public function order_payment(){
        return $this->hasMany(OrderPayment::class, 'order_id','id' );
    }
   
  
  
}
