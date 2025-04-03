<?php

namespace App\Http\Controllers\frontend;
use App\Http\Controllers\Controller;

use App\Repositories\Interfaces\OrderRepositoryInterface as OrderRepository;
use App\Services\Interfaces\OrderServiceInterface as OrderService;
use App\Services\Interfaces\CartServiceInterface as CartService;


use App\Classes\Vnpay;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\District;  
use App\Models\Ward;  
class VnpayController extends Controller
{
   
    protected $vnpay;
    protected $orderRepository;
    protected $orderService;
    protected $cartService;
    public function __construct(
       
        Vnpay $vnpay,
        OrderRepository $orderRepository,
        OrderService $orderService,
        CartService $cartService
    )
    {
        
        $this->vnpay = $vnpay;
        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
        $this->cartService = $cartService;
        
    }

   
    public function vnpay_return(Request $request)
    {
       
        
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $orderCode = $request->input('vnp_TxnRef');
    $configVnPay = VnpayConfig();
    
    $vnp_Url = $configVnPay['vnp_Url'];
    $vnp_Returnurl = $configVnPay['vnp_Returnurl'];
    $vnp_TmnCode = $configVnPay['vnp_TmnCode'];
    $vnp_HashSecret = $configVnPay['vnp_HashSecret'];
  
    $vnp_apiUrl = $configVnPay['vnp_apiUrl'];
    $apiUrl = $configVnPay['apiUrl'];

    $startTime = date("YmdHis");
    $expire = date('YmdHis',strtotime('+15 minutes',strtotime($startTime)));

        $vnp_SecureHash = $_GET['vnp_SecureHash'];
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        if ($secureHash == $vnp_SecureHash) {
            if ($_GET['vnp_ResponseCode'] == '00') {
                
                $order = $this->orderRepository->findByCondition([
                    ['code','=',$orderCode],
                ]);
                $this->cartService->mail($order);
               
                $province = Province::find($order->province_id);
                $district = District::find($order->district_id);
                $ward = Ward::find($order->ward_id);
                
                $order->province_name = $province ? $province->name : '';
                $order->district_name = $district ? $district->name : '';
                $order->ward_name = $ward ? $ward->name : '';
               
                $seo = (__('frontend.seo-success'));
               
                $config = $this->config();
                $template = 'frontend.cart.component.vnpay';
                return view('frontend.cart.success',compact(
                  
                    'seo',
                    'order',
                    'config',
                    'template',
                    'secureHash',
                    'vnp_SecureHash'
                 
        
                ));
               
            } 
            else {
                echo "GD Khong thanh cong";
                }
        } else {
            echo "Chu ky khong hop le";
            }
        
       
    
    }
    public function vnpay_ipn(Request $request){
   
    
     $configVnPay = VnpayConfig();
    
     $vnp_Url = $configVnPay['vnp_Url'];
     $vnp_Returnurl = $configVnPay['vnp_Returnurl'];
     $vnp_TmnCode = $configVnPay['vnp_TmnCode'];
     $vnp_HashSecret = $configVnPay['vnp_HashSecret'];
   
     $vnp_apiUrl = $configVnPay['vnp_apiUrl'];
     $apiUrl = $configVnPay['apiUrl'];
 
     $startTime = date("YmdHis");
     $expire = date('YmdHis',strtotime('+15 minutes',strtotime($startTime)));
    $inputData = array();
    $returnData = array();
    
    foreach ($_GET as $key => $value) {
        if (substr($key, 0, 4) == "vnp_") {
            $inputData[$key] = $value;
        }
    }
    
    $vnp_SecureHash = $inputData['vnp_SecureHash'];
    unset($inputData['vnp_SecureHash']);
    ksort($inputData);
    $i = 0;
    $hashData = "";
    foreach ($inputData as $key => $value) {
        if ($i == 1) {
            $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
        } else {
            $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }
    }
    
    $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
    $vnpTranId = $inputData['vnp_TransactionNo']; //Mã giao dịch tại VNPAY
    $vnp_BankCode = $inputData['vnp_BankCode']; //Ngân hàng thanh toán
    $vnp_Amount = $inputData['vnp_Amount']/100; // Số tiền thanh toán VNPAY phản hồi
    
    $Status = 0; // Là trạng thái thanh toán của giao dịch chưa có IPN lưu tại hệ thống của merchant chiều khởi tạo 
    
    $orderId = $inputData['vnp_TxnRef'];
  
    try {
        
        if ($secureHash == $vnp_SecureHash) {
          
            $order = $this->orderRepository->findByCondition([
                ['code','=',$orderId],

            ]);
            $payload =[];
            if ($order != NULL) {
                $orderAmount = $order['cart']['cartTotal'] - $order['promotion']['discount'];
                if($orderAmount == $vnp_Amount) //Kiểm tra số tiền thanh toán của giao dịch: giả sử số tiền 
                
                {
                    if ($order['payment'] == 'unpaid' ) {
                        if ($inputData['vnp_ResponseCode'] == '00' || $inputData['vnp_TransactionStatus'] == '00') {
                            $payload['payment'] = 'paid';
                        }else{
                            $payload['payment'] = 'false';
                        }
                        $this->orderService->update($order->id,$payload);
                        $this->cartService->mail($order);
                        $returnData['RspCode'] = '00';
                        $returnData['Message'] = 'Confirm Success';
                    } else {
                        $returnData['RspCode'] = '02';
                        $returnData['Message'] = 'Order already confirmed';
                    }
                }
                else {
                    $returnData['RspCode'] = '04';
                    $returnData['Message'] = 'invalid amount';
                }
            } else {
                $returnData['RspCode'] = '01';
                $returnData['Message'] = 'Order not found';
            }
        } else {
            $returnData['RspCode'] = '97';
            $returnData['Message'] = 'Invalid signature';
        }
    } catch (Exception $e) {
        $returnData['RspCode'] = '99';
        $returnData['Message'] = 'Unknow error';
    }
    //Trả lại VNPAY theo định dạng JSON
    echo json_encode($returnData);
            
    }
    private function config(){
        return [
            'language' =>  $this->language,
            'js'=>[
              
                'frontend/core/library/cart.js',
                'frontend/core/library/vnpay.js',

                
            ]
        ];
    }
}
