<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\OrderServiceInterface as OrderService;
use App\Repositories\Interfaces\OrderRepositoryInterface as OrderRepository;
use App\Repositories\Interfaces\CustomerRepositoryInterface as CustomerRepository;
use Illuminate\Support\Facades\Auth;

use App\Models\Province;
use App\Models\District;  
use App\Models\Ward; 

class OrderCustomerController extends Controller
{
    protected $orderService;
    protected $orderRepository;
    protected $customerRepository;
    public function __construct(
        OrderService $orderService,
        OrderRepository $orderRepository,
        CustomerRepository $customerRepository

    ) {
        $this->orderService = $orderService;
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
      
    }
    public function orderHistory($id,Request $request)
    {   
        $customer = $this->customerRepository->findById($id);

        $orders = $this->orderService->paginate($request ,$id);
        $config = [
            'js' => [
                'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js',
                'backend/js/plugins/daterangepicker/daterangepicker.js',

                
            ],
            'css' => [
                'backend/css/plugins/daterangepicker/daterangepicker-bs3.css',
                'frontend/css/order.css',

            ]
        ];
        $orders= $this->address($orders);
        $seo = (__('frontend.seo-checkout'));
        $route ='order.history';
        $template = 'frontend.customer.component.order';
        $method = 'GET';
        return view('frontend.customer.profile', compact(
            'template',
            'config',
            'orders',
            'route',
            'customer',
            'seo',
            'method'
            
        ));
    }
    private function address($orders)
    {
        if (!$orders) {
            return $orders;
        }
    
        if (is_iterable($orders)) {
            foreach ($orders as $order) {
                $this->attachAddress($order);
            }
        } else {
            $this->attachAddress($orders);
        }
    
        return $orders;
    }
    
  
    private function attachAddress($order)
    {
        $order->province_name = optional(Province::find($order->province_id))->name ?? '';
        $order->district_name = optional(District::find($order->district_id))->name ?? '';
        $order->ward_name = optional(Ward::find($order->ward_id))->name ?? '';
    }
    
    public function orderCustomerDetail($id){
        
       
        $order= $this->orderRepository->findById($id);
        $customer = $this->customerRepository->findById(Auth::guard('customer')->id());

        $config = [
            'js' => [
                'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js',
                'backend/library/order.js',
                'frontend/core/library/review.js'
                
            ],
            'css' => [
                'backend/css/plugins/daterangepicker/daterangepicker-bs3.css',
                'frontend/css/order.css',

            ]
        ];
        
        $order= $this->address($order);
        $seo = (__('frontend.seo-checkout'));
        $route = null;
        $template = 'frontend.customer.component.order-detail';
        return view('frontend.customer.profile', compact(
            'template',
            'config',
            'order',
            'route',
            'customer',
            
            'seo'
            
        ));
    }
    public function cancel($id,Request $request){
        
        if ($this->orderService->cancel($id,$request)) {
            return redirect()->route('order.customer.detail',['id'=>$id])->with('success', 'Hủy đơn thành công');
        }
        
        return redirect()->route('order.customer.detail',['id'=>$id])->with('error', 'Hủy không thành công');
    }
  

  

  
 
  
}
