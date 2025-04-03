    @php
        $name =   old('name', $customer->name ?? '') ;
        $phone =   old('phone', $customer->phone ?? '') ;
        $email = old('email', $customer->email ?? '') ;
        $address = old('address', $customer->address ?? '') ;
       
    @endphp
    <div class="cart-information">
       <div class="uk-grid uk-grid-medium mb20">
           <div class="uk-width-medium-1-2">
               <div class="form-row">
                   <input type="text" name="fullname" placeholder="Nhập họ tên" class="input-text" value="{{$name}}">
               </div>
           </div>
           <div class="uk-width-medium-1-2">
               <div class="form-row">
                   <input type="text" name="phone" placeholder="Nhập số điện thoại" class="input-text"  value={{$phone}}>
               </div>
           </div>
           <div class="uk-width-1-1 mt10">
               <div class="form-row">
                   <input type="text" name="email" placeholder="Nhập email"  class="input-text" value={{$email}}>
               </div>
           </div>
       </div>
       <div class="uk-grid uk-grid-medium mb20">
           <div class="uk-width-medium-1-3">
               <div class="form-row">
                   <select name="province_id" class="setupSelect2  province location"   id="province_id"  data-target="districts" >
                       <option value="0">Chọn Tỉnh/Thành phố</option>
                       
                       @if(isset($province))
                       @foreach($province as $prov)
                           <option @if(old('province_id') == $prov->code) selected @endif
                           value="{{ $prov->code }}">{{ $prov->name }}</option>
                       @endforeach
                   @endif
                   </select>
               </div>
           </div>
           <div class="uk-width-medium-1-3">
               <div class="form-row">
                   <select name="district_id" class="setupSelect2 districts location "  id="district_id"  data-target="wards">
                       <option value="0">Chọn Quận/Huyện</option>
                   </select>
               </div>
           </div>
           <div class="uk-width-medium-1-3">
               <div class="form-row">
                   <select name="ward_id" class="setupSelect2 wards"  id="ward_id"  >
                       <option value="0">Chọn Phường/Xã</option>
                   </select>
               </div>
           </div>

       </div>
       <div class="uk-width-medium-1-1 mb10">
           <div class="form-row">
               <input type="text" name="address" placeholder="Nhập địa chỉ chi tiết"  class="input-text" value="{{$address}}">
           </div>
       </div>
       <div class="uk-width-1-1">
           <div class="form-row">
               <textarea name="description" placeholder="Ghi chú (tuỳ chọn)"  class="input-text"></textarea>
           </div>
       </div>
    </div>
   
   
      
    <script type="text/javascript">
        var oldProvinceId = "{{ (isset($customer ->province_id)) ? $customer->province_id :old('province_id') }}";  
        var oldDistrictId = "{{ (isset($customer ->district_id)) ? $customer->district_id :old('district_id') }}";
        var oldWardId = "{{ (isset($customer ->ward_id)) ? $customer->ward_id :old('ward_id') }}";
    
    
        
    </script>