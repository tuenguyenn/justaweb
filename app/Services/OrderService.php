<?php
namespace App\Services;
use App\Services\Interfaces\OrderServiceInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface ;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;


/** 
 * Class OrderService
 * @package App\Services
 */
class OrderService implements OrderServiceInterface
{   
    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Phân trang người dùng
     *
     * @return mixed
     */
    
    public function paginate($request ,$customerId = null)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['dropdown'] =[
            'confirm'=>$request->string('confirm'),
            'payment'=>$request->string('payment'),
            'delivery'=>$request->string('delivery'),
            'method'=>$request->string('method'),
        ];
        $condition['created_at'] = $request->input('created_at');
        $path = ['path'=>'order/index'];
        if($customerId){
            $condition['customer_id'] = $customerId;
            $path = ['path'=>$customerId.'/order-history'];
        }
        $columns = ['*']; 
        $perpage = $request ->integer('perpage');
        $orders = $this->orderRepository->pagination($columns,$condition,[],$path, 10);
      
        return $orders;
    }
    public function update($id,$payload)
    {
        DB::beginTransaction();
        try {
            if(array_key_exists("confirm", $payload)){
                $payload['confirmed_at'] = now();
            }elseif(array_key_exists("delivery", $payload) && $payload['delivery'] == 'processing'){
                $payload['delivery_at'] = now();
            }elseif(array_key_exists("delivery", $payload) && $payload['delivery'] == 'success'){
                $payload['deliveried_success_at'] = now();
                $payload['payment'] = 'paid';
            };

            $order = $this->orderRepository->update($id, $payload);

            
            DB::commit();
            return [
                'status' => true,
                'message' => 'Cập nhật thành công',
                'order' => $order,
            ];
        } catch (\Exception $e) {
            DB::rollBack(); 
            echo $e->getMessage();die();
            return false;
        }
    }
    public function cancel($id,$request)
    {
        DB::beginTransaction();
        try {
            $payload =$request->except('_token');
            
            $payload['canceled_at'] = now();

            $order = $this->orderRepository->update($id, $payload);

            
            DB::commit();
            return true;
          
        } catch (\Exception $e) {
            DB::rollBack(); 
            echo $e->getMessage();die();
            return false;
        }
    }
   

    public function orderStatistics()
    {
        $now = now();
    
        // === Ngày ===
        $startToday = $now->copy()->startOfDay();
        $endToday = $now->copy()->endOfDay();
    
        $startYesterday = $now->copy()->subDay()->startOfDay();
        $endYesterday = $now->copy()->subDay()->endOfDay();
    
        // === Tuần ===
        $startThisWeek = $now->copy()->startOfWeek();
        $endThisWeek = $now->copy()->endOfWeek();
    
        $startLastWeek = $now->copy()->subWeek()->startOfWeek();
        $endLastWeek = $now->copy()->subWeek()->endOfWeek();
    
        // === Tháng ===
        $startThisMonth = $now->copy()->startOfMonth();
        $endThisMonth = $now->copy()->endOfMonth();
    
        $startLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endLastMonth = $now->copy()->subMonth()->endOfMonth();
    
        return [
            'day' => $this->compareGrowth($startToday, $endToday, $startYesterday, $endYesterday),
            'week' => $this->compareGrowth($startThisWeek, $endThisWeek, $startLastWeek, $endLastWeek),
            'month' => $this->compareGrowth($startThisMonth, $endThisMonth, $startLastMonth, $endLastMonth),
        ];
    }
        public function compareGrowth($startCurrent, $endCurrent, $startPrevious, $endPrevious)
        {
            $current = $this->orderRepository->getOrderStatistics($startCurrent, $endCurrent);
            $previous = $this->orderRepository->getOrderStatistics($startPrevious, $endPrevious);

            $orderGrowth = caculateGrowth($current['total_orders'], $previous['total_orders']);
            $revenueGrowth = caculateGrowth($current['total_revenue'], $previous['total_revenue']);

            return [
                'current' => $current,
                'previous' => $previous,
                'orderGrowth' => round($orderGrowth, 2),
                'revenueGrowth' => round($revenueGrowth, 2),
            ];
        }
        public function getRevenueChartData($range)
{
    switch ($range) {
        case '7days':
            return $this->revenueBy7Days();

        case '30days':
            return $this->revenueBy30Days();

        case 'this-month':
            return $this->revenueByWeek();

        case 'this-quarter':
            return $this->revenueByQuarter();

        case 'this-year':
            return $this->revenueByYear();

        default:
            return [];
    }
}

        
private function revenueBy7Days()
{
    $from = Carbon::now()->subDays(6)->startOfDay();
    $to = Carbon::now()->endOfDay();
    $groupBy = 'DATE';

    $rawData = $this->orderRepository
        ->getRevenueBetween($from, $to, $groupBy)
        ->keyBy(fn($item) => Carbon::parse($item->date)->format('d/m'));

    $result = [];
    for ($date = $from->copy(); $date <= $to; $date->addDay()) {
        $label = $date->format('d/m');
        $revenue = isset($rawData[$label]) ? (float)$rawData[$label]->total_revenue : 0;
        $result[] = [
            'label' => $label,
            'revenue' => $revenue
        ];
    }

    return $result;
}



private function revenueBy30Days()
{
    $from = Carbon::now()->subDays(29)->startOfDay();
    $to = Carbon::now()->endOfDay();
    $groupBy = 'DATE';

    $rawData = $this->orderRepository
        ->getRevenueBetween($from, $to, $groupBy)
        ->keyBy(fn($item) => Carbon::parse($item->date)->format('d/m'));

    $result = [];
    for ($date = $from->copy(); $date <= $to; $date->addDay()) {
        $label = $date->format('d/m');
        $revenue = isset($rawData[$label]) ? (float)$rawData[$label]->total_revenue : 0;
        $result[] = [
            'label' => $label,
            'revenue' => $revenue
        ];
    }

    return $result;
}

private function revenueByWeek()
{
    $from = Carbon::now()->startOfMonth();
    $to = Carbon::now()->endOfMonth();
    $groupBy = 'WEEK';

    $rawData = $this->orderRepository
        ->getRevenueBetween($from, $to, $groupBy)
        ->keyBy('week');

    $weeks = [];
    $start = $from->copy()->startOfWeek(Carbon::MONDAY);
    while ($start < $to) {
        $week = $start->isoWeek();
        $label = "Tuần $week";
        $revenue = isset($rawData[$week]) ? (float)$rawData[$week]->total_revenue : 0;
        $weeks[] = [
            'label' => $label,
            'revenue' => $revenue
        ];
        $start->addWeek();
    }

    return $weeks;
}

private function revenueByQuarter()
{
    $from = Carbon::now()->firstOfQuarter();
    $to = Carbon::now()->lastOfQuarter();
    $groupBy = 'MONTH';

    $rawData = $this->orderRepository
        ->getRevenueBetween($from, $to, $groupBy)
        ->keyBy('month');

    $result = [];
    for ($month = $from->month; $month <= $to->month; $month++) {
        $label = "Tháng $month";
        $revenue = isset($rawData[$month]) ? (float)$rawData[$month]->total_revenue : 0;
        $result[] = [
            'label' => $label,
            'revenue' => $revenue
        ];
    }

    return $result;
}


private function revenueByYear()
{
    $from = Carbon::now()->startOfYear();
    $to = Carbon::now()->endOfYear();
    $groupBy = 'MONTH';

    $rawData = $this->orderRepository
        ->getRevenueBetween($from, $to, $groupBy)
        ->keyBy('month');

    $result = [];
    for ($month = 1; $month <= 12; $month++) {
        $label = "Tháng $month";
        $revenue = isset($rawData[$month]) ? (float)$rawData[$month]->total_revenue : 0;
        $result[] = [
            'label' => $label,
            'revenue' => $revenue
        ];
    }

    return $result;
}


   




    
    
  
   
   
}   