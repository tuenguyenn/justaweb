
@include('backend.dashboard.component.breadcumb', [
    'title' => ($config['method'] == 'edit' ? $config['seo']['update']['title'] : $config['seo']['create']['title'])
])
@php
    $url = ($config['method']== 'create' ? route('promotion.store'):route('promotion.update',$promotion->id))
@endphp
   

    <form action="{{ $url }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('backend.dashboard.component.formerror')
        <div class="d-flex mt-3 ">
         
            <div class="col-lg-8">
              @include('backend.promotion.component.content',['model'=>($promotion)  ?? null ])

              @include('backend.promotion.promotion.component.detail',['model'=>($promotion)  ?? null ])

            </div>
                          
            <div class="col-lg-4">
              @include('backend.promotion.component.aside', ['model'=>($promotion)  ?? null ])
              
            </div>
            <!-- Promotion Details -->
           
          </div>
       <button type="submit" class="btn btn-primary float-right mt-2">Lưu</button>

       @include('backend.promotion.promotion.component.popup')

       


    </form>
    
     
    <input type="hidden" class="preload_promotionMethod" value="{{old('method' , ($promotion->method) ?? null )}}"> 
    <input type="hidden" class="preload_select-product-and-quantity" value="{{old('module_type',($promotion->discountInformation['info']['model']) ?? null )}}"> 
    <input type="hidden" class="input_order_amount_range" value="{{json_encode(old('promotion_order_amount_range' , ($promotion->discountInformation['info']) ?? [] ))}}"> 
    <input type="hidden" class="input_product_and_quantity" 
    value="{{ json_encode(old('product_and_quantity', $promotion->discountInformation['info'] ?? [])) }}">
    <input type="hidden" class="input_object" 
    value="{{ json_encode(old('object', $promotion->discountInformation['info']['object'] ?? [])) }}">

  