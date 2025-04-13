<?php

namespace App\Repositories;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Customer;
/**
 * Class CustomerService
 * @package App\Services
 */
class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    protected $model  ;
    public function __construct(Customer $model)
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
            if(isset($condition['customer_catalogue_id']) && ($condition['customer_catalogue_id'])!= 0){
                $query->where('customer_catalogue_id','=', $condition['customer_catalogue_id']);
            }
            return $query;
        })->with('customer_catalogues');
    
       
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
    public function getCustomerCatalogue(){
        $query = $this->model->newQuery();
        $query->select([
            'tb2.name',
            'tb2.id',
        ]);
        $query->leftJoin('customer_catalogues as tb2', 'tb2.id', '=', 'customers.customer_catalogue_id');
        $query->orderBy('tb2.id','asc');
        return $query->get();
    }
    
  public function getAllCustomer(){
    return $this->model->count();
  }
   


}
