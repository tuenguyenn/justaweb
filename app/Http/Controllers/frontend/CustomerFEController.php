<?php

namespace App\Http\Controllers\frontend;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\CustomerServiceInterface as CustomerService;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
use App\Repositories\Interfaces\CustomerRepositoryInterface as CustomerRepository;
use App\Repositories\Interfaces\SourceRepositoryInterface as SourceRepository;

use App\Repositories\Interfaces\CustomerCatalogueRepositoryInterface as CustomerCatalogueRepository;

 
use App\Http\Requests\Customer\StoreCustomerFeRequest;
use App\Http\Requests\Customer\UpdateCustomerFeRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
class CustomerFEController extends Controller
{
    protected $customerService;
    protected $provinceRepository;
    protected $customerRepository;
    protected $customerCatalogueRepository;
    protected $sourceRepository;


    public function __construct(
        CustomerService $customerService,
        ProvinceRepository $provinceRepository,
        CustomerRepository $customerRepository,
        CustomerCatalogueRepository $customerCatalogueRepository,
        SourceRepository $sourceRepository

    ) {
        $this->customerService = $customerService;
        $this->provinceRepository = $provinceRepository;
        $this->customerRepository = $customerRepository;
        $this->customerCatalogueRepository = $customerCatalogueRepository;
        $this->sourceRepository = $sourceRepository;
    }


   
    public function create()
    {   
        // $this->authorize('module','customer.create');
     

        $seo = (__('frontend.seo-login'));
        $config =[
            'js' =>[
                'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
            ],
            'css' =>[
                'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',

            ]
           
        ];
       

        return view('frontend.customer.form', compact(
            'seo',
            'config',
           
        ));
    }

    public function store(StoreCustomerFeRequest $request)
    {
        if ($this->customerService->create($request)) {
            return redirect()->route('customer')->with('success', 'Thêm mới thành công');
        }
        return redirect()->route('customer')->with('error', 'Thêm mới không thành công');
    }
    public function profile($id)
    {
        // $this->authorize('module','customer.update');
     
        $customer = $this->customerRepository->findById($id);
        $province = $this->provinceRepository->all();

        $config = [
            'js' => [
                'backend/library/location.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
            

            ],
        ];
        $seo = (__('frontend.seo-login'));
        $route = 'customerfe.update';
        $template = 'frontend.customer.component.infor';
        return view('frontend.customer.profile', compact(
            'route',
            
            'config',
            'province',
            'template',
            'customer',
            'seo'
          
        ));
    }
    public function update($id, UpdateCustomerFeRequest $request) {
        if ($this->customerService->update($id, $request)) {
            return redirect()->route('customer.profile', ['id' => $id])->with('success', 'Cập nhật thành công');
        }
        
        return redirect()->route('customer.profile', ['id' => $id])->with('error', 'Cập nhật không thành công');
    }
    
    public function verify($id){

        $customer = $this->customerRepository->findById($id);

        $seo = (__('frontend.seo-login'));
        $route = 'customerfe.verifyEmail';
        $template = 'frontend.customer.component.verify';
        return view('frontend.customer.profile', compact(
            
           
            'route',
            'template',
            'customer',
            'seo'
          
        ));
    }
    public function verifyEmail($id, Request $request) {
        $customer = $this->customerRepository->findById($id);
        $otp = $this->customerService->generateOtp($customer);
        $seo = (__('frontend.seo-login'));
        $route = 'otp.verify';
        $template = 'frontend.customer.component.otp';
        return view('frontend.customer.profile', compact(
            
           
            'route',
            'template',
            'customer',
            'seo'
          
        ));
      
    }
   
    public function verifyOtp(Request $request)
    {   
        $customerId =Auth::guard(name: 'customer')->id();
        $customer = $this->customerRepository->findById($customerId);
        $flag= $this->customerService->validateOtp($request);   
        if ($flag) {
            return redirect()->route( 'customerfe.verify', ['id' => $customer->id])->with('success', 'Xác thực thành  thành công');
        }
        return redirect()->route('customerfe.verify', ['id' => $customer->id])->with('error', 'Xác thực không thành công');


      

    }
    public function showChangePasswordForm()
    {
        $customerId =Auth::guard(name: 'customer')->id();
        $customer = $this->customerRepository->findById($customerId);
        $seo = (__('frontend.seo-login'));
        $route = 'password.change';
        $template = 'frontend.customer.component.changepassword';
        return view('frontend.customer.profile', compact(
            
           
            'route',
            'template',
            'customer',
            'seo'
          
        ));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $customerId =Auth::guard(name: 'customer')->id();
        $customer = $this->customerRepository->findById($customerId);

        if (!Hash::check($request->current_password, $customer->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Mật khẩu cũ không đúng.',
            ]);
        }
        $payload['password'] =  Hash::make($request->new_password);
        $customer = $this->customerRepository->update($customerId,$payload);
     

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }


}
