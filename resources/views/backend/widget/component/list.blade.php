<div class="d-flex flex-column">
    
    <div class="bg-white  me-3">
        <div class="widget-title pt-4 ps-3 pe-3">
            <h5 class=" mainColor">THÔNG TIN WIDGET</h5>
            
        <hr>
        </div>
        <div class="widgetContent">
            @include('backend.dashboard.component.content',['offTitle'=> TRUE, 'model'=> ($widget ?? null)])
        </div>
    </div> 

     <div class="bg-white mt-3 me-3">
        <div class="widget-title pt-4 ps-3 pe-3">
            <a href="" class="upload-picture float-right">Chọn hình</a>

            <h5 class=" mainColor">CẤU HÌNH NỘI DUNG WIDGET</h5>
        
        </div>
        <div class="widgetContent module-list">
          
            @include('backend.dashboard.component.album',['offTitle'=> TRUE,'album'=> ($widget->album ?? null) ])
        </div>
      
        
    </div>        

     
    <div class="bg-white mt-3 me-3 p-4 rounded shadow-sm">
        <div class="widget-title mb-3">
            <h5 class="mainColor ">CẤU HÌNH NỘI DUNG WIDGET</h5>
            <hr>
        </div>
        <div class="widgetContent module-list mb-4">
            <div class="labelText fw-bold mb-2">Chọn Module</div>
            @foreach (__('module.model') as $key => $val)
                <div class="form-check mb-2">
                    <input type="radio" 
                           class="form-check-input input-radio" 
                           value="{{$key}}" 
                           id="model_{{$key}}" 
                           name="model"
                           {{ (old('model',($widget->model)?? null) == $key) ? 'checked' : ''}}>
                    <label class="form-check-label" for="model_{{$key}}">
                        {{$val}}
                    </label>
                </div>
            @endforeach
        </div>
        <div class="search-model-box mb-3 ">
            <div class="position-relative">  
                <i class="fa fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                <input type="text" class="form-control ps-5 search-model" placeholder="Tìm kiếm module">
            </div>
           
            <div class="ajax-search-result  shadow bg-body rounded ">

        
            </div>
        </div>
        @php
            $model_id = old('model_id',($widgetItem) ?? null)
        @endphp
        <div class="search-model-result">
            @if (!is_null($model_id))
             @foreach ($model_id['id'] as $key => $val)
            <div class="search-model-item d-flex align-items-center justify-content-between  border rounded mb-2" 
            data-modelId="{{$val}}"
                id="model-{{$val}}">
            <div class="d-flex align-items-center">
                <img class=" me-3" 
                    src="{{$model_id['image'][$key]}}" 
                    alt="Image">
                <span class="text-primary">{{$model_id['name'][$key]}}</span>
                <div class="d-none">
                    <input type="text" name="model_id[id][]" value="{{$val}}">
                    <input type="text" name="model_id[name][]" value="{{$model_id['name'][$key]}}">
                    <input type="text" name="model_id[image][]" value="{{$model_id['image'][$key]}}">

                </div>
            </div>
        <a class="delete-model-item text-danger fw-bold cursor-pointer"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" color="black" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"></path>
        </svg></a>
        
        </div>
            @endforeach 
          
      
            @endif
        </div>
    </div>
     
</div> 
