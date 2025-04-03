<div class="col-lg-12 mt-3 bg-white p-3">
    <h5 class="mainColor">THÔNG TIN CHI TIẾT KHUYẾN MÃI</h5>
    <hr>
  
    <!-- Lựa chọn hình thức khuyến mãi -->
    <div class="col-12 ps-4">
      <label for="promotion-type">Chọn hình thức khuyến mãi</label>
      <select id="promotion-type" name="method" class="promotionMethod form-select">
        @foreach (__('module.promotion') as $key => $item)
          <option value="{{ $key }}">{{ $item }}</option>
        @endforeach
      </select>
    </div>
  
    <div class="promotion-container bg-white">
      
    </div>
  </div>
  
  
  <script>
    function formatInputValue(input) {
      const value = input.value.replace(/\D/g, ""); 
      input.value = new Intl.NumberFormat("vi-VN").format(value); 
    }
  
   
  </script>