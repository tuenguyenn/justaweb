
@php
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
    $price = $product['price'];
    $priceAfterDiscount =  $price - $discountPrice;
    $description = $product->description;
    $review = getReview();
    $image = $product->image;
    $canonical = write_url($product->canonical);
    $catName = $product->product_catalogues->first()->pivot->name;
    $proName = $product->name;
    $album =  (count(json_decode($product->album))) ? json_decode($product->album) : [$image];
    $attrCatalogue = $product->attributeCatalogue === '' ? null : $product->attributeCatalogue;

  
  
@endphp
 <div class="panel-body">
            <div class="uk-grid uk-grid-medium">
                <div class="uk-width-large-1-3">
                    <div class="popup-gallery">
                        <div class="swiper-container main-slider">
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                                <div class="swiper-wrapper big-pic">
                                    @foreach ($album as $key => $val)
                                    <div class="swiper-slide">  
                                        <a href="{{$album[$key]}}" class="image img-cover ">
                                            <img id="{{($album[0]) ? 'product-image' : ' ' }}" src="{{$album[$key]}}" alt="{{$album[$key]}}">
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    
                        @if (count($album) > 1)
                        <div class="swiper-container swiper-container-thumbs">
                            <div class="swiper-wrapper pic-list">
                                <?php foreach($album as $key => $val){ ?>
                                <div class="swiper-slide">
                                    <span class="image img-cover">
                                        <img  src="{{$album[$key]}}" alt="{{$album[$key]}}">
                                    </span>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    
                </div>
                <div class="uk-width-large-2-3">
                    <div class="popup-product">
                        <h2 class="title main-product-name"><span>{{$proName}}</span>
                        </h2>
                        <div class="rating">
                            <div class="uk-flex uk-flex-middle">
                                <div class="star">
                                    @for ($j = 1; $j <= 5; $j++)
                                        
                                        <i class="fa fa-star {{ $j <= $review['stars'] ? 'review-star' : 'star-default' }}"></i>
                                    @endfor
                                </div>
                                
                                <span class="rate-number">({{$review['reviews']}})</span>
                            </div>
                        </div>
                        <div class="price">
                            <div class="uk-flex uk-flex-bottom">
                                <div class="price-sale">{{ number_format($priceAfterDiscount) }}đ</div>
                            
                                @if ($discountPrice > 0)
                                    <div class="price-old">{{ number_format($price) }}đ</div>
                                    <span class="discount-badge">-{{$discountType}}</span>
                                    @endif
                            </div>
                            
                        </div>
                        <div class="description">
                            {!!$description!!}
                        </div>
                        @include('frontend.product.product.component.attribute')
                        <div class="quantity">
                            <div class="text">Quantity</div>
                            <div class="uk-flex uk-flex-middle">
                                <div class="quantitybox uk-flex uk-flex-middle">
                                    <div class="minus quantity-button"><img src="frontend/resources/img/minus.svg" alt=""></div>
                                    <input type="text" name="" value="1" class="quantity-text">
                                    <div class="plus quantity-button"><img src="frontend/resources/img/plus.svg" alt=""></div>
                                </div>
                                <div class="button-group">
                                    <button class="btn btn-outline addToCart" data-id="{{$product->id}}">
                                        <svg viewBox="0 0 24 24 " fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M14 2C14 1.44772 13.5523 1 13 1C12.4477 1 12 1.44772 12 2V8.58579L9.70711 6.29289C9.31658 5.90237 8.68342 5.90237 8.29289 6.29289C7.90237 6.68342 7.90237 7.31658 8.29289 7.70711L12.2929 11.7071C12.6834 12.0976 13.3166 12.0976 13.7071 11.7071L17.7071 7.70711C18.0976 7.31658 18.0976 6.68342 17.7071 6.29289C17.3166 5.90237 16.6834 5.90237 16.2929 6.29289L14 8.58579V2ZM1 3C1 2.44772 1.44772 2 2 2H2.47241C3.82526 2 5.01074 2.90547 5.3667 4.21065L5.78295 5.73688L7.7638 13H18.236L20.2152 5.73709C20.3604 5.20423 20.9101 4.88998 21.4429 5.03518C21.9758 5.18038 22.29 5.73006 22.1448 6.26291L20.1657 13.5258C19.9285 14.3962 19.1381 15 18.236 15H8V16C8 16.5523 8.44772 17 9 17H16.5H18C18.5523 17 19 17.4477 19 18C19 18.212 18.934 18.4086 18.8215 18.5704C18.9366 18.8578 19 19.1715 19 19.5C19 20.8807 17.8807 22 16.5 22C15.1193 22 14 20.8807 14 19.5C14 19.3288 14.0172 19.1616 14.05 19H10.95C10.9828 19.1616 11 19.3288 11 19.5C11 20.8807 9.88071 22 8.5 22C7.11929 22 6 20.8807 6 19.5C6 18.863 6.23824 18.2816 6.63048 17.8402C6.23533 17.3321 6 16.6935 6 16V14.1339L3.85342 6.26312L3.43717 4.73688C3.31852 4.30182 2.92336 4 2.47241 4H2C1.44772 4 1 3.55228 1 3ZM16 19.5C16 19.2239 16.2239 19 16.5 19C16.7761 19 17 19.2239 17 19.5C17 19.7761 16.7761 20 16.5 20C16.2239 20 16 19.7761 16 19.5ZM8 19.5C8 19.2239 8.22386 19 8.5 19C8.77614 19 9 19.2239 9 19.5C9 19.7761 8.77614 20 8.5 20C8.22386 20 8 19.7761 8 19.5Z" fill="#000000"></path> </g></svg> Thêm Vào Giỏ Hàng
                                    </button>
                                    <button class="btn btn-filled">
                                        Mua Với Voucher 
                                    </button>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
<input type="hidden" name="origin-product-name" value="{{$proName}}">
<input type="hidden" name="canonical-product" value="{{$canonical}}">


