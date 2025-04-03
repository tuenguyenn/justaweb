<div class="uk-container pt20">
    <h2>Xác thực Email</h2>
    <p>Nhập mã OTP đã gửi đến email của bạn.</p>

    <div class="otp-container">
        <input type="text" maxlength="1" class="otp-input" name="otp[]" id="otp1">
        <input type="text" maxlength="1" class="otp-input" name="otp[]" id="otp2">
        <input type="text" maxlength="1" class="otp-input" name="otp[]" id="otp3">
        <input type="text" maxlength="1" class="otp-input" name="otp[]" id="otp4">
        <input type="text" maxlength="1" class="otp-input" name="otp[]" id="otp5">
        <input type="text" maxlength="1" class="otp-input" name="otp[]" id="otp6">
    </div>
   <div class="uk-flex uk-flex-middle uk-flex-space-between">
    <a id="resend-btn" class="button-resend disabled" data-id="{{$customer->id}}">Gửi lại mã (60s)</a>
 
  
    <button type="submit" class="button-13 ">Xác nhận</button>

   
   </div>
  
   <p class="text-resend uk-text-danger uk-hidden"   > Mã OTP mới đã được gửi!</p>
</div>
