<?php
return [
    'structure' => [
        'module' => 'core/modules',
        'plugin' => 'core/plugins',
        'theme' => 'core/themes'
    ],
    'modules' => ['admin', 'user'],
    'plugins' => ['Comment', 'MegaMenu'],
    'providers' => [

    ],
    'router' => [
        'backend' => [
            'prefix' => '/admin',
            'guard' => 'admin'
        ],
        'frontend' => [
            'prefix' => '/',
            'guard' => 'user'
        ]
    ],
    'auth' => [
        'backend' => [
            'login' => 'backend:login'
        ],
        'frontend' => [
            'login' => 'login'
        ]
    ],
    'theme' => 'zoe',
    'cache' => [
        "status" => false
    ],
    'language' => [
//        'en_us' => ['flag' => 'gb', 'lang' => 'en-us', 'label' => 'English (United States)'],
        'vi' => ['flag' => 'vn', 'lang' => 'vi', 'label' => 'Viet Nam'],
        'jp' => ['flag' => 'jp', 'lang' => 'jp', 'label' => 'Japanese'],
//        'zh_cn' => ['flag' => 'cn', 'lang' => 'zh-cn', 'label' => 'Chinese (PRC)'],
    ],
    'language_data' => [],

];
