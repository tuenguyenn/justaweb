<div class="mt-2 col-lg-12 rounded bg-white">
    <div class="p-3">
      <h2 class="fw-normal mb-4" style="color: #1a0dab;"> {{ __('messages.seo') }}</h2>
      <div class="meta-title">
        {{ old('meta_title', $model->meta_title ??  __('messages.seoTitle') ) }}
    </div>
            @php
            $canonicalValue = old('canonical', $model->canonical ?? '');
        @endphp
      <div class="canonical">
          {{$canonicalValue ? config('app.url') . $canonicalValue : __('messages.seo_canonical')}}
      </div>
        <div class="meta-description ps-3 mb-2">
          {{ old('meta_description', $model->meta_description ?? __('messages.seo_description')) }}
      </div>       
     <div class="seo mb-2 pb-2">

            <div data-mdb-input-init class="form-outline mb-2">
              <label class="form-label" for="form3Examplev2"> <strong class="text-dark">{{__('messages.seo_meta_title')}}</strong> </label>
              <input 
              type="text" 
              class="form-control meta_title form-control-lg" 
              id=" form3Examplev2 meta_title" 
              name="translate_meta_title" 
              value="{{old('meta_title',($model->meta_title) ?? '')}}"
          >
            </div>
            <div data-mdb-input-init class="form-outline mb-2">
                <label class="form-label" for="form3Examplev2"><strong class="text-dark" >{{__('messages.seo_meta_keyword')}}</strong> </label>

                <input 
                type="text" 
                class="form-control meta_keyword form-control-lg" 
                id=" form3Examplev2 meta_keyword" 
                name="translate_meta_keyword" 
                value="{{old('meta_keyword',($model->meta_keyword) ?? '')}}"
            >
              </div>
              <div data-mdb-input-init class="form-outline mb-2">
                <label class="form-label" for="form3Examplev2"><strong class="text-dark">{{__('messages.seo_meta_desc')}}</strong> </label>

                <textarea 
                type="text" 
                class="form-control meta_description form-control-lg  " 
                id=" form3Examplev2 meta_description" 
                name="translate_meta_description" 
                >{{ old('meta_description', ($model->meta_description) ?? '') }}</textarea>

            </textarea>
              </div>
              <div class="link form-outline mb-2">
                <label class="form-label" for="form3Examplev2">
                    <strong class="text-dark">{{__('messages.canonical')}}</strong>
                </label>
                <div class="input-wrapper">
                    <span class="baseUrl">{{config('app.url')}}</span>
                    <input 
                    type="text" 
                    class="form-control seo-canonical form-control-lg" 
                    id="form3Examplev2" 
                    name="translate_canonical" 
                    value="{{old('canonical',($model->canonical) ?? '')}}" 
                >
                
                </div>
            </div>
            
    
          </div>
    </div>
</div>

