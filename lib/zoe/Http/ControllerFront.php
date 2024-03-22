<?php

namespace Zoe\Http;
class ControllerFront extends Controller
{

    public function render($view, $data = [], $layout = 'home', $key = "theme")
    {
        $request = request();
        $theme = config_get('theme', "active");
        $keyNameLayout = app()->getKey("_layout");
        $layout = isset($request->route()->defaults[$keyNameLayout]) ? $request->route()->defaults[$keyNameLayout] : $layout;

        $this->layout = "zoe::layouts.theme." . $theme . '.layout-' . $layout;

        $alias = app()->getConfig()['views']['alias'];
        $data = array_merge($this->data, $data);

        $keyName = app()->getKey("_view_alias");
        $_view_alias = isset($request->route()->defaults[$keyName]) ? $request->route()->defaults[$keyName] : "";

        if (isset($alias['frontend'][$_view_alias . ":" . $view])) {
            $keyView = $alias['frontend'][$_view_alias . ":" . $view];
        } else if (isset($_view_alias)) {
            $keyView = $_view_alias . '::controller.' . $view;
        } else {
            $keyView = $view;
        }
        return $this->_render($keyView, $data, $key);
    }
}
