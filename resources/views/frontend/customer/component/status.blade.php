<div class="uk-card uk-card-default uk-card-body order-detail-card uk-margin">
    <h4 class="uk-margin-small-bottom">Trạng thái đơn hàng</h4>
    <div class="uk-margin-medium-top">
        <div class="timeline-item">
            <div class="timeline-dot {{ $order->created_at ? 'active' : '' }}"></div>
            <h5 class="uk-margin-remove">Ngày đặt</h5>
            <p class="uk-text-meta uk-margin-remove">{{ formatDate($order->created_at,"H:i d-m-Y") }}</p>
        </div>
        <div class="timeline-item">
            <div class="timeline-dot {{ ($order->confirm == 'confirm') ? 'active' : (($order->confirm =='cancel') ? 'cancel' : '') }}"></div>
            
            <h5 class="uk-margin-remove {{ ($order->confirm == 'cancel') ? 'uk-text-danger' : '' }}">
                {{ ($order->confirm == 'cancel') ? 'Đã hủy' : 'Xác nhận' }}
            </h5>
            
            <p class="uk-text-meta uk-margin-remove">
                {{ ($order->confirm == 'cancel') ? formatDate($order->canceled_at, "H:i d-m-Y") : formatDate($order->confirmed_at, "H:i d-m-Y") }}
            </p>
        </div>
        
        <div class="timeline-item">
            <div class="timeline-dot {{ $order->confirmed_at ? 'active' : '' }}"></div>
            <h5 class="uk-margin-remove">Chuẩn bị giao hàng</h5>
            <p class="uk-text-meta uk-margin-remove">{{ formatDate($order->confirmed_at,"H:i d-m-Y") }}</p>
        </div>
        <div class="timeline-item">
            <div class="timeline-dot {{ $order->delivery_at ? 'active' : '' }}"></div>
            <h5 class="uk-margin-remove">Đang giao hàng</h5>
            <p class="uk-text-meta uk-margin-remove">{{ formatDate($order->delivery_at,"H:i d-m-Y") }}</p>
            <p class="uk-text-meta uk-margin-small-top">Đơn hàng sắp đến, chú ý nghe điện thoại</p>
        </div>
        <div class="timeline-item">
            <div class="timeline-dot {{ $order->deliveried_success_at ? 'active' : '' }}"></div>
            <h5 class="uk-margin-remove">Giao hàng thành công</h5>
            <p class="uk-text-meta uk-margin-remove">{{ formatDate($order->deliveried_success_at,"H:i d-m-Y") }}</p>
        </div>
    </div>
</div>