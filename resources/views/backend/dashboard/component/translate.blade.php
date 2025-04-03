
<div class="rounded col-lg-12  mb-2  bg-white">
  <div class="{{ !isset($offTitle) ? 'p-3' : 'p-2' }}">
    @if (!isset($offTitle))
      <div class="col-md-12  pb-2">
        <div data-mdb-input-init class="form-outline">
          <label class="form-label" for="form3Examplev2"><strong class="text-dark"> {{ __('messages.title') }}<span class="text-danger">(*)</span></strong></label>
          <input 
            type="text" 
            class="form-control name form-control-lg" 
            id="form3Examplev2 " 
            name="translate_name" 
            value="{{ old('name', $model->name ?? '') }}" 
          />
        </div>
      </div>
      @endif

      <div class="col-md-12 pb-2 ">
        <div data-mdb-input-init class="form-outline">
          <label class="form-label" for="form3Examplev3"><strong class="text-dark"> {{ __('messages.description') }}</strong></label>
          <textarea 
            class="ck-editor form-control form-control-lg" 
            id="form3Examplev3 ck-description" 
            name="translate_description" 
            data-height="100"
          >{{ old('description', $model->description ?? '') }}</textarea>
        </div>
      </div>
   @if (!isset($offTitle))

      <div class="col-md-12  pb-2">
        <div data-mdb-input-init class="form-outline">
          <label class="form-label" for="form3Examplev4"><strong class="text-dark"> {{ __('messages.content') }}</strong></label>
          <textarea 
            class="form-control form-control-lg ck-editor" 
            id="form3Examplev4 ck-content" 
            name="translate_content" 
            data-height="270"
          >{{ old('content', $model->content ?? '') }}</textarea>
        </div>
      </div>
    </div>
  </div>
  @endif
  