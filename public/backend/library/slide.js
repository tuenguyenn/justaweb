(function($) {
    "use strict";
    var TN = {};
    var counter =1
    TN.addSlide =(type) =>{
        $(document).on('click','.add-slide',function(e){
            e.preventDefault()
            if (typeof(type) == 'undefined') {
                type = 'Images'; 
            }
    
            var finder = new CKFinder();
            finder.resourceType = type;
            
            finder.selectActionFunction = function(fileUrl, data, allFiles) {
                let html =''
                for(var i=0;i<allFiles.length;i++){
                    var image = allFiles[i].url
                    html += TN.renderSlideItemHtml(image)
                }
               $('.slide-list').append(html)
               TN.checkSlideNotification()

    
            };
            finder.popup();
        })
    }
    TN.checkSlideNotification =()=>{
        let slideItem = $('.slide-item')
        if(slideItem.length){
            
            $('.slide-notification').hide()
        }else{
            $('.slide-notification').show()
        }
    }
    TN.renderSlideItemHtml =(image)=>{
        let tab_1 = "tab"+counter
        let tab_2 = "tab"+(counter+1)
        let html =`
              <div class="col-lg-12 p-2 bg-white border-0 ui-state-default">
                    <div class="slide-item mb-3 d-flex">
                        <div class="col-lg-3 text-center">
                            <span class="slide-image">
                                <img class="img-fluid" src="${image}" alt="Banner Image">
                                <input type="hidden" name="slide[image][]" id="" value ="${image}">
                                <button class="delete-slide position-absolute top-0 end-0 btn btn-danger btn-sm" type="button">
                                <i class="fa fa-trash"></i></button>
                            </span>
                        </div>
                        <div class="col-lg-9">
                            <div class="tabs-container">
                                <ul class="custom-tabs  nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <button class="nav-link active" id="${tab_1}-tab" data-bs-toggle="tab" data-bs-target="#${tab_1}" type="button" role="tab" aria-controls="${tab_1}" aria-selected="true">
                                            Thông tin chung
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="${tab_2}-tab" data-bs-toggle="tab" data-bs-target="#${tab_2}" type="button" role="tab" aria-controls="${tab_2}" aria-selected="false">
                                            SEO
                                        </button>
                                    </li>
                                </ul>
        
                                <div class="tab-content border border-top-0" id="myTabContent">
                                    <div class="tab-pane fade show active w-100 p-3" id="${tab_1}" role="tabpanel" aria-labelledby="${tab_1}-tab">
                                        <div class="mb-2">
                                            <label class="text-dark" for="description">MÔ TẢ</label>
                                            <textarea name="slide[description][]" id="description" class="form-control"></textarea>
                                        </div>
                                        <div class="row align-items-center justify-content-between mb-2">
                                            <div class="col-auto flex-grow-1">
                                                <input type="text" class="form-control" id="canonical-input" name="slide[canonical][]" placeholder="Canonical">
                                            </div>
                                            <div class="col-auto">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"  name="slide[window][]" value="_blank" id="input_${tab_1}">
                                                    <label class="form-check-label text-dark mt-2" for="new-tab">Mở trong tab mới</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="tab-pane fade w-100 p-3 border-top-0" id="${tab_2}" role="tabpanel" aria-labelledby="${tab_2}-tab">
                                        <div class="mb-2">
                                            <label class="text-dark" for="image-title">Tiêu đề ảnh</label>
                                            <input type="text" class="form-control" id="image-title" name="slide[name][]" placeholder="Tiêu đề ảnh">
                                        </div>
                                        <div class="mb-2">
                                            <label class="text-dark" for="image-description">Mô tả</label>
                                            <input type="text" class="form-control" id="image-description" name="slide[alt][]" placeholder="Mô tả">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end slide-item -->
                  
                </div>
        `;
        counter += 2
        return html
    }
    TN.deleteSlide =()=>{
        $(document).on('click','.delete-slide',function(){
            $(this).parents('.ui-state-default').remove()
            TN.checkSlideNotification()
        })
        
    }
    $(document).ready(function() {
        TN.addSlide()
        TN.checkSlideNotification()
        TN.deleteSlide()
        

    });
})(jQuery);
