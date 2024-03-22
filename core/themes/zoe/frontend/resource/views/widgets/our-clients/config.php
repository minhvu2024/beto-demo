<?php
return component_config($_opt_,
    component_config_data(
        [

        ]
    ),
    component_config_configs(
        [
            'image' => [
                'template' => 'image',
            ]
        ]
    ),
    component_config_views([
        'main' => ["label" => "Main", "view" => "main"]
    ]),
    ["name" => "our-clients", "view" => "main"]
);