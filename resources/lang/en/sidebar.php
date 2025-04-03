<?php
return [
    'module' => [
        [
            'title' => 'Home',
            'icon' => 'bx bx-grid-alt nav_icon',
            'route' => 'dashboard.index',
        ],
        [
            'title' => 'Members',
            'icon' => 'bx bx-user nav_icon',
            'subModule' => [
                [
                    'title' => 'Members',
                    'route' => 'user.index',
                ],
                [
                    'title' => 'Member Groups',
                    'route' => 'user.catalogue.index',
                ],
                [
                    'title' => 'Permissions',
                    'route' => 'permission.index',
                ],
            ],
        ],
        [
            'title' => 'Customers',
            'icon' => 'bx bx-user-circle nav_icon',
            'subModule' => [
                [
                    'title' => 'Customers',
                    'route' => 'customer.index',
                ],
                [
                    'title' => 'Customer Groups',
                    'route' => 'customer.catalogue.index',
                ],
            ],
        ],
        [
            'title' => 'News',
            'icon' => 'bx bx-news nav_icon',
            'subModule' => [
                [
                    'title' => 'Articles',
                    'route' => 'post.index',
                ],
                [
                    'title' => 'Categories',
                    'route' => 'post.catalogue.index',
                ],
            ],
        ],
        [
            'title' => 'Products',
            'icon' => 'bx bx-box nav_icon',
            'subModule' => [
                [
                    'title' => 'Products',
                    'route' => 'product.index',
                ],
                [
                    'title' => 'Categories',
                    'route' => 'product.catalogue.index',
                ],
                [
                    'title' => 'Attributes',
                    'route' => 'attribute.catalogue.index',
                ],
                [
                    'title' => 'Attribute Details',
                    'route' => 'attribute.index',
                ],
            ],
        ],
        [
            'title' => 'Marketing',
            'icon' => 'bx bxs-discount nav_icon',
            'subModule' => [
                [
                    'title' => 'Promotions',
                    'route' => 'promotion.index',
                ],
                [
                    'title' => 'Customer Sources',
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
                    'title' => 'Menu Settings',
                    'route' => 'menu.index',
                ],
            ],
        ],
        [
            'title' => 'Settings',
            'icon' => 'bx bx-cog nav_icon',
            'subModule' => [
                [
                    'title' => 'Languages',
                    'route' => 'language.index',
                ],
                [
                    'title' => 'Modules',
                    'route' => 'generate.index',
                ],
                [
                    'title' => 'Widgets',
                    'route' => 'widget.index',
                ],
            ],
        ],
    ],
];
