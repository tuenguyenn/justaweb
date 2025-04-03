<?php

namespace App\Repositories;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Order;

/**
 * Class OrderCatalogueService
 * @package App\Services
 */
class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    protected $model  ;
    public function __construct(Order $model)
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
        array $orderBy = ['orders.id','desc'],
        array $rawQuery =[],
        
    )
    {
        $query = $this->model->select($columns);
       
        return $query->keyword($condition['keyword'] ?? null,['fullname','phone','email','address','code'],['field'=> 'name','relation' =>'products'])
                ->customWhereCondition($condition['dropdown'] ?? null)
                ->customWhereCustomer($condition['customer_id'] ?? null)
                ->CustomWhere($condition['where'] ?? null)
                ->CustomWhereRaw($rawQuery ?? null) 
                ->relationCount($relations ?? null)
                ->CustomJoin($join ?? null)
                ->CustomGroupBy($extend['groupBy'] ?? null)
                ->CustomOrderBy($orderBy ?? null)
                ->customConditionCreated($condition['created_at'] ?? null)
                ->paginate($perpage)
                ->withQueryString()->withPath(env('APP_URl').$extend['path']);
                ;

    }
    
   
   


}
