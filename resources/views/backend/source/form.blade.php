
@include('backend.dashboard.component.breadcumb', [
    'title' => ($config['method'] == 'edit' ? $config['seo']['update']['title'] : $config['seo']['create']['title'])
])
@php
    $url = ($config['method']== 'create' ? route('source.store'):route('source.update',$source->id))
@endphp
    @include('backend.dashboard.component.formerror')

    <form action="{{ $url }}" method="POST" enctype="multipart/form-data">
        @csrf
       <div class="d-flex  mb-2">
       
        
        @include('backend.source.component.list')

      
        <div class="col-lg-3 p-4  bg-white  shadow-sm rounded">
            @include('backend.source.component.aside')
            
        </div>
        
       </div>
       <button type="submit" class="btn btn-primary float-right mt-2">Lưu</button>

      

    

    </form>






