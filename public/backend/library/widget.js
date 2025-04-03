(function($) {
    "use strict";

    var TN = {};
    var _token =$('meta[name="csrf-token"]').attr('content')
    let typingTimer
    let doneTypingInterval =500
   
    TN.searchMenu =() =>{
       
        $(document).on('keyup', '.search-model', function (e) {
            e.preventDefault()
            let _this = $(this)
            if($('input[type=radio]:checked').length === 0){
                alert('Vui lòng chọn Module')
                _this.val('')
                return false;
            }
           let keyword = _this.val()

            let option ={
                model : $('input[type=radio]:checked').val(),
                keyword : keyword,
            }
            if(keyword.length >=2 ){
               TN.sendAjax(option)
            }

        })
    }
    TN.renderSearchResult =(data)=>{
        let html=''
        if(data.length){
            for(let i=0; i<data.length;i++){
                let flag = ($('#model-'+data[i].id).length) ? 1 : 0;
                let setChecked = ($('#model-'+data[i].id).length) ? TN.setChecked() : ''
                html+= `
                 <button class="ajax-search-item w-100 p-2 d-flex align-items-center justify-content-between border-0  bg-light text-start"
                   
                    data-flag ="${flag}"
                    data-canonical="${data[i].languages[0].pivot.canonical}" 
                    data-id="${data[i].id}" 
                    data-image="${data[i].image}"
                    data-name ="${data[i].languages[0].pivot.name}"
                    >
                <span class="text-primary fw-bold">${data[i].languages[0].pivot.name}</span>
                <div class="auto-icon">
                   ${setChecked}
                </div>
             </button>`
            }
        }
        return html
    }
    TN.chooseModel =()=>{
        $(document).on('change','.input-radio',function(e){
            e.preventDefault()
            let _this= $(this)
            let inputKeyword =$('.search-model').val()
            let option ={
                model : _this.val(),
                keyword : inputKeyword ,
            }
            $('.search-model-result').html('')
            if(keyword.length >=2 ){
                TN.sendAjax(option)
             }
 
        })
    }
    TN.setChecked = ()=>{
        let icon = ` <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="rgba(0, 0, 0, 1)">
                        <path d="m10 15.586-3.293-3.293-1.414 1.414L10 18.414l9.707-9.707-1.414-1.414z"></path>
                    </svg>`
        return icon
    }
    TN.unfocusSearchBox =()=>{
        $(document).on('click','html',function(e){
            if(!$(e.target).hasClass('.search-model-box') || !(e.target).hasClass('search-model')){
                $('.ajax-search-result').html('')
            }
        })
        $(document).on('click','.search-model-box',function(e){
            e.stopPropagation()
        })
      
    }
    TN.addModel =()=>{
        $(document).on('click','.ajax-search-item',function(e){
            e.preventDefault()
            let _this= $(this)
            let data= _this.data()
            let flag = _this.attr('data-flag')
            if(flag == 0){
                _this.find('.auto-icon').html(TN.setChecked())
                _this.attr('data-flag',1)
                $('.search-model-result').append(TN.modelTemplate(data))
            }else{
                $('#model-'+data.id).remove()
                _this.find('.auto-icon').html('')
                _this.attr('data-flag',0)
            }
            
            
        })

    }
    TN.modelTemplate =(data)=>{
        let image = (data.image == null) ? 'userfiles/image/languages/no-image-icon.jpg' : data.image
        let html =`  <div class="search-model-item d-flex align-items-center justify-content-between  border rounded mb-2" 
                        data-modelId="${data.id}"
                         id="model-${data.id}">
                <div class="d-flex align-items-center">
                    <img class=" me-3" 
                         src="${image}" 
                         alt="Module Image">
                    <span class="text-primary">${data.name}</span>
                    <div class="d-none">
                        <input type="text" name="model_id[id][]" value="${data.id}">
                        <input type="text" name="model_id[name][]" value="${data.name}">
                        <input type="text" name="model_id[image][]" value="${image}">

                    </div>
                </div>
                <a class="delete-model-item text-danger fw-bold cursor-pointer"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" color="black" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"></path>
                 </svg></a>
                
            </div>`;
        return html
    }
    TN.removeModel =() =>{
        $(document).on('click','.delete-model-item',function(){
            let _this = $(this)
            _this.parent('.search-model-item').remove()
        })
    }    
    TN.sendAjax = (option)=>{
        clearTimeout(typingTimer)
        typingTimer = setTimeout(function(){
            $.ajax({
                type: "GET",
                url: "ajax/status/findModelObject",
                data: option,
                dataType: 'json',
                beforeSend:function(){
                    $('.ajax-search-result').html('')

                },
                success: function (res) {
                    let html = TN.renderSearchResult(res)
                    if (html.length){
                        $('.ajax-search-result').html(html)
                    }else{
                        $('.ajax-search-result').html(
                            '<div class="alert alert-danger" role="alert">Không tìm thấy kết quả nào</div>'
                        )
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log("Lỗi khi tải dữ liệu:", textStatus);
                }
            })

        },doneTypingInterval)
    }
    $(document).ready(function() {
        TN.searchMenu()
        TN.chooseModel()
        TN.unfocusSearchBox()
        TN.addModel()
        TN.removeModel()
    });
    
})(jQuery);
