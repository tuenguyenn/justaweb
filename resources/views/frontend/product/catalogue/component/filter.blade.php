<div class="filter">
    <div class="uk-flex uk-flex-middel uk-flex-space-between">
      <div class="filter-widget">
        <div class="uk-flex uk-flex-middle">
            <a href="" class="view-grid active"><i class="fi-rs-grid"></i></a>
            <a href="" class="view-grid view-list"><i class="fi-rs-list"></i></a>
    
         
    
            <!-- Input tìm kiếm -->
            <div class="search-box">
                <input type="text" name="keyword" id="keyword" placeholder="Nhập từ khóa..." />
                <button type="submit"><i class="fi-rs-search"></i></button>
            </div>
    
            <div class="pergpage uk-flex uk-flex-middle">
                <div class="filter-text">Hiển thị</div>
                <select name="perpage" class="nice-select filtering" id="perpage">
                    @for ($i = 20; $i < 100; $i+=20)
                        <option value="{{$i}}">{{$i}} bản ghi</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>
    
     <div class="uk-flex uk-flex-middle uk-flex-space-between"> 
      <div class="sorting">
        <select name="sort" class="nice-select filtering" id="">
          <option value="">Lọc kết quả theo</option>
          <option value="price:desc">Giá: Từ cao đến thấp</option>
          <option value="price:asc">Giá: Từ thấp đến cao</option>
          <option value="name:desc">Tên sản phẩm A-Z</option>
          <option value="name:asc">Tên sản phẩm Z-A</option>
          

        </select>
      </div>
      <div class="filter-button ml10 mr20">
        <div class="btn-filter uk-flex uk-flex-middle">
            <i class="fi-rs-filter mr5"></i>
            <span>Bộ lọc</span>
        </div>
    </div>
     </div>
     
    </div>
  </div>