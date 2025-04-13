
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
        @if(isset($attributes) && is_object($attributes))
            @foreach($attributes as $attribute)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $attribute->id }}" class="input-checkbox checkboxItem">
                    </td>
                    <td class="text-left">
                        <div class="attribute-item">
                            <img src="{{$attribute->image}}" class="thumbnail img-fluid" alt="..." >
                    
                            <div class="attribute-info ms-2" >
                                <div class="meta-title text-dark">
                                    <h5>{{$attribute->name}}</h5>
                                </div>
                                    <div class="categories">
                                        <span class="text-danger">Danh mục:</span>
                                      
                                        @foreach($attribute->attribute_catalogues as $catalogue)

                                            @foreach($catalogue->attribute_catalogue_language as $val)
                                              @if ($val->language_id == $currentLanguage)
                                              <a href="{{route('attribute.index',['attribute_catalogue_id'=>$catalogue->id])}}" class="category-link">{{$val->name}}</a>

                                              @endif
                                            @endforeach
                                            @endforeach

                                    </div>

                            </div>
                        </div>
                    </td>
                    @include('backend.dashboard.component.languageTd',['model'=>$attribute ,'modeling'=>'Attribute'])

                    <td class="text-right"style="width:60px">
                        <input type="text" name="order" value="{{$attribute->order}}" class="form-control sort-order bg-white text-right" data-id="{{$attribute->id}}" data-model="Attribute">
                    </td>
                    
                    <td class="text-center js-switch-{{$attribute->id}}">
                        <input type="checkbox" id="status" 
                            value="{{$attribute->publish}}" 
                            class="js-switch status" 
                            data-field="publish" 
                            data-modelId={{$attribute->id}} 
                            data-model="Attribute" {{($attribute->publish==2) ? 'checked': '' }}>
                    </td>
                    <td class="text-center">
                        <a href="{{route('attribute.edit', $attribute->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <button type="button" class="btn btn-danger delete-button" data-id="{{ $attribute->id }}" data-name="{{ $attribute->name }}" data-model="attribute"  >
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
    {{$attributes->links('pagination::bootstrap-5')}}
</div>


