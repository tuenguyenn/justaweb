<div class="row">
    <div class="col-lg-3">
      <div class="dashboard-card">
          <div class="dashboard-card-header">
              <span class="tag tag-success">Monthly</span>
              <h5>Đơn hàng trong tháng</h5>
          </div>
          <div class="dashboard-card-body">
              <h1>{{ number_format($order['month']['current']['total_orders']) }}</h1>
              <div class="stat">
                  <span class="{{($order['month']['orderGrowth'] >  0 ) ? 'text-success' : 'text-danger'}}">{{ number_format($order['month']['orderGrowth']) }} <i class="fa fa-bolt"></i></span>
                  <small>Tăng trưởng</small>
              </div>
          </div>
      </div>
    </div>
    <div class="col-lg-3">
        <div class="dashboard-card ">
            <div class="dashboard-card-header">
                <span class="label label-info float-right">Đơn hàng mới</span>
                <h5>Orders</h5>
            </div>
            <div class="dashboard-card-body">
                <h1 class="no-margins">{{ number_format($order['week']['current']['total_pending']) }}</h1>
                <div class="stat-percent font-bold text-info">{{ number_format($order['week']['orderGrowth']) }}%<i class="fa fa-level-up"></i></div>
                <small>New orders</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="dashboard-card ">
            <div class="dashboard-card-header">
                <span class="label label-primary float-right">Hôm nay</span>
                <h5>visits</h5>
            </div>
            <div class="dashboard-card-body">
                <h1 class="no-margins">{{ number_format($order['day']['current']['total_pending']) }}</h1>
                <div class="stat-percent font-bold text-navy">{{ number_format($order['day']['orderGrowth']) }}%<i class="fa fa-level-up"></i></div>
                <small>New visits</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="dashboard-card ">
            <div class="dashboard-card-header">
                <span class="label label-danger float-right">Khách hàng</span>
                <h5>User activity</h5>
            </div>
            <div class="dashboard-card-body">
                <h1 class="no-margins">{{$customer}}</h1>
                <div class="stat-percent font-bold text-danger">38% <i class="fa fa-level-down"></i></div>
                <small>In first month</small>
            </div>
        </div>
</div>
</div>