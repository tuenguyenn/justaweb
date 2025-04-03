<?php

namespace App\Repositories\Interfaces;
use App\Models\User;
/**
 * Interface DistrictServiceInterface
 * @package App\Services\Interfaces
 */
interface DistrictRepositoryInterface
{
    public function all();
    public function getDistrictsByProvinceId(int $province_id);
    public function findById(int $id);

}
