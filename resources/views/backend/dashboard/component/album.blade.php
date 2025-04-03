<div class="{{ (!isset($offTitle)) ? 'col-lg-9' : 'col-lg-12' }} rounded bg-white ">
    @if (!isset($offTitle))
    <div class="title d-flex justify-content-between align-items-center p-2">
        <h5 class="mainColor">ALBUM ẢNH</h5>
        <a href="" class="upload-picture">Chọn hình</a>
    </div>
    
    @endif


    @php
    
        $albumData = old('album', isset($album) ? $album : []);
       
    @endphp
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                @if(!isset($albumData) || count($albumData)==0)
                <div class="click-to-upload">
                    <div class="icon text-center">
                            <a href="" class="upload-picture">
                                <svg style="width:150px;height:150px" viewBox="0 0 48 48" id="a" xmlns="http://www.w3.org/2000/svg" fill="#000000">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier"><defs><style>.b{fill:none;stroke:#000000;stroke-linecap:round;stroke-linejoin:round;}</style>
                                    </defs><path class="b" d="M29.4995,12.3739c.7719-.0965,1.5437,.4824,1.5437,1.2543h0l2.5085,23.8312c.0965,.7719-.4824,1.5437-1.2543,1.5437l-23.7347,2.5085c-.7719,.0965-1.5437-.4824-1.5437-1.2543h0l-2.5085-23.7347c-.0965-.7719,.4824-1.5437,1.2543-1.5437l23.7347-2.605Z"></path><path class="b" d="M12.9045,18.9347c-1.7367,.193-3.0874,1.7367-2.8945,3.5699,.193,1.7367,1.7367,3.0874,3.5699,2.8945,1.7367-.193,3.0874-1.7367,2.8945-3.5699s-1.8332-3.0874-3.5699-2.8945h0Zm8.7799,5.596l-4.6312,5.6925c-.193,.193-.4824,.2894-.6754,.0965h0l-1.0613-.8683c-.193-.193-.5789-.0965-.6754,.0965l-5.0171,6.1749c-.193,.193-.193,.5789,.0965,.6754-.0965,.0965,.0965,.0965,.193,.0965l19.9719-2.1226c.2894,0,.4824-.2894,.4824-.5789,0-.0965-.0965-.193-.0965-.2894l-7.8151-9.0694c-.2894-.0965-.5789-.0965-.7719,.0965h0Z"></path><path class="b" d="M16.2814,13.8211l.6754-6.0784c.0965-.7719,.7719-1.3508,1.5437-1.2543l23.7347,2.5085c.7719,.0965,1.3508,.7719,1.2543,1.5437h0l-2.5085,23.7347c0,.6754-.7719,1.2543-1.5437,1.2543l-6.1749-.6754"></path><path class="b" d="M32.7799,29.9337l5.3065,.5789c.2894,0,.4824-.193,.5789-.4824,0-.0965,0-.193-.0965-.2894l-5.789-10.5166c-.0965-.193-.4824-.2894-.6754-.193h0l-.3859,.3859"></path></g></svg>
                            </a>
                        
                    </div>
                    <div class="small-text text-center text-muted">Click vào đây để upload ảnh</div>

                </div>
                @endif
                   
                        
                <div class="upload-list mt-4">
                    <ul id="sortable" class="d-flex flex-wrap gap-3 list-unstyled">
                     
                        
                        @if (!empty($albumData))
                            @foreach ($albumData as $key => $val)
                                <li class="ui-state-default border-0 ">
                                    <div class="thumb position-relative">
                                        <button class="delete-image position-absolute top-0 end-0 btn btn-danger btn-sm" type="button">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <span class="image-item img-scaledown">
                                            <img src="{{ $val }}" alt="{{ $val }}" class="list-slide-image">
                                            <input type="hidden" name="album[]" value="{{ $val }}">
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                
                    

                    
            </div>
        </div>
    </div>
</div>
  



