<?php

namespace App\Repositories;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Permission;
/**
 * Class PermissionService
 * @package App\Services
 */
class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    protected $model  ;
    public function __construct(Permission $model)
    {
        $this->model = $model ;
        
    }
   
  
  
  
   


}
