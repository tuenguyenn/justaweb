@include('backend.dashboard.component.formerror')

<table class="table table-bordered">
    <thead>
        <tr>
            <th class="text-center">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th class="text-center col-md-6" >{{__('messages.{module}.index.title')}}</th>
            @include('backend.dashboard.component.languageTh')

               
            <th class="text-center">{{__('messages.tableStatus')}}</th>
            <th class="text-center">{{__('messages.tableAction')}}</th>
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
                        {{ str_repeat('|----',((${module}->level>0)?(${module}->level-1):0)).${module}->name}}
                    </td>
                   
                    @include('backend.dashboard.component.languageTd',['model'=>${module} ,'modeling'=>'{Module}'])

                    <td class="text-center js-switch-{{${module}->id}}">
                        <input type="checkbox" id="status" 
                            value="{{${module}->publish}}" 
                            class="js-switch status" 
                            data-field="publish" 
                            data-modelId={{${module}->id}} 
                            data-model="{Module}" {{(${module}->publish==2) ? 'checked': '' }}>
                    </td>
                    <td class="text-center">
                        <a href="{{route('{view}.edit', ${module}->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <button type="button" class="btn btn-danger delete-button" data-id="{{ ${module}->id }}" data-name="{{ ${module}->name }}" data-model="{link}"  >
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

@include('backend.dashboard.component.footer')




