<?php

namespace App\Repositories\Interfaces;
use App\Models\User;
/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
interface UserRepositoryInterface
{
    public function pagination(
        array $columns = ['*'],
        array $condition = [],
        array $join = [],
        array $extend =[],

        $perpage ,
    );
}
