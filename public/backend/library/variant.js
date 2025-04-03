(function($) {
  "use strict";
  
  var TN = {
    options: '', 
    maxAttributes: 0, 

    setupOptions: function () {
      var options = '<option value="">Chọn thuộc tính</option>';
      attributeCatalogue.forEach(function (attribute) {
        options += `<option value="${attribute.id}">${attribute.name}</option>`;
      });

      this.options = options;
      this.maxAttributes = attributeCatalogue.length; 
    },

    setupCheckbox: function () {
      $('#variantCheckbox').change(function () {
          if ($(this).is(':checked')) {
              $('.variant-wrapper').removeClass('d-none'); 
          } else {
              $('.variant-wrapper').addClass('d-none'); 
          }
      });
  },
  

  setupAddVariant: function () {
    var self = this;

    $('.variant-foot').off('click', '.add-variant').on('click', '.add-variant', function () {
       
        var newVariantBody = `
            <div class="mb-2 variant-item row">
                <div class="col-lg-3">
                    <div class="attribute-catalogue">
                        <select name="attributeCatalogue[]" class=" choose-attribute niceSelect">
                            ${self.options}
                        </select>
                    </div>
                </div>
                <div class="col-lg-8">
                    <input type="text" name="" disabled class="fake-variant form-control">
                </div>
                <div class="col-lg-1">
                    <button type="button" class="btn btn-danger remove-attribute"><i class="fa fa-trash"></i></button>
                </div>
            </div>
        `;

        $('.variant-body').append(newVariantBody);


        $('.variantTable thead').html('');
        $('.variantTable tbody').html('');

        self.disabledChoosed();
        self.checkMaxAttribute();
    });
},



    chooseVariantGroup: function () {
      $(document).on('change', '.choose-attribute', function () {
        let _this = $(this);
        let attributeCatalogueId = _this.val();
        
        if (attributeCatalogueId != 0) {
          _this.parents('.col-lg-3')
               .siblings('.col-lg-8')
               .html(TN.select2Variant(attributeCatalogueId))
               $('.selectVariant').each(function(key,index){
                    TN.getSelect2($(this))
                 })
               
        } else {
          _this.parents('.col-lg-3')
               .siblings('.col-lg-8')
               .html('<input type="text" name="attribute['+attributeCatalogueId+'][]" disabled="" class="fake-variant form-control">');
        }
        TN.disabledChoosed();
      });
    },

    getSelect2 : function(object){
        let option = {
            'attributeCatalogueId': object.attr('data-catid')
        };
        $(object).select2({
            minimumInputLength: 2,
            placeholder: 'Nhập tối thiểu 2 kí tự để tìm kiếm',
            ajax: {
                url: 'ajax/attribute/getAttribute',
                type: 'GET',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        option: option
                    };
                },
                processResults: function(data) {


                    return {
                        results: $.map(data, function(obj, i) {
                            return obj;
                        })
                    };
                },
                cache: true
            }
        });
    
    
    },

    setupRemoveAttribute: function () {
      $(document).on('click', '.remove-attribute', function () {
          $(this).closest('.variant-item').remove();
          TN.createVariant(); 

          TN.checkMaxAttribute(); 
      });
  },
  
    disabledChoosed: function () {
      let id = [];
      $('.choose-attribute').each(function () {
        let _this = $(this);
        let selected = _this.find('option:selected').val();
        if (selected != 0) {
          id.push(selected);
        }
      });
      $('.choose-attribute').find('option').removeAttr('disabled');
      for (let i = 0; i < id.length; i++) {
        $('.choose-attribute').find('option[value=' + id[i] + ']').prop('disabled', true);
      }

      if ($('.niceSelect').length) {
        $('.niceSelect').niceSelect('destroy');
      }
      $('.choose-attribute').niceSelect();
      $('.choose-attribute').find('option:selected').removeAttr('disabled');
    },

    checkMaxAttribute: function () {
      if ($('.variant-item').length >= this.maxAttributes) {
        $('.add-variant').hide(); 
      } else {
        $('.add-variant').show(); 
      }
    },

    select2Variant: function(attributeCatalogueId) {
      let html = `<select class="selectVariant variant-${attributeCatalogueId} form-control" name="attribute[${attributeCatalogueId}][]" multiple data-catid="${attributeCatalogueId}"></select>`;
      return html;
  },
  
  createProductVariant: function() {
    $(document).off('change', '.selectVariant').on('change', '.selectVariant', function() {
        let _this = $(this);
        TN.createVariant();
    });
},

createVariant: function() {
  let attributes = [];
  let variants = [];
  let attributeTitle = [];

  $('.variant-item').each(function() {
      let _this = $(this);
      let attr = [];
      let attrVariant = [];
      let attributeCatalogueId = _this.find('.choose-attribute').val();
      let optionText = _this.find('.choose-attribute option:selected').text();
      let attributeElement = $('.variant-' + attributeCatalogueId);
      let attribute = attributeElement.length ? attributeElement.select2('data') : [];

      if (Array.isArray(attribute) && attribute.length > 0) {
          attribute.forEach(item => {
              attr.push({ [optionText]: item.text });
              attrVariant.push({ [attributeCatalogueId]: item.id });
          });
      }
      attributeTitle.push(optionText);
      attributes.push(attr);
      variants.push(attrVariant);
  });

  attributes = attributes.reduce(
      (a, b) => a.flatMap(d => b.map(e => ({ ...d, ...e }))),
  );
  variants = variants.reduce(
      (a, b) => a.flatMap(d => b.map(e => ({ ...d, ...e }))),
  );
  TN.createTableHeader(attributeTitle);

  let trClass = [];
  attributes.forEach((item, index) => {
      let classModified = 'tr-variant-' + Object.values(variants[index]).map(val => val.toString().replace(/\W/g, '')).join('-');
      let $row = TN.createVariantRow(item, variants[index]);

      trClass.push(classModified);
      if ($(`table.variantTable tbody tr.${classModified}`).length === 0) {
          $('table.variantTable tbody').append($row);
      }
  });

  $('table.variantTable tbody tr').each(function() {
      const $row = $(this);
      const rowClasses = $row.attr('class') || '';
      if (!trClass.some(cls => rowClasses.includes(cls))) {
          $row.remove();
      }
  });
},

  createTableHeader :function(attributeTitle){
    let $thead =$('table.variantTable thead')
    let $row =$('<tr>')
    $row.append($('<td>').text('Hình ảnh'))
    for(let i=0;i<attributeTitle.length;i++){
      $row.append($('<td>').text(attributeTitle[i]))
    }
    $row.append($('<td>').text('Số lượng'))
    $row.append($('<td>').text('Giá tiền'))

    $row.append($('<td>').text('SKU'))
    $thead.html($row)
    return $thead

  },
  createVariantRow: function(attributeItem, variantItem){
      let attributeString =Object.values(attributeItem).join(',')
      let attributeId =Object.values(variantItem).join(',')
      let classModified = attributeId.replace(/,/g, '-'); 


      let $row =$('<tr>').addClass('variant-row tr-variant-'+ classModified)
      let $td

      $td =$('<td>').append(
        $('<img>').addClass('imgSrc thumbnail rounded').attr('src','userfiles\\image\\languages\\no-img.png')
      )
      $row.append($td)
      Object.values(attributeItem).forEach((value)=>{
        $td = $('<td>').text(value) 
        $row.append($td)
      })

      $td =$('<td>').addClass('d-none td-variant')
      let mainPrice=  $('input[name=price]').val()
      let mainSku = $('input[name=code]').val()
      let inputHiddenFields =[
        {name:'variant[quantity][]', class:'variant_quantity' },
        {name:'variant[sku][]', class:'variant_sku' ,value: mainSku +'-'+ classModified},
        {name:'variant[price][]', class:'variant_price' ,value: mainPrice} ,
        {name:'variant[barcode][]', class:'variant_barcode'},
        {name:'variant[file_name][]', class:'variant_filename'},
        {name:'variant[file_url][]', class:'variant_fileurl'},
        {name:'variant[album][]', class:'variant_album'},
        {name:'productVariant[name][]', value: attributeString},
        {name:'productVariant[id][]', value:attributeId},
      ]
      $.each(inputHiddenFields ,function(_,field){
        let $input =$('<input>').attr('type','text').attr('name',field.name).addClass(field.class)
        if(field.value){
          $input.val(field.value)
        }
        $td.append($input)
      })

      $row.append($('<td>').addClass('td-quantity').text('-'))
          .append($('<td>').addClass('td-price').text(mainPrice))
          .append($('<td>').addClass('td-sku').text( mainSku +'-'+ classModified))
          .append($td)

      return $row
         
         

 },
 

  variantAlbum:function(){
    $(document).on('click', '.click-to-upload-variant, .click-to-upload-variant-text',function(e){
       TN.browseVariantServerAlbum()
       e.preventDefault();

    })

  },
  browseVariantServerAlbum :function(){
    var type = 'Images'; 
   
    var finder = new CKFinder();
    finder.resourceType = type;

    finder.selectActionFunction = function(fileUrl, data, allFiles) {
        let html =''
        for(var i=0;i<allFiles.length;i++){
            var image = allFiles[i].url
            html+= '<li class="ui-state-default">'
                html+= '<div class="thumb position-relative">'
                    html+= '<button class="remove-image position-absolute top-0 end-0 btn btn-danger btn-sm" type="button">'
                        html+= '<i class="fa fa-trash"></i>'
                    html+= '</button>'
                    html+= '<span class="image img-scaledown">'
                        html+= '<img src="'+image+'" alt="'+image+' " class="img-thumbnail">'
                        html+= '<input type="hidden" name="variantAlbum[]" value="'+image+'">'
                    html+= '</span>'
                html+= '</div>'
            html+= '</li> '
        }
        $('.click-to-upload-variant').addClass('d-none')
        $('.upload-variant-list').removeClass('d-none')
        $('#sortVariantAlbum').append(html)


    };
    
    finder.popup();
},
    sortImg :function() {
      $("#sortVariantAlbum").disableSelection();
      $("#sortVariantAlbum").sortable();

    },
    removePicture :function(){
      $(document).on('click','.remove-image',function(){
          let _this =$(this)
          _this.parents('.ui-state-default').remove()
          if($('.ui-state-default').length ==0){
              $('.click-to-upload-variant').removeClass('d-none')
              $('.upload-variant-list').addClass('d-none')
          }
      })
  },
    switchChange:function(){
      $(document).on('change','.js-switch',function(){
        let _this = $(this)
         let isChecked = _this.prop('checked')
         if(isChecked ==true){
            _this.parents('.col-lg-2').siblings('.col-lg-10').find('.disabled').removeAttr('disabled')
         }else{
          _this.parents('.col-lg-2').siblings('.col-lg-10').find('.disabled').attr('disabled',true)

         }

      })
    },
    switchery :function ()  {
      $('.js-switch').each(function() {
          if (!$(this).data('switchery')) {
              var switchery = new Switchery(this, { color: '#1AB394' ,size: 'tiny'});
              $(this).data('switchery', switchery);
          }
      });
    },
    updateVariant: function() {
      $(document).on('click', '.variant-row', function() {
          let _this = $(this);
          let variantData ={}
          _this.find(".td-variant input[type=text][class^='variant_']").each(function() {
            let className = $(this).attr('class');
            
            variantData[className] = $(this).val();
        });
        
          let updateVariantBox = TN.updateVariantHTML(variantData);
          if($('.updateVariantTr').length ==0){
            _this.after(updateVariantBox);
            TN.switchery()
            TN.sortImg()

          }
          
      });
  },
   variantAlbumList :function(album){
    let html = ''
      if(album.length){
        for(let i=0;i<album.length;i++){
          html += `
          <li class="ui-state-default">
          <div class="thumb position-relative">
          <button class="remove-image position-absolute top-0 end-0 btn btn-danger btn-sm" type="button">
          <i class="fa fa-trash"></i></button><span class="image img-scaledown">
            <img src="${album[i]}" class="img-thumbnail">
            <input type="hidden" name="variantAlbum[]" value="${album[i]}">
            </span>
            </div>
            </li>
       `;
        }
      }
       return html
       
   } ,

    updateVariantHTML:function(variantData){
      let variantAlbum =[]
      if (variantData.variant_album && variantData.variant_album.trim() !== '') {
        variantAlbum = variantData.variant_album.split(',').map(album => album.trim());
        
      } 
      let variantAlbumItem =TN.variantAlbumList(variantAlbum);
        let html = ` <tr class="updateVariantTr">
        <td colspan="10">
           <div class="updateVariant ibox">
             <div class="ibox-title">
                <div class="d-flex justify-content-between">
                  <h4> Cập nhật thôg tin phiên bản</h4>
                  <a href="" class="click-to-upload-variant-text float-right">Chọn hình</a>

                
                </div>
             </div>
             <div class="ibox-content">
              <div class="click-to-upload-variant text-center ${(variantAlbumItem !== '') ?'d-none' :''}">
                  <div class="icon mb-3">
                    <a href="" class="upload-variant-picture">
                      <svg style="width:150px;height:150px" viewBox="0 0 48 48" id="a" xmlns="http://www.w3.org/2000/svg" fill="#000000">
                          <g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                          <g id="SVGRepo_iconCarrier"><defs><style>.b{fill:none;stroke:#000000;stroke-linecap:round;stroke-linejoin:round;}</style>
                          </defs><path class="b" d="M29.4995,12.3739c.7719-.0965,1.5437,.4824,1.5437,1.2543h0l2.5085,23.8312c.0965,.7719-.4824,1.5437-1.2543,1.5437l-23.7347,2.5085c-.7719,.0965-1.5437-.4824-1.5437-1.2543h0l-2.5085-23.7347c-.0965-.7719,.4824-1.5437,1.2543-1.5437l23.7347-2.605Z"></path><path class="b" d="M12.9045,18.9347c-1.7367,.193-3.0874,1.7367-2.8945,3.5699,.193,1.7367,1.7367,3.0874,3.5699,2.8945,1.7367-.193,3.0874-1.7367,2.8945-3.5699s-1.8332-3.0874-3.5699-2.8945h0Zm8.7799,5.596l-4.6312,5.6925c-.193,.193-.4824,.2894-.6754,.0965h0l-1.0613-.8683c-.193-.193-.5789-.0965-.6754,.0965l-5.0171,6.1749c-.193,.193-.193,.5789,.0965,.6754-.0965,.0965,.0965,.0965,.193,.0965l19.9719-2.1226c.2894,0,.4824-.2894,.4824-.5789,0-.0965-.0965-.193-.0965-.2894l-7.8151-9.0694c-.2894-.0965-.5789-.0965-.7719,.0965h0Z"></path><path class="b" d="M16.2814,13.8211l.6754-6.0784c.0965-.7719,.7719-1.3508,1.5437-1.2543l23.7347,2.5085c.7719,.0965,1.3508,.7719,1.2543,1.5437h0l-2.5085,23.7347c0,.6754-.7719,1.2543-1.5437,1.2543l-6.1749-.6754"></path><path class="b" d="M32.7799,29.9337l5.3065,.5789c.2894,0,.4824-.193,.5789-.4824,0-.0965,0-.193-.0965-.2894l-5.789-10.5166c-.0965-.193-.4824-.2894-.6754-.193h0l-.3859,.3859"></path></g></svg>
                  </a>
                  </div>
                  <div class="text-sm text-secondary">Sử dụng nút chọn hình để thêm mới hình ảnh</div>
              </div>
          
              <!-- Upload List - Hidden by default -->
              <ul class="upload-variant-list d-flex flex-wrap gap-3 list-unstyled ${(variantAlbumItem !== '') ? '' :'d-none'} border p-3" id="sortVariantAlbum">
                  ${variantAlbumItem}
              </ul>
          
              <div class="row mt-4">
                  <div class="col-lg-2 mt-4">
                      <label for="" class="form-label">Quản lý kho</label>
                      <input type="checkbox" class="js-switch"  form-check-input" ${(variantData.variant_quantity !== '') ? 'checked' : ''} id="" data-target="variantQuantity">
                  </div>
                  <div class="col-lg-10">
                      <div class="row ">
                          <div class="col-lg-3">
                              <label for="variantQuantity" class="form-label">Số lượng</label>
                              <input type="text" id="variantQuantity" ${(variantData.variant_quantity !== '') ? '' : 'disabled'} name="variant_quantity" value="${variantData.variant_quantity}" class="form-control disabled int">
                          </div>
                          <div class="col-lg-3">
                              <label for="sku" class="form-label">SKU</label>
                              <input type="text" id="sku" name="variant_sku" value="${variantData.variant_sku}" class="form-control">
                          </div>
                          <div class="col-lg-3">
                              <label for="price" class="form-label">Giá tiền</label>
                              <input type="text" id="price" name="variant_price" value="${TN.addCommas(variantData.variant_price)}" class="form-control int">
                          </div>
                          <div class="col-lg-3">
                              <label for="barcode" class="form-label">Barcode</label>
                              <input type="text" id="barcode" name="variant_barcode" value="${variantData.variant_barcode}" class="form-control">
                          </div>
                      </div>
                  </div>
              </div>
              <div class="row mt-4">
                <div class="col-lg-2 mt-4">
                    <label for="" class="form-label">Quản lý file</label>
                    <input type="checkbox" class="js-switch form-check-input" ${(variantData.variant_filename !== '') ? 'checked' : ''} id="" data-target="">
                </div>
                <div class="col-lg-10">
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="" class="form-label">Tên File</label>
                            <input type="text" ${(variantData.variant_filename !== '') ? '' : 'disabled'} name="variant_file_name" value="${variantData.variant_filename}" class="form-control disabled">
                        </div>
                        <div class="col-lg-6">
                            <label for="" class="form-label">Đường dẫn</label>
                            <input type="text" ${(variantData.variant_fileurl !== '') ? '' : 'disabled'} name="variant_file_url" value="${variantData.variant_fileurl}" class="form-control disabled ">
                        </div>
                        
                    </div>
                </div>
                 <div class="row mt-2">
                    <div class="button-group col-lg-12 d-flex justify-content-end">
                        <button type="button" class="cancelUpdate btn btn-danger me-1">HUỶ</button>
                        <button type="button" class="saveUpdate btn btn-primary">LƯU</button>
                    </div>
                </div>

              </div>
            </div>
             
          </div>

        </td>
      </tr>
      `;
      return html
    },

    cancelVariantUpdate :function(){
      $(document).on('click','.cancelUpdate',function(){
        TN.closeUpdateVariantBox()
      })
    },
    closeUpdateVariantBox: function(){
      $('.updateVariantTr').remove()

    },
    saveVariantUpdate: function () {
      $(document).on('click', '.saveUpdate', function () {
          let variant = {
              quantity: $('input[name="variant_quantity"]').val(),
              sku: $('input[name="variant_sku"]').val(),
              price: $('input[name="variant_price"]').val(),
              barcode: $('input[name="variant_barcode"]').val(),
              filename: $('input[name="variant_file_name"]').val(),
              fileurl: $('input[name="variant_file_url"]').val(),
              album: $("input[name='variantAlbum[]']").map(function () {
                  return $(this).val();
              }).get(),
          };
  
          $.each(variant, function (key, value) {
              $('.updateVariantTr').prev().find('.variant_' + key).val(value)
              
          });
  
          setTimeout(function () {
              TN.previewVariantTr(variant)
              TN.closeUpdateVariantBox();

          }, 250);
      });
  },

  previewVariantTr:function(variant){
    let option ={
       'quantity' : variant.quantity,
        'price': TN.addCommas(variant.price),
        'sku':variant.sku,
    }
    $.each(option, function (key, value) {
      $('.updateVariantTr').prev().find('.td-' + key).html(value)
    })
    $('.updateVariantTr').prev().find('.imgSrc').attr('src',variant.album[0])


  },

  
  addCommas :function(nStr)  {
    nStr = String(nStr);
    nStr = nStr.replace(/\.|,/gi, ""); // Loại bỏ dấu "." hoặc ","
    let str = '';
    for (let i = nStr.length; i > 0; i -= 3) {
        let a = (i - 3 < 0) ? 0 : i - 3;
        str = nStr.slice(a, i) + '.' + str; // Thêm dấu "." phân cách từng nhóm 3 số
    }
    str = str.slice(0, str.length - 1); // Loại bỏ dấu "." thừa ở cuối
    return str;
  },

  inputPrice: function() {
    const priceInputs = document.querySelectorAll('input[name="price"]');
    
    priceInputs.forEach(input => {
        
        if (input.value) {
            input.value = TN.addCommas(input.value);
        }
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); 
            value = TN.addCommas(value);
            e.target.value = value;
        });
    });
},


  setUpSelectMultiple : function(callback){
    if(('.selectVariant').length){
        let count = $('.selectVariant').length
        $('.selectVariant').each(function(){
            let _this = $(this)
            let attributeCatalogueId = _this.attr('data-catid')

            if(attribute != ''){
                $.get('ajax/attribute/loadAttribute', {
                    attribute: attribute,
                    attributeCatalogueId: attributeCatalogueId
                }, function(json) {
                    if(json.items != 'undefined' && json.items.length){
                        _this.empty();

                        // Thêm các option mới vào
                        for(let i = 0; i < json.items.length; i++) {
                            var option = new Option(json.items[i].text, json.items[i].id, true, true);
                            _this.append(option).trigger('change');
                        }
                    }
                    if(--count === 0 && callback){
                      callback()
                    }
                });
            }

            TN.getSelect2(_this);
        })
    }
},
  productVariant: function(){
    let productVariant = JSON.parse(atob(variant))
    console.log(productVariant);
    const findIndexVariantBySku = (sku)=> productVariant.sku.findIndex((item) =>item === sku)
    
    $('.variant-row').each(function (index, value) {
      let _this = $(this);
      let match = _this.attr('class').match(/tr-variant-(\d+-\d+)/);
      let variantKey = match ? match[1] : null;
      let dataIndex = productVariant.sku.findIndex(sku =>sku.includes(variantKey))
     
      
      if(dataIndex !== -1){
        let inputHiddenFields = [
          { name: 'variant[quantity][]', class: 'variant_quantity', value: productVariant.quantity[dataIndex] },
          { name: 'variant[sku][]', class: 'variant_sku', value: productVariant.sku[dataIndex] },
          { name: 'variant[price][]', class: 'variant_price', value: productVariant.price[dataIndex] },
          { name: 'variant[barcode][]', class: 'variant_barcode', value: productVariant.barcode[dataIndex] },
          { name: 'variant[file_name][]', class: 'variant_filename', value: productVariant.file_name[dataIndex] },
          { name: 'variant[file_url][]', class: 'variant_fileurl', value: productVariant.file_url[dataIndex] },
          { name: 'variant[album][]', class: 'variant_album', value: productVariant.album[dataIndex] }
      ];
   
      for (let i = 0; i < inputHiddenFields.length; i++) {
          _this.find('input[name="' + inputHiddenFields[i].name + '"]').val((inputHiddenFields[i].value) ? (inputHiddenFields[i].value) : 0);
      }
      let album  =  productVariant.album[dataIndex]
      
      
      let variantImg = (album) ? album.split(',')[0] :'userfiles\\image\\languages\\no-img.png'
      _this.find('.td-quantity').html((productVariant.quantity[dataIndex]) ? TN.addCommas(productVariant.quantity[dataIndex]) : 0)
      _this.find('.td-price').html((productVariant.price[dataIndex]) ? TN.addCommas(productVariant.price[dataIndex]) : 0)
      _this.find('.td-sku').html((productVariant.sku[dataIndex]) ? (productVariant.sku[dataIndex]) : 0)

      _this.find('td img.imgSrc').attr('src', variantImg);

      }
      
    



  });
  
  },

};

 

  $(document).ready(function () {
    TN.setupOptions(); 
    TN.setupCheckbox(); 
    TN.setupAddVariant(); 
    TN.setupRemoveAttribute(); 
    TN.chooseVariantGroup(); 
    TN.createProductVariant();
    TN.variantAlbum();
    TN.sortImg();
    TN.removePicture();
    TN.switchChange();
    TN.updateVariant()
    TN.cancelVariantUpdate()
    TN.saveVariantUpdate()
    TN.addCommas()
    TN.inputPrice()
    TN.setUpSelectMultiple(
      ()=> {
        TN.productVariant()

      }
    )

  });
  document.addEventListener('DOMContentLoaded', function() {
    TN.inputPrice();
});


})(jQuery);
