<?php

namespace App\Services\Interfaces;
use App\Services\UserService;
/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
interface UserServiceInterface
{
    public function paginate($request);

}   
