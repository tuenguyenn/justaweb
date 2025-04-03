<div class="attribute">
                                 
    @if (!is_null($attrCatalogue) && isset($attrCatalogue) && count($attrCatalogue) )
    @foreach ($attrCatalogue as $attr)
    <div class="attribute-item attribute-color">
        <div class="label">{{$attr->name}} <span></span></div>
        @if (!is_null($attr->attributes))
            <div class="attribute-value">
                @foreach ($attr->attributes as $key => $item)
                <a class="choose-attribute {{($key == 0) ? 'active' : ' '}}" data-attributeid = {{$item->id}} title="{{$item->name}}">{{$item->name}}</a>
                @endforeach
               
            </div>
        @endif
     
    </div>
    @endforeach
   
    @endif
   

</div><!-- .attribute -->

<input type="hidden" name="product_id" value="{{$product->id}}">