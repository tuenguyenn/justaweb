<?php

namespace App\Http\Controllers\backend\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\CustomerCatalogueServiceInterface as CustomerCatalogueService;
use App\Repositories\Interfaces\CustomerCatalogueRepositoryInterface as CustomerCatalogueRepository;

use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;

use App\Http\Requests\Customer\UpdateCustomerCatalogueRequest;
use App\Http\Requests\Customer\StoreCustomerCatalogueRequest;

class CustomerCatalogueController extends Controller
{
    protected $customerCatalogueService;
    protected $customerCatalogueRepository;
    protected $permissionRepository;



    public function __construct(
        CustomerCatalogueService $customerCatalogueService,
        CustomerCatalogueRepository $customerCatalogueRepository,
        PermissionRepository $permissionRepository
    ) {
        $this->customerCatalogueService = $customerCatalogueService;
        $this->customerCatalogueRepository = $customerCatalogueRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function index(Request $request)
    {
        // $this->authorize('module','customer.catalogue.index');
        $customerCatalogues = $this->customerCatalogueService->paginate($request);
       

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ]
        ];

        $config['seo'] = (__('messages.customerCatalogue'));
        $template = 'backend.customer.catalogue.index';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'customerCatalogues'
        ));
    }

    public function create()
    {
        // $this->authorize('module','customer.catalogue.create');

        $template = 'backend.customer.catalogue.form';
        $config['seo'] = (__('messages.customerCatalogue'));
        $config['method'] = 'create';

        return view('backend.dashboard.layout', compact(
            'template',
            'config'
        ));
    }

    public function store(StoreCustomerCatalogueRequest $request)
    {
        if ($this->customerCatalogueService->create($request)) {
            return redirect()->route('customer.catalogue.index')->with('success', 'Thêm mới thành công');
        }
        return redirect()->route('customer.catalogue.index')->with('error', 'Thêm mới không thành công');
    }

    public function edit($id)
    {
        // $this->authorize('module','customer.catalogue.update');

        $customerCatalogues = $this->customerCatalogueRepository->findById($id);

        $template = 'backend.customer.catalogue.form';
        $config['seo'] = (__('messages.customerCatalogue'));
        $config['method'] = 'edit';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'customerCatalogues'
        ));
    }

    public function update($id, UpdateCustomerCatalogueRequest $request)
    {
        if ($this->customerCatalogueService->update($id, $request)) {
            return redirect()->route('customer.catalogue.index')->with('success', 'Cập nhật thành công');
        }

        return redirect()->route('customer.catalogue.index')->with('error', 'Cập nhật không thành công');
    }

    public function destroy($id)
    {
        // $this->authorize('module','customer.catalogue.destroy');

        if ($this->customerCatalogueService->destroy($id)) {
            return redirect()->route('customer.catalogue.index')->with('success', 'Xoá thành công');
        }
        return redirect()->route('customer.catalogue.index')->with('error', 'Xoá không thành công');
    }
    public function permission(){
        // $this->authorize('module','customer.catalogue.permission');

        $customerCatalogues =$this->customerCatalogueRepository->all(['permissions']);
        $permissions =$this->permissionRepository->all();
        $template = 'backend.customer.catalogue.permission';
        $config['seo'] = __('messages.customerCatalogue.permission');
        $config['method'] = 'create';

        return view('backend.dashboard.layout', compact(
            'template',
            'permissions',
            'customerCatalogues',
            'config'
        ));
    }
    public function updatePermission(Request $request){
        if($this->customerCatalogueService->setPermission($request)){
            return redirect()->route('customer.catalogue.permission')->with('success', 'Cập nhật quyền thành công');
        }
        return redirect()->route('customer.catalogue.permission')->with('error', 'Cập nhật quyền không thành công');

    }
}
