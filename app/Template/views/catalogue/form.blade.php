
@include('backend.dashboard.component.breadcumb', [
    'title' => ($config['method'] == 'edit' ? $config['seo']['update']['title'] : $config['seo']['create']['title'])
])

@php
    $url = ($config['method'] == 'create' 
        ? route('{view}.store') 
        : route('{view}.update', ${module}->id))
@endphp

<div>
 
    @include('backend.dashboard.component.formerror')

 
    <form  action="{{$url}}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Name -->
        <div class="d-flex h-100">
            @include('backend.dashboard.component.content',['model'=>(${module})?? null])
            @include('backend.dashboard.component.aside',['model'=>(${module})?? null])
          </div>
        </div>
         
  
        @include('backend.dashboard.component.seo',['model'=>(${module})?? null])

      <button type="submit" class="btn btn-primary float-right " name="send" value="send">{{__('messages.btnSave')}}</button>

      
    </form>
</div>
    



