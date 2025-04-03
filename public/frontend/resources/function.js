(function($) {
	"use strict";
	var TN = {}; // Khai báo là 1 đối tượng
	var timer;	
	var _token =$('meta[name="csrf-token"]').attr('content')


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
	
	TN.openChat = () => {
		$(".chatbot-toggle, .close-chat").on("click", function () {
			$(".chat-container").fadeToggle();
			if ($(".chat-container").is(":visible")) {
				$("#message").focus();
			}
		});
	};
	
	// Xử lý sự kiện nhấn Enter
	TN.enterPress = () => {
		$("#message").keypress(function (e) {
			if (e.which === 13) {
				TN.sendMessage();
			}
		});
	};
	
	// Xử lý sự kiện nhấn nút gửi
	TN.sendBtn = () => {
		$("#sendBtn").on("click", function () {
			TN.sendMessage();
		});
	};
	
	// Xử lý gửi tin nhắn
	TN.sendMessage = () => {
		let message = $("#message").val().trim();
		if (message === "") return;
		
		// Thêm tin nhắn người dùng
		$("#chatBody").append(`<div class="user-message">${message}</div>`);
		$("#message").val("");
		
		// Hiển thị chỉ báo đang soạn
		let typingIndicator = `<div class="bot-message typing">AI đang soạn...</div>`;
		$("#chatBody").append(typingIndicator);
		TN.scrollToBottom();
		
		// Gửi yêu cầu Ajax đến server
		$.ajax({
			url: "/chatbot",
			type: "POST",
			data: { message: message, _token: _token },
			success: function (response) {
				$(".typing").remove();
				$("#chatBody").append(`<div class="bot-message">${response.response}</div>`);
				TN.scrollToBottom();
			},
			error: function () {
				$(".typing").remove();
				$("#chatBody").append(`<div class="bot-message">Có lỗi xảy ra!</div>`);
				TN.scrollToBottom();
			}
		});
	};
	
	// Cuộn xuống tin nhắn mới nhất
	TN.scrollToBottom = () => {
		$(".chat-body").scrollTop($(".chat-body")[0].scrollHeight);
	};
	
	// Thêm tin nhắn chào mừng
	TN.addWelcomeMessage = () => {
		setTimeout(() => {
			$("#chatBody").append(`<div class="bot-message">Xin chào! Bạn cần giúp đỡ gì hôm nay?</div>`);
		}, 500);
	};
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

	$(document).ready(function(){
		TN.wow()
		TN.swiperCategory()
		TN.swiperBestSeller()
		TN.swiper()
		TN.niceSelect()	
		TN.resendOtp()
		TN.openChat()
		TN.sendMessage();
		TN.enterPress();
		TN.sendBtn()
		TN.scrollToBottom()
		TN.addWelcomeMessage()
	

		
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
