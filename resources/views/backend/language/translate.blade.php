@include('backend.dashboard.component.breadcumb', [
    'title' =>  $config['seo']['create']['title']
])


 
    @include('backend.dashboard.component.formerror')

 
    <form  action="{{route('language.storeTranslate')}}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <input type="hidden" name ="option[id]" value="{{$option['id']}}">
        <input type="hidden" name ="option[languageId]" value="{{$option['languageId']}}">

        <input type="hidden" name ="option[model]" value="{{$option['model']}}">

        <div class="d-flex">
            <div class="col-lg-6  me-2">
                @include('backend.dashboard.component.content', ['model' => ($object) ?? null ,'disabled'=>1 ,'offCol9'=> TRUE])
                @include('backend.dashboard.component.seo', ['model' => ($object) ?? null, 'disabled'=>1 ,'offCol9'=> TRUE])
            </div>
            

            <div class="col-lg-6 ">
                @include('backend.dashboard.component.translate', ['model' => ($objectTranslate) ?? null])
                @include('backend.dashboard.component.translateSeo', ['model' => ($objectTranslate) ?? null ])

            </div>
        </div>
              
      <button type="submit" class="btn btn-primary float-right " name="send" value="send">{{__('messages.btnSave')}}</button>

      
    </form>
