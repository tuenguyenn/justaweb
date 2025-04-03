
<div class="uk-container">
    <h3 class="uk-heading-divider">ĐÁNH GIÁ TỪ KHÁCH HÀNG</h3>
    @php
        $reviews = $product->reviews;
        $count = $product->reviews()->count();     
        $totalRate = $product->reviews()->avg('score');
        $starCounts = [
        5 => $product->reviews()->where('score', 5)->count(),
        4 => $product->reviews()->where('score', 4)->count(),
        3 => $product->reviews()->where('score', 3)->count(),
        2 => $product->reviews()->where('score', 2)->count(),
        1 => $product->reviews()->where('score', 1)->count(),
        ];

    // Tính phần trăm từng mức sao
    $starPercents = [];
    foreach ($starCounts as $star => $starCount) {
        $starPercents[$star] = $count > 0 ? round(($starCount / $count) * 100, 1) : 0;
    }
    @endphp
    <!-- Thống kê đánh giá -->
    <div class="uk-grid uk-grid-medium uk-flex-middle" uk-grid>
        <!-- Điểm trung bình -->
        <div class="uk-width-1-3 ">
            <div class="review-score-container uk-text-right">
                <span class="review-score uk-text-large">{{ number_format($totalRate, 1) }}/5</span>
                
                <div class="star-reviews uk-text-large">
                    @php
                        $fullStars = floor($totalRate);
                        
                        $halfStar = ($totalRate - $fullStars) ; 
                        
                        $emptyStars = 5 - ($fullStars + $halfStar); 
                    @endphp
            
                    {{-- In ra sao đầy --}}
                    @for ($i = 0; $i < $fullStars; $i++)
                        <i class="fa fa-star uk-text-warning"></i>
                    @endfor
            
                    {{-- In ra sao nửa nếu có --}}
                    @if ($halfStar)
                        <i class="fa fa-star-half-o uk-text-warning"></i>
                    @endif
                 
                   
                </div>
            
                <p class="uk-text-muted">{{ $count }} đánh giá</p>
            </div>
            
        </div>

        <!-- Phân bố số sao -->
        <div class="uk-width-1-3">
            <div class="rating-distribution">
                @foreach ($starCounts as $star => $starCount)
                    <div class="rating-bar">
                        <span>{{ $star }} ★</span>
                        <div class="bar">
                            <div class="filled" style="width: {{ $starPercents[$star] }}%;"></div>
                        </div>
                        <span>{{ $starPercents[$star] }}%</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Danh sách đánh giá -->
    <div class="uk-list uk-margin-top">
      @if (!is_null($reviews))
       
          @foreach ($reviews as $key => $val)
          @php
              $reviewerImg = $val->customers->image ?? '';
              $reviewerName = $val->customers->name;
              $score = $val->score;
              $reviewText = $val->description;
              $reviewDate = formatDate($val->created_at)


          @endphp
          <div class="review-item">
            <div class="uk-flex uk-flex-middle">
                <img src="{{asset($reviewerImg)}}" alt="Avatar" class="review-avatar">
                <div class="uk-margin-small-left">
                    <strong>{{$reviewerName}}</strong>
                    @for ($i = 0; $i < 5; $i++)
                    @if ($i < $score) 
                        <i class="fa fa-star uk-text-warning"></i> {{-- Sao đầy --}}
                    @else 
                        <i class="fa fa-star "></i> {{-- Sao rỗng --}}
                    @endif
                @endfor
                
                    <p class="uk-text-muted">{{$reviewDate}}</p>
                </div>
            </div>
            <p class="review-text ml40">{{$reviewText}}</p>
            
            <!-- Admin trả lời -->
            {{-- <div class="admin-reply">
                <div class="uk-flex uk-flex-middle">
                    <img src="https://via.placeholder.com/50" alt="Admin Avatar" class="review-avatar">
                    <div class="uk-margin-small-left">
                        <strong>Admin</strong>
                        <p class="uk-text-muted">Hôm nay</p>
                    </div>
                </div>
                <p class="admin-text">Cảm ơn bạn đã tin tưởng sản phẩm của shop. Chúc bạn mua sắm vui vẻ!</p>
            </div> --}}
        </div>
          @endforeach
      @endif

        
    </div>

    <!-- Form đánh giá -->
   
</div>
