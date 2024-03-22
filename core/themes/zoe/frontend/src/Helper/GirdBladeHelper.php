<?php

namespace ZoeTheme\Helper;
class GirdBladeHelper extends \Admin\Lib\GirdBladeHelper
{
    public function CallBackTag()
    {
        return require __DIR__ . '/../../resource/configs/gird.php';
    }

    public function layout_body($content, $option = [])
    {
        return "<div id='app'>" . $content . "</div>";
    }

    public function layout_main($content, $option = [])
    {
        return "<div class='py-4'>" . $content . "</div>";
    }

    public function layout_card($content, $option = [])
    {
        $html = '<div class="card">';
        $par = [];
        if (isset($option['cfg']['title']) && !empty($option['cfg']['title'])) {
            $html .= '<div class="card-header">{{$option["cfg"]["title"]}}</div>';
            $par['$option'] = var_export($option, true);
        }
        $html .= '<div class="card-body">' . $content . '</div></div>';
        return count($par) > 0 ? $this->func($html, $par) : $html;
    }

    public function layout_container($content, $option = [])
    {
        return '<div class="container"><div class="row justify-content-center"><div class="col-md-8">' . $content . '</div></div></div>';
    }

    public function layout_mainmenu($content, $option = [])
    {
        return '<div class="mainmenu-wrapper"><div class="container">' . $content . '</div></div>';
    }
    public function layout_homepageSlider($content, $option = []    ){

    }
}