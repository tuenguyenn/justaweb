(function($) {
    "use strict";
    var HT = {};
    var _token =$('meta[name="csrf-token"]').attr('content')

    HT.switchery = () => {
        $('.js-switch').each(function() {
            if (!$(this).data('switchery')) {
                var switchery = new Switchery(this, { color: '#1AB394' ,size: 'tiny'});
                $(this).data('switchery', switchery);
            }
        });
    };

    HT.changeSTT = () => {
        $(document).on('change', '.js-switch.status', function(e) {
            let _this =$(this)
            let option ={
                'value':_this.val(),
                'modelId':_this.attr('data-modelId'),
                'model':_this.attr('data-model'),
                'field' : _this.attr('data-field'),
                '_token': _token
            }
            $.ajax({
                type: "POST",
                url: "ajax/status/changeStatus", 
                data: option,
                dataType: 'json',
                success: function(res) {  
                  
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Lỗi:' + textStatus + ' ' + errorThrown);
                }

            });
            e.preventDefault()

        });
    };
   
    HT.checkAll = () => {
        if ($('#checkAll').length) {
            $(document).on('click', '#checkAll', function () {
                let isChecked = $(this).prop('checked'); 
                $('.checkboxItem').prop('checked',isChecked)
                $('.checkboxItem').each(function () {
                    let _this =$(this)
                    if (_this.prop('checked')) {
                        _this.closest('tr').addClass('active-bg'); 
                        $('.ibox-tools').removeClass('d-none')
                    } else {
                        _this.closest('tr').removeClass('active-bg'); 
                        $('.ibox-tools').addClass('d-none')
                    }
                });
            });
        }
    };
    HT.checkBoxItem = () => {
        if (!$('.checkboxItem').length) return;
    
        $(document).on('click', '.checkboxItem', function () {
            let _this = $(this);
            let isChecked = _this.prop('checked');
    
            _this.closest('tr').toggleClass('active-bg', isChecked);
    
            let totalChecked = $('.checkboxItem:checked').length;
            let totalCheckboxes = $('.checkboxItem').length;
    
            $('#checkAll').prop('checked', totalChecked === totalCheckboxes);
    
            $('.ibox-tools').toggleClass('d-none', totalChecked === 0);
        });
    };
    
    HT.changeSttAll = () => {
        if ($('.changeAllStatus').length) {
            $(document).on('click', '.changeAllStatus', function (e) {
                let _this = $(this);
                let ids = [];
    
                $('.checkboxItem').each(function () {
                    if ($(this).prop('checked')) {
                        ids.push($(this).val()); 
                    }
                });
    
                let option = {
                    'value': _this.attr('data-value'),
                    'id': ids,
                    'model': _this.attr('data-model'),
                    'field': _this.attr('data-field'),
                    '_token': _token
                };
                e.preventDefault();
    
                $.ajax({
                    type: "POST",
                    url: "ajax/status/changeAllStatus",
                    data: option,
                    dataType: 'json',
                    success: function (res) {
                        if (res.flag === true) {
                                for(let i=0;i<ids.length;i++){
                                    if(option.value ==2){
                                        let cssActive1 ='background-color: rgb(26, 179, 148);border-color: rgb(26, 179, 148); box-shadow: rgb(26, 179, 148) 0px 0px 0px 16px inset; transition: border 0.4s ease 0s, box-shadow 0.4s ease 0s, background-color 1.2s ease 0s;'
                                        let cssActive2 ='left: 20px; background-color: rgb(255, 255, 255); transition: background-color 0.4s ease 0s, left 0.2s ease 0s;'
                                        $('.js-switch-'+ids[i]).find('span.switchery').attr('style',cssActive1).find('small').attr('style',cssActive2)
                                    }
                                    else if(option.value==1){
                                        let cssUnActive1 ='box-shadow: rgb(223, 223, 223) 0px 0px 0px 0px inset; border-color: rgb(223, 223, 223); background-color: rgb(255, 255, 255); transition: border 0.4s ease 0s, box-shadow 0.4s ease 0s;'
                                        let cssUnActive2 ='left: 0px; transition: background-color 0.4s ease 0s, left 0.2s ease 0s;'
                                        $('.js-switch-'+ids[i]).find('span.switchery').attr('style',cssUnActive1).find('small').attr('style',cssUnActive2)

                                    
                                    }
                            }
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log('Lỗi:' + textStatus + ' ' + errorThrown);
                    }
                });
            });
        }
    };
    HT.niceSelect =()=>{
        $('.niceSelect').niceSelect();
    }
   
    HT.sortui =()=>{
        $("#sortable").sortable();
        $("#sortable").disableSelection();

    }
    HT.intInput= (nStr)=>{
          
        nStr = String(nStr);
        nStr = nStr.replace(/\.|,/gi, ""); // Loại bỏ dấu "." hoặc ","
        let str = '';
        for (let i = nStr.length; i > 0; i -= 3) {
            let a = (i - 3 < 0) ? 0 : i - 3;
            str = nStr.slice(a, i) + '.' + str; // Thêm dấu "." phân cách từng nhóm 3 số
        }
        str = str.slice(0, str.length - 1); // Loại bỏ dấu "." thừa ở cuối
        return str;
    
    }
    
    
    HT.setUpDateRangePicker=()=>{
        if($('.rangepicker').length>0){
            $('.rangepicker').daterangepicker()
        }
    }
    $(document).ready(function() {
        HT.switchery(); 
        HT.changeSTT(); 
        HT.checkAll(); 
        HT.checkBoxItem();
        HT.changeSttAll();
        HT.niceSelect();
        HT.sortui();
        HT.intInput();
        HT.setUpDateRangePicker();
      
    });
})(jQuery);
