
@include('backend.dashboard.component.breadcumb', [
    'title' => ($config['method'] == 'edit' ? $config['seo']['update']['title'] : $config['seo']['create']['title'])
])
@php
    $url = ($config['method']== 'create' ? route('customer.store'):route('customer.update',$customer->id))
@endphp


    <form action="{{ $url }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('backend.dashboard.component.formerror')
        <div class="mt-2 d-flex">
           
        <!-- Thông tin tài khoản -->
        <div class=" p-3 col-lg-8 bg-white rounded">
            <h4 class="mb-3 mainColor">THÔNG TIN TÀI KHOẢN</h4>
            <hr>
           <div class="d-flex">
            <div class="col-lg-4">
                <p>Nhập thông tin của người sử dụng</p>
                <p>Lưu ý: Những trường đánh dấu <span class="text-danger">(*)</span> là bắt buộc</p>
            </div>
            <div class="col-lg-8">
                <div class="d-flex mb-3">
                    <div class="form-floating col-lg-6 ">
                        <input 
                            type="text" 
                            class="form-control" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $customer->name ?? '') }}" 
                            placeholder="Name">
                        <label for="name">Họ Tên <span class="text-danger">(*)</span></label>
                    </div>
        
                    <!-- Email -->
                    <div class="form-floating col-lg-6 ">
                        <input 
                            type="email" 
                            class="form-control" 
                            id="email" 
                            name="email" 
                            value="{{ old('email', $customer->email ?? '') }}" 
                            placeholder="Email">
                        <label for="email">Email <span class="text-danger">(*)</span></label>
                    </div>
                </div>
    
              
    
                <!-- Role/ Nền tảng -->
                <div class="mb-3">
                    <label for="customer_catalogue_id" class="form-label">Nền tảng</label>
                    <select class="form-select" id="customer_catalogue_id" name="customer_catalogue_id">
                        <option value="0" {{ old('customer_catalogue_id', $customer->customer_catalogue_id ?? '') == '0' ? 'selected' : '' }}>Chọn nền tảng</option>
                        @foreach($catalogues as $catalogue)
                            <option value="{{ $catalogue->id }}" {{ old('customer_catalogue_id', $customer->customer_catalogue_id ?? '') == $catalogue->id ? 'selected' : '' }}>
                                {{ $catalogue->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
    
                <!-- Nhóm khách hàng -->
                <div class="mb-3">
                    <label for="source_id" class="form-label">Nhóm Khách Hàng</label>
                    <select class="form-select" id="source_id" name="source_id">
                        <option value="0" {{ old('source_id', $customer->source_id ?? '') == '0' ? 'selected' : '' }}>Chọn nhóm</option>
                        @foreach($sources as $source)
                            <option value="{{ $source->id }}" {{ old('source_id', $customer->source_id ?? '') == $source->id ? 'selected' : '' }}>
                                {{ $source->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
    
                <!-- Date of Birth -->
                <div class="form-floating mb-3">
                    <input 
                        type="date" 
                        class="form-control" 
                        id="birthday" 
                        name="birthday" 
                        value="{{ old('birthday', isset($customer->birthday) ? date('Y-m-d', strtotime($customer->birthday)) : '') }}"
                        placeholder="Date of Birth">
                    <label for="birthday">Ngày sinh</label>
                </div>
                  <!-- Password (chỉ hiển thị khi tạo mới) -->
                  @if ($config['method'] == 'create')
                  <div class="form-floating mb-3">
                      <input 
                          type="password" 
                          class="form-control" 
                          id="password" 
                          name="password" 
                          placeholder="Password">
                      <label for="password">Mật khẩu </label>
                  </div>
  
                  <div class="form-floating mb-3">
                      <input 
                          type="password" 
                          class="form-control" 
                          id="re_password" 
                          name="re_password" 
                          placeholder="Confirm Password">
                      <label for="re_password">Xác nhận mật khẩu</label>
                  </div>
              @endif
              <div class="form-floating mb-3">
                <input 
                    type="text" 
                    class="form-control upload-image" 
                    id="image" 
                    name="image" 
                    accept="image/*"
                    value="{{ old('image', $customer->image ?? '') }}"
                    placeholder="Img URL">
                <label for="image">Ảnh đại diện</label>
            </div>
            </div>
           </div>
           
        </div>

        <!-- Thông tin liên hệ -->
        <div class="border-left p-3 bg-white col-lg-4 rounded">
            <h4 class="mb-3 mainColor">THÔNG TIN LIÊN HỆ</h4>
            <hr>

            <!-- Phone -->
            <div class="form-floating mb-3">
                <input 
                    type="tel" 
                    class="form-control" 
                    id="phone" 
                    name="phone" 
                    value="{{ old('phone', $customer->phone ?? '') }}" 
                    placeholder="Phone Number">
                <label for="phone">Số điện thoại</label>
            </div>

            <!-- Address -->
            <div class="form-floating mb-3">
                <input 
                    type="text" 
                    class="form-control" 
                    id="address" 
                    name="address" 
                    value="{{ old('address', $customer->address ?? '') }}" 
                    placeholder="Address">
                <label for="address">Địa chỉ</label>
            </div>

            <!-- Địa chỉ chi tiết: Tỉnh/Thành, Quận/Huyện, Phường/Xã -->
            <div class="row">
                <!-- City/Province -->
                <div class="col-md-4 mb-3">
                    <label for="province_id" class="form-label">Tỉnh/Thành</label>
                    <select class="setupSelect2 form-select province location" id="province_id" name="province_id" data-target="districts">
                        <option value="" selected>Chọn Tỉnh/Thành Phố</option>
                        @if(isset($province))
                            @foreach($province as $prov)
                                <option value="{{ $prov->code }}" {{ old('province_id') == $prov->code ? 'selected' : '' }}>
                                    {{ $prov->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- District -->
                <div class="col-md-4 mb-3">
                    <label for="district_id" class="form-label">Quận/Huyện</label>
                    <select class="form-select districts location" id="district_id" name="district_id" data-target="wards">
                        <option value="0" selected>[Chọn Quận/Huyện]</option>
                    </select>
                </div>

                <!-- Ward -->
                <div class="col-md-4 mb-3">
                    <label for="ward_id" class="form-label">Phường/Xã</label>
                    <select class="form-select wards" id="ward_id" name="ward_id">
                        <option value="" selected>Select Ward</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary ">Register</button>    

            <!-- Image Upload (URL) -->
            
        </div>

        <!-- Submit Button -->
    </div>  
   

    </form>




<script type="text/javascript">
    var oldProvinceId = "{{ (isset($customer ->province_id)) ? $customer->province_id :old('province_id') }}";  
    var oldDistrictId = "{{ (isset($customer ->district_id)) ? $customer->district_id :old('district_id') }}";
    var oldWardId = "{{ (isset($customer ->ward_id)) ? $customer->ward_id :old('ward_id') }}";


    
</script>



