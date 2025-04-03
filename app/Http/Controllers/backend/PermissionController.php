<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\PermissionServiceInterface as PermissionService;
use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;

use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;

class PermissionController extends Controller
{
    protected $permissionService;
    protected $permissionRepository;

    public function __construct(
        PermissionService $permissionService,
        PermissionRepository $permissionRepository
    ) {
        $this->permissionService = $permissionService;
        $this->permissionRepository = $permissionRepository;
    }

    public function index(Request $request)
    {   
        $this->authorize('module','permission.index');

        $permissions = $this->permissionService->paginate($request);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ]
        ];

        $config['seo'] = __('messages.permission');
        $template = 'backend.permission.index';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'permissions'
        ));
    }

    public function create()
    {
        $this->authorize('module','permission.create');

        $config = [
            'js' => [
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js'
            ],
        ];
        $template = 'backend.permission.form';
        $config['seo'] = __('messages.permission');
        $config['method'] = 'create';

        return view('backend.dashboard.layout', compact(
            'template',
            'config'
        ));
    }

    public function store(StorePermissionRequest $request)
    {
        // Create a new catalogue entry using the service
        if ($this->permissionService->create($request)) {
            return redirect()->route('permission.index')->with('success', 'Thêm mới thành công');
        }
        return redirect()->route('permission.index')->with('error', 'Thêm mới không thành công');
    }

    public function edit($id)
    {   
        $this->authorize('module','permission.update');

        $config = [
            'js' => [
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js'
            ],
        ];
        $permission = $this->permissionRepository->findById($id);
        $template = 'backend.permission.form';
        $config['seo'] = __('messages.permission');
        $config['method'] = 'edit';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'permission'
        ));
    }

    public function update($id, UpdatePermissionRequest $request)
    {
        if ($this->permissionService->update($id, $request)) {
            return redirect()->route('permission.index')->with('success', 'Cập nhật thành công');
        }

        return redirect()->route('permission.index')->with('error', 'Cập nhật không thành công');
    }

    public function destroy($id)
    {
        $this->authorize('module','permission.destroy');

        if ($this->permissionService->destroy($id)) {
            return redirect()->route('permission.index')->with('success', 'Xoá thành công');
        }
        return redirect()->route('permission.index')->with('error', 'Xoá không thành công');
    }
   
}
