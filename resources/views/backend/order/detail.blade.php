

@include('backend.dashboard.component.breadcumb',['title' => $config['seo']['detail']['title']])
    <div class="order-status bg-white p-2">
        <h4 class="ms-2 mainColor">Theo dõi đơn hàng</h4>
        <div class="timeline-container">
            <!-- Thanh timeline -->
            <div class="timeline-line 
                {{ ($order->confirm == 'confirm') ? 'confirm-completed' : '' }} 
                {{ ($order->delivery == 'processing' || $order->delivery == 'success') ? 'delivery-completed' : '' }} 
                {{ ($order->delivery == 'success') ? 'success-completed' : '' }}">
            </div>
        
            <div class="timeline-item completed">
                <div class="timeline-content">
                    Đặt hàng
                    <br><small class="text-muted">{{formatDate($order->created_at,'H:i d-m-Y')}}</small>
                </div>
            </div>
        
            <div class="timeline-item {{ ($order->confirm == 'confirm') ? 'completed' : '' }} confirm-tracking">
                <div class="timeline-content">
                    Xác nhận
                    @if (!is_null($order->confirmed_at))
                        <br><small class="text-muted">{{formatDate($order->confirmed_at,'H:i d-m-Y')}}</small>
                    @else
                        <br><small class="confirm-time text-muted">1-2 ngày</small>
                    @endif
                </div>
            </div>
        
            <div class="timeline-item {{ ($order->delivery == 'processing' || $order->delivery == 'success') ? 'completed' : '' }} delivery-tracking">
                <div class="timeline-content">
                    Đang giao hàng
                    @if (!is_null($order->delivery_at))
                        <br><small class="text-muted">{{formatDate($order->delivery_at,'H:i d-m-Y')}}</small>
                    @else
                        <br><small class="delivery-time text-muted">1-2 ngày</small>
                    @endif
                </div>
            </div>
        
            <div class="timeline-item {{ ($order->delivery == 'success') ? 'completed' : '' }} success-tracking">
                <div class="timeline-content">
                    Giao hàng thành công
                    @if (!is_null($order->deliveried_success_at))
                        <br><small class="text-muted">{{formatDate($order->deliveried_success_at,'H:i d-m-Y')}}</small>
                    @else
                        <br><small class="success-time text-muted">1-2 ngày</small>
                    @endif
                </div>
            </div>
        </div>
        

        
    </div>
    <div class="order-wrapper row mt-4 ">
        <!-- Order Details -->
    
        <div class="col-md-8  ">
            <div class="order-card bg-white p-2">
                <div class="order-heading ms-2">
                    <h3 class="mb-3 mainColor">Đơn hàng<span class="badge bg-primary ms-1">#{{$order->code}}</span></h3>
                   
                    <hr>
                </div>
                @php
                $orders = $order->cart['detail'];
                @endphp
            
               @foreach ($orders as $val)
               @php
              
               $image = $val['options']['image'];
               $canonical = $val['options']['canonical'];
               $priceOriginal =$val['options']['price_original'];
               $attrName =$val['options']['attributeName'] ?? '';
              
                @endphp
             <div class="d-flex border-bottom pb-3 order-item align-items-center">
                <!-- Hình ảnh -->
                <img src="{{asset($image)}}" alt="{{$image}}" class="me-3" style="width: 80px; height: 80px; object-fit: cover;">
            
                <!-- Thông tin sản phẩm -->
                <div class="flex-grow-1 d-flex flex-column">
                    <h4 class="mb-1">{{$val['name']}}</h4>
                    <span class="text-muted">{{$attrName}}</span>
            
                    <!-- Giá sản phẩm -->
                    
                </div>
            
                <!-- Cột số lượng -->
                <div class="qty mx-3">
                    {{$val['qty']}}
                </div>
            
                <!-- Giá tiền -->
                <strong class="text-end" style="width: 80px;">{{number_format($val['subtotal'])}}đ</strong>
            </div>
            
               @endforeach
              
            
            
                
                <!-- Order Summary -->
              
            </div>
            <div class="order-price-info d-flex flex-column bg-white align-items-end p-3">
                <div>Tổng giá sản phẩm: <strong>{{number_format($order['cart']['cartTotal'])}}đ</strong></div>
                <div>Giảm giá <strong>{{number_format($order['promotion']['discount'])}}đ</strong></div>
                <div>Phí ship: <strong>{{number_format($order['shipping'])}}đ</strong></div>
                <div>Thanh toán <strong>{{number_format($order['cart']['cartTotal'] - $order['promotion']['discount'])}}đ</strong></div>
            </div>
           
            <div class="payment-confirm mt-4 bg-white p-3">
                <div class="confirm-box d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        @php
                                    $image = 'userfiles/image/icons8-warning-48.png'; 

                                    if ($order->confirm == 'confirm' && $order->delivery == 'pending') {
                                        $image = 'userfiles/image/icons8-success.svg';
                                    } elseif ($order->delivery == 'processing') {
                                        $image = 'userfiles/image/on-deli1.jpg';
                                    } elseif ($order->delivery == 'success') {
                                        $image = 'userfiles/image/delisuccess.jpg';
                                    }
                                @endphp

                                <span class="order-status-image icon">
                                    <img class="w-100" src="{{ asset($image) }}" alt="Order Status">
                        </span>

                    
                        <div class="payment-title ms-2">
                            {{-- Hiển thị trạng thái đơn hàng --}}
                            <div class="text_1">
                                @if ($order->confirm == 'pending')
                                    <span class="isConfirm text-warning">ĐANG CHỜ XÁC NHẬN ĐƠN HÀNG</span>
                                @elseif ($order->confirm == 'confirm' && $order->delivery == 'pending')
                                    <span class="isConfirm text-primary">ĐƠN HÀNG ĐÃ XÁC NHẬN - CHƯA GIAO</span>
                                @elseif ($order->delivery == 'processing')
                                    <span class="isConfirm text-info">ĐANG GIAO HÀNG</span>
                                @elseif ($order->delivery == 'success')
                                    <span class="isConfirm text-success">ĐƠN HÀNG ĐÃ HOÀN THÀNH</span>
                                @elseif ($order->confirm == 'canceled')
                                    <span class="isConfirm text-danger">ĐƠN HÀNG ĐÃ HỦY</span>
                                @endif
                            </div>
            
                            {{-- Hiển thị phương thức thanh toán --}}
                            <div class="text_2">
                                Hình thức thanh toán ({{ strtoupper($order->method) }})
                            </div>
                        </div>
                    </div>  
                    <div class="cancle-block">
                    {{-- Hiển thị nút Xác nhận đơn hàng --}}
                    @if ($order->confirm == 'pending')
                      
                            <button class="confirm updateField" role="button" data-confirm="confirm" data-field="confirm">
                                Xác nhận đơn hàng
                            </button>
                       
                    @elseif( $order->confirm == 'confirm' && $order->delivery == 'pending')
                        <button class="confirm updateField" role="button" data-confirm="processing" data-field="delivery">
                            Giao hàng
                        </button>
                    @elseif( $order->confirm == 'confirm' && $order->delivery == 'processing')
                        <button class="confirm updateField" role="button" data-confirm="success" data-field="delivery">
                            Hoàn tất giao hàng
                        </button>
                    @endif
                </div>
            
                    {{-- Hiển thị nút Hủy đơn hàng (nếu được phép) --}}
                 
                </div>
            </div>
       
           
        </div>
        
        <!-- Customer Details -->
        <div class="col-md-4">
            <div class="order-card bg-white p-3">
                <div class="order-heading">
                    <h3 class="mb-3 mainColor">Khách hàng</h3>
                   

                </div>
                <hr>
                <div class="d-flex align-items-center mb-3">
                   
                    <div>
                        <h3 class="mb-0">{{$order['fullname']}}</h3>
                        <small>7 orders</small>
                    </div>
                </div>
                <p>Email: <strong>{{$order['email']}}</strong></p>
                <p>Phone: <strong>{{$order['phone']}}</strong></p>
                
                <h6>Địa chỉ nhận hàng</h6>
              
                <p>{{$order->address.'-'.$order->ward_name.'-'.$order->district_name.'-'.$order->ward_name}}</p>
                <p>Ghi chú:{{$order->description}}</p>
                
              
                
                
            </div>
        </div>
    </div>
<input type="hidden" name="" class="orderId" value="{{$order->id}}">


