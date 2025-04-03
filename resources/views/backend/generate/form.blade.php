

@include('backend.dashboard.component.breadcumb', [
    'title' => ($config['method'] == 'edit' ? $config['seo']['update']['title'] : $config['seo']['create']['title'])
])
@php
    $url = ($config['method']== 'create' ? route('generate.store'):route('generate.update',$generate->id))
@endphp
    @include('backend.dashboard.component.formerror')
        
    <form  action="{{$url}}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Name -->
        <div class="container bg-white border rounded mt-3 p-3 col-lg-8 float-left">

            <div class="d-flex gap-4">
                <div class="mb-3">
                    <label for="name" class="form-label form-label-lg"><strong>Tên MODEL</strong> <span class="text-danger">(*)</span></label>
                    <input 
                        type="text" 
                        class="form-control name" 
                        id="name" 
                        name="name" 
                        value="{{old('name',($generate->name) ?? '')}}"
                    >
                    <span class="fst-italic text-danger">Ví dụ: ProductCatalogue</span>
                </div>
                <div class="mb-3">
                    <label for="module" class="form-label form-label-lg"><strong>Tên chức năng</strong> <span class="text-danger">(*)</span></label>
                    <input 
                        type="text" 
                        class="form-control module" 
                        id="module" 
                        name="module" 
                        value="{{old('module',($generate->name) ?? '')}}"
                    >
                </div>
                <div class="mb-3">
                    <label for="" class="form-label form-label-lg"><strong>Loại</strong> <span class="text-danger">(*)</span></label>
                    <select class="form-select" id="" name="module_type">
                        <option value="0" >Chọn loại Module</option>
                        <option value="catalogue">Module danh mục</option>
                        <option value="detail" >Module chi tiết </option>
                        <option value="other" >Module Khác</option>

                    </select>
                </div>
                <div class="mb-3">
                    <label for="link" class="form-label form-label-lg"><strong>Đường dẫn</strong> <span class="text-danger">(*)</span></label>
                    <input 
                        type="text" 
                        class="form-control link" 
                        id="link" 
                        name="link" 
                        value="{{old('link',($generate->name) ?? '')}}"
                    >
                </div>
              
            </div>
            <div class=" mb-3 ">
                <div data-mdb-input-init class="form-outline">
                <label class="form-label" for="form3Examplev3"><strong class="text-dark"> {{ __('messages.generate.schema') }} <span class="text-danger">(*)</span></strong></label>
                <textarea 
                    class="shema-textarea ck-editor form-control form-control-lg" 
                    id="schema" 
                    name="schema" 
                    data-height="300"
                
                >{{ old('schema', $generate->schema ?? '') }}</textarea>
                </div>
            </div>
            <div class=" mb-3 ">
                <div data-mdb-input-init class="form-outline">
                <label class="form-label" for="form3Examplev3"><strong class="text-dark"> {{ __('messages.generate.schema') }} 2 <span class="text-danger">(*)</span></strong></label>
                <textarea 
                    class="shema-textarea ck-editor form-control form-control-lg" 
                    id="schema_option" 
                    name="schema_option" 
                    data-height="300"
                
                >{{ old('schema', $generate->schema ?? '') }}</textarea>
                </div>
            </div>
        <button type="submit" class="btn btn-primary " name="send" value="send">Register</button>
    </div>

    </form>




