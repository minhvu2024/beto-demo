<?php

namespace Admin\Lib;
class ViewHelper extends Layout
{
    public function content($option = [], $key = "content")
    {
        return "@yield('{$key}')";
    }

    public function commposer($option)
    {
        $func = rand(1000, 9999) . "_" . rand(1000, 9999);
        $content = "";

        if (isset($option['cfg']['template']["view"]) && isset($option['cfg']['template']["data"][$option['cfg']['template']["view"]])) {
            $content = $option['cfg']['template']["data"][$option['cfg']['template']["view"]];
        }
        $arr_lang = [];

//        if (isset($option['lang'])) {
//            foreach ($option['lang'] as $langname => $langs) {
//                foreach ($langs as $lang) {
//                    $arr_lang[$langname][$lang['key']] = $lang['val'];
//                }
//            }
//        }
        $t = time();
        $html = '
        @function(func_' . $t . '_' . $func . ' ($data))
            ' . (empty($content) ? "" : htmlspecialchars_decode($content)) . '
        @endfunction';
        $this->addFunc($html);
        return '@func_' . $t . '_' . $func . '(' . var_export(isset($option['opt']) ? $option['opt'] : [], true) . ')';
    }
}