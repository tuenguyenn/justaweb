
<div class="mt-4">
    <div class="table-responsive">
        <table class="table order-table">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="checkAll" >
                    </th>
                    <th>Đơn hàng</th>
                    
                    <th>Thông tin khách hàng</th>
                    <th>Trạng thái</th>
                    <th>Phương thức </th>

                    <th>Thanh toán</th>
                    <th>Giao hàng</th>
                
                    <th>Phí ship</th>
                    <th>Tổng tiền</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($orders) && is_object($orders))
               
                @foreach($orders as $order)
                @php
                $methodPayment =collect(__('payment.method'))->firstWhere('name', $order->method);
                if($methodPayment){
                    $image =$methodPayment['image'];
                }
                   
                 @endphp
                <tr>
                    <td>             <input type="checkbox" value="{{ $order->id }}" class="checkboxItem">
                    </td>
                    <td>
                        <a href="{{route('order.detail', ['id' => $order->id])}}"><span class="text-primary">#{{ $order->code }}</span></a>
                        <p>Ngày đặt :{{ formatDate($order['created_at'], 'H:i d-m-Y') }}</p>
                    </td>
                    <td>
                        <div class="summary-box">
                            <p><strong>{{ $order->fullname }}</strong> - {{ $order->phone }}</p>
                            <p>{{ $order->address }}<br>{{ $order->ward_name }}-{{ $order->district_name }}-{{ $order->district_name }}-{{ $order->province_name }}</p>
                            <p>{{ $order->description }}</p>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $order->confirm ? 'badge-success' : 'badge-danger' }}">
                            {{ __('frontend.confirm')[$order->confirm] }}
                        </span>
                    </td>
                    <td class="text-center">
                       <span class="methodPayment ">
                        <img src="{{asset($image)}}" alt="">
                       </span>
                    </td>
                    <td>
                        <span class="badge badge-danger">
                            {{ __('frontend.payment')[$order->payment] }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-warning">
                            {{ __('frontend.delivery')[$order->delivery] }}
                        </span>
                    </td>
                    <td>
                        {{number_format($order->shipping)}}
                    </td>
                    <td  class="">{{number_format($order['cart']['cartTotal'] - $order['promotion']['discount'])}}đ</td>

                    
                   
                    
                    
                
                   
                </tr>
                @endforeach
               @endif
            </tbody>
        </table>
    </div>
</div>
<!-- Phần phân trang -->
<div class="pagination-wrapper">
    {{ $orders->links('pagination::bootstrap-5') }}
</div>
