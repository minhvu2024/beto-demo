<?php

namespace Zoe\Providers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;
use Composer\Autoload\ClassLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;
use Appstract\BladeDirectives\DirectivesRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    protected $config_zoe = [];
    protected $file;

    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
//        \Debugbar::enable();
//        DB::listen(function ($query) {
//           // echo $query->sql;
//            // $query->sql
//            // $query->bindings
//            // $query->time
//        });
        $this->app->key = md5(config('app.key'));
        $this->app->ReadCache();
        $this->app->InitLanguage();
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {

        });
        $this->file = new \Illuminate\Filesystem\Filesystem();
        $time_start = microtime(true);
        $this->blade();
        $this->app['router']->aliasMiddleware("permission", \Zoe\Http\Middleware\PermissionMiddleware::class);
        $this->app['router']->aliasMiddleware("minify", \Zoe\Http\Middleware\Minify::class);

        $prefixAdmin = explode("/", request()->path());
        $admin_url = config('tigon.url_admin');
        $this->app->is_admin = isset($prefixAdmin[0]) ? ("/" . $prefixAdmin[0] == $admin_url) : false;

        $this->config_zoe = config('zoe');

        $this->app->singleton('asset-manager-minify-css', function () {
            return new \MatthiasMullie\Minify\CSS();
        });
        $this->app->singleton('asset-manager-minify-js', function () {
            return new \MatthiasMullie\Minify\JS();
        });


        $this->InitModules();

        $this->InitPlugins();
        $this->InitTheme();
        $this->autoLoad();
        $this->providers();
        $this->InitViews();
        $this->InitComponents();

    }

    public function blade()
    {
        Blade::directive('Zoe_Asset', function ($expr) {
            return '<?php echo asset("' . $expr . '") ?>';
        });
        Blade::directive('ImgZoeImage', function ($expr) {
            return Blade_ImgZoeImage($expr);
        });
        Blade::directive('ZoeImage', function ($expr) {
            $isBool = 'true';
            $par = $expr . ',$config,' . $isBool;
            //$url,[],$action,false,$option
            return '<?php echo ZoeImage(' . $par . ') ?>';
        });
        Blade::directive('src_img_platform', function ($expr) {
            return '<?php echo ZoeSrcImgMobile(' . var_export(json_decode($expr, true), true) . ') ?>';
        });
        Blade::directive('ZoeWidget', function ($expr) {
            return "<?php print_r({$expr}); ?>";
        });
        Blade::directive('zlang', function ($parameters) {
            return '<?php echo $zlang(' . ($parameters) . '); ?>';
        });
        Blade::directive('zlang_e', function ($parameters) {
            return '<?php echo $zlang(' . e($parameters) . ') ?>';
        });
        Blade::directive('z_language', function ($parameters) {
            return 'call_user_func_array("z_language",' . $parameters . ')';
        });
        Blade::directive('run_component', function ($parameters) {
            return 'run_component(' . $parameters . ')';
        });
        Blade::directive('z_include', function ($parameters) {
            return '<?php require_once(base_path("' . $parameters . '")); ?>';
        });


        Blade::directive('function', function ($expression) {
            if (!preg_match("/^\s*([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/", $expression, $matches)) {
                throw new \Exception("Invalid function name given in blade template: '$expression' is invalid");
            }
            $name = $matches[1];
            if (preg_match("/\((.*)\)/", $expression, $matches)) {
                $params = $matches[1];
            } else {
                $params = "";
            }
            Blade::directive($name, function ($expression) use ($name) {
                $expression = trim($expression);
                if ($expression) {
                    $expression .= " , ";
                }
                return "<?php $name ($expression \$__env); ?>";
            });
            $params = trim($params);
            if ($params) {
                $params .= " , ";
            }
            return "<?php function $name ( $params  \$__env ) { ?>";
        });

        Blade::directive('return', function ($expression) {
            return "<?php return ($expression); ?>";
        });
        Blade::directive('endfunction', function () {
            return "<?php } ?>";
        });
    }

    public function InitModules()
    {
        if (isset($this->config_zoe ['modules'])) {
            $modules = $this->config_zoe['modules'];

            foreach ($modules as $module) {
                $this->InitModule($module);
            }
            $modules = DB::table('module')
                ->select()->where('status', 1)->get();

            foreach ($modules as $module) {
                $this->InitModule($module->name, false);
            }

        }
    }

    public function providers()
    {

        foreach ($this->app->getConfig()->packages["providers"] as $class => $provider) {
            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        }
    }

    public function autoLoad()
    {
        $loader = new ClassLoader();

        foreach ($this->app->getConfig()->packages['namespaces'] as $namespace => $path) {
            $loader->addPsr4($namespace . '\\', base_path($path));
        }
        if (is_array($this->app->getConfig()->class_maps)) {

            foreach ($this->app->getConfig()->class_maps as $class_map) {
                foreach ($class_map as $namespace => $path) {
                    $loader->addClassMap([$namespace => base_path($path)]);
                }
            }
        }

        $loader->register();

    }

    public function InitModule($module, $system = true)
    {
        $absolute_path = ($this->config_zoe['structure']['module'] . '/' . $module);
        $relativePath = base_path($absolute_path);
        if (file_exists($relativePath . '/Module.php')) {
            require_once $relativePath . '/Module.php';
            if ($system) {
                $name = ucwords($module);
            } else {
                $name = 'Module' .Str::studly($module);
            }
            $class = '\\' . $name . '\\Module';
            $object = new $class();

            if ($this->app->getConfig(true)->cache == 0) {
                $this->module($module, $object, $absolute_path, "module", $system);
            }
            $this->app->_modules[$module] = $object;

        }
    }

    public function module($module, $object, $absolute_path, $typeModule, $system)
    {

        $fileConfig = $object->FileConfig();
        $relativePath = base_path($absolute_path);
        $folders = ["backend", "frontend"];
        foreach ($folders as $type) {
            foreach ($fileConfig as $file) {
                $_file = $relativePath . "/" . $type . "/resource/configs/" . $file . ".php";

                if (file_exists($_file)) {
                    $data = include $_file;

                    if (is_array($data)) {
                        if (isset($data["routers"])) {
                            $routers = [];
                            foreach ($data["routers"] as $keys => $_routers) {
                                $routers[$keys] = [];
                                foreach ($_routers as $key => $router) {
                                    $routers[$keys][$key] = $router;
                                    if ($typeModule == "theme") {
                                        $routers[$keys][$key]["sub_prefix"] = "/";
                                    } else {
                                        if (isset($routers[$keys][$key]["sub_prefix"])) {
                                            if ($system == false) {
                                                $routers[$keys][$key]["sub_prefix"] = "/module" . $routers[$keys][$key]["sub_prefix"];
                                            }
                                        }
                                    }
                                    if (!isset($routers[$keys][$key]['module'])) {
                                        $routers[$keys][$key]['module'] = ["name" => $module, "type" => $typeModule];
                                    }
                                }
                            }
                            $data["routers"] = $routers;
                        }
                        if (isset($data["views"])) {
                            $_paths = [];
                            foreach ($data["views"]["paths"] as $alise => $paths) {
                                $_paths[$module][$type] = [
                                    "alias" => $alise,
                                    "path" => $absolute_path . "/" . $paths . '/resource/views',
                                ];
                            }
                            $data["views"]["paths"] = $_paths;
                            if (isset($data["views"]["alias"])) {
                                $data["views"]["alias"] = [$type => $data["views"]["alias"]];
                            }
                        }
                        if (isset($data["components"])) {
                            if (isset($data["components"]["components"])) {
                                $components = $data["components"]["components"];
                                $data["components"]["components"] = [];
                                foreach ($components as $component => $conf) {
                                    $data["components"]["components"][$component] = [$module => ['v' => 'component', 't' => $type, "m" => $typeModule, 'conf' => $conf]];
                                }
                            }
                            if (isset($data["components"]["widgets"])) {
                                $components = $data["components"]["widgets"];
                                $data["components"]["widgets"] = [];
                                foreach ($components as $component => $conf) {
                                    $data["components"]["widgets"][$component] = [$module => ['v' => 'widget', 't' => $type, "m" => $typeModule, 'conf' => $conf]];
                                }
                            }
                            if (isset($data["components"]["configs"])) {
                                $components = $data["components"]["configs"];
                                foreach ($components as $k => $value) {
                                    $data["components"]["configs"][$k] = $value;
                                    $data["components"]["configs"][$k]['view'] = [$module => ['v' => 'widget', 't' => $type, "m" => $typeModule, 'v' => $value["view"]]];
                                }
                            }
                        }
                        if (isset($data["packages"])) {
                            $data["packages"]["paths"][$typeModule . ":" . $module] = $absolute_path;
                            foreach ($data["packages"]["namespaces"] as $namespaces => $_path) {
                                $data["packages"]["namespaces"][$namespaces] = $absolute_path . "/" . $_path . "/src";
                            }
                        }

                        $this->app->getConfig()->add($data);
                    }
                }
            }
        }

    }

    public function InitPlugins()
    {
        if (isset($this->config_zoe ['plugins'])) {

            //  $plugins = $this->config_zoe['plugins'];
            $plugins = config_get('plugin', 'lists');

            foreach ($plugins as $plugin => $time) {
                $this->InitPlugin($plugin);
            }
        }
    }

    public function InitPlugin($plugin)
    {
        $absolute_path = $this->config_zoe['structure']['plugin'] . "/" . $plugin;
        $relativePath = base_path($absolute_path);

        if (file_exists($relativePath . '/Plugin.php')) {
            require_once $relativePath . '/Plugin.php';
            $class = '\\' . $plugin . '\\Plugin';
            $object = new $class();
            if ($this->app->getConfig(true)->cache == 0) {
                $this->plugin($plugin, $object, $absolute_path);
            }
//         $this->app->_plugin[$module] = $object;
        }
    }

    public function plugin($plugin, $object, $absolute_path)
    {
        $fileConfig = $object->FileConfig();
        $relativePath = base_path($absolute_path);
        foreach ($fileConfig as $file) {
            $_file = $relativePath . "/resource/configs/" . $file . ".php";
//            echo $_file;
            if (file_exists($_file)) {
                $data = include $_file;
                if (is_array($data)) {
                    $data = include $_file;
                    $class_maps = [];
                    if (isset($data["class_maps"])) {
                        $class_maps = $data["class_maps"];
                        foreach ($data["class_maps"] as $n => $c) {
                            $class_maps[$n] = $absolute_path . "/src" . $c;
                        }
                    }
                    if (count($class_maps) > 0) {
                        $data["class_maps"] = [];
                        $data["class_maps"][$plugin] = $class_maps;
                    }
                    $routers = [];
                    if (isset($data["routers"])) {
                        foreach ($data["routers"] as $key => $router) {
                            $routers["backend"]["plugin:" . $key] = $router;
                            if (isset($routers["backend"]["plugin:" . $key]["sub_prefix"])) {
                                $routers["backend"]["plugin:" . $key]["sub_prefix"] = "/plugin" . $routers["backend"]["plugin:" . $key]["sub_prefix"];
                            }
                            if (!isset($routers["backend"]["plugin:" . $key]['module'])) {
                                $routers["backend"]["plugin:" . $key]['module'] = ["name" => $plugin, "type" => "plugin"];
                            }
                        }
                    }
                    $data["routers"] = $routers;
                    if (isset($data["views"])) {
                        $views = $data["views"];
                        if (isset($views["path"])) {
                            $views["paths"]["plugin"][$plugin] = [
                                "path" => $absolute_path . $views["path"],
                                "alias" => "plugin" . $plugin
                            ];
                        }
                        unset($views["path"]);
                        $data['views'] = $views;
                    }

                    if (isset($data["components"])) {

                        if (isset($data["components"]["components"])) {
                            $components = $data["components"]["components"];
                            $data["components"]["components"] = [];
                            foreach ($components as $component => $conf) {
                                $data["components"]["components"][$component] = [$plugin => ['t' => "", "m" => "plugin", 'conf' => $conf]];
                            }
                        }
                        if (isset($data["components"]["configs"])) {
                            $components = $data["components"]["configs"];
                            foreach ($components as $k => $value) {
                                $data["components"]["configs"][$k] = $value;
                                $data["components"]["configs"][$k]['view'] = [$plugin => ['t' => "", "m" => "plugin", 'v' => $value["view"]]];
                            }
                        }
                    }
                    $this->app->getConfig()->add($data);
                }
            }
        }
        $this->app->getConfig()->add(["packages" => ["paths" => ["plugin:" . $plugin => $absolute_path]]]);
    }

    public function InitTheme()
    {
        $theme = config_get('theme', "active", "");
        $this->app->_theme = $theme;

        $absolute_path = ($this->config_zoe['structure']['theme'] . '/' . $theme);
        $relativePath = base_path($absolute_path);

        if (file_exists($relativePath . '/Theme.php')) {

            require_once $relativePath . '/Theme.php';

            $class = '\\' . ucwords($theme) . 'Theme\\Theme';

            $object = new $class();

            $this->module($theme, $object, $absolute_path, "theme", false);
        }
    }

    public function InitComponent($component, $_file, $_alias, $_opt_, $config, $view = "components")
    {

        if (file_exists($_file)) {
            $config_component = include $_file;

            if (isset($config_component['views'])) {
                $_arr_view = [];
                foreach ($config_component['views'] as $___key => $____view) {

                    if (is_array($____view)) {
                        if (isset($____view['view'])) {

                            if (view()->exists($_alias . "::" . $view . "." . $component . ".views." . $____view['view'], [])) {
//                                                dump($_alias . "::component." . $component . ".views." . $____view['view']);
                                $_arr_view[$___key] = $____view;
                                $_arr_view[$___key]["view"] = $_alias . "::" . $view . "." . $component . ".views." . $____view['view'];
                            } else {
//                                                dump("-".$_alias . "::component." . $component . ".views." . $____view['view']);
                            }
                        }
                    }
                }
                $config_component['views'] = $_arr_view;
            }

            if (isset($config_component['configs'])) {
                $_arr_config = [];
                foreach ($config_component['configs'] as $_key => $_config) {
                    if (is_array($_config)) {
                        if (isset($_config["view"])) {
                            if (view()->exists($_alias . "::" . $view . "." . $component . ".configs." . $_config['view'], [])) {
                                $_arr_config[$_key] = $_config;
                                $_arr_config[$_key]["view"] = $_alias . "::" . $view . "." . $component . ".configs." . $_config['view'];
                            }
                        } else if (isset($_config["template"])) {
                            $_arr_config[$_key] = $_config;
                        }
                    }
                }

                $config_component['configs'] = $_arr_config;
            }

            if (isset($config['conf']['type'])) {
                $prefix = $config['conf']['type'];
            } else {
                $prefix = $config['m'];
            }
            $this->app->getComponents()->config->add([$prefix . ":" . $view . ":" . $component => $config_component]);
        } else {
            echo $_file . "<BR>";
        }
    }

    public function InitComponents()
    {
        if ($this->app->getComponents(true)->cache > 0) {
            return;
        }
        $folder = "components";

        $components = $this->app->getConfig()->components["components"];

        $components_configs = $this->app->getConfig()->components["configs"];

//        dump($components_configs);
        foreach ($components_configs as $key => $components_config) {
            $_view = $components_config["view"];
            $_arr = [];
            foreach ($_view as $m => $__view) {
                $_alias = $this->app->getConfig()->views["paths"][$m][$__view["t"]]["alias"];
                if (view()->exists($_alias . "::" . $folder . ".configs." . $__view["v"], [])) {
                    $_arr[] = $_alias . "::" . $folder . ".configs." . $__view["v"];
                }
            }
            if (count($_arr) > 0) {
                $components_config["view"] = array_pop($_arr);
                $this->app->getComponents()->template->add([$key => $components_config]);
            }
        }
        $folders = ['components', "widgets"];
        foreach ($folders as $folder) {
            $components = $this->app->getConfig()->components[$folder];
            foreach ($components as $component => $modules) {
                foreach ($modules as $module => $opt) {
                    $keyPath = $opt["m"] . ":" . $module;
                    $type = $opt["m"];

                    $_module = $module;
                    if (isset($this->app->getConfig()->packages["paths"][$keyPath])) {
                        $path = base_path($this->app->getConfig()->packages["paths"][$keyPath]);
                        if ($type == 'plugin') {
                            $_fileCom = $path . "/resource/views/" . $folder . "/" . $component . "/component.php";
                        } else {
                            $_fileCom = $path . "/" . $opt["t"] . "/resource/views/" . $folder . "/" . $component . "/component.php";
                        }
                        if (file_exists($_fileCom)) {
                            $info_component = include $_fileCom;
                            $info_component['name'] = $component;
                            $info_component['option']['cfg']["id"] = gen_uuid();
                            $info_component['option']['stg']["system"] = $opt["m"];
                            $info_component['option']['stg']["module"] = $module;
                            $info_component['option']['stg']["type"] = $folder;
                            $info_component['option']['stg']["pos"] = $opt["t"];

                            $info_component['option']['stg']["layout"] = isset($opt["conf"]['layout']) ? $opt["conf"]['layout'] : "layout";
                            $this->app->getComponents()->info->add([$type . ":" . $folder . ":" . $component => $info_component]);
                        } else {
//                        var_dump($opt);
//                        echo $_fileCom . "<BR>";
                        }
                    }
                    if (isset($this->app->getConfig()->packages["paths"][$keyPath])) {
                        $path = base_path($this->app->getConfig()->packages["paths"][$keyPath]);
                        $_opt_ = [
                            "module" => $module,
                            "type" => $opt["t"],
                            "alias" => ""//$this->app->getConfig()->views["paths"][$module][$opt["t"]]["alias"]
                        ];
                        if ($opt['m'] == "plugin") {
                            $view_paths = isset($this->app->getConfig()->views["paths"]["plugin"][$module]) ? $this->app->getConfig()->views["paths"]["plugin"][$module] : false;
                            $_fileConfig = $path . "/resource/views/" . $folder . "/" . $component . "/config.php";
                        } else {
                            $view_paths = isset($this->app->getConfig()->views["paths"][$module][$opt["t"]]) ? $this->app->getConfig()->views["paths"][$module][$opt["t"]] : false;
                            $_fileConfig = $path . "/" . $opt["t"] . "/resource/views/" . $folder . "/" . $component . "/config.php";
                        }
                        if (is_array($view_paths)) {
                            $this->InitComponent($component, $_fileConfig, $view_paths["alias"], $_opt_, $opt, $folder);
                        } else {

                        }
                    }
                }
            }
        }
//        dd($this->app->getComponents());
    }

    public function InitViews()
    {
        foreach ($this->app->getConfig()->views['paths'] as $alise => $modules) {
            foreach ($modules as $_view) {
                $this->loadViewsFrom(base_path($_view['path']), $_view['alias']);
            }
        }
        $this->loadViewsFrom(storage_path('app/views'), "zoe");
    }

}
