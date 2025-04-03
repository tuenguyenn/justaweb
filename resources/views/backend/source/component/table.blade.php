<table class="table table-bordered table-hover" style="background-color:white !important">
    <thead>
        <tr>
            <th class="text-center">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th class="text-center">Tên Nguồn Khách</th>
            <th class="text-center">Từ khoá</th>
            <th class="text-center">Mô tả</th>
          

    
           

            <th class="text-center">Trạng thái</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($sources) && is_object($sources))
            @foreach($sources as $key => $source)
                   
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $source->id }}" class="input-checkbox checkboxItem">
                    </td>
                    
                    <td class="text-center">{{ $source->name }}</td>
                    <td class="text-center">{{ $source->keyword }}</td>
                   
                    <td class="text-center">{{ ($source->description) ? strip_tags(html_entity_decode( $source->description)) : '-' }}</td>
                   
                    <td class="text-center js-switch-{{$source->id}}">
                        <input type="checkbox" id="status" 
                            value="{{$source->publish}}" 
                            class="js-switch status" 
                            data-field="publish" 
                            data-modelId={{$source->id}} 
                            data-model="Source" {{($source->publish==2) ? 'checked': '' }}>
                    </td>
                    <td class="text-center">
                        <a href="{{route('source.edit', $source->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>

                        <button type="button" class="btn btn-danger delete-button" 
                            data-id="{{ $source->id }}" data-name="{{ $source->name }}" data-model="source">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

<div class="pagination-wrapper">
    {{ $sources->links('pagination::bootstrap-5') }}
</div>

