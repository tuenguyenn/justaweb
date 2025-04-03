<?php

namespace App\Http\Controllers\frontend;
use App\Http\Controllers\Controller;

use App\Repositories\Interfaces\OrderRepositoryInterface as OrderRepository;
use App\Services\Interfaces\OrderServiceInterface as OrderService;
use App\Services\Interfaces\CartServiceInterface as CartService;

use App\Classes\Momo;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\District;  
use App\Models\Ward;  
class MomoController extends Controller
{
   
    protected $momo;
    protected $orderRepository;
    protected $orderService;
    protected $cartService;
    public function __construct(
       
        Momo $momo,
        OrderRepository $orderRepository,
        OrderService $orderService,
        CartService $cartService
    )
    {
        
        $this->momo = $momo;
        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
        $this->cartService = $cartService;
        
    }

   
    public function momo_return(Request $request)
    {   
        $momoConfig = MomoConfig();
        $secretKey = $momoConfig['secretKey'];
        $partnerCode = $momoConfig['partnerCode'];
        $accessKey = $momoConfig['accessKey'];

        if (!empty($_GET)) {
            $rawData = "accessKey=" . $accessKey;
            $rawData .= "&amount=" . $_GET['amount'];
            $rawData .= "&extraData=" . $_GET['extraData'];
            $rawData .= "&message=" . $_GET['message'];
            $rawData .= "&orderId=" . $_GET['orderId'];
            $rawData .= "&orderInfo=" . $_GET['orderInfo'];
            $rawData .= "&orderType=" . $_GET['orderType'];
            $rawData .= "&partnerCode=" . $_GET['partnerCode'];
            $rawData .= "&payType=" . $_GET['payType'];
            $rawData .= "&requestId=" . $_GET['requestId'];
            $rawData .= "&responseTime=" . $_GET['responseTime'];
            $rawData .= "&resultCode=" . $_GET['resultCode'];
            $rawData .= "&transId=" . $_GET['transId'];
            

            $m2signature = $_GET['signature'];
        
            $partnerSignature = hash_hmac("sha256", $rawData, $secretKey);
            $momo= [
                'm2signature' => $m2signature,
                'partnerSignature' => $partnerSignature,
                'message' => $_GET['message']
            ];
            
            $order = $this->orderRepository->findByCondition([
                ['code','=',$_GET['orderId']],
            ]);
            $payload =[];
            if ($m2signature == $partnerSignature) {
                
                $payload['payment'] = 'paid';
               

                   
            } else {
                $payload['payment'] = 'false';
                
            }
            $this->orderService->update($order->id,$payload);
            $this->cartService->mail($order);

            $province = Province::find($order->province_id);
            $district = District::find($order->district_id);
            $ward = Ward::find($order->ward_id);
            
            $order->province_name = $province ? $province->name : '';
            $order->district_name = $district ? $district->name : '';
            $order->ward_name = $ward ? $ward->name : '';
           
            $seo = (__('frontend.seo-success'));
           
            $config = $this->config();
            $template = 'frontend.cart.component.momo';
            return view('frontend.cart.success',compact(
              
                'seo',
                'order',
                'config',
                'template',
                'momo'
             
    
            ));
        
          
        }
      
    
    }
    public function momo_ipn(Request $request){
   
        http_response_code(200); //200 - Everything will be 200 Oke
        if (!empty($_POST)) {
            $response = array();
            $momoConfig = MomoConfig();
            $secretKey = $momoConfig['secretKey'];
            $partnerCode = $momoConfig['partnerCode'];
            $accessKey = $momoConfig['accessKey'];
    
            try {
                $rawData = "accessKey=" . $accessKey;
                $rawData .= "&amount=" . $_POST['amount'];
                $rawData .= "&extraData=" . $_POST['extraData'];
                $rawData .= "&message=" . $_POST['message'];
                $rawData .= "&orderId=" . $_POST['orderId'];
                $rawData .= "&orderInfo=" . $_POST['orderInfo'];
                $rawData .= "&orderType=" . $_POST['orderType'];
                $rawData .= "&partnerCode=" . $_POST['partnerCode'];
                $rawData .= "&payType=" . $_POST['payType'];
                $rawData .= "&requestId=" . $_POST['requestId'];
                $rawData .= "&responseTime=" . $_POST['responseTime'];
                $rawData .= "&resultCode=" . $_POST['resultCode'];
                $rawData .= "&transId=" . $_POST['transId'];
        
        
                //Checksum
              
                $m2signature = $_POST['signature'];
            
                $partnerSignature = hash_hmac("sha256", $rawData, $secretKey);
                $payload =[];
                if ($m2signature == $partnerSignature) {
                    $order = $this->orderRepository->findByCondition([
                        ['code','=',$_POST['orderId']],
        
                    ]);
                    $payload['payment'] = 'paid';

                       
                } else {
                    $payload['payment'] = 'false';
                    
                }
                $this->orderService->update($order->id,$payload);

        
            } catch (Exception $e) {
                echo $response['message'] = $e;
            }
        
            $debugger = array();
            $debugger['rawData'] = $rawData;
            $debugger['momoSignature'] = $m2signature;
            $debugger['partnerSignature'] = $partnerSignature;
        
            if ($m2signature == $partnerSignature) {
                $response['message'] = "Received payment result success";
            } else {
                $response['message'] = "ERROR! Fail checksum";
            }
            $response['debugger'] = $debugger;
        }
    
    }
    private function config(){
        return [
            'language' =>  $this->language,
            'js'=>[
              
                'frontend/core/library/cart.js',
                'frontend/core/library/momo.js',

                
            ]
        ];
    }
}
