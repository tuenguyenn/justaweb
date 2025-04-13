<div class="row">
    <div class="col-lg-12">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h4>DOANH THU</h4>
                <div class="float-right">
                    <select id="time-range-selector">
                        <option value="7days">7 ngày qua</option>
                      
                        <option value="this-month">Tháng này</option>
                        <option value="this-quarter">Quý này</option>
                        <option value="this-year">Năm nay</option>
                    </select>
                    
                
                    
                </div>
            </div>
            <div class="dashboard-card-body">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="card-body">
                            <h5 class="card-title">Biểu đồ doanh thu</h5>
                            <canvas id="revenueChart" height="100"></canvas>
                        </div>
                    </div>
                    
                    <div class="col-lg-3">
                        <ul class="stat-list">
                            <li>
                                <h6>Số lượng: <span class="no-margins">{{ number_format($order['day']['current']['total_orders']) }}</span></h6>
                                <div class="stat-percent">{{ $order['day']['orderGrowth'] }}% 
                                    <i class="fa {{ $order['day']['orderGrowth'] >= 0 ? 'fa-level-up text-navy' : 'fa-level-down text-danger' }}"></i>
                                </div>
                                <small>Đơn hàng mới</small>
                              
                                <div class="progress progress-mini">
                                    <div style="width: {{ abs($order['day']['orderGrowth']) }}%;" class="progress-bar {{ $order['day']['orderGrowth'] >= 0 ? 'bg-success' : 'bg-danger' }}"></div>
                                </div>
                            </li>
                            <li>
                                <h6>Doanh thu ngày:<span class="no-margins"> {{ number_format($order['day']['current']['total_revenue']) }} VNĐ</h2>
                                    <div class="stat-percent">{{ $order['day']['revenueGrowth'] }}% 
                                        <i class="fa {{ $order['day']['revenueGrowth'] >= 0 ? 'fa-level-up text-navy' : 'fa-level-down text-danger' }}"></i>
                                    </div>
                                <small>Doanh thu</small>
                              
                                <div class="progress progress-mini">
                                    <div style="width: {{ abs($order['day']['revenueGrowth']) }}%;" class="progress-bar {{ $order['day']['revenueGrowth'] >= 0 ? 'bg-success' : 'bg-danger' }}"></div>
                                </div>
                            </li>
                            <li>
                                <h6>Khách hàng<span class="no-margins"> {{ number_format($order['day']['current']['total_revenue']) }} VNĐ</h2>
                                    <div class="stat-percent">{{ $order['month']['orderGrowth'] }}% 
                                        <i class="fa {{ $order['month']['orderGrowth'] >= 0 ? 'fa-level-up text-navy' : 'fa-level-down text-danger' }}"></i>
                                    </div>
                                <small>Khách hàng mới</small>
                              
                                <div class="progress progress-mini">
                                    <div style="width: {{ abs($order['month']['orderGrowth']) }}%;" class="progress-bar {{ $order['month']['orderGrowth'] >= 0 ? 'bg-success' : 'bg-danger' }}"></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let chart; // lưu biểu đồ để cập nhật lại khi filter
    
    function loadRevenueChart(type = '7days') {
        $.ajax({
            url: '/ajax/order/revenue-chart',
            method: 'GET',
            data: { type: type },
            success: function (data) {
                renderBarChart(data);
            }
        });
    }
    
    function renderBarChart(data) {
        const ctx = document.getElementById('revenueChart').getContext('2d');
    
        // Xoá biểu đồ cũ nếu có
        if (chart) {
            chart.destroy();
        }
    
        chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(item => item.label),
                datasets: [{
                    label: 'Doanh thu',
                    data: data.map(item => item.revenue),
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('vi-VN') + ' đ';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let value = context.raw;
                                return 'Doanh thu: ' + value.toLocaleString('vi-VN') + ' đ';
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Gọi khi trang tải lần đầu
    loadRevenueChart();
    
    // Ví dụ: gọi khi người dùng chọn filter
    $('#time-range-selector').on('change', function() {
        let type = $(this).val();
        loadRevenueChart(type);
    });
    </script>