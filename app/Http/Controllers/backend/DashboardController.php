<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Interfaces\OrderServiceInterface;
use App\Repositories\Interfaces\CustomerRepositoryInterface;

class DashboardController extends Controller
{

    protected $orderService;
    protected $customerRepository;

    public function __construct(
        OrderServiceInterface $orderService,
        CustomerRepositoryInterface $customerRepository
    )
    {
        $this->orderService = $orderService;
        $this->customerRepository = $customerRepository;
        
    }
    
    public function index(){
        
        $template = 'backend.dashboard.home.index';
        $order = $this->orderService->orderStatistics();
        $customer = $this->customerRepository->getAllCustomer();
        $config =[
            'js'=>[
                'backend/js/plugins/chartJs/Chart.min.js',
                'backend/library/dashboard.js',

            ]
            ];
         $labels = ['01/04', '02/04', '03/04']; // ngày hoặc tháng
        $data = [12000000, 15000000, 17000000];
        return view('backend.dashboard.layout',compact(
            'template',
            'order',
            'customer',
            'config',
            'labels',
            'data'


        ));
    }
  
   
}
