<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\QueriesScope;

class Promotion extends Model
{
    use HasFactory,QueriesScope;
    protected $guarded = [];

    protected $table = 'promotions';
    protected $casts = [
        'discountInformation' => 'json'
    ];
    public function products() {
        return $this->belongsToMany(Product::class, 'promotion_product_variant', 'promotion_id', 'product_id')
            ->withPivot('model');
    }
    

}
