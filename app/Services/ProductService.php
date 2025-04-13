<?php
namespace App\Services;

use App\Services\Interfaces\ProductServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\RouterRepositoryInterface;
use App\Repositories\Interfaces\ProductVariantLanguageRepositoryInterface ;
use App\Repositories\Interfaces\ProductVariantAttributeRepositoryInterface ;
use App\Repositories\Interfaces\PromotionRepositoryInterface;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface;
use App\Repositories\Interfaces\AttributeRepositoryInterface;
use App\Services\Interfaces\ProductCatalogueServiceInterface;


use Ramsey\Uuid\Uuid;




/** 
 * Class ProductService
 * @package App\Services
 */
class ProductService extends BaseService implements ProductServiceInterface
{   
    protected $productRepository;
    protected $controllerName ='ProductController';
    protected $routerRepository;
    protected $productVariantLanguageRepository;
    protected $productVariantAttributeRepository;
    protected $promotionRepository;
    protected $attributeCatalogueRepository;
    protected $attributeRepository;
    protected $productCatalogueService;


    


    public function __construct(
        ProductRepositoryInterface $productRepository,
        RouterRepositoryInterface $routerRepository,
        ProductVariantLanguageRepositoryInterface $productVariantLanguageRepository,
        ProductVariantAttributeRepositoryInterface $productVariantAttributeRepository,
        PromotionRepositoryInterface $promotionRepository,
        AttributeCatalogueRepositoryInterface $attributeCatalogueRepository,
        AttributeRepositoryInterface $attributeRepository,
        ProductCatalogueServiceInterface $productCatalogueService

        )
    {
        $this->productRepository = $productRepository;
        $this->routerRepository = $routerRepository;
        $this->productVariantLanguageRepository = $productVariantLanguageRepository;
        $this->productVariantAttributeRepository = $productVariantAttributeRepository;
        $this->promotionRepository = $promotionRepository;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
        $this->attributeRepository = $attributeRepository;
        $this->productCatalogueService = $productCatalogueService;
    }

    /**
     * Phân trang người dùng
     */
    public function paginate($request,$productCatalogue = null, $extend=[])
    {   
        
        $languageId = $this->getRegion();
        
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
        ];
       
        $condition['product_catalogue_id']=$request->input('product_catalogue_id');
        $condition['where'] = [
            ['tb2.language_id', '=', $languageId],
        ];
    
        $perpage = $request->integer('perpage');
        $product= $this->productRepository->pagination(
            $this->panigateSelect(),
            $condition,
            [
                ['product_language as tb2', 'tb2.product_id', '=', 'products.id'],
                ['product_catalogue_product as tb3','products.id','=','tb3.product_id'],
               
            ],
            ['path' => ($extend['path']) ??  'product.index'  ,
            'groupBy'=>$this->panigateSelect()],
            $perpage,
            ['product_catalogues'],
            ['products.id', 'desc'],
            $this->whereRaw($request,$languageId,$productCatalogue),

        );
        return $product;
    }
    private function whereRaw($request ,$languageId,$productCatalogue = null)
    {   
        $rawCondition = []; 
        if ($request->integer('product_catalogue_id') > 0 || !is_null($productCatalogue)) {
            $catId = ($request->integer('product_catalogue_id') > 0) ? $request->integer('product_catalogue_id') : $productCatalogue->id;
            $rawCondition['whereRaw'] = [
                [
                    'tb3.product_catalogue_id IN (
                        SELECT id
                        FROM product_catalogues
                        JOIN product_catalogue_language ON product_catalogues.id = 
                        product_catalogue_language.product_catalogue_id
                        WHERE lft >= (SELECT lft FROM product_catalogues as pc WHERE pc.id = ?)
                        AND rgt <= (SELECT rgt FROM product_catalogues as pc WHERE pc.id = ?)
                        AND product_catalogue_language.language_id = '.$languageId.'
                    )',
                    [$catId, $catId]
                ]
            ];
        }
    return $rawCondition;
    }


    public function create($request,$languageId)
    {
        return $this->handleTransaction(function () use ($request, $languageId) {
           
            $payload = $this->preparePayload($request);

            $product = $this->productRepository->create($payload);
            if ($product->id) {
                $this->createLanguageAndSyncCatalogue($product, $request,$languageId);
                $router = $this->formatRouterPayload($product, $request,$this->controllerName,$languageId);
                $this->routerRepository->create($router);
               if($request->input('attribute')){
                    $this->createVariant($request, $product,$languageId);
               };
               $this->productCatalogueService->setAttribute($product,);
            }
            return true;
        });
    }
    private function createVariant($request, $product,$languageId){
        $payload =$request->only(['variant', 'productVariant','attribute']);
        
        $variant = $this->createVariantArray($payload,$product);
        $variants= $product->product_variants()->createMany($variant);
       
        $variantId= $variants->pluck('id');
        $productVariantLanguage =[];
        $variantAttribute =[];
        $attributeCombines =$this->combineAttribute(array_values($payload['attribute']));
       
        if(count($variantId)){
            foreach($variantId as $key =>$val){
                $productVariantLanguage[] = [
                    'product_variant_id' =>$val,
                    'language_id'=>$languageId,
                    'name'=>$payload['productVariant']['name'][$key]
                ];
                
                if(count($attributeCombines)){
                    foreach($attributeCombines[$key] as $attrId){
                        $variantAttribute[]=[
                            'product_variant_id'=> $val,
                            'attribute_id'=> $attrId
                        ];
                    }
                }
    
            }
        }
        $variantLanguage= $this->productVariantLanguageRepository->createBatch($productVariantLanguage);
       
        $variantAttribute= $this->productVariantAttributeRepository->createBatch($variantAttribute);
    }

    

    private function combineAttribute($attributes =[], $index =0){
        if($index === count($attributes)) return [[]];

        $subCombines =$this->combineAttribute($attributes,$index+1);
        $combine =[];
        foreach($attributes[$index] as $key =>$val){
            foreach($subCombines as $keySub =>$valSub){
                $combine[] = array_merge([$val], $valSub);
            }
        }
        return $combine;
    }
   
    private function createVariantArray(array $payload=[],$product):array{

        $variant = [];
        if(isset($payload['variant']['sku'])){
            foreach(($payload['variant']['sku']) as $key => $val){
                $vId= $payload['productVariant']['id'][$key] ?? '';
                $productVariantId = sortString($vId);
                $uuid = Uuid ::uuid5(Uuid::NAMESPACE_DNS,$product->id.','.$payload['productVariant']['id'][$key]);
                $price = $payload['variant']['price'][$key] ?? 0;
                $formattedPrice = str_replace('.', '', $price); 
                $variant[] =[
                    'uuid' => $uuid,
                    
                    'code' =>$productVariantId,
                    'quantity' => $payload['variant']['quantity'][$key] ?? 0,
                    'price' => $formattedPrice,
                    'sku' => $val,
                    'barcode' => ($payload['variant']['barcode'][$key]) ?? '',
                    'file_name' =>($payload['variant']['file_name'][$key]) ?? '',
                    'file_url' => ($payload['variant']['file_url'][$key]) ?? '',
                    'album' => ($payload['variant']['album'][$key]) ?? '',
                    'user_id'=> Auth::id(),
                ];

            }
            
        }
        return $variant;
    }
    public function update($id, $request, $languageId)
    {   
       
        return $this->handleTransaction(function () use ($id, $request, $languageId) {
            $oldProduct = $this->productRepository->findById($id);
            $oldProductCatalogue =$oldProduct->product_catalogue_id;
            $payload = $this->preparePayload($request);
            $product = $this->productRepository->returnModelUpdate($id, $payload);

            if ($product) {
                $product->languages()->detach();
                $this->createLanguageAndSyncCatalogue($product, $request, $languageId);
                $condition = [
                    ['module_id', '=', $product->id],
                    ['controllers', '=', 'App\Http\Controllers\Frontend\ProductController'],
                    ['language_id', '=', $languageId],
                ];
                $router = $this->routerRepository->findByCondition($condition);
                $payloadRouter =$this->formatRouterPayload($product, $request,$this->controllerName,$languageId);
                $this->routerRepository->update($router->id, $payloadRouter);
                $product->product_variants()->each(function($variant){
                    $variant->languages()->detach();
                    $variant->attributes()->detach();
                    $variant->delete();


                });
                if($request->input('attribute')){
                    $this->createVariant($request, $product,$languageId);
                };
                $this->productCatalogueService->setAttribute($product,$oldProductCatalogue);

            }

            return true;
        });
    }

    public function destroy($id)
    {
        return $this->handleTransaction(function () use ($id) {
            $product = $this->productRepository->findById($id);
            
            if ($this->deleteLanguageAndRouter($product,$id)) {
                return redirect()->route('product.index')->with('success', 'Xoá thành công.');
            }

           
        });
    }

    private function deleteLanguageAndRouter($product, $id)
    {   
        $payloadLanguage['language_id'] = $this->getRegion();
        $product->languages()->detach([$payloadLanguage['language_id'], $product->id]);
        
        $productRemain = $product->languages()->where('product_id', $product->id)->exists();
        if (!$productRemain) {
            $this->productRepository->destroy($id);
        }
        
        $condition = [
            ['module_id', '=', $product->id],
            ['controllers', '=', 'App\Http\Controllers\Frontend\ProductController'],
            ['language_id','=', $this->getRegion()],
        ];
        
        $router = $this->routerRepository->findByCondition($condition);
        
        if ($router) {  // Kiểm tra xem có dữ liệu không
            $this->routerRepository->forceDelete($router->id);
        }
        
        return true;  // Thêm dòng này để đảm bảo hàm trả về thành công
    }
    
    public function updateStatus($product)
    {
        return $this->handleTransaction(function () use ($product) {
            $payload = [$product['field'] => $product['value'] == 1 ? 2 : 1];
            $this->productRepository->update($product['modelId'], $payload);
            return true;
        });
    }

  
    public function updateAllStatus($product)
    {
        return $this->handleTransaction(function () use ($product) {
            $payload = [$product['field'] => $product['value']];
            $this->productRepository->updateByWhereIn('id', $product['id'], $payload);
            return true;
        });
    }

    /**
     * Các phương thức hỗ trợ chung
     */
    private function preparePayload($request)
    {
        $payload = $request->only($this->payload());
        $payload['price']=  ($payload['price'] ?? 0 );
        $payload['price']= str_replace('.', '', $payload['price']); 
        $payload['user_id'] = Auth::id();
        $payload['attributeCatalogue']= $this->formatJson($request,'attributeCatalogue');
        $payload['attribute']= $request->input('attribute');
        $payload['variant']= $this->formatJson($request,'variant');
        $payload['album'] = !empty($payload['album']) ? json_encode($payload['album']) : json_encode([]);

        return $payload;
    }

    private function createLanguageAndSyncCatalogue($product, $request,$languageId)
    {
        $payloadLanguage = $request->only($this->payloadLanguage());
        $payloadLanguage['language_id'] = $languageId;
        $payloadLanguage['product_id'] = $product->id;

        $this->productRepository->createPivot($product, $payloadLanguage, 'languages');
        $catalogue = $request->has('catalogue') && is_array($request->input('catalogue'))
                    ? array_unique(array_merge($request->input('catalogue'), [$request->product_catalogue_id]))
                    : [$request->product_catalogue_id];
    
        $product->product_catalogues()->sync($catalogue);
    
       
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
        return ['follow', 'publish', 'image', 'product_catalogue_id','price','code','made_in','attributeCatalogue','attribute','variant','album'];
    }

    private function payloadLanguage()
    {
        return ['name', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
    private function panigateSelect()
    {
        return ['products.id','products.publish', 'products.image','products.order','tb2.name','tb2.canonical','tb2.meta_title','products.product_catalogue_id','products.price'];
    }


    /* FE */
 

    public function mapPromotionsToProducts($products,$productIds)
    {   
      
        $promotions = $this->promotionRepository->findByProduct($productIds);
        $filteredPromotions = [];

        foreach ($promotions as $promotion) {
            $productId = $promotion['product_id'];

            if (!isset($filteredPromotions[$productId]) || $promotion['discountPrice'] > $filteredPromotions[$productId]['discountPrice']) {
                $filteredPromotions[$productId] = $promotion;
            }
        }

        $filteredPromotions = array_values($filteredPromotions);
       
        
        if (empty($promotions)) {
            return $products;
        }

        $promotionMap = [];
        foreach ($filteredPromotions as $promotion) {
            $promotionMap[$promotion->product_id] = $promotion;
        }

        return $products->transform(function ($product) use ($promotionMap) {
            if (isset($promotionMap[$product->id])) {
                $product->promotion = $promotionMap[$product->id];
            }
            return $product;
        });
    }
    public function findPromotionProduct($productId){
       
        $promotion = $this->promotionRepository->findByProduct($productId)->toArray();
       
        if (!empty($promotion) && is_array($promotion) && isset($promotion[0])) {
            $promotion = $promotion[0]; 
         }
    return $promotion;
    }
    public function getAttribute($product,$language){
        if(is_null($product->attribute)){
            return $product;
        }
        $attributeCatalogueId = array_keys($product->attribute);
        $attributeCatalogue = $this->attributeCatalogueRepository->getAttributeByIdWhereIn($attributeCatalogueId,$language,'attribute_catalogues.id');

        $attributeId = array_merge(...$product->attribute);
        $attribute = $this->attributeRepository->findAttributeByIdArray($attributeId,$language);
        if(!is_null($attributeCatalogue)){
            foreach($attributeCatalogue as $key => $val){
                $temp =[];
                foreach($attribute as $index => $item){
                    if($val->id === $item->attribute_catalogue_id){
                        $temp[] = $item;
                    }
                }
                $val->attributes = $temp;
            }
        }
        $product->attributeCatalogue = $attributeCatalogue;
       return $product;
    }
    public function filter($request){
        $perpage = $request->input('perpage') ?? 20;
        $param['priceQuery'] = $this->priceQuery($request);
        $param['attributeQuery'] =$this->attributeQuery($request);
        $param['rateQuery']= $this->rateQuery($request);
        $param['productCatalogueQuery'] =$this->productCatalogueQuery($request);
        $query = $this->combineQuery($param);
       
        $products= $this->productRepository->filter($query ,$perpage);
        return $products;
    
    }
    private function priceQuery($request){
       $price = $request->input('price');
       $minPrice = str_replace('.','',$price['price_min']);
       $maxPrice = str_replace('.','',$price['price_max']);
       $query['select'] = null;
       $query['join'] = null;
       $query['having'] = null;
       if($maxPrice > $minPrice){
            $query['join'] = [
                ['promotion_product_variant as ppv','ppv.product_id','=','products.id'],
                ['promotions','ppv.promotion_id','=','promotions.id'],

            ];
            $query['select'] = "
            (products.price - MAX(
                IF(promotions.maxDiscountValue != 0,
                    LEAST(
                        CASE
                            WHEN discountType = 'cash' THEN discountValue
                            WHEN discountType = 'percent' THEN products.price * discountValue / 100
                            ELSE 0
                        END,  
                        promotions.maxDiscountValue
                    ),
                    CASE
                        WHEN discountType = 'cash' THEN discountValue
                        WHEN discountType = 'percent' THEN products.price * discountValue / 100
                        ELSE 0
                    END
                )
            )) as discountPrice";
        
                $query['having'] = function ($query) use ($minPrice, $maxPrice){
              $query->havingRaw('discountPrice >= ? AND discountPrice<= ?',[ $minPrice, $maxPrice]);
            };
       }
       return $query;

    }
   
    private function attributeQuery($request){
        $attributes = $request->input('attributes');
        $query['select'] = null;
        $query['join'] = null;
        $query['where'] = null;
        $query['groupBy'] = null;
        if(!is_null($attributes) && count($attributes)  ){
          
            $query['select'] = ' pv.price AS variant_price, 
                                 pv.sku AS variant_sku'
                                ;
            $query['join'] = [
                ['product_variants as pv', 'pv.product_id','=','products.id'],
              
            ];
            foreach($attributes as $key => $value){
                $joinKey = 'tb'.($key +1);
                $query['join'][] =  ["product_variant_attribute as {$joinKey}" ,"{$joinKey}.product_variant_id",'=','pv.id'];
                $query['where'][] = function ($query) use($joinKey, $value){
                    foreach($value as $k => $attr){
                        $query->orWhere("{$joinKey}.attribute_id",'=', $attr);
                    }
                };
            }
            $query['groupBy'] =[ ['variant_price','variant_sku']];
       
              
        }
        return $query;
    }
    public function rateQuery($request){
      
        $rate = $request->input('rate');
      
        $query['orderBy'] = null;
        $query['join'] = [
            ['reviews','reviews.reviewable_id','=','products.id']
        ];
        if(!is_null($rate) && isset($rate) ){
            $query['orderBy'] = $rate;
        }
        return $query;

    }
    public function productCatalogueQuery($request){
        $productCatalogueId = $request->input('productCatalogueId');
       
        $query['join'] = null;
        $query['whereRaw'] = null;
        if($productCatalogueId>0){
            $query['join'] = [
                ['product_catalogue_product as pcp','pcp.product_id','=','products.id'],
            ];
            $query['whereRaw'] = [
                'pcp.product_catalogue_id IN (
                    SELECT id
                    FROM product_catalogues
                 
                    WHERE lft >= (SELECT lft FROM product_catalogues as pc WHERE pc.id = ?)
                    AND rgt <= (SELECT rgt FROM product_catalogues as pc WHERE pc.id = ?)
                  
                )',
                [$productCatalogueId, $productCatalogueId]
            ];
            
        }
        return $query;

    }
    private function combineQuery($param){
        $mergedArrays = [];

        foreach ($param as $array) {
            foreach ($array as $key => $value) {
                if (!isset($mergedArrays[$key])) {
                    $mergedArrays[$key] = [];
                }

                if (is_array($value)) {
                    $mergedArrays[$key] = array_merge($mergedArrays[$key], $value);
                } else {
                    $mergedArrays[$key][] = $value;
                }
            }
        }

        return $mergedArrays;
    }
}
