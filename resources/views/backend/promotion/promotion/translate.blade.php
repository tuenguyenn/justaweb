@php
    $title = str_replace('{language}',$translate->name, $config['seo']['create']['translate'].' '.$widget['name'])
@endphp
@include('backend.dashboard.component.breadcumb', [
    'title' =>  $title
])


    @include('backend.dashboard.component.formerror')

 
    <form  action="{{route('widget.saveTranslate')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="translateId" value="{{$translate->id}}">
        <input type="hidden" name="widgetId" value="{{$widget->id}}">
        <div class="d-flex me-3">
            <div class="col-lg-6 me-3 bg-white p-3">
                <div class="widget-title mb-3">
                    <h5 class="mainColor ">CẤU HÌNH NỘI DUNG WIDGET</h5>
                    <hr>
                </div>
                @include('backend.dashboard.component.content', ['model' => ($widget) ?? null ,'disabled'=>1 ,'offTitle'=> TRUE])
                
            </div>
            

            <div class="col-lg-6 bg-white p-3">
                <div class="widget-title mb-3 ">
                    <h5 class="mainColor ">CẤU HÌNH NỘI DUNG WIDGET</h5>
                    <hr>
                </div>
                @include('backend.dashboard.component.translate', ['model' => ($widgetTranslate) ?? null,'offTitle'=> TRUE])
                

            </div>
        </div>
        
        
        
      <button type="submit" class="btn btn-primary float-right " name="send" value="send">{{__('messages.btnSave')}}</button>

      
    </form>
