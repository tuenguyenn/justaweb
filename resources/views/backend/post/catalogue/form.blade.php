
@include('backend.dashboard.component.breadcumb', [
    'title' => ($config['method'] == 'edit' ? $config['seo']['update']['title'] : $config['seo']['create']['title'])
])

@php
    $url = ($config['method'] == 'create' 
        ? route('post.catalogue.store') 
        : route('post.catalogue.update', $postCatalogue->id))
@endphp


 
    @include('backend.dashboard.component.formerror')

 
    <form  action="{{$url}}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Name -->
        <div class="d-flex h-100 me-3   ">
            @include('backend.dashboard.component.content',['model'=>($postCatalogue)?? null])
            @include('backend.dashboard.component.aside',['model'=>($postCatalogue)?? null])
        </div>
        
        <div class="me-3">

            @include('backend.dashboard.component.album')
                
            @include('backend.dashboard.component.seo',['model'=>($postCatalogue)?? null])
       </div>
       
      <button type="submit" class="btn btn-primary float-right " name="send" value="send">{{__('messages.btnSave')}}</button>

      
    </form>




