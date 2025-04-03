<?php

namespace App\Repositories;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\UserCatalogue;
use Illuminate\Support\Facades\Log;

/**
 * Class UserService
 * @package App\Services
 */
class UserCatalogueRepository extends BaseRepository implements UserCatalogueRepositoryInterface
{
    protected $model  ;
    public function __construct(UserCatalogue $model)
    {
        $this->model = $model ;
        
    }
    public function pagination(
        array $columns = ['*'],
        array $condition = [],
        array $join = [],
        array $extend = [],
        $perpage,
        array $relations = [],
        array $orderBy = ['id','desc'],
        array $rawQuery = []

    ) {
        $query = $this->model->select($columns)->where(function ($query) use ($condition) {
            if (isset($condition['keyword']) && !empty($condition['keyword'])) {
                $query->where('name', 'like', '%' . $condition['keyword'] . '%');
            }
            if (isset($condition['publish']) && $condition['publish'] != 0) {
                $query->where('publish', '=', $condition['publish']);
            }
        });
    
        if (!empty($relations)) {
            foreach ($relations as $relation) {
                if (method_exists($this->model, $relation)) {
                    $query->withCount($relation);
                } else {
                    Log::warning("Quan hệ '$relation' không tồn tại trong " . get_class($this->model));
                }
            }
        }
    
        return $query->paginate($perpage)
            ->withQueryString()
            ->withPath(env('APP_URL') . ($extend['path'] ?? ''));
    }
    
  
   


}
