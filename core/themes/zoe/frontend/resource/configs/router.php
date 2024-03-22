<?php
return [
    'routers' => [
        'frontend' => [
            'home' => [
                "namespace" => "ZoeTheme\Http\Controllers",
                "controller" => "HomeController",
                "router" => [
                    "lists" => [
                        "url" => "/",
                        "guard" => "",
                        "action"=>'getLists'
                    ],
                ]
            ]
        ]
    ]
];