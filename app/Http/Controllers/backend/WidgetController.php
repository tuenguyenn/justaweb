<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\WidgetServiceInterface as WidgetService;
use App\Repositories\Interfaces\WidgetRepositoryInterface as WidgetRepository;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;

use App\Http\Requests\StoreWidgetRequest;
use App\Http\Requests\UpdateWidgetRequest;
use Illuminate\Support\Arr;
use App\Models\Language;
use Illuminate\Support\Collection;


class WidgetController extends Controller
{
    protected $widgetService;
    protected $widgetRepository;
    protected $languageRepository;
    public function __construct(
        WidgetService $widgetService,
        WidgetRepository $widgetRepository,
        LanguageRepository $languageRepository

    ) {
        $this->widgetService = $widgetService;
        $this->widgetRepository = $widgetRepository;
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
        $this->authorize('module','widget.index');
        $widgets = $this->widgetService->paginate($request);
        
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ]
        ];
        
        $config['seo'] = __('messages.widget');
        $template = 'backend.widget.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'widgets',
            
        ));
    }
    
    public function create()
    {   
        $this->authorize('module','widget.edit');

        $config = [

            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/widget.js',


            ]
        ];
        $template = 'backend.widget.form';
        $config['seo'] = __('messages.widget');
        $config['method']= 'create';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
          
        ));
    }

    public function store(StoreWidgetRequest $request)
    {
        if ($this->widgetService->create($request,$this->language)) {
            return redirect()->route('widget.index')->with('success', 'Thêm mới thành công');
        }
        return redirect()->route('widget.index')->with('error', 'Thêm mới không thành công');
    }

    private function menuItemArgument(array $whereIn = [])
    {   
        $currentLanguage =$this->language;
        return [
            'condition' => [],
            'flag' => true,
            'relation' => [
                'languages' => function($query) use ($currentLanguage){
                    $query->where('language_id','=',$currentLanguage);
                }
            ],  
            'orderBy' => ['id', 'desc'],
            'param' => [
                'whereIn' => Arr::flatten($whereIn), 
                'whereInField' => 'id'
            ],
            [],
        ];
    }
    

    
    public function edit($id)
    {
        $this->authorize('module','widget.edit');
        $widget = $this->widgetRepository->findById($id);
        $widget->description = $widget->description[$this->language];
        $repositoryInterfaceNamespace = '\App\Repositories\\' . $widget->model . 'Repository';
    
        if (class_exists($repositoryInterfaceNamespace)) {
            $repositoryInstance = app($repositoryInterfaceNamespace);
        } 
        $widgetItem = $repositoryInstance->findByWhere(
            ...array_values($this->menuItemArgument([$widget->model_id]))
        );
        $widgetItem =convertArrayByKey($widgetItem,['id','name.languages','image']);
        
        
        $config = [
           
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',

                'backend/library/widget.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js'
            ]
        ];

        $template = 'backend.widget.form';
        $config['seo'] = __('messages.widget');
        $config['method']= 'edit';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            
            'widget',
            'widgetItem',
        ));
    }
 
    public function update($id , UpdateWidgetRequest $request){
        {
            if ($this->widgetService->update($id,$request,$this->language)) {
                return redirect()->route('widget.index')->with('success', 'Cập nhật thành công');
            }
            
            return redirect()->route('widget.index')->with('error', 'Cập nhật không thành công');
        }
    }
    public function destroy($id ){
        $this->authorize('module','widget.edit');

        if ($this->widgetService->destroy($id)) {
            return redirect()->route('widget.index')->with('success', 'Xoá thành công');
        }
        return redirect()->route('widget.index')->with('error', 'Xoá không thành công');
        
    }
    public function translate($languageId, $widgetId)
    {
        $config = [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/library/widget.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
            ],
        ];
    
        $widget = $this->widgetRepository->findById($widgetId);
    
        $widget->jsonDescription = $widget->description;
        $widget->description = $widget->description[$this->language];
    
        $widgetTranslate = new \stdClass;
        $widgetTranslate->description = ($widget->jsonDescription[$languageId]) ?? '';
    
        $translate = $this->languageRepository->findById($languageId);
    
        $template = 'backend.widget.translate';
        $config['seo'] = __('messages.widget');
    
        
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'widget',
            'translate',
            
            'widgetTranslate'
        ));
    }
    
    public function saveTranslate(Request $request){
        if($this->widgetService->saveTranslate($request, $this->language)){
            return redirect()->route('widget.index')->with('success', 'Cập nhật thành công');
        }
        return redirect()->route('widget.index')->with('error', 'Cập nhật không thành công');
    }
    

}
