(function($) {
    "use strict";

    var TN = {};
    var _token = $('meta[name="csrf-token"]').attr('content');
    TN.findProductModal = () => {
        $('.findProduct').on('click', function() {
            $('#findProductPopUp').css('display', 'block');
        });

        $('.close-modal, .btn-cancel').on('click', function() {
            $('#findProductPopUp').css('display', 'none');
        });

      
    };

    TN.getCurrentDateTimeUTC7 = () => {
        const now = new Date();

        const utc7Offset = 2 * 60;
        const localOffset = now.getTimezoneOffset(); 
        const utc7Time = new Date(now.getTime() + (utc7Offset + localOffset) * 60 * 1000);

        return utc7Time.toISOString().slice(0, 16);
    };

    TN.setDefaultDateTimeUTC7 = () => {
        let startDate = $('input[name=startDate]');
        const formattedDateTime = TN.getCurrentDateTimeUTC7();
        if (startDate.val() == '') {
           startDate.val(formattedDateTime);
        }

    };

    
    TN.promotionNeverEnd = () => {
        $(document).on('change', '#no-end-date', function() {
            const isChecked = $(this).is(':checked');
            const $endDateInput = $('#end-date');

            if (isChecked) {
                $endDateInput.prop('disabled', true).val('');
            } else {
                $endDateInput.prop('disabled', false).val(TN.getCurrentDateTimeUTC7());
            }
        });
    };

    TN.validateDateRange = () => {
        $(document).on('blur', '#end-date', function() {
            const startDateValue = $('#start-date').val();
            const endDateValue = $(this).val();
    
            if (startDateValue && endDateValue) {
                const startDate = new Date(startDateValue);
                const endDate = new Date(endDateValue);
    
                if (endDate < startDate) {
                    setTimeout(() => {
                        $('.error-date').removeClass('d-none')
                        $("input[name='endDate']").addClass('input-error')
                        $(this).val(TN.getCurrentDateTimeUTC7());
                        $(this).attr('data-invalid', 'true'); // Đánh dấu là không hợp lệ
                    }, 100);
                } else {
                    $("input[name='endDate']").removeClass('input-error')
                    $('.error-date').addClass('d-none')
                    $(this).removeAttr('data-invalid'); // Xóa đánh dấu lỗi
                }
            }
        });
    
        // Ngăn chặn gửi form nếu có lỗi
        $('form').on('submit', function(event) {
            if ($("input[name='endDate']").attr('data-invalid')) {
                event.preventDefault(); // Chặn gửi form
                alert('Ngày kết thúc không hợp lệ! Vui lòng kiểm tra lại.');
            }
        });
    };
    
    
    TN.promotionSource =()=>{
        $(document).on('click','.chooseSource',function(){
            let _this= $(this)
            let flag = (_this.attr('id') =='apply-all') ? true :false;
            if(flag){
                _this.parents('.form-section').find('.source-wrapper').remove()
            }else{
                 
                $.ajax({
                    type: "GET",
                    url: "ajax/source/getAllSource",
                  
                    success: function (res) {
                        if(!$('.source-wrapper').length){
                            let sourceHtml = TN.renderPromotionSource(res.data).prop('outerHTML')
                            _this.parents('.form-section').append(sourceHtml)
                            TN.setUpMultipleSelect2()
                        }    
                    }
                })
            }
        })
    }
    TN.chooserCustomerCondition = () => {
        $(document).on('click', '.chooseApply', function (e) {
            let _this = $(this);
            let flag = _this.attr('id') === 'allApply';
            
            
    
            if (flag) {
                _this.parents('.form-section').find('.apply-wrapper').remove();
            } else {
               
                if (_this.parents('.form-section').find('.apply-wrapper').length === 0) {
                    let customerData = [
                        {
                            id: 'staff_take_care_customer',
                            name: 'Nhân viên chăm sóc khách hàng',
                        },
                        {
                            id: 'customer_group',
                            name: 'Nhóm khách hàng',
                        },
                        {
                            id: 'customer_gender',
                            name: 'Giới tính khách hàng',
                        },
                        {
                            id: 'customer_birthday',
                            name: 'Ngày sinh',
                        },
                    ];
                    let sourceCustomerHtml = TN.renderCustomerSource(customerData).prop('outerHTML');
                    _this.parents('.form-section').append(sourceCustomerHtml);
                    TN.setUpMultipleSelect2();
                }
            }
        });
    };
    
    TN.renderPromotionSource = (sourceData) => {
        
        let wrapper = $('<div>').addClass('source-wrapper'); 
        let select = $('<select>').addClass('multipleSelect2').attr('name', 'sourceValue[]').attr('multiple', true); 
        if (sourceData.length) {
            for (let i = 0; i < sourceData.length; i++) { 
                let option = $('<option>').attr('value', sourceData[i].id).text(sourceData[i].name); 
                select.append(option);
            }
            wrapper.append(select);
        }
    
        return wrapper;
    };
    TN.renderCustomerSource = (customerData) => {
        
        let wrapper = $('<div>').addClass('apply-wrapper'); 
        let select = $('<select>').addClass('multipleSelect2 conditionItem').attr('name', 'applyValue[]').attr('multiple', true); 
        let wrapperConditionItem = $('<div>').addClass('wrapper-condition')
        if (customerData.length) {
            for (let i = 0; i < customerData.length; i++) { 
                let option = $('<option>').attr('value', customerData[i].id).text(customerData[i].name); 
                select.append(option);
            }
            wrapper.append(select);
            wrapper.append(wrapperConditionItem);
        }
    
        return wrapper;
    };
    TN.chooseCustomerItem =()=>{
        $(document).on('change','.conditionItem',function(){
            let _this= $(this)
            let condition = {
                value : _this.val(),
                label : _this.select2('data')
            }
            
            $('.wrapperConditionItem').each(function(){
                let _item = $(this)
                let itemClass = _item.attr('class').split(' ')[2]
                if(condition.value.includes(itemClass) == false){
                    _item.remove()
                }
                
            })
            
            for(let i =0;i<condition.value.length;i++){
                let value = condition.value[i]
                if(!$('.wrapper-condition').find('.'+value).elExist()){
                    let html=''
                    html = TN.createConditionItem(value, condition.label[i].text)
                   
                }
               
            }
            
        })
    }

    $.fn.elExist = function () {
        return this.length > 0;
    };
    
    TN.createConditionLabel = (label,value) =>{
        // let deleteGroup = $('<div>').addClass('deleteGroup').attr('data-condition-item',value).html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="M8 12.052c1.995 0 3.5-1.505 3.5-3.5s-1.505-3.5-3.5-3.5-3.5 1.505-3.5 3.5 1.505 3.5 3.5 3.5zM9 13H7c-2.757 0-5 2.243-5 5v1h12v-1c0-2.757-2.243-5-5-5zm11.293-4.707L18 10.586l-2.293-2.293-1.414 1.414 2.292 2.292-2.293 2.293 1.414 1.414 2.293-2.293 2.294 2.294 1.414-1.414L19.414 12l2.293-2.293z"></path></svg>')
        let conditionLable =$('<div>').addClass('conditionLabel text-primary').text(label)
        let flex = $('<div>').addClass('d-flex justify-content-between align-items-center mb-2')
        let wrapperBox = $('<div>').addClass(' bg-white  ')

        flex.append(conditionLable)
        wrapperBox.append(flex)
        return wrapperBox.prop('outerHTML')
        
     
    }
    TN.createConditionItem = (value, label) => {
        if ($('.wrapper-condition').find('.' + value).elExist() === false){
        $.ajax({
            type: "GET",
            url: "ajax/status/getPromotionContitionValue",
            data: { value: value },
            success: function (res) {
                let optionData = res.data
                let conditionItem = $('<div>').addClass('wrapperConditionItem mt-2 ' + value);
                
                let inputHiddenCondition = $('.condition_input_' + value);
                let inputHiddenConditionValue = [];
                if (inputHiddenCondition.length) {
                    inputHiddenConditionValue = JSON.parse(inputHiddenCondition.val() || "[]"); 
                   
                }
                let select = $('<select>')
                    .addClass('multipleSelect2 objectItem')
                    .attr('name', value + "[]")
                    .attr('multiple', true);
    
                for(let i = 0; i<optionData.length;i++){
                    let option = $('<option>').attr('value', optionData[i].id).text(optionData[i].text);
                    select.append(option); 
                };
                
                
    
                select.val(inputHiddenConditionValue).trigger('change');
    
                const conditionLabel = TN.createConditionLabel(label, value);
                conditionItem.html(conditionLabel);
                conditionItem.append(select);
                $('.wrapper-condition').append(conditionItem)
                TN.setUpMultipleSelect2();
            }
        });
    }
    };
    
    
    
    
   
    
    
    TN.BtnJs100 = () => {
        $(document).on('click', '.btn-js-100', function () {
            let _btn = $(this);
    
            let $tr = $('<tr>').addClass('order_amount_range').append(
                $('<td>').append(
                    $('<input>', {
                        type: 'text',
                        class: 'form-control bg-white',
                        name: 'promotion_order_amount_range[amountFrom][]',
                        value: 0
                    }).on('input', function () {
                        this.value = TN.intInput(this.value);
                    })
                )
            );
    
            let $discountId = $('<td>').addClass('discountType').append(
                $('<div>', {
                    class: 'd-flex',
                }).append(
                    $('<input>', {
                        type: 'text',
                        class: 'bg-white col-lg-9 form-control',
                        name: 'promotion_order_amount_range[amountValue][]',
                        value: 0
                    }).on('input', function () {
                        this.value = TN.intInput(this.value);
                    }),
                    $('<select>', {
                        class: 'form-select',
                        name: 'promotion_order_amount_range[amountType][]'
                    }).append(
                        $('<option>', { value: 'cash', text: 'VND' }),
                        $('<option>', { value: 'percent', text: '%' })
                    )
                )
            );
    
            $tr.append($discountId);
    
            $tr.append(
                $('<td>').addClass('text-center').append(
                    $('<button>', {
                        class: 'delete-some-item delete-order-amount-condition btn btn-light',
                        type: 'button',
                    }).append(
                        $('<i>', {
                            class: 'fa fa-trash'
                        })
                    )
                )
            );
    
            $('.order_amount_range table tbody').append($tr);
        });
    };
    
    TN.intInput = (nStr) => {
        nStr = String(nStr).replace(/[^0-9]/g, "");
        nStr = nStr.replace(/^0+/, "");
        if (!nStr) return "0";
        
        let str = "";
        for (let i = nStr.length; i > 0; i -= 3) {
          let a = (i - 3 < 0) ? 0 : i - 3;
          str = nStr.slice(a, i) + "." + str;
        }
        return str.slice(0, -1);
      };
      
    

    TN.deleteDiscountCondition=()=>{
          
          $(document).on('click', '.delete-order-amount-condition', function () {
            let _this = $(this)
            _this.parents('tr').remove()
        });
    }

    TN.renderOrderConditionContainer =()=>{
       
        $(document).on('change','.promotionMethod', function(){
            
            let _this = $(this)
            let method = _this.val()
                switch(method)
                {
                    case 'order_amount_range':                      
                        TN.renderOrderAmountRange()
                        break;
                    case 'product_and_quantity':
                        TN.renderProductAndQuantity()
                        break;
                    // case 'product_quantity_range':
                    //     console.log("this is 3");
                    //     break;  
                    // case 'goods_discount_by_quantity':
                    //     console.log("this is 4");
                    //     break; 
                    default:
                        TN.removePromotionContainer()

                
                }

        })
        let method = $('.preload_promotionMethod').val()
        if(method.length && typeof method !== 'undefined'){
            
            $('.promotionMethod').val(method).trigger('change')
        }
        
    }

    TN.removePromotionContainer =()=>{
        $('.promotion-container').html('')
    }

    TN.renderOrderAmountRange = () => {
        let $tr = ""
        let order_amount_range;
        
        order_amount_range = JSON.parse($('.input_order_amount_range').val()) 
        let method = $('.preload_promotionMethod').val();
       
        
        if(order_amount_range.length == 0 || order_amount_range == null || method != 'order_amount_range'){
            order_amount_range =  {
                amountFrom: ['0'],
                amountValue: ['0'],
                amountType: ['cash']
              };
        }
        
        
        for (let i = 0; i < order_amount_range.amountFrom.length; i++) {
            let amountFrom = order_amount_range.amountFrom[i];
            let amountValue = order_amount_range.amountValue[i];
            let amountType = order_amount_range.amountType[i];
          
            $tr += `<tr>
              <td class="order_amount_from text-left">
                <input 
                  type="text" 
                  name="promotion_order_amount_range[amountFrom][]" 
                  class="form-control bg-white" 
                  value="${amountFrom}" 
                  oninput="formatInputValue(this)"
                >
              </td>
              <td class="text-left discountType">
                <div class="d-flex">
                  <input 
                    type="text" 
                    name="promotion_order_amount_range[amountValue][]" 
                    class="bg-white col-lg-9 form-control" 
                    value="${amountValue}" 
                    oninput="formatInputValue(this)"
                  >
                  <select name="promotion_order_amount_range[amountType][]" class="form-select">
                    <option value="cash" ${amountType === 'cash' ? 'selected' : ''}>VND</option>
                    <option value="percent" ${amountType === 'percent' ? 'selected' : ''}>%</option>
                  </select>
                </div>
              </td>
              <td class="text-center">`;
            
            if(i > 0) {
              $tr += `<button class="delete-some-item delete-order-amount-condition btn btn-light" type="button">
                        <i class="fa fa-trash"></i>
                      </button>`;
            }
            
            $tr += `</td></tr>`;
          }
          
            
            let html = `<div class="col-12 p-3">
              <div class="order_amount_range table-responsive">
                <table class="table table-borderless">
                  <thead class="table">
                    <tr>
                      <th class="text-left">Giá trị từ (VND)</th>
                      <th class="text-left">Chiết khẩu</th>
                      <th class="text-center">Hành động</th>
                    </tr>
                  </thead>
                  <tbody>
                    ${$tr}
                  </tbody>
                </table>
                <a class="btn btn-primary text-white btn-js-100">Thêm điều kiện</a>
              </div>
            </div>`;
            
            $('.promotion-container').html(html);
        

        
        
      };
      
    TN.renderProductAndQuantity = ()=>{
        let selectData = JSON.parse($('.input-product-and-quantity').val())
        let selectHtml = '' 
        let module_type = $('.preload_select-product-and-quantity').val()
        
        let method = $('.preload_promotionMethod').val();
        for (let key in selectData) {
            selectHtml += `<option ${ (module_type.length && typeof module_type !== 'undefined' && module_type == key) ? 'selected' : '' } value="${key}">${selectData[key]}</option>`;
        }
        let preloadData = JSON.parse($('.input_product_and_quantity').val())
       
        
       
        if(preloadData == null || preloadData.length ==0 || method != 'product_and_quantity'){
            preloadData =  {
                quantity: ['1'],
                maxDiscountValue: ['0'],
                discountValue: ['0'],
                discountType: ['cash'],

              };
        }
        
        let html = `<div class="product-and-quantity ps-4 pt-3">
      
      <div class="choose-module">
        <div class="fix-label pb-2">Sản phẩm áp dụng</div>
        <select name="module_type" id="" class="form-select select-product-and-quantity">
        ${selectHtml}
        </select>
      </div>

      <table class="table table-borderless mt-2">
        <thead>
          <tr>
            <th class="text-left" style="width: 350px;">Sản phẩm mua</th>
            <th class="text-left" style="width: 100px;">SL tối thiểu</th>
            <th class="text-left" style="width: 150px;">GH khuyến mãi</th>
            <th class="text-left">Chiết khấu </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <!-- Sản phẩm mua -->
            <td class="text-left">
              <div class="d-flex product-quantity border rounded align-items-start flex-column findProduct ">
                
                
                <!-- Ô tìm kiếm -->
                <div class="boxWrapper ">
                  <div class="boxSearch ms-1 d-flex align-items-center  ">
                    <i class="fa fa-search fs-5 text-primary me-2"></i>
                    <p class="mb-0 text-dark">Tìm kiếm theo tên, mã sản phẩm</p>
                  </div>
                
                  
                  <div class="product-list w-100 px-2 row ms-1 ">
                  </div>
                
                  
                </div>
            
              </div>
            </td>
            <td class="text-left">
              <input 
                type="text" 
                name="product_and_quantity[quantity]" 
                class="form-control bg-white" 
                value="${preloadData.quantity}" 
                oninput="formatInputValue(this)"
              >
            </td>

            <!-- Giới hạn khuyến mãi -->
            <td class="text-left">
              <input 
                type="text" 
                name="product_and_quantity[maxDiscountValue]" 
                class="form-control bg-white" 
                value="${preloadData.maxDiscountValue}" 
                oninput="formatInputValue(this)"
              >
            </td>

            <!-- Chiết khấu -->
            <td class="text-left discountType">
              <div class="d-flex">
                <input 
                  type="text" 
                  name="product_and_quantity[discountValue]" 
                  class="bg-white  form-control" 
                    value="${preloadData.discountValue}" 
                  oninput="formatInputValue(this)"
                  style="width:200px"
                >
                <select name="product_and_quantity[discountType]" class="ms-2 form-select">
                  <option value="cash" ${ (preloadData.discountType == 'cash') ? 'selected' : ''} >VND</option>
                  <option value="percent" ${ (preloadData.discountType == 'percent') ? 'selected' : ''}>%</option>
                </select>
              </div>
            </td>

            <!-- Hành động -->
            <td class="text-center">
              <!-- Add button or action here -->
            </td>
          </tr>
        </tbody>
      </table>
    </div>
        `;
        TN.removePromotionContainer()
            $('.promotion-container').append(html)
            TN.setUpMultipleSelect2()
            TN.findProductModal()
            TN.deleteProductPromotion()

    }
    TN.setUpMultipleSelect2 =()=>{
        $('.multipleSelect2').select2(
          {
        
          placeholder: 'Nhập 2 kí tự để tìm kiếm',
       

     }
    ); 
    },
    TN.setUpAjaxSearch =()=>{
        $('.ajaxSearch').each(function(){
            let _this = $(this)
            let option = {
                model : _this.attr('data-model')

            }
            _this.select2(
                {
                minimumInputLength: 2,
                placeholder: 'Nhập 2 kí tự để tìm kiếm',
                  ajax: {
                      url: 'ajax/status/findPromotionObject',
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
                             results:data.items
                          };
                      },
                      cache: true
                  }
           }
          ); 
        })
       
    },
    TN.loadProduct =(option)=>{
        
        $.ajax({
            type: "GET",
            url: "ajax/product/loadProductPromotion",
            data: option,
            dataType: 'json',
            success: function (res) {
                
                TN.fillToObjectList(res)  
            }
        })
    }
    TN.ProductQuantityLoadProduct =()=>{
        $(document).on('click','.product-quantity',function(e){
            e.preventDefault()
            let option ={
                model : $('.select-product-and-quantity').val(),
                keyword : $('.search-model').val()

            }
            TN.loadProduct(option)
        })
    }
    TN.fillToObjectList =(data)=>{
        switch(data.model){
            case "Product":
                TN.fillProductList(data.objects)
                break;
            case "ProductCatalogue":
                TN.fillProductCatalogueList(data.objects)
                break;
        }
    }
    
    TN.fillProductList=(objects)=>{
        console.log(objects);
        
        let productHtml = '';
        if(objects.data.length){
            let model = $('.select-product-and-quantity').val() 
            for(let i=0;i< objects.data.length;i++){
                let image  = objects.data[i].image
                let name = objects.data[i].variant_name
                let product_variant_id = objects.data[i].product_variant_id
                let uuid = objects.data[i].uuid

                let price = objects.data[i].price
                let product_id = objects.data[i].id
                let sku =objects.data[i].sku
                let inventory =   10000
                let couldSell =   9000
                let classBox = model+'_'+product_id+'_'+product_variant_id
                let isChecked = ($('.product-list .'+ classBox+ '').length) ? true : false
                
                productHtml += `<div class="search-object-item"  
                                    data-productid="${product_id}"  
                                    data-variant-id="${product_variant_id}"
                                    data-name="${name}"
                                    data-uuid="${uuid}">
                               <div class="d-flex justify-content-between">
                                <div class="object-info">
                                    <div class="d-flex">
                                        <input 
                                            type="checkbox" 
                                            name="" 
                                             class="input-checkbox" 
                                             value="${product_id}_${product_variant_id}" 
                                             ${(isChecked) ? 'checked' : ''}>
                                        <span class="img-product">
                                            <img src="${image}" alt="" class="img-fluid">
                                        </span>
                                        <div class="object-name">
                                            <div class="m-0">${name} </div>
                                            <div class="jscode">Mã SP: ${sku}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="object-extra-info">
                                    <div class="price">${TN.intInput(price)}</div>
                                    <div class="object-inventory">
                                        <div class="d-flex">
                                            <span class="text-danger">Tồn kho :</span>
                                            <span class="text-value">${inventory} |</span>
                                            <span class="text-primary"> Có thể bán :</span>
                                            <span class="text-value">${couldSell}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                               
                            </div>
                           
                            
                        `;
            }
        }
        productHtml +=TN.paginationLink(objects.links)
        $('.search-list').html(productHtml)
    }
    TN.fillProductCatalogueList=(objects)=>{
        let html = '';
        if(objects.data.length){
            let model = $('.select-product-and-quantity').val() 
            for(let i=0;i< objects.data.length;i++){
                let name = objects.data[i].name
                let uuid = objects.data[i].uuid
                let id = objects.data[i].product_id
                let classBox = model+'_'+id
                let isChecked = ($('.product-list .'+ classBox+ '').length) ? true : false
                html += `<div class="search-object-item"  
                                    data-productid="${id}"  
                                  
                                    data-name="${name}"
                                    data-uuid ="${uuid}">
                               <div class="d-flex justify-content-between">
                                <div class="object-info">
                                    <div class="d-flex">
                                        <input 
                                            type="checkbox" 
                                            name="" 
                                             class="input-checkbox" 
                                             value="${id}" 
                                             ${(isChecked) ? 'checked' : ''}>
                                       
                                        <div class="object-name">
                                            <div class="m-0">${name} </div>
                                          
                                        </div>
                                    </div>
                                </div>
                               
                            </div>
                               
                            </div>
                           
                            
                        `;
            }
        }
        html +=TN.paginationLink(objects.links)
        $('.search-list').html(html)
    }
    TN.paginationLink = (links) => {
        let html = '';
        if (links.length >3) {
            html += '<nav class="d-flex  justify-content-center pt-2">';
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
    TN.getPaginationMenu =()=>{
        $(document).on('click','.page-link',function(e){
          
              e.preventDefault()
              let _this=$(this)
              let option ={
                  model : $('.select-product-and-quantity').val(),
                  page : _this.text(),
                  keyword : $('.search-model').val()

              }
              TN.loadProduct(option)
      
              
        })
    }
    TN.findProduct =() =>{
        let typingTimer
        let doneTypingInterval =0
        $(document).on('keyup', '.search-model', function () {
           let _this = $(this)
           let keyword = _this.val()

            let option ={
                model : $('.select-product-and-quantity').val(),
                keyword: keyword

            }
           if(keyword.length >=2 || keyword.length ==0){
                clearTimeout(typingTimer)
                typingTimer = setTimeout(function(){
                   

                    TN.loadProduct(option)
                },doneTypingInterval)
           }

        })
    }
    
    var objectChoosed =[]
    TN.chooseProductPromotion = () => {
        $(document).on('click', '.search-object-item', function () {
            
            let model = $('.select-product-and-quantity').val();
            let _this = $(this);
            let isChecked = _this.find('input[type=checkbox]').prop('checked');
            let objectItem = {
                model :model,
                product_id: _this.attr('data-productid'),
                name: _this.attr('data-name'),
                uuid: _this.attr('data-uuid'),
                product_variant_id: _this.attr('data-variant-id'),
            }; 
           
            
            if (isChecked) {
                objectChoosed = objectChoosed.filter(item => {
                    if (item.product_id === objectItem.product_id) {
                        if (objectItem.product_variant_id && item.product_variant_id) {
                            return item.product_variant_id != objectItem.product_variant_id;
                        }
                        if (!objectItem.product_variant_id) {
                            return false;
                        }
                    }
                    return true;
                });
                _this.find('input[type=checkbox]').prop('checked', false);
            } else {
                objectChoosed.push(objectItem);
                _this.find('input[type=checkbox]').prop('checked', true);
            }
    
            
        });
        
    };
    
    
    TN.confirmProductPromotion = () => {
        let preloadData = JSON.parse($('.input_object').val());
       
        
        
        if (preloadData == null || preloadData.length == 0) {
          preloadData = {
            id: [],
            product_variant_id: [],
            uuid : [],
            name: [],
          };
        }
        
        $(document).on('click', '.confirm-promotion-product', function (e) {
          e.preventDefault();
          let html = '';
          let model = $('.select-product-and-quantity').val();
      
          if (objectChoosed.length) {
            objectChoosed.forEach((item) => {
              const product_id = item.product_id;
              const name = item.name;
              const uuid = item.uuid;
              const product_variant_id = item.product_variant_id ?? null;
              let classBox = '';
      
              if (model === 'Product') {
                classBox = model + '_' + product_id + "_" + product_variant_id;
                if ($('.product-list .' + classBox).length === 0) {
                  html += `
                    <div class="goods-item d-flex align-items-center justify-content-between mb-2 col-lg-12 me-2 ${classBox}">
                      <a class="goods-item-name fs-6" title="${name}">${name}</a>
                      <button class="delete-goods-item btn btn-sm" data-model="${model}" data-product="${product_id}"  data-variant-id="${product_variant_id}" data-uuid="${uuid}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 50 50">
                          <path d="M25 2C12.31 2 2 12.31 2 25s10.31 23 23 23 23-10.31 23-23S37.69 2 25 2zm0 2c11.61 0 21 9.39 21 21S36.61 46 25 46 4 36.61 4 25 13.39 4 25 4zm7.99 11.99a1 1 0 00-1.71.3L25 23.59l-6.29-6.29a1 1 0 00-1.71 1.41L23.59 25l-6.29 6.29a1 1 0 101.41 1.41L25 26.41l6.29 6.29a1 1 0 001.41-1.41L26.41 25l6.29-6.29a1 1 0 00-.7-1.71z"/>
                        </svg>
                      </button>
                      <div class="d-none">
                        <input type="hidden" name="object[id][]" value="${product_id}">
                        <input type="hidden" name="object[name][]" value="${name}">
                        <input type="hidden" name="object[uuid][]" value="${uuid}">
                        <input type="hidden" name="object[product_variant_id][]" value="${product_variant_id}">
                      </div>
                    </div>
                  `;
                }
              } else if (model === 'ProductCatalogue') {
                classBox = model + '_' + product_id;
                if ($('.product-list .' + classBox).length === 0) {
                  html += `
                    <div class="goods-item d-flex align-items-center justify-content-between mb-2 col-lg-12 me-2 ${classBox}">
                      <a class="goods-item-name fs-6" title="${name}">${name}</a>
                      <button class="delete-goods-item btn btn-sm" data-model="${model}" data-product="${product_id}"  data-variant-id="${product_variant_id}" data-uuid="${uuid}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 50 50">
                          <path d="M25 2C12.31 2 2 12.31 2 25s10.31 23 23 23 23-10.31 23-23S37.69 2 25 2zm0 2c11.61 0 21 9.39 21 21S36.61 46 25 46 4 36.61 4 25 13.39 4 25 4zm7.99 11.99a1 1 0 00-1.71.3L25 23.59l-6.29-6.29a1 1 0 00-1.71 1.41L23.59 25l-6.29 6.29a1 1 0 101.41 1.41L25 26.41l6.29 6.29a1 1 0 001.41-1.41L26.41 25l6.29-6.29a1 1 0 00-.7-1.71z"/>
                        </svg>
                      </button>
                      <div class="d-none">
                        <input type="hidden" name="object[id][]" value="${product_id}">
                        <input type="hidden" name="object[name][]" value="${name}">
                        <input type="hidden" name="object[product_variant_id][]" value="null">
                      </div>
                    </div>
                  `;
                }
              }
            });
          }
      
          $('#findProductPopUp').css('display', 'none');
          $('.product-list').append(html).show();
         
        
        });
        if (preloadData.id.length > 0) {
            objectChoosed = preloadData.id.map((id, index) => ({
                product_id: id,
                uuid: preloadData.uuid?.[index] ?? 'null',  // Kiểm tra nếu preloadData.uuid là undefined
                product_variant_id: preloadData.product_variant_id?.[index] ?? 'null',  // Tương tự cho product_variant_id
                name: preloadData.name[index],
                model: $('.select-product-and-quantity').val(),
            }));
        
        
      
        $('.confirm-promotion-product').trigger('click');
      }
      };
      
   
    
      TN.deleteProductPromotion = () => {
        $(document).on('click', '.delete-goods-item', function (e) {
            $('#findProductPopUp').css('display', 'none');
            let _btn = $(this);
    
            let uuid = _btn.attr('data-uuid');
            let product_id = _btn.attr('data-product');
    
            objectChoosed = objectChoosed.filter(item => {
                if (uuid === null || uuid === "null" || uuid === undefined) {
                    return item.product_id !== product_id; 
                }
                return item.uuid !== uuid;
            });
    
            _btn.parents('.goods-item').remove();
            $('#findProductPopUp').css('display', 'none');
        });
    };
    
    
    
    
    TN.changeModelPromotionMethod = () => {
        $(document).on('change', '.select-product-and-quantity', function () {
            let userConfirmed = window.confirm("Bạn có chắc chắn muốn thay đổi phương pháp khuyến mãi? Tất cả sản phẩm đã chọn sẽ bị xóa.");
            if (userConfirmed) {
                $('.goods-item').remove();
                objectChoosed = [];
            } else {
                $(this).val($(this).data('previous-value'));
            }
        });
    
        $(document).on('focus', '.select-product-and-quantity', function () {
            $(this).data('previous-value', $(this).val());
        });
    };
    
    
    TN.checkConditionItemSet =()=>{
        let checkedValue = $('.conditionItemSelected').val()
        if(checkedValue.length && $('.conditionItem').length){
            checkedValue = JSON.parse(checkedValue)
            $('.conditionItem').val(checkedValue).trigger('change')
            
        }
    }
   
    TN.ValidateOrderValue = () => {
        $("form").submit(function (event) {
            let isValid = true;
            let errorMessages = [];

    
            $("input[name='promotion_order_amount_range[amountValue][]']").each(function (index) {
                let $amountValueInput = $(this);
                let amountType = $("select[name='promotion_order_amount_range[amountType][]']").eq(index).val();
                let amountValue = parseFloat($amountValueInput.val().replace(/\./g, "").replace(",", ".")) || 0;
                let amountFrom = parseFloat($("input[name='promotion_order_amount_range[amountFrom][]']").eq(index).val().replace(/\./g, "").replace(",", ".")) || 0;
                
                $amountValueInput.removeClass("input-error").next(".error-message").remove();
    
               
    
                if (amountType === "percent" && amountValue >= 100) {
                    errorMessages.push(`Chiết khấu không được quá 100%`);
                    $amountValueInput.addClass("input-error");
                    isValid = false;
                }
            });
    
            if (!isValid) {
                event.preventDefault(); 

            errorMessages.forEach((message) => {
                toastr.error(message, "Lỗi nhập liệu");
            });
            }
        });
    };
    TN.ValidateProductAndQuantity = () => {
        $("form").submit(function (event) {
            let isValid = true;
            let errorMessages = [];
            let $quantity = $("input[name='product_and_quantity[quantity]']")
            let quantity = parseInt($quantity.val()) ;
            let maxDiscountValue = parseFloat($("input[name='product_and_quantity[maxDiscountValue]']").val().replace(/\./g, "")) ;
            let discountValue = parseFloat($("input[name='product_and_quantity[discountValue]']").val().replace(/\./g, "")) ;
            let discountType = $("select[name='product_and_quantity[discountType]']").val();
    
            if (quantity <= 0) {
               
                $quantity.addClass('input-error')
                errorMessages.push("Số lượng tối thiểu phải lớn hơn 0.")
                isValid = false;
            }
    
            if (discountType === "cash") {
                if (discountValue > maxDiscountValue) {
                    
                    errorMessages.push(`Giá trị giảm không được vượt quá ${maxDiscountValue.toLocaleString()} VND.`);
                    isValid = false;
                }
            } else if (discountType === "percent") {
                if (discountValue >= 100) {
                    isValid = false;
                    errorMessages.push("Phần trăm giảm giá không được vượt quá 100%.");
                }
               
            }
    
            if (!isValid) {
                event.preventDefault();
                errorMessages.forEach((message) => {
                    toastr.error(message, "Lỗi nhập liệu");
                });
            }
        });
    };
    
    

    $(document).ready(function() {
        TN.setDefaultDateTimeUTC7();
        TN.promotionNeverEnd();
        TN.validateDateRange();
        TN.promotionSource();
        TN.setUpMultipleSelect2();
        TN.chooserCustomerCondition();
        TN.chooseCustomerItem();
        TN.BtnJs100();
        TN.deleteDiscountCondition();
        TN.renderOrderConditionContainer();
        TN.setUpAjaxSearch();
        TN.findProductModal();
        TN.ProductQuantityLoadProduct();
        TN.getPaginationMenu();
        // TN.deleteCondition()
        TN.findProduct();
        TN.chooseProductPromotion();
        TN.confirmProductPromotion();
        TN.deleteProductPromotion();
        TN.changeModelPromotionMethod();
        TN.checkConditionItemSet();
        TN.ValidateOrderValue();
        TN.ValidateProductAndQuantity();

    });
})(jQuery);
