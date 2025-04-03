<table class="table table-bordered">
    <thead>
        <tr>
            <th class="text-center">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th class="text-center">Họ Tên</th>
            <th class="text-center">Mô tả</th>            
            <th class="text-center">Tình trạng</th>
            <th class="text-center">Thao tác</th>

         
        </tr>
    </thead>
    <tbody>
        @if(isset($userCatalogues) && is_object($userCatalogues))
            @foreach($userCatalogues as $userCatalogue)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $userCatalogue->id }}" class="input-checkbox checkboxItem">
                        
                    </td>
                    <td class="text-center">
                        <div class="user-item name">{{$userCatalogue->name}} (<strong>{{$userCatalogue->users_count}}</strong>)</div>
                    </td>
                    <td class="text-center">
                        <div class="user-item description">{{$userCatalogue->description}}</div>
                    </td>
                    
                  
                    <td class="text-center js-switch-{{$userCatalogue->id}}">
                        <input type="checkbox" id="status" 
                            value="{{$userCatalogue->publish}}" 
                            class="js-switch status " 
                            data-field="publish" 
                            data-modelId={{$userCatalogue->id}} 
                            data-model="UserCatalogue" {{($userCatalogue->publish==2) ? 'checked': '' }}>
                    </td>
                    <td class="text-center">
                        <a href="{{route('user.catalogue.edit', $userCatalogue->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <button type="button" class="btn btn-danger delete-button" 
                        data-id="{{ $userCatalogue->id }}" data-name="{{ $userCatalogue->name }}" data-model="user/catalogue">
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
    {{$userCatalogues->links('pagination::bootstrap-5')}}
</div>

   
