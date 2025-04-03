<?php

namespace App\Repositories;
use App\Repositories\Interfaces\CartItemRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\CartItem;
/**
 * Class CartItemService
 * @package App\Services
 */
class CartItemRepository extends BaseRepository implements CartItemRepositoryInterface
{
    protected $model  ;
    public function __construct(CartItem $model)
    {
        $this->model = $model ;
        
    }
   
    
  
   


}
