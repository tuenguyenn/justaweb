<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\FrontendController;
use App\Services\Interfaces\CartServiceInterface;
use Cart;


use Illuminate\Http\Request; 

class AjaxCartController extends FrontendController
{   
    protected $cartService;

    public function __construct(
        CartServiceInterface $cartService,

        
    )
    {
        $this->cartService = $cartService;
        parent:: __construct();

        $this->cartService = $cartService;
      
       
    }
  
    public function create(Request $request){
      
        
        $flag = $this->cartService->create($request,$this->language);
        $cart = Cart::instance('shopping')->content();

        
        return response()->json([
            'cart' => $cart,
            'messages'=> 'Đã thêm vào giỏ',
            'code' => ($flag) ? 10 :11,
        ]);

    }
    public function update(Request $request){
      
        
        $payload = $request->input();
        $res = $this->cartService->update($payload,$this->language);

        return response()->json([
            'response'=> $res,
            'messages'=> 'Cập nhật thành công',
            'code' => ($res) ? 10 :11,
        ]);

    }
    public function delete(Request $request){
      
        
        $payload = $request->input();
        $res = $this->cartService->delete($payload,$this->language);

        return response()->json([
            'response'=> $res,
            'messages'=> 'Xoá thành công',
            'code' => ($res) ? 10 :11,
        ]);

    }
  

    
}
