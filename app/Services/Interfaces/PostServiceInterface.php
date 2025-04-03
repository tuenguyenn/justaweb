<?php

namespace App\Services\Interfaces;
use App\Services\PostService;
/**
 * Interface PostServiceInterface
 * @package App\Services\Interfaces
 */
interface PostServiceInterface
{
    public function paginate($request);

}   
