<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueriesScope;

class OrderPayment extends Model
{
    use HasFactory,SoftDeletes,QueriesScope;
    protected $guarded = [];
   
    protected $table ='order_paymentable';
    public function orders(){
        return $this->belongsTo(Order::class,'order_id','id');
    }
  
  
}
