
@php
    $url = ($config['method']== 'create' ? route('customer.catalogue.store'):route('customer.catalogue.update',$customerCatalogues->id))
@endphp
@include('backend.dashboard.component.breadcumb', [
    'title' => ($config['method'] == 'edit' ? $config['seo']['update']['title'] : $config['seo']['create']['title'])
])
<div class="container mt-5 col-lg-6 float-left">
    <h2 class="text-center mb-4">Create Account</h2>
 
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
 
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
                value="{{old('name',($customerCatalogues->name) ?? '')}}"
            >
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Mô tả:</label>
            <input 
                type="text" 
                class="form-control description" 
                id="description" 
                name="description" 
                value="{{old('description',($customerCatalogues->description) ?? '')}}"
            >
        </div>

        <button type="submit" class="btn btn-primary w-100" name="send" value="send">Register</button>
    </form>
</div>



