<div class="rounded pt-2 {{  isset($offCol9) ? 'col-lg-12' : 'col-lg-9' }} mb-2 bg-white ">
  <div class="p-2 ">
      <h4 class="mainColor" >{{ __('messages.seo') }}</h4>
      
      <div class="meta-title">
          {{ old('meta_title', $model->meta_title ?? __('messages.seoTitle')) }}
      </div>

      <!-- Canonical -->
      @php
          $canonicalValue = old('canonical', $model->canonical ?? '');
      @endphp
      <div class="canonical">
          {{ $canonicalValue ? config('app.url') . $canonicalValue : __('messages.seo_canonical') }}
      </div>

      <!-- Meta Description -->
      <div class="meta-description ps-3 mb-3">
          {{ old('meta_description', $model->meta_description ?? __('messages.seo_description')) }}
      </div>       

      <!-- SEO Form -->
      <div class="seo mb-3 pb-2">
          <!-- Meta Title Input -->
          <div data-mdb-input-init class="form-outline">
              <label class="form-label" for="form3Examplev2">
                  <strong class="text-dark">{{ __('messages.seo_meta_title') }}</strong>
              </label>
              <input 
                  type="text" 
                  class="form-control meta_title form-control-lg" 
                  id="form3Examplev2_meta_title" 
                  name="meta_title" 
                  {{ isset($disabled) ? 'disabled' : '' }}
                  value="{{ old('meta_title', $model->meta_title ?? '') }}"
              >
          </div>

          <!-- Meta Keyword Input -->
          <div data-mdb-input-init class="form-outline">
              <label class="form-label" for="form3Examplev2">
                  <strong class="text-dark">{{ __('messages.seo_meta_keyword') }}</strong>
              </label>
              <input 
                  type="text" 
                  class="form-control meta_keyword form-control-lg" 
                  id="form3Examplev2_meta_keyword" 
                  name="meta_keyword" 
                  {{ isset($disabled) ? 'disabled' : '' }}
                  value="{{ old('meta_keyword', $model->meta_keyword ?? '') }}"
              >
          </div>

          <!-- Meta Description Input -->
          <div data-mdb-input-init class="form-outline">
              <label class="form-label" for="form3Examplev2">
                  <strong class="text-dark">{{ __('messages.seo_meta_desc') }}</strong>
              </label>
              <textarea 
                  class="form-control meta_description form-control-lg" 
                  id="form3Examplev2_meta_description" 
                  name="meta_description" 
                  {{ isset($disabled) ? 'disabled' : '' }}
              >{{ old('meta_description', $model->meta_description ?? '') }}</textarea>
          </div>

          <!-- Canonical Input -->
          <div class="link form-outline">
              <label class="form-label" for="form3Examplev2">
                  <span>{{ __('messages.canonical') }}</span>
              </label>
              <div class="input-wrapper d-flex">
                  <span class="baseUrl">{{ config('app.url') }}</span>
                  <input 
                      type="text" 
                      class="form-control seo-canonical form-control-lg" 
                      id="form3Examplev2" 
                      name="canonical" 
                      {{ isset($disabled) ? 'disabled' : '' }}
                      value="{{ old('canonical', $model->canonical ?? '') }}" 
                  >
              </div>
          </div>
      </div>
  </div>
</div>
