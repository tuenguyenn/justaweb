<div class="panel-foot mb10">
    <h2 class="cart-heading mt20"><span>Hình thức thanh toán</span></h2>
    <div class="cart-method">
     @foreach ((__('payment.method')) as $key => $item)
     <label for="{{$item['name']}}" class="uk-flex uk-flex-middle method-item">
         <input type="radio" name="method" id="{{$item['name']}}" value="{{$item['name']}}" 
             {{ old('method', $key == 0 ? $item['name'] : '') == $item['name'] ? 'checked' : '' }}>
         <span class="image"><img src="{{asset($item['image'])}}" alt=""></span>
         <span class="title">{{$item['title']}}</span>
     </label>
 @endforeach
 
     
    </div>
    <div class="cart-return mb10">
        {!!(__('payment.return'))!!}
    </div>
    <button type="submit" class="cart-checkout" value="create" name="create">THANH TOÁN</button>
 </div>