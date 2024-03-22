<?php
return [
    'routers' => [
        'comment' => [
            "namespace" => "PluginComment\Controllers",
            "controller" => "IndexController",
            "sub_prefix" => "/comment",
            "router" => [
                "list" => [
                    "url" => "/"
                ],
            ]
        ]
    ]
];