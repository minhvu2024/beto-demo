<?php
$sidebars = [
    "sidebars" => [
        "dashboard" => [
            "name" => z_language("Thông kê", false),
            "pos" => 1,
            "url" => "backend:dashboard:list",
            "icon" => "fa fa-dashboard"
        ],
        "backend:shop_ja:order:excel:list" => [
            "name" => z_language("QL Hóa Đơn",false),
            "url" => "backend:shop_ja:order:excel:list",
            "pos" => 2,
            "header" => true,
            "icon"=>"fa fa-cart-plus",
        ],
        "module:shop-ja:sim" => [
            "name" => z_language('Sim',false),
            "pos" => 3,
            "url" => "backend:shop_ja:sim:list",
            "header" => true,
            "icon"=>"fa fa-sticky-note-o",
            "items" => [
                [
                    "name" => z_language("Danh sách Sim",false),
                    "url" => "backend:shop_ja:sim:list",
                ],
                [
                    "name" => z_language("Xuất Sim hết hạn",false),
                    "url" => "backend:shop_ja:sim:export",
                ],
            ]
        ],
        "module:shop-ja::excel:show" => [
            "name" => z_language("Xuất Excel",false),
            "url" => "backend:shop_ja:order:action:show",
            "pos" => 4,
            "header" => true,
            "icon"=>"fa fa-upload",
        ],
        "media" => [
            "name" => z_language('Tài nguyên', false),
            "pos" => 5,
            "url" => "backend:dashboard:media",
            "header" => true,
            "icon"=>"fa fa-server",
        ],
        "backend:shop_ja:order:search" => [
            "name" => z_language("Tìm kiếm hóa đơn",false),
            "url" => "backend:shop_ja:order:search:search",
            "pos" => 6,
            "header" => true,
            "icon"=>"fa fa-search",
        ],
        "shop-ja:user" => [
            "name" => z_language('Tài khoản',false),
            "pos" => 7,
            "url" => "",
            "header" => true,
            "icon" => "fa fa-users",
            "order"=>1,
            "items" => [
                "1"=>[
                    "name" => z_language("Tài khoản", false),
                    "url" => "backend:shop_ja:user:list",
                ],
                "2"=>[
                    "name" => z_language("Tài khoản CTV", false),
                    "url" => "backend:shop_ja:user_cvt:ctv",
                ],
                "3"=>[
                    "name" => z_language("Quyền", false),
                    "url" => "backend:user:role:list",
                ]
            ]
        ],
        "module:shop-ja:excel:imports" => [
            "name" =>z_language("Nhập Checking",false),
            "pos" => 8,
            "url" => "backend:shop_ja:sim:list",
            "header" => true,
            "icon"=>"fa fa-sticky-note-o",
            "items" => [
                [
                    "name" =>z_language("Danh sách",false),
                    "url" => "backend:shop_ja:order:excel:tracking_list",
                ],
                [
                    "name" => z_language("Nhập dữ liệu",false),
                    "url" => "backend:shop_ja:order:action:imports",
                ],
                [
                    "name" => z_language("Xử lý",false),
                    "url" => "backend:shop_ja:order:excel:tracking",
                ],
            ]
        ],
        "user"=>false,
        "configuration" => [
            "name" => z_language('Cấu hình', false),
            "pos" => 9,
            "url" => "backend:configuration:list",
        ],
        "module:admin" => [
            "name" => z_language('Mở rộng',false),
            "pos" => 12,
            "url" => "",
            "header" => true,
            "icon"=>"fa fa-newspaper-o",
            "items" => [
                "dashboard" =>false,
                'log'=>false,
                'media'=>false,
                'configuration'=>false,
                'name'=>false,
                'language'=>false,
            ]
         ],
        "module:system" => [
            "name" => z_language('Hệ thống',false),
            "pos" => 11,
            "url" => "",
            "header" => true,
            "icon"=>"fa fa-newspaper-o",
            "items" => [
                "log" => [
                    "name" => z_language("Nhật ký", false),
                    "pos" => 9,
                    "url" => "backend:log:list",
                    "icon" => "fa fa-file-text"
                ],
                "language" => [
                    "name" => z_language("Ngôn ngữ", false),
                    "pos" => 1,
                    "url" => "backend:language:list",
                    "icon" => "fa fa-language"
                ],
                "backend:announce:list" => [
                    "name" => z_language("Thông báo",false),
                    "url" => "backend:announce:list",
                    "pos" => 2,
                    "header" => true,
                    "icon"=>"fa fa fa-envelope",
                ],
                "backend:shop_ja:product:list" => [
                    "name" => z_language("Sản Shẩm",false),
                    "url" => "backend:shop_ja:product:list",
                    "pos" => 2,
                    "header" => true,
                    "icon"=>"fa fa fa-table",
                ],
                "backend:shop_ja:category:show" => [
                    "name" => z_language("CT Chuyển Phát",false),
                    "url" => "backend:shop_ja:category:show",
                    "pos" => 2,
                    "header" => true,
                    "icon"=>"fa fa-cab",
                ],
                "backend:shop_ja:japan:category:show" => [
                    "name" => z_language("QL Tỉnh",false),
                    "url" => "backend:shop_ja:japan:category:show",
                    "pos" => 2,
                    "header" => true,
                    "icon"=>"fa  fa-thumbs-up",
                ],
                "backend:shop_ja:ship:list" => [
                    "name" => z_language("QL Chuyển phát",false),
                    "url" => "backend:shop_ja:ship:list",
                    "pos" => 2,
                    "header" => true,
                    "icon"=>"fa fa-inbox",
                ],
                "backend:shop_ja:japan:category:ship:show" => [
                    "name" => z_language("QL COU",false),
                    "url" => "backend:shop_ja:japan:category:ship:show",
                    "pos" => 2,
                    "header" => true,
                    "icon"=>"fa fa-newspaper-o",
                ],
            ]
        ],
    ]
];
return $sidebars;