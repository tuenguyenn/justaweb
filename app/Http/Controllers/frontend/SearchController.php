<?php

namespace App\Http\Controllers\frontend;
use App\Http\Controllers\FrontendController;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use App\Services\Interfaces\ProductServiceInterface as ProductService;
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;
use App\Repositories\Interfaces\ReviewRepositoryInterface as ReviewRepository;


use Illuminate\Http\Request;

class SearchController extends FrontendController
{

    protected $productCatalogueRepository;
    protected $productService;
    protected $productRepository;
    protected $reviewRepository;

    public function __construct(
        ProductCatalogueRepository $productCatalogueRepository,
        ProductService $productRepository,
        ProductRepository $productService,
        ReviewRepository $reviewRepository,
    )
    {
       parent:: __construct();
       $this->productCatalogueRepository = $productCatalogueRepository;
       $this->productService = $productRepository;
       $this->productRepository = $productService;
       $this->reviewRepository = $reviewRepository;

    }

    public function search(Request $request)
    {
        
        $products = $this->productService->paginate($request);
        $productId = $products->pluck('id')->toArray();
        $keyword = $request->input('keyword');
       
      
        $productPromotion = [];
        if(count($products) &&!is_null($products)){ 
            $productPromotion = $this->productService->mapPromotionsToProducts($products, $productId);
        }
        $config = $this->config();
      
       
        
        $seo = (__('frontend.seo-login'));
       

        return view('frontend.product.product.search',compact(
          
            
            'seo',
          
            
            'products',
            'keyword',
            'productPromotion'
          
           
        ));
    }
    private function config(){
        return [
            'js'=>[
                'frontend/core/library/cart.js',
                'frontend/core/library/product.js',
                ]

        ];
    }
  
}
