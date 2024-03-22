<?php
return [
    'routers' => [
        'layout' => [
            "namespace" => "PluginLayout\Controllers",
            "controller" => "IndexController",
            "sub_prefix" => "/layout",
            "router" => [
                "list" => [
                    "url" => "/"
                ],
            ]
        ]
    ]
];