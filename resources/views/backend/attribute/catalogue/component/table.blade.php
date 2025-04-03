@include('backend.dashboard.component.formerror')

<table class="table table-bordered">
    <thead>
        <tr>
            <th class="text-center">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th class="text-center col-md-6" >{{__('messages.attributeCatalogue.index.title')}}</th>
            @include('backend.dashboard.component.languageTh')

               
            <th class="text-center">{{__('messages.tableStatus')}}</th>
            <th class="text-center">{{__('messages.tableAction')}}</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($attributeCatalogues) && is_object($attributeCatalogues))
            @foreach($attributeCatalogues as $attributeCatalogue)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $attributeCatalogue->id }}" class="input-checkbox checkboxItem">
                    </td>
                    <td class="text-left">
                        {{ str_repeat('|----',(($attributeCatalogue->level>0)?($attributeCatalogue->level-1):0)).$attributeCatalogue->name}}
                    </td>
                   
                    @include('backend.dashboard.component.languageTd',['model'=>$attributeCatalogue ,'modeling'=>'AttributeCatalogue'])

                    <td class="text-center js-switch-{{$attributeCatalogue->id}}">
                        <input type="checkbox" id="status" 
                            value="{{$attributeCatalogue->publish}}" 
                            class="js-switch status" 
                            data-field="publish" 
                            data-modelId={{$attributeCatalogue->id}} 
                            data-model="AttributeCatalogue" {{($attributeCatalogue->publish==2) ? 'checked': '' }}>
                    </td>
                    <td class="text-center">
                        <a href="{{route('attribute.catalogue.edit', $attributeCatalogue->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <button type="button" class="btn btn-danger delete-button" data-id="{{ $attributeCatalogue->id }}" data-name="{{ $attributeCatalogue->name }}" data-model="attribute/catalogue"  >
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
    {{$attributeCatalogues->links('pagination::bootstrap-5')}}
</div>

@include('backend.dashboard.component.footer')




