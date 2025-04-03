

@include('backend.dashboard.component.breadcumb', [
    'title' => ($config['method'] == 'edit' ? $config['seo']['update']['title'] : $config['seo']['create']['title'])
])
@php
    $url = ($config['method']== 'create' ? route('permission.store'):route('permission.update',$permission->id))
@endphp
<div class="container mt-5 col-lg-6 float-left">
    <h2 class="text-center mb-4">{{$config['seo']['create']['title']}}</h2>
 
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
            <label for="name" class="form-label">{{__('messages.permission.name')}}</label>
            <input 
                type="text" 
                class="form-control name" 
                id="name" 
                name="name" 
                value="{{old('name',($permission->name) ?? '')}}"
            >
        </div>
        <div class="mb-3">
            <label for="canonical" class="form-label">{{__('messages.permission.canonical')}}</label>
            <input 
                type="text" 
                class="form-control canonical" 
                id="canonical" 
                name="canonical" 
                value="{{old('canonical',($permission->canonical) ?? '')}}"
            >
        </div>
     
       
        <button type="submit" class="btn btn-primary w-100" name="send" value="send">{{__('messages.btnSave')}}</button>
    </form>
</div>



