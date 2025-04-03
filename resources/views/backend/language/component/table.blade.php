<table class="table table-bordered">
    <thead>
        <tr>
            <th class="text-center">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th class="text-center" >Ngôn ngữ</th>
            <th class="text-center">Tình trạng</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($languages) && is_object($languages))
            @foreach($languages as $language)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $language->id }}" class="input-checkbox checkboxItem">
                    </td>
                        
                    <td class="text-left">
                        <div class="d-flex align-items-center ">
                            <span class="me-2">
                                <img src="{{$language->image}}" alt="" class="language-item">
                            </span>
                            <div class="name">
                                {{$language->name}} ( <strong>{{$language->canonical}} </strong>)
                            </div>
                        </div>
                    </td>
                    
                    <td class="text-center js-switch-{{$language->id}}">
                        <input type="checkbox" id="status" 
                            value="{{$language->publish}}" 
                            class="js-switch status" 
                            data-field="publish" 
                            data-modelId={{$language->id}} 
                            data-model="Language" {{($language->publish==2) ? 'checked': '' }}>
                    </td>
                    <td class="text-center">
                        <a href="{{route('language.edit', $language->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <button type="button" class="btn btn-danger delete-button" data-id="{{ $language->id }}" data-name="{{ $language->name }}" data-model="languages"  >
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

