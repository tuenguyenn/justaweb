<?php
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;
use App\Models\Language; 

class LanguageComposer
{   
    protected $languageRepository;

    public function __construct(
        LanguageRepository $languageRepository,
    ) {
        $this->languageRepository = $languageRepository;
    }

    public function compose(View $view)
    {   
        $argu = $this->agrument();
        $language= $this->languageRepository->findByWhere(...$argu);
        $view->with('language', $language); 
    }
    private function agrument(){
     
        $locale = app()->getLocale();
        $language = Language::where("canonical", $locale)->first();
        $languaId = $language->id;

       
        return [
            'condition' =>    [
                config('apps.general.defaultPublish')
            ],
            'flag' => true,
            'relation' =>  [],
            'orderBy' =>['current','desc'],
            ];
    }
}
