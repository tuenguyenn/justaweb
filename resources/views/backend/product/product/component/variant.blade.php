
@php
$variantCatalogue = old('attributeCatalogue', (isset($product->attributeCatalogue)) 
                    ? json_decode($product->attributeCatalogue, TRUE) 
                    : []);
@endphp
<div class="rounded col-lg-9 mb-2 bg-white d-flex mt-2">
    <div class="col-lg-12 p-4">
      <h3 class="mb-2 mainColor" >Sản phẩm có nhiều phiên bản </h3>
      <p>Lựa chọn nhiều thuộc tính cho sản phẩm</p>
  
      <div class="pb-2">
        <div class="variant-checkbox">
          <input type="checkbox"
           name="accept" id="variantCheckbox" 
           class="variantInputCheckbox" 
           {{ (old('accept')== 'on' || (isset($product) && count($product->product_variants) > 0)) ? 'checked' : '' }}>
          <label for="variantCheckbox">Sản phẩm có nhiều tuỳ chọn, kích thước, màu sắc</label>
        </div>
      </div>
      <div class="variant-wrapper {{ (isset($variantCatalogue))  ? '' : 'd-none' }}">
        <div class="pb-2 d-flex variant-container">
            <div class="col-lg-3">
              <a href="">Chọn thuộc tính</a>
            </div>
            <div class="col-lg-9">
              <a href="">Chọn giá trị thuộc tính</a>
            </div>
          </div>
        <div class="variant-body" >
         
          @if ($variantCatalogue  && count($variantCatalogue))
          @foreach($variantCatalogue as $keyAttr => $valAttr)
          <div class="mb-2 variant-item row">
            <div class="col-lg-3">
                <div class="attribute-catalogue">
                    <select name="attributeCatalogue[]" class="niceSelect choose-attribute">
                      @foreach ($attributeCatalogue as $key =>$value)
                          <option  {{$valAttr == $value->id ? 'selected' : ''}} value="{{$value->id}}">{{$value->attribute_catalogue_language->first()->name}}</option>
                      @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-8">
                <select name="attribute[{{$valAttr}}][]"
                 class="selectVariant variant-{{$valAttr}} form-control" 
                 id=""
                multiple data-catid="{{$valAttr}}"
                 ></select>
            </div>
            <div class="col-lg-1">
                <button type="button" class="btn btn-danger remove-attribute"><i class="fa fa-trash"></i></button>
            </div>
        </div>
        @endforeach
          @endif
           
         
          </div>
          
          <div class="variant-foot">
            <button type="button" class="add-variant">Thêm thuộc tính</button>
          </div>
      </div>
  
    </div>
  </div>
  <div class="rounded col-lg-9  mb-2 bg-white">
    <h3 class=" p-4">Danh sách phiên bản</h3>
    <div class="col-lg-12  table-responsive">
        <table class=" variantTable mb-2 table-hover ">
              <thead class="table-white"></thead>
              <tbody></tbody>
        </table>
    </div>
     
</div>

     

  
  
  <script>
    var attributeCatalogue = @json(
        $attributeCatalogue->map(function ($item) {
            $name = $item->attribute_catalogue_language->first()->name;
            return [
                'id' => $item->id,
                'name' => $name,
            ];
        })->values()
    );
    var attribute = '{{ base64_encode(json_encode(old('attribute', (isset($product->attribute)) 
                     ? $product->attribute
                     : []))) }}';


    var variant =  '{{ base64_encode(json_encode(old('variant', (isset($product->variant)) 
                     ? json_decode($product->variant, TRUE) 
                     : []))) }}';

 

</script>
