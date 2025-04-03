
@php
$catalogue= [];
if(isset($attribute)){
    foreach ($attribute->attribute_catalogues as $key => $value) {
        $catalogue[] = $value->id;
    }
}

@endphp
<div class="col-lg-4 bg-white border " >
    <div class="p-5">
    <h2 class="fw-normal ">Contact Details</h2>
    <div class="mb-4 pb-2">
        
        <div data-mdb-input-init class="form-outline form-white col-12">
            <label class="form-label" for="form3Examplea2">Chọn danh mục cha</label>
          
            <select name="attribute_catalogue_id"  class="form-control">
               @foreach($dropdown as $key =>$val){
                    <option 
                    {{$key == old('attribute_catalogue_id', (isset($attribute->attribute_catalogue_id)) ? $attribute->attribute_catalogue_id: '') ? 'selected' :''}} 
                    value = "{{ $key }}">{{ $val }}
                </option>
               }
               @endforeach
    
            </select>
         
            </div>
            <div data-mdb-input-init class="form-outline form-white col-12">
                <label class="form-label" for="form3Examplea2">Chọn danh mục phụ</label>
                <select class="form-control sl2-multiple" name="catalogue[]" multiple="multiple">
                    @foreach($dropdown as $key => $val)
                        <option 
                            {{ in_array($key, old('catalogue', isset($catalogue) ? $catalogue : [])) ? 'selected' : '' }}
                            value="{{ $key }}">{{ $val }}
                        </option>
                    @endforeach
                </select>
            </div>

              
    </div>
    <div class="mb-3">
        <label for="image" class="form-label ">Ảnh</label>
        <span class="image img-cover image-target"><img src="{{old('image',($attribute->image) ?? 'backend/img/no-image-icon.jpg')}}" alt=""> </span>
        <input type="hidden" name="image" 
         value="{{old('image',($attribute->image) ?? '')}}"
        >
    </div>
    <div class="mb-4 pb-2">
        <div data-mdb-input-init class="form-outline form-white">
            <label class="form-label" for="form3Examplea2">Chọn tình trạng</label>
            <select name="publish" class="form-control">
                @foreach(config('apps.general.publish') as $key => $val)
                    <option value="{{$key}}" {{ old('publish', $attribute->publish ?? '') == $key ? 'selected' : '' }}>
                        {{$val}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    
    <div class="mb-4 pb-2">
        <div data-mdb-input-init class="form-outline form-white">
            <label class="form-label" for="form3Examplea2">Chọn điều hướng</label>
            <select name="follow" class="form-control">
                @foreach(config('apps.general.follow') as $key => $val)
                    <option value="{{$key}}" {{ old('follow', $attribute->follow ?? '') == $key ? 'selected' : '' }}>
                        {{$val}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    
</div>

</div>


<script>
$(document).ready(function() {
    $('.sl2-multiple').select2({
        placeholder: "Chọn một giá trị",
        allowClear: true
    });
});

</script>