<?php
return [
    "components" => [
        "configs" => [
            "template" => [
                "label" => "Template",
                "view" => "template",
                "data" => [
                    "count" => 2
                ]
            ],
            "language" => [
                "label" => "Language",
                "view" => "language",
                "data" => [

                ]
            ],
            'data-builder' => [
                "label" => "Builder",
                "view" => "data-builder",
                "data" => [
                    "count" => 2,
                    "action" => [
                        "create" => true,
                        "delete" => true,
                        "sort" => true
                    ],
                    'items' => [
                        'name' => ['type' => 'text', 'label' => z_language('Name', false)],
                        'link' => ['type' => 'route', 'label' => z_language('Link', false)],
                        'image' => ['type' => 'img', 'label' => z_language('Image', false)]
                    ],
                    'attrs' => [
                        'route' => 'all'
                    ]
                ]
            ],
            'image' => [
                "label" => "Image",
                "view" => "image",

            ]
        ]
    ]
];