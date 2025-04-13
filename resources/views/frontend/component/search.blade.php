  <div class="header-form">
    <form action="{{route('search')}}" method="get">
      <div class="uk-form form search-form">
        <div class="form-row">
            <input type="text" name="keyword" class="input-text" placeholder="Tìm kiếm sản phẩm...">
        </div>
        <button type="submit"  id="searchButton">Tìm kiếm</button>
    </div>
  </form>
    <div class="search-dropdown">
       
        <div class="search-section recent-searches">
          <div class="section-header">
            <h4>Lịch sử tìm kiếm</h4>
            <button class="clear-history">Xóa lịch sử</button>
          </div>
          <ul >
           
         
          </ul>
        </div>
        <div class="realtime-results">
            <div class="results-header">
              <h4>Gợi ý cho "<span class="current-query"></span>"</h4>
              <div class="results-count">0 kết quả</div>
            </div>
            
            <div class="quick-products-grid">
              
            </div>
            
            <div class="view-all">
              <button>Xem tất cả kết quả cho "<span class="current-query"></span>"</button>
            </div>
          </div>
        
        <!-- Xu hướng -->
        @if (!is_null($widgets['trending']))
        <div class="search-section trending-searches">
            <div class="section-header">
              <h4>{{$widgets['trending']->name}}</h4>
              <span class="trending-badge">HOT</span>
            </div>
            <ul>
             @foreach ($widgets['trending']->objects as $key=>$val)
                @php
                    $name =$val->languages->first()->pivot->name;
                    $canonical = write_url($val->languages->first()->pivot->canonical)
                @endphp
             <li>
                <span class="trend-rank">{{$key+1}}</span>
                <a href="{{$canonical}}"><span class="search-term">{{$name}}</span></a>
                <span class="trend-icon">↑</span>
              </li>
             @endforeach
             
            </ul>
          </div>
        @endif
        
        
        
        @if (!is_null($widgets['best-seller']))
        <div class="search-section popular-products">
          <div class="section-header">
            <h4>{{$widgets['best-seller']->name}}</h4>
           
          </div>
          <div class="product-grid">
            @foreach ($widgets['best-seller']->objects as $key => $val)
              @php
                $image = image($val['image'] ?? asset('frontend/resources/img/product-' . rand(1,10) . '.jpg'));
                $price = $val['price'] ?? 0;
                $languageData = $val->languages->first()->pivot;
                $name = $languageData->name ?? '';
                $canonical = write_url($languageData->canonical ?? '');
              @endphp
              
              <div class="product-card-horizontal">
                <a href="{{ $canonical }}" class="product-image-link">
                  <div class="product-image-container">
                    <img src="{{ $image }}" alt="{{ $name }}" class="product-image" loading="lazy">
                    @if($key < 3) <!-- Hiển thị badge cho 3 sản phẩm đầu -->
                      <div class="product-badge">Top {{ $key + 1 }}</div>
                    @endif
                  </div>
                </a>
                <div class="product-info">
                  <a href="{{ $canonical }}" class="product-name" title="{{ $name }}">
                    {{ Str::limit($name, 40) }}
                  </a>
                  <div class="product-price">{{ number_format($price) }}₫</div>
                
                </div>
              </div>
            @endforeach
          </div>
        </div>
        @endif
        
        <!-- Khuyến mãi -->
        <div class="promo-banner">
          <div class="promo-icon">🎉</div>
          <div class="promo-text">Lần đặt hàng đầu giảm đến 100K</div>
        </div>
      </div>
</div>
