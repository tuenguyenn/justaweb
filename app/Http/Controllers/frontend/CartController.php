<?php

namespace App\Http\Controllers\frontend;
use App\Http\Controllers\FrontendController;
use Cart;
use App\Services\Interfaces\CartServiceInterface;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
use App\Repositories\Interfaces\CartItemRepositoryInterface as CartItemRepository;

use App\Repositories\Interfaces\PromotionRepositoryInterface as PromotionRepository;
use App\Repositories\Interfaces\OrderRepositoryInterface as OrderRepository;
use App\Repositories\Interfaces\CustomerRepositoryInterface as CustomerRepository;
use Illuminate\Support\Facades\Auth;

use App\Models\Province;
use App\Models\District;  
use App\Models\Ward;  
use App\Http\Requests\StoreCartRequest;
use Illuminate\Http\Request;
use App\Classes\Vnpay;
use App\Classes\Momo;

use App\Mail\OrderMail;

class CartController extends FrontendController
{
    protected $cartService;
    protected $provinceRepository;
    protected $promotionRepository;
    protected $orderRepository;
    protected $customerRepository;
    protected $vnpay;
    protected $momo;
    protected $cartRepository;
    public function __construct(
        CartServiceInterface $cartService,
        ProvinceRepository $provinceRepository,
        PromotionRepository $promotionRepository,
        OrderRepository $orderRepository,
        CustomerRepository $customerRepository,
        CartItemRepository $cartRepository,
        Vnpay $vnpay,
        Momo $momo
    )
    {
        $this->cartService = $cartService;
        $this->provinceRepository = $provinceRepository;
        $this->promotionRepository = $promotionRepository;
        $this->orderRepository = $orderRepository;
        $this->vnpay = $vnpay;
        $this->momo = $momo;
        $this->customerRepository = $customerRepository;
        $this->cartRepository = $cartRepository;
        
        parent:: __construct();
    }

   
    public function checkout()
    {   
        $customerId =Auth::guard(name: 'customer')->id();
        $customer = null;
        $carts =Cart::instance('shopping')->content();
        $cartTotal = Cart::instance('shopping')->subtotal();
        if($customerId ){
            $customer = $this->customerRepository->findById($customerId);
            $carts = $this->cartRepository->findByWhere([
                ['customer_id','=',$customerId]
            ],true);
            $cartTotal = $carts ? $carts->map(fn ($item) => $item->price * $item->qty)->sum() : 0;
                      
        }
        $seo = (__('frontend.seo-checkout'));
        $province = $this->provinceRepository->all();
        
        $promotions = $this->promotionRepository->getPromotionByCartTotal();
        $validPromotions = $this->cartService->filterPromotions($cartTotal,$promotions);
        $config = $this->config();
        return view('frontend.cart.index',compact(
          
            'seo',
            'province',
            'config',
            'carts',
            'cartTotal',
            'validPromotions',
            'customer'

        ));
    
    }
    public function store(StoreCartRequest $request){
        $order =$this->cartService->order($request);
        if ($order['flag']) {
            $response = $this->paymentOnline($order);
            if($response['errorCode'] == 0){
                return redirect()->away($response['url']);
            }
            $this->mail($order['order']);
            
            return redirect()->route('cart.success',['code'=>$order['order']->code])->with('success', 'Đặt hàng thành công');
            
        }
    
      
        return redirect()->route('cart.checkout')->with('error', 'Đặt hàng không thành công');
    }
     
      private function mail($order){
        $to = $order->email;
        $cc = 'tuenguyenn2706@gmail.com';
        
        $data =['order'=>$order];
        \Mail::to($to)->cc($cc)->send(new OrderMail($data));
    }

    public function success( $code){

        $order = $this->orderRepository->findByCondition([
            ['code','=',$code]
        ]);
        $province = Province::find($order->province_id);
        $district = District::find($order->district_id);
        $ward = Ward::find($order->ward_id);
        
        $order->province_name = $province ? $province->name : '';
        $order->district_name = $district ? $district->name : '';
        $order->ward_name = $ward ? $ward->name : '';
       
        $seo = (__('frontend.seo-success'));
       
        $config = $this->config();
        return view('frontend.cart.success',compact(
          
            'seo',
            'order',
            'config',
         

        ));
    }
  
    
   public function paymentOnline($order) {
       
        switch ($order['order']->method) {
            case "vnpay":
                $response = $this->vnpay->payment($order);
                break;
            case "momo":
                $response = $this->momo->payment($order);
                break;
            default :
                $response['errorCode'] =1;
                break;
           
                

        }
        return $response;
   }
   
    private function config(){
        return [
            'language' =>  $this->language,
            'js'=>[
                'backend/library/location.js',
                'frontend/core/library/cart.js',

                
            ]
        ];
    }
}
