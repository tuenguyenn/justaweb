@extends('frontend.homepage.layout')
@section('content')

        <div id="homepage" class="homepage">
            @include('frontend.component.slide')
            @include('frontend.component.banner')
            <div class="panel-category page-setup">
                <div class="uk-container uk-container-center">
                    @if (!is_null($widgets['cate-2']))
                    <div class="panel-head">
                        <div class="uk-flex uk-flex-middle">
                            <h2 class="heading-1"><span>Danh mục sản phẩm</span></h2>
                            @include('frontend.component.category')

                        </div>
                    </div>
                    @endif

                    @if (!is_null($widgets['category']))
                    <div class="panel-body">
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                @foreach ($widgets['category']->objects as $val)
                                @php

                                    $name = $val->languages->first()->pivot->name;
                                    $canonical = write_url($val->languages->first()->pivot->canonical);
                                    $image =  $val->image;
                                @endphp
                                <div class="swiper-slide"> 
                                    <div class="category-item bg-<?php echo rand(1,7) ?>">
                                         <a href="{{$canonical}}" class="image img-scaledown img-zoomin"><img src="{{$image}}" alt=""></a>
                                         <div class="title"><a href="{{$canonical}}" title="">{{$name}}</a></div>
                                         <div class="total-product">{{$val->products_count }}  sản phẩm</div>
                                    </div>
                                 </div>
                                @endforeach
                              
                              
                            </div>
                        </div>
                    </div>
                    @endif
                    
                </div>
            </div>
            <div class="panel-deal page-setup">
                <div class="uk-container uk-container-center">
                    <div class="panel-head">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <h2 class="heading-1"><span>FLASH SALE</span></h2>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="uk-grid uk-grid-medium">
                            <?php for($i = 0; $i<=3; $i++){  ?>
                            <div class="uk-width-large-1-4">
                                @include('frontend.component.product-item-2')

                            </div>
                            <?php }  ?>
                        </div>
                    </div>
                </div>
            </div>
          
        
            @if (isset($widgets['cate-home']))
            @foreach ($widgets['cate-home']->objects as $category)
           
            @php
                $catName = $category->languages->first()->pivot->name;
                $catCanonical = write_url($category->languages->first()->pivot->canonical);
                $printedCanonicals = []; 
               
            @endphp
            <div class="panel-popular">
                <div class="uk-container uk-container-center">
                    <div class="panel-head">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <h2 class="heading-1"><a href="{{ $catCanonical }}">{{ $catName }}</a></h2>
                            <div class="category-children">
                                <ul class="uk-list uk-clearfix uk-flex uk-flex-middle">
                                    <li class=""><a href="{{ $catCanonical }}" title="{{ $catName }}">Tất cả</a></li>
                                        @foreach ($category->childrens as $children)
                                            
                                            @if (!is_null($children))
                                               
                                                @php
                                                    $childName = $children['name'];
                                                    $childCanonical = write_url($children['canonical'], true, true);
                                                @endphp
                                                @if (!in_array($childCanonical, $printedCanonicals))
                                                    <li class=""><a href="{{ $childCanonical }}" title="{{ $childName }}">{{ $childName }}</a></li>
                                                    @php
                                                        $printedCanonicals[] = $childCanonical;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="uk-grid uk-grid-medium">
                            @php
                                $seenIds = [];
                            @endphp
                           
                            @foreach ($category->products as $product)
                                @if (!in_array($product->id, $seenIds))
                                    @php
                                        $seenIds[] = $product->id; 
                                    @endphp
                                    <div class="uk-width-large-1-5 mb20">
                                        
                                        @if (isset($product))
                                        @include('frontend.component.product-item', ['product' => $product, 'cat' => $category->childrens])
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                    </div>
                </div>

                </div>
            </div>
            @endforeach

        @endif
        
        @if (isset($widgets['best-seller']))
            @php
                $widName = $widgets['best-seller']->name;
                $widImage = isset($widgets['best-seller']->album[0]) 
                            ? $widgets['best-seller']->album[0] 
                            : 'userfiles/image/languages/bestseller.png" alt=""'; 
                $widDesc = $widgets['best-seller']->description[$config['language']] ;
            @endphp
            <div class="panel-bestseller">
                <div class="uk-container uk-container-center">
                    <div class="panel-head">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <h2 class="heading-1"><span>{{$widName}}</span></h2>
                            @include('frontend.component.category')
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="uk-grid uk-grid-medium">
                            <div class="uk-width-large-1-4">
                                <div class="best-seller-banner">
                                    <a href="" class="image img-cover"><img src="{{$widImage}}" alt="{{$widName}}"></a>
                                    <div class="banner-title">{!!$widDesc!!}</div>
                                </div>
                            </div>
                            <div class="uk-width-large-3-4">
                              
                                <div class="product-wrapper">
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                    <div class="swiper-container">
                                        <div class="swiper-wrapper">
                                            @foreach ($widgets['best-seller']->objects as $product)
                                            <div class="swiper-slide">
                                              
                                                @include('frontend.component.product-item',['product'=>$product])

                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
           
            <div class="uk-container uk-container-center mt50">
                <div class="panel-group">
                    <div class="panel-body">
                        <div class="group-title">Stay home & get your daily <br> needs from our shop</div>
                        <div class="group-description">Start Your Daily Shopping with Nest Mart</div>
                        <span class="image"><img src="{{asset('userfiles/footer.png')}}" style="height:400px" alt=""></span>
                    </div>
                </div>
            </div>
            <div class="panel-commit">
                <div class="uk-container uk-container-center">
                    <div class="uk-grid uk-grid-medium">
                        <div class="uk-width-large-1-5">
                            <div class="commit-item">
                                <div class="uk-flex uk-flex-middle">
                                    <span class="image"><img src="frontend/resources/img/commit-1.png" alt=""></span>
                                    <div class="info">
                                        <div class="title">Giá ưu đãi</div>
                                        <div class="description">Khi mua từ 500.000đ</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-width-large-1-5">
                            <div class="commit-item">
                                <div class="uk-flex uk-flex-middle">
                                    <span class="image"><img src="frontend/resources/img/commit-2.png" alt=""></span>
                                    <div class="info">
                                        <div class="title">Miễn phí vận chuyển</div>
                                        <div class="description">Trong bán kính 2km</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-width-large-1-5">
                            <div class="commit-item">
                                <div class="uk-flex uk-flex-middle">
                                    <span class="image"><img src="frontend/resources/img/commit-3.png" alt=""></span>
                                    <div class="info">
                                        <div class="title">Ưu đãi</div>
                                        <div class="description">Khi đăng ký tài khoản</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-width-large-1-5">
                            <div class="commit-item">
                                <div class="uk-flex uk-flex-middle">
                                    <span class="image"><img src="frontend/resources/img/commit-4.png" alt=""></span>
                                    <div class="info">
                                        <div class="title">Đa dạng </div>
                                        <div class="description">Sản phẩm đa dạng</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-width-large-1-5">
                            <div class="commit-item">
                                <div class="uk-flex uk-flex-middle">
                                    <span class="image"><img src="frontend/resources/img/commit-5.png" alt=""></span>
                                    <div class="info">
                                        <div class="title">Đổi trả </div>
                                        <div class="description">Đổi trả trong ngày</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <div id="fb-root"></div>

      
    


      
@endsection