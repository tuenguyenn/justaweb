<?php

namespace App\Repositories;
use App\Repositories\Interfaces\ReviewRepositoryInterface;
use App\Repositories\BaseRepository;

use App\Models\Review;
/**
 * Class ReviewService
 * @package App\Services
 */
class ReviewRepository extends BaseRepository implements ReviewRepositoryInterface
{   
    protected $model  ;
    public function __construct(Review $model)
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
        array $rawQuery =[],
        
    )
    {
        $query = $this->model->select($columns);
        
        return $query->keyword($condition['keyword'] ?? null ,['reviews.description'])
                ->publish($condition['publish'] ?? null)
                ->CustomWhere($condition['where'] ?? null)
                ->CustomWhereRaw($rawQuery ?? null) 
                ->relationCount($relations ?? null)
                ->CustomJoin($join ?? null)
                ->CustomGroupBy($extend['groupBy'] ?? null)
                ->CustomOrderBy($orderBy ?? null)
                ->paginate($perpage)
                ->withQueryString()->withPath(env('APP_URl').$extend['path']);
                ;

    }
  


   

}
