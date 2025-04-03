(function($) {
    "use strict";
    var HT = {};
    HT.getLocation = () => {
        $(document).on('change', '.location', function() {
            let _this = $(this);
            console.log(123);
            
            let option = {
                'data': {
                    'location_id': _this.val(),
                },
                'target': _this.attr('data-target')  
            };
            

            HT.sendDataToGetLocation(option);
        });
    };

    HT.loadCity = () => {
        if (oldProvinceId != '') {  
            $(".province").val(oldProvinceId).trigger('change');  
        }
    }

    HT.sendDataToGetLocation = (option) => {
        $.ajax({
            type: "GET",
            url: "ajax/location/getLocation",  
            data: option,
            dataType: 'json',
            success: function(res) {  
                $('.' + option.target).html(res.html);
                if(district_id != '' && option.target == "districts"){
                    $('.districts').val(oldDistrictId).trigger('change');  
                }
                if(ward_id != '' && option.target == "wards"){
                    $('.wards').val(oldWardId).trigger('change');  
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Lỗi:' + textStatus + ' ' + errorThrown);
            }
        });
    };
    HT.setupSelect2 = () => {
        if($('.setupSelect2').length){
           $('.setupSelect2').select2();
       }
     }
     
    $(document).ready(function() {
        HT.getLocation();  
        HT.loadCity();  
        HT.setupSelect2();

    });
})(jQuery);
