<?php
return [
    "components" => [
        "components" => [
            "content" => [
                'name' => 'admin',
                'type' => 'module',
                'com' => 'content',
                'layout' => 'layout'
            ],
            'mega-menu' => [
                "name" => "MegaMenu",
                "type" => "plugin",
                'com' => 'mega-menu',
                'layout' => 'layout'
            ],
            "thumbnail-image" => [
                'layout' => 'theme'
            ]
        ],
        "widgets" => [
            'menu-extras' => [
                'layout' => 'widget'
            ],
            'slider' => [
                'layout' => 'widget'
            ],
            'coverage' => [
                'layout' => 'widget'
            ],
            'services' => [
                'layout' => 'widget'
            ],
            'our-clients' => [
                'layout' => 'widget'
            ],
        ]
    ]
];