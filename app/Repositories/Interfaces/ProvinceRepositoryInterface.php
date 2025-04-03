<?php

namespace App\Repositories\Interfaces;
use App\Models\User;
/**
 * Interface ProvinceServiceInterface
 * @package App\Services\Interfaces
 */
interface ProvinceRepositoryInterface
{
    public function all();
    public function findById(int $modelId, array $columns = ["*"], array $relations = []);

}
