(function($) {
   "use strict";
   var TN = {}; // Khai báo là 1 đối tượng
   var timer = null
   var _token = $('meta[name="csrf-token"]').attr('content');

/* MAIN VARIABLE */

   var   $window = $(window),
         $document = $(document);


   // FUNCTION DECLARGE
   $.fn.elExists = function() {
     return this.length > 0;
   };


   TN.addCart = () => {
      $(document).on('click', '.addToCart', function(e){
         e.preventDefault()
         let _this = $(this)
         let id = _this.attr('data-id')
         let quantity = $('.quantity-text').val()
         let price = $('.price-sale').text().replace(/,/g, '');
         price = parseInt(price.replace(/\D/g, ''), 10);
         var imageSrc = $("#product-image").attr("src") ?? ' ';
         let canonical = $('input[name=canonical-product]').val();
         

         let price_original = $('.price-old').text().replace(/,/g, '') ||$('.price-sale').text().replace(/,/g, '');
         price_original = parseInt(price_original.replace(/\D/g, ''), 10);
      
         
         



         if(typeof quantity === 'undefined'){
            quantity = 1
         }
         
         let attribute_id = []
         $('.attribute-value .choose-attribute').each(function(){
            let _this = $(this)
            if(_this.hasClass('active')){
               attribute_id.push(_this.attr('data-attributeid'))
            }
         })

         let option = {
            id : id,
            quantity: quantity,
            attribute_id: attribute_id,
            price: price,
            image: imageSrc,
            price_original: price_original,
            canonical: canonical,

            _token: $('meta[name="csrf-token"]').attr('content')
         }
         
         $.ajax({
				url: 'ajax/cart/create', 
				type: 'POST', 
				data: option, 
				dataType: 'json', 
				beforeSend: function() {
					
				},
				success: function(res) {
               toastr.clear()
					if(res.code === 10){
                  toastr.success(res.messages, 'Thông báo từ hệ thống!')
               }else{
                  toastr.error('Có vấn đề xảy ra! Hãy thử lại', 'Thông báo từ hệ thống!')
               }
				},
			});


      })
   }
  


   TN.changeQuantity = () => {
      $(document).on('click', '.btn-qty', function(){
         let _this = $(this)
         let qtyElement = _this.siblings('.input-qty')
         let qty = qtyElement.val()
         let newQty = (_this.hasClass('minus')) ? parseInt(qty) - 1 : parseInt(qty) + 1
         newQty = (newQty < 1) ? 1 : newQty
         qtyElement.val(newQty)

         let option = {
            qty: newQty,
            rowId: _this.siblings('.rowId').val(),
            _token: _token
         }

         TN.handleUpdateCart(_this, option)
      })
   }

   TN.changeQuantityInput = () => {
      $(document).on('blur', '.input-qty', function(){
         let _this = $(this);
         let qty = parseInt(_this.val());
   
         // Nếu không phải số hoặc số nhỏ hơn hoặc bằng 0
         if (isNaN(qty) || qty <= 0) {
            toastr.error('Vui lòng nhập số lượng hợp lệ', 'Thông báo từ hệ thống!');
            _this.val(1); // Đặt lại giá trị mặc định là 1
            return false;
         }
   
         let option = {
            qty: qty,
            rowId: _this.siblings('.rowId').val(),
            _token: _token
         };
   
         TN.handleUpdateCart(_this, option);
      });
   };
   

   TN.handleUpdateCart = (_this, option) => {
      $.ajax({
         url: 'ajax/cart/update', 
         type: 'POST', 
         data: option, 
         dataType: 'json', 
         beforeSend: function() {
            
         },
         success: function(res) {
            toastr.clear()
            if(res.code === 10){
              
               
               TN.checkPromotion(res)
               TN.changeMinyCartQuantity(res)
               TN.changeMinyQuantityItem(_this, option)
               TN.changeCartItemSubTotal(_this, res)
               TN.changeCartTotal(res)
               toastr.success(res.messages, 'Thông báo từ hệ thống!')
            }else{
               toastr.error('Có vấn đề xảy ra! Hãy thử lại', 'Thông báo từ hệ thống!')
            }
         },
      });
   }

   TN.changeMinyQuantityItem = (item, option) => {
      item.parents('.cart-item').find('.cart-item-number').html(option.qty)
   }

   TN.changeCartItemSubTotal = (item, res) => {
      item.parents('.cart-item-info').find('.cart-price-sale').html(addCommas(res.response.productSubtotal)+'đ')
   }

   TN.changeMinyCartQuantity = (res) => {
      $('#cartTotalItem').html(res.response.count)  
   }

   TN.changeCartTotal = (res) => {
      $('.cart-total').html( addCommas(parseInt(res.response.total))+'đ')
      $('input[name=totalPrice]').val(res.response.total)
      
   }
   
   TN.popUpDelete = () => {
      let deleteRowId = null;
      let deleteElement = null;
  
      $(document).on('click', '.cart-item-remove', function () {
          deleteRowId = $(this).attr('data-row-id'); // Lưu rowId
          deleteElement = $(this); // Lưu phần tử cần xóa
  
          let modal = $('#confirm-delete');
          modal.removeClass('uk-hidden'); // Bỏ class uk-hidden nếu có
          UIkit.modal(modal).show(); // Hiển thị modal
      });
  
      // Xử lý khi bấm nút xác nhận xóa (gán sự kiện chỉ 1 lần)
      $('#confirm-delete-btn').off('click').on('click', function () {
          if (deleteRowId) {
              TN.removeCartItem(deleteRowId, deleteElement);
              UIkit.modal('#confirm-delete').hide(); // Đóng modal sau khi xóa
          }
      });
  };
  
  TN.removeCartItem = (deleteRowId, deleteElement) => {
      $.ajax({
          url: 'ajax/cart/delete',
          type: 'POST',
          data: {
              rowId: deleteRowId,
              _token: _token
          },
          dataType: 'json',
          beforeSend: function () {
              // Hiển thị loading nếu cần
          },
          success: function (res) {
              toastr.clear();
              if (res.code === 10) {
                  TN.checkPromotion(res)
                  TN.changeMinyCartQuantity(res);
                  TN.changeCartTotal(res);
                  TN.removeCartItemRow(deleteElement);
                  toastr.success(res.messages, 'Thông báo từ hệ thống!');
              } else {
                  toastr.error('Có vấn đề xảy ra! Hãy thử lại', 'Thông báo từ hệ thống!');
              }
          },
          error: function () {
              toastr.error('Có lỗi xảy ra, vui lòng thử lại!', 'Thông báo từ hệ thống!');
          }
      });
  };
  
  
   TN.removeCartItemRow = (_this) => {
      _this.parents('.cart-item').remove()
   }

   TN.setupSelect2 = () => {
      if($('.setupSelect2').length){
         $('.setupSelect2').select2();
     }
   }
   
	TN.niceSelect = () => {
		if($('.nice-select').length){
			$('.nice-select').niceSelect();
		}
		
	}
   TN.caculatePromotion = () => {
      $(document).on('click', '.voucher-item', function () {
        
          let _this = $(this);
          if (_this.hasClass('invalid')) {
            return;
        }
          if (_this.hasClass('active')) {
            _this.removeClass('active');
            let total = parseFloat($('input[name=totalPrice]').val()) || 0;
            $('.cart-total').text(total.toLocaleString('vi-VN') + ' VNĐ'); 
            $('input[name=promotionName]').val('')

            $('.discount-value').text('-0 VNĐ'); 
            $('input[name=discount]').val(0)
            return;
        }

          $('.voucher-item').removeClass('active')
          _this.addClass('active')
          let discountType = _this.attr('data-promotion-type'); 
          let discountValue = parseFloat(_this.attr('data-promotion-discount')) || 0; 
            let total =  $('input[name=totalPrice]').val()
          let discountAmount = 0; 
          let promotionName = _this.attr('data-promotion-name');
          
          
  
          if (total > 0) {
              if (discountType === 'cash') {
                  discountAmount = Math.min(total, discountValue);
                  total -= discountAmount;
              } else if (discountType === 'percent') {
                  discountAmount = total * (discountValue / 100);
                  total -= discountAmount;
              }
          }
          console.log(promotionName);
          
          $('input[name=promotionName]').val(promotionName)

         $('input[name=discount]').val(discountAmount)
          $('.cart-total').text(total.toLocaleString('vi-VN') + ' VNĐ'); 
          $('.discount-value').text('-' + discountAmount.toLocaleString('vi-VN') + ' VNĐ'); 
      });
  };
  
  TN.checkPromotion = (res) => {
   let total = parseFloat(res.response.total) || 0;


   $('.voucher-item').each(function () {
       let amount = parseFloat($(this).attr('data-amount')) || 0; 
       $(this).removeClass('active');
       if (total < amount) {  
         $('input[name=promotionName]').val('')
          $('.cart-total').text(0); 
          $('.discount-value').text('-0 VNĐ'); 
          $('input[name=discount]').val(0)

           $(this).addClass('invalid'); 

       } else {
           $(this).removeClass('invalid'); 
       }
   });
};

  

   // Document ready functions
   $document.ready(function() {
      TN.addCart()
      TN.setupSelect2()
      TN.popUpDelete()
      TN.changeQuantity()
      TN.changeQuantityInput()
      TN.caculatePromotion()
    
      
   });

})(jQuery);
addCommas = (nStr) => { 
   nStr = String(nStr);
   nStr = nStr.replace(/\./gi, "");
   let str ='';
   for (let i = nStr.length; i > 0; i -= 3){
       let a = ( (i-3) < 0 ) ? 0 : (i-3);
       str= nStr.slice(a,i) + '.' + str;
   }
   str= str.slice(0,str.length-1);
   return str;
}
