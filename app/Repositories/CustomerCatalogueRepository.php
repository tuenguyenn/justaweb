<?php

namespace App\Repositories;
use App\Repositories\Interfaces\CustomerCatalogueRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\CustomerCatalogue;
use Illuminate\Support\Facades\Log;

/**
 * Class CustomerService
 * @package App\Services
 */
class CustomerCatalogueRepository extends BaseRepository implements CustomerCatalogueRepositoryInterface
{
    protected $model  ;
    public function __construct(CustomerCatalogue $model)
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
    public function getCustomerCatalogue(){
        $query = $this->model->newQuery();
        $query->select([
            'name',
            'id',
        ]);
        $query->orderBy('id','asc');
        return $query->get();
    }
    
    
  
   


}
