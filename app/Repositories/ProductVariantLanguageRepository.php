<?php

namespace App\Repositories;
use App\Repositories\Interfaces\ProductVariantLanguageRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\ProductVariantLanguage;
/**
 * Class ProductVariantLanguageService
 * @package App\Services
 */
class ProductVariantLanguageRepository extends BaseRepository implements ProductVariantLanguageRepositoryInterface
{
    protected $model  ;
    public function __construct(ProductVariantLanguage $model)
    {
        $this->model = $model ;
        
    }
  
    
    
   
   


}
