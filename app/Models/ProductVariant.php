<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\QueriesScope;

class ProductVariant extends Model
{
    use HasFactory,QueriesScope;
    protected $guarded = [];
    protected $table = 'product_variants';

    public function products(){
        return $this->belongsToMany(Product::class,'product_id','id');
    }
    public function languages(){
        return $this->belongsToMany(Language::class, 'product_variant_language','product_variant_id','language_id')
        ->withPivot('name')->withTimestamps();
    } 
    public function attributes(){
        return $this->belongsToMany(Attribute::class, 'product_variant_attribute','product_variant_id','attribute_id')
        ->withTimestamps();
    }
    

}
