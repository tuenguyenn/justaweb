<!DOCTYPE html>
<html lang="en">
<head>
    <title>Mail Đơn Hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .order-info {
            padding: 10px;
            background: #f1f1f1;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .product {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .product img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            margin-right: 10px;
        }
        .product-info {
            flex: 1;
        }
        .price-original {
            text-decoration: line-through;
            color: #888;
        }
        .price-discount {
            font-weight: bold;
            color: #ff5722;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    @php
        $order = $data['order']
    @endphp
    <div class="email-container">
        <div class="header">Cảm ơn bạn đã đặt hàng!</div>
        <div class="order-info">
            <p><strong>Mã đơn hàng:</strong> {{$order->code}}</p>
            <p><strong>Ngày đặt:</strong> {{formatDate($order->created_at)}}</p>
            <p><strong>Người nhận:</strong> {{$order->fullname}} - {{$order->phone}}</p>
            <p><strong>Địa chỉ:</strong> {{$order->address}}, {{$order->ward_name}}, {{$order->district_name}}</p>
            <p><strong>Thanh toán:</strong> {{ strtoupper($order->method) }}</p>
        </div>
        
        @foreach ($order->cart['detail'] as $val)
        <div class="product">
            <img src="{{$val['options']['image']}}" alt="Product">
            <div class="product-info">
                <p>{{$val['name']}} @if(!empty($val['options']['attributeName'])) ({{$val['options']['attributeName']}}) @endif</p>
                <p>
                    @if ($val['options']['price_original'] != $val['price'])
                        <span class="price-original">{{ number_format($val['options']['price_original']) }}đ</span>
                        <span class="price-discount">{{ number_format($val['price']) }}đ</span>
                    @else
                        <span class="price-discount">{{ number_format($val['price']) }}đ</span>
                    @endif
                </p>
                <p>Số lượng: {{$val['qty']}}</p>
            </div>
        </div>
        @endforeach
        
        <div class="order-info">
            <p><strong>Tổng cộng:</strong> {{ number_format($order['cart']['cartTotal']) }}đ</p>
            <p><strong>Giảm giá:</strong> {{ number_format($order['promotion']['discount']) }}đ</p>
            <p><strong>Phí ship:</strong> {{ number_format($order['shipping']) }}đ</p>
            <p><strong>Thanh toán:</strong> {{ number_format($order['cart']['cartTotal'] - $order['promotion']['discount']) }}đ</p>
        </div>
        
        <div class="footer">Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi.</div>
    </div>
</body>
</html>