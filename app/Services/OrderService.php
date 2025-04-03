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
  
   
   
}   