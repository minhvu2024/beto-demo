<?php
return [
    'routers' => [
        'backend' => [
            'guest' => [
                "namespace" => "User\Http\Controllers",
                "controller" => "AuthController",
                "router" => [
                    "login" => [
                        "url" => "/login",
                        "action" => "getLogin",
                        "name" => "backend:login",
                        "guard" => "",
                        "cache" => 60
                    ],
                    "login:post" => [
                        "url" => "/login/action",
                        "action" => "postLogin",
                        "method" => ["post"],
                        "guard" => ""
                    ],

                    "logout" => [
                        "url" => "/logout",
                        "action" => "logout",
                        "method" => ["post"],
                        "name" => "backend:logout"
                    ],
                ]
            ],
            'member' => [
                "namespace" => "User\Http\Controllers",
                "controller" => "MemberController",
                "acl" => "member",
                "sub_prefix" => "/member",
                "router" => [
                    "list" => [
                        "url" => "/"
                    ],
                    "create" => [
                        "url" => "/create"
                    ],
                    "edit" => [
                        "url" => "/edit/{id}"
                    ],
                    "delete" => [
                        "url" => "/delete","method" => ['post'],
                    ],
                    "store" => [
                        "url" => "/store",
                        "method" => ['post'],
                    ],
                ]
            ],
            'user' => [
                "namespace" => "User\Http\Controllers",
                "controller" => "UserController",
                "acl" => "user",
                "sub_prefix" => "/user",
                "router" => [
                    "list" => [
                        "url" => "/"
                    ],
                    "create" => [
                        "url" => "/create"
                    ],
                    "edit" => [
                        "url" => "/edit/{id}"
                    ],
                    "delete" => [
                        "url" => "/delete","method" => ['post'],
                    ],
                    "store" => [
                        "url" => "/store",
                        "method" => ['post'],
                    ],
                ]
            ],
            'user:role' => [
                "namespace" => "User\Http\Controllers",
                "controller" => "RoleController",
                "acl" => "user:role",
                "guard" => "backend",
                "router" => [
                    "list" => [
                        "url" => "/user/role"
                    ],
                    "create" => [
                        "url" => "/user/role/create"
                    ],
                    "edit" => [
                        "url" => "/user/role/edit/{id}"
                    ],
                    "store" => [
                        "url" => "/user/role/store",
                        "method" => ['post'],
                    ],
                    "permission" =>[
                        'url' => '/user/permission/{id}/{guard}',
                        "method" => ['get','post']
                    ],
                    "error" =>[
                        'url' => '/error',
                    ],
                ]
            ],
            'user:role:error' => [
                "namespace" => "User\Http\Controllers",
                "controller" => "RoleController",
                "guard" => "backend",
                "router" => [
                    "error" =>[
                        'url' => '/error',
                    ],
                ]
            ],
        ]
    ]
];
