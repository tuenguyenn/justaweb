(function($) {
	"use strict";
	var TN = {}; // Khai báo là 1 đối tượng
	var timer;
    var _token = $('meta[name="csrf-token"]').attr('content');
    TN.review = () => {
        let productId = null; // Khai báo productId ở phạm vi toàn cục
    
        $(document).on('click', '.reviewBtn', function(){
            productId = $(this).attr('data-id'); 
            $('#review-modal').attr('data-id', productId);
        });
    
        $(document).on('click', '.submit-review', function(){
           
            productId = $('#review-modal').attr('data-id'); 
    
            let option = {
                score: $('.rate:checked').val(),
                description : $('.description').val(),
                reviewable_type: $('.reviewable_type').val(),
                reviewable_id: productId, 
                customer_id : $('.customer_id').val(),
                _token: _token,
                parent_id: $('.review_parent_id').val()
            };
    
            $.ajax({
                url: 'ajax/review/create', 
                type: 'POST', 
                data: option, 
                dataType: 'json', 
                beforeSend: function() {},
                success: function(res) {
                    if(res.code === 10){
                        toastr.success(res.messages, 'Thông báo từ hệ thống!');
                        $('.reviewBtn[data-id="' + productId + '"]').remove();
                        UIkit.modal('#review-modal').hide();
                    } else {
                        toastr.error(res.messages, 'Thông báo từ hệ thống!');
                    }
                },
            });
        });
    }
    

	$(document).ready(function(){
		TN.review()
	});

})(jQuery);

