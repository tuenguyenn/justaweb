(function($) {
	"use strict";
	var TN = {}; // Khai báo là 1 đối tượng
	var timer;	
	let searchDebounce;
	let searchHistory = JSON.parse(localStorage.getItem('searchHistory')) || [];
	var _token =$('meta[name="csrf-token"]').attr('content')
	TN.openChat = () => {
		$(".chatbot-toggle, .close-chat").on("click", function () {
			$(".chat-container").fadeToggle();
		});
	}
	TN.addWelcomeMessage = () => {
		setTimeout(() => {
			$("#chatBody").append(`<div class="bot-message">Xin chào, tôi có thể giúp gì cho bạn?</div>`);
		}, 500);
	};
	TN.swiperOption = (setting) => {
		let option = {}
		if(setting.animation.length){
			option.effect = setting.animation;
		}	
		if(setting.arrow === 'accept'){
			option.navigation = {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			}
		}
		if(setting.autoplay === 'on'){
			option.autoplay = {
			    delay: 2000,
			    disableOnInteraction: false,
			}
		}
		if(setting.navigate === 'dots'){
			option.pagination = {
				el: '.swiper-pagination',
			}
		}
		return option
	}
	
	/* MAIN VARIABLE */
	TN.swiper = () => {
		if($('.panel-slide').length){
			let setting = JSON.parse($('.panel-slide').attr('data-setting'))
			let option = TN.swiperOption(setting)
			var swiper = new Swiper(".panel-slide .swiper-container", option);
		}
		
	}

	TN.swiperCategory = () => {
		var swiper = new Swiper(".panel-category .swiper-container", {
			loop: false,
			pagination: {
				el: '.swiper-pagination',
			},
			spaceBetween: 20,
			slidesPerView: 3,
			breakpoints: {
				415: {
					slidesPerView: 3,
				},
				500: {
				  slidesPerView: 3,
				},
				768: {
				  slidesPerView: 6,
				},
				1280: {
					slidesPerView: 10,
				}
			},
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
			
		});
	}

	TN.swiperBestSeller = () => {
		var swiper = new Swiper(".panel-bestseller .swiper-container", {
			loop: false,
			pagination: {
				el: '.swiper-pagination',
			},
			spaceBetween: 20,
			slidesPerView: 2,
			breakpoints: {
				415: {
					slidesPerView: 1,
				},
				500: {
				  slidesPerView: 2,
				},
				768: {
				  slidesPerView: 3,
				},
				1280: {
					slidesPerView: 4,
				}
			},
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
			
		});
	}
	
	
	TN.resendOtp = function () {
		let $resendBtn = $("#resend-btn");
		let countdown = 5;
	
		function updateButton() {
			$resendBtn.text(`Gửi lại mã (${countdown}s)`).addClass("disabled");
			let timer = setInterval(() => {
				countdown--;
				$resendBtn.text(`Gửi lại mã (${countdown}s)`);
				if (countdown === 0) {
					clearInterval(timer);
					$resendBtn.text("Gửi lại mã").removeClass("disabled");
				}
			}, 1000);
		}
	
		updateButton();
	
		$resendBtn.on("click", function (e) {
			e.preventDefault(); // Ngăn chặn hành động mặc định của thẻ <a>
			if ($resendBtn.hasClass("disabled")) return;
			let id = $(this).attr('data-id')
			
			
			
	
			$.ajax({
				url: "ajax/customer/resendOtp",  
				type: "POST",
				data: { 
					id: id ,
					_token :_token
				},

				dataType: "json",
				success: function (response) {
					if(response){
						$(".text-resend").removeClass("uk-hidden");
						countdown = 60;
						updateButton();
					}else{
						alert("Có lỗi xảy ra! Vui lòng thử lại.");
					}
				},
				error: function (xhr) {
					alert("Có lỗi xảy ra! Vui lòng thử lại.");
					console.error(xhr.responseJSON || { message: "Lỗi không xác định" });
				}
			});
		});
	};

	TN.enterMess =()=>{
		$("#message").keypress(function (e) {
			if (e.which === 13) { // 13 là mã phím Enter
				TN.sendMessage();
			}
		});
	}
	

	TN.sendBtn =()=>{
		$("#sendBtn").on("click", function () {
			TN.sendMessage();
		});
	}
	TN.sendMessage =()=>{
		let message = $("#message").val().trim();
        if (message === "") return;

        $("#chatBody").append(`<div class="user-message">${message}</div>`);
        $("#message").val(""); 
     
        let typingIndicator = `<div class="bot-message typing">AI đang soạn...</div>`;
        $("#chatBody").append(typingIndicator);
        $(".chat-body").scrollTop($(".chat-body")[0].scrollHeight);

        // Gửi tin nhắn đến server bằng AJAX
		$.ajax({
			url: "/chatbot",
			type: "POST",
			data: { message: message, _token: _token },
			success: function (response) {
				$(".typing").remove(); 
				$("#chatBody").append(`<div class="bot-message">${response.response}</div>`);
			},
			error: function () {
				$(".typing").remove();
				$("#chatBody").append(`<div class="bot-message">Có lỗi xảy ra!</div>`);
			}
		});
    }
	
	
	

	TN.wow = () => {
		var wow = new WOW(
			{
			  boxClass:     'wow',      // animated element css class (default is wow)
			  animateClass: 'animated', // animation css class (default is animated)
			  offset:       0,          // distance to the element when triggering the animation (default is 0)
			  mobile:       true,       // trigger animations on mobile devices (default is true)
			  live:         true,       // act on asynchronously loaded content (default is true)
			  callback:     function(box) {
				// the callback is fired every time an animation is started
				// the argument that is passed in is the DOM node being animated
			  },
			  scrollContainer: null,    // optional scroll container selector, otherwise use window,
			  resetAnimation: true,     // reset animation on end (default is true)
			}
		  );
		  wow.init();


	}

	TN.niceSelect = () => {
		if($('.nice-select').length){
			$('.nice-select').niceSelect();
		}
		
	}
	
	TN.formatNumber=(number)=>{
		return new Intl.NumberFormat('vi-VN').format(number)
	}
	TN.saveToHistory=(term)=>{
        if (!term.trim()) return;

       
        searchHistory = searchHistory.filter(item => item !== term);
        searchHistory.unshift(term); 
        searchHistory = searchHistory.slice(0, 10); 

        
        localStorage.setItem('searchHistory', JSON.stringify(searchHistory));
        TN.renderSearchHistory();
    }
	TN.renderSearchHistory=() =>{
        const $historyList = $('.recent-searches ul');
        $historyList.empty();

        searchHistory.forEach(term => {
            $historyList.append(`
                <li>
                    <span class="search-icon">⌕</span>
                    <span class="search-term">${term}</span>
                    <span class="delete-term">×</span>
                </li>
            `);
        });
		TN.deleteHistory()
		TN.clearHistory()
		TN.chooseSuggest()
    }

	TN.openPopup =()=>{
		$('.input-text').on('click', function() {
			$('.search-dropdown').addClass('active');
		});
	}
	TN.closePopup =()=>{
		$(document).on('click', function(e) {
			if (!$(e.target).closest('.search-form').length) {
				$('.search-dropdown').removeClass('active');
			}
		});
	}
	TN.chooseSuggest =()=>{
		$('.search-term').on('click', function() {
			const searchTerm = $(this).text() 
			$('.input-text').val(searchTerm);

			TN.performSearch(searchTerm);
		  });
	}
	TN.deleteHistory =()=>{
		$(document).on('click','.delete-term',function(e) {
			e.stopPropagation();
			const term = $(this).closest('li').find('.search-term').text();
			console.log(term);
			
			searchHistory = searchHistory.filter(item => item !== term);
			localStorage.setItem('searchHistory', JSON.stringify(searchHistory));
			
			$(this).closest('li').remove();
		  });
	}
	TN.clearHistory=()=>{
		$(document).on('click','.clear-history', function() {
			searchHistory = [];
			localStorage.removeItem('searchHistory');
			$('.recent-searches').remove()
		  });
	}

	TN.inputSearch=()=>{
		$('.input-text').on('input keyup', function() {
			const query = $(this).val().trim();
			
			
			if (query.length > 0) {
			  $('.search-section').hide();
			
			  $('.realtime-results').show();
			  $('.realtime-results .current-query').text(query);
			} else {
				
			  $('.search-section').show();
			  $('.realtime-results').hide();
			  return;
			}

			clearTimeout(searchDebounce);
			searchDebounce = setTimeout(() => {
				TN.performSearch();
			}, 300);
		  });
	}
	TN.performSearch=()=>{
		const searchTerm = $('.input-text').val();
		if (searchTerm.trim() !== '') {
			TN.saveToHistory(searchTerm)
			$.ajax({
				type: "GET",
				url: "ajax/product/loadProductPromotion",
				data: {
					model: 'Product',
					keyword : searchTerm
				},
				dataType: 'json',
				success: function(res) {
					// Kiểm tra dữ liệu trả về có hợp lệ không
					if (res && res.objects && res.objects.data && res.objects.data.length > 0) {
					  TN.renderRealtimeResults(res.objects.data, searchTerm);
					} else {
					  // Hiển thị thông báo khi không có kết quả
					  TN.showNoResults(searchTerm);
					}
				  },
				error: function(xhr, status, error) {
					// Xử lý khi gọi API bị lỗi
					console.error('Lỗi tìm kiếm:', error);
					TN.showErrorNotification();
				  }
			})
		}
	}
	TN.showNoResults = function(searchTerm) {
		$('.quick-products-grid').html(`
		  <div class="no-results">
			<i class="icon icon-search-empty"></i>
			<p>Không tìm thấy kết quả cho "<strong>${searchTerm}</strong>"</p>
			
		  </div>
		`);
		
		$('.results-count').text('0 kết quả');
	  };
	  
	  TN.showErrorNotification = function() {
		$('.quick-products-grid').html(`
		  <div class="api-error">
			<i class="icon icon-error"></i>
			<p>Đã xảy ra lỗi khi tìm kiếm. Vui lòng thử lại sau.</p>
			<button class="retry-btn">Thử lại</button>
		  </div>
		`);
		
		// Xử lý sự kiện click nút thử lại
		$('.retry-btn').click(function() {
		  TN.performSearch($('#searchInput').val());
		});
	  };
	TN.renderRealtimeResults=(products, query) =>{
		const $grid = $('.quick-products-grid');
		$grid.empty();
		
		
		products.forEach(product => {
			let name = product.name
			let canonical= write_canonical(product.canonical)
			if(product.product_variant_id != null){
				name= product.variant_name
			}
		  $grid.append(`
			<div class="quick-product-card" data-id="${product.id}">
			 <a href="${canonical}" class="product-link">
			  <div class="quick-product-image">
				<img src="${product.image}" alt="${name}">
			  </div>
			  <div class="quick-product-name">${name}</div>
			  <div class="quick-product-price">${addCommas(product.price)}đ</div>
			  </a>
			</div>
		  `);
		});
		
		
		$('.realtime-results .results-count').text(`${products.length} kết quả`);
		$('.view-all .current-query').text(query);
	  }

	TN.sortBtn=()=>{
		$('.sort-btn').on('click', function () {
			var sortType = $(this).data('sort');
			var $products = $('.product');
		
			
			$('.sort-btn').removeClass('active');
			$(this).addClass('active');
		
			if (sortType === 'relevance') {
			
				location.reload(); // hoặc lưu thứ tự gốc lúc load
				return;
			}
		
			var sorted = $products.sort(function (a, b) {
				var priceA = parseInt($(a).find('.price-sale').text().replace(/[^\d]/g, ''));
				var priceB = parseInt($(b).find('.price-sale').text().replace(/[^\d]/g, ''));
				return sortType === 'asc' ? priceA - priceB : priceB - priceA;
			});
		
			$('.product-list').empty().append(sorted);
			$('.product-item').addClass('mr20')
		});
	}
	
	$(document).ready(function(){
		TN.wow()
		TN.swiperCategory()
		TN.swiperBestSeller()
		TN.swiper()
		TN.niceSelect()	
		TN.resendOtp()
		TN.openChat()
		TN.enterMess()
		TN.sendBtn()
		TN.sendMessage()
		TN.openPopup()
		TN.closePopup()
		TN.chooseSuggest()
	
		
		TN.deleteHistory()
		TN.clearHistory()
		TN.renderSearchHistory()
		TN.inputSearch()
		TN.addWelcomeMessage()
		TN.sortBtn()
	
		
		

		
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
write_canonical=(text) =>{
    return BASE_URL+text
        .toLowerCase()               // Chuyển về chữ thường
        .trim()                      // Xóa khoảng trắng 2 đầu
        .replace(/\s+/g, '-')        // Thay khoảng trắng bằng dấu gạch ngang
        .replace(/[^\w\-]+/g, '')    // Xóa ký tự đặc biệt
        .replace(/\-\-+/g, '-');     // Gộp nhiều dấu - thành 1
}