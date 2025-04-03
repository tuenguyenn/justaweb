<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueriesScope;

class ProductCatalogue extends Model
{
    use HasFactory, SoftDeletes,QueriesScope;
    protected $guarded = [];

    protected $table ='product_catalogues';
    public function languages(){
        return $this->belongsToMany( Language::class, 'product_catalogue_language','product_catalogue_id','language_id')
        ->withPivot('name',
                    'canonical',
                    'meta_title',
                    'meta_keyword',
                    'meta_description',
                    'description',
                    'content')->withTimestamps();
    } 
    public function products(){
        return $this->belongsToMany(Product::class,'product_catalogue_product','product_catalogue_id','product_id');
    }
    public function product_catalogue_language(){
        return $this->hasMany(ProductCatalogueLanguage::class,'product_catalogue_id','id');
    }
    protected $cast =[
        'attribute'=> 'json',
    ];
    public static function isChildrenNode($id = 0)
    {
        $productCatalogue = ProductCatalogue::find($id);
    
        if (!$productCatalogue) {
            return false;
        }
        if ($productCatalogue->rgt - $productCatalogue->lft > 1) {
            return false;
        }
    
        return true;
    }
    
}