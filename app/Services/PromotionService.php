<?php
namespace App\Services;
use App\Services\Interfaces\PromotionServiceInterface;
use App\Services\BaseService;
use App\Enums\PromotionEnum;
use App\Repositories\Interfaces\PromotionRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



/** 
 * Class PromotionService
 * @package App\Services
 */
class PromotionService extends BaseService implements PromotionServiceInterface
{   
    protected $promotionRepository;
    public function __construct(
        PromotionRepositoryInterface $promotionRepository,
        )
    {
        $this->promotionRepository = $promotionRepository;
    }
    /**
     * Phân trang người dùng
     */
    public function paginate($request)
    {   
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
        ];
       
        $perpage = $request->integer('perpage');
        $promotion= $this->promotionRepository->pagination(
            $this->panigateSelect(),
            $condition,
            [ ],
            ['path' => 'promotion.index'],
            $perpage,
            [],
            ['promotions.id', 'desc'],
            
        );
        return $promotion;
    }
    
    public function create($request)
    {
        return $this->handleTransaction(function () use ($request) {
            
            $payload = $this->preparePayload($request);
            switch ($payload['method']){
                case PromotionEnum :: ORDER_AMOUNT_RANGE;
                    $payload['discountInformation']=$this->orderByRange($request);
                    $promotion= $this->promotionRepository->create($payload);
                    break;
                case PromotionEnum ::PRODUCT_AND_QUANTITY;
                    $get = $request->input('product_and_quantity');
                    
                    $payload['discountInformation'] =$this->productAndQuantity($request,$payload);
                    $payload['maxDiscountValue'] = str_replace('.', '', $get['maxDiscountValue']);
                    $payload['discountValue'] = str_replace('.', '', $get['discountValue']);
                    $payload['discountType' ] = $get['discountType'];
                    $promotion= $this->promotionRepository->create($payload);
                  
                    if($promotion->id && $payload['discountInformation']['info']['model']== 'Product')
                    {
                        $promotion->products()->detach();
                        $payloadRelation = $this->createPromotionProductVariant($promotion,$request);
                        $promotion->products()->sync($payloadRelation);
                    }
                    break;
                
            }
          
            return true;
        });
    }
    private function orderByRange($request){

        $data = $this->handleSourceAndCondidtion($request);
        $data['info'] = $request->input('promotion_order_amount_range');
        $data['info']['amountFrom'] =  str_replace('.', '', $data['info']['amountFrom']); 
        $data['info']['amountValue'] =  str_replace('.', '', $data['info']['amountValue']); 
        return $data;
   
        
    }
    private function productAndQuantity($request){
        
        $data = $this->handleSourceAndCondidtion($request);
        $data['info'] =  $request ->input('product_and_quantity')    ;
        $data['info']['quantity'] =  str_replace('.', '', $data['info']['quantity']); 
        $data['info']['model'] = $request ->input('module_type');
        $data['info']['object'] = $request->input('object');
      
        return $data;
        
    }
    
    private function handleSourceAndCondidtion($request){
        $data = [
            'source' => [
                'status'=> $request->input('source'),
                'data' => $request->input('sourceValue'),
            ],
            'apply' => [
                'status' => $request->input('applyStatus'),
                'data' => $request->input('applyValue'),
               
            ]
        ];
        if( !empty($request->input('applyValue')))
            {
                foreach($data['apply']['data'] as $key => $val){
                    $data['apply']['condition'][$val]= $request ->input($val);
                }   
        }
        else{
            $data['apply']['item'] = [];
        }
        return $data;

        
    }
    private function createPromotionProductVariant($promotion,$request){
        $object = $request->input('object');
        $payloadRelation=[];
        foreach($object['id'] as $key => $val){
            $payloadRelation[]=[
                'promotion_id'=> $promotion->id,
                'product_id' => $val,
                'variant_uuid'=>$object['uuid'][$key],
                'model' => $request->input('module_type')

            ];
        }
       
        return $payloadRelation;
    }

    public function update($id, $request)
    {
        return $this->handleTransaction(function () use ($id, $request) {
            $promotion = $this->promotionRepository->findById($id);
            
            $payload = $this->preparePayload($request);
    
            switch ($payload['method']) {
                case PromotionEnum::ORDER_AMOUNT_RANGE:
                    $promotion->products()->detach();
                    $payload['discountInformation'] = $this->orderByRange($request);
                    break;
    
                case PromotionEnum::PRODUCT_AND_QUANTITY:
                    $promotion->products()->detach();
                    $payload['discountInformation'] = $this->productAndQuantity($request);
                    $get = $request->input('product_and_quantity');
                    $payload['maxDiscountValue'] = str_replace('.', '', $get['maxDiscountValue']);
                    $payload['discountValue'] = str_replace('.', '', $get['discountValue']);
                    $payload['discountType' ] = $get['discountType'];
                    if ($payload['discountInformation']['info']['model'] == 'Product') {

                        $payloadRelation = $this->createPromotionProductVariant($promotion, $request);
                        
                        $promotion->products()->sync($payloadRelation);
                    }
                    break;
            }
    
            $this->promotionRepository->update($id, $payload);
    
            return true;
        });
    }
    

    public function destroy($id)
    {
        return $this->handleTransaction(function () use ($id) {
            if ($this->promotionRepository->destroy($id)) {
                return redirect()->route('promotion.index')->with('success', 'promotion deleted successfully.');
            }

            return redirect()->route('promotion.index')->with('error', 'promotion not found.');
        });
    }
    public function updateStatus($promotion)
    {
        return $this->handleTransaction(function () use ($promotion) {
            $payload = [$promotion['field'] => $promotion['value'] == 1 ? 2 : 1];
            $this->promotionRepository->update($promotion['modelId'], $payload);
            return true;
        });
    }

    public function updateAllStatus($promotion)
    {
        return $this->handleTransaction(function () use ($promotion) {
            $payload = [$promotion['field'] => $promotion['value']];
            $this->promotionRepository->updateByWhereIn('id', $promotion['id'], $payload);
            return true;
        });
    }
   

    private function preparePayload($request)
    {
        $payload = $request->only($this->payload());
        $payload['code'] = $payload['code'] ?? Str::upper(Str::random(10));
        $payload['neverEndDate'] = ($payload['neverEndDate']) ?? 'null';
        if( $payload['neverEndDate'] == 'accept'){
            $payload['endDate'] = null;
        }
       
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
        return [ 'name', 'code', 'description','method','startDate','endDate','neverEndDate'];
    }

   
    private function panigateSelect()
    {
        return ['id','name','description','code','startDate','endDate','publish','order','method','discountInformation'];
    }


}
