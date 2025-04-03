<div class="form-section bg-white p-4">
    <h5 class="mainColor">THỜI GIAN ÁP DỤNG</h5>
    <hr>
    <div class="row g-3">
      <div class="col-md-6">
        <label for="start-date">Ngày bắt đầu</label>
        <input type="datetime-local" id="start-date" name="startDate" 
          value="{{ isset($model->startDate) ? date('Y-m-d\TH:i', strtotime($model->startDate)) : '' }}" 
          class="form-control">
      </div>
      @php
      $neverEndDate = old('neverEndDate', $model->neverEndDate ?? '');
      if ($neverEndDate === 'null') {
          $neverEndDate = '';
      }
  @endphp

  
          
      <div class="col-md-6">
        <label for="end-date">Ngày kết thúc</label>
        <input type="datetime-local" id="end-date" name="endDate" 
          value="{{ old('endDate', isset($model->endDate) ? date('Y-m-d\TH:i', strtotime($model->endDate)) : '') }}" 
          class="form-control"
          @if($neverEndDate === 'accept')
            disabled
         @endif>
      </div>
      
    </div>
    <div class="error-date d-none">
      <p class="text-danger fst-italic "> Ngày kết thúc không được trước ngày bắt đầu</p>
    </div>
    <div class="form-check mt-1">
      <input class="form-check-input" type="checkbox" id="no-end-date" name="neverEndDate" value="accept"
       @if($neverEndDate === 'accept' || $neverEndDate = '') checked @endif>
      <label class="form-check-label" for="no-end-date">Không có ngày kết thúc</label>
    </div>
    
  </div>
  
  

  <!-- Customer Source Section -->
  <div class="form-section bg-white p-4 mt-3">
    <h5 class="mainColor">NGUỒN KHÁCH ÁP DỤNG</h5>
    <hr>
    @php
        $sourceStatus = old('source',($model->discountInformation['apply']['status']) ?? [])
        
    @endphp
    <div>
      <div class="form-check">
        <input class="form-check-input chooseSource " type="radio" name="source" id="apply-all" value="all" checked
        {{(old('source', ($model->discountInformation['apply']['status'] ) ?? '') === 'all') ? 'checked' : ''}}>
        <label class="form-check-label" for="apply-all">Áp dụng cho toàn bộ nguồn khách</label>
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input  chooseSource" type="radio" name="source" id="select-source" value="choose"
        {{(old('source', ($model->discountInformation['apply']['status'] ) ?? '') === 'choose') ? 'checked' : ''}}>
        

        <label class="form-check-label" for="select-source">Chọn nguồn khách áp dụng</label>
      </div>
      
    </div>
   
    @if($sourceStatus == 'choose')
      @php
        $sourceValue = old('sourceValue',($model->discountInformation ['source']['data']) ?? []);
      
      @endphp
      
      <div class="source-wrapper">
        <select class="multipleSelect2 " name="sourceValue[]" multiple="" data-select2-id="1" tabindex="-1" aria-hidden="true">
          @foreach ($source as $key => $val)
            
            <option value="{{$val->id}}"
              {{(in_array($val->id,$sourceValue)) ? 'selected' : ' ' }}
                  
              >{{$val->name}} 
            </option>
              
          @endforeach
        </select>
          </div>
    
    @endif
  </div>
  <div class="form-section bg-white p-4 mt-3">
    <h5 class="mainColor">ĐỐI TƯỢNG ÁP DỤNG</h5>
    <hr>
    @php
      $applyStatus = old('applyStatus',($model->discountInformation ['source']['status']) ?? [])
    @endphp
    <div class="form-check">
      <input 
          class="form-check-input chooseApply"
          type="radio" 
          name="applyStatus" 
          id="allApply" 
          value="all" 
          {{ old('applyStatus',$applyStatus) === 'all' ? 'checked' : '' }} 
          checked>
      <label class="form-check-label" for="allApply">Áp dụng cho toàn bộ đối tượng</label>
  </div>
  
      <div class="form-check">
        <input class="form-check-input chooseApply" type="radio" name="applyStatus" id="chooseApply" value="choose"
        {{(old('applyStatus', $applyStatus) === 'choose') ? 'checked' : ''}}>

        
        <label class="form-check-label" for="chooseApply">Chọn đối tượng khách hàng </label>
      </div>
     
      @if ($applyStatus == 'choose')
        @php
         
        $applyValue = old('applyValue',($model->discountInformation ['apply']['data']) ?? []);
       

       @endphp
        <div class="apply-wrapper"> 
          <select class="multipleSelect2 conditionItem " name="applyValue[]" multiple="" data-select2-id="2" tabindex="-1" aria-hidden="true">
            @foreach ((__('module.applyStatus')) as $item)
            <option value="{{ $item['id'] }}"
             >

                {{ $item['name'] }}
            </option>
            @endforeach
          </select>
              <div class="wrapper-condition">
               
                
              </div>
        </div>
      @endif
      
    
  </div>

  @php
  $applyValueSelected =  old('applyValue',($model->discountInformation ['apply']['data']) ?? []);
  
  

@endphp
<input type="hidden" class="input-product-and-quantity" value ="{{ json_encode(__('module.item')) }}">
<input type="hidden"  class="conditionItemSelected"  value="{{ json_encode( $applyValueSelected)}}">
@if (count($applyValueSelected))
@foreach ($applyValueSelected as $key => $val)
<input type="hidden"  class="condition_input_{{ $val }}"  value="{{ json_encode( old($val,$model->discountInformation ['apply']['condition'][$val] ?? []))}}"  > 
@endforeach

@endif