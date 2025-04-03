<?php
namespace App\Services;

use App\Services\Interfaces\WidgetServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\WidgetRepositoryInterface;
use App\Services\Interfaces\ProductServiceInterface;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface;

use Illuminate\Support\Facades\DB;


/** 
 * Class WidgetService
 * @package App\Services
 */
class WidgetService extends BaseService implements WidgetServiceInterface
{   
    protected $widgetRepository;
    protected $productService;
    protected $productCatalogueRepository;


    public function __construct(
        WidgetRepositoryInterface $widgetRepository,
        ProductServiceInterface $productService,
        ProductCatalogueRepositoryInterface $productCatalogueRepository
        

        )
    {
        $this->widgetRepository = $widgetRepository;
        $this->productService = $productService;
        $this->productCatalogueRepository = $productCatalogueRepository;
    }

    /**
     * Phân trang người dùng
     */
    public function paginate($request)
    {   
        $languageId = $this->getRegion();
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
        ];
       
        $perpage = $request->integer('perpage');
        $widget= $this->widgetRepository->pagination(
            $this->panigateSelect(),
            $condition,
            [ ],
            ['path' => 'widget.index'],
            $perpage,
            [],
            ['widgets.id', 'desc'],
            
        );
        return $widget;
    }
    
    public function create($request,$languageId)
    {
        return $this->handleTransaction(function () use ($request,$languageId) {
            $payload = $this->preparePayload($request,$languageId);
            $widget= $this->widgetRepository->create($payload);
            return true;
        });
    }

    public function update($id, $request, $languageId)
    {
        return $this->handleTransaction(function () use ($id, $request, $languageId) {

            $widget = $this->widgetRepository->findById($id);
            $payload = $this->preparePayload($request,$languageId);
            $widget= $this->widgetRepository->update($id,$payload);

            return true;
        });
    }

    public function destroy($id)
    {
        return $this->handleTransaction(function () use ($id) {
            if ($this->widgetRepository->destroy($id)) {
                return redirect()->route('widget.index')->with('success', 'widget deleted successfully.');
            }

            return redirect()->route('widget.index')->with('error', 'widget not found.');
        });
    }

    public function updateStatus($widget)
    {
        return $this->handleTransaction(function () use ($widget) {
            $payload = [$widget['field'] => $widget['value'] == 1 ? 2 : 1];
            $this->widgetRepository->update($widget['modelId'], $payload);
            return true;
        });
    }

  
    public function updateAllStatus($widget)
    {
        return $this->handleTransaction(function () use ($widget) {
            $payload = [$widget['field'] => $widget['value']];
            $this->widgetRepository->updateByWhereIn('id', $widget['id'], $payload);
            return true;
        });
    }
    public function saveTranslate($request, $languageId)
    {
        return $this->handleTransaction(function () use ($request,$languageId) {
            $widget= $this->widgetRepository->findById($request->input('widgetId'));
            $payload['description']= $widget->description; 
            $payload['description'][$request->input('translateId')] = $request->input('translate_description');
           
            $this->widgetRepository->update($request->input('widgetId'), $payload);
        
            return true;
        });
    }


    /**
     * Các phương thức hỗ trợ chung
     */
    private function preparePayload($request,$languageId)
    {
        $payload = $request->only($this->payload());
        $payload['model_id'] = $request->input('model_id.id');
        $payload['description'] = [
            $languageId => $payload['description']
        ];
        return $payload;
    }

    private function handleTransaction($callback)
    {
        DB::beginTransaction();
        try {
            $result = $callback();
            DB::commit();
            return $result;
        }catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Transaction Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            die("Error: " . $e->getMessage() . "<br>File: " . $e->getFile() . "<br>Line: " . $e->getLine());
        }
    }
    
    private function payload()
    {
        return [ 'name', 'keyword', 'model','short_code','description','album'];
    }
    private function panigateSelect()
    {
        return ['id','name','description','publish','order','keyword','short_code'];
    }

    /* FE */
    public function getWidgets(array $keys, $languageId)
    {
        $widgets = [];
        foreach ($keys as $key => $params) {
            $widgets[$key] = $this->findWidgetByKeyWord(
                $languageId,
                $params['keyword'],
                $params['options'] ?? []
            );
        }
        return $widgets;
       
    }
    
    private function findWidgetByKeyWord(int $languageId, string $keyword = '', $param = [])
    {
        $widget = $this->widgetRepository->findByCondition([
            ['keyword', '=', $keyword],
            config('apps.general.defaultPublish')
        ]);
    
        if (is_null($widget)) {
            return null;
        }
        $loadClass = loadClass($widget->model);
        $agru = $this->widgetAgru($languageId, $widget, $param);
        $objects = $loadClass->findByWhere(...$agru);
        if (!count($objects)) {
            return $objects;
        }
       
        $model = lcfirst(str_replace('Catalogue', '', $widget->model)).'s';
        
        foreach ($objects as $item) {
            $objectId = $item->id;
            $item->{$model} = collect($item->{$model});
            
            if (!empty($param['children'])) {
                // $item->childrens = $loadClass->findByWhere([
                //     ['lft', '>', $item->lft],
                //     ['rgt', '<', $item->rgt],
                //     config('apps.general.defaultPublish')
                // ], true,
                // [
                //     'languages' => function($query) use ($languageId){
                //         $query->where('language_id','=',$languageId);
                //     }
                // ]
                // );
                // $parameter = implode(',',$objectId);
                $childrens = $loadClass->recursiveCategory($objectId ,$widget->model,$languageId);
                if(count($childrens)){
                    $childId = array_column($childrens, 'ids');
                }
                $item->childrens = $childrens;
               
               
                $classModel = loadClass(lcfirst(str_replace('Catalogue', '', $widget->model)));
                if($item->rgt - $item->lft > 1){
                    $item->{$model} = $classModel->findObjectByCategoryIds($childId,$model,$languageId);
                }
                }
                if (!empty($param['promotion']) ) {
                    if(count($item->{$model})){
                        $productIds = $item->products->pluck('id')->toArray();
                        $item->{$model} = $this->productService->mapPromotionsToProducts($item->products, $productIds);
                    }else{
                       $item->promotion = $this->productService->findPromotionProduct([$objectId]);
                      
                     
                    }
                    
                       
                    }
                   
                    
                
          
        }
       
        $widget->objects = $objects;
        
        return $widget;
    }
    
    private function widgetAgru($languageId, $widget, $param)
    {
        $relation = [
            'languages' => function ($query) use ($languageId) {
                $query->where('language_id', $languageId);
            }
        ];
        $withCount = [];
    
        if (strpos($widget->model, 'Catalogue') !== false && !empty($param['object'])) {
            $model = lcfirst(str_replace('Catalogue', '', $widget->model)) . 's';
            

            $relation[$model] = function ($query) use ($param, $languageId) {
                $query->where('publish', 2)->take($param['limit'] ?? 20);
            };
    
            $relation["{$model}.languages"] = function ($query) use ($languageId) {
                $query->where('language_id', $languageId);
            };
    
            if (!empty($param['countObject'])) {
                $withCount[] = $model;
            }
        }
        elseif(!empty($param['object'])){
            $model = lcfirst($widget->model.'_catalogues');
            $relation[$model] = function($query) use ($languageId){
               $query->with('languages',function($query) use ($languageId){
                $query->where('language_id',$languageId);
               });
            };
        }
        return [
            'condition' => [config('apps.general.defaultPublish')],
            'flag' => true,
            'relation' => $relation,
            'param' => [
                'whereIn' => $widget->model_id,
                'whereInField' => 'id'
            ],
            'withCount' => $withCount
        ];
    }
    
    


}
