@php
 $title = str_replace('{language}', $language->name ,$config['seo']['translate']['title'].' '.$menuCatalogue->name)   
@endphp
@include('backend.dashboard.component.breadcumb', [
    'title' =>  $title
])
@include('backend.dashboard.component.formerror')
<form action="{{route('menu.translate.save',['languageId'=> $languageId])}}" method="post">
    @csrf
    <div class="mt-2 me-3" >
        <div class="d-flex">
            <div class="col-lg-3 p-4 me-3 rounded-start bg-white h-100">
                <div class="title fs-3 ">Thông tin chung            </div>
                <div class="description mt-2">
                    <p>+ Hệ thống lấy ra các bản dịch 
                        <span class="text-primary">nếu có</span>
                    </p>
                    <p>+ Cập nhật các thông tin về bản dịch cho các Menu của bạn bên phải <span class="text-primary">menu đến vị trí mong muốn</span></p>
                    <p>+ Lưu ý cập nhật đầy đủ thông tin <span class="text-primary">Quản lý Menu con</span></p>
                </div>
            </div>
            <div class="col-lg-9 p-4 rounded-end bg-white">
                <div class="ibox">
                    <div class="">
                        <div class="d-flex justify-content-between">
                            <h5>Danh sách bản dịch</h5>
                        </div>
                
                        <div class="ibox-content" id="dataCatalogue" >
                            @if (count($menus))
                            @foreach ($menus as $key => $val)
                             @php
                                 $name = $val->languages->first()->pivot->name;
                                 $canonical =$val->languages->first()->pivot->canonical
                             @endphp
                            <div class="menu-translate-item ps-{{$val->level}} d-flex mb-3">
                                <div class="col-lg-6">
                                    
                                    <div class="form-floating mb-1">
                                        <input class="form-control" 
                                        name="" 
                                        placeholder="" 
                                        value="{{$name}}" 
                                        type="" 
                                        disabled>
                                        <label class="ps-4">Tên Menu</label>
                                    </div>
                                    
                                    <div class="form-floating  mb-1">
                                        <input class="form-control " 
                                        name="" 
                                        placeholder="" 
                                        value="{{$canonical}}" 
                                        type="" 
                                        disabled>
                                        <label class="ps-4 text-dark">Đường dẫn</label>
                                        
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-floating  mb-1">
                                        <input class="form-control" 
                                        name="translate[name][]" 
                                        placeholder="" 
                                        value="{{ ($val->translate_name) ? $val->translate_name : ''}}" 
                                        type="" 
                                        min="0">
                                        <label class="ps-4">Bản dịch</label>
                                    </div>
                                    
                                    <div class="form-floating  mb-1">
                                        <input class="form-control" 
                                        name="translate[canonical][]" 
                                        placeholder="" 
                                        value="{{ ($val->translate_canonical) ? $val->translate_canonical : ''}}" 
                                        type="" 
                                        min="0">
                                        <label class="ps-4">Bản dịch</label>
                                        <input class="form-control" 
                                        name="translate[id][]" 
                                        value="{{ ($val->id) ? $val->id : ''}}" 
                                        type="hidden" 
                                       >
                                    </div>
                                </div>
                            </div>  
                            @endforeach
                            @endif
                           
                          
    
                        </div>
                </div>
            
        </div>
        <button type="submit" class="btn btn-primary float-right " name="send" value="send">{{__('messages.btnSave')}}</button>
    
    </div>
    
    </div>
    
    
</form>
