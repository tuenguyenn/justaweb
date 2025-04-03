
@include('backend.dashboard.component.formerror')

<table class="table table-bordered">
    <thead>
        <tr>
            <th class="text-center">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th class="text-center">Danh mục</th>
            @include('backend.dashboard.component.languageTh')

            <th class="text-center " style="width:80px">Ưu tiên</th>

            <th class="text-center">Tình trạng</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($posts) && is_object($posts))
            @foreach($posts as $post)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $post->id }}" class="input-checkbox checkboxItem">
                    </td>
                    <td class="text-left">
                        <div class="post-item">
                            <img src="{{$post->image}}" class="thumbnail img-fluid" alt="..." >
                    
                            <div class="post-info ms-2" >
                                <div class="meta-title text-dark">
                                    <h5>{{$post->meta_title}}</h5>
                                </div>
                                    <div class="categories">
                                        <span class="text-danger">Danh mục:</span>
                                      
                                        @foreach($post->post_catalogues as $catalogue)

                                            @foreach($catalogue->post_catalogue_language as $val)
                                              @if ($val->language_id == $currentLanguage)
                                              <a href="{{route('post.index',['post_catalogue_id'=>$catalogue->id])}}" class="category-link">{{$val->name}}</a>

                                              @endif
                                            @endforeach
                                            @endforeach

                                    </div>

                            </div>
                        </div>
                    </td>
                    @include('backend.dashboard.component.languageTd',['model'=>$post ,'modeling'=>'Post'])

                    <td class="text-right"style="width:60px">
                        <input type="text" name="order" value="{{$post->order}}" class="form-control sort-order bg-white text-right" data-id="{{$post->id}}" data-model="Post">
                    </td>
                    
                    <td class="text-center js-switch-{{$post->id}}">
                        <input type="checkbox" id="status" 
                            value="{{$post->publish}}" 
                            class="js-switch status" 
                            data-field="publish" 
                            data-modelId={{$post->id}} 
                            data-model="Post" {{($post->publish==2) ? 'checked': '' }}>
                    </td>
                    <td class="text-center">
                        <a href="{{route('post.edit', $post->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <button type="button" class="btn btn-danger delete-button" data-id="{{ $post->id }}" data-name="{{ $post->name }}" data-model="post"  >
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
    {{$posts->links('pagination::bootstrap-5')}}
</div>


