@include('backend.dashboard.component.formerror')

<table class="table table-bordered">
    <thead>
        <tr>
            <th class="text-center">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th class="text-center col-md-6" >{{__('messages.productCatalogue.index.title')}}</th>
            @include('backend.dashboard.component.languageTh')

               
            <th class="text-center">{{__('messages.tableStatus')}}</th>
            <th class="text-center">{{__('messages.tableAction')}}</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($productCatalogues) && is_object($productCatalogues))
            @foreach($productCatalogues as $productCatalogue)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $productCatalogue->id }}" class="input-checkbox checkboxItem">
                    </td>
                    <td class="text-left">
                        {{ str_repeat('|----',(($productCatalogue->level>0)?($productCatalogue->level-1):0)).$productCatalogue->name}}
                    </td>
                   
                    @include('backend.dashboard.component.languageTd',['model'=>$productCatalogue ,'modeling'=>'ProductCatalogue'])

                    <td class="text-center js-switch-{{$productCatalogue->id}}">
                        <input type="checkbox" id="status" 
                            value="{{$productCatalogue->publish}}" 
                            class="js-switch status" 
                            data-field="publish" 
                            data-modelId={{$productCatalogue->id}} 
                            data-model="ProductCatalogue" {{($productCatalogue->publish==2) ? 'checked': '' }}>
                    </td>
                    <td class="text-center">
                        <a href="{{route('product.catalogue.edit', $productCatalogue->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <button type="button" class="btn btn-danger delete-button" data-id="{{ $productCatalogue->id }}" data-name="{{ $productCatalogue->name }}" data-model="product/catalogue"  >
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
    {{$productCatalogues->links('pagination::bootstrap-5')}}
</div>

@include('backend.dashboard.component.footer')




