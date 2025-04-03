<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CustomerRepositoryInterface as CustomerRepository;
use App\Services\Interfaces\CustomerServiceInterface as CustomerService;



use Illuminate\Http\Request; 

class AjaxCustomerController extends Controller
{   
    protected $customerRepository;
    protected $customerService;



    public function __construct(
        CustomerService $customerService,
        CustomerRepository $customerRepository
       
    )
    {
        $this->customerRepository = $customerRepository;
        $this->customerService = $customerService;
       
       
    }
    public function resendOtp(Request $request)
    {   
      
       $id = $request->input('id');
       
        
        try {
            $data= $this->customerService->resendOtp($id);
            return response()->json([
                'data'=> $data,
               
                
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status' => 500
                ], 500);
        }
       
        
    
           
    }
    
  

    
}
