<div class="uk-card uk-card-default uk-card-body uk-border-rounded mt20">
    <h4 class="uk-card-title">Tài khoản</h4>

    <ul class="uk-list uk-list-divider">
        <li>
            <a href="{{ route('customer.profile',['id' =>$customer->id]) }}" class="uk-flex uk-flex-middle">
                <span uk-icon="user"></span>
                <span class="uk-margin-small-left">Thông tin tài khoản</span>
            </a>
        </li>
        <li>
            <a href="{{ route('order.history', ['id'=> $customer->id])}}" class="uk-flex uk-flex-middle">
                <span uk-icon="lock"></span>
                <span class="uk-margin-small-left">Đơn hàng</span>
            </a>
        </li>
        <li>
            <a href="{{ route('customerfe.verify',['id' =>$customer->id]) }}" class="uk-flex uk-flex-middle">
                <span uk-icon="check"></span>
                <span class="uk-margin-small-left">Xác thực email</span>
            </a>
        </li>
        <li>
            <a href="{{ route('password.change')}}" class="uk-flex uk-flex-middle">
                <span uk-icon="lock"></span>
                <span class="uk-margin-small-left">Đổi mật khẩu</span>
            </a>
        </li>
     
        <li>
            <a href="" class="uk-flex uk-flex-middle" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span uk-icon="sign-out"></span>
                <span class="uk-margin-small-left">Đăng xuất</span>
            </a>
            {{-- <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form> --}}
        </li>
    </ul>
</div>