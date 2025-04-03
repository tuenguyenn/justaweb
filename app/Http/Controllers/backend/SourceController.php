<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\SourceServiceInterface as SourceService;
use App\Repositories\Interfaces\SourceRepositoryInterface as SourceRepository;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;

use App\Http\Requests\StoreSourceRequest;
use App\Http\Requests\UpdateSourceRequest;
use Illuminate\Support\Arr;
use App\Models\Language;

use function App\Helpers\convertArrayByKey;

class SourceController extends Controller
{
    protected $sourceService;
    protected $sourceRepository;
    protected $languageRepository;
    public function __construct(
        SourceService $sourceService,
        SourceRepository $sourceRepository,
        LanguageRepository $languageRepository

    ) {
        $this->sourceService = $sourceService;
        $this->sourceRepository = $sourceRepository;
        $this->languageRepository = $languageRepository;
        $this->middleware(function($request, $next){
            $locale = app()->getLocale();
            $language = Language::where("canonical", $locale)->first();
            $this->language =$language->id;
            return $next($request);
        });
    }
    public function index(Request $request)
    {   
        // $this->authorize('module','source.index');
        $sources = $this->sourceService->paginate($request);
       
        
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ]
        ];
        
        $config['seo'] = __('messages.source');
        $template = 'backend.source.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'sources',
            
        ));
    }
    
    public function create()
    {   
        // $this->authorize('module','source.edit');

        $config = [

            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/source.js',


            ]
        ];
        $template = 'backend.source.form';
        $config['seo'] = __('messages.source');
        $config['method']= 'create';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
          
        ));
    }

    public function store(StoreSourceRequest $request)
    {
        if ($this->sourceService->create($request,$this->language)) {
            return redirect()->route('source.index')->with('success', 'Thêm mới thành công');
        }
        return redirect()->route('source.index')->with('error', 'Thêm mới không thành công');
    }

  
    

    
    public function edit($id)
    {
        $source = $this->sourceRepository->findById($id);
    
    
        $config = [
           
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',

                'backend/library/source.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js'
            ]
        ];

        $template = 'backend.source.form';
        $config['seo'] = __('messages.source');
        $config['method']= 'edit';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            
            'source',
            
        ));
    }
 
    public function update($id , UpdateSourceRequest $request){
        {
            if ($this->sourceService->update($id,$request,$this->language)) {
                return redirect()->route('source.index')->with('success', 'Cập nhật thành công');
            }
            
            return redirect()->route('source.index')->with('error', 'Cập nhật không thành công');
        }
    }
    public function destroy($id ){
        // $this->authorize('module','source.edit');

        if ($this->sourceService->destroy($id)) {
            return redirect()->route('source.index')->with('success', 'Xoá thành công');
        }
        return redirect()->route('source.index')->with('error', 'Xoá không thành công');
        
    }
   

}
