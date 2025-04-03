
<div class="rounded {{ (isset($offTitle) || (isset($offCol9))) ? 'col-lg-12' : 'col-lg-9' }} mb-2 me-3  bg-white">
  <div class="{{ !isset($offTitle) ? 'p-4' : 'p-2' }}">
  
      <!-- Input Field for Name -->
      @if (!isset($offTitle))
    
      <div class="pb-2">
        <div data-mdb-input-init class="form-outline">
          <label class="form-label" for="form3Examplev2"><strong class="text-dark"> {{ __('messages.title') }}<span class="text-danger">(*)</span></strong></label>
          <input 
            type="text" 
            class="form-control name form-control-lg" 
            id="form3Examplev2" 
            name="name" 
            {{(isset($disabled))? 'disabled' :''}}
            value="{{ old('name', $model->name ?? '') }}" 
          />
        </div>
      </div>
      @endif
  
      <!-- Textarea for Description -->
      <div class=" pb-2">
        <div data-mdb-input-init class="form-outline">
          <label class="form-label" for="form3Examplev3"><strong class="text-dark"> {{ __('messages.description') }}</strong></label>
          <textarea 
            class="ck-editor form-control form-control-lg" 
            id="form3Examplev3" 
            name="description" 
            data-height="100"
            {{(isset($disabled))? 'disabled':''}}
          >{{ old('description', $model->description ?? '') }}</textarea>
        </div>
      </div>
  
      <!-- Textarea for Content -->
      @if (!isset($offTitle))

      <div class=" mb-3 pb-2">
        <div data-mdb-input-init class="form-outline">
          <label class="form-label" for="form3Examplev4"><strong class="text-dark"> {{ __('messages.content') }}</strong></label>

          <textarea 
            class="form-control form-control-lg ck-editor" 
            id="form3Examplev4" 
            name="content" 
            data-height="270"
            {{(isset($disabled)) ? 'disabled' :''}}
          >{{ old('content', $model->content ?? '') }}</textarea>
        </div>
      </div>
      @endif

    </div>
     

  </div>
  
 
 