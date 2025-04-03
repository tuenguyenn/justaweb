(function($) {
    "use strict";
    var TN = {};
    var _token =$('meta[name="csrf-token"]').attr('content')

    TN.updateField =()=>{
        $(document).on('click','.updateField',function(){
            let _this = $(this)
            let option = {
                payload:{
                    [_this.attr('data-field')] : _this.attr('data-confirm')
                },
                orderId : $('.orderId').val(),
                _token : _token
            }
            
            $.ajax({
                type: "POST",
                url: "ajax/order/updateField",  
                data: option,
                dataType: 'json',
                success: function(res) {  
                    toastr.clear()
                  

                    if(res.code == true){
                        TN.updateTracking(_this.attr('data-field'),_this.attr('data-confirm'))
                        TN.updateOrderStatus(res.response,_this.attr('data-field'),_this.attr('data-confirm'))
                        
                        toastr.success(res.messages)
                    }
                    
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Lỗi:' + textStatus + ' ' + errorThrown);
                }
            });
           
            
        })
    }
    TN.updateOrderStatus = (order, field, status) => {
        let image = '';
        let message = '';
        let nextField = ''; 
        let nextStatus = ''; 
        let nextButtonText = '';
    
        if (field === 'confirm') {
            switch (status) {
                case 'confirm':
                    image ='userfiles/image/icons8-success.svg'
                    message = 'ĐƠN HÀNG ĐÃ XÁC NHẬN - CHƯA GIAO';
                    nextField = 'delivery';
                    nextStatus = 'processing';
                    nextButtonText = 'Giao hàng';
                    break;
               
               
                default:
                    message = 'TRẠNG THÁI KHÔNG HỢP LỆ';
            }
        } else if (field === 'delivery') {
            switch (status) {
              
                case 'processing':
                  
                    image = 'userfiles/image/on-deli1.jpg'
                    message = 'ĐƠN HÀNG ĐANG GIAO';
                    nextField = 'delivery';
                    nextStatus = 'success';
                    nextButtonText = 'Hoàn tất giao hàng';
                    break;
                case 'success':
                    image = 'userfiles/image/delisuccess.jpg'
                    message = 'ĐƠN HÀNG ĐÃ GIAO THÀNH CÔNG';
                    nextField = ''; 
                    nextStatus = ''; 
                    nextButtonText = '';
                    break;
                default:
                    message = 'TRẠNG THÁI KHÔNG HỢP LỆ';
            }
        }
    
        
        $('.confirm-box').find('img').attr('src', BASE_URL + image);
        $('.isConfirm').html(message);
    
        if (nextField && nextStatus) {
            $('.updateField')
                .attr('data-field', nextField)
                .attr('data-confirm', nextStatus)
                .text(nextButtonText);
        } else {
            $('.updateField').hide(); // Ẩn nút nếu không có hành động tiếp theo
        }
    };

    TN.updateTracking =(field,status)=>{
       

        

        if (field === 'confirm') {
            switch (status) {
                case 'confirm':
                    $('.confirm-tracking').addClass('completed')
                    $('.timeline-line').addClass('confirm-completed')
                    $('.confirm-time').html(moment().format('HH:mm DD-MM-YYYY'));
                    
                    break;

                default:
                    message = 'TRẠNG THÁI KHÔNG HỢP LỆ';
            }
        } else if (field === 'delivery') {
            switch (status) {
              
                case 'processing':
                    $('.delivery-tracking').addClass('completed')
                    $('.timeline-line').addClass('delivery-completed')
                    $('.delivery-time').html(moment().format('HH:mm DD-MM-YYYY'));

                   
                    break;
                case 'success':
                    $('.success-tracking').addClass('completed')
                    $('.timeline-line').addClass('success-completed')
                    $('.success-time').html(moment().format('HH:mm DD-MM-YYYY'));
                    break;
                default:
                    message = 'TRẠNG THÁI KHÔNG HỢP LỆ';
            }
        }
    
    }
    
    $(document).ready(function() {
      TN.updateField();

    });
})(jQuery);
