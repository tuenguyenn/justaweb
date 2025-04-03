
@include('backend.dashboard.component.breadcumb', [
    'title' => ($config['method'] == 'edit' ? $config['seo']['update']['title'] : $config['seo']['create']['title'])
])
@php
    $url = ($config['method']== 'create' ? route('slide.store'):route('slide.update',$slide->id))
@endphp
    @include('backend.dashboard.component.formerror')

    <form action="{{ $url }}" method="POST" enctype="multipart/form-data">
        @csrf
       <div class="d-flex me-3 mb-2">
       
        <div class="col-lg-9 h-100 p-4 bg-white me-3 shadow-sm rounded">
            @include('backend.slide.slide.component.list')

        </div> <!-- end col-lg-9 -->
        
        <div class="col-lg-3 p-4 h-100 bg-white  shadow-sm rounded">
            @include('backend.slide.slide.component.aside')
            
       

        </div>
        
       </div>
       <button type="submit" class="btn btn-primary float-right mt-2">Lưu</button>

      

    
 
        <!-- Submit Button -->
    </form>





