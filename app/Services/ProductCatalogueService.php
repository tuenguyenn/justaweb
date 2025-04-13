<?php

namespace App\Services;

use App\Services\Interfaces\ProductCatalogueServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface;
use App\Repositories\Interfaces\RouterRepositoryInterface;
use App\Repositories\Interfaces\AttributeRepositoryInterface;

use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Classes\Nestedsetbie;
use Illuminate\Support\Str;

class ProductCatalogueService extends BaseService implements ProductCatalogueServiceInterface
{
    protected $productCatalogueRepository;
    protected $attributeCatalogueRepository;
    protected $attributeRepository;

    protected $routerRepository;
    protected $nestedset;
    protected $controllerName = 'ProductCatalogueController';
    
    public function __construct(
        ProductCatalogueRepositoryInterface $productCatalogueRepository,
        RouterRepositoryInterface $routerRepository,
        AttributeCatalogueRepositoryInterface $attributeCatalogueRepository,
        AttributeRepositoryInterface $attributeRepository
    ) {
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->routerRepository = $routerRepository;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
        $this->attributeRepository = $attributeRepository;
    }

    public function paginate($request)
    {
        $languageId = $this->getRegion();
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
        ];
        $condition['where'] = [
            ['tb2.language_id', '=', $languageId],
        ];
        $columns = ['*'];
        $perpage = $request->integer('perpage');
        return $this->productCatalogueRepository->pagination(
            $columns,
            $condition,
            [
                ['product_catalogue_language as tb2', 'tb2.product_catalogue_id', '=', 'product_catalogues.id'],
            ],
            ['path' => 'product/catalogue/index'],
            $perpage,
            [],
            ['product_catalogues.lft', 'asc']
        );
    }

    public function create($request)
    {
        return $this->handleTransaction(function () use ($request) {
            $payload = $request->only($this->payload());
            $payload['user_id'] = Auth::id();
            $productCatalogue = $this->productCatalogueRepository->create($payload);
    
            if ($productCatalogue->id > 0) {
                $this->createLanguageAndRouter($request, $productCatalogue);
                $this->nestedset = new NestedSetBie([
                    'table' => 'product_catalogues',
                    'foreignkey' => 'product_catalogue_id',
                    'language_id' => $this->getRegion(),
                ]);
                $this->updateNestedSet();
            }
            return true;
        });
    }
    
    public function update($id, $request)
    {
        return $this->handleTransaction(function () use ($id, $request) {
            $payload = $request->only($this->payload());
            $this->productCatalogueRepository->update($id, $payload);
    
            $productCatalogue = $this->productCatalogueRepository->findById($id);
            $this->updateLanguageAndRouter($request, $productCatalogue);
            $this->nestedset = new NestedSetBie([
                'table' => 'product_catalogues',
                'foreignkey' => 'product_catalogue_id',
                'language_id' => $this->getRegion(),
            ]);
            $this->updateNestedSet();
    
            return true;
        });
    }
    private function payload()
    {
        return ['parent_id', 'follow', 'publish', 'image'];
    }

    private function payloadLanguage()
    {
        return ['name', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
    public function destroy($id)
    {
        return $this->handleTransaction(function () use ($id) {
            $productCatalogue = $this->productCatalogueRepository->findById($id);
    
            if ($this->deleteLanguageAndRouter($productCatalogue, $id)) {
                // Chỉ tạo NestedSet nếu đã xóa thành công
                $this->nestedset = new NestedSetBie([
                    'table' => 'product_catalogues',
                    'foreignkey' => 'product_catalogue_id',
                    'language_id' => $this->getRegion(),
                ]);
                $this->updateNestedSet(); // Cập nhật NestedSet
            }
            return true;
        });
    }
    public function updateStatus($productCatalogue)
    {
        return $this->handleTransaction(function () use ($productCatalogue) {
            $payload[$productCatalogue['field']] = $productCatalogue['value'] == 1 ? 2 : 1;
            $this->productCatalogueRepository->update($productCatalogue['modelId'], $payload);
            return true;
        });
    }

    public function updateAllStatus($productCatalogue)
    {
        return $this->handleTransaction(function () use ($productCatalogue) {
            $payload[$productCatalogue['field']] = $productCatalogue['value'];
            $this->productCatalogueRepository->updateByWhereIn('id', $productCatalogue['id'], $payload);
            return true;
        });
    }
    
    private function createLanguageAndRouter($request, $productCatalogue)
    {
        $payloadLanguage = $request->only($this->payloadLanguage());
        $payloadLanguage['language_id'] = $this->getRegion();
        $payloadLanguage['product_catalogue_id'] = $productCatalogue->id;
        $payloadLanguage['canonical']= Str ::slug( $payloadLanguage['canonical']);
        $this->productCatalogueRepository->createPivot($productCatalogue, $payloadLanguage, 'languages');
    
        $router = $this->formatRouterPayload($productCatalogue, $request, $this->controllerName,$this->getRegion());
        $this->routerRepository->create($router);
    }
   
    
    private function updateLanguageAndRouter($request, $productCatalogue)
    {
        $payloadLanguage = $request->only($this->payloadLanguage());
        $payloadLanguage['language_id'] = $this->getRegion();
        $payloadLanguage['product_catalogue_id'] = $productCatalogue->id;
        $payloadLanguage['canonical']= Str ::slug( $payloadLanguage['canonical']);

        $productCatalogue->languages()->detach([$payloadLanguage['language_id'], $productCatalogue->id]);
        $this->productCatalogueRepository->createPivot($productCatalogue, $payloadLanguage, 'languages');
    
        $condition = [
            ['module_id', '=', $productCatalogue->id],
            ['controllers', '=', 'App\Http\Controllers\Frontend\ProductCatalogueController'],
            ['language_id','=', $this->getRegion()],
        ];
        $router = $this->routerRepository->findByCondition($condition);
        $payloadRouter = $this->formatRouterPayload($productCatalogue, $request, $this->controllerName,$this->getRegion());
        $this->routerRepository->update($router->id, $payloadRouter);
    }
  
    
    private function deleteLanguageAndRouter($productCatalogue, $id)
    {
        $payloadLanguage['language_id'] = $this->getRegion();
    
        // Xóa ngôn ngữ
        $productCatalogue->languages()->detach([$payloadLanguage['language_id'], $productCatalogue->id]);
    
        // Kiểm tra còn ngôn ngữ nào không
        $productCatalogueRemain = $productCatalogue->languages()
            ->where('product_catalogue_id', $productCatalogue->id)
            ->exists();
    
        if (!$productCatalogueRemain) {
            // Xóa productCatalogue khỏi database
            $this->productCatalogueRepository->destroy($id);
            
            // Xóa router nếu có
            $condition = [
                ['module_id', '=', $productCatalogue->id],
                ['controllers', '=', 'App\Http\Controllers\Frontend\ProductCatalogueController'],
                ['language_id', '=', $this->getRegion()],
            ];
            $router = $this->routerRepository->findByCondition($condition);
    
            if ($router) {
                $this->routerRepository->forceDelete($router->id);
            }
    
            return true; // Trả về true nếu xóa thành công
        }
    
        return false; // Không xóa được
    }
    

   

    private function handleTransaction($callback)
{
    DB::beginTransaction();
    try {
        $result = $callback();
        DB::commit();
        return $result;
    } catch (\Exception $e) {
        DB::rollBack();

        // Ghi lỗi vào log để theo dõi
        \Log::error('Transaction Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

        // Hiển thị lỗi chi tiết ra màn hình
        die("Error: " . $e->getMessage() . "<br>File: " . $e->getFile() . "<br>Line: " . $e->getLine());
    }
}
    public function setAttribute($product){{
       
        $attr = ($product->attribute) ??  [];
      
        $productCatalogue = $this->productCatalogueRepository->findById($product->product_catalogue_id);
        $productCatalogueAttribute =json_decode($productCatalogue->attribute,true);
        if(!is_array($productCatalogueAttribute)){
            $payload['attribute']=$attr;
        }else{
          
            $mergeArr = $productCatalogueAttribute;
            foreach($attr  as $key => $val){
                if(!isset($mergeArr[$key])){
                    $mergeArr[$key] = $val;
                }else{
                    $mergeArr[$key] = array_values(array_unique(array_merge($mergeArr[$key],$val)));
                }
            }
       
       
        $flatArray = array_merge(...$mergeArr);
       $attributeList = $this->attributeRepository->findAttributeProductCatalogueAndProductArray($flatArray, $productCatalogue->id);
       $payload['attribute'] = array_map(function($newArray) use ($attributeList){
         return array_intersect($newArray,$attributeList->all());
       },$mergeArr);
        }
    
        $flag =$this->productCatalogueRepository->update($product->product_catalogue_id,$payload);
        return $flag;
    }}
 
    public function getFilters(array $attribute =[],$languageId){
        
       $attrCatalogueId = array_keys($attribute);
      
       $attrId = array_unique(array_merge(...$attribute));
       ;
       $attributeCatalogues = $this->attributeCatalogueRepository->findByWhere([
        config('apps.general.defaultPublish')
        ],
        true,
        ['languages'=>function($query) use ($languageId){
            $query->where('language_id', $languageId);
        }],
        ['id','asc'],
        [
            'whereIn'=>$attrCatalogueId,
            'whereInField'=> 'id'
        ]);
        $attributes = $this->attributeRepository->findByWhere([
            config('apps.general.defaultPublish')
            ],
            true,
            ['languages'=>function($query) use ($languageId){
                $query->where('language_id', $languageId);
            }],
            ['id','asc'],
            [
                'whereIn'=>$attrId,
                'whereInField'=> 'id'
            ]);
      
        foreach($attributeCatalogues as $key => $value) {
            $attributeItem =[];
            foreach($attributes as $index => $attribute) {
               
                if($attribute->attribute_catalogue_id == $value->id) {

                  $attributeItem[]= $attribute;
                  $value->setAttribute('attrs',$attributeItem);
                }
            }
        }
        return $attributeCatalogues;
    }

}
