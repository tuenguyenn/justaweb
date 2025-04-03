<?php

namespace App\Http\Controllers\backend\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\CustomerServiceInterface as CustomerService;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
use App\Repositories\Interfaces\CustomerRepositoryInterface as CustomerRepository;
use App\Repositories\Interfaces\SourceRepositoryInterface as SourceRepository;

use App\Repositories\Interfaces\CustomerCatalogueRepositoryInterface as CustomerCatalogueRepository;

use App\Models\Province;
use App\Models\District;  
use App\Models\Ward;  
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;

class CustomerController extends Controller
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


    public function index(Request $request)
    {
        // $this->authorize('module','customer.index');

        $customers = $this->customerService->paginate($request);
        
        foreach ($customers as $customer) {
            $province = Province::find($customer->province_id);
            $district = District::find($customer->district_id);
            $ward = Ward::find($customer->ward_id);
            
            $customer->province_name = $province ? $province->name : '';
            $customer->district_name = $district ? $district->name : '';
            $customer->ward_name = $ward ? $ward->name : '';
        }
       
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ]
        ];
        
        $config['seo'] = (__('messages.customer'));
        $template = 'backend.customer.customer.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'customers',
            
            

        ));
    }
    
    public function create()
    {   
        // $this->authorize('module','customer.create');

        $province = $this->provinceRepository->all();
        $catalogues = $this->customerCatalogueRepository->all();
        $sources = $this->sourceRepository->all();
        
      
        $config = [

            'js' => [
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/location.js',
            ]
        ];

        $template = 'backend.customer.customer.form';
        $config['seo'] = (__('messages.customer'));
        $config['method']= 'create';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'province',
            'catalogues',
            'sources'
           
        ));
    }

    public function store(StoreCustomerRequest $request)
    {
        if ($this->customerService->create($request)) {
            return redirect()->route('customer.index')->with('success', 'Thêm mới thành công');
        }
        return redirect()->route('customer.index')->with('error', 'Thêm mới không thành công');
    }
    public function edit($id)
    {
        // $this->authorize('module','customer.update');
        $customer = $this->customerRepository->findById($id);
        $catalogues= $this->customerCatalogueRepository->getCustomerCatalogue();
        $sources = $this->sourceRepository->all();

        $province = $this->provinceRepository->all();

        $districts = $customer->province_id ? District::where('province_code', $customer->province_id)->get() : [];

        $wards = $customer->district_id ? Ward::where('district_code', $customer->district_id)->get() : [];

        $config = [
           
            'js' => [
                'backend/library/location.js',
                 'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js'
            ]
        ];

        $template = 'backend.customer.customer.form';
        $config['seo'] = (__('messages.customer'));
        $config['method']= 'edit';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'province',
            'districts',  
            'wards',      
            'customer',
            'catalogues',
            'sources'
        ));
    }
    public function update($id , UpdateCustomerRequest $request){
        {
            if ($this->customerService->update($id,$request)) {
                return redirect()->route('customer.index')->with('success', 'Cập nhật thành công');
            }
            
            return redirect()->route('customer.index')->with('error', 'Cập nhật không thành công');
        }
    }
    public function destroy($id ){
        // $this->authorize('module','customer.destroy');

        
        if ($this->customerService->destroy($id)) {
            return redirect()->route('customer.index')->with('success', 'Xoá thành công');
        }
        return redirect()->route('customer.index')->with('error', 'Xoá không thành công');
        
    }
    

}
