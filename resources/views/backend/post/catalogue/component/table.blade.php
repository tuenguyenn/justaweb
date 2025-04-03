@include('backend.dashboard.component.formerror')

 
<table class="table table-bordered">
    <thead>
        <tr>
            <th class="text-center">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th class="text-center col-md-6" >{{__('messages.postCatalogue.index.title')}}</th>
            @include('backend.dashboard.component.languageTh')

               
            <th class="text-center">{{__('messages.tableStatus')}}</th>
            <th class="text-center">{{__('messages.tableAction')}}</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($postCatalogues) && is_object($postCatalogues))
            @foreach($postCatalogues as $postCatalogue)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $postCatalogue->id }}" class="input-checkbox checkboxItem">
                    </td>
                    <td class="text-left">
                        {{ str_repeat('|----',(($postCatalogue->level>0)?($postCatalogue->level-1):0)).$postCatalogue->name}}
                    </td>
                   
                    @include('backend.dashboard.component.languageTd',['model'=>$postCatalogue ,'modeling'=>'PostCatalogue'])

                    <td class="text-center js-switch-{{$postCatalogue->id}}">
                        <input type="checkbox" id="status" 
                            value="{{$postCatalogue->publish}}" 
                            class="js-switch status" 
                            data-field="publish" 
                            data-modelId={{$postCatalogue->id}} 
                            data-model="PostCatalogue" {{($postCatalogue->publish==2) ? 'checked': '' }}>
                    </td>
                    <td class="text-center">
                        <a href="{{route('post.catalogue.edit', $postCatalogue->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <button type="button" class="btn btn-danger delete-button" data-id="{{ $postCatalogue->id }}" data-name="{{ $postCatalogue->name }}" data-model="post/catalogue"  >
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
    {{$postCatalogues->links('pagination::bootstrap-5')}}
</div>

@include('backend.dashboard.component.footer')




