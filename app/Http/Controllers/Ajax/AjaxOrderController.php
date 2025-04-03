<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\OrderServiceInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface;



use Illuminate\Http\Request; 

class AjaxOrderController extends Controller
{   
    protected $orderService;
    protected $orderRepository;

    public function __construct(
        OrderServiceInterface $orderService,
        OrderRepositoryInterface $orderRepository

        
    )
    {
        $this->orderService = $orderService;
        $this->orderRepository = $orderRepository;


      
       
    }
  
   
    public function updateField(Request $request){
        $get = $request->input();
        $payload = $get['payload'];
    
        $order = $this->orderService->update($get['orderId'],$payload);
       
        

        return response()->json([
            'response'=> $order['order'],
            'messages'=> $order['message'],
            'code' => $order['status'],
        ]);

    }
  

    
}
