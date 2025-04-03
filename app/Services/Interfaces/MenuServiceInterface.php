<?php

namespace App\Services\Interfaces;
use App\Services\MenuService;
/**
 * Interface MenuServiceInterface
 * @package App\Services\Interfaces
 */
interface MenuServiceInterface
{
    public function paginate($request,$languageId);

}   
