<?php

namespace App\Repositories;
use App\Repositories\Interfaces\ProductVariantAttributeRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\ProductVariantAttribute;
/**
 * Class ProductVariantAttributeService
 * @package App\Services
 */
class ProductVariantAttributeRepository extends BaseRepository implements ProductVariantAttributeRepositoryInterface
{
    protected $model  ;
    public function __construct(ProductVariantAttribute $model)
    {
        $this->model = $model ;
        
    }
  
    
    
   
   


}
