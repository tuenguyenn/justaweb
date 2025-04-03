<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\OrderServiceInterface as OrderService;
use App\Repositories\Interfaces\OrderRepositoryInterface as OrderRepository;
use App\Models\Province;
use App\Models\District;  
use App\Models\Ward; 

class OrderController extends Controller
{
    protected $orderService;
    protected $orderRepository;
    public function __construct(
        OrderService $orderService,
        OrderRepository $orderRepository,

    ) {
        $this->orderService = $orderService;
        $this->orderRepository = $orderRepository;
      
    }
    public function index(Request $request)
    {   
        $this->authorize('module','order.index');
        $orders = $this->orderService->paginate($request);
        
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js',
                // 'backend/js/plugins/fullcalendar/moment.min.js',
                'backend/js/plugins/daterangepicker/daterangepicker.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'backend/css/plugins/daterangepicker/daterangepicker-bs3.css'
            ]
        ];
        $orders= $this->address($orders);
        $config['seo'] = __('messages.order');
        $template = 'backend.order.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'orders',
            
        ));
    }
    private function address($orders)
    {
        if (!$orders) {
            return $orders;
        }
    
        // Kiểm tra nếu $orders là một mảng hoặc Collection
        if (is_iterable($orders)) {
            foreach ($orders as $order) {
                $this->attachAddress($order);
            }
        } else {
            $this->attachAddress($orders);
        }
    
        return $orders;
    }
    
    /**
     * Hàm riêng để gán thông tin địa chỉ cho đơn hàng
     */
    private function attachAddress($order)
    {
        $order->province_name = optional(Province::find($order->province_id))->name ?? '';
        $order->district_name = optional(District::find($order->district_id))->name ?? '';
        $order->ward_name = optional(Ward::find($order->ward_id))->name ?? '';
    }
    
    public function detail($id){
    
        $order= $this->orderRepository->findById($id);
     
        $template = 'backend.order.detail';
        $config = [
            'js' => [
                'backend/library/order.js',
                'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js',

               
            ],
            
          
        ];
        $config['seo'] = __('messages.order');
        $order= $this->address($order);
        
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'order',
            
        ));
    }
  

  

  
 
  
}
