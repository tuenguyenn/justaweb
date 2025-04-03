
@include('backend.dashboard.component.breadcumb', [
    'title' => ($config['method'] == 'edit' ? $config['seo']['update']['title'] : $config['seo']['create']['title'])
])
@php
    $url = ($config['method']== 'create' ? route('user.store'):route('user.update',$user->id))
@endphp
<div class="container mt-5 col-lg-6">
    <h2 class="text-center mb-4">Create Account <i class="bi bi-airplane-engines-fill"></i></h2>
    
    <!-- Error Messages -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $url }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Name -->
        <div class="form-floating mb-3">
            <input 
                type="text" 
                class="form-control" 
                id="name" 
                name="name" 
                value="{{ old('name', $user->name ?? '') }}" 
                placeholder="Name"
            >
            <label for="name">Name</label>
        </div>

        <!-- Email -->
        <div class="form-floating mb-3">
            <input 
                type="text" 
                class="form-control" 
                id="email" 
                name="email" 
                value="{{ old('email', $user->email ?? '') }}" 
                placeholder="Email"
            >
            <label for="email">Email</label>
        </div>

        <!-- Phone -->
        <div class="form-floating mb-3">
            <input 
                type="tel" 
                class="form-control" 
                id="phone" 
                name="phone" 
                value="{{ old('phone', $user->phone ?? '') }}" 
                placeholder="Phone Number"
            >
            <label for="phone">Phone Number</label>
        </div>

        <!-- Role Selection -->
        <div class="mb-3">
            <label for="user_catalogue_id" class="form-label">Role</label>
            <select class="form-select" id="user_catalogue_id" name="user_catalogue_id">
                <option value="0" {{ old('user_catalogue_id', $user->user_catalogue_id ?? '') == '0' ? 'selected' : '' }}>Select Role</option>
                <option value="1" {{ old('user_catalogue_id', $user->user_catalogue_id ?? '') == '1' ? 'selected' : '' }}>Admin</option>
                <option value="2" {{ old('user_catalogue_id', $user->user_catalogue_id ?? '') == '2' ? 'selected' : '' }}>User</option>
            </select>
        </div>

        <!-- Date of Birth -->
        <div class="form-floating mb-3">
            <input 
                type="date" 
                class="form-control" 
                id="birthday" 
                name="birthday" 
                value="{{ old('birthday', isset($user->birthday) ? date('Y-m-d', strtotime($user->birthday)) : '') }}"
                placeholder="Date of Birth"
            >
            <label for="birthday">Date of Birth</label>
        </div>

        <!-- Address -->
    
        <div class="row">
            <!-- City -->
            <div class="col-md-4 mb-3">
                <label for="city" class="form-label">City:</label>
                <select class="setupSelect2 form-select province location" id="province_id" name="province_id" data-target="districts">
                    <option value="" selected>Chọn Tỉnh/Thành Phố</option>
                    @if(isset($province))
                        @foreach($province as $prov)
                            <option @if(old('province_id') == $prov->code) selected @endif
                            value="{{ $prov->code }}">{{ $prov->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        
            <!-- District -->
            <div class="col-md-4 mb-3">
                <label for="district" class="form-label" >District:</label>
                <select class="form-select districts location" id="district_id" name="district_id" data-target="wards">
                    <option value="0" selected>[Chọn Quận/Huyện]</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="ward" class="form-label">Ward:</label>
                <select class="form-select wards" id="ward_id" name="ward_id">
                    <option value="" selected>Select Ward</option>
                   
                </select>
            </div>
        </div>
        <!-- Address -->
        <div class="form-floating mb-3">
            <input 
                type="text" 
                class="form-control" 
                id="address" 
                name="address" 
                value="{{ old('address', $user->address ?? '') }}" 
                placeholder="Address"
            >
            <label for="address">Address</label>
        </div>

        <!-- Image Upload -->
        <div class="form-floating mb-3">
            <input type="text" class="form-control upload-image" id="image" name="image" accept="image/*"
            value="{{old('image',($user->image) ?? '')}}"

            placeholder="Img URL">
            <label for="image">Image URL</label>
        </div>

        <!-- Password -->
        @if ($config['method'] == 'create')
            <div class="form-floating mb-3">
                <input 
                    type="password" 
                    class="form-control" 
                    id="password" 
                    name="password" 
                    placeholder="Password"
                >
                <label for="password">Password</label>
            </div>

            <div class="form-floating mb-3">
                <input 
                    type="password" 
                    class="form-control" 
                    id="re_password" 
                    name="re_password" 
                    placeholder="Confirm Password"
                >
                <label for="re_password">Confirm Password</label>
            </div>
        @endif

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
</div>


<script type="text/javascript">
    var oldProvinceId = "{{ (isset($user ->province_id)) ? $user->province_id :old('province_id') }}";  
    var oldDistrictId = "{{ (isset($user ->district_id)) ? $user->district_id :old('district_id') }}";
    var oldWardId = "{{ (isset($user ->ward_id)) ? $user->ward_id :old('ward_id') }}";


    
</script>



