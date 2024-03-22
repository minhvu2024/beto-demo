<?php
return component_config(
    $_opt_,
    component_config_data(
        [

        ]
    ),
    component_config_configs(
        [
            "temp"=>["template" => "template", "prefix"=>"temp" , "data" => ["count" => 3]],
        ]
    ),
    component_config_views([
        'view' => ["label" => "View", "view" => "view"]
    ]),
    ["view"=>"content","name" => "Content"]
);