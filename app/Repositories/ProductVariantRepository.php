<?php

namespace App\Repositories;
use App\Repositories\Interfaces\ProductVariantRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\ProductVariant;
/**
 * Class ProductVariantService
 * @package App\Services
 */
class ProductVariantRepository extends BaseRepository implements ProductVariantRepositoryInterface
{
    protected $model  ;
    public function __construct(ProductVariant $model)
    {
        $this->model = $model ;
        
    }
    public function  findVariant($code ,$productId, $language){
        return $this->model->where(
            ['code' => $code, 'product_id' => $productId]
        )
        ->with('languages' ,function ($query) use ($language){
            $query->where('language_id', $language);
        })
        ->first();
    }
    
    
   
   


}
