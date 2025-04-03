

 
    <div id="review-modal" uk-modal data-id="">
        <div class="uk-modal-dialog uk-modal-body">
            <h2 class="uk-modal-title">Đánh giá sản phẩm</h2>
           
            <div class="star-rating-form">
                @for ($i = 5; $i >= 1; $i--) 
                    <input type="radio" id="star{{$i}}" name="rating" class="rate" value="{{$i}}">
                    <label for="star{{$i}}">★</label>
                @endfor
            </div>
            
            
            
            
                <div class="uk-margin">
                    <textarea class="uk-textarea uk-width-2xlarge description" rows="7" name=" "placeholder="Viết đánh giá tại đây..." 
                    maxlength="500"></textarea>
                        <p class="uk-text-small uk-text-muted uk-margin-small-top">
                            Tối đa 500 từ
                        </p>
          
                </div>
                <div class="uk-margin-top uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Hủy</button>
                    <button class="uk-button uk-button-danger submit-review" type="submit">Gửi</button>
                </div>
               
           
        </div>
    </div>
    
    <input type="hidden" class="reviewable_type" value="App\Models\Product">
    <input type="hidden" class="customer_id" value="{{$customer->id}}">
    <input type="hidden" class="parent_id" value="{{$review->id ?? 0}}">

   

    
  
