<?php

namespace Admin\Http\Controllers;

use Admin\Http\Models\PageModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ThemeController extends \Zoe\Http\ControllerBackend
{
    private function CreateThemeObject($theme)
    {
        $config_zoe = config('zoe');
        $relativePath = base_path($config_zoe['structure']['theme']) . DIRECTORY_SEPARATOR . $theme;
        require_once $relativePath . '/Theme.php';
        $name = ucwords($theme) . 'Theme';
        $class = '\\' . $name . '\\Theme';
        return new $class();
    }

    public function ajax(Request $request)
    {
        $data = $request->all();
        $response = ["status" => false];
        if (isset($data["act"])) {
            switch ($data["act"]) {
                case "install":
                    try {
                        DB::beginTransaction();
                        $plugin = $data['theme'];
                        $object = $this->CreateThemeObject($data['theme']);
                        $response['status'] = $object->install();
                        $plugins = $plugin;
                        config_set('theme', "active", ['data' => $plugins]);
                        DB::commit();
                    } catch (\Exception $ex) {
                        $response['status'] = $ex->getMessage();
                        DB::rollBack();
                    }
                    break;
                case "uninstall":
                    try {
                        $plugin = $data['theme'];
                        $object = $this->CreateThemeObject($data['theme']);
                        $response['status'] = $object->uninstall();
                        config_set('theme', "active", ['data' => ""]);
                        DB::commit();
                    } catch (\Exception $ex) {
                        $response['status'] = $ex->getMessage();
                        DB::rollBack();
                    }
                    break;
                case "remove":

                    break;
            }
        }
        return response()->json($response);
    }

    public function list()
    {
        $config_zoe = config('zoe');
        $relativePath = base_path($config_zoe['structure']['theme']);
        $lists_folder = scandir($relativePath);
        $array = [];
        $modules = $config_zoe['modules'];
        $this->data['plugins'] = config_get('plugin', "lists");
        $this->data['modules'] = collect(DB::table('module')->select()->where('status', 1)->get())->keyBy('name')->toArray();

        $relativePluginPath = base_path($config_zoe['structure']['plugin']);
        $relativeModulePath = base_path($config_zoe['structure']['module']);

        foreach ($lists_folder as $theme) {
            if ($theme == "." || $theme == "..") {
                continue;
            }
            if (file_exists($relativePath . DIRECTORY_SEPARATOR . $theme . DIRECTORY_SEPARATOR . "Theme.php")) {
                require_once $relativePath . DIRECTORY_SEPARATOR . $theme . DIRECTORY_SEPARATOR . "Theme.php";
                $system = false;
                $name = ucwords($theme) . 'Theme';
                $class = '\\' . $name . '\\Theme';
                if (class_exists($class)) {
                    $array[$theme] = [
                        "name" => $class::$name ? $class::$name : $theme,
                        "description" => $class::$description ? $class::$description : $theme,
                        "version" => $class::$version,
                        "author" => $class::$author,
                        "system" => $system,
                        "require" => []
                    ];
                    foreach ($class::$require as $name => $type) {
                        if ($type == "plugin") {
                            if (file_exists($relativePluginPath . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . "Plugin.php")) {
                                $array[$theme]["require"]['plugin'][$name] = isset($this->data['plugins'][$name]) ? 1 : 0;
                            } else {
                                $array[$theme]["require"]['plugin'][$name] = 2;
                            }
                        }
                        if ($type == "module") {
                            if (file_exists($relativeModulePath . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . "Module.php")) {
                                $array[$theme]["require"]['module'][$name] = isset($this->data['modules'][$name]) ? 1 : 0;
                            } else {
                                $array[$theme]["require"]['module'][$name] = 2;
                            }
                        }
                    }
                }
            }
        }
        $this->data['lists'] = $array;
        $this->data['lists_install'] = config_get('theme', "active");
        return $this->render('theme.list');
    }
}