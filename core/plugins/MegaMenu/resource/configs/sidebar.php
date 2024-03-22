<?php
return [
    "sidebars" => [
//        "plugin:mega-menu" => [
//            "name" => z_language('Mega Menu'),
//            "pos" => 2,
//            "url" => "",
////            "header" => true,
//            "items" => [
//                [
//                    "name" => z_language("Layout"),
//                    "url" => "backend:plugin:mega-menu:layout:list",
//                ]
//            ]
//        ],
        "plugin:item" => [
            "items" => [
                "plugin:mega-menu" => [
                    "name" => z_language('Mega Menu'),
                    "pos" => 2,
                    "url" => "",
                    "items" => [
                        [
                            "name" => z_language("Layout"),
                            "url" => "backend:plugin:mega-menu:layout:list",
                            "icon"=>"fa fa-navicon"
                        ]
                    ]
                ]
            ]
        ]
    ]
];