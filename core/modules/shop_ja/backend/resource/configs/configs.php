<?php
    return [
        'aclsKey'=>[
            'dashboard'
        ],
        'views' => [
            'paths' => ['shop_ja' => 'backend'],
            'alias' => [
                'shop_ja.category.show' => 'shop_ja::controller.category.show',
                'shop_ja.category.com-ship' => 'shop_ja::controller.category.com-ship',
                'shop_ja.category.ship' => 'shop_ja::controller.category.ship',


            ],
        ],
        'packages' => [
            'namespaces' => [
                'ShopJa' => 'backend'
            ],
        ],

        'options'=>[
            'module:shop_ja:sim' => [
                'config' => [
                    'columns' => [
                        'lists' => [
                            'id' => ['label' => z_language('Mã', false), 'type' => 'id', 'primary' => true],
                            'GetName' => ['label' => z_language('Tên File', false),'primary' => true, 'type' => 'title','callback' => "GetName"],
                            'UserName' => ['label' => z_language('Người lập', false), 'type' => 'text','callback' => "GetUserName"],
                            'CountCompany' => ['label' => z_language('Sản phẩm', false), 'type' => 'text','callback' => "CountCompany"],
                            'GetStatus' => ['label' => z_language('Trạng thái', false), 'type' => 'status','callback'=>'GetStatus'],
                            'created_at' => ['label' => z_language('Tạo lúc', false), 'type' => 'date'],
                            'updated_at' => ['label' => z_language('Sửa lúc', false), 'type' => 'date'],
                            'GetButtonEdit' => ['label' => z_language('Hành động', false), 'type' => 'text','callback' => "GetButtonEdit"],
                        ],
                    ],
                    'pagination' => [
                        'item' => 20,
                        'router' => [
                            'edit' => ['label' => z_language('Sửa', false), 'name' => "backend:shop_ja:sim:edit", 'par' => ['date' => 'key_date']],

                        ]
                    ],
                    'config' => [
                        "type" => [
                            'status' => [
                                'label' => [
                                    '1' => z_language('Bật', false),
                                    '0' => z_language('Tắt', false),
                                ],
                                'type' => [
                                    'name' => 'label',
                                    'color' => [
                                        '1' => 'primary',
                                        '0' => 'danger'
                                    ]
                                ]
                            ],
                            'image'=>[
                                'width'  => '50px',
                                'height' => '50px',
                            ]
                        ],
                        "column"=>[
                            'image'=>[
                                'style'=>'width="50px";height="50px";text-align:center'
                            ]
                        ]
                    ]
                ],
                'data' => [
                    'pagination' => ['item' => 20],
                    'columns' => ['id', 'title'],
                    'search' => ['title'],

                ],
                'views' => [
                    'configs.layout'
                ]
            ],
            'module:shop_ja:product' => [
                'config' => [
                    'columns' => [
                        'lists' => [
                            'id' => ['label' => z_language('Mã', false), 'type' => 'id', 'primary' => true],
                            'code' => ['label' => z_language('Code', false), 'type' => 'id', 'primary' => true],
                            'title' => ['label' => z_language('Tiêu đề', false), 'type' => 'title'],
                            'price' => ['label' => z_language('Giá nhập', false), 'type' => 'number'],
                            'price_buy' => ['label' => z_language('Giá Bán', false), 'type' => 'number'],
                            'description' => ['label' => z_language('Mô tả', false), 'type' => 'text'],
                            'image' => ['label' => z_language('Ảnh', false), 'type' => 'image'],
                            'status' => ['label' => z_language('Trạng thái', false), 'type' => 'status'],
                            'created_at' => ['label' => z_language('Tạo lúc', false), 'type' => 'date'],
                            'updated_at' => ['label' => z_language('Sửa lúc', false), 'type' => 'date'],

                            'GetButtonEdit' => ['label' => z_language('Sửa', false), 'type' => 'number','callback' => "GetButtonEdit"],
                        ],
                    ],
                    'pagination' => [
                        'item' => 20,
                        'router' => [
                            'edit' => ['label' => z_language('Sửa', false), 'name' => "backend:shop_ja:product:edit", 'par' => ['id' => 'id']],
                            'preview' => ['label' => z_language('Xem', false), 'name' => "backend:shop_ja:product:edit", 'par' => ['id' => 'id']],
                            'trash' => ['method' => 'post', 'label' => z_language('Xóa', false), 'name' => "backend:shop_ja:product:delete", 'par' => ['id' => 'id']],
                        ]
                    ],
                    'config' => [
                        "type" => [
                            'status' => [
                                'label' => [
                                    '1' => z_language('Bật', false),
                                    '0' => z_language('Tắt', false),
                                ],
                                'type' => [
                                    'name' => 'label',
                                    'color' => [
                                        '1' => 'primary',
                                        '0' => 'danger'
                                    ]
                                ]
                            ],
                            'image'=>[
                                'width'  => '50px',
                                'height' => '50px',
                            ]
                        ],
                        "column"=>[
                            'image'=>[
                                'style'=>'width="50px";height="50px";text-align:center'
                            ]
                        ]
                    ]
                ],
                'data' => [
                    'pagination' => ['item' => 20],
                    'columns' => ['id', 'title'],
                    'search' => ['title'],

                ],
                'views' => [
                    'configs.layout'
                ]
            ],
            'module:shop_ja:order' => [
                'config' => [
                    'columns' => [
                        'lists' => [
                            'id' => ['label' => z_language('Mã', false), 'type' => 'id', 'primary' => true],
                            'UserName' => ['label' => z_language('Người lập', false), 'type' => 'text','callback' => "GetUserName"],
                            'fullname' => ['label' => z_language('Tên Khách Hàng', false), 'type' => 'title', 'primary' => true],
                            'GetCountOrder' => ['label' => z_language('Sản phẩm', false), 'type' => 'number','callback' => "GetCountOrder"],
                            'GetStatus' => ['label' => z_language('Trạng thái', false), 'type' => 'status','callback'=>'GetStatus'],
                            'created_at' => ['label' => z_language('Tạo lúc', false), 'type' => 'date'],
                            'updated_at' => ['label' => z_language('Sửa lúc', false), 'type' => 'date'],

                        ],
                    ],
                    'pagination' => [
                        'item' => 20,
                        'router' => [
                            'edit' => ['label' => z_language('Sửa', false), 'name' => "backend:shop_ja:order:edit", 'par' => ['id' => 'id']],
                            'preview' => ['label' => z_language('Xem', false), 'name' => "backend:shop_ja:order:edit", 'par' => ['id' => 'id']],
                            'trash' => ['method' => 'post', 'label' => z_language('Xóa', false), 'name' => "backend:shop_ja:order:delete", 'par' => ['id' => 'id']],
                        ]
                    ],
                    'config' => [
                        "type" => [
                            'status' => [
                                'label' => [
                                    '1' => z_language('Bật', false),
                                    '0' => z_language('Tắt', false),
                                ],
                            ],
                        ],
                        "column"=>[

                        ]
                    ]
                ],
                'data' => [
                    'pagination' => ['item' => 20],
                    'columns' => ['id', 'title'],
                    'search' => ['title'],
                ],
                'views' => [
                    'configs.layout'
                ]
            ],
            'module:shop_ja:ship' => [
                'config' => [
                    'columns' => [
                        'lists' => [
                            'id' => ['label' => z_language('Mã', false), 'type' => 'id', 'primary' => true],
                            'GetNameCategory' => ['label' => z_language('Công Ty', false), 'type' => 'title','callback' => "GetNameCategory"],
                            'GetEqual' => ['label' => z_language('So sánh', false), 'type' => 'text','callback' => "GetEqual"],
                            'value_start' => ['label' => z_language('Bắt đầu', false), 'type' => 'number'],
                            'value_end' => ['label' => z_language('Kết thúc', false), 'type' => 'number'],
                            'GetUnit' => ['label' => z_language('Đơn vị', false), 'type' => 'status','callback' => "GetUnit"],
                            'created_at' => ['label' => z_language('Tạo luc', false), 'type' => 'date'],
                            'updated_at' => ['label' => z_language('Sửa lúc', false), 'type' => 'date'],
                        ],
                    ],
                    'pagination' => [
                        'item' => 20,
                        'router' => [
                            'edit' => ['label' => z_language('Sửa', false), 'name' => "backend:shop_ja:ship:edit", 'par' => ['id' => 'id']],
                            'copy' => ['label' => z_language('Nhân bản', false), 'name' => "backend:shop_ja:ship:copy", 'par' => ['id' => 'id']],
                            'trash' => ['method' => 'post', 'label' => z_language('Xóa', false), 'name' => "backend:shop_ja:ship:delete", 'par' => ['id' => 'id']],
                        ]
                    ],
                    'config' => [
                        "type" => [

                        ],
                        "column"=>[
                            'image'=>[
                                'style'=>'width="50px";height="50px";text-align:center'
                            ]
                        ]
                    ]
                ],
                'data' => [
                    'pagination' => ['item' => 20],
                    'columns' => ['id', 'title'],
                    'search' => ['title'],

                ],
                'views' => [
                    'configs.layout'
                ]
            ],
            'module:shop_ja:order:excel' => [
                'config' => [
                    'columns' => [
                        'lists' => [
                            'id' => ['label' => z_language('Mã', false), 'type' => 'id', 'primary' => true],
                            'GetName' => ['label' => z_language('Tên File', false),'primary' => true, 'type' => 'title','callback' => "GetName"],
                            'UserName' => ['label' => z_language('Người lập', false), 'type' => 'text','callback' => "GetUserName"],
                            'CountCompany' => ['label' => z_language('Sản phẩm', false), 'type' => 'text','callback' => "CountCompany"],
                            'GetStatus' => ['label' => z_language('Trạng thái', false), 'type' => 'status','callback'=>'GetStatus'],
                            'created_at' => ['label' => z_language('Tạo lúc', false), 'type' => 'date'],
                            'updated_at' => ['label' => z_language('Sửa lúc', false), 'type' => 'date'],
                            'GetButtonEdit' => ['label' => z_language('Hành động', false), 'type' => 'text','callback' => "GetButtonEdit"],
                        ],
                    ],
                    'pagination' => [
                        'item' => 20,
                        'router' => [
                            'edit' => ['hide'=>'hide','label' => z_language('Sửa', false), 'name' => "backend:shop_ja:order:excel:edit", 'par' => ['id' => 'id']],
//                            'preview' => ['label' => z_language('Preview', false), 'name' => "backend:shop_ja:order:excel:edit", 'par' => ['id' => 'id']],
//                            'trash' => ['method' => 'post', 'label' => z_language('Xóa', false), 'name' => "backend:shop_ja:order:excel:delete", 'par' => ['id' => 'id']],
                        ]
                    ],
                    'config' => [
                        "type" => [
                            'status' => [
                                'label' => [
                                    '1' => z_language('Bật', false),
                                    '0' => z_language('Tắt', false),
                                ],
                            ],
                        ],
                        "column"=>[

                        ]
                    ]
                ],
                'data' => [
                    'pagination' => ['item' => 20],
                    'columns' => ['id', 'title'],
                    'search' => ['title'],
                ],
                'views' => [
                    'configs.layout'
                ]
            ],
            'core:shop_ja:user:list' => [
                'config' => [
                    'columns' => [
                        'lists' => [
                            'id' => ['label' => z_language('Mã', false), 'type' => 'id', 'primary' => true, 'order_by' => "numeric"],
                            'name' => ['label' => z_language('Tên', false), 'type' => 'title', 'primary' => true, 'order_by' => 'alpha'],
                            'username' => ['label' => z_language('Tài khoản', false), 'type' => 'text', 'primary' => true, 'order_by' => 'amount'],
                            'GetGroupRole' => ['label' => z_language('Nhóm', false), 'type' => 'number','callback' => "GetGroupRole"],
                            'avatar' => ['label' => z_language('Ảnh', false), 'type' => 'image'],
                            'status' => ['label' => z_language('Trạng thái', false), 'type' => 'status', 'order_by' => 'amount'],
                            'created_at' => ['label' => z_language('Ngày tạo', false), 'type' => 'date'],
                            'updated_at' => ['label' => z_language('Ngày cập nhật', false), 'type' => 'date'],
                            'ctvButton' => ['label' => z_language('Thông tin', false), 'type' => 'number','callback' => "ctvButton"],
                            'ctvOptionButton' => ['label' => z_language('Cấu hình', false), 'type' => 'number','callback' => "ctvOptionButton"],
                        ],
                    ],
                    'pagination' => [
                        'item' => 20,
                        'router' => [
                            'edit' => ['label' => z_language('Sửa', false), 'name' => "backend:user:edit", 'par' => ['id' => 'id']],

                            'trash' => ['method' => 'post', 'label' => z_language('Xóa', false), 'name' => "backend:user:delete", 'par' => ['id' => 'id']],
                        ]
                    ],
                    'config' => [
                        "type" => [
                            'image' => [
                                'width' => 100,
                                'height' => 100
                            ],
                            'status' => [
                                'label' => [
                                    '1' => z_language('Bật', false),
                                    '0' => z_language('Tắt', false),
                                ],
                                'type' => [
                                    'name' => 'label',
                                    'color' => [
                                        '1' => 'primary',
                                        '0' => 'danger'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'data' => [
                    'pagination' => ['item' => 20],
                    'columns' => ['id', 'name'],
                    'search' => ['name'],
                ],
                'option' => [

                ]
            ],
            'module:shop_ja:tracking' => [
                'config' => [
                    /*
                        - Ngày đặt
                        - Tên KH
                        - Mã tracking
                        - Thời gian check cuối cùng
                        - Thông tin check
                        - Mã đơn hàng
                        - Cty chuyển phát
                        - Form
                        - Link KH
                        - Trang kiểm tra
                     */
                    'columns' => [
                        'lists' => [
                            'get_note' => ['label' => z_language('Ghi chú'), 'type' => 'text','callback' => "get_note"],
                            'cancelOrder' => ['label' => 'Hủy Check', 'type' => 'text','callback' => "cancelOrder"],
                            'id' => ['label' => z_language('Mã', false), 'type' => 'id', 'primary' => true],
                            'get_info_create_order' => ['label' => z_language('Ngày lập | Check cuối'), 'type' => 'text','callback' => "get_info_create_order"],
                            'get_info_fullname' => ['label' => z_language('Tên KH'), 'type' => 'text','callback' => "get_info_fullname"],
                            'tracking_id' => ['label' => z_language('Mã Tracking', false), 'type' => 'text'],
                            'updated_at' => ['label' => z_language('Ngày kiểm tra', false), 'type' => 'date'],
                            'get_results' => ['label' => z_language('Ngày | Nội dung', false), 'type' => 'text','callback' => "get_results"],
                            'order_id' => ['label' => z_language('Mã đơn hàng', false), 'type' => 'id', 'primary' => true],
                            'type' => ['label' => z_language('CT chuyển phát', false), 'type' => 'title'],
                            'company' => ['label' => z_language('CT sản phẩm', false), 'type' => 'title'],
                            'status' => ['label' => z_language('Trạng thái', false), 'type' => 'status','onClick'=>'updateStatus(this)'],
                            'get_info_link' => ['label' => z_language('Fb'), 'type' => 'text','callback' => "get_info_link"],
                            'get_info_check' => ['label' => z_language('Kiểm tra'), 'type' => 'text','callback' => "get_info_check"],
                            'get_info_redeliver' => ['label' => z_language('Gửi lại'), 'type' => 'text','callback' => "get_info_redeliver"],
                            'get_info_1' => ['label' => 'FB | Kiểm tra | Gửi lại đơn', 'type' => 'text','callback' => "get_info_1"],
//                            'count' => ['label' => z_language('Số lần', false), 'type' => 'number'],
//                            'GetTimeCheck' => ['label' => z_language('Thơi gian chờ', false), 'type' => 'text','callback' => "GetTimeCheck"],
//                            'created_at' => ['label' => z_language('Ngày đăng', false), 'type' => 'date'],
//                            'updated_at' => ['label' => z_language('Ngày sửa', false), 'type' => 'date'],
                        ],
                    ],
                    'pagination' => [
                        'item' => 20,
                        'router' => [
//                            'edit' => ['label' => z_language('Edit', false), 'name' => "backend:shop_ja:product:edit", 'par' => ['id' => 'id']],
//                            'preview' => ['label' => z_language('Preview', false), 'name' => "backend:shop_ja:product:edit", 'par' => ['id' => 'id']],
//                            'trash' => ['method' => 'post', 'label' => z_language('Trash', false), 'name' => "backend:shop_ja:product:delete", 'par' => ['id' => 'id']],
                        ]
                    ],
                    'config' => [
                        "type" => [
                            'status' => [
                                'label' => [
                                    '1' => z_language('Thành công', false),
                                    '2' => z_language('Đang kiểm tra', false),
                                    '0' => z_language('Chưa kiểm tra', false),
                                    '3' => z_language('Đã kiểm tra', false),
                                    '10' => z_language('Sai mã', false),
                                ],
                                'type' => [
                                    'name' => 'label',
                                    'color' => [
                                        '0' => 'default',
                                        '1' => 'primary',
                                        '2' => 'danger',
                                        '3' => 'info'
                                    ]
                                ]
                            ],
                            'image'=>[
                                'width'  => '50px',
                                'height' => '50px',
                            ]
                        ],
                        "column"=>[
                            'image'=>[
                                'style'=>'width="50px";height="50px";text-align:center'
                            ]
                        ]
                    ]
                ],
                'data' => [
                    'pagination' => ['item' => 20],
                    'columns' => ['id', 'title'],
                    'search' => ['title'],

                ],
                'views' => [
                    'configs.layout'
                ]
            ],
        ],
        'configs' => [
            'lists'=>[
                'shop_ja' => [
                    'view' => [
                            'post' => [
                                'view' => 'shop_ja::configs.company',
                                'label' => z_language('Công Ty'),
                            ],
                            'excel_export' => [
                                'view' => 'shop_ja::configs.excel',
                                'label' => z_language('Excel Config'),
                            ],
                        ],
                        'label' => z_language("Cửa hàng", false),
                        'data' => [

                        ]
                ],

            ]
        ],
    ];
