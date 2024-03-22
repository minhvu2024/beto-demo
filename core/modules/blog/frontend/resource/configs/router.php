<?php
return [
    'routers' => [
        'frontend' => [
            'blog:post' => [
                "namespace" => "BlogFront\Http\Controllers",
                "controller" => "PostController",
                "router" => [
                    "lists" => [
                        "url" => "blog/post",
                        "action"=>'getLists'
                    ],
                ]
            ]
        ]
    ]
];