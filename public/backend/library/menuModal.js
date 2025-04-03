(function($) {
    "use strict";
    var _token =$('meta[name="csrf-token"]').attr('content')

    var HT = {};

    HT.createModalMenu = () => {
        $('.createMenuCatalogue').on('click', function() {
            $('#createCatalogueMenuModal').css('display', 'block');
        });

        $('.close-modal, .btn-cancel').on('click', function() {
            $('#createCatalogueMenuModal').css('display', 'none');
        });

      
    };

    HT.createMenuCatalogue =()=>{
        $(document).on('submit','.create-menu-catalogue', function(e){
            e.preventDefault();
            let _form =$(this)
            let option ={
                'name': _form.find('input[name=name]').val(),
                'keyword':  _form.find('input[name=keyword]').val(),
                '_token': _token
            }
            $.ajax({
                type: "POST",
                url: "ajax/menu/createCatalogue",  
                data: option,
                dataType: 'json',
                success: function(res) {  
                  if(res.code == 0){
                    $('.form-error').removeClass('text-danger').addClass('text-success').html(res.message).show()
                    const menuCatalogueSelect =$('select[name=menu_catalogue_id]')
                    menuCatalogueSelect.append('<option value="'+ res.data.id+'">'+res.data.name+'</option>')
                  }else{
                    $('.form-error').removeClass('text-success').addClass('text-danger').html(res.message).show()
                  }
                },
                beforeSend: function(){
                    _form.find('.error').html(''),
                    _form.find('.form-error').hide()


                },

                error: function(jqXHR, textStatus, errorThrown) {
                   if(jqXHR.status === 422){
                    let errors = jqXHR.responseJSON.errors
                    for(let field in errors){
                        let errorMessage= errors[field]
                        errorMessage.forEach(function(message){
                            $('.'+field).html(message)
                        })
                    }

                   }
                }
            });
        })

    },

    HT.createMenuRow =()=>{
        $(document).on('click','.add-menu',function(e){
            e.preventDefault()
          
            let _this= $(this)
            $('.menu-wrapper').append(HT.menuRowHtml()).find('.notification').hide()


        })
    }
    HT.menuRowHtml =(option)=>{
        let $row = $('<div>').addClass('d-flex pt-2 menu-item ' + ((option && option.canonical) ? option.canonical : '') + '');
        const columns = [
            { class: 'form-floating col-lg-4 mb-3', name: 'menu[name][]', label: 'Tên Menu', labelClass: 'ps-4', value: (option && option.name) ? option.name : '' },
            { class: 'form-floating col-lg-4 mb-3', name: 'menu[canonical][]', label: 'Đường dẫn', labelClass: 'ps-4', value: (option && option.canonical) ? option.canonical : '' },
            { class: 'form-floating col-lg-2 mb-3', name: 'menu[order][]', label: 'Vị trí', labelClass: 'ps-4', value:  0 },
        ];
        
        columns.forEach(col => {
            let $col = $('<div>').addClass(col.class);
        
            let $input = $('<input>')
                .addClass('form-control')
                .attr('name', col.name)
                .attr('placeholder', '')
                .attr('value', col.value );
        
            if (col.name === 'menu[order][]') {
                $input.attr('type', 'number'); 
                $input.attr('min', '0'); 
            } else {
                $input.attr('type', 'text'); 
            }
        
            let $label = $('<label>')
                .html(col.label)
                .addClass(col.labelClass);
        
            $col.append($input);
            $col.append($label);
        
            $row.append($col);
        });
        
        let $removeCol = $('<div>').addClass('col-lg-2 mt-2')
        let $a =$('<a>').addClass('delete-menu text-right')
        let $svg = $(`<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" color="black" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
         </svg>`);
        let $input =$('<input>').addClass('d-none').attr('name','menu[id][]').attr('value',0)

       $a.append($svg)
        $removeCol.append($a)
        $removeCol.append($input)
        $row.append($removeCol)
        return $row
        

    }
    HT.deleteMenuRow=()=>{
        $(document).on('click','.delete-menu',function(){
            let _this= $(this)
            _this.parents('.menu-item').remove()
            HT.checkMenuItemLength()
        
        })
    }
    HT.checkMenuItemLength =()=>{
        if($('.menu-item').length ===0){
            $('.notification').show()
        }
    }
    HT.getMenu = () => {
        $(document).on('click', '.menu-module', function () {
    
            let _this = $(this);
            let option = {
                model: _this.attr('data-model')
            };
    
            let target =  _this.parents('.accordion-item').find('.menu-list')
            let menuRowClass = HT.checkMenuRowExist()

          HT.sendAjaxGetMenu(option,target, menuRowClass)
        });
    }
    HT.checkMenuRowExist=()=>{
        let menuRowClass = $('.menu-item').map(function(){
            let allClasses = $(this).attr('class').split(' ').slice(3).join(' ')
            return allClasses
        }).get()

        return menuRowClass
    }
    HT.menuLink = (links) => {
        let html = '';
        if (links.length >3) {
            html += '<nav class="d-flex justify-items-center justify-content-between">';
            html += `
                <div>
                    <ul class="pagination">`;
    
            for (let i = 0; i < links.length; i++) {
                const link = links[i];
    
                if (link.active === false && (link.label.includes('Previous') || link.label.includes('Next') ) ) {
                    html += `
                    <li class="page-item">
                        <a class="page-link" href="${link.url ? link.url : '#'}">${link.label.includes('Previous') ? '&laquo;' : '&raquo;'}</a>
                    </li>`;
                } else if (link.active) {
                    html += `
                        <li class="page-item active" aria-current="page">
                            <span class="page-link">${link.label}</span>
                        </li>`;
                } else {
                    html += `
                        <li class="page-item">
                            <a class="page-link" href="${link.url}">${link.label}</a>
                        </li>`;
                }
            }
    
            html += `
                    </ul>
                </div>
            </nav>`;
        }
    
        return html; 
    };
    HT.sendAjaxGetMenu =(option,target,menuRowClass)=>[
          
        $.ajax({
            type: "GET",
            url: "ajax/status/getMenu",
            data: option,
            dataType: 'json',
            beforeSend:function(){
                target.html('')
            },
            success: function (res) {
                let html = '';
                for (let i = 0; i < res.data.length; i++) {
                    html += HT.renderModelMenu(res.data[i],menuRowClass);
                }
                html+= HT.menuLink(res.links)
                target.html(html)
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("Lỗi khi tải dữ liệu:", textStatus);
            }
        })
    ]
    HT.getPaginationMenu =()=>{
          $(document).on('click','.page-link',function(e){
            
                e.preventDefault()
                let _this=$(this)
                let option ={
                    model : _this.parents('.accordion-collapse').attr('id'),
                    page : _this.text()

                }
                let target = _this.parents('.menu-list')
                let menuRowClass = HT.checkMenuRowExist()
                HT.sendAjaxGetMenu(option,target,menuRowClass)
        
                
          })
    }
    
    
    
    HT.renderModelMenu =(object, renderModelMenu)=>{
        let html =`
            <div class="m-item">
                <div>
                    <input type="checkbox" ${(renderModelMenu.includes(object.canonical) ? 'checked' : '')} name="" value="${object.canonical}" class="m-0 choose-menu" id="${object.canonical}">
                    <label for="${object.canonical}" class="text-primary">${object.name}</label>
                </div>
            </div>
        
        `;
        return html
    }
    HT.chooseMenu =()=>{
        $(document).on('click', '.choose-menu', function () {
            
            let _this = $(this);
            let canonical = _this.val()
            let name = _this.next('label').text(); 
            let $row = HT.menuRowHtml({
                name : name,
                canonical : canonical
            })
            let isChecked = _this.prop('checked')
            if(isChecked === true){
                $('.menu-wrapper').append($row).find('.notification').hide()
            }else{
                $('.menu-wrapper').find('.'+canonical).remove()
                HT.checkMenuItemLength()

            }
        })
    }

    HT.searchMenu =() =>{
        let typingTimer
        let doneTypingInterval =1000
        $(document).on('keyup', '.search-menu', function () {
           let _this = $(this)
           let keyword = _this.val()

            let option ={
                model : _this.parents('.accordion-collapse').attr('id'),
                keyword : keyword,
            }
           if(keyword.length >=2 || keyword.length ==0){
                clearTimeout(typingTimer)
                typingTimer = setTimeout(function(){
                    let menuRowClass = HT.checkMenuRowExist()
                    let target = _this.parent().siblings('.menu-list')

                    HT.sendAjaxGetMenu(option,target,menuRowClass)
                },doneTypingInterval)
           }

        })
    }
    HT.setupNestable =()=>{
        if($('#nestable2').length){
                    
    
            $('#nestable2').nestable({
                group: 1
            }).on('change', HT.updateNestableOutput);
    
    
        }
    }
    HT.updateNestableOutput = (e) => {
        var list = $(e.currentTarget),
            output = list.data('output');
        
        let json = JSON.stringify(list.nestable('serialize'));
        if (json.length) {
            let option = {
                json: json,
               menu_catalogue_id: $('#dataCatalogue').attr('data-catalogueId'),
                _token: _token  
            };
    
            $.ajax({
                type: "POST",
                url: "http://ecommere.test/ajax/menu/drag",
                data: option,
                dataType: 'json',
                success: function (res) {
                  
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);  
                }
            });
        }
    };
    

    HT.runUpdateNestableOutput=()=>{
        updateOutput($('#nestable2').data('output', $('#nestable2-output')));

    }
    HT.expandAndCollapse =()=>{
        
        $('#nestable-menu').on('click', function (e) {
            var target = $(e.target),
                    action = target.data('action');
            if (action === 'expand-all') {
                $('.dd').nestable('expandAll');
            }
            if (action === 'collapse-all') {    
                $('.dd').nestable('collapseAll');
            }
        });
    }
    $(document).ready(function() {
        HT.createModalMenu();
        HT.createMenuCatalogue();
        HT.createMenuRow();
        HT.deleteMenuRow();
        HT.getMenu();
        HT.chooseMenu();
        HT.getPaginationMenu();
        HT.searchMenu();
        HT.setupNestable();
        HT.updateNestableOutput();
        HT.runUpdateNestableOutput();
        HT.expandAndCollapse();
    });
    
})(jQuery);
