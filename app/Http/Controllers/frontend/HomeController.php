<?php

namespace App\Http\Controllers\frontend;
use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Auth;

use App\Services\Interfaces\WidgetServiceInterface as WidgetService;
use App\Services\Interfaces\SlideServiceInterface as SlideService;
use Cart;

class HomeController extends FrontendController
{
    
    protected $widgetService;
    protected $slideService;
    
    public function __construct(
        WidgetService $widgetService,
        SlideService $slideService
    )
    {
       parent:: __construct();
       $this->widgetService = $widgetService;
       $this->slideService = $slideService;
    }

   
    public function index()
    {   
        $config = $this->config();
        $slides= $this->slideService->getSlide(['banner','main-slide'], $this->language);
        
        $widgets = $this->widgetService->getWidgets([
            'category' => ['keyword' => 'category', 'options' => ['children' => true, 'object' => true, 'countObject' => true,'promotion' => true]],
            'news' => ['keyword' => 'news','options' => ['children' => true, 'object' => true, 'countObject' => true]],
            'cate-2' => ['keyword' => 'category-hightlight'],
            'best-seller' => ['keyword' => 'best-seller','options' => ['promotion' => true]],
            'cate-home' => ['keyword' => 'cate-home','options' => ['children' => true, 'object' => true, 'countObject' => true,'promotion' => true]],
        ], $this->language);
        $enableMenu = true;
        $seo = (__('frontend.seo'));
        return view('frontend.homepage.home.index',compact(
            'config',
            'slides',
            'widgets',
            'enableMenu',
            

            
            'seo'
        ));
    }
   
   
    private function config(){
        return [
            'language' =>  $this->language
        ];
    }
}
