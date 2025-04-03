
@include('backend.dashboard.component.breadcumb', [
    'title' =>   $config['seo']['update']['title']
])

@include('backend.dashboard.component.formerror')
<div class="mt-2 me-3" >
    <div class="d-flex">
        <div class="col-lg-3 p-4 me-3 rounded-start bg-white">
            <div class="title fs-3 ">
                Danh sách Menu
                
                @foreach($languages as $language)
                @php
                    $url = (session('app_locale') == $language->canonical) ? route('menu.edit',['id'=>$id]) 
                                          : route('menu.translate',['languageId'=> $language->id , 'id' =>$id])
                 @endphp
                <a href="{{ $url}}" class="float-right pe-1" ><span><img class="img-translate" src="{{$language->image}}" alt=""></span></â>

                @endforeach
            </div>
            <div class="description mt-2">
                <p>+MENU giúp bạn kiểm soát bố cục. Bạn có thể them mới hoặc cập nhật bằng nút
                    <span class="text-primary">Cập nhật MENU</span>
                </p>
                <p>+Thay đổi vị trí hiển thị menu bằng cách <span class="text-primary">menu đến vị trí mong muốn</span></p>
                <p>+Tạo mới MENU con bằng cách nhấn vào <span class="text-primary">Quản lý Menu con</span></p>
            </div>
        </div>
        <div class="col-lg-9 p-4 rounded-end bg-white">
            <div class="ibox">
                <div class="">
                    <div class="d-flex justify-content-between">
                        <h5>{{$menuCatalogue->name}}</h5>
                        <a href="{{route('menu.editMenu', $id)}}" class="custom-button ">Cập nhật menu</a>
                    </div>
            
                    <div class="ibox-content" id="dataCatalogue" data-catalogueId={{$id}}>
                        @if (count($menus))

                        <div class="dd" id="nestable2">
                            <ol class="dd-list">
                                {!!$menuString!!}

                            </ol>
                        </div>
                    </div>

                     @endif
                </div>
            </div>
        
    </div>
</div>
</div>





