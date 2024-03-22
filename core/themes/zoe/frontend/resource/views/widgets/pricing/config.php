<?php
return component_config($_opt_,
    component_config_data(
        [

        ]
    ),
    component_config_configs(
        [
            'data-builder' => [
                'template' => 'data-builder',
                'data' => [
                    'count' => 3,
                    'items' => [
                        'icon' => [
                            'type' => 'select',
                            'label' => z_language('Icon', false),
                            'select' => [
                                'img/service-icon/diamond.png' => 'diamond',
                                'img/service-icon/ruler.png' => 'ruler',
                                'img/service-icon/box.png' => 'box',
                            ]
                        ],
                        'target' => [
                            'type' => 'select',
                            'label' => z_language('Target', false),
                            'select' => [
                                '_blank' => '_blank',
                                '_self' => '_self',
                                '_parent' => '_parent',
                                '_top' => '_top',
                            ]
                        ],
                        'info' => [
                            'type' => 'textarea',
                            'label' => z_language('Info', false),
                        ],
                        "image" => false
                    ],
                    'opt' => [

                    ]
                ]
            ]
        ]
    ),
    component_config_views([
        'main' => ["label" => "Main", "view" => "main"]
    ]),
    ["name" => "services", "view" => "main"]
);