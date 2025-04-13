<div class="header-lower">
    <div class="uk-container uk-container-center">
        <div class="uk-flex uk-flex-middle">
            <div class="categories">
                <span>Danh mục sản phẩm</span>
                <div class="categories-dropdown">
                    <div class="uk-grid uk-grid-small">
                        
                        {{-- @if (!is_null($widgets['category']))
                        @foreach ($widgets['category']->objects as $key => $val)
                        @php
                        $name = $val->languages->first()->pivot->name;
                        $canonical = write_url($val->languages->first()->pivot->canonical);
                        $image =  $val->image;
                         @endphp
                        @if ($key <8)
                        <div class="uk-width-large-1-2 mb10">
                          
                            <div class="categories-item">
                                <a href="" title="" class="uk-flex uk-flex-middle">
                                    <img src="{{$image}}" alt="">
                                    <span class="title">{{$name}}</span>
                                    <span class="total">{{$val->products_count}}</span>
                                </a>
                            </div>
                         
                        </div>
                        @endif
                        @endforeach
                           
                        @endif --}}
                       
                    </div>
                </div>
            </div>
            @include('frontend.component.navigation')

        </div>
    </div>
</div>