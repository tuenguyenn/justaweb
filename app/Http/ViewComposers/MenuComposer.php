<?php
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\Interfaces\MenuCatalogueRepositoryInterface as MenuCatalogueRepository;
use App\Models\Language; // Đừng quên import model nếu chưa có

class MenuComposer
{   
    protected $menuCatalogueRepository;

    public function __construct(
        MenuCatalogueRepository $menuCatalogueRepository,
    ) {
        $this->menuCatalogueRepository = $menuCatalogueRepository;
    }

    public function compose(View $view)
    {   
       
        $agrument = $this->agrument();
        $menuCatalogue = $this->menuCatalogueRepository->findByWhere(...$agrument);
        $menu =[];
        $htmlType = ['main-menu'];
        if(count($menuCatalogue)){
            foreach($menuCatalogue as $key => $val){
                $type = (in_array($val->keyword, $htmlType)) ? 'html': 'array';
                $menu[$val->keyword] = fe_recursive_menu(recursive($val->menus),0,1,$type);
            };
        }
       
        
        $view->with('menu', $menu); 
    }
    private function agrument(){
     
        $locale = app()->getLocale();
        $language = Language::where("canonical", $locale)->first();
        $languageId = $language->id;

        return [
            'condition' =>    [
                config('apps.general.defaultPublish')
            ],
            'flag' => true,
            'relation' =>  [
                'menus' => function($query) use ($languageId){
                    $query->orderBy('order', 'desc');
                    $query->with([
                        'languages'=>function($query) use ($languageId){
                            $query->where('language_id',$languageId);
                        }
                    ]);
                }
            ]
            ];
    }
}
