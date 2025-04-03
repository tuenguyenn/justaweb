<?php

namespace App\Repositories\Interfaces;
/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
interface CustomerRepositoryInterface
{
    public function pagination(
        array $columns = ['*'],
        array $condition = [],
        array $join = [],
        array $extend =[],

        $perpage ,
    );
}
