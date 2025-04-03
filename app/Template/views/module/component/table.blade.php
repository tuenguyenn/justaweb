
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
        @if(isset(${module}s) && is_object(${module}s))
            @foreach(${module}s as ${module})
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ ${module}->id }}" class="input-checkbox checkboxItem">
                    </td>
                    <td class="text-left">
                        <div class="{module}-item">
                            <img src="{{${module}->image}}" class="thumbnail img-fluid" alt="..." >
                    
                            <div class="{module}-info ms-2" >
                                <div class="meta-title text-dark">
                                    <h5>{{${module}->meta_title}}</h5>
                                </div>
                                    <div class="categories">
                                        <span class="text-danger">Danh mục:</span>
                                      
                                        @foreach(${module}->{module}_catalogues as $catalogue)

                                            @foreach($catalogue->{module}_catalogue_language as $val)
                                              @if ($val->language_id == $currentLanguage)
                                              <a href="{{route('{module}.index',['{module}_catalogue_id'=>$catalogue->id])}}" class="category-link">{{$val->name}}</a>

                                              @endif
                                            @endforeach
                                            @endforeach

                                    </div>

                            </div>
                        </div>
                    </td>
                    @include('backend.dashboard.component.languageTd',['model'=>${module} ,'modeling'=>'{Module}'])

                    <td class="text-right"style="width:60px">
                        <input type="text" name="order" value="{{${module}->order}}" class="form-control sort-order bg-white text-right" data-id="{{${module}->id}}" data-model="{Module}">
                    </td>
                    
                    <td class="text-center js-switch-{{${module}->id}}">
                        <input type="checkbox" id="status" 
                            value="{{${module}->publish}}" 
                            class="js-switch status" 
                            data-field="publish" 
                            data-modelId={{${module}->id}} 
                            data-model="{Module}" {{(${module}->publish==2) ? 'checked': '' }}>
                    </td>
                    <td class="text-center">
                        <a href="{{route('{module}.edit', ${module}->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <button type="button" class="btn btn-danger delete-button" data-id="{{ ${module}->id }}" data-name="{{ ${module}->name }}" data-model="{module}"  >
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
    {{${module}s->links('pagination::bootstrap-5')}}
</div>


