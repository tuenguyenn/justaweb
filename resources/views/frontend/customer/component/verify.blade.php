<div class="uk-width-large-4-6 ">
    <div class="panel-cart cart-left">
        <div class="panel-heading">
            Thông tin cần xác thực
        </div>
        <div class="panel-body">
            <div class="uk-width-1-1 mt10">
                <div class="form-row input-container">
                    <input type="text" name="email" placeholder="Nhập email"  
                        class="input-text uk-input" value="{{ $customer->email }}" readonly>
                    
                    @if(is_null($customer->google_id) && is_null($customer->email_verified_at))
                        <span class="status-message uk-text-danger">
                            <i class="uk-icon-warning"></i> Chưa xác thực
                        </span>
                    @elseif(!is_null($customer->google_id))
                        <span class="status-message uk-text-success">
                            <i class="uk-icon-google"></i> Đã đăng nhập bằng Google
                        </span>
                    @else
                        <span class="status-message uk-text-success">
                            <i class="uk-icon-check"></i> Đã xác thực
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <button type="submit" name="send" class="button-13 ">Lưu</button>
    </div>
</div>
<div class="uk-width-large-1-6">

</div>
