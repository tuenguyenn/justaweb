<div class="uk-width-large-4-6 mt10">
    
    <div class="uk-container uk-margin-top uk-margin-bottom">
       

        @php
              $methodPayment =collect(__('payment.method'))->firstWhere('name', $order->method);
                if($methodPayment){
                    $imagePayment =$methodPayment['image'];
                };
                if ($order->confirm !== 'confirm') {
                    $status = __('frontend.confirm')[$order->confirm];
                    $statusClass = getConfirmClass($order->confirm);
                } else {
                    $status = __('frontend.delivery')[$order->delivery];
                    $statusClass = getDeliveryClass($order->delivery);
                }
        @endphp
        <div class="uk-card uk-card-default uk-card-body order-detail-card uk-margin">
            <div class="order-header uk-flex uk-flex-space-between uk-flex-middle">
                <div>
                    <h3 class="uk-card-title uk-margin-remove">Mã #{{$order->code}}</h3>
                    <p class="uk-text-meta uk-margin-remove-top">Đặt hàng lúc {{formatDate($order->created_at, "H:i d-m-Y")}}</p>
                   
                </div>
                <div class="uk-flex uk-flex-column">
                   
                    @if ($order->confirm == 'pending')
                    <div class="cancelOrder uk-text-danger" uk-toggle="target: #cancel-order-modal">
                        HỦY ĐƠN
                    </div>
                    @else
                    <span class="uk-label {{ $statusClass }} border-0 "> {{ $status }}</span>
                    @endif
                    
                </div>
                
              
                
              
            </div>

            <div class="uk-grid-divider uk-grid-medium uk-child-width-1-3@m uk-margin-top" uk-grid>
                <div>
                    <h5 class="uk-margin-small-bottom">Thông tin người nhận</h5>
                    <p class="uk-margin-remove">{{$order->fullname}}<br>
                        <p>Email: <strong>{{$order['email']}}</strong></p>
                        <p>Phone: <strong>{{$order['phone']}}</strong></p>
                        
                        <h6></h6>
                      
                        <p>{{$order->address.'-'.$order->ward_name.'-'.$order->district_name.'-'.$order->ward_name}}</p>
                        
                </div>
                <div>
                    <h5 class="uk-margin-small-bottom">Phương thức thanh toán</h5>
                    <p class="uk-margin-remove">
                        <span uk-icon="" class=""></span>
                        <img class="paymentMethod" src="{{asset($imagePayment)}}" alt="">
                    </p>
                    <p class="uk-text-meta uk-margin-small-top"> ({{ strtoupper($order->method) }})</p></p>
                </div>
                <div>
                    <h5 class="uk-margin-small-bottom">Ghi chú :</h5>
                    <p class="uk-margin-remove">
                        <span uk-icon="icon: mail; ratio: 0.8" class="uk-margin-small-right"></span>
                        {{$order->description}}
                    </p>
                    
                </div>
            </div>
        </div>

        <!-- Order Status Timeline -->
      
        <!-- Order Items -->
        <div class="uk-card uk-card-default uk-card-body order-detail-card uk-margin">
            <h4 class="uk-margin-small-bottom">Thông tin đơn hàng</h4>
            @php
            $orders = $order->cart['detail'];
            @endphp
        
           @foreach ($orders as $val)
           @php
          
           $image = $val['options']['image'];
           $canonical = $val['options']['canonical'];
           $priceOriginal =$val['options']['price_original'];
           $attrName =$val['options']['attributeName'] ?? '';
            $explode = explode('_' ,$val['id']);
            $productId = $explode[0];
          
          
          
            @endphp
            <div class="product-row uk-grid-small uk-flex-middle uk-flex " >
                <div class="uk-width-3-6 uk-flex uk-flex-middle">
                   <a href="{{$canonical}}"> <img src="{{asset($image)}}" alt="Product" class="product-img"></a>
                    <div class="uk-width-expand">
                        <div>
                            <h5 class="uk-margin-remove">{{$val['name']}}</h5>
                        <p class="uk-text-meta uk-margin-remove">{{$attrName}}</p>
                        </div>
                        <div>
                            @if ($priceOriginal != $val['price'])
                                <span class="price-original text-muted" style="text-decoration: line-through;">{{ number_format($priceOriginal) }}đ</span>
                                <span class="price-discount text-danger fw-bold ms-2">{{ number_format($val['price']) }}đ</span>
                            @else
                                <span class="price-discount text-danger fw-bold">{{ number_format($val['price']) }}đ</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="uk-width-2-6">
                    <p class="uk-margin-remove">{{$val['qty'] . '×'. number_format($val['price'])}}đ</p>
                </div>
                <div class="uk-width-1-6">
                   
                    <p class="uk-text-bold">{{number_format($val['subtotal'])}}đ</p>
                     
                    
                </div>
               
            </div>
           
         
            <!-- From Uiverse.io by d4niz --> 
            <button class="contactButton reviewBtn" uk-toggle="target: #review-modal" data-id="{{$productId}}">
            Đánh giá
                <div class="iconButton">
                <svg
                    height="24"
                    width="24"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path d="M0 0h24v24H0z" fill="none"></path>
                    <path
                    d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"
                    fill="currentColor"
                    ></path>
                </svg>
                </div>
            </button>
  
            @endforeach
          
        <!-- Order Summary -->
        
        </div>
        
        <div class="uk-width-1-3@m mt10">
            <div class="order-summary-section uk-padding-small">
                <h4 class="uk-margin-small-bottom">Thông tin đơn hàng</h4>
                <div class="uk-grid-small" uk-grid>
                    <div class="uk-width-expand">Tổng giá sản phẩm:</div>
                    <div class="uk-width-auto">{{number_format($order['cart']['cartTotal'])}}đ</div>
                </div>
                <div class="uk-grid-small" uk-grid>
                    <div class="uk-width-expand">Phí giao hàng:</div>
                    <div class="uk-width-auto">{{number_format($order['shipping'])}}đ <span class="free-shipping-badge">Free</span></div>
                </div>
                <div class="uk-grid-small" uk-grid>
                    <div class="uk-width-expand">Giảm giá</div>
                    <div class="uk-width-auto">-{{number_format($order['promotion']['discount'])}}đ</div>
                </div>
                <hr>
                <div class="uk-grid-small" uk-grid>
                    <div class="uk-width-expand uk-text-bold">Tổng tiền</div>
                    <div class="uk-width-auto uk-text-bold">{{number_format($order['cart']['cartTotal'] - $order['promotion']['discount'])}}đ</div>
                </div>
            </div>
        </div>
        <div class="uk-width-1-3@m mt10">
            <div class="review-product uk-padding-small">
              
            </div>
         
        </div>
        
        



     
    </div>

  
</div>
<div class="uk-width-large-1-6 mt20 ">
    @include('frontend.customer.component.status')

   
</div>


<!-- Modal Xác Nhận Hủy Đơn -->
<div id="cancel-order-modal" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Xác nhận hủy đơn</h2>
        <p>Bạn có chắc chắn muốn hủy đơn hàng này không?</p>
        <form action="{{ route('order.cancel', ['id' => $order->id]) }}" method="POST">
            @csrf
            <textarea class="uk-textarea review-textarea" name="reason_cancel" placeholder="Lý do hủy đơn (không bắt buộc)"></textarea>
            <div class="uk-margin-top uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Hủy</button>
                <button class="uk-button uk-button-danger" type="submit">Xác nhận hủy</button>
            </div>
            <input type="hidden" name="confirm" id="" value="cancel">
        </form>
    </div>
</div>
@include('frontend.customer.component.review')