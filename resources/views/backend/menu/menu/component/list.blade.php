
    <div class="d-flex me-3 ">
        <div class="col-lg-4 mt-3  me-3">
            <div class="accordion" id="newAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header " id="headingOne">
                        <button class="accordion-button " type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                           <strong> LIÊN KẾT TỰ TẠO</strong>
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse " aria-labelledby="headingOne" data-bs-parent="#newAccordion" >
                        <div class="accordion-body">
                            <div class="accordion-title"><strong>Tạo MENU</strong></div>
                            <small><a href="" class="btn btn-default add-menu border m-3">Thêm đường dẫn</a></small>
                        </div>
                    </div>
                </div>
                @foreach (__('module.model') as $key =>$val)

                <div class="accordion-item">
                    <h2 class="accordion-header" id="{{$key.'Heading'}}">
                        <a class="accordion-button collapsed menu-module" type="button" data-bs-toggle="collapse" data-model={{$key}} data-bs-target="#{{$key}}" aria-expanded="false" aria-controls="collapseTwo">
                            <strong class="text-uppercase"> {{$val}}</strong>
                        </a>
                    </h2>
                    <div id="{{$key}}" class="accordion-collapse collapse" aria-labelledby="{{$key.'Heading'}}" data-bs-parent="#newAccordion">
                        <div class="accordion-body">
                            <form action="" method="get" data-model={{$key}} class="search-model"> 
                                <div class="form-group">
                                    <input type="text"
                                            value=""
                                            class="form-control search-menu"
                                            name="keyword"
                                            placeholder="Nhập 2 kí tự để tìm kiếm">
                                </div>
                                <div class="menu-list">
                                  
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach

               
            </div>
        </div>
        @php
            $menu =   old('menu',($menuList) ?? null)
        @endphp
        <div class="mt-3 col-lg-8 bg-white  border rounded h-100 ">
            <div class="border-bottom pt-3">
                <p class="fs-4">Thêm đường dẫn</p>
            </div>
            <div class="menu-wrapper p-4">
                <div class="notification text-center {{(is_array($menu) && count($menu)) ? 'd-none' : '' }} ">
                    <h4 class="">Danh sách này chưa có đường liên kết nào</h4>
                    <small >Hãy nhấn vào <span class="text-primary">"Thêm đường dẫn"</span> để bắt đầu thêm</small>
                </div>
              
                @if(is_array($menu) && count($menu))
                @foreach ($menu['name'] as $key =>$val)
                <div class="d-flex pt-2 menu-item {{$menu['canonical'][$key]}}">
                    <div class="form-floating col-lg-4 mb-3">
                        <input class="form-control" 
                        name="menu[name][]" 
                        placeholder="" 
                        value="{{$val}}"
                         type="text">
                         <label class="ps-4">Tên Menu</label>
                    </div>
                    <div class="form-floating col-lg-4 mb-3">
                        <input class="form-control" 
                        name="menu[canonical][]"
                         placeholder="" 
                         value="{{$menu['canonical'][$key]}}" 
                         type="text">
                        <label class="ps-4">Đường dẫn</label>
                    </div>
                    <div class="form-floating col-lg-2 mb-3">
                        <input class="form-control" 
                        name="menu[order][]" 
                        placeholder="" 
                        value="{{$menu['order'][$key]}}" 
                        type="number" 
                        min="0">
                    <label class="ps-4">Vị trí</label>
                </div>
                <div class="col-lg-2 mt-2">
                    <a class="delete-menu text-right">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" color="black" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"></path>
                 </svg></a>
                 <input type="text" class="d-none" name="menu[id][]" value="{{$menu['id'][$key]}}">
                </div>
            </div>
                @endforeach
            @endif  
            </div>   
           
        </div>
        
    </div>



