<?php

namespace ModuleBlog;


use Admin\Lib\Database;
use Zoe\Module as ZModule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use \Illuminate\Database\Query\Builder;

class Module extends ZModule
{
    public static $name = "Blog";
    public static $description = "Blog module";
    public static $require = ['Comment'];
    public static $dev = true;

    public function Init()
    {
        // TODO: Implement Init() method.
        $this->path = __DIR__;
    }

    public function import($step = true, $settings = [], $datas = [])
    {
        $action = [];
        $return = true;
        $errors = "";
        try {
            $pathModule = storage_path('zoe/export/modules/blog');
            $configs = [];
            if (\File::exists($pathModule . '/import.json')) {
                $configs = json_decode(\File::get($pathModule . '/import.json'), true);
            }
            if (!isset($settings['name'])) {
                return ['error' => '100', 'data' => $settings];
            }
            $path = $pathModule . "/" . $settings['name'];
            $pathSql = $path . '/sql';
            if ($step == 0) {
                Database::addFileRow($pathSql . '/config.sql', 'config');
                Database::addFileRow($pathSql . '/categories.sql', 'categories');
                Database::addFileRow($pathSql . '/layout.sql', 'layout');
                Database::addFileRow($pathSql . '/tag.sql', 'tag');
                $return = ["step" => $step + 1, 'status' => true];
            } else if ($step == 1) {
                $datas = include $path . '/create_table.php';
                $return = ["step" => $step + 1, 'action' => $action, 'status' => Database::createTable($datas)];
            } else if ($step == 2) {
                $tables = ['blog_post', 'blog_post_category', 'blog_post_translation'];
                $data = Database::addFileRows($pathSql, $tables, $datas, $settings);
                $errors = $data['errors'];
                $return = [
                    "step" => $step + $data['step'],
                    "data" => $data,
                    "configs" => $datas,
                    'settings' => $settings
                ];
            }
            if (is_array($return) && !empty($errors)) {
                $return['error'] = $errors;
            }
            return $return;
        } catch (\Exception $ex) {
            return ['error' => $ex->getMessage() . ' ' . $ex->getLine(), 'action' => $action];
        }
    }

    public function uninstall($func = null, $posts = [])
    {
        try {
            $tables = ['blog_post', 'blog_post_category', 'blog_post_translation'];

            Database::dropIfExists($tables);

            DB::table('config')->where('name', 'blog')->delete();
            DB::table('config')->where('name', 'core-modules-blog')
                ->where('type', 'language')
                ->delete();

            DB::table("categories")->where('type', 'blog:category')->delete();
            DB::table('layout')->where('type_group', 'blog')->delete();
            DB::table("tag")->where('type', 'blog:post')->delete();

            return true;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function export($step = true, $settings = [], $datas = [])
    {
        $pathModule = storage_path('zoe/export/modules/blog');
        $return = true;
        $errors = "";

        $configs = \Admin\Lib\Database::Init($pathModule);

        if ($step == 0) {
            $configs['steps']["0"] = \Admin\Lib\Database::SaveRows(
                [
                    "config" => [
                        "table" => "config",
                        "lists" => [
                            function (Builder $db) {
                                return $db->where('name', 'blog')->get();
                            },
                            function (Builder $db) {
                                return $db->where('name', 'blog:category')->get();
                            }
                        ]
                    ],
                    "language" => [
                        "table" => "config",
                        "lists" => function (Builder $db) {
                            return $db->where('name', 'core-modules-blog')->where("type", "language")->get();
                        }

                    ],
                    "categories" => [
                        "table" => "categories",
                        "lists" => function (Builder $db) {
                            return $db->where('type', 'blog:category')->get();
                        }
                    ],
                    'layout' => [
                        "table" => "layout",
                        "lists" => function (Builder $db) {
                            return $db->where('type_group', 'blog')->get();
                        }
                    ],
                    'tag' => [
                        "table" => "tag",
                        "lists" => function (Builder $db) {
                            return $db->where('type', 'blog:post')->get();
                        }
                    ]
                ]
            );
            $return = [
                'result' => [
                    "step" => $step + 1,
                ],
                'errors' => ""
            ];
        } else if ($step == 1) {

            $result = Database::createFileTable(['blog_post', 'blog_post_category', 'blog_post_translation']);
            $errors = $result['errors'];
            $configs['steps']["1"] = $result['settings'];
            $return = [
                'result' => [
                    "step" => $step + 1,
                    "data" => [

                    ]
                ],
                'errors' => $errors
            ];

        } else if ($step == 2) {
            $tables = [
                'blog_post' => function (Builder $db) {
                    return $db->where('featured', 0);
                },
                'blog_post_category' => function ($db) {
                    return $db;
                },
                'blog_post_translation' => function ($db) {
                    return $db;
                }];

            $data = Database::createRowTable($tables, $datas, $settings);

            $errors = $data['errors'];
            $configs['steps']["2"] = $data['settings'];
            $return = [
                'result' => [
                    "step" => $step + $data['step'],
                    "data" => $data,
                    "configs" => $datas,
                    'settings' => $settings
                ],
                'errors' => $errors
            ];
        }

        saveFile($pathModule . '/configs.json', json_encode($configs));

        if (isset($return['result'])) {
            $return = $return['result'];
            if (!empty($return['errors'])) {
                $return['error'] = $return['errors'];
            }
            return $return['result'];
        }
        return $return;
    }
}
