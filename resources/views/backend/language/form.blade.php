

@include('backend.dashboard.component.breadcumb', [
    'title' => ($config['method'] == 'edit' ? $config['seo']['update']['title'] : $config['seo']['create']['title'])
])
@php
    $url = ($config['method']== 'create' ? route('language.store'):route('language.update',$language->id))
@endphp
<div class="container mt-5 col-lg-6 float-left">
    <h2 class="text-center mb-4">{{$config['seo']['create']['title']}}</h2>
    @include('backend.dashboard.component.formerror')
        
    <form  action="{{$url}}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input 
                type="text" 
                class="form-control name" 
                id="name" 
                name="name" 
                value="{{old('name',($language->name) ?? '')}}"
            >
        </div>
        <div class="mb-3">
            <label for="canonical" class="form-label">Kí hiệu:</label>
            <input 
                type="text" 
                class="form-control canonical" 
                id="canonical" 
                name="canonical" 
                value="{{old('canonical',($language->canonical) ?? '')}}"
            >
        </div>
        <div class="mb-3">
            <label for="image" class="form-label ">Ảnh</label>
            <input 
                type="text" 
                class="form-control upload-image" 
                name="image" 
                value="{{old('image',($language->image) ?? '')}}"
                data-type="Images"
                placeholder=""
                autocomplete="off"

            >
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <input 
                type="text" 
                class="form-control description" 
                id="description" 
                name="description" 
                value="{{old('description',($language->description) ?? '')}}"
            >
        </div>
        <button type="submit" class="btn btn-primary w-100" name="send" value="send">Register</button>
    </form>
</div>



