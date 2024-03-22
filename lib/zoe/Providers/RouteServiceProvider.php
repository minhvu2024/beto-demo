<?php

namespace Zoe\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Storage;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->setRootControllerNamespace();
        if ($this->routesAreCached()) {
            $this->loadCachedRoutes();
        } else {
            $this->loadRoutes();
            $this->app->booted(function () {
                $this->app['router']->getRoutes()->refreshNameLookups();
                $this->app['router']->getRoutes()->refreshActionLookups();
            });
        }
        $this->InitRouters();
        view()->share('time_exe', microtime(true) - $this->app->time_start);
    }

    public function InitRouters()
    {
        $config = config('zoe.router');
        if (isset($config['backend'])) {
            $this->InitRouter('backend', $this->app->getConfig()->routers['backend'], $config['backend']);
        }
        if (isset($config['frontend']) && isset($this->app->getConfig()->routers['frontend'])) {
            $this->InitRouter('frontend', $this->app->getConfig()->routers['frontend'], $config['frontend']);
        }

    }

    public function InitRouter($guard, $routers, $config)
    {
        $configRouter = config_get('router', $guard);

        $views_paths = $this->app->getConfig()->views['paths'];

        $keyPrivate = $this->app->key;
        foreach ($routers as $name => $route) {

            if (isset($route['prefix'])) {
                $prefix = $route['prefix'];
            } else if (isset($route['sub_prefix'])) {
                $prefix = $config['prefix'] . $route['sub_prefix'];
            } else {
                $prefix = $config['prefix'];
            }
            $namespace = isset($route['namespace']) ? $route['namespace'] . '\\' : '';
            $controller = isset($route['controller']) ? $route['controller'] . '@' : '';

            $_module = $route['module']['name'];

            if ($route['module']['type'] == "plugin") {
                $_view_alias = isset($views_paths["plugin"][$_module]['alias']) ? $views_paths["plugin"][$_module]['alias'] : "";
            } else {
                $_view_alias = isset($views_paths[$_module][$guard]['alias']) ? $views_paths[$_module][$guard]['alias'] : "";
            }

            $permissions = $this->app->getPermissions();
            foreach ($route['router'] as $key => $_route) {
                $method = ['get'];
                if (isset($_route['method'])) {
                    $method = $_route['method'];
                }
                $permission = $name . ':' . $key;
                if (isset($_route['name'])) {
                    $alias = $_route['name'];
                } else {
                    $alias = $guard . ":" . $permission;
                }
                if (isset($_route['action'])) {
                    $action = $namespace . $controller . $_route['action'];
                } else {
                    $action = $namespace . $controller . $key;
                }
                if (isset($configRouter['data'][$alias]['uri'])) {
                    $link = $configRouter['data'][$alias]['uri'];
                } else {
                    $link = isset($_route['link']) ? $_route['link'] : (isset($_route['url']) ? $prefix . $_route['url'] : "");
                }
                if (empty($link)) {
                    continue;
                }
                $middleware = ["web"];
                $acl = "";
                $auth_guard = isset($_route["guard"]) ? $_route["guard"] : (isset($route["guard"]) ? $route["guard"] : $guard);
                if (isset($configRouter['data'][$alias]['acl'])) {
                    if ($configRouter['data'][$alias]['acl'] == 'no-login') {
                        $auth_guard = "";
                    } else {
                        if ($configRouter['data'][$alias]['acl'] != 'login') {
                            $auth_guard = $guard;
                            $acl = $configRouter['data'][$alias]['acl'];
                        } else {
                            $auth_guard = $guard;
                        }
                    }
                }
                if (!empty($auth_guard)) {
                    $middleware[] = 'auth:' . $auth_guard;

                    if (empty($acl)) {
                        $acl = isset($_route["acl"]) ? ($_route["acl"] == true ? $permission : $_route["acl"]) : (isset($route["acl"]) ? $route["acl"] :"");
                    }
                    if (!empty($acl)) {
                        $middleware[] = "permission:" . $auth_guard . "-" . $acl;
                    }
                    if (!empty($acl)) {
                        if (!isset($permissions->data[$auth_guard][$acl])) {
                            $this->app->getPermissions()->data[$auth_guard][$acl] = [];
                        }
                        $this->app->getPermissions()->data[$auth_guard][$acl][] = $alias;
                        $this->app->getPermissions()->aliases[$alias] = $acl;
                    }
                }

                if (isset($configRouter['data'][$alias]['status']) && $configRouter['data'][$alias]['status'] == 2) {
                    continue;
                }
                if (isset($_route['form'])) {
                    $r = Route::match(['post'], $link . '-form', $namespace . $controller . "postCreate");
                    $r->name($alias . ":post");
                    $r->middleware($middleware);
                }

                $r = Route::match($method, $link, $action);

                if (isset($_route['defaults'])) {
                    foreach ($_route['defaults'] as $_key => $_default) {
                        $r->defaults($_key, $_default);
                    }
                }
                $r->defaults($keyPrivate . "_module", $_module);

                $r->defaults($keyPrivate . "_view_alias", $_view_alias);

                if (isset($configRouter['data'][$alias]['layout'])) {
                    $r->defaults($keyPrivate . "_layout", $configRouter['data'][$alias]['layout']);
                }
                $r->name($alias);
                if(isset($_route['wheres'])){
                    foreach ($_route['wheres'] as $_name=>$_exp){
                        $r->where($_name, $_exp);
                    }
                }
                if (isset($configRouter['data'][$alias]['cache']) && $configRouter['data'][$alias]['cache'] != 0) {
                    $middleware[] = 'cache.response:' . $alias . "," . $configRouter['data'][$alias]['cache'];
                }
//                echo "<pre>";
//                echo $alias;
//                print_r($middleware);
                $r->middleware($middleware);
            }
        }
//        dd($routers);
        $this->app->WriteCache();
//        die;
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
