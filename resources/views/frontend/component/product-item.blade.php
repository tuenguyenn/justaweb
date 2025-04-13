@php
   
    $catName = $cat[0]['name'] ?? '';
    if (isset($cat)) {
    foreach ($cat as $child) {
        if ($child['ids'] == $product->product_catalogue_id) {
            $catName = $child['name'];
            break;
        }
    }
    }
    else{
        $catName= $product->product_catalogues->first()->languages->first()->pivot->name;
    }
  
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


    
    $image = image($product['image'] ?? asset('frontend/resources/img/product-' . rand(1,10) . '.jpg'));
    $name = $product->languages->first()->pivot->name;
   
    $canonical = write_url($product->languages->first()->pivot->canonical);
    $price = $product['price'];
    $priceAfterDiscount =  $price - $discountPrice;
    $review = getReview($product);
@endphp


<div class="product-item product">
    @if ($discountPrice != 0)
    <div class="badge badge-bg{{ rand(1,3) }}">
        - {{ $discountType}}
    </div>
    @endif
    <a href="{{$canonical}}" class="image img-cover">
        <img src="{{ $image }}" alt="{{ $name}}">
    </a>
    <div class="info">
        <div class="category-title">
            <a href="{{$canonical}}" title="">{{$catName}}</a>
        </div>
        <h3 class="title">
            <a href="{{$canonical}}" title="" class="">{{ $name }}</a>
        </h3>
        <div class="rating">
            <div class="uk-flex uk-flex-middle">
                <div class="star">
                    @for ($j = 1; $j <= 5; $j++)
                        
                        <i class="fa fa-star {{ $j <= $review['stars'] ? 'review-star' : 'star-default' }}"></i>
                    @endfor
                </div>
                
                <span class="rate-number">{{$review['reviews']}}</span>
            </div>
        </div>
        <div class="product-group">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <div class="price uk-flex uk-flex-bottom">
                    @if ($discountPrice !=0)
                        <div class="price-sale ">{{ number_format($priceAfterDiscount) }}đ</div>

                    @endif
                    <div class=" {{( $discountPrice !=0) ? 'price-old': 'price-sale'}} ">{{ number_format($price) }}đ</div>
                </div>
                <div class="addcart">
                    <a href="{{$canonical}}"   class="btn-addCart">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g>
                        <path d="M24.4941 3.36652H4.73614L4.69414 3.01552C4.60819 2.28593 4.25753 1.61325 3.70863 1.12499C3.15974 0.636739 2.45077 0.366858 1.71614 0.366516L0.494141 0.366516V2.36652H1.71614C1.96107 2.36655 2.19748 2.45647 2.38051 2.61923C2.56355 2.78199 2.68048 3.00626 2.70914 3.24952L4.29414 16.7175C4.38009 17.4471 4.73076 18.1198 5.27965 18.608C5.82855 19.0963 6.53751 19.3662 7.27214 19.3665H20.4941V17.3665H7.27214C7.02705 17.3665 6.79052 17.2764 6.60747 17.1134C6.42441 16.9505 6.30757 16.7259 6.27914 16.4825L6.14814 15.3665H22.3301L24.4941 3.36652ZM20.6581 13.3665H5.91314L4.97214 5.36652H22.1011L20.6581 13.3665Z" fill="#253D4E"></path>
                        <path d="M7.49414 24.3665C8.59871 24.3665 9.49414 23.4711 9.49414 22.3665C9.49414 21.2619 8.59871 20.3665 7.49414 20.3665C6.38957 20.3665 5.49414 21.2619 5.49414 22.3665C5.49414 23.4711 6.38957 24.3665 7.49414 24.3665Z" fill="#253D4E"></path>
                        <path d="M17.4941 24.3665C18.5987 24.3665 19.4941 23.4711 19.4941 22.3665C19.4941 21.2619 18.5987 20.3665 17.4941 20.3665C16.3896 20.3665 15.4941 21.2619 15.4941 22.3665C15.4941 23.4711 16.3896 24.3665 17.4941 24.3665Z" fill="#253D4E"></path>
                        </g>
                        <defs>
                        <clipPath>
                        <rect width="24" height="24" fill="white" transform="translate(0.494141 0.366516)"></rect>
                        </clipPath>
                        </defs>
                        </svg></a>
                    {{-- {!! renderQuickBuy($product,$canonical,$name)!!} --}}
                </div>
            </div>
        </div>
        
    </div>
    <div class="tools">
        <a href="{{$canonical}}" title=""><img src="{{ asset('frontend/resources/img/trend.svg') }}" alt=""></a>
        <a href="" title=""><img src="{{ asset('frontend/resources/img/wishlist.svg') }}" alt=""></a>
        <a href="" title=""><img src="{{ asset('frontend/resources/img/compare.svg') }}" alt=""></a>
        <a href="#popup"  title=""><img src="{{ asset('frontend/resources/img/view.svg') }}" alt=""></a>

    </div>
</div>


