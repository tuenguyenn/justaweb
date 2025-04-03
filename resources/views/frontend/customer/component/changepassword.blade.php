<div class="uk-container mt20">
    <h2 class="uk-text-center">Đổi Mật Khẩu</h2>
    

        <div class="uk-margin">
            <label for="current_password" class="uk-form-label">Mật khẩu cũ</label>
            <input type="password" name="current_password" class="uk-input" required>
            @error('current_password')
                <p class="uk-text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div class="uk-margin">
            <label for="new_password" class="uk-form-label">Mật khẩu mới</label>
            <input type="password" name="new_password" class="uk-input" required>
            @error('new_password')
                <p class="uk-text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div class="uk-margin">
            <label for="new_password_confirmation" class="uk-form-label">Nhập lại mật khẩu</label>
            <input type="password" name="new_password_confirmation" class="uk-input" required>
        </div>

        <div class="uk-margin">
            <button type="submit" class="uk-button uk-button-primary uk-width-1-1">Đổi mật khẩu</button>
        </div>
   
</div>
