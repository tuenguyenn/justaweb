// Lấy URL hiện tại
let urlParams = new URLSearchParams(window.location.search);
let newUrl = "http://ecommere.test/return/vnpay_ipn?" + urlParams.toString();

fetch(newUrl)
   
    .catch(error => console.error("Lỗi:", error));
