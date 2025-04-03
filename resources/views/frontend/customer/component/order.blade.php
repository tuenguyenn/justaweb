<div class="uk-width-large-4-6">
    <main class="uk-width-expand@m uk-margin-left">
       
        <div class="uk-section uk-section-xsmall uk-section-default uk-padding-remove-top">
            <div class="uk-container uk-container-expand">
               
                <!-- Orders List -->
                <div class="uk-margin-medium-top">
                    <!-- Order 1 -->
                  @foreach ($orders as $key => $order)
                  @php
                      
                        $orderItem =array_values($order->cart['detail']);
                       
                        $imgOrder = $orderItem[0]['options']['image'] ?? '';
                        $nameProduct = $orderItem[0]['name'] ?? '';
                        $canonicalProduct = $orderItem[0]['options']['canonical'];
                       
                        $placedDate = formatDate($order->created_at);
                        $attrName =$orderItem[0]['options']['attributeName'] ?? '';
                        
                    @endphp
                    
                  <div class="order-item uk-card uk-card-default uk-card-small uk-card-hover uk-margin-bottom uk-position-relative">
                    <div class="uk-card-body">
                        <div class="uk-grid-small uk-flex-middle uk-flex" >
                            <div class="uk-width-medium-3-6">
                                <div class="uk-flex uk-flex-middle">
                                    <div class="uk-margin-right">
                                        <a href="{{$canonicalProduct}}"><img src="{{asset($imgOrder)}}" alt="Product" class="uk-border-rounded imgOrder"></a>
                                    </div>
                                    <div>
                                        <p class="uk-text-meta uk-margin-remove-bottom">Mã #{{$order->code}}</p>
                                        <h5 class="uk-margin-remove-top uk-margin-remove-bottom">{{$nameProduct}}</h5>
                                        <p class="uk-text-meta uk-margin-remove-top">{{$attrName}}</p>
                                    </div>
                                </div>
                            </div>
                            @php
                            if ($order->confirm !== 'confirm') {
                                $status = __('frontend.confirm')[$order->confirm];
                                $statusClass = getConfirmClass($order->confirm);
                            } else {
                                $status = __('frontend.delivery')[$order->delivery];
                                $statusClass = getDeliveryClass($order->delivery);
                            }
                        @endphp
                        
                        <div class="uk-width-medium-2-6 uk-text-center uk-margin-small-top uk-margin-remove-top@m">
                              <p class="uk-text-bold uk-margin-remove">{{number_format($order['cart']['cartTotal'] - $order['promotion']['discount'])}}đ</p>
                        </div>
                        
                          
                               
                              

                            <div class="uk-width-medium-1-6 uk-text-right uk-margin-small-top uk-margin-remove-top@m">
                                <a href="{{ route('order.customer.detail', ['id' => $order->id]) }}">
                                    Xem chi tiết đơn hàng
                                </a>
                                
                            </div>
                            
                           
                        </div>
                    </div>
                    <span class="uk-label {{ $statusClass }} uk-position-absolute uk-position-top-right">
                        {{ $status }}
                    </span>
                </div>

                  @endforeach
    
                   
                </div>
    
                <!-- Pagination -->
                <div class="uk-flex uk-flex-between uk-flex-middle uk-margin-medium">
                   {{ $orders->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </main>
</div>
<div class="uk-width-large-1-6 uk-flex uk-flex-column p30">
    @include('frontend.customer.component.aside')
</div>