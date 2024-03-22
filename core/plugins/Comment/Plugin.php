<?php

namespace Comment;

use Zoe\Module as ZModule;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Admin\Lib\Database;

class Plugin extends ZModule
{
    public static $require = ["Builder" => 'plugin'];

    public function Init()
    {

    }

    public function install()
    {
        try {
            Schema::create('comments', function (Blueprint $table) {
                $table->increments('id');
                $table->string('key', 255)->collation('utf8_general_ci');
                $table->integer('user_id')->unsigned()->index()->references('id')->on('user');
                $table->string('title', 255)->collation('utf8_general_ci');
                $table->string('content', 255)->collation('utf8_general_ci');
                $table->tinyInteger('status')->unsigned()->default(0);
                $table->integer('parent_id')->unsigned()->default(0);
                $table->timestamps();
            });
            return true;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function uninstall()
    {
        try {

            $tables = ['plugin_comments'];
            foreach ($tables as $table) {
                Schema::dropIfExists($table);
            }
            DB::table('config')->where('name', 'Comment')->delete();
            return true;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function import($step = true, $settings = [], $datas = [])
    {
        $action = [];
        try {

            $path = storage_path('zoe/export/plugins/Comment');
            $pathSql = $path . '/sql';

            if ($step == 0) {

                Database::addFileRow($pathSql . '/config.sql', 'config');
                return ["step" => $step + 1, 'status' => true];
            } else if ($step == 1) {
                $datas = include $path . '/create_table.php';
                return ["step" => $step + 1, 'action' => $action, 'status' => Database::createTable($datas)];
            }
            return true;
        } catch (\Exception $ex) {
            return ['error' => $ex->getMessage(), 'action' => $action];
        }
    }

    public function export($step = true, $settings = [], $datas = [])
    {
        $pathPlugin = storage_path('zoe/export/plugins/Comment');


        $configs = ['dates' => []];
        if (\File::exists($pathPlugin . '/configs.json')) {
            $configs = json_decode(\File::get($pathPlugin . '/configs.json'), true);
        }
        $date = date('Y-m-d') . "-" . (date('h')) . "h";
        $configs['date'] = $date;
        $configs['dates'][$date] = $date;
        $path = $pathPlugin . '/' . $date;

        $pathSql = $path . '/sql';

        $return = true;
        $errors = "";

        if ($step == 0) {

            if (!\File::exists($path)) {
                \File::makeDirectory($path);
            }
            if (!\File::exists($pathSql)) {
                \File::makeDirectory($pathSql);
            }
            $config[] = DB::table('config')->where('name', 'core-plugins-Comment')->get();
            saveFile($pathSql . '/config.sql', \Admin\Lib\Database::rows($config, ['id']));
            $return = [
                "step" => $step + 1,
                "data" => [

                ]
            ];

        } else if ($step == 1) {
            Database::createFileTable($path, ['plugin_comments']);
            $return = [
                "step" => $step + 1,
                "data" => [

                ]
            ];
        } else if ($step == 2) {
            $return = [
                "step" => $step + 1,
                "data" => [

                ]
            ];
        }
        if (is_array($return) && !empty($errors)) {
            $return['error'] = $errors;
        }
        return $return;
    }

}