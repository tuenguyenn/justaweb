<?php

namespace App\Http\Controllers\frontend;
use App\Http\Controllers\FrontendController;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use App\Services\Interfaces\ProductServiceInterface as ProductService;
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;
use App\Repositories\Interfaces\ReviewRepositoryInterface as ReviewRepository;


use Illuminate\Http\Request;

class ProductController extends FrontendController
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

    public function index($id ,$request)
    {     
        $languageId = $this->language;
      
        $product=  $this->productRepository->getProductById($id,$this->language);
        
        $product->promotion = $this->productService->findPromotionProduct([$id]);
        $productCatalogue = $this->productCatalogueRepository->getProductCatalogueById($product->product_catalogue_id,$this->language);
       
        $product = $this->productService->getAttribute($product,$this->language);
        $category = $this->productCatalogueRepository->all([
            'languages' => function ($query) use ($languageId) {
                $query->where('language_id', $languageId);
            }
        ]);
    
        
        $config = $this->config();  
        $seo = seo($product);
        $breadcumb = $this->productCatalogueRepository->breadcumb($productCatalogue,$this->language);

        
        return view('frontend.product.product.index',compact(
          
            'config',
            'seo',
            'productCatalogue',
            'breadcumb',
            'product',
            'category',
           
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
