<?php

namespace App\Services\Interfaces;
use App\Services\UserCatalogueService;
/**
 * Interface UserCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface UserCatalogueServiceInterface
{
    public function paginate($request);

}   
