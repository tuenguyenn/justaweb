@if (count($slides['main-slide']->slideItems))
<div class="panel-slide page-setup" data-setting ="{{json_encode($slides['main-slide']->setting)}}">
    <div class="uk-container uk-container-center">
        <div class="swiper-container">
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-wrapper">
                @foreach ($slides['main-slide']->slideItems as $key => $val)
                <div class="swiper-slide">
                    <div class="slide-item">
                        <div class="slide-overlay">
                            <div class="slide-title">{!! $val['name']!!}</div>
                            <div class="slide-description">{!! $val['description']!!}</div>
                        </div>
                      
                        <span class="image "><img src="{{$val['image']}}" alt="" ></span> 
                    </div>
                    
                </div>
                @endforeach
                
            </div>
            <div class="swiper-pagination"></div>
        </div>
        
    </div>
</div>
@endif
