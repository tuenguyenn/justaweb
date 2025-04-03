@extends('frontend.homepage.layout')
@section('content')


<div class="uk-container order-summary">

    <div class="confirmation-box">
        <span class="icon" uk-icon="check">
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 48 48">
                <linearGradient id="I9GV0SozQFknxHSR6DCx5a_70yRC8npwT3d_gr1" x1="9.858" x2="38.142" y1="9.858" y2="38.142" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#21ad64"></stop><stop offset="1" stop-color="#088242"></stop></linearGradient><path fill="url(#I9GV0SozQFknxHSR6DCx5a_70yRC8npwT3d_gr1)" d="M44,24c0,11.045-8.955,20-20,20S4,35.045,4,24S12.955,4,24,4S44,12.955,44,24z"></path><path d="M32.172,16.172L22,26.344l-5.172-5.172c-0.781-0.781-2.047-0.781-2.828,0l-1.414,1.414	c-0.781,0.781-0.781,2.047,0,2.828l8,8c0.781,0.781,2.047,0.781,2.828,0l13-13c0.781-0.781,0.781-2.047,0-2.828L35,16.172	C34.219,15.391,32.953,15.391,32.172,16.172z" opacity=".05"></path><path d="M20.939,33.061l-8-8c-0.586-0.586-0.586-1.536,0-2.121l1.414-1.414c0.586-0.586,1.536-0.586,2.121,0	L22,27.051l10.525-10.525c0.586-0.586,1.536-0.586,2.121,0l1.414,1.414c0.586,0.586,0.586,1.536,0,2.121l-13,13	C22.475,33.646,21.525,33.646,20.939,33.061z" opacity=".07"></path><path fill="#fff" d="M21.293,32.707l-8-8c-0.391-0.391-0.391-1.024,0-1.414l1.414-1.414c0.391-0.391,1.024-0.391,1.414,0	L22,27.758l10.879-10.879c0.391-0.391,1.024-0.391,1.414,0l1.414,1.414c0.391,0.391,0.391,1.024,0,1.414l-13,13	C22.317,33.098,21.683,33.098,21.293,32.707z"></path>
                </svg>
        </span>
        <h2 class="uk-text-bold mt10">CẢM ƠN BẠN ĐÃ ĐẶT HÀNG</h2>
        <p>Bạn sẽ nhận được email xác nhận đơn hàng có thông tin chi tiết về đơn hàng của bạn và liên kết để theo dõi  đơn hàng.
        </p>
        <p>Chúng tôi đã gửi tất cả thông tin cần thiết về việc giao hàng đến email của bạn</p>
        <p class="order-number">Mã đơn hàng #: <strong>{{$order->code}}</strong></p>
        <p>Ngày đặt: {{formatDate($order->created_at,'H:i d-m-Y')}}</p>
    </div>
    <table class="uk-table uk-table-divider uk-table-middle order-table">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>SL</th>
                <th>Giá sản phẩm</th>
            </tr>
        </thead>
        <tbody>
            @php
                $carts = $order->cart['detail'];
               
            @endphp
           @foreach ($carts as $val)
           @php
               $image = $val['options']['image'];
               $canonical = $val['options']['canonical'];
               $priceOriginal =$val['options']['price_original'];
               $attrName =$val['options']['attributeName'] ?? ''
              

           @endphp
           <tr>
            <td><a href="{{$canonical}}"><img src="{{$image}}" alt="Product"></a></td>
            <td>{{$val['name']}}<br><small>{{$attrName}}</small></td>
            <td>
                @if ($priceOriginal != $val['price'])
                    <span class="price-original">{{ number_format($priceOriginal) }}đ</span>
                    <span class="price-discount">{{ number_format($val['price']) }}đ</span>
                @else
                    <span class="price-discount">{{ number_format($val['price']) }}đ</span>
                @endif
            </td>
            
            <td>{{$val['qty']}}</td>
            <td>{{number_format($val['subtotal'])}}</td>
        </tr>
           @endforeach
         
        </tbody>
        <tfoot class="summary-footer">
           
            <tr>
                <td colspan="4" class="text-foot uk-text-right">Tổng giá sản phẩm:</td>
                <td  class="text-foot">{{number_format($order['cart']['cartTotal'])}}đ</td>
            </tr>
          
            <tr>
                <td colspan="4" class="text-foot uk-text-right">Giảm giá:</td>
                <td  class="text-foot">{{number_format($order['promotion']['discount'])}}đ</td>
            </tr>
            <tr>
                <td colspan="4" class="text-foot uk-text-right">Phí ship:</td>
                <td  class="text-foot">{{number_format($order['shipping'])}}đ</td>
            </tr>
            <tr>
                <td colspan="4" class="text-foot uk-text-right">Thanh toán</td>
                <td  class="text-foot">{{number_format($order['cart']['cartTotal'] - $order['promotion']['discount'])}}đ</td>
            </tr>
        </tfoot>
    </table>

    <!-- Address & Payment Info -->
    <div class="uk-grid-small uk-child-width-1-2@s" uk-grid>
        <div>
            <div class="summary-box">
                <h4>Thông tin người nhận</h4>
                <p>{{$order->fullname}} - {{$order->phone}}</p>
                <p>{{$order->address.'-'.$order->ward_name.'-'.$order->district_name.'-'.$order->ward_name}}</p>
                <p>{{$order->description}}</p>
                <p>Hình thức thanh toán ({{ strtoupper($order->method) }})</p>
                @if (!empty($template))
                @include($template)
            @endif
                        </div>
         
        </div>
      
    </div>

    

    <!-- Buttons -->
    <div class="uk-flex uk-flex-between order-buttons">
        <button class="uk-button uk-button-default" >← CONTINUE SHOPPING</button>
       
    </div>

</div>


@endsection