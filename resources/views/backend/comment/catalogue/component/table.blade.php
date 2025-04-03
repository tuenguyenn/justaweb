@include('backend.dashboard.component.formerror')

<table class="table table-bordered">
    <thead>
        <tr>
            <th class="text-center">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th class="text-center col-md-6" >{{__('messages.commentCatalogue.index.title')}}</th>
            @include('backend.dashboard.component.languageTh')

               
            <th class="text-center">{{__('messages.tableStatus')}}</th>
            <th class="text-center">{{__('messages.tableAction')}}</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($commentCatalogues) && is_object($commentCatalogues))
            @foreach($commentCatalogues as $commentCatalogue)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $commentCatalogue->id }}" class="input-checkbox checkboxItem">
                    </td>
                    <td class="text-left">
                        {{ str_repeat('|----',(($commentCatalogue->level>0)?($commentCatalogue->level-1):0)).$commentCatalogue->name}}
                    </td>
                   
                    @include('backend.dashboard.component.languageTd',['model'=>$commentCatalogue ,'modeling'=>'CommentCatalogue'])

                    <td class="text-center js-switch-{{$commentCatalogue->id}}">
                        <input type="checkbox" id="status" 
                            value="{{$commentCatalogue->publish}}" 
                            class="js-switch status" 
                            data-field="publish" 
                            data-modelId={{$commentCatalogue->id}} 
                            data-model="CommentCatalogue" {{($commentCatalogue->publish==2) ? 'checked': '' }}>
                    </td>
                    <td class="text-center">
                        <a href="{{route('comment.catalogue.edit', $commentCatalogue->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <button type="button" class="btn btn-danger delete-button" data-id="{{ $commentCatalogue->id }}" data-name="{{ $commentCatalogue->name }}" data-model="comment/catalogue"  >
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
    {{$commentCatalogues->links('pagination::bootstrap-5')}}
</div>

@include('backend.dashboard.component.footer')




