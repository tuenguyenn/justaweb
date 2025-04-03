<?php

namespace App\Http\Controllers\frontend;
use App\Http\Controllers\FrontendController;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use App\Services\Interfaces\ProductServiceInterface as ProductService;
use App\Services\Interfaces\ProductCatalogueServiceInterface as ProductCatalogueService;

use Illuminate\Http\Request;

class ProductCatalogueController extends FrontendController
{

    protected $productCatalogueRepository;
    protected $productService;
    protected $productCatalogueService;


    public function __construct(
        ProductCatalogueRepository $productCatalogueRepository,
        ProductService $productRepository,
        ProductService $productService,
        ProductCatalogueService $productCatalogueService
    )
    {
       parent:: __construct();
       $this->productCatalogueRepository = $productCatalogueRepository;
       $this->productService = $productRepository;
       $this->productCatalogueService = $productCatalogueService;
    }

    public function index($id ,$request)
    {      
        $productCatalogue = $this->productCatalogueRepository->getProductCatalogueById($id,$this->language);
        $products=  $this->productService->paginate($request,$productCatalogue,['path'=>$productCatalogue->canonical]);
        $productId = $products->pluck('id')->toArray();
        
        $filters = $this->filter($productCatalogue);
      
        $productPromotion = [];
        if(count($products) &&!is_null($products)){ 
            $productPromotion = $this->productService->mapPromotionsToProducts($products, $productId);
        }
        $config = $this->config();
        $seo = seo($productCatalogue);
        $breadcumb = $this->productCatalogueRepository->breadcumb($productCatalogue,$this->language);
        return view('frontend.product.catalogue.index',compact(
          
            'config',
            'seo',
            'productCatalogue',
            'breadcumb',
            'products',
            'productPromotion',
            'filters'
        ));
    }
    private function config(){
        return [
            'js'=>[
                'frontend/core/library/filter.js'
            ]
        ];
    }
    public function filter($productCatalogue){
        $filters = null ;
        if(!is_null($productCatalogue->attribute)){
            $filters = $this->productCatalogueService->getFilters(json_decode($productCatalogue->attribute,true),$this->language);
        }
        return $filters;

    }
  
}
