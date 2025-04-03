<?php

return [

    'paths' => ['ajax/location/getLocation'], // Thêm các đường dẫn bạn muốn áp dụng CORS (ví dụ: 'ajax/location/*')
    'allowed_methods' => ['*'], // Cho phép tất cả các HTTP methods
    'allowed_origins' => ['*'], // Cho phép tất cả các nguồn gốc
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Cho phép tất cả headers
    'exposed_headers' => [],
    'allowed_origins' => [
    'http://127.0.0.1:8000', // Thêm domain của bạn vào đây
],
    'max_age' => 0,
    'supports_credentials' => false,
];
