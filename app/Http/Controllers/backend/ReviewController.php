<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\ReviewServiceInterface as ReviewService;
use App\Repositories\Interfaces\ReviewRepositoryInterface as ReviewRepository;
use App\Models\Province;
use App\Models\District;  
use App\Models\Ward; 

class ReviewController extends Controller
{
    protected $reviewService;
    protected $reviewRepository;
    public function __construct(
        ReviewService $reviewService,
        ReviewRepository $reviewRepository,

    ) {
        $this->reviewService = $reviewService;
        $this->reviewRepository = $reviewRepository;
      
    }
    public function index(Request $request)
    {
        
        // $this->authorize('module','slide.edit');
        $reviews = $this->reviewService->paginate($request);
        
        
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ]
        ];
        
        $config['seo'] = __('messages.slide');
        $template = 'backend.review.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'reviews',

        ));
    }
    
   

    
   
   
    public function destroy($id ){
        $this->authorize('module','slide.edit');

        
        if ($this->reviewService->destroy($id)) {
            return redirect()->route('slide.index')->with('success', 'Xoá thành công');
        }
        return redirect()->route('slide.index')->with('error', 'Xoá không thành công');
        
    }
    

  

  
 
  
}
