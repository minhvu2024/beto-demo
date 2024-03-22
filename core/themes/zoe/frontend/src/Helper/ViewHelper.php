<?php

namespace ZoeTheme\Helper;
class ViewHelper extends \Admin\Lib\ViewHelper
{
    public function layout_content($option = [], $key = "content")
    {
        return "<div class='content'>@yield('{$key}')</div>";
    }

    public function layout_login_form($option = [])
    {
        if (isset($option['cfg']['view'])) {
            $content = "@includeIf('" . $option['cfg']['view'] . "', " . (var_export(isset($option['opt']) ? $option['opt'] : [], true)) . ")";
            return $content;
        }
        return "";
    }


}