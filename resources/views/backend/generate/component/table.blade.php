<table class="table table-bordered">
    <thead>
        <tr>
            <th class="text-center">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th class="text-center" >Tên module</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($generates) && is_object($generates))
            @foreach($generates as $generate)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $generate->id }}" class="input-checkbox checkboxItem">
                    </td>
                        
                    <td class="text-left">
                        <div class="d-flex align-items-center ">
                            
                            <div class="name">
                                {{$generate->name}} 
                            </div>
                        </div>
                    </td>
                    
                    
                    <td class="text-center">
                        <a href="{{route('generate.edit', $generate->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <button type="button" class="btn btn-danger delete-button" data-id="{{ $generate->id }}" data-name="{{ $generate->name }}" data-model="generate"  >
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>   
            @endforeach
        @endif
    </tbody>
</table>

{{-- Pagination --}}


@include('backend.dashboard.component.footer')

