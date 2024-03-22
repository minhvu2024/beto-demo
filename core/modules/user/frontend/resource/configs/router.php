<?php
return [
    'routers' => [
        'frontend' => [
            'user' => [
                "namespace" => "UserFront\Http\Controllers",
                "controller" => "UserController",
                "router" => [
                    "info" => [
                        "url" => "user/info",
                        "guard" => "",
                        "cache" => 5,
                        'action' =>'getInfo'
                    ],
                ]
            ],
            'guest' => [
                "namespace" => "UserFront\Http\Controllers",
                "controller" => "AuthController",
                "router" => [
                    "login" => [
                        "url" => "/login",
                        "action" => "getLogin",
                        "name" => "login",
                        "guard" => "",
//                        "cache" => 60
                    ],
                    "register" => [
                        "url" => "/register",
                        "action" => "getRegister",
                        "name" => "register",
                        "guard" => "",
//                        "cache" => 60
                    ],
                    "login:post" => [
                        "url" => "/login/action",
                        "action" => "postLogin",
                        "method" => ["post"],
                        "guard" => ""
                    ],
                    "register:post" => [
                        "url" => "/register/action",
                        "action" => "postRegister",
                        "method" => ["post"],
                        "guard" => ""
                    ],
                    "logout" => [
                        "url" => "/logout",
                        "action" => "postLogout",
                        "method" => ["post", "get"],
                        "name" => "logout"
                    ],
                ]
            ],
        ]
    ]
];