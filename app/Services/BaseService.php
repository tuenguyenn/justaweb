<?php
namespace App\Services;
use App\Classes\Nestedsetbie;
use Illuminate\Support\Str;

use App\Services\Interfaces\BaseServiceInterface;

/** 
 * Class PostCatalogueService
 * @package App\Services
 */
class BaseService implements BaseServiceInterface
{   
   
    public function __construct( )
    {
        
    }
   
    public function updateNestedSet()
    {
        $this->nestedset->Get('level ASC, order ASC');
        $this->nestedset->Recursive(0, $this->nestedset->Set());
        $this->nestedset->Action();
    }
    public function formatRouterPayload($model, $request,$controllerName,$language_id){
        $router = [
            'canonical' => Str::slug($request['canonical']),
            'module_id' => $model->id,
            'controllers' => 'App\Http\Controllers\Frontend\\'.$controllerName.'',
            'language_id'=> $language_id,
        ];
        return $router;
    }
    public function getRegion(){
        $appLocale = app()->getLocale(); 
        return $this->getLanguageIdByLocale($appLocale); 
    }
    public function getLanguageIdByLocale($locale)
    {
        $languages = [
            'vn' => 1,
            'en' => 2,
        ];

        return $languages[$locale] ?? 1;
    }
    public function formatJson($request , $inputName){
        return ($request->input($inputName) && !empty($request->input($inputName))) ? json_encode($request->input($inputName)) : '';
    }
  
   
}   