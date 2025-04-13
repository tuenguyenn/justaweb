
@include('backend.dashboard.component.formerror')

<table class="table table-bordered">
    <thead>
        <tr>
            <th class="text-center">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th class="text-center">Danh mục</th>
            @include('backend.dashboard.component.languageTh')

            <th class="text-center " style="width:80px">Ưu tiên</th>

            <th class="text-center">Tình trạng</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($products) && is_object($products))
            @foreach($products as $product)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $product->id }}" class="input-checkbox checkboxItem">
                    </td>
                    <td class="text-left">
                        <div class="product-item d-flex">
                            <img src="{{$product->image}}" class="product-img img-fluid" alt="..." >
                    
                            <div class="product-info ms-2" >
                                <div class="meta-title text-dark">
                                    <h5>{{$product->name}}</h5>
                                </div>
                                    <div class="categories">
                                        <span class="text-danger">Danh mục:</span>
                                      
                                        @foreach($product->product_catalogues as $catalogue)

                                            @foreach($catalogue->product_catalogue_language as $val)
                                              @if ($val->language_id == $currentLanguage)
                                              <a href="{{route('product.index',['product_catalogue_id'=>$catalogue->id])}}" class="category-link">{{$val->name}}</a>

                                              @endif
                                            @endforeach
                                            @endforeach

                                    </div>

                            </div>
                        </div>
                    </td>
                    @include('backend.dashboard.component.languageTd',['model'=>$product ,'modeling'=>'Product'])

                    <td class="text-right"style="width:60px">
                        <input type="text" name="order" value="{{$product->order}}" class="form-control sort-order bg-white text-right" data-id="{{$product->id}}" data-model="Product">
                    </td>
                    
                    <td class="text-center js-switch-{{$product->id}}">
                        <input type="checkbox" id="status" 
                            value="{{$product->publish}}" 
                            class="js-switch status" 
                            data-field="publish" 
                            data-modelId={{$product->id}} 
                            data-model="Product" {{($product->publish==2) ? 'checked': '' }}>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger delete-button" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-model="product">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>   
            @endforeach
        @endif
    </tbody>
</table>

{{-- Pagination --}}
<div class="d-flex justify-content-center">
    {{$products->links('pagination::bootstrap-5')}}
</div>


