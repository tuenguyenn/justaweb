<?php
namespace App\Classes;



class Vnpay{

  
    public function __construct()
    {
        
    }
    public function payment($order){
    date_default_timezone_set('Asia/Ho_Chi_Minh');

    $order = $order['order'];
    $configVnPay = VnpayConfig();
    
    $vnp_Url = $configVnPay['vnp_Url'];
    $vnp_Returnurl = $configVnPay['vnp_Returnurl'];
    $vnp_TmnCode = $configVnPay['vnp_TmnCode'];
    $vnp_HashSecret = $configVnPay['vnp_HashSecret'];
  
    $vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
    $apiUrl = $configVnPay['apiUrl'];
    
    $startTime = date("YmdHis");
    $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));
    $vnp_TxnRef = $order->code; 
  
   
    $vnp_OrderInfo = (!empty($order->description) ? $order->description : 'Thanh toán đơn hàng #'.$order->code.'qua VnPay' );
 
    $vnp_OrderType = 'billpayment';
    $vnp_Amount = ($order['cart']['cartTotal'] - $order['promotion']['discount']) * 100;
  
    $vnp_Locale = 'vn';
 
    $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
    $vnp_ExpireDate = $expire;
 
    $inputData = array(
        "vnp_Version" => "2.1.0",
        "vnp_TmnCode" => $vnp_TmnCode,
        "vnp_Amount" => $vnp_Amount,
        "vnp_Command" => "pay",
        "vnp_CreateDate" => date('YmdHis'),
        "vnp_CurrCode" => "VND",
        "vnp_IpAddr" => $vnp_IpAddr,
        "vnp_Locale" => $vnp_Locale,
        "vnp_OrderInfo" => $vnp_OrderInfo,
        "vnp_OrderType" => $vnp_OrderType,
        "vnp_ReturnUrl" => $vnp_Returnurl,
        "vnp_TxnRef" => $vnp_TxnRef,
        "vnp_ExpireDate" => $vnp_ExpireDate,

      

       
    );
  
    if (isset($vnp_BankCode) && $vnp_BankCode != "") {
        $inputData['vnp_BankCode'] = $vnp_BankCode;
    }
   
    //var_dump($inputData);
    ksort($inputData);
    $query = "";
    $i = 0;
    $hashdata = "";
    foreach ($inputData as $key => $value) {
        if ($i == 1) {
            $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
        } else {
            $hashdata .= urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }
        $query .= urlencode($key) . "=" . urlencode($value) . '&';
    }
    
    $vnp_Url = $vnp_Url . "?" . $query;
    if (isset($vnp_HashSecret)) {
        $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
    }
    $returnData = array(
        'errorCode' => 0
        , 'message' => 'success'
        , 'url' => $vnp_Url);
    return $returnData;
        // vui lòng tham khảo thêm tại code demo
    
    }
}
