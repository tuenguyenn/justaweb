<?php

namespace App\Services\Interfaces;
use App\Services\CustomerService;
/**
 * Interface CustomerServiceInterface
 * @package App\Services\Interfaces
 */
interface CustomerServiceInterface
{
    public function paginate($request);

}   
