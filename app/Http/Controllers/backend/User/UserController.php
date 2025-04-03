<?php

namespace App\Http\Controllers\backend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\UserServiceInterface as UserService;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use App\Models\Province;
use App\Models\District;  
use App\Models\Ward;  
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;

class UserController extends Controller
{
    protected $userService;
    protected $provinceRepository;
    protected $userRepository;


    public function __construct(
        UserService $userService,
        ProvinceRepository $provinceRepository,
        UserRepository $userRepository

    ) {
        $this->userService = $userService;
        $this->provinceRepository = $provinceRepository;
        $this->userRepository = $userRepository;
    }


    public function index(Request $request)
    {
        $this->authorize('module','user.index');

        $users = $this->userService->paginate($request);
        foreach ($users as $user) {
            $province = Province::find($user->province_id);
            $district = District::find($user->district_id);
            $ward = Ward::find($user->ward_id);
            
            $user->province_name = $province ? $province->name : '';
            $user->district_name = $district ? $district->name : '';
            $user->ward_name = $ward ? $ward->name : '';
        }
       
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ]
        ];
        
        $config['seo'] = (__('messages.user'));
        $template = 'backend.user.user.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'users',
            

        ));
    }
    
    public function create()
    {   
        $this->authorize('module','user.create');

        $province = $this->provinceRepository->all();

        $config = [

            'js' => [
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/location.js',
            ]
        ];

        $template = 'backend.user.user.form';
        $config['seo'] = (__('messages.user'));
        $config['method']= 'create';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'province',
           
        ));
    }

    public function store(StoreUserRequest $request)
    {
        if ($this->userService->create($request)) {
            return redirect()->route('user.index')->with('success', 'Thêm mới thành công');
        }
        return redirect()->route('user.index')->with('error', 'Thêm mới không thành công');
    }
    public function edit($id)
    {
        $this->authorize('module','user.update');
        $user = $this->userRepository->findById($id);

        $province = $this->provinceRepository->all();

        $districts = $user->province_id ? District::where('province_code', $user->province_id)->get() : [];

        $wards = $user->district_id ? Ward::where('district_code', $user->district_id)->get() : [];

        $config = [
           
            'js' => [
                'backend/library/location.js',
                 'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js'
            ]
        ];

        $template = 'backend.user.user.form';
        $config['seo'] = (__('messages.user'));
        $config['method']= 'edit';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'province',
            'districts',  
            'wards',      
            'user'
        ));
    }
    public function update($id , UpdateUserRequest $request){
        {
            if ($this->userService->update($id,$request)) {
                return redirect()->route('user.index')->with('success', 'Cập nhật thành công');
            }
            
            return redirect()->route('user.index')->with('error', 'Cập nhật không thành công');
        }
    }
    public function destroy($id ){
        $this->authorize('module','user.destroy');

        
        if ($this->userService->destroy($id)) {
            return redirect()->route('user.index')->with('success', 'Xoá thành công');
        }
        return redirect()->route('user.index')->with('error', 'Xoá không thành công');
        
    }
    

}
