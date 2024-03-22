<?php

namespace Admin\Http\Controllers;

use Admin\Http\Models\Layout;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;

class LayoutController extends \Zoe\Http\ControllerBackend
{
    protected $listsType = ['layout' => 'Layout', 'partial' => 'Partial'];


    public function getCrumb()
    {
        $this->breadcrumb(z_language("Layout"), route('backend:layout:list'));
        return $this;
    }


    public function getListType($type)
    {

        if (isset(app()->getConfig()['modules']['admin.layout'][$type])) {
            $data_type = (app()->getConfig()['modules']['admin.layout'][$type]);
            return $data_type;
        } else {
            $this->getCrumb();
            $arr = [];
            $lists = isset(app()->getConfig()['modules']['admin.layout']) ? app()->getConfig()['modules']['admin.layout'] : [];
            foreach ($lists as $k => $row) {
                $arr[$row['value']] = $row['label'];
            }
            return $arr;
        }
    }

    public function delete()
    {

    }

    private function GetViewHelperBlade()
    {

        if (class_exists("\ZoeTheme\Helper\ViewHelper")) {
            return new \ZoeTheme\Helper\ViewHelper();
        } else {
            return new \Admin\Lib\ViewHelper();
        }
    }

    private function GetGridBlade()
    {

        if (class_exists("\ZoeTheme\Helper\GirdBladeHelper")) {
            return new \ZoeTheme\Helper\GirdBladeHelper();
        } else {
            return new \Admin\Lib\GirdBladeHelper();
        }
    }

    private function GetMethod($obj, $prefix = "layout_")
    {
        $data = [];
        $class_name = get_class($obj);
        $methods = get_class_methods($class_name);
        foreach ($methods as $method) {
            if (substr($method, 0, 7) == $prefix) {
                $data[$method] = substr($method, 7);
            }
        }
        return $data;
    }

    public function ajaxFormConfig(Request $request)
    {
        $alise = "backend::controller.layout.ajax.config";
        $templates = [
            "template" => ["file" => $alise . ".template", "label" => "Template", "data" => []],
        ];
        $items = $request->all();

        $theme = config_get('theme', "active");
        $data = ["views" => [], "func" => ["No Action" => "0"]];

        $components_conf = app()->getComponents()->config;
        $components_info = app()->getComponents()->info;

//        dump(app()->getConfig()['views']["paths"]);
//        dump(app()->getComponents());


//        $components_config = app()->getComponents()->config;
//        dd($items);

        if (isset($items['config']['stg'])) {
            if (isset($items['config']['stg']['system'])) {
                $stg = $items['config']['stg'];
                $data['compiler'] = [];
                $data["compiler"]['grid'] = $this->GetMethod($this->GetGridBlade());
                $data["compiler"]['blade'] = $this->GetMethod($this->GetViewHelperBlade());

                $data["config"] = [
                    "compiler" => isset($items["config"]['cfg']['compiler']) ? $items["config"]['cfg']['compiler'] : []
                ];
                $data['tags'] = [];

                $path = "";
                $view_config = "";
                $view_view = "";
                switch ($stg['system']) {
                    case "theme":
                        if ($stg['module'] == $theme) {
                            if ($stg['type'] == "components") {
                                $view_config = "theme::components." . $stg['name'] . ".config.";
                                $view_view = "theme::components." . $stg['name'] . ".views.";
                                $path = base_path('core/themes/' . $stg['module'] . '/frontend/resource/views/components/' . $stg['name']);
                            }
                        }
                        break;
                    case "module":
                        if ($stg['type'] == "components") {
                            $view_config = $stg['module'] . "::components." . $stg['name'] . ".config.";
                            $view_view = $stg['module'] . "::components." . $stg['name'] . ".views.";
                            $path = base_path('core/modules/' . $stg['module'] . '/frontend/resource/views/components/' . $stg['name']);
                        } else {

                        }
                        break;
                    case "plugin":
                        if ($stg['type'] == "components") {
                            $path = base_path('core/plugins/' . $stg['module'] . '/resource/views/components/' . $stg['name']);
                        } else {

                        }
                        break;
                }
                $prefix = $stg['system'] . ":" . $stg['type'] . ":";

                if (isset($items['config']['stg']['name'])) {

                    if (isset($components_info[$prefix . $items['config']['stg']['name']]['main']) && is_array($components_info[$prefix . $items['config']['stg']['name']]['main'])) {
                        $data["func"] = array_merge($data["func"], $components_info[$prefix . $items['config']['stg']['name']]['main']);
                    }
                    if (isset($components_conf[$prefix . $items['config']['stg']['name']])) {
                        $config = $components_conf[$prefix . $items['config']['stg']['name']];
                        $is_template_dynamic = false;
                        if (isset($config['configs'])) {
                            $config['configs']['lang'] = ["template" => "language"];
                            foreach ($config['configs'] as $label => $_view) {
//                            dump($_view);
                                if (isset($_view['view']) && isset($_view['label'])) {
                                    $data['views'][$label] = [
                                        'label' => $_view['label'],
                                        'view' => $_view['view'],
                                        'data' => new \Zoe\Config($_view['data'])
                                    ];
                                } else if (isset($_view['template']) && isset(app()->getComponents()->template[$_view['template']])) {
                                    if (isset($_view['data'])) {
                                        $__data = $_view["data"];
                                    } else {
                                        $__data = [];
                                    }
                                    $_view = app()->getComponents()->template[$_view['template']];
                                    $is_template_dynamic = true;

                                    $_tmp = [
                                        'label' => $_view['label'],
                                        'view' => $_view['view'],
                                        "data" => new \Zoe\Config(isset($_view['data']) ? $_view['data'] : []),
                                    ];
                                    if (is_array($__data)) {
                                        $_tmp['data']->add($__data);
                                    } else {
                                        $_tmp['data'] = [];
                                    }

                                    $data['views'][$label] = $_tmp;
                                }
                                $data['views'][$label]['key'] = $label;
                            }
                        }
                        $data["list_views"] = [
                            "default" => ["label" => "Select View", "view" => "0"]
                        ];

                        if ($is_template_dynamic) {
                            $data["list_views"]["dynamic"] = ["label" => "Dynamic", "view" => "dynamic"];
                        }
                        if (isset($config["views"])) {
                            foreach ($config["views"] as $_k => $_view) {
                                $data["list_views"][$view_view . $_k] = $_view;
                            }
                        }

                        $data['items'] = $items['config'];
                    } else {
                        $data["list_views"] = [];
                    }
                    $data['type'] = 'components';
                    return $this->render('layout.ajax.config', $data);
                } else {

                    $data['compiler'] = [];
                    $data["compiler"]['grid'] = $this->GetMethod($this->GetGridBlade());
                    $data["compiler"]['blade'] = $this->GetMethod($this->GetViewHelperBlade());

                    $data["config"] = [
                        "compiler" => isset($items["config"]['cfg']['compiler']) ? $items["config"]['cfg']['compiler'] : []
                    ];

                    $data["list_views"] = [
                        "default" => ["label" => "Select View", "view" => ""]
                    ];
                    $data['items'] = $items['config'];

                    return $this->render('layout.ajax.config', $data);
                }

            } else {
                $data['compiler'] = [];

                $data["compiler"]['grid'] = $this->GetMethod($this->GetGridBlade());
                $data["compiler"]['blade'] = $this->GetMethod($this->GetViewHelperBlade());
                $data['tags'] = array_keys($this->GetGridBlade()->CallBackTag());

                $data["configs"] = [
                    "compiler" => isset($items["config"]['cfg']['compiler']) ? $items["config"]['cfg']['compiler'] : []
                ];
                $data["list_views"] = [
                    "default" => ["label" => "Select View", "view" => ""]
                ];
                $data['items'] = $items['config'];

                $data['views']["gird"] = [
                    'label' => "Gird",
                    'view' => "backend::controller.layout.ajax.grid",
                    'data' => []
                ];
                $data['type'] = 'grid';
//                $data['views']["temp"] = app()->getComponents()->template["template"];;
                return $this->render('layout.ajax.config', $data);
            }
        }

    }

    public function ajaxReviewBlade(Request $request)
    {
        $items = $request->all();
        $obj_layout = new \Admin\Lib\LayoutBlade();
        if (isset($items["cfg"]['template'])) {
            if ($items['stg']['type'] == "components" || $items['stg']['type'] == "widgets") {
                $obj_layout->ViewHelper = $this->GetViewHelperBlade();
                $obj_layout->GridHelper = $this->GetGridBlade();


                $plugin = $obj_layout->plugin($items);
                $phpContent = Blade::compileString($obj_layout->InitBuild() . $obj_layout->InitFuc());
                $php = $phpContent . Blade::compileString($plugin);

                $__env = app(\Illuminate\View\Factory::class);

                $obLevel = ob_get_level();

                ob_start();
                $repon = ['content' => "", 'status' => 1, 'php' => $php];
                try {
                    eval('?' . '>' . $php);
                    $repon['content'] = trim(ob_get_clean());
                    $repon['status'] = 0;
                } catch (\Exception $e) {
                    while (ob_get_level() > $obLevel) ob_end_clean();
                    $repon['content'] = $e->getMessage() . " " . $e->getLine();
                } catch (\Throwable $e) {
                    $repon['content'] = $e->getMessage() . " " . $e->getLine();
                    while (ob_get_level() > $obLevel) ob_end_clean();
                }

                echo json_encode($repon);
            } else {
                $repon = ['content' => $items["cfg"]['template'], 'status' => 1, 'php' => $obj_layout->rows(['option' => $items])];
                echo json_encode($repon);
            }
        }
    }

    public function ajaxGetLang(Request $request)
    {

        $items = $request->all();
        $obj_layout = new \Admin\Lib\LayoutBlade();
        if (isset($items["cfg"]['template'])) {
            $obj_layout->ViewHelper = $this->GetViewHelperBlade();
            $obj_layout->GridHelper = $this->GetGridBlade();

            $stringBlade = $obj_layout->plugin($items, "", $obj_layout->InitBuild(true));
            $stringBlade = $obj_layout->InitFuc() . $stringBlade;

            // $php = Blade::compileString($obj_layout->InitBuild() . $obj_layout->InitFuc() . $obj_layout->plugin($items));
            $__env = app(\Illuminate\View\Factory::class);

            $obLevel = ob_get_level();
            ob_start();
            $repon = ['content' => $stringBlade, 'status' => 1, 'php' => $stringBlade];
            try {

                $repon['data'] = \Admin\Http\Controllers\LanguageController::lang($stringBlade, "", 'zlang');


                $langs = app()->getLanguage();
                $repon['langs'] = [];

                foreach ($repon['data'] as $val_lang) {
                    foreach ($langs as $lang => $value) {
                        if (isset($value[$val_lang["name"]])) {
                            $repon['langs'][$lang][$val_lang["name"]] = $value[$val_lang["name"]];
                        }
                    }
                }
                $repon['status'] = 0;
            } catch (\Exception $e) {

                while (ob_get_level() > $obLevel) ob_end_clean();
                $repon['content'] = $e->getMessage() . " " . $e->getLine();
            } catch (\Throwable $e) {
                while (ob_get_level() > $obLevel) ob_end_clean();
                $repon['content'] = $e->getMessage() . " " . $e->getLine();
            }
            echo json_encode($repon);
        }
//        $items = $request->all();
//        if (isset($items["cfg"]['template'])) {
//            // var_export($items);
//            \Admin\Lib\LayoutBlade::$ViewHelper = $this->GetViewHelperBlade();
//            Blade::directive('zlang', function ($expr) {
//                return '"<div class="___lang___">' . $expr . '</div>"';
//            });
//            $php = Blade::compileString(\Admin\Lib\LayoutBlade::plugin($items));
//
////            preg_match_all('/@zlang\(\s*([\"\'])(.*?)\\1\s*\)/', \Admin\Lib\LayoutBlade::plugin($items), $match);
////            $data = [];
////            foreach ($match[2] as $value) {
////                $data[md5(trim($value, "'\""))] = trim($value, "'\"");
////            }
//            //echo json_encode($data);
//
//
//            $__env = app(\Illuminate\View\Factory::class);
//            $obLevel = ob_get_level();
//            ob_start();
//            $content = "";
//            try {
//                eval('?' . '>' . $php);
//                $content = trim(ob_get_clean());
//                var_dump($content);
//                //  preg_match_all('/(?<=class\=\"___lang___\"\>).*(?=\<\/div\>)/', $content, $match);
//                // var_dump($match);
//            } catch (\Exception $e) {
//                while (ob_get_level() > $obLevel) ob_end_clean();
//                echo $e->getMessage();
//            } catch (\Throwable $e) {
//                while (ob_get_level() > $obLevel) ob_end_clean();
//            }
//        }
    }

    public function ajaxGetConfigPost(Request $request)
    {
        $items = $request->all();
    }

    public function ajaxPostCom(Request $request)
    {
        $items = $request->all();
        $rs = DB::table('component')->find($items['widget']['cfg']['id']);
        $isExtit = false;
        if ($rs) {
            try {
                $data = new \Zoe\Config(unserialize($rs->layout));
                $isExtit = true;
            } catch (\Exception $ex) {
                $data = new \Zoe\Config([]);
            }
        } else {
            $data = new \Zoe\Config([]);
        }
        if (isset($items['type']) && $items['type'] == "get") {
            if ($isExtit) {
                echo json_encode(unserialize($rs->data));
            } else {
                $items['widget']["cfg"]['public'] = "0";
                $items['widget']["cfg"]['dynamic'] = "0";
                echo json_encode($items['widget']);
            }
        } else {
            if (isset($items['type']) && $items['type'] == "remove" && $isExtit) {
                $data->offsetUnset($items['id']);
            } else {
                $data->add([$items['id'] => date('Y-d-m H:i:s')]);
            }
            if ($isExtit) {
                if ($data->count() == 0) {
                    return DB::table('component')->delete($items['widget']['cfg']['id']);
                }
            }
            if ($isExtit || $items['type'] == "create") {
                DB::table('component')->updateOrInsert(
                    [
                        'id' => $items['widget']['cfg']['id'],
                    ],
                    [
                        'data' => serialize($items['widget']),
                        'type' => $items['widget']['stg']['type'],
                        'layout_id' => isset($items['widget']['stg']['id']) ? $items['widget']['stg']['id'] : 0,
                        'layout' => serialize($data->getArrayCopy()),
                        'update_at' => date('Y-m-d H:i:s')
                    ]
                );
            }
        }
    }

    public function ajaxBuild(Request $request)
    {
        $items = $request->all();
        if (isset($items['act']) && isset($items['id'])) {
            $model = \Admin\Http\Models\Layout::find($items['id']);
            if ($model) {
                switch (true) {
                    case $items['act'] == 'build':
                        $layout = unserialize(base64_decode($model->content));
                        echo json_encode($this->saveFile($model, $layout));
                        break;
                    case $items['act'] == 'delete':
                        $obj_layout = new \Admin\Lib\LayoutBlade();
                        if ($model->type == "partial") {
                            $fileName = $obj_layout->FilenamePartial($model->slug, $model);
                        } else {
                            $fileName = $obj_layout->FilenameLayout($model->slug, $model);
                        }
                        $data_path = $obj_layout->initPath($model->type_group);
                        $FileNameBlade = view()->exists("zoe::" . $data_path['prefix'] . $fileName) ? view()->getFinder()->find(\Illuminate\View\ViewName::normalize("zoe::" . $data_path['prefix'] . $fileName)) : "";

                        if (!empty($FileNameBlade)) {
                            $FileNamePhp = config('view.compiled') . "/" . sha1($FileNameBlade) . ".php";
                            echo $FileNamePhp;
                            if ($obj_layout->file->exists($FileNamePhp)) {
                                $obj_layout->file->delete($FileNamePhp);
                            }
                        }
                        break;
                    case $items['act'] == 'view-php' || $items['act'] == "view-blade":
                        $obj_layout = new \Admin\Lib\LayoutBlade();

                        if ($model->type == "partial") {
                            $fileName = $obj_layout->FilenamePartial($model->slug, $model);
                        } else {
                            $fileName = $obj_layout->FilenameLayout($model->slug, $model);
                        }
                        $data_path = $obj_layout->initPath($model->type_group);
                        $FileNameBlade = view()->exists("zoe::" . $data_path['prefix'] . $fileName) ? view()->getFinder()->find(\Illuminate\View\ViewName::normalize("zoe::" . $data_path['prefix'] . $fileName)) : "";
                        $data = ["content" => ""];

                        if (!empty($FileNameBlade)) {
                            if ($items['act'] == 'view-php') {
                                $FileNamePhp = config('view.compiled') . "/" . sha1($FileNameBlade) . ".php";
                                if (file_exists($FileNamePhp)) {
                                    $data['content'] = e($obj_layout->file->get($FileNamePhp));
                                }
                            } else if ($items['act'] == "view-blade") {
                                if (file_exists($FileNameBlade)) {
                                    $data['content'] = e($obj_layout->file->get($FileNameBlade));

                                }
                            }

                        }
                        return response()->json($data);
                }
            }
        }
    }

    public function saveFile($model, $layout)
    {
        $obj_layout = new \Admin\Lib\LayoutBlade();
        $obj_layout->ViewHelper = $this->GetViewHelperBlade();
        $obj_layout->GridHelper = $this->GetGridBlade();
        $obj_layout->TagHelper = $obj_layout->GridHelper->CallBackTag();
        $use = $this->getListType($model->type_group);

        if (isset($use['template'])) {
            $template = base_path($use['template']);
        } else {
            $template = base_path('core/modules/admin/backend/resource/stubs/layout.stubs');
        }

        $filename = $obj_layout->render($model->type_group, $template, $layout, $model->slug, $model->token, $model->type, "test");
        $obLevel = ob_get_level();
        try {
            ob_start();
            echo view('zoe::' . $filename);
            $content = ob_get_contents();
            ob_clean();
            $filename = $obj_layout->render($model->type_group, $template, $layout, $model->slug, $model->token, $model->type);
            $model->data = $obj_layout->getData();
            $model->save();
            return (['data' => $obj_layout->getData(), 'error' => "", 'content' => e($content), 'id' => $model->id, 'template' => $template, '' => $use, 'filename' => $filename]);
        } catch (\Exception $ex) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            return (['error' => $ex->getMessage(), 'content' => '', 'id' => $model->id, 'template' => $template, '' => $use, 'filename' => $filename]);
        }
    }

    public function ajaxPost(Request $request)
    {

        $theme = config_get('theme', "active");
        $items = $request->all();
        $model = null;
        if (isset($items["info"]['id']) && $items["info"]['id'] != 0) {
            $model = \Admin\Http\Models\Layout::find($items["info"]['id']);
        }
        if ($model == null) {
            $model = new \Admin\Http\Models\Layout();
        }
        $model->name = $items["info"]['name'];
        $model->type_group = $items["info"]['type_group'];
        $slug = Str::slug($items["info"]['name'], '-');
        $model->token = $items["info"]['token'];
        $model->slug = $slug;
        $model->type = $items["info"]['type'];
        $layout = isset($items['layout']) ? json_decode($items['layout'], true) : [];
        $model->content = base64_encode(serialize($layout));

        echo json_encode($this->saveFile($model, $layout));
//        $obj_layout = new \Admin\Lib\LayoutBlade();
//        $obj_layout->ViewHelper = $this->GetViewHelperBlade();
//        $obj_layout->GridHelper = $this->GetGridBlade();
//        $obj_layout->TagHelper = $obj_layout->GridHelper->CallBackTag();
//        $use = $this->getListType($model->type_group);
//
//        if (isset($use['template'])) {
//            $template = base_path($use['template']);
//        } else {
//            $template = base_path('core/modules/admin/backend/resource/stubs/layout.stubs');
//        }
//        $filename = $obj_layout->render($template, $layout, $model->id, $model->token, $model->type, "test");
//        $obLevel = ob_get_level();
//        try {
//            ob_start();
//            echo view('zoe::' . $filename);
//            $content = ob_get_contents();
//            ob_clean();
//            $filename = $obj_layout->render($template, $layout, $model->id, $model->token, $model->type);
//
//            $model->data =$obj_layout->getData();
//            $model->save();
//
//            echo json_encode(['data'=>$obj_layout->getData(),'error' => "", 'content' => e($content), 'id' => $model->id, 'template' => $template, '' => $use, 'filename' => $filename]);
//        } catch (\Exception $ex) {
//
//            while (ob_get_level() > $obLevel) ob_end_clean();
//            echo json_encode(['error' => $ex->getMessage(), 'content' => '', 'id' => $model->id, 'template' => $template, '' => $use, 'filename' => $filename]);
//        }
    }

    function getPartial($id)
    {
        $theme = config_get('theme', "active");
        $rs = DB::table('layout')->select()->where(['type' => 'partial'])->get()->toArray();
        $array = [];
        foreach ($rs as $val) {
            if ($id == $val->id) {
                continue;
            }
            $item = component_create('zoe', [], [], [], 'partial');
            $item["name"] = $val->name;
            $item["option"]['stg']['id'] = $val->id;
            $item["option"]['stg']['token'] = $val->token;
            $item["option"]['stg']['system'] = "theme";
            $array[$val->id] = $item;
        }

        return $array;
    }

    function getComponent()
    {
        $rs = DB::table('component')->select()->get()->toArray();
        $array = [];
        if ($rs) {
            foreach ($rs as $val) {
                $item = [
                    "type" => $val->type,
                    "option" => unserialize($val->data)
                ];
                $array[$val->id] = $item;
            }
        }
        return $array;
    }

    public function list(Request $request)
    {
        $this->getcrumb();
        $filter = $request->query('filter', "");
        $search = $request->query('search', "");
        $status = $request->query('status', "");
        $date = $request->query('date', "");

        $config = config_get('option', "core:layout");
        $data = $request->query();

        $page = null;
        if (isset($data['action'])) {
            //$parameter = $data['form'];
            $page = 1;
        } else {
            //$parameter = $data;
        }
        $parameter = $data;

        $type = isset($request->route()->defaults['type']) ? $request->route()->defaults['type'] : '';

        $use = $this->getListType($type);
        $listsType = [];
        $route = [];
        if (count($use) > 0) {
            if ($type != "") {
                $listsType = [$use['value'] => $use['label']];
                $route['type'] = $type;
                $parameter['filter']['type'] = $use['value'];
            } else {
                $listsType = array_merge($this->listsType, $use);
            }
        }
        $item = isset($config['pagination']['item']) ? $config['pagination']['item'] : 20;
        $item = 1;
        $models = DB::table('layout');

        if (isset($search) && !empty($search) || isset($parameter["filter"]['name']) && !empty($parameter['filter']['name']) && $search = $parameter['filter']['name']) {
            $models->where('name', 'like', '%' . $search . '%');

        }
        if (isset($parameter["filter"]['type']) && !empty($parameter['filter']['type'])) {
            $models->where('type', $parameter['filter']['type']);
        }
        if (!empty($status) || $status != "") {
            $models->where('status', $status);
        }
        if (!isset($parameter['order_by'])) {
            $parameter['order_by']['col'] = 'id';
            $parameter['order_by']['type'] = 'desc';
        } else {
            if (isset($parameter['action'])) {
                $parameter['order_by']['type'] = isset($parameter['order_by']['type']) && $parameter['order_by']['type'] == "desc" ? "asc" : "desc";

            }
        }
        if (isset($parameter['action'])) {
            unset($parameter['action']);
        }
        $models->orderBy($parameter['order_by']['col'], $parameter['order_by']['type']);

        $models = $models->paginate($item, ['*'], 'page', $page);
        $models->appends($parameter);


        return $this->render('layout.list', [
            'models' => $models,
            "listsType" => $listsType,
            "use" => $use,
            "route" => $route,
            'parameter' => $parameter
        ]);
    }

    public function create($type = "")
    {

        $this->getcrumb()->breadcrumb("Create Layout", false);
        $model = new \Admin\Http\Models\Layout();
        $content = [
            "data" => [],
            "widget" => [],
        ];
        $model->id = 0;
        $model->token = gen_uuid();
        $use = $this->getListType($type);
        return $this->render("layout.create", [
            'model' => $model,
            "content" => $content,
            "info" => [],
            "partials" => $this->getPartial($model->id),
            "db_components" => $this->getComponent(),
            "group" => $use,
            "listsType" => $this->listsType
        ]);
    }

    public function edit($id, $type = "")
    {
        $arr = $this->getListType('theme');


        $info = [];
        $model = \Admin\Http\Models\Layout::find($id);
        $this->getcrumb()->breadcrumb(z_language('Edit Layout :name', ["name" => $model->name]), false);
        try {
            $content = unserialize(base64_decode($model->content));
            $info = $model->toArray();
            unset($info['content']);
            unset($info['data']);
        } catch (\Exception $ex) {
            $content = [
                "data" => [],
                "widget" => []
            ];
        }
        if (!isset($content['data'])) {
            $content["data"] = [];
        }
        if (!isset($content['widget'])) {
            $content["widget"] = [];
        }

        $use = $this->getListType($type);
        $obj_layout = new \Admin\Lib\LayoutBlade();
        return $this->render("layout.edit", [
            'model' => $model,
            "content" => $content,
            "info" => $info,
            "partials" => $this->getPartial($model->id),
            "db_components" => $this->getComponent(),
            'group' => $use,
            "listsType" => $this->listsType,
            "sources" => $obj_layout->getContent($model->slug, $model->token, $model->type_group, $model->type)
        ]);
    }

    public function build(Request $request)
    {
        $this->getcrumb()->breadcrumb("Build Layout", false);
        $lists = \Admin\Http\Models\Layout::where('type_group', 'theme')->orderBy("updated_at", "desc")->get();
        return $this->render('layout.build', ['lists' => $lists]);
    }


}