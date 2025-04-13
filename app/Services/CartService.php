<?php
namespace App\Services;
use App\Services\Interfaces\CartServiceInterface;
use Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\ProductVariantRepositoryInterface;
use App\Repositories\Interfaces\PromotionRepositoryInterface;
use App\Repositories\Interfaces\CartItemRepositoryInterface;
use Illuminate\Support\Str;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Mail\OrderMail;



/** 
 * Class CustomerService
 * @package App\Services
 */
class CartService implements CartServiceInterface
{   
    protected $productRepository;
    protected $productVariantRepository;
    protected $promotionRepository;
    protected $orderRepository;
    protected $cartItemRepository;

    public function __construct( 
        ProductRepositoryInterface $productRepository,
        ProductVariantRepositoryInterface $productVariantRepository,
        PromotionRepositoryInterface $promotionRepository,
        OrderRepositoryInterface $orderRepository,
        CartItemRepositoryInterface $cartItemRepository
        
        

    )
    {

        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->promotionRepository = $promotionRepository;
        $this->orderRepository = $orderRepository;
        $this->cartItemRepository = $cartItemRepository;
    }
    public function create($request,$languageId = 1) {
        DB::beginTransaction();
        try {
            $payload = $request->input();
      
            $data= [];
            $product = $this->productRepository->findById($payload['id'],['*'],
             ['languages' => function ($query) use($languageId){
                 $query->where('language_id',$languageId);
             }]
            );
          
            $data =[
             'id' => $product->id,
             'name' => $product->languages->first()->pivot->name,
             'qty' =>$payload['quantity'],
             'price' =>$payload['price'],

            ];
            $data['options']=[
                'image' => $payload['image'],
                'price_original' => $payload['price_original'],
                'canonical' => $payload['canonical']
            ];
            if(isset($payload['attribute_id']) && count($payload['attribute_id'])){
                 $attrId = sortArray($payload['attribute_id']);
                 $variant = $this->productVariantRepository->findVariant($attrId,$payload['id'],$languageId);
              
                 $data['id'] = $product->id.'_'.$variant->uuid;
                 $data['name'] = $product->languages->first()->pivot->name .' '.$variant->languages->first()->pivot->name;
                 $data['options']['attribute'] = $payload['attribute_id'];
                 $data['options']['attributeName'] = $variant->languages->first()->pivot->name;

            
                 
            }
            if(Auth::guard('customer')->id()){
              
                $data['customer_id'] = Auth::guard('customer')->id();
                $data['rowId'] = Str::uuid();
                $cart =$this->cartItemRepository->create($data,true);
               
               
            }
            
            Cart::instance('shopping')->add($data);
            DB::commit(); // Nếu dùng transaction

            return true;
        }
        catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
     
    }
    public function mail($order){
        $to = $order->email;
        $cc = 'tuenguyenn2706@gmail.com';
        
        $data =['order'=>$order];
        \Mail::to($to)->cc($cc)->send(new OrderMail($data));
    }

    public function update($payload, $languageId = 1) {
        DB::beginTransaction();
        try {
            $customerId = Auth::guard('customer')->id(); 
           
            if ($customerId > 0) {
               
                $cartItem =   $this->cartItemRepository->findByWhere([
                    ['rowId','=',$payload['rowId']],
                ]);
                if ($cartItem)
                {
                    $cartItem = $this->cartItemRepository->updateByWhere(
                        [['rowId', '=', $payload['rowId']]], 
                        ['qty' => $payload['qty']], 
                        true 
                    );
                    
                }
               
               
                $productSubtotal = $cartItem ? $cartItem->price * $cartItem->qty : 0;
                $carts = $this->cartItemRepository->findByWhere([
                    ['customer_id','=',$customerId]
                ],true);
               
                $total = $carts ? $carts->map(fn ($item) => $item->price * $item->qty)->sum() : 0;
                DB::commit();
                return [
                    'cart' => $cartItem,
                    'total' => $total,
                    'count' => $carts ? $carts->sum('qty') : 0, // ✅ sum() chỉ nhận tên cột
                    'productSubtotal' => $productSubtotal,
                ];
                
            } else {
                Cart::instance('shopping')->update($payload['rowId'], $payload['qty']);
                $cart = Cart::instance('shopping')->content();
                $cartRowId = Cart::instance('shopping')->get($payload['rowId']);
                $productSubtotal = $cartRowId->price * $cartRowId->qty;
    
              
                return [
                    'cart' => $cart,
                    'total' => Cart::instance('shopping')->subtotal(),
                    'count' => Cart::instance('shopping')->count(),
                    'productSubtotal' => $productSubtotal
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function delete($payload) {
        DB::beginTransaction();
        try {
            $customerId = Auth::guard('customer')->id(); 
            
            if ($customerId > 0) {
                $cartItem = $this->cartItemRepository->findByWhere([
                    ['rowId', '=', $payload['rowId']],
                    ['customer_id', '=', $customerId]
                ]);
    
                if ($cartItem) {
                    $this->cartItemRepository->deleteByWhere([
                        ['rowId', '=', $payload['rowId']],
                        ['customer_id', '=', $customerId]
                    ]);
                }
    
                $carts = $this->cartItemRepository->findByWhere([
                    ['customer_id', '=', $customerId]
                ], true);
    
                $total = $carts ? $carts->map(fn ($item) => $item->price * $item->qty)->sum() : 0;
    
                DB::commit();
                return [
                    'cart' => $carts,
                    'total' => $total,
                    'count' => $carts ? $carts->sum('qty') : 0,
                ];
            } else {
                // Xử lý khi user chưa đăng nhập (giỏ hàng session)
                Cart::instance('shopping')->remove($payload['rowId']);
                $cart = Cart::instance('shopping')->content();
    
                return [
                    'cart' => $cart,
                    'total' => Cart::instance('shopping')->subtotal(),
                    'count' => Cart::instance('shopping')->count(),
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
   
    function filterPromotions($cartTotal, $promotions)
    {
        $cartTotal = (float) $cartTotal;
    
        return collect($promotions)->map(function ($promotion) use ($cartTotal) {
            $amountFromList = $promotion['discountInformation']['info']['amountFrom'] ?? [];
            $amountValueList = $promotion['discountInformation']['info']['amountValue'] ?? [];
            $amountTypeList = $promotion['discountInformation']['info']['amountType'] ?? [];
    
            $amountFromList = array_map('floatval', $amountFromList);
            $amountValueList = array_map('floatval', $amountValueList);
    
            // Tìm các mức hợp lệ
            $validAmounts = [];
            $discountAmounts = [];
    
            foreach ($amountFromList as $key => $amount) {
                if ($cartTotal >= $amount) {
                    $validAmounts[] = $amount;
    
                    // Tính giá giảm
                    $discountValue = $amountValueList[$key] ?? 0;
                    $discountType = $amountTypeList[$key] ?? 'cash';
                    $discountAmount = ($discountType === 'percent') ? ($cartTotal * $discountValue / 100) : $discountValue;
    
                    $discountAmounts[] = $discountAmount;
                }
            }
    
            $promotion['validAmounts'] = array_values($validAmounts);
            $promotion['maxDiscount'] = !empty($discountAmounts) ? max($discountAmounts) : 0;
    
            return $promotion;
        })
        ->sortByDesc('maxDiscount') 
        ->toArray();
    }
    public function order($request){
        DB::beginTransaction();
        try {
            $customerId = Auth::guard('customer')->id(); 
            $payload = $this->preparePayload($request,);
            $order = $this->orderRepository->create($payload);
            if($order->id >0){
                $this->createOrderProduct($payload,$order,$request);
               
               
            }
            Cart::instance('shopping')->destroy();
            if ($customerId > 0) {
   
                $this->cartItemRepository->deleteByWhere([
                      ['customer_id', '=', $customerId]
                  ]);
                
            }
            DB::commit();
            return [
                'flag' => true,
                'order'=> $order,
            ];

         
        }
        catch (\Exception $e) {
            echo $e->getMessage();die();    
            return [
                'flag' => false,
                'order'=> null,
            ];
        }
    }
   
    private function preparePayload($request)  {
       
        $cart = Cart::instance('shopping')->content();
        $cartTotal = Cart::instance('shopping')->subtotal();
       
        $payload = $request->except('_token','create','voucher');
        $payload['code'] = time();
        $payload['total_amount'] = $cartTotal-$payload['discount'];
        $payload['cart']['cartTotal'] = $cartTotal;
        $payload['cart']['detail'] = $cart;
        $payload['promotion']['discount'] = $payload['discount'];
        $payload['promotion']['name'] = $payload['promotionName'];
        $payload['confirm'] = 'pending';
        $payload['payment'] = 'unpaid';
        $payload['delivery'] = 'pending';
        $payload['shipping'] = 0;
        $customerId = Auth::guard('customer')->id(); 
           
        if ($customerId > 0) {
            $payload['customer_id'] = $customerId;
        }
        unset($payload['promotionName']);
        unset($payload['discount']);
        return $payload;          
            
        
    }
    private function createOrderProduct($payload,$order,$request){
        
        $temp =[];
       
        if(!is_null($payload['cart']['detail'])){
            foreach ($payload['cart']['detail'] as $key => $value) {
                $extract = explode('_',$value->id);
                $temp[] =[
                    'product_id' => $extract[0],
                    'uuid'=> ($extract[1]) ?? null,
                    'name'=> $value->name,
                    'price' => $value->price,
                    'qty' => $value->qty,
                    'priceOriginal'=> $value->options->price_original,
                    'option'=> json_encode($value->options)

                ];
               
                
            }
           
        }
        $order->products()->sync($temp);
    }
    public function paymentOnline($method){
        switch ($method){
            
            case 'momo':
                // return $this->paypalPayment();
                break;
            case 'zalopay':
                // return $this->bankTransferPayment();
                break;
            case 'vnpay':
                // return $this->cashOnDeliveryPayment();
                break;
        }
    }
    
}   