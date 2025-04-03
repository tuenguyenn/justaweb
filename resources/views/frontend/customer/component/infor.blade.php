<div class="uk-width-large-4-6">
                      
    <div class="panel-cart cart-left">
        <div class="panel-body">
            @include('frontend.cart.component.content', ['customer' => $customer ?? null])
        </div>
       
    </div>
       
</div>
<div class="uk-width-1-6 mt50">
    <div class="uk-margin">
        <div class="avatar-container image-target uk-flex uk-flex-middle uk-flex-column">
            <img src="{{ old('image', $customer->image ?? asset('backend/img/no-image-icon.jpg')) }}" 
                 alt="Avatar" class="avatar-img">
            <span href="#" class="uk-button uk-button-text image-target mt10">Click để thêm ảnh đại diện</span>
        </div>
        
        <input type="hidden" name="image" value="{{ old('image', $customer->image ?? '') }}">
    </div>
    <button type="submit" name="send" class="button-13 ">Lưu</button>
</div>