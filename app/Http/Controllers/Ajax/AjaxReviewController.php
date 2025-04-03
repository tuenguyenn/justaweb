<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\FrontendController;
use App\Services\Interfaces\ReviewServiceInterface;



use Illuminate\Http\Request; 

class AjaxReviewController extends FrontendController
{   
    protected $reviewService;

    public function __construct(
        ReviewServiceInterface $reviewService,

        
    )
    {
        $this->reviewService = $reviewService;
        parent:: __construct();

        $this->reviewService = $reviewService;
      
       
    }
  
    public function create(Request $request){
      
        
        $flag = $this->reviewService->create($request,$this->language);
       

        
        return response()->json($flag);

    }
    public function update(Request $request){
      
        
        $payload = $request->input();
        $res = $this->reviewService->update($payload,$this->language);

        return response()->json([
            'response'=> $res,
            'messages'=> 'Cập nhật thành công',
            'code' => ($res) ? 10 :11,
        ]);

    }
    public function delete(Request $request){
      
        
        $payload = $request->input();
        $res = $this->reviewService->delete($payload,$this->language);

        return response()->json([
            'response'=> $res,
            'messages'=> 'Xoá thành công',
            'code' => ($res) ? 10 :11,
        ]);

    }
  

    
}
