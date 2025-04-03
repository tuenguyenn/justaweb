<table class="table table-bordered table-hover" style="background-color:white !important">
    <thead>
        <tr>
            <th class="text-center">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th class="text-center">Tên nhóm</th>
            <th class="text-center">Từ khoá</th>
            <th class="text-center">Danh sách hình ảnh</th>

            <th class="text-center">Trạng thái</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($slides) && is_object($slides))
            @foreach($slides as $key => $slide)
                   
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $slide->id }}" class="input-checkbox checkboxItem">
                    </td>
                    
                    <td class="text-center">{{ $slide->name }}</td>
                    <td class="text-center">{{ $slide->keyword }}</td>

                    <td>
                        <div id="sortable" class="image-gallery sortui ui-sortable">
                            @for ($i = 0; $i < count($slide->item[1]); $i++)
                                <div class="image-item " data-id="{{ $i }}">
                                    <img 
                                        class="list-slide-image" 
                                        src="{{ $slide->item[1][$i]['image'] }}" 
                                        alt="Image {{ $i + 1 }}">
                                </div>
                            @endfor
                        </div>
                    </td>
                    
                    
                    <td class="text-center js-switch-{{$slide->id}}">
                        <input type="checkbox" id="status" 
                            value="{{$slide->publish}}" 
                            class="js-switch status" 
                            data-field="publish" 
                            data-modelId={{$slide->id}} 
                            data-model="Slide" {{($slide->publish==2) ? 'checked': '' }}>
                    </td>
                    <td class="text-center">
                        <a href="{{route('slide.edit', $slide->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>

                        <button type="button" class="btn btn-danger delete-button" 
                            data-id="{{ $slide->id }}" data-name="{{ $slide->name }}" data-model="slide">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

<div class="pagination-wrapper">
    {{ $slides->links('pagination::bootstrap-5') }}
</div>

