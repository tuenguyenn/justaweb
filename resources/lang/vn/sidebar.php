<?php
    return [
        'module' => [
            [
                'title' => 'Trang chủ',
                'icon' => 'bx bx-grid-alt nav_icon',
                'route' => 'dashboard.index',
            ],
            [
                'title' => 'Thành Viên',
                'icon' => 'bx bx-user nav_icon',
                'subModule' => [
                    [
                        'title' => 'Thành Viên',
                        'route' => 'user.index',
                    ],
                    [
                        'title' => 'Nhóm thành viên',
                        'route' => 'user.catalogue.index',
                    ],
                    [
                        'title' => 'Quyền',
                        'route' => 'permission.index',
                    ],
                ],
            ],
            [
                'title' => 'Đơn Hàng',
                
                'icon' => 'bx bxs-shopping-bag nav_icon',
                'subModule' => [
                    [
                        'title' => 'Đơn Hàng',
                        'route' => 'order.index',
                    ],
                    [
                        'title' => 'Nhóm Đơn Hàng',
                        'route' => 'customer.catalogue.index',
                    ],
                   
                ],
            ],
            [
                'title' => 'Khách hàng',
                'icon' => 'bx bx-user-circle nav_icon',
                'subModule' => [
                    [
                        'title' => 'Khách hàng',
                        'route' => 'customer.index',
                    ],
                    [
                        'title' => 'Nhóm Khách hàng',
                        'route' => 'customer.catalogue.index',
                    ],
                   
                ],
            ],
            [
                'title' => 'Bình luận',
                'icon' => 'bx bx-comment-dots nav_icon',
                'subModule' => [
                    [
                        'title' => 'Comment',
                        'route' => 'review.index',
                    ],
                    [
                        'title' => 'Danh mục',
                        'route' => 'post.catalogue.index',
                    ],
                ],
            ],
            [
                'title' => 'Tin tức',
                'icon' => 'bx bx-news nav_icon',
                'subModule' => [
                    [
                        'title' => 'Bài viết',
                        'route' => 'post.index',
                    ],
                    [
                        'title' => 'Danh mục',
                        'route' => 'post.catalogue.index',
                    ],
                ],
            ],
            [
                'title' => 'Sản phẩm',
                'icon' => 'bx bx-box nav_icon',
                'subModule' => [
                    [
                        'title' => 'Sản phẩm',
                        'route' => 'product.index',
                    ],
                    [
                        'title' => 'Danh mục',
                        'route' => 'product.catalogue.index',
                    ],
                    [
                        'title' => 'Thuộc tính',
                        'route' => 'attribute.catalogue.index',
                    ],
                    [
                        'title' => 'Thuộc tính chi tiết',
                        'route' => 'attribute.index',
                    ],
                ],
            ],
            [
                'title' => 'Marketing',
                'icon' => 'bx bxs-discount nav_icon',
                'subModule' => [
                    [
                        'title' => 'Khuyến mãi',
                        'route' => 'promotion.index',
                    ],
                    [
                        'title' => 'Nguồn khách',
                        'route' => 'source.index',
                    ],
                  
                ],
            ],
            [
                'title' => 'Banner/Slide',
                'icon' => 'bx bx-images nav_icon',
                'subModule' => [
                 
                    [
                        'title' => 'Banner/Slide',
                        'route' => 'slide.index',
                    ],
                    
                ],
            ],
            
            [
                'title' => 'Menu',
                'icon' => 'bx bx-menu nav_icon',
                'subModule' => [
                 
                    [
                        'title' => 'Cài đặt Menu',
                        'route' => 'menu.index',
                    ],
                    
                ],
            ],
            
            
            [
                'title' => 'Cấu hình',
                'icon' => 'bx bx-cog nav_icon',
                'subModule' => [
                    [
                        'title' => 'Ngôn ngữ',
                        'route' => 'language.index',
                    ],
                    [
                        'title' => 'Module',
                        'route' => 'generate.index',
                    ],
                    [
                        'title' => 'Widget',
                        'route' => 'widget.index',
                    ],
                ],
            ],
            
        ],
    ];