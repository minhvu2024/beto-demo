<?php
return [
    "sidebars" => [
        "user" => [
            "name" => z_language('Tài khoản',false),
            "pos" => 2,
            "url" => "",
            "header" => true,
            "icon" => "fa fa-users",
            "order"=>1,
            "items" => [
                "1"=>[
                    "name" => z_language("Quản trị", false),
                    "url" => "backend:user:list",
                ],
                "2"=>[
                    "name" => z_language("Người dùng", false),
                    "url" => "backend:member:list",
                ],
                "3"=>[
                    "name" => z_language("Quyền", false),
                    "url" => "backend:user:role:list",
                ]
            ]
        ]
    ]
];