<?php
namespace App\Http\ViewComposers;
use Illuminate\Support\Facades\Auth;

use Illuminate\View\View;
use App\Repositories\Interfaces\CustomerRepositoryInterface as CustomerRepository;

class CustomerComposer
{   
    protected $customerRepository;

    public function __construct(
        CustomerRepository $customerRepository,
    ) {
        $this->customerRepository = $customerRepository;
    }

    public function compose(View $view)
    {   
        $customerId =Auth::guard('customer')->id();
        $customer = null;
        if(!is_null($customerId)) {
            $customer = $this->customerRepository->findById($customerId);
        }
        
       
        
        $view->with('customer', $customer); 
    }
   
}
