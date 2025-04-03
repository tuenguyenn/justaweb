<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;

use Illuminate\Support\Str;
use SebastianBergmann\Type\FalseType;

class StatusController extends Controller
{   

    public function __construct(
       
    )
    {
        $this->middleware(function($request, $next){
            $locale = app()->getLocale();
            $language = Language::where("canonical", $locale)->first();
            $this->language =$language->id;
            return $next($request);
        });
    }
    public function changeStatus(Request $request){
        $post = $request->input();
        $serviceInterfaceNamespace ='\App\Services\\'.ucfirst($post['model']).'Service';
        if(class_exists($serviceInterfaceNamespace)){
            $serviceInstance = app($serviceInterfaceNamespace);
        }
        $flag= $serviceInstance->updateStatus($post);
        return response()->json(['flag' => $flag]);

    }
    public function changeAllStatus(Request $request){
        $post = $request->input();
        $serviceInterfaceNamespace ='\App\Services\\'.ucfirst($post['model']).'Service';
        if(class_exists($serviceInterfaceNamespace)){
            $serviceInstance = app($serviceInterfaceNamespace);
        }
        $flag= $serviceInstance->updateAllStatus($post);
        return response()->json(['flag' => $flag]);

    }
    public function getMenu(Request $request)  {
        $model =$request->input('model');
        $page =($request->input('page')) ?? 1;
        $keyword =($request->string('keyword')?? '');

        $serviceInterfaceNamespace ='\App\Repositories\\'.ucfirst($model).'Repository';
        if(class_exists($serviceInterfaceNamespace)){
            $serviceInstance = app($serviceInterfaceNamespace);
        } 
        $arguments =$this->paginationAgrument($model ,$keyword);
        $object = $serviceInstance->pagination(...array_values($arguments));
        return response()->json($object);     
    }

    private function paginationAgrument(string $model= '', string $keyword ='') {
        $model = Str::snake($model); 
        $languageTable = $model === 'post' ? 'posts_language' : "{$model}_language";
        $catalogueTable = "{$model}_catalogue_{$model}";
        $join = [
            [$languageTable . ' as tb2', 'tb2.' . $model . '_id', '=', $model . 's.id']
        ];
        
        if (strpos($model, '_catalogue') === false) {
            $join[] = [$catalogueTable . ' as tb3', $model . 's.id', '=', 'tb3.' . $model . '_id'];
        }
        $condition =[
            'where' => [
                    ['tb2.language_id', '=', $this->language],
            ],
                'keyword' => $keyword,
            ];
        
        return [
            'select' => ['id', 'name','canonical'],
            'condition' => $condition,
            'join' => $join,
            'paginationConfig' => ['path' => "{$model}.index", 'groupBy' => ['id', 'name','canonical']],
            'perpage' => 10,
            'relation' => [],
            'order_by' => ["{$model}s.id", 'desc'],
            'rawQuery' => [],
        ];
    }

    public function findModelObject (Request $request){
       
        $model =$request->input('model');
        $modelSnake = Str::snake($model);
        $languageTable = $modelSnake === 'post' ? 'posts_language' : "{$modelSnake}_language";

        $keyword = $request->input('keyword');
        $loadClass = loadClass($model);
        
        $object = $loadClass->findByWhereHas([
            ['name','like', '%' . $keyword . '%'],
            ['language_id', '=', $this->language]
        ],
            'languages',
            $languageTable,
            TRUE
        
        );
        return response()->json($object);     

        
    }
    public function findPromotionObject(Request $request){
        $data = $request->all(); 
        $model = $data['option']['model'] ;
        $modelSnake = Str::snake($model);
        $languageTable = "{$modelSnake}_language";

        $keyword = $data['search'];
        $loadClass = loadClass($model);
        
        $object = $loadClass->findByWhereHas([
            ['name','like', '%' . $keyword . '%'],
            ['language_id', '=', $this->language]
        ],
            'languages',
            $languageTable,
            TRUE
        
        );
        
        $temp=[];
        if(count($object)){
            foreach($object as $key =>$val){
                $temp[]=[
                    'id'=>$val->id,
                    'text'=>$val->languages->first()->pivot->name,

                ];
            }
        }
        return response()->json(array('items'=>$temp));     

    }
    public function getPromotionContitionValue(Request $request){
        try {
            $get= $request->input();
            switch ($get['value']){
                case 'staff_take_care_customer':
                    $repositoryInterfaceNamespace ='\App\Repositories\\UserRepository';
                    if(class_exists($repositoryInterfaceNamespace)){
                        $repositoryInstance = app($repositoryInterfaceNamespace);
                    } 
                    $object = $repositoryInstance->all()->toArray();
                    break;
                case 'customer_group':
                    $repositoryInterfaceNamespace ='\App\Repositories\\CustomerCatalogueRepository';
                    if(class_exists($repositoryInterfaceNamespace)){
                        $repositoryInstance = app($repositoryInterfaceNamespace);
                    } 
                    $object = $repositoryInstance->all()->toArray();
                    break;
                case 'customer_gender':
                    $object= __('module.gender');
                    break;
                case 'customer_birthday':
                    $object= __('module.day');
                    break;
    
    
            }
            $temp=[];
            if(!is_null($object) && count($object)){
                
                foreach($object as $key =>$val){
                    $temp[]=[
                        'id'=>$val['id'],
                        'text'=>$val['name'],
                        
                    ];
                }
            }
            return response()->json([
                'data'=>$temp,
                'error' => false,
                
                
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status' => 500
                ], 500);
        }
       

        
    }
}
    




