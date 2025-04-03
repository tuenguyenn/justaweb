<?php

namespace App\Repositories;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\User;
/**
 * Class UserService
 * @package App\Services
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $model  ;
    public function __construct(User $model)
    {
        $this->model = $model ;
        
    }
    public function pagination(
        array $columns = ['*'],
        array $condition = [],
        array $join = [],
        array $extend =[],
        $perpage ,
        array $relations = [],
        array $orderBy = ['id','desc'],
        array $rawQuery = []
    )
    {
        $query = $this->model->select($columns)->where(function($query) use ($condition){
            if(isset($condition['keyword']) && !empty($condition['keyword'])){
                $query->where('name','like','%'.$condition['keyword'].'%')
                ->orWhere('email','like','%'.$condition['keyword'].'%')
                ->orWhere('phone','like','%'.$condition['keyword'].'%')
                ->orWhere('address','like','%'.$condition['keyword'].'%')
;
            }
            if(isset($condition['publish']) && ($condition['publish'])!= 0){
                $query->where('publish','=', $condition['publish']);
            }
            if(isset($condition['user_catalogue_id']) && ($condition['user_catalogue_id'])!= 0){
                $query->where('user_catalogue_id','=', $condition['user_catalogue_id']);
            }
            return $query;
        })->with('user_catalogues');
    
       
        if (!empty($join)) {
            foreach ($join as $joinTable) {
            }
        }
        if (!empty($orderBy)) {
            $query->orderBy($orderBy[0],$orderBy[1]);
        }
    
        return $query->paginate($perpage)
            ->withQueryString()->withPath(env('APP_URl').$extend['path']);
    }
  
   


}
