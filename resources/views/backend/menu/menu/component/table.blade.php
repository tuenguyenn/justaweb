<table class="table table-bordered table-hover" style="background-color:white">
    <thead>
        <tr>
            <th class="text-center">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th class="text-center">Tên</th>
            <th class="text-center">Từ khoá</th>
            <th class="text-center">Tình trạng</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($menuCatalogues) && is_object($menuCatalogues))
            @foreach($menuCatalogues as $menuCatalogue)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="" class="input-checkbox checkboxItem">
                    </td>
                    
                    <td class="text-center">{{$menuCatalogue->name}}</td>
                    <td class="text-center">{{$menuCatalogue->keyword}}</td>

                    <td class="text-center js-switch-{{$menuCatalogue->id}}">
                        <input type="checkbox" id="status" 
                            value="{{$menuCatalogue->publish}}" 
                            class="js-switch status" 
                            data-field="publish" 
                            data-modelId={{$menuCatalogue->id}} 
                            data-model="MenuCatalogue" {{($menuCatalogue->publish==2) ? 'checked': '' }}>
                    </td>
                    <td class="text-center">
                        <a href="{{route('menu.edit', $menuCatalogue->id )}}" class="btn btn-success"><i class="fa fa-edit"></i></a>

                        <button type="button" class="btn btn-danger delete-button" 
                            data-id="{{$menuCatalogue->id}}" data-name="{{$menuCatalogue->name}}" data-model="menu">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>


