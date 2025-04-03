<?php

namespace App\Services\Interfaces;
use App\Services\ProductCatalogueService;
/**
 * Interface ProductCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface ProductCatalogueServiceInterface
{
    public function paginate($request);

}   
