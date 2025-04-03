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
        @if(isset($customerCatalogues) && is_object($customerCatalogues))
            @foreach($customerCatalogues as $customerCatalogue)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $customerCatalogue->id }}" class="input-checkbox checkboxItem">
                        
                    </td>
                    <td class="text-center">
                        <div class="customer-item name">{{$customerCatalogue->name}} (<strong>{{$customerCatalogue->customers_count}}</strong>)</div>
                    </td>
                    <td class="text-center">
                        <div class="customer-item description">{{$customerCatalogue->description}}</div>
                    </td>
                    
                  
                    <td class="text-center js-switch-{{$customerCatalogue->id}}">
                        <input type="checkbox" id="status" 
                            value="{{$customerCatalogue->publish}}" 
                            class="js-switch status " 
                            data-field="publish"    
                            data-modelId={{$customerCatalogue->id}} 
                            data-model="CustomerCatalogue" {{($customerCatalogue->publish==2) ? 'checked': '' }}>
                    </td>
                    <td class="text-center">
                        <a href="{{route('customer.catalogue.edit', $customerCatalogue->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                       
                        <button type="button" class="btn btn-danger delete-button" 
                            data-id="{{ $customerCatalogue->id }}" data-name="{{ $customerCatalogue->name }}" data-model="customer/catalogue">
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
    {{$customerCatalogues->links('pagination::bootstrap-5')}}
</div>

   
