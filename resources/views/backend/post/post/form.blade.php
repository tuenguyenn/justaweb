
@include('backend.dashboard.component.breadcumb', [
    'title' => ($config['method'] == 'edit' ? $config['seo']['update']['title'] : $config['seo']['create']['title'])
])

@php
    $url = ($config['method'] == 'create' 
        ? route('post.store') 
        : route('post.update', $post->id))
@endphp


@php
    $url = ($config['method']== 'create' ? route('post.store'):route('post.update',$post->id))
@endphp

 
    @include('backend.dashboard.component.formerror')

 
    <form  action="{{$url}}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Name -->
        <div class="d-flex me-3 ">
            @include('backend.dashboard.component.content',['model'=>($post)?? null])
            @include('backend.post.post.component.aside')
        </div> 
        

        @include('backend.dashboard.component.seo',['model'=>($post)?? null]) 

      <button type="submit" class="btn btn-primary float-right " name="send" value="send">{{__('messages.btnSave')}}</button>

      
    </form>

    



