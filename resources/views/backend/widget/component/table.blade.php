<table class="table table-bordered table-hover" style="background-color:white !important">
    <thead>
        <tr>
            <th class="text-center">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th class="text-center">Tên Widget</th>
            <th class="text-center">Từ khoá</th>
            <th class="text-center">Short-code</th>
            @foreach($languages as $key => $val)
            @if(session('app_locale') == $val->canonical) 
            @continue;
            @endif
            <th class="text-center" ><span><img class="img-translate" src="{{$val->image}}" alt=""></span></th>

            @endforeach
           

            <th class="text-center">Trạng thái</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($widgets) && is_object($widgets))
            @foreach($widgets as $key => $widget)
                   
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $widget->id }}" class="input-checkbox checkboxItem">
                    </td>
                    
                    <td class="text-center">{{ $widget->name }}</td>
                    <td class="text-center">{{ $widget->keyword }}</td>
                   
                    <td class="text-center">{{ ($widget->short_code) ? $widget->short_code: '-' }}</td>
                    @foreach($languages as $language)
                        @if(session('app_locale') == $language->canonical) 
                            @continue;
                        @endif
                        @php
                           
                           $translated = isset($widget->description[$language->id]) && $widget->description[$language->id] ? 1 : 0;

                        @endphp
                        <td class="text-center" >
                          
                            <a class="{{$translated == 1 ? '' :'text-danger '}}"
                                href="{{route('widget.translate',['id'=> $widget->id, 'languageId'=>$language->id])}}">{{$translated == 1 ? 'Đã dịch' :'Chưa dịch '}} </a>
                        </td>

                        @endforeach
                    <td class="text-center js-switch-{{$widget->id}}">
                        <input type="checkbox" id="status" 
                            value="{{$widget->publish}}" 
                            class="js-switch status" 
                            data-field="publish" 
                            data-modelId={{$widget->id}} 
                            data-model="Widget" {{($widget->publish==2) ? 'checked': '' }}>
                    </td>
                    <td class="text-center">
                        <a href="{{route('widget.edit', $widget->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>

                        <button type="button" class="btn btn-danger delete-button" 
                            data-id="{{ $widget->id }}" data-name="{{ $widget->name }}" data-model="widget">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

<div class="pagination-wrapper">
    {{ $widgets->links('pagination::bootstrap-5') }}
</div>

