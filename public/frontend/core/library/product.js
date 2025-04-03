(function($) {
	"use strict";
	var TN = {}; // Khai báo là 1 đối tượng
	var timer;

	
	TN.niceSelect = () => {
		if($('.nice-select').length){
			$('.nice-select').niceSelect();
		}
		
	}
	TN.galleryProduct = ()=>{
		var swiperThumbsEl = document.querySelector(".swiper-container-thumbs");
		var swiperMainEl = document.querySelector(".main-slider");
	
		if (swiperThumbsEl && swiperMainEl) {
			var swiperThumbs = new Swiper(swiperThumbsEl, {
				spaceBetween: 10,
				slidesPerView: 4,
				freeMode: true,
				watchSlidesProgress: true,
			});
	
			var swiperMain = new Swiper(swiperMainEl, {
				spaceBetween: 10,
				navigation: {
					nextEl: ".swiper-button-next",
					prevEl: ".swiper-button-prev",
				},
				pagination: document.querySelector(".swiper-pagination")
					? {
						el: ".swiper-pagination",
						clickable: true,
					}
					: false,
				thumbs: {
					swiper: swiperThumbs,
				},
			});
		}
	}
	TN.selectVariantProduct = () => {
		if ($('.choose-attribute').length) {
			
	
			$(document).on('click', '.choose-attribute', function (e) {
				e.preventDefault();
				let _this = $(this);
	
				_this.closest('.attribute-value').find('.choose-attribute').removeClass('active');
				_this.addClass('active');
	
				_this.closest('.attribute-item').find('span').text(_this.text());
	
				TN.handleAttribute();
			});
		}
	};
	
	TN.handleAttribute = () => {
		let attrId = $('.attribute-value .choose-attribute.active')
			.map(function () {
				return $(this).attr('data-attributeId');
			})
			.get(); // Chuyển sang array
	
		let productId = $('input[name=product_id]').val();
		
		let allSelected = $('.attribute-item').toArray().every(item => 
			$(item).find('.choose-attribute.active').length > 0
		);
	
		if (allSelected) {
			$.ajax({
				type: "GET",
				url: "ajax/product/loadVariant", 
				data: {
					'attribute_id': attrId,
					'productId': productId
				},
				dataType: 'json',
				success: function(res) {  
					TN.setUpVariantGallery(res);
					TN.setUpVariantInfor(res);
					TN.setUpVariantPrice(res.variant);
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.error('Lỗi:', textStatus, errorThrown);
				}
			});
		}
	};
	
	TN.setUpVariantGallery = (res) => {
		let album = res.variant.album.split(',')

		let html = `
			<div class="swiper-container main-slider">
				<div class="swiper-button-next"></div>
				<div class="swiper-button-prev"></div>
				<div class="swiper-wrapper big-pic">
		`;
		
		album.forEach((val, index) => {
			html += `
				<div class="swiper-slide">
					<a href="${val}" class="image img-cover">
						<img ${index === 0 ? 'id="product-image"' : ''} src="${val}" alt="${val}">
					</a>
				</div>
			`;
		});
		
	
		html += `
				</div>
				<div class="swiper-pagination"></div>
			</div>
			<div class="swiper-container swiper-container-thumbs">
				<div class="swiper-wrapper pic-list">
		`;
	
		album.forEach((val) => {
			html += `
				<div class="swiper-slide">
					<span class="image img-cover">
						<img src="${val}" alt="${val}">
					</span>
				</div>
			`;
		});
	
		html += `
				</div>
			</div>
		`;
		if(album.length){
			$('.popup-gallery').html()
			$('.popup-gallery').html(html);
			TN.galleryProduct()
		}
	
		
	};
	TN.setUpVariantInfor =(res)=>{
		let productName = $('input[name=origin-product-name]').val()
		
		
		let productVariantName = productName + ' '+ res.variant.languages[0].pivot.name
		$('.main-product-name').html('')
		$('.main-product-name').html(productVariantName)
	}
	TN.setUpVariantPrice=(variant)=>{
		let discountType = ''
		let price = variant.price
		let discountPrice = 0
		if(variant.promotion !== null){
			if(variant.promotion.discountType == 'percent'){
				 discountType = '-'+ variant.promotion.discountValue + ' %'
			}else{
				 discountType = variant.promotion.discountValue + ' VND'
			}
			discountPrice = variant.promotion.discountPrice
		}
		let priceAfterDiscount = price -discountPrice

		$('.price-sale').html(TN.formatNumber(priceAfterDiscount))
		if(discountPrice > 0){
			$('.price-old').html(TN.formatNumber(price))
			$('.discount-badge').show()
			$('.discount-badge').html(discountType)
		}
		else{
			$('.price-old').html('')
			$('.discount-badge').hide()
		}
		

		
		
	
	}
	TN.formatNumber=(number)=>{
		return new Intl.NumberFormat('vi-VN').format(number)
	}
	TN.quantityProduct =()=>{
		$(document).on('click','.plus',function () {
			let input = $(this).siblings(".quantity-text");
			let currentValue = parseInt(input.val());
			input.val(currentValue + 1);
		});
	
		$(document).on('click','.minus',function () {
			let input = $(this).siblings(".quantity-text");
			let currentValue = parseInt(input.val());
			if (currentValue > 1) {
				input.val(currentValue - 1);
			}
		});
	}
	$(document).ready(function(){
	
		TN.niceSelect()	
		TN.selectVariantProduct()
		TN.galleryProduct()
		TN.formatNumber()
		TN.quantityProduct()

		
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
