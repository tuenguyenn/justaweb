<a href="" class="add-slide float-end">Thêm SLIDE</a>
            <h5 class="mb-3 mainColor">DANH SÁCH SLIDE</h5>
            <div class="ibox-content">
                <div class="slide-notification  ">
                    <p > Chưa có ảnh nào được chọn </p>
                    <p>Hãy nhấn <small class=" text-primary">"Thêm SLIDE "</small> để chọn ảnh</p>
                </div>
                @php
                    $slides = old('slide',(($slideItem) ?? null));
                    $count =1;
                @endphp
                <div id="sortable" class="slide-list sortui ui-sortable">
                    @if (isset($slides)  && is_array($slides) )
                    @foreach ($slides['image'] as $key =>$val)
                        @php
                           
                            $image = $val;
                            $desc= $slides['description'][$key];
                            $canonical = $slides['canonical'][$key];
                            $name = $slides['name'][$key];
                            $alt = $slides['alt'][$key];
                            $window = (isset($slides['window'][$key])   )  ? $slides['window'][$key]  : '';                           



                        @endphp
                    <div class="col-lg-12 p-2 bg-white border-0 ui-state-default">
                        <div class="slide-item mb-3 d-flex">
                            <div class="col-lg-3 text-center border">
                                <span class="slide-image">
                                    <img class="img-fluid" src="{{$val}}" alt="Banner Image">
                                    <input type="hidden" name="slide[image][]" id="" value ="{{$val}}">
                                    <button class="delete-slide position-absolute top-0 end-0 btn btn-danger btn-sm" type="button">
                                    <i class="fa fa-trash"></i></button>
                                </span>
                            </div>
                            <div class="col-lg-9">
                                <div class="tabs-container">
                                    <ul class="custom-tabs  nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <button class="nav-link active" id="{{$count}}-tab" data-bs-toggle="tab" data-bs-target="#{{$count}}" type="button" role="tab" aria-controls="{{$count}}" aria-selected="true">
                                                Thông tin chung
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" id="{{$count+1}}-tab" data-bs-toggle="tab" data-bs-target="#{{$count+1}}" type="button" role="tab" aria-controls="{{$count+1}}" aria-selected="false">
                                                SEO
                                            </button>
                                        </li>
                                    </ul>
            
                                    <div class="tab-content border border-top-0" id="myTabContent">
                                        <div class="tab-pane fade show active w-100 p-3" id="{{$count}}" role="tabpanel" aria-labelledby="{{$count}}-tab">
                                            <div class="mb-2">
                                                <label class="text-dark" for="description">MÔ TẢ</label>
                                                <textarea name="slide[description][]" id="description" class="form-control" >{{$desc}}</textarea>
                                            </div>
                                            <div class="row align-items-center justify-content-between mb-2">
                                                <div class="col-auto flex-grow-1">
                                                    <input type="text" class="form-control" id="canonical-input" name="slide[canonical][]" placeholder="canonical" value="{{$canonical}}">
                                                </div>
                                                <div class="col-auto">
                                                    <div class="form-check">
                                                        <input type="checkbox" 
                                                            class="form-check-input" 
                                                             name="slide[window][]"
                                                              value="_blank" 
                                                              {{($window == '_blank') ? 'checked' : ''}}
                                                              id="input_{{$count}}">
                                                        <label class="form-check-label text-dark mt-2" for="new-tab">Mở trong tab mới</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
            
                                        <div class="tab-pane fade w-100 p-3 border-top-0" id="{{$count+1}}" role="tabpanel" aria-labelledby="{{$count+1}}-tab">
                                            <div class="mb-2">
                                                <label class="text-dark" for="image-title">Tiêu đề ảnh</label>
                                                <input type="text" class="form-control" id="image-title" name="slide[name][]" placeholder="Tiêu đề ảnh" value="{{$name}}">
                                            </div>
                                            <div class="mb-2">
                                                <label class="text-dark" for="image-description">Mô tả</label>
                                                <input type="text" class="form-control" id="image-description" name="slide[alt][]" placeholder="Mô tả" value="{{$alt}}"> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end slide-item -->
                      
                    </div>
                
                        @php
                            $count +=2;
                        @endphp
                  
                    @endforeach
                    @endif
                  
                </div>
              
            </div> <!-- end ibox-content -->
          
        