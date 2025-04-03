<?php

namespace App\Services\Interfaces;
use App\Services\ProductService;
/**
 * Interface ProductServiceInterface
 * @package App\Services\Interfaces
 */
interface ProductServiceInterface
{
    public function paginate($request);

}   
