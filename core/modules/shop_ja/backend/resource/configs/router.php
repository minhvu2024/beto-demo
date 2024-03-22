<?php
return [
    'routers' => [
        'backend' => [
            'shop_ja:product' => [
                    "namespace" => "ShopJa\Http\Controllers",
                    "controller" => "ProductController",
                    "sub_prefix" => "/shop-ja/product",
                    "guard" => "backend",
                    "acl"=> "shop_ja:product:store",
                    "router" => [
                        "list" => [
                            "url" => "/",
                            'acl'=>true
                        ],
                        "create" => [
                            "url" => "/create",
                        ],
                        "edit" => [
                            "url" => "/edit/{id}",
                        ],
                        "delete" => [
                            "url" => "/delete/{id}",
                            "method" => ['post'],
                        ],
                        "store" => [
                            "url" => "/store",
                            "method" => ['post'],
                        ]
                    ]
                ],
//                'shop_ja:order' => [
//                    "namespace" => "ShopJa\Http\Controllers",
//                    "controller" => "OrderController",
//                    "sub_prefix" => "/shop-ja/order",
//                    "guard" => "backend",
//                    "acl"=> "shop_ja:order:store",
//                    "router" => [
//                        "list" => [
//                            "url" => "/",
//                            'acl'=>true
//                        ],
//                        "create" => [
//                            "url" => "/create",
//                        ],
//                        "edit" => [
//                            "url" => "/edit/{id}",
//                        ],
//                        "delete" => [
//                            "url" => "/delete/{id}",
//                            "method" => ['post'],
//                        ],
//                        "store" => [
//                            "url" => "/store",
//                            "method" => ['post'],
//                        ],
//                        "ajax" => [
//                            "url" => "/ajax", "method" => ['post'],
//                        ],
//                    ]
//                ],
                'shop_ja:japan:category:ship' => [
                    "namespace" => "ShopJa\Http\Controllers",
                    "controller" => "CategoryController",
                    "sub_prefix" => "/shop-ja/category/japan/com-ship",
                    "guard" => "backend",
                    "acl"=> "shop_ja:category:com-ship",
                    "module" => [
                        "name" => "admin",
                        "type" => "module"
                    ],
                    "router" => [
                        "show" => [
                            "url" => "/",
                            'defaults' => [
                                "type" => "shop-ja:japan:category:com-ship",
                                "view_render" => "shop_ja.category.com-ship",
                                "slug" => false,
                                'nestable'=>'\ShopJa\Libs\CategoryNestableShip'
                            ]
                        ],
                        "ajaxComShip" => [
                            "url" => "/ajax-com-ship", "method" => ['post'],
                        ],

                    ]
                ],
                'shop_ja:category' => [
                    "namespace" => "Admin\Http\Controllers",
                    "controller" => "CategoryController",
                    "sub_prefix" => "/shop-ja/product/category",
                    "guard" => "backend",
                    "acl"=> "shop_ja:category",
                    "module" => [
                        "name" => "admin",
                        "type" => "module"
                    ],
                    "router" => [
                        "show" => [
                            "url" => "/",
                            'defaults' => [
                                "type" => "shop-ja:product:category",
                                "view_render" => "shop_ja.category.ship",
                                "views" => "blog::module.admin.category",
                                'nestable'=>'\ShopJa\Libs\CategoryShipNestable'
                            ]
                        ],

                    ]
                ],
                'shop_ja:japan:category' => [
                    "namespace" => "ShopJa\Http\Controllers",
                    "controller" => "CategoryController",
                    "sub_prefix" => "/shop-ja/category/japan",

                    "guard" => "backend",
                    "acl"=> "shop_ja:category",
                    "module" => [
                        "name" => "admin",
                        "type" => "module"
                    ],
                    "router" => [
                        "show" => [
                            "url" => "/{product_id?}",
                            "wheres"=>['product_id'=>'[0-9]+'],
                            'defaults' => [
                                "type" => "shop-ja:japan:category",
                                "view_render" => "shop_ja.category.show",
                                "slug" => false,
                                'nestable'=>'\ShopJa\Libs\CategoryNestable'
                            ]
                        ],
                        "ajax" => [
                            "url" => "/ajax", "method" => ['post'],
                        ],
                    ]
                ],
                'shop_ja:excel' => [
                    "namespace" => "ShopJa\Http\Controllers",
                    "controller" => "ExcelController",
                    "sub_prefix" => "/shop-ja/order/excel",
                    "guard" => "backend",
                    "acl"=> "shop_ja:order:excel",
                    "module" => [
                        "name" => "admin",
                        "type" => "module"
                    ],
                    "router" => [
                        "list" => [
                            "url" => "/"
                        ],
                        "YAMADA" => [
                            "url" => "/YAMADA"
                        ],
                        "KOGYJA" => [
                            "url" => "/KOGYJA"
                        ],
                        "OHGA" => [
                            "url" => "/OHGA"
                        ],
                        "FUKUI" => [
                            "url" => "/FUKUI"
                        ],
                        "KURICHIKU" => [
                            "url" => "/KURICHIKU"
                        ],
                    ]
                ],

            'shop_ja:ship' => [
                "namespace" => "ShopJa\Http\Controllers",
                "controller" => "ShipController",
                "sub_prefix" => "/shop-ja/ship",
                "guard" => "backend",
                "acl"=> "shop_ja:ship:store",
                "router" => [
                    "list" => [
                        "url" => "/",
                        'acl'=>true
                    ],
                    "create" => [
                        "url" => "/create",
                    ],
                    "edit" => [
                        "url" => "/edit/{id}",
                    ],
                    "copy" => [
                        "url" => "/copy/{id}",
                    ],
                    "delete" => [
                        "url" => "/delete/{id}",
                        "method" => ['post'],
                    ],
                    "store" => [
                        "url" => "/store",
                        "method" => ['post'],
                    ],
                ]
            ],
            'shop_ja:order:excel' => [
                "namespace" => "ShopJa\Http\Controllers",
                "controller" => "OrderExcelController",
                "sub_prefix" => "/shop-ja/order/excel-form",
                "guard" => "backend",
                "acl"=> "shop_ja:order:excel",
                "name"=>"Hóa đơn",
                "router" => [
                    "list" => [
                        "url" => "/list/{admin_id?}",
                    ],
                    "create" => [
                        "url" => "/create",
                        "method" => ['post','get'],
                    ],
                    "edit" => [
                        "url" => "/edit/{id}",
                        "method" => ['post','get'],
                    ],
                    "copy" => [
                        "url" => "/copy/{id}",
                    ],
                    "delete" => [
                        "url" => "/delete/{id}",
                        "method" => ['post'],
                    ],
                    "export" => [
                        "url" => "/export",
                        "method" => ['post'],
                    ],
                    "imports" => [
                        "url" => "/imports",
                        "method" => ['get','post'],
                        "acl"=>true,
                    ],
                    "tracking" => [
                        "url" => "/tracking",
                        "method" => ['get','post'],
                        "acl"=>true,
                    ],
                    "tracking_list" => [
                        "url" => "/tracking-list",
                        "method" => ['get','post'],
                        "acl"=>true,
                    ],
                    "show" => [
                        "url" => "/show/{company?}/{date?}/{hour?}/{type?}",
                        "method" => ['get','post'],
                        "acl"=>true,
                    ],
                    "store" => [
                        "url" => "/store",
                        "method" => ['post'],
                    ],
                    "search" => [
                        "url" => "/search",
                        "method" => ['get','post'],
                    ],
                ]
            ],
            'shop_ja:order:search' => [
                "namespace" => "ShopJa\Http\Controllers",
                "controller" => "OrderExcelController",
                "sub_prefix" => "/shop-ja/order/search",
                "guard" => "backend",
                "acl"=> "shop_ja:order:search",
                "router" => [
                    "search" => [
                        "url" => "/search",
                        "method" => ['get','post'],
                    ],
                ]
            ],
            'shop_ja:order:action' => [
                "namespace" => "ShopJa\Http\Controllers",
                "controller" => "OrderExcelController",
                "sub_prefix" => "/shop-ja/order/action",
                "guard" => "backend",
                "acl"=> "shop_ja:order:action",
                "router" => [
                    "imports" => [
                        "url" => "/imports",
                        "method" => ['get','post'],
                    ],
                    "show" => [
                        "url" => "/show/{company?}/{date?}/{hour?}",
                        "method" => ['get','post'],
                    ],

                ]
            ],
            'shop_ja:sim' => [
                "namespace" => "ShopJa\Http\Controllers",
                "controller" => "SimController",
                "sub_prefix" => "/shop-ja/sim",
                "guard" => "backend",
                "acl"=> "shop_ja:sim",
                "router" => [
                    "list" => [
                        "url" => "/",
                        'acl'=>true
                    ],
                    "create" => [
                        "url" => "/create/{date}",

                    ],
                    "edit" => [
                        "url" => "/edit/{date}",

                    ],
                    "store" => [
                        "url" => "/store",
                        "method" => ['post'],
                    ],
                    "delete" => [
                        "url" => "/delete/{id}",
                        "method" => ['post'],
                    ],
                    "export" => [
                        "url" => "/export",
                        "method" => ['get','post'],
                    ],
                    "notification" => [
                        "url" => "/notification",
                        "method" => ['post','get'],
                    ],
                    "show" => [
                        "url" => "/show/{month}",
                        "method" => ['post','get'],
                    ],
                ]
            ],
            'shop_ja:user' => [
                "namespace" => "ShopJa\Http\Controllers",
                "controller" => "UserController",
                "sub_prefix" => "/shop-ja/user",
                "guard" => "backend",
                "acl"=> "user",
                "router" => [
                    "list" => [
                        "url" => "/",
                    ],
                    "option" => [
                        "url" => "/option/{id}","method" => ['post','get'],
                    ],
                ]
            ],
            'shop_ja:user_cvt' => [
                "namespace" => "ShopJa\Http\Controllers",
                "controller" => "UserController",
                "sub_prefix" => "/shop-ja/user-ctv",
                "guard" => "backend",
                "acl"=> "user",
                "router" => [
                    "ctv" => [
                        "url" => "/",
                    ],
                ]
            ],
            'dashboard' => [
                    "namespace" => "ShopJa\Http\Controllers",
                    "controller" => "DashboardController",
                    "guard" => "backend",
                    "acl"=> "dashboard",
                    "router" => [
                        "export"=>[
                            "url" => "/dashboard/export","method" => ['post'],
                        ],
                        "analytics"=>[
                            "url" => "/dashboard/analytics","method" => ['post'],
                        ]
                    ]
                ],
            ]
    ]
];
