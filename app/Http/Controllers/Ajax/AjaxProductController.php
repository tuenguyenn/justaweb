<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;
use App\Services\Interfaces\ProductServiceInterface as ProductService;

use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use App\Repositories\Interfaces\ProductVariantRepositoryInterface as ProductVariantRepository;
use App\Repositories\Interfaces\PromotionRepositoryInterface as PromotionRepository;


use App\Models\Language;
use Illuminate\Http\Request; 

class AjaxProductController extends Controller
{   
    protected $productRepository;
    protected $productService;

    protected $productCatalogueRepository;
    protected $language;
    protected $productVariantRepository;
    protected $promotionRepository;


    public function __construct(
       ProductRepository $productRepository,
       ProductCatalogueRepository $productCatalogueRepository,
       ProductVariantRepository $productVariantRepository,
       PromotionRepository $promotionRepository,
       ProductService $productService,

       
    )
    {
        $this->productRepository = $productRepository;
        $this->productService = $productService;
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->promotionRepository = $promotionRepository;
        $this->middleware(function($request, $next){
            $locale = app()->getLocale();
            $language = Language::where("canonical", $locale)->first();
            $this->language =$language->id;
            return $next($request);
        });
       
    }
    public function loadProductPromotion(Request $request)
    {   
        $languageId= $this->language;
        $get = $request->input(); 
        $loadClass = $get['model'];

        $relation = []; 
        if($loadClass == 'Product'){
               
            $condition = [];
            if($get['keyword'] != null){
                $keywordCondition = [
                    'tb2.name', 'like', '%' . $get['keyword'].'%'
                ];
                array_push($condition ,$keywordCondition);

            }
            $objects = $this->productRepository->findProductPromotion($condition, $relation,$languageId);
        }else if($loadClass == 'ProductCatalogue'){
            $conditionArray['keyword'] =$get['keyword'];
            $conditionArray['where'] = [
                ['tb2.language_id', '=', $this->language] 
            ];
            $objects = $this->productCatalogueRepository->pagination(
            ['name','id as product_id',],
            $conditionArray,
            [
                ['product_catalogue_language as tb2', 'tb2.product_catalogue_id', '=', 'product_catalogues.id'],
            ],
            ['path' => 'product/catalogue/index'],
            20,
            [],
            ['product_catalogues.id', 'desc']);
            
        }
        
        return response()->json([
            'objects' => $objects,
            'model' => ($get['model']) ?? 'Product'
        ]);
    
           
    }
    public function loadVariant(Request $request){
        $language= $this->language;
        $get = $request->input();
        $attrId= $get['attribute_id'];
        $productId = $get['productId'];
        
        $attrId = sortArray($attrId);
        $variant = $this->productVariantRepository->findVariant($attrId,$productId,$language);
       
        $variant->promotion = $this->promotionRepository->findPromotionProductVariantUuid($variant->uuid);
        return response()->json([
            'variant' => $variant,
        ]);

    }
    public function filter(Request $request){
        $products = $this->productService->filter($request);
      
        $productId = $products->pluck('id')->toArray();

        $productPromotion = [];
        if(count($products) &&!is_null($products)){ 
            $productPromotion = $this->productService->mapPromotionsToProducts($products, $productId);
        }
       
        $html = $this->renderFilterProduct($productPromotion);
       
        return response()->json([
            'html' => $html,
        ]);
    }
    public function renderFilterProduct($products)
{
    $html = '';
    if (!is_null($products)) {
        $html .= '  <div class="uk-grid uk-grid-medium">';
        
        foreach ($products as $product) {
           
            $catName = $product->product_catalogues->first()->languages->first()->pivot->name;
            $productId = $product->id;
            $discountType = '';
            $discountPrice = 0;

            if (!empty($product['promotion'])) {
                if ($product['promotion']['discountType'] === 'percent') {
                    $discountType = $product['promotion']['discountValue'] . '%';
                } else {
                    $discountType = number_format($product['promotion']['discountValue']) . 'đ';
                }

                $discountPrice = $product['promotion']['discountPrice'] ?? 0;
            }

            // Hình ảnh sản phẩm
            $image = image($product['image'] ?? asset('frontend/resources/img/product-' . rand(1, 10) . '.jpg'));
            $name = $product->languages->first()->pivot->name;
            $canonical = write_url($product->languages->first()->pivot->canonical);
            $price = $product['price'];
            $priceAfterDiscount = $price - $discountPrice;
            $review = getReview($product);
            $html .= '<div class="uk-width-1-2 uk-with-small-1-2 uk-width-medium-1-3 uk-width-large-1-5 mb20">';
            // Tạo HTML cho sản phẩm
            $html .= '<div class="product-item product">';
            if ($discountPrice != 0) {
                $html .= '<div class="badge badge-bg' . rand(1, 3) . '">- ' . $discountType . '</div>';
            }
            $html .= '<a href="' . $canonical . '" class="image img-cover">';
            $html .= '<img src="' . $image . '" alt="' . $name . '">';
            $html .= '</a>';
            $html .= '<div class="info">';
            $html .= '<div class="category-title">';
            $html .= '<a href="' . $canonical . '" title="">' . $catName . '</a>';
            $html .= '</div>';
            $html .= '<h3 class="title"><a href="' . $canonical . '" title="">' . $name . '</a></h3>';
            $html .= '<div class="rating">';
            $html .= '<div class="uk-flex uk-flex-middle">';
            $html .= '<div class="star">';

            // Đánh giá sao
            for ($j = 1; $j <= 5; $j++) {
                $html .= '<i class="fa fa-star ' . ($j <= $product->avg_score ? 'review-star' : 'star-default') . '"></i>';
            }

            $html .= '</div><span class="rate-number">' . $review['reviews'] . '</span>';
            $html .= '</div></div>';
            $html .= '<div class="product-group"><div class="uk-flex uk-flex-middle uk-flex-space-between">';
            $html .= '<div class="price uk-flex uk-flex-bottom">';
            if ($discountPrice != 0) {
                $html .= '<div class="price-sale">' . number_format($priceAfterDiscount) . 'đ</div>';
            }
            $html .= '<div class="' . ($discountPrice != 0 ? 'price-old' : 'price-sale') . '">' . number_format($price) . 'đ</div>';
            $html .= '</div><div class="addcart">';
            $html .= renderQuickBuy($product, $canonical, $name);
            $html .= '</div></div></div>';
            $html .= '</div>';

            // Công cụ
            $html .= '<div class="tools">';
            $html .= '<a href="' . $canonical . '" title=""><img src="' . asset('frontend/resources/img/trend.svg') . '" alt=""></a>';
            $html .= '<a href="" title=""><img src="' . asset('frontend/resources/img/wishlist.svg') . '" alt=""></a>';
            $html .= '<a href="" title=""><img src="' . asset('frontend/resources/img/compare.svg') . '" alt=""></a>';
            $html .= '<a href="#popup" title=""><img src="' . asset('frontend/resources/img/view.svg') . '" alt=""></a>';
            $html .= '</div>';

            $html .= '</div>'; 
            $html .= '</div>';// đóng div .product-item
        }
        $html .= '</div>'; // đóng div
    }

    return $html; // Trả về HTML được tạo ra
}

  

    
}
