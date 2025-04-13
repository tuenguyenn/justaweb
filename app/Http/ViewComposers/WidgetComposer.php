<?php
namespace App\Http\ViewComposers;
use App\Http\Controllers\FrontendController;
use Illuminate\View\View;
use App\Services\Interfaces\WidgetServiceInterface;
use App\Models\Language;
class WidgetComposer
{   
    protected $widgetService;

    public function __construct(
        WidgetServiceInterface $widgetService,
    ) {
        $this->widgetService = $widgetService;
    }

    public function compose(View $view)
    {   
        $locale = app()->getLocale();
        $language = Language::where("canonical", $locale)->first();
        $languageId = $language->id;
        $widgets = $this->widgetService->getWidgets([
            
            'trending' => ['keyword' => 'trending','options' => ['promotion' => true]],
            'best-seller' => ['keyword' => 'best-seller','options' => ['promotion' => true]],
         
        ], $languageId);
        
        $view->with('widgets', $widgets); 
    }
   
}
