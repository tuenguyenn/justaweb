<div class="col-lg-3 bg-white " >
    <div class="p-5">
    <h2 class="fw-normal ">  {{ __('messages.cata') }}</h2>
    <div class="mb-4 pb-2">
        <div data-mdb-input-init class="form-outline form-white">
            <label class="form-label" for="form3Examplea2">
                {{ __('messages.parent') }}
            </label>
            <span class="text-danger d-block fst-italic">  *{{ __('messages.parentNotice') }}</span>
            
      
        <select name="parent_id"  class="form-control">
           @foreach($dropdown as $key =>$val){
              
                <option 
                {{$key == old('parent_id', (isset($model->parent_id)) ? $model->parent_id: '') ? 'selected' :''}} 
                value = "{{ $key }}">{{ $val }}
            </option>
           }
           @endforeach

        </select>

        </div>
    </div>
    <div class="mb-3">
        <label for="image" class="form-label ">  {{ __('messages.image') }}</label>
        <span class="image img-cover image-target"><img src="{{old('image',($model->image) ?? 'backend/img/no-image-icon.jpg')}}" alt=""> </span>
        <input type="hidden" name="image" 
         value="{{old('image',($model->image) ?? '')}}"
        >
    </div>
    <div class="mb-4 pb-2">
        <div data-mdb-input-init class="form-outline form-white">
            <label class="form-label" for="form3Examplea2">{{ __('messages.selectStatus') }}</label>
            <select name="publish" class="form-control">
                @foreach(config('apps.general.publish') as $key => $val)
                    <option value="{{$key}}" {{ old('publish', $model->publish ?? '') == $key ? 'selected' : '' }}>
                        {{$val}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    
    <div class="mb-4 pb-2">
        <div data-mdb-input-init class="form-outline form-white">
            <label class="form-label" for="form3Examplea2">{{ __('messages.selectFollow') }}</label>
            <select name="follow" class="form-control">
                @foreach(config('apps.general.follow') as $key => $val)
                    <option value="{{$key}}" {{ old('follow', $model->follow ?? '') == $key ? 'selected' : '' }}>
                        {{$val}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    
</div>
</div>