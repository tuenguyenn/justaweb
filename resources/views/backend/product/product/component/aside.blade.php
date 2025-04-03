
@php
$catalogue= [];
if(isset($product)){
    foreach ($product->product_catalogues as $key => $value) {
        $catalogue[] = $value->id;
    }
}

@endphp
<div class="col-lg-3 mb-2 " >
    <div class="mb-3 p-4 bg-white  rounded">
        <div data-mdb-input-init class="form-outline form-white col-12">
            <label class="form-label" for="form3Examplea2">Chọn danh mục cha <span class="text-danger">(*)</span></label>
          
            <select name="product_catalogue_id"  class="form-control">
               @foreach($dropdown as $key =>$val){
                    <option 
                    {{$key == old('product_catalogue_id', (isset($product->product_catalogue_id)) ? $product->product_catalogue_id: '') ? 'selected' :''}} 
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
    <div class="mb-3 p-4 bg-white  rounded">
        <div data-mdb-input-init class="form-outline form-white col-12">
    
            <!-- Mã sản phẩm -->
            <div class="mb-2 d-flex align-items-center">
                <label for="sku" class="form-label me-2" style="min-width: 120px;"><strong>Mã sản phẩm :</strong></label>
                <input type="text" class="form-control" name="code" id="code" value="{{ old('sku', $product->code ?? '') }}">
            </div>
    
            <!-- Giá -->
            <div class="mb-2 d-flex align-items-center">
                <label for="price" class="form-label me-2" style="min-width: 120px;"><strong>Giá :</strong></label>
                <input type="text" class="form-control text-primary" name="price" id="price" value="{{ old('price', $product->price ?? '') }}">
            </div>
    
            <!-- Xuất xứ -->
            <div class="mb-2 d-flex align-items-center">
                <label for="made_in" class="form-label me-2" style="min-width: 120px;"><strong>Xuất xứ :</strong></label>
                <input type="text" class="form-control" name="made_in" id="made_in" value="{{ old('made_in', $product->made_in ?? '') }}">
            </div>
    
        </div>
    </div>
    
    <div class="mb-3 p-4 bg-white  rounded">
        <label for="image" class="form-label ">Ảnh</label>

        <div class="mb-3">
            <span class="image img-cover image-target"><img src="{{old('image',($product->image) ?? 'backend/img/no-image-icon.jpg')}}" alt=""> </span>
            <input type="hidden" name="image" 
             value="{{old('image',($product->image) ?? '')}}"
            >
        </div>
        <div class="mb-4 pb-2">
            <div data-mdb-input-init class="form-outline form-white">
                <label class="form-label" for="form3Examplea2">Chọn tình trạng</label>
                <select name="publish" class="form-control">
                    @foreach(config('apps.general.publish') as $key => $val)
                        <option value="{{$key}}" {{ old('publish', $product->publish ?? '') == $key ? 'selected' : '' }}>
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
                        <option value="{{$key}}" {{ old('follow', $product->follow ?? '') == $key ? 'selected' : '' }}>
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