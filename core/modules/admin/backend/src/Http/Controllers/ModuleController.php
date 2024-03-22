<?php

namespace Admin\Http\Controllers;

use Admin\Http\Models\PageModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModuleController extends \Zoe\Http\ControllerBackend
{
    public function getCrumb()
    {
        $this->breadcrumb(z_language("Module"), route('backend:module:list'));
        return $this;
    }

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

    private function CreateModuleObject($module)
    {
        $config_zoe = config('zoe');
        $relativePath = base_path($config_zoe['structure']['module']) . DIRECTORY_SEPARATOR . $module;
        require_once $relativePath . DIRECTORY_SEPARATOR.'Module.php';
        $name = 'Module' . ucwords($module);
        $class = '\\' . $name . '\\Module';
        return new $class();
    }

    public function ajax(Request $request)
    {
        $data = $request->all();
        $response = ["status" => false];
        if (isset($data["act"])) {
            switch ($data["act"]) {
                case "install":
                    DB::beginTransaction();
                    try {
                        $module = $data['module'];
                        $object = $this->CreateModuleObject($module);
                        $name = 'Module' . ucwords($module);
                        $class = '\\' . $name . '\\Module';
                        if ($object) {
                            DB::table('module')->updateOrInsert(['name' => $module], [
                                'version' => $class::$version,
                                'data' => serialize(['require' => $class::$require]),
                                'status' => 1,
                                'create_at' => date('Y-m-d H:i:s')
                            ]);
                            $response['status'] = true;
                            DB::commit();
                        } else {
                            DB::rollBack();
                            $response['status'] = z_language("Error :class", ['class' => $class]);
                        }
                    } catch (\Exception $ex) {
                        DB::rollBack();
                        $response['status'] = $ex->getMessage();
                    }
                    break;
                case "uninstall":
                    DB::beginTransaction();
                    try {
                        $module = $data['module'];
                        $object = $this->CreateModuleObject($module);
                        $name = 'Module' . ucwords($module);
                        $class = '\\' . $name . '\\Module';

                        if ($object) {
                            $rs = $object->uninstall();

                            if ($rs == true) {
                                DB::table('module')->where('name', $module)->delete();
                                $response['status'] = true;
                                DB::commit();
                            } else {
                                $response['status'] = $rs;
                                DB::rollBack();
                            }
                        } else {
                            DB::rollBack();
                            $response['status'] = z_language("Error :class", ['class' => $class]);
                        }
                    } catch (\Exception $ex) {
                        DB::rollBack();
                        $response['status'] = $ex->getMessage();
                    }
                    break;
                case "remove":

                    break;
                case "export":
                    $module = $data['module'];
                    $object = $this->CreateModuleObject($module);
                    if ($object) {
                        $response['status'] = $object->export($data['step'],
                            isset($data['settings']) ? $data['settings'] : [],
                            isset($data['datas']) ? $data['datas'] : []
                        );
                    }
                    break;
                case "import":
                    $module = $data['module'];
                    $object = $this->CreateModuleObject($module);
                    DB::beginTransaction();
                    if ($object) {
                        $response['status'] = $object->import(
                            $data['step'],
                            isset($data['settings']) ? $data['settings'] : [],
                            isset($data['datas']) ? $data['datas'] : []);
                    }
                    if (isset($response['status']['error'])) {
                        DB::rollBack();
                    } else {
                        DB::commit();
                    }
                    break;
            }
        }
        return response()->json($response);
    }

    public function list()
    {

        $config_zoe = config('zoe');
        $relativePath = base_path($config_zoe['structure']['module']);
        $lists_folder = scandir($relativePath);
        $array = [];
        $modules = $config_zoe['modules'];
        $this->data['plugins'] = config_get('plugin', "lists");
        $relativePluginPath = base_path($config_zoe['structure']['plugin']);
        foreach ($lists_folder as $module) {
            if ($module == "." || $module == "..") {
                continue;
            }
            if (file_exists($relativePath . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . "Module.php")) {
                require_once $relativePath . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . "Module.php";
                $system = false;
                if (in_array($module, $modules)) {
                    $name = Str::studly($module);
                    $system = true;
                } else {
                    $name = 'Module' . Str::studly($module);
                }
                $class = '\\' . $name . '\\Module';

                if (class_exists($class)) {
                    $pathModule = storage_path('zoe/export/modules/' . $module);
                    $configs = [];
                    if (\File::exists($pathModule . '/configs.json')) {
                        $configs = json_decode(\File::get($pathModule . '/configs.json'), true);
                    }
                    $array[$module] = [
                        "name" => $class::$name ? $class::$name : $module,
                        "description" => $class::$description ? $class::$description : $module,
                        "version" => $class::$version,
                        "author" => $class::$author,
                        "system" => $system,
                        "require" => [],
                        "configs" => $configs
                    ];
                    foreach ($class::$require as $plugin) {
                        if (file_exists($relativePluginPath . DIRECTORY_SEPARATOR . $plugin . DIRECTORY_SEPARATOR . "Plugin.php")) {
                            $array[$module]["require"][$plugin] = isset($this->data['plugins'][$plugin]) ? 1 : 0;
                        } else {
                            $array[$module]["require"][$plugin] = 2;
                        }
                    }
                }
            }
        }
        $this->data['lists'] = $array;

        $this->data['lists_install'] = collect(DB::table('module')->select()->where('status', 1)->get())->keyBy('name');
        return $this->render('module.list');
    }
}
