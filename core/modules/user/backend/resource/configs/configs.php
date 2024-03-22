<?php
return [
    'views' => [
        'paths' => ['user' => 'backend'],
        'alias' => [

        ]
    ],
    'acls'=>[
        'descriptions'=>[
            'backend:shop_ja:category:show'=>'Chức năng chuyển phát',
            'backend:shop_ja:japan:category:show'=>'Chức năng quản lý ship',
        ]
    ],
    'packages' => [
        'namespaces' => [
            'User' => 'backend'
        ],
        'providers' => [
            "User\Providers\ComposerServiceProvider" => "User\Providers\ComposerServiceProvider",
        ]
    ],
    'options' => [
        'core:user:list' => [
            'config' => [
                'columns' => [
                    'lists' => [
                        'id' => ['label' => z_language('Id', false), 'type' => 'id', 'primary' => true, 'order_by' => "numeric"],
                        'name' => ['label' => z_language('Name', false), 'type' => 'title', 'primary' => true, 'order_by' => 'alpha'],
                        'username' => ['label' => z_language('Username', false), 'type' => 'text', 'primary' => true, 'order_by' => 'amount'],
                        'avatar' => ['label' => z_language('Avatar', false), 'type' => 'image'],
                        'status' => ['label' => z_language('Status', false), 'type' => 'status', 'order_by' => 'amount'],
                        'created_at' => ['label' => z_language('Create At', false), 'type' => 'date'],
                        'updated_at' => ['label' => z_language('Update At', false), 'type' => 'date']
                    ],
                ],
                'pagination' => [
                    'item' => 20,
                    'router' => [
                        'edit' => ['label' => z_language('Edit', false), 'name' => "backend:user:edit", 'par' => ['id' => 'id']],
                        'preview' => ['label' => z_language('Preview', false), 'name' => "backend:user:edit", 'par' => ['id' => 'id']],
                        'trash' => ['method' => 'post', 'label' => z_language('Trash', false), 'name' => "backend:user:delete", 'par' => ['id' => 'id']],
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
                                '1' => z_language('Public', false),
                                '0' => z_language('UnPublic', false),
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
        'core:member:list' => [
            'config' => [
                'columns' => [
                    'lists' => [
                        'id' => ['label' => z_language('Id', false), 'type' => 'id', 'primary' => true, 'order_by' => "numeric"],
                        'name' => ['label' => z_language('Name', false), 'type' => 'title', 'primary' => true, 'order_by' => 'alpha'],
                        'username' => ['label' => z_language('Username', false), 'type' => 'text', 'primary' => true, 'order_by' => 'amount'],
                        'avatar' => ['label' => z_language('Avatar', false), 'type' => 'image'],
                        'status' => ['label' => z_language('Status', false), 'type' => 'status', 'order_by' => 'amount'],
                        'created_at' => ['label' => z_language('Create At', false), 'type' => 'date'],
                        'updated_at' => ['label' => z_language('Update At', false), 'type' => 'date']
                    ],
                ],
                'pagination' => [
                    'item' => 20,
                    'router' => [
                        'edit' => ['label' => z_language('Edit', false), 'name' => "backend:member:edit", 'par' => ['id' => 'id']],

                        'trash' => ['method' => 'post', 'label' => z_language('Trash', false), 'name' => "backend:member:delete", 'par' => ['id' => 'id']],
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
                                '1' => z_language('Public', false),
                                '0' => z_language('UnPublic', false),
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
        ]
    ],
    'configs' => [
       'lists'=>[
           'user' => [
               'view' => 'user::configs.config',
               'label' => z_language("Members", false),
               'data' => [

               ]
           ]
       ]
    ],
];
