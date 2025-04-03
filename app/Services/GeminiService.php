<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Services\Interfaces\ProductServiceInterface as ProductService;
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;

class GeminiService
{
    protected $client;
    protected $apiKey;
    protected $apiUrl;
    protected $productRepository;
    protected $productService;

    public function __construct(
        ProductRepository $productRepository,
        ProductService $productService,
    )
    {
        $this->client = new Client();
        $this->apiKey = env('GEMINI_API_KEY');
        $this->apiUrl = env('GEMINI_API_URL');
        $this->productRepository = $productRepository;
        $this->productService = $productService;
    }

    public function sendMessage($message)
{
    try {
      
        $products = $this->productRepository->getAllProducts()->toArray();

        // $keywords = explode(' ', strtolower(string: $message));
        $matchedProduct = null;
        foreach ($products as $product) {
            // foreach ($keywords as $keyword) {
                if (strpos($product['name'], $message) !== false) {
                    $matchedProduct = $product;
                    break;
                   
                }elseif(strpos(' anh', $message)){
                   return 'Ngọc Ánh là người yêu minh tuệ';
                // }   
                
            }

            if ($matchedProduct) {
                break;
            }
        }

        if ($matchedProduct) {
            return $this->getProductResponse($matchedProduct);
        }

        $response = $this->client->post($this->apiUrl . '?key=' . $this->apiKey, [
            'json' => [
                'contents' => [['parts' => [['text' => $message]]]]
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Không có phản hồi từ AI.';
    } catch (\Exception $e) {
        Log::error("Lỗi API Gemini: " . $e->getMessage());
        return 'Lỗi API: ' . $e->getMessage();
    }
}

private function getProductResponse($product)
{   
   
  
$promotion = $this->productService->findPromotionProduct([$product['id']]);

if(count($promotion)){
    $priceDiscount = $promotion['product_price'] - $promotion['discountPrice'];
}
$canonical = write_url($product['canonical']);




$productInfo = '
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<div class="product-card">
    <div class="product-header">Thông tin sản phẩm</div>
    <div class="product-image-container">
        <img src="' . asset($product['image']) . '" alt="' . $product['name'] . '" class="product-image">
        
    </div>
    <div class="product-details">
        <div class="product-name">
            <i class="fas fa-tag"></i> ' . $product['name'] . '
        </div>
        <div class="product-price">
            <i class="fas fa-money-bill-wave"></i>
            <span>' . number_format($product['price'], 0, ',', '.') . ' VND</span>
           
        </div>';

if (count($promotion) && $promotion['discountPrice'] > 0) {
    $productInfo .= '
        <div class="discount-price">
            <i class="fas fa-percentage"></i> Giá giảm: ' . number_format($priceDiscount, 0, ',', '.') . ' VND
        </div>';
}

$productInfo .= '
    </div>
    <div class="product-action">
        <a href="' . $canonical . '" class="view-product-btn">
            <i class="fas fa-eye"></i> Xem sản phẩm
        </a>
    </div>
</div>';

// Đừng quên bổ sung CSS vào trang của bạn (đặt ở phần header hoặc file CSS riêng)

    return $productInfo;
}

  

}
