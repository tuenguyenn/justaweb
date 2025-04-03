@if (isset($carts) && !is_null($carts))
@foreach ($carts as $key =>$val)

<div class="cart-list mb10">
    <div class="cart-item">
        <div class="uk-grid uk-grid-medium">
            <div class="uk-with-small-1-1 uk-with-medium-1-5">
                <div class="cart-item-image">
                    <span class="image product-cart-image"><a href="{{$val->options['canonical']}}"><img src="{{$val->options['image']}}" alt=""></a></span>
                    <span class="cart-item-number">{{$val->qty}}</span>
                </div>
            </div>
            <div class="uk-with-small-1-1 uk-with-medium-4-5">
                <div class="cart-item-info">
                    <h3 class="title"><span>{{$val->name}}</span></h3>
                    <div class="cart-item-action uk-flex uk-flex-middle uk-flex-space-between">
                        <div class="uk-flex uk-flex-column">
                            <div class="cart-item-original-price">
                                @if ($val->options['price_original'] == $val->price)
                                    <span class="cart-price-original">{{ number_format($val->price) }}đ</span>
                                @else
                                    <span class="cart-price-old ml10">{{ number_format($val->options['price_original']) }}đ</span>

                                    <span class="cart-price-original">{{ number_format($val->price ) }}đ</span>
                                @endif
                            </div>
                            <div class="cart-item-qty">
                                <button type="button" class="btn-qty minus">-</button>
                                <input type="number" value="{{$val->qty}}" class="input-qty" >
                                <input type="hidden" value="{{$val->rowId}}" class="rowId">
                                <button type="button" class="btn-qty plus">+</button>
                            </div>
                        </div>
                        

                       
                        <div class="cart-item-price mt30">
                            <span class="cart-price-sale">{{ number_format($val->price * $val->qty) }}đ</span>

                            </div>
                        </div>
                        <div class="cart-item-remove" data-row-id="{{$val->rowId}}">
                            <span class="delete-item">
                                <img src="{{ asset('frontend/resources/img/icons8-remove-50.png') }}" style="width:30px">
                            </span>
                        </div>
                        
                      
                    </div>
                </div>

            </div>

        </div>
    </div>
    @endforeach
@endif
