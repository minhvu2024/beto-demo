<?php
return [
    'routers' => [
        'backend' => [
            'dashboard' => [
                "namespace" => "Admin\Http\Controllers",
                "controller" => "DashboardController",
                "prefix" => "/admin",
                "guard" => "backend",// pải login
                "acl"=> "dashboard",
                "router" => [
                    "list" => [
                        "url" => "/",
                    ],
                    "media" => [
                        "url" => "/media",
                        "acl"=>"media"
                    ],
                    "router" => [
                        "url" => "/router",
                        "method" => ['post', 'get'],
                    ],
                    "option" => [
                        "url" => "/option",
                        "method" => ['post'],
                    ]
                ]
            ],
            'elfinder' => [
                "namespace" => "Admin\Http\Controllers",
                "controller" => "ElfinderController",
                "sub_prefix" => "/elfinder-".md5("198c652bea53b1a3a6a407128e6059ea"),
                "guard" => "backend",// pải login
                "acl"=>"dashboard:media",
                "router" => [
                    "list" => [
                        "url" => "/",
                    ],
                    "showConnector" => [
                        "url" => '/show-connector',
                        "method" => ['get', 'post'],
                    ],
                    "tinymce4" => [
                        "url" => '/show-tinymce4',
                        "method" => ['get', 'post'],
                    ]
                ]
            ],
            'page' => [
                "namespace" => "Admin\Http\Controllers",
                "controller" => "PageController",
                "sub_prefix" => "/page",
                "guard" => "backend",// pải login
                "acl"=> "page",
                "router" => [
                    "list" => [
                        "url" => "/",
                    ],
                    "create" => [
                        "url" => "/create",
                    ],
                    "edit" => [
                        "url" => "/edit/{id}",
                    ],
                    "delete" => [
                        "url" => "/delete/{id}",
                        "method" => ['post'],
                    ],
                    "store" => [
                        "url" => "/store",
                        "method" => ['post'],
                    ]
                ]
            ],
            'language' => [
                "namespace" => "Admin\Http\Controllers",
                "controller" => "LanguageController",
                "sub_prefix" => "/language",
                "guard" => "backend",// pải login
                "acl"=> "language",
                "router" => [
                    "list" => [
                        "url" => "/",
                    ],
                    "set_lang" => [
                        "url" => "/set-lang/{lang}",
                    ],
                    "ajax:save" => [
                        "url" => "/save",
                        "method" => ['post'],
                        "action" => "ajaxFormSave"
                    ],
                ]
            ],
            'layout' => [
                "namespace" => "Admin\Http\Controllers",
                "controller" => "LayoutController",
                "sub_prefix" => "/layout",
                "guard" => "backend",// pải login
                "acl"=> "layout",
                "router" => [
                    "list" => [
                        "url" => "/",
                    ],
                    "create" => [
                        "url" => "/create/{type?}",
                    ],
                    "edit" => [
                        "url" => "/edit/{id}/{type?}",
                    ],
                    "delete" => [
                        "url" => "/delete/{id}",
                        "method" => ['post'],
                    ],
                    "build" => [
                        "url" => "/build/{type?}",
                    ],

                    "ajax:build" => [
                        "url" => "/ajax-build",
                        "method" => ['post'],
                        "action" => "ajaxBuild"
                    ],
                    "ajax" => [
                        "url" => "/ajax",
                        "method" => ['post'],
                        "action" => "ajaxPost"
                    ],
                    "ajax:form_config" => [
                        "url" => "/ajax-form-config",
                        "method" => ['post'],
                        "action" => "ajaxFormConfig"
                    ],
                    "ajax:review_blade" => [
                        "url" => "/ajax-review-blade",
                        "method" => ['post'],
                        "action" => "ajaxReviewBlade"
                    ],
                    "ajax:get_lang" => [
                        "url" => "/ajax-get-lang",
                        "method" => ['post'],
                        "action" => "ajaxGetLang"
                    ],
                    "ajax:get_com" => [
                        "url" => "/ajax-post-com",
                        "method" => ['post'],
                        "action" => "ajaxPostCom"
                    ],
                ]
            ],
            'component' => [
                "namespace" => "Admin\Http\Controllers",
                "controller" => "ComponentController",
                "sub_prefix" => "/component",
                "guard" => "backend",
                "acl"=> "component",
                "router" => [
                    "list" => [
                        "url" => "/",
                    ],
                ]
            ],
            'category' => [
                "namespace" => "Admin\Http\Controllers",
                "controller" => "CategoryController",
                "sub_prefix" => "/category",
                "guard" => "backend",
                "acl"=> "category",
                "router" => [
                    "show" => [
                        "url" => "/show",
                    ],
                    "list" => [
                        "url" => "/",
                    ],
                    "ajax" => [
                        "url" => "/ajax", "method" => ['post'],
                    ],
                ]
            ],
            'configuration' => [
                "namespace" => "Admin\Http\Controllers",
                "controller" => "ConfigurationController",
                "sub_prefix" => "/configuration",
                "guard" => "backend",
                "acl"=>'configuration',
                "router" => [
                    "list" => [
                        "url" => "/{type?}",
                    ],
                    "ajax" => [
                        "url" => "/ajax", "method" => ['post'],
                    ],
                ]
            ],
            'plugin' => [
                "namespace" => "Admin\Http\Controllers",
                "controller" => "PluginController",
                "sub_prefix" => "/plugin",
                "guard" => "backend",
                "acl"=> "plugin",
                "router" => [
                    "list" => [
                        "url" => "/",
                    ],
                    "ajax" => [
                        "url" => "/ajax", "method" => ['post'],
                    ],
                ]
            ],
            'module' => [
                "namespace" => "Admin\Http\Controllers",
                "controller" => "ModuleController",
                "sub_prefix" => "/module",
                "guard" => "backend",
                "acl"=> "module",
                "router" => [
                    "list" => [
                        "url" => "/",
                    ],
                    "ajax" => [
                        "url" => "/ajax", "method" => ['post'],
                    ],
                ]
            ],
            'theme' => [
                "namespace" => "Admin\Http\Controllers",
                "controller" => "ThemeController",
                "sub_prefix" => "/theme",
                "guard" => "backend",
                "acl"=> "theme",
                "router" => [
                    "list" => [
                        "url" => "/",
                    ],
                    "ajax" => [
                        "url" => "/ajax", "method" => ['post'],
                    ],
                ]
            ],
            'menu' => [
                "namespace" => "Admin\Http\Controllers",
                "controller" => "MenuController",
                "sub_prefix" => "/menu",
                "guard" => "backend",
                "acl"=> "menu",
                "router" => [
                    "show" => [
                        "url" => "/show",
                    ],
                    "list" => [
                        "url" => "/",
                    ],
                    "ajax" => [
                        "url" => "/ajax", "method" => ['post'],
                    ],
                ]
            ],
            'log' => [
                "namespace" => "Admin\Http\Controllers",
                "controller" => "LogController",
                "sub_prefix" => "/log",
                "guard" => "backend",// pải login
                "acl"=> "log",
                "router" => [
                    "list" => [
                        "url" => "/",
                    ],

                    "store" => [
                        "url" => "/store",
                        "method" => ['post'],
                    ]
                ]
            ],
            'backup' => [
                "namespace" => "Admin\Http\Controllers",
                "controller" => "BackupController",
                "sub_prefix" => "/backup",
                "guard" => "backend",// pải login
                "acl"=> "log",
                "router" => [
                    "list" => [
                        "url" => "/",
                    ],

                    "store" => [
                        "url" => "/store",
                        "method" => ['post'],
                    ]
                ]
            ],
            'sidebar' => [
                "namespace" => "Admin\Http\Controllers",
                "controller" => "SidebarController",
                "sub_prefix" => "/sidebar",
                "guard" => "backend",
                "acl"=> "menu",
                "router" => [
                    "list" => [
                        "url" => "/",
                    ],
                    "ajax" => [
                        "url" => "/ajax", "method" => ['post'],
                    ],
                ]
            ],
            'announce' => [
                "namespace" => "Admin\Http\Controllers",
                "controller" => "AnnounceController",
                "sub_prefix" => "/announce",
                "guard" => "backend",// pải login
                "acl"=> "announce",
                "router" => [
                    "list" => [
                        "url" => "/",
                    ],
                    "create" => [
                        "url" => "/create",
                    ],
                    "edit" => [
                        "url" => "/edit/{id}",
                    ],
                    "delete" => [
                        "url" => "/delete/{id}",
                        "method" => ['post'],
                    ],
                    "store" => [
                        "url" => "/store",
                        "method" => ['post'],
                    ]
                ]
            ],
        ]
    ]
];
