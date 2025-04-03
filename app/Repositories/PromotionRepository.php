<?php

namespace App\Repositories;
use App\Repositories\Interfaces\PromotionRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Promotion;

/**
 * Class PromotionCatalogueService
 * @package App\Services
 */
class PromotionRepository extends BaseRepository implements PromotionRepositoryInterface
{
    protected $model  ;
    public function __construct(Promotion $model)
    {
        $this->model = $model ;
        
    }
    
    public function findByProduct($productId= []){
            
            return $this->model->select(
                // 'promotions.id as promotion_id' ,
                'promotions.discountValue',
                'promotions.discountType',
                // 'promotions.maxDiscountValue',
                'products.id as product_id',
                'products.price as product_price',
                )
                ->selectRaw("
                    MAX(
                    IF(promotions.maxDiscountValue !=0,
                                        LEAST(
                                        CASE
                                            WHEN discountType = 'cash' THEN  discountValue 
                                            WHEN discountType = 'percent' THEN products.price * discountValue / 100
                                            ELSE 0
                                        END,  
                                        promotions.maxDiscountValue
                                        
                                        ),
                                    
                                    CASE
                                        WHEN discountType = 'cash' THEN  discountValue
                                        WHEN discountType = 'percent' THEN  products.price * discountValue/100
                                        ELSE 0
                                        END
                                    
                    )
                                        )
                     as discountPrice
                ")
                ->join('promotion_product_variant as ppv', 'ppv.promotion_id', '=', 'promotions.id')
                ->join('products','products.id','=','ppv.product_id')
                ->where('products.publish',2)
                ->where('promotions.publish',2)
                ->whereIn('products.id',$productId)
                ->whereDate('promotions.endDate','>',now())
                ->groupBy(
                    'products.id',
                    // 'promotions.id',
                    'promotions.discountValue',
                    'promotions.discountType',
                    // 'promotions.maxDiscountValue',
                   
                )
                ->orderBy('discountPrice','desc')
                ->get();
                
    }
    public function findPromotionProductVariantUuid($uuid){
        return $this->model->select(
            'promotions.discountValue',
            'promotions.discountType',
           
            )
            ->selectRaw("
                MAX(
                IF(promotions.maxDiscountValue !=0,
                                    LEAST(
                                    CASE
                                        WHEN discountType = 'cash' THEN  discountValue 
                                        WHEN discountType = 'percent' THEN product_variants.price * discountValue / 100
                                        ELSE 0
                                    END,  
                                    promotions.maxDiscountValue
                                    
                                    ),
                                
                                CASE
                                    WHEN discountType = 'cash' THEN  discountValue
                                    WHEN discountType = 'percent' THEN  product_variants.price * discountValue/100
                                    ELSE 0
                                    END
                                
                )
                                    )
                 as discountPrice
            ")
            ->join('promotion_product_variant as ppv', 'ppv.promotion_id', '=', 'promotions.id')
            ->join('product_variants','product_variants.uuid','=','ppv.variant_uuid')
            ->where('promotions.publish',2)
            ->where('ppv.variant_uuid',$uuid)
            ->whereDate('promotions.endDate','>',now())
            ->groupBy(
                
                'promotions.discountValue',
                'promotions.discountType',
               
            )
            ->orderBy('discountPrice','desc')
            ->first();
    }
    public function getPromotionByCartTotal(){
        return $this->model->where('method','order_amount_range')
        ->where('publish',2)
        ->whereDate('endDate','>',now())
        ->get();
    }
    

}
