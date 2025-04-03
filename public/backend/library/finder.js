(function($) {
    "use strict";
    var HT = {};

    HT.uploadImageToInput = () => {
        $('.upload-image').click(function() {
            let input =$(this)
            let type = input.attr('data-type')
            HT.setupCkFinder2(input,type); 
        });
    };

    HT.uploadImageAvatar =()=>{
        $('.image-target').click(function(){
            let input =$(this)
            let type = 'Images'
            HT.browseServerAvatar(input,type);
    })
    }
    HT.setupCkeditor =()=>{
        if($('.ck-editor')){
            $('.ck-editor').each(function(){
                let editor =$(this)
                let elementId = editor.attr('id')
                let elementHeight = editor.attr('data-height')
                HT.ckeditor4(elementId,elementHeight)
            })
        }

    }
    HT.ckeditor4 = (elementId,elementHeight) => {
        if(typeof(elementHeight) == 'undefined'){
            elementHeight = '500'
        }
        CKEDITOR.replace(elementId, {
          height: elementHeight,
          removeButtons: '',
          entities: true,
          allowedContent: true,
          removePlugins: 'exportpdf', // Vô hiệu hóa plugin exportpdf

          toolbarGroups: [
            { name: 'Clipboard', groups: ['clipboard', 'undo'] },
            { name: 'editing', groups: ['find', 'selection', 'spellchecker'] },
            { name: 'links' },
            { name: 'insert' },
            { name: 'forms' },
            { name: 'tools' },
            { name: 'document', groups: ['mode', 'document', 'doctools'] },
            { name: 'colors' },
            { name: 'others' },
            '/',
            { name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
            { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'] },
            { name: 'styles' },
          ],
        });
      };
    HT.uploadAlbum =()=>{
        $(document).on('click', '.upload-picture',function(e){
            HT.browseServerAlbum()
            e.preventDefault()

        })
    }
    HT.browseServerAvatar =(object,type)=>{
        if (typeof(type) == 'undefined') {
            type = 'Images'; 
        }

        var finder = new CKFinder();
        finder.resourceType = type;

        finder.selectActionFunction = function(fileUrl, data) {
            object.find('img').attr('src',fileUrl)
            object.siblings('input').val(fileUrl)

        };
        
        finder.popup();
    };
    HT.browseServerAlbum =()=>{
        var type = 'Images'; 
       
        var finder = new CKFinder();
        finder.resourceType = type;

        finder.selectActionFunction = function(fileUrl, data, allFiles) {
            let html =''
            for(var i=0;i<allFiles.length;i++){
                var image = allFiles[i].url
                html+= '<li class="ui-state-default border-0">'
                    html+= '<div class="thumb position-relative">'
                        html+= '<button class="delete-image position-absolute top-0 end-0 btn btn-danger btn-sm" type="button">'
                            html+= '<i class="fa fa-trash"></i>'
                        html+= '</button>'
                        html+= '<span class="image-item img-scaledown">'
                            html+= '<img src="'+image+'" alt="'+image+' " class="list-slide-image">'
                            html+= '<input type="hidden" name="album[]" value="'+image+'">'
                        html+= '</span>'
                    html+= '</div>'
                html+= '</li> '
            }
            $('.click-to-upload').addClass('d-none')
            $('.upload-list').removeClass('d-none')
            $('#sortable').append(html)


        };
        
        finder.popup();
    }
    
    HT.setupCkFinder2 = (object,type) => {
        if (typeof(type) == 'undefined') {
            type = 'Images'; 
        }
        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function(fileUrl, data) {
            object.val(fileUrl); 
        };
        
        

        finder.popup();
    };
    HT.deletePicture =()=>{
        $(document).on('click','.delete-image',function(){
            let _this =$(this)
            _this.parents('.ui-state-default').remove()
            if($('.ui-state-default').length ==0){
                $('.click-to-upload').removeClass('d-none')
                $('.upload-list').addClass('d-none')
            }
        })
    }

    $(document).ready(function() {
        HT.uploadImageToInput();
        HT.setupCkeditor();
        HT.uploadImageAvatar();
        HT.uploadAlbum();
        HT.deletePicture();


    });

})(jQuery); 