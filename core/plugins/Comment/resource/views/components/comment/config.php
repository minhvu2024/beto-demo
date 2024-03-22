<?php
return component_config($_opt_,
    component_config_data(
        [
            "arr"=>[
                ["image"=>"image","title"=>"title","link"=>"link"]
            ]
        ]
    ),
    component_config_configs(
        [
            "template"=>["template" => "template", "data" => ["count" => 3]],
            "data"=>["view" => "data", "label" => "Data", "data" => ['count' => 4, 'key' => "lists"]],
        ]
    ),
    component_config_views([
        'main' => ["label" => "Main", "view" => "main"],
        'list-new' => ["label" => "ListNew", "view" => "list-new"],
    ]),
    ["name" => "Comment","view"=>""]
);