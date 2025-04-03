<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\PromotionServiceInterface as PromotionService;
use App\Repositories\Interfaces\PromotionRepositoryInterface as PromotionRepository;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;
use App\Repositories\Interfaces\SourceRepositoryInterface as SourceRepository;

use App\Http\Requests\StorePromotionRequest;
use App\Http\Requests\UpdatePromotionRequest;
use App\Models\Language;


class PromotionController extends Controller
{
    protected $promotionService;
    protected $promotionRepository;
    protected $languageRepository;
    protected $sourceRepository;
    public function __construct(
        PromotionService $promotionService,
        PromotionRepository $promotionRepository,
        LanguageRepository $languageRepository,
        SourceRepository $sourceRepository

    ) {
        $this->promotionService = $promotionService;
        $this->promotionRepository = $promotionRepository;
        $this->languageRepository = $languageRepository;
        $this->sourceRepository = $sourceRepository;
        $this->middleware(function($request, $next){
            $locale = app()->getLocale();
            $language = Language::where("canonical", $locale)->first();
            $this->language =$language->id;
            return $next($request);
        });
    }
    public function index(Request $request)
    {   
        $this->authorize('module','promotion.index');
      
        $promotions = $this->promotionService->paginate($request);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ]
        ];
        
        $config['seo'] = __('messages.promotion');
        $template = 'backend.promotion.promotion.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'promotions',
            
        ));
    }
    
    public function create()
    {   
        $this->authorize('module','promotion.edit');
        $source = $this->sourceRepository->all();
        $config = [

            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/promotion.js',


            ]
        ];
        $template = 'backend.promotion.promotion.form';
        $config['seo'] = __('messages.promotion');
        $config['method']= 'create';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'source',
          
        ));
    }

    public function store(StorePromotionRequest $request)
    {
        if ($this->promotionService->create($request)) {
            return redirect()->route('promotion.index')->with('success', 'Thêm mới thành công');
        }
        return redirect()->route('promotion.index')->with('error', 'Thêm mới không thành công');
    }

   
    
    public function edit($id)
    {
        $this->authorize('module','promotion.edit');
        $promotion = $this->promotionRepository->findById($id);
        
        $source = $this->sourceRepository->all();
        $config = [
           
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',

                'backend/library/promotion.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js'
            ]
        ];

        $template = 'backend.promotion.promotion.form';
        $config['seo'] = __('messages.promotion');
        $config['method']= 'edit';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            
            'promotion',
            'source',
        ));
    }
 
    public function update($id , UpdatePromotionRequest $request){
        {

            if ($this->promotionService->update($id,$request)) {
                return redirect()->route('promotion.index')->with('success', 'Cập nhật thành công');
            }
            
            return redirect()->route('promotion.index')->with('error', 'Cập nhật không thành công');
        }
    }
    public function destroy($id ){
        $this->authorize('module','promotion.edit');

        if ($this->promotionService->destroy($id)) {
            return redirect()->route('promotion.index')->with('success', 'Xoá thành công');
        }
        return redirect()->route('promotion.index')->with('error', 'Xoá không thành công');
        
    }
   
    
  
}
