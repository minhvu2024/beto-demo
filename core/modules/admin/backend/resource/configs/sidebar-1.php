<?php
return [
    "sidebars" => [
        "module:shop-ja" => [
            "name" => z_language('Shop'),
            "pos" => 2,
            "url" => "",
            "header" => true,
            "icon"=>"fa fa-newspaper-o",
            "items" => [

            ]
        ],
        "dashboard" => [
            "name" => z_language("Dashboard", false),
            "pos" => 1,
            "url" => "backend:dashboard:list",
            "icon" => "fa fa-dashboard"
        ],
        "language" => [
            "name" => z_language("Language", false),
            "pos" => 1,
            "url" => "backend:language:list",
            "icon" => "fa fa-language"
        ],
        "menu" => [
            "name" => z_language("Menu", false),
            "pos" => 1,
            "url" => "backend:menu:list",
            "icon" => "fa fa-language"
        ],
        "layout" => [
            "name" => z_language("Layout", false),
            "pos" => 1,
            "url" => "backend:layout:list",
            "icon" => "fa fa-list-alt",
            "items" => [
                [
                    "name" => z_language("Layout", false),
                    "url" => "backend:layout:list",
                ],
                [
                    "name" => z_language("Build", false),
                    "url" => "backend:layout:build",
                ],
            ]
        ],
        "page" => [
            "name" => z_language("Page", false),
            "pos" => 1,
            "url" => "backend:page:list",
            "icon" => "fa fa-file-text"
        ],
        "component" => [
            "name" => z_language('Component', false),
            "pos" => 2,
            "url" => "backend:component:list",
            "header" => true,

        ],
        "plugin" => [
            "name" => z_language('Plugins', false),
            "pos" => 2,
            "url" => "backend:plugin:list",
            "header" => true,

        ],
        "module" => [
            "name" => z_language('Modules', false),
            "pos" => 2,
            "url" => "backend:module:list",
            "header" => true,

        ],
        "theme" => [
            "name" => z_language('Themes', false),
            "pos" => 2,
            "url" => "backend:theme:list",
            "header" => true,

        ],
        "media" => [
            "name" => z_language('Media', false),
            "pos" => 2,
            "url" => "backend:dashboard:media",
            "header" => true,

        ],
        "plugin:item" => [
            "name" => z_language('Plugins', false),
            "pos" => 2,
            "url" => "",
            "header" => true,
            "items" => [

            ]
        ],
        "configuration" => [
            "name" => z_language('Configuration', false),
            "pos" => 2,
            "url" => "",
            'items'=>[
                [
                    "name" => z_language('Configuration', false),
                    "pos" => 2,
                    "url" => "backend:configuration:list",
                    "header" => true
                ],
                [
                    "name" => z_language('Router', false),
                    "url" => "backend:dashboard:router",
                ],
            ]
        ],
        "log" => [
            "name" => z_language("Log", false),
            "pos" => 1,
            "url" => "backend:log:list",
            "icon" => "fa fa-file-text"
        ],
        "backup" => [
            "name" => z_language("Backup", false),
            "pos" => 1,
            "url" => "backend:backup:list",
            "icon" => "fa fa-file-text"
        ],
    ]
];
