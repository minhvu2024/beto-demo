<?php
return component_config($_opt_,
    component_config_data(
        [

        ]
    ),
    component_config_configs(
        [
            'image' => [
                "template" => "image"
            ],
            'data-builder' => [
                'template' => 'data-builder',
                'data' => [
                    'count' => 3,
                    'items' => [
                        'info' => [
                            'type' => 'text',
                            'label' => z_language('Info', false)
                        ],
                        'bg' => [
                            'type' => 'select',
                            'label' => z_language('Background', false),
                            'select' => [
                                'bg1' => 'bg1',
                                'bg2' => 'bg2',
                                'bg3' => 'bg3',
                                'bg4' => 'bg4',
                                'bg5' => 'bg5',
                                'bg6' => 'bg6',
                                'bg7' => 'bg7',
                                'bg8' => 'bg8',
                                'bg9' => 'bg9',
                                'bg10' => 'bg10',
                                'bg11' => 'bg11',
                                'bg12' => 'bg12',
                                'bg13' => 'bg13',
                                'bg14' => 'bg14',
                            ]
                        ]
                    ],
                    'attrs' => [
                        'route' => 'backend',
                        'config' => 'frontend'
                    ],
                    'views' => [
                        'top' => ["config" => 'theme::widgets.slider.config']
                    ]
                ]
            ]
        ]
    ),
    component_config_views([
        'main' => ["label" => "Main", "view" => "main"]
    ]),
    ["name" => "slider", "view" => "main"]
);