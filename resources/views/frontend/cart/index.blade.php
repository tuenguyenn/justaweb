@extends('frontend.homepage.layout')
@section('content')
<div class="cart-container">
    
    <div class="uk-container uk-container-center">
        @include('backend.dashboard.component.formerror')

        <form action="{{route('cart.store')}}" class="uk-form form" method="post">
            @csrf
            <div class="cart-wrapper">
                <div class="uk-grid uk-grid-medium">
                    <div class="uk-width-large-3-5">
                        <div class="panel-cart cart-left">
                            <div class="panel-head">
                                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                    <h2 class="cart-heading">
                                        <span>Thông tin đặt hàng</span>
                                    </h2>
                                    <span class="has-account">Bạn đã có tài khoản? <a href="">Đăng nhập ngay</a></span>
                                    </div>
                            </div>
                            <div class="panel-body">
                              
                                @include('frontend.cart.component.content', ['customer' => $customer ?? null])
                                @include('frontend.cart.component.payment')

                            </div>
                        </div>
                    </div>
                    <div class="uk-width-large-2-5">
                        <div class="panel-cart uk-flex uk-flex-space-between uk-flex-middle ">
                            <h2 class="cart-heading">
                                <span>Giỏ hàng</span>
                            </h2>
                            <a href="">Cập nhật </a>
                        </div>
                        <div class="panel-body">

                            @include('frontend.cart.component.item')

                            </div>
                            <div class="panel-voucher">                  
                                @include('frontend.cart.component.voucher')             

                             </div>
                             <div class="panel-foot mt30">
                                @include('frontend.cart.component.price')             

                                </div>
                             </div>
                    </div>
                    
                    </div>
                    </div>
            </div>
        </form>
    </div>
</div>

@endsection




<!-- Modal xác nhận xóa (UIkit) -->
<div id="confirm-delete" class="uk-hidden" uk-modal>
    <div class="uk-modal-dialog uk-modal-body uk-text-left uk-border-rounded">
        <h3 class="uk-modal-title">Xác nhận xóa</h3>
        <p>Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng không?</p>
        <div class="modal-actions ">
            <button class="uk-button uk-button-default uk-modal-close">Hủy</button>
            <button class="uk-button uk-button-danger" id="confirm-delete-btn">Xóa</button>
        </div>
    </div>
</div>
<input type="hidden" name="totalPrice" value="{{$cartTotal}}">