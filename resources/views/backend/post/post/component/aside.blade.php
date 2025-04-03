

<div class="col-lg-3 mb-2 bg-white " >
    <div class="p-4">
    <h2 class="fw-normal ">Thông tin</h2>
    <div class="mb-4 pb-2">
        
        <div data-mdb-input-init class="form-outline form-white col-12">
            <label class="form-label" for="form3Examplea2">Chọn danh mục cha</label>
          
            <select name="post_catalogue_id"  class="form-control">
               @foreach($dropdown as $key =>$val){
                    <option 
                    {{$key == old('post_catalogue_id', (isset($post->post_catalogue_id)) ? $post->post_catalogue_id: '') ? 'selected' :''}} 
                    value = "{{ $key }}">{{ $val }}
                </option>
               }
               @endforeach
    
            </select>
         
            </div>
            @php
                $catalogue= [];
                if(isset($post)){
                    foreach ($post->post_catalogues as $key => $value) {
                        $catalogue[] = $value->id;
                    }
                }

                @endphp
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
        <span class="image img-cover image-target"><img src="{{old('image',($post->image) ?? 'backend/img/no-image-icon.jpg')}}" alt=""> </span>
        <input type="hidden" name="image" 
         value="{{old('image',($post->image) ?? '')}}"
        >
    </div>
    <div class="mb-4 pb-2">
        <div data-mdb-input-init class="form-outline form-white">
            <label class="form-label" for="form3Examplea2">Chọn tình trạng</label>
            <select name="publish" class="form-control">
                @foreach(config('apps.general.publish') as $key => $val)
                    <option value="{{$key}}" {{ old('publish', $post->publish ?? '') == $key ? 'selected' : '' }}>
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
                    <option value="{{$key}}" {{ old('follow', $post->follow ?? '') == $key ? 'selected' : '' }}>
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