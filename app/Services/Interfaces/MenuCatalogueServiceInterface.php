<?php

namespace App\Services\Interfaces;
use App\Services\MenuCatalogueService;
/**
 * Interface MenuCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface MenuCatalogueServiceInterface
{
    public function paginate($request,$languageId);

}   
