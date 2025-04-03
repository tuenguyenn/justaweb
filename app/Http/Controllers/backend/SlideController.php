<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\SlideServiceInterface as SlideService;
use App\Repositories\Interfaces\SlideRepositoryInterface as SlideRepository;
use App\Models\Language;
use App\Http\Requests\StoreSlideRequest;
use App\Http\Requests\UpdateSlideRequest;

class SlideController extends Controller
{
    protected $slideService;
    protected $slideRepository;
    protected $language;



    public function __construct(
       SlideService $slideService,
       SlideRepository $slideRepository

    ) {
        $this->slideService = $slideService;
        $this->slideRepository = $slideRepository;
        $this->middleware(function ($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where("canonical", $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }


    public function index(Request $request)
    {
        
        $this->authorize('module','slide.edit');
        $slides = $this->slideService->paginate($request);
        
        
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ]
        ];
        
        $config['seo'] = __('messages.slide');
        $template = 'backend.slide.slide.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'slides',

        ));
    }
    
    public function create()
    {   
        
        $this->authorize('module','slide.edit');
        $config = [

            'js' => [
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/slide.js',
            ]
        ];

        $template = 'backend.slide.slide.form';
        $config['seo'] = __('messages.slide');
        $config['method']= 'create';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
           
        ));
    }

    public function store(StoreSlideRequest $request )
    {   
        if ($this->slideService->create($request, $this->language)) {
            return redirect()->route('slide.index')->with('success', 'Thêm mới thành công');
        }
        return redirect()->route('slide.index')->with('error', 'Thêm mới không thành công');
    }
    public function edit($id)
    {
        $this->authorize('module','slide.edit');
        $slide = $this->slideRepository->findById($id);

        $slideItem = $this->slideService->convertSlideArray($slide->item[$this->language]);
        
        $config = [
           
            'js' => [
                 'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/slide.js',

            ]
        ];

        $template = 'backend.slide.slide.form';
        $config['seo'] = __('messages.slide');
        $config['method']= 'edit';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'slideItem',
              
            'slide'
        ));
    }
    public function update($id , UpdateSlideRequest $request){
        {
            
            if ($this->slideService->update($id,$request,$this->language)) {
                return redirect()->route('slide.index')->with('success', 'Cập nhật thành công');
            }
            
            return redirect()->route('slide.index')->with('error', 'Cập nhật không thành công');
        }
    }
    public function destroy($id ){
        $this->authorize('module','slide.edit');

        
        if ($this->slideService->destroy($id)) {
            return redirect()->route('slide.index')->with('success', 'Xoá thành công');
        }
        return redirect()->route('slide.index')->with('error', 'Xoá không thành công');
        
    }
    

}
