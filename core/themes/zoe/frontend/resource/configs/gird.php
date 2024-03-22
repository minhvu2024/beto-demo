<?php
return [
    "main" => function ($type, $option = []) {
        if ($type == "start") {
            return "<selector class='demo'>";
        } else {
            return "</selector>";
        }
    },
    "app" => function ($type) {

    }
];