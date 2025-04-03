<?php

namespace App\Http\Controllers\backend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\UserCatalogueServiceInterface as UserCatalogueService;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface as UserCatalogueRepository;

use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;

use App\Http\Requests\User\UpdateUserCatalogueRequest;
use App\Http\Requests\User\StoreUserCatalogueRequest;

class UserCatalogueController extends Controller
{
    protected $userCatalogueService;
    protected $userCatalogueRepository;
    protected $permissionRepository;



    public function __construct(
        UserCatalogueService $userCatalogueService,
        UserCatalogueRepository $userCatalogueRepository,
        PermissionRepository $permissionRepository
    ) {
        $this->userCatalogueService = $userCatalogueService;
        $this->userCatalogueRepository = $userCatalogueRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function index(Request $request)
    {
        $this->authorize('module','user.catalogue.index');

        $userCatalogues = $this->userCatalogueService->paginate($request);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ]
        ];

        $config['seo'] = __('messages.userCatalogue');
        $template = 'backend.user.catalogue.index';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'userCatalogues'
        ));
    }

    public function create()
    {
        $this->authorize('module','user.catalogue.create');

        // Preparing for the 'create' form view
        $template = 'backend.user.catalogue.form';
        $config['seo'] = __('messages.userCatalogue');
        $config['method'] = 'create';

        return view('backend.dashboard.layout', compact(
            'template',
            'config'
        ));
    }

    public function store(StoreUserCatalogueRequest $request)
    {
        // Create a new catalogue entry using the service
        if ($this->userCatalogueService->create($request)) {
            return redirect()->route('user.catalogue.index')->with('success', 'Thêm mới thành công');
        }
        return redirect()->route('user.catalogue.index')->with('error', 'Thêm mới không thành công');
    }

    public function edit($id)
    {
        $this->authorize('module','user.catalogue.update');

        $userCatalogues = $this->userCatalogueRepository->findById($id);

        $template = 'backend.user.catalogue.form';
        $config['seo'] = __('messages.userCatalogue');
        $config['method'] = 'edit';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'userCatalogues'
        ));
    }

    public function update($id, UpdateUserCatalogueRequest $request)
    {
        if ($this->userCatalogueService->update($id, $request)) {
            return redirect()->route('user.catalogue.index')->with('success', 'Cập nhật thành công');
        }

        return redirect()->route('user.catalogue.index')->with('error', 'Cập nhật không thành công');
    }

    public function destroy($id)
    {
        $this->authorize('module','user.catalogue.destroy');

        if ($this->userCatalogueService->destroy($id)) {
            return redirect()->route('user.catalogue.index')->with('success', 'Xoá thành công');
        }
        return redirect()->route('user.catalogue.index')->with('error', 'Xoá không thành công');
    }
    public function permission(){
        $this->authorize('module','user.catalogue.permission');

        $userCatalogues =$this->userCatalogueRepository->all(['permissions']);
        $permissions =$this->permissionRepository->all();
        $template = 'backend.user.catalogue.permission';
        $config['seo'] = __('messages.userCatalogue.permission');
        $config['method'] = 'create';

        return view('backend.dashboard.layout', compact(
            'template',
            'permissions',
            'userCatalogues',
            'config'
        ));
    }
    public function updatePermission(Request $request){
        if($this->userCatalogueService->setPermission($request)){
            return redirect()->route('user.catalogue.permission')->with('success', 'Cập nhật quyền thành công');
        }
        return redirect()->route('user.catalogue.permission')->with('error', 'Cập nhật quyền không thành công');

    }
}
