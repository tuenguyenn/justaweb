<?php

namespace App\Services\Interfaces;
use App\Services\PostCatalogueService;
/**
 * Interface PostCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface PostCatalogueServiceInterface
{
    public function paginate($request);

}   
