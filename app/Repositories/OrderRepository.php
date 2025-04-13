<?php

namespace App\Repositories;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Order;

use Illuminate\Support\Facades\DB;
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
   
   public function getOrderStatistics($fromDate, $toDate)
   {
       return [
           'total_orders' => $this->model->whereBetween('created_at', [$fromDate, $toDate])->count(),
   
           'total_success' => $this->model->whereBetween('created_at', [$fromDate, $toDate])
                                   ->where('delivery', 'success')->count(),
   
           'total_cancelled' => $this->model->whereBetween('created_at', [$fromDate, $toDate])
                                     ->whereNotNull('canceled_at')->count(),
   
           'total_pending' => $this->model->whereBetween('created_at', [$fromDate, $toDate])
                                   ->where('confirm', 'pending')->count(),
   
        
           'total_revenue' => $this->model->whereBetween('created_at', [$fromDate, $toDate])
                                   ->where('delivery', 'success') 
                                   ->sum('total_amount'), 
       ];
   }
   public function getRevenueBetween($fromDate, $toDate, $groupBy)
   {
       $selects = [
           DB::raw("SUM(CASE WHEN delivery = 'success' THEN total_amount ELSE 0 END) as total_revenue")
       ];
   
       switch ($groupBy) {
           case 'DATE':
               $selects[] = DB::raw("DATE(created_at) as date");
               $groupByRaw = "DATE(created_at)";
               break;
   
           case 'WEEK':
               $selects[] = DB::raw("YEAR(created_at) as year");
               $selects[] = DB::raw("WEEK(created_at, 1) as week");
               $groupByRaw = "YEAR(created_at), WEEK(created_at, 1)";
               break;
   
           case 'MONTH':
               $selects[] = DB::raw("YEAR(created_at) as year");
               $selects[] = DB::raw("MONTH(created_at) as month");
               $groupByRaw = "YEAR(created_at), MONTH(created_at)";
               break;
   
           case 'QUARTER':
               $selects[] = DB::raw("YEAR(created_at) as year");
               $selects[] = DB::raw("QUARTER(created_at) as quarter");
               $groupByRaw = "YEAR(created_at), QUARTER(created_at)";
               break;
   
           default: // fallback to DATE
               $selects[] = DB::raw("DATE(created_at) as date");
               $groupByRaw = "DATE(created_at)";
               break;
       }
   
       return $this->model
           ->select($selects)
           ->whereBetween('created_at', [$fromDate, $toDate])
           ->groupBy(DB::raw($groupByRaw))
           ->orderBy(DB::raw($groupByRaw))
           ->get();
   }
   
   
   

   
   
  
   
   


}
