<?php

namespace Admin\Http\Controllers;

use Admin\Http\Models\PageModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PluginController extends \Zoe\Http\ControllerBackend
{
    function getDirContents($dir, $filter = '', &$results = array())
    {
        $files = scandir($dir);

        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                if (empty($filter) || preg_match($filter, $path)) $results[] = $path;
            } elseif ($value != "." && $value != "..") {
                $this->getDirContents($path, $filter, $results);
            }
        }
        return $results;
    }

    public function getCrumb()
    {
        $this->breadcrumb(z_language("Plugin"), route('backend:plugin:list'));
        return $this;
    }

    private function CreatePluginObject($plugin)
    {
        $config_zoe = config('zoe');
        $relativePath = base_path($config_zoe['structure']['plugin']) . DIRECTORY_SEPARATOR . $plugin;
        require_once $relativePath . '/Plugin.php';
        $class = '\\' . $plugin . '\\Plugin';
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
                        $plugin = $data['plugin'];
                        $object = $this->CreatePluginObject($data['plugin']);
                        $response['status'] = $object->install();
                        $plugins = config_get('plugin', "lists");
                        $plugins[$plugin] = time();
                        config_set('plugin', "lists", ['data' => $plugins]);
                        DB::commit();
                    } catch (\Exception $ex) {
                        $response['status'] = $ex->getMessage();
                        DB::rollBack();
                    }
                    break;
                case "uninstall":
                    try {
                        $plugin = $data['plugin'];
                        $object = $this->CreatePluginObject($data['plugin']);
                        $response['status'] = $object->uninstall();
                        $plugins = config_get('plugin', "lists");
                        unset($plugins[$plugin]);
                        config_set('plugin', "lists", ['data' => $plugins]);
                        DB::commit();
                    } catch (\Exception $ex) {
                        $response['status'] = $ex->getMessage();
                        DB::rollBack();
                    }
                    break;
                case "remove":

                    break;
                case "export":
                    $object = $this->CreatePluginObject($data['plugin']);
                    $response['status'] = $object->export($data['step'], isset($data['data']) ? $data['data'] : []);
                    break;
                case "import":
                    $object = $this->CreatePluginObject($data['plugin']);
                    $response['status'] = $object->import($data['step']);
                    break;
            }
        }
        return response()->json($response);
    }

    public function list()
    {

        $config_zoe = config('zoe');
        $relativePath = base_path($config_zoe['structure']['plugin']);
        $lists_folder = scandir($relativePath);
        $array = [];
        $this->data['lists_install'] = config_get('plugin', "lists");
        foreach ($lists_folder as $plugin) {
            if ($plugin == "." || $plugin == "..") {
                continue;
            }
            if (file_exists($relativePath . DIRECTORY_SEPARATOR . $plugin . DIRECTORY_SEPARATOR . "Plugin.php")) {
                $class = '\\' . $plugin . '\\Plugin';
                if (!class_exists($class)) {
                    require_once $relativePath . DIRECTORY_SEPARATOR . $plugin . DIRECTORY_SEPARATOR . "Plugin.php";
                }
                $array[$plugin] = [
                    "name" => $class::$name ? $class::$name : $plugin,
                    "description" => $class::$description ? $class::$description : $plugin,
                    "version" => $class::$version,
                    "author" => $class::$author,
                    "require" => []
                ];
                foreach ($class::$require as $_plugin => $_type) {
                    if (file_exists($relativePath . DIRECTORY_SEPARATOR . $_plugin . DIRECTORY_SEPARATOR . "Plugin.php")) {
                        $array[$plugin]["require"][$_plugin] = isset($this->data['lists_install'][$_plugin]) ? 1 : 0;
                    } else {
                        $array[$plugin]["require"][$_plugin] = 2;
                    }
                }
            }
        }
        $this->data['lists'] = $array;
        $modules = collect(DB::table('module')->select()->where('status', 1)->get())->keyBy('name');
        $modules = $modules->map(function ($i) {
            $i->data = unserialize($i->data);
            return $i;
        });
        $this->data['modules_install'] = $modules->toArray();

        return $this->render('plugin.list');
    }
}