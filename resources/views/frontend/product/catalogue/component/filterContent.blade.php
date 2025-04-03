<div class="filter-content">
    <div class="filter-overlay">
        <div class="filter-close">
            <i class="fi fi-rs-cross"></i>
        </div>
    </div>
   
   
  
    <div class="filter-content-container mt10">
        <input type="hidden" class="product_catalogue_id" value="{{$productCatalogue->id}}">
        <div class="filter-review uk-text-left">
            <div class="filter-heading">Đánh giá</div>
            <div class="filter-body">
                <div class="filter-choose">
                    <input type="radio" id="rate-desc" name="rate" value="desc" class="input-checkbox filtering">
                    <label for="rate-desc" class="totalProduct ml5 mb5">Từ cao đến thấp</label>
                </div>
                <div class="filter-choose">
                    <input type="radio" id="rate-asc" name="rate" value="asc" class="input-checkbox filtering">
                    <label for="rate-asc" class="totalProduct ml5 mb5">Từ thấp đến cao</label>
                </div>
            </div>
        </div>
      
        @if (!is_null($filters))
            @foreach ($filters as $key =>$val)
            @php

                $attrCatalogue = $val->languages->first()->pivot->name;
                
            @endphp
            <div class="filter-item mt10">
                <div class="filter-heading">{{$attrCatalogue}}</div>
                <div class="filter-body">
                    @if (!is_null($val->attrs))
                    @foreach ($val->attrs as $item)

                    @php

                        $name = $item->languages->first()->pivot->name;



                        $id = $item->id;

                    @endphp

                    <div class="filter-choose">

                        <input type="checkbox" id="attribute-{{$id}}" class="input-checkbox filtering filterAttribute" value="{{$id}}" data-group="{{$val->id}}">

                        <label for="attribute-{{$id}}">{{$name}}</label>

                    </div>

                    @endforeach
                    @endif
                   
                </div>
            </div>
            @endforeach
        @endif
        <div class="filter-item filter-price slider-box">
            <div class="filter-heading" for="priceRange">Lọc Theo Giá:</div>
            <div class="filter-price-content">
                <input type="text" id="priceRange" readonly="" class="uk-hidden">
                <div id="price-range" class="slider ui-slider ui-slider-horizontal ui-widget"></div>
            </div>
            <div class="filter-input-value mt5">
                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    <input type="text" class="min-value input-value" value="0đ">
                    <input type="text" class="max-value input-value" value="100.000.000">
                </div>
            </div>
        </div>
        
        <div class="filter-item filter-category">
            <div class="filter-heading">Tình trạng sản phẩm</div>
            <div class="filter-body">
                <div class="filter-choose">
                    <input id="input-availble" type="checkbox" name="stock[]" value="1" class="">
                    <label for="input-availble">Còn hàng</label>
                </div>
                <div class="filter-choose">
                    <input id="input-outstock" type="checkbox" name="stock[]" value="0" class="">
                    <label for="input-outstock">Hết Hàng</label>
                </div>
            </div>
        </div>
      
        
        
    </div>
</div>
