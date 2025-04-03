<?php

namespace App\Services\Interfaces;
use App\Services\CustomerCatalogueService;
/**
 * Interface CustomerCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface CustomerCatalogueServiceInterface
{
    public function paginate($request);

}   
