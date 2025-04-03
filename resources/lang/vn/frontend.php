<?php
    return [
        'address' => '772 Kim Giang , Thanh Liệt , Thanh Trì, Hà Nội,',
        'hotline' => 'Hotline: 0828427851',
        'email' => 'Email: tuenguyenn2706@gmail.com',
        'workhour' => 'Giờ làm việc: 10:00 - 18:00, Mon - Sat',
        'seo' =>[
            'meta_title' => 'TN VIETNAM',
            'meta_keywords' => 'TNVIETNAM',
            'meta_description' => 'Website thương mại điện tử',
            'canonical' => config('app.url'),
            'meta_image' => 'image'
        ],
        'seo-checkout' =>[
            'meta_title' => 'Giỏ hàng | Thanh toán',
            'meta_keywords' => 'cart',
            'meta_description' => 'Giỏ hàng',
            'canonical' => write_url('checkout',true,true),
            'meta_image' => ''
        ],
        'seo-success' =>[
            'meta_title' => 'Đơn Hàng',
            'meta_keywords' => 'success',
            'meta_description' => 'success',
            'canonical' => write_url('cart/success',true,true),
            'meta_image' => ''
        ],
        'seo-login' =>[
            'meta_title' => 'Đăng nhập',
            'meta_keywords' => 'login',
            'meta_description' => 'login',
            'canonical' => write_url('/customer',true,true),
            'meta_image' => ''
        ],
        'confirm'=>[
            'none'=>'Chọn trạng thái',
            'pending' => 'Chờ xác nhận',
            'confirm' => 'Đã xác nhận ',
            'cancel' => 'Đã hủy',

        ],
        'payment' => [
            'none'=>'Chọn trạng thái',            
            'unpaid' => 'Chưa thanh toán',
            'paid' => 'Đã thanh toán',
            'fail' => 'Thanh toán không thành công',
        ],
        'delivery' =>[
            'none'=>'Chọn trạng thái',
            'pending'=>'Chuẩn bị giao hàng',
            'processing'=>'Đang giao hàng',
            'success'=>'Giao hàng thành công',
            'failed'=>'Giao hàng thất bại'


        ]

    ];