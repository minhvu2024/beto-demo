<?php

namespace Admin\Lib;
class GirdBladeHelper extends Layout
{
    public function CallBackTag()
    {
        return [];
    }

    public function layout_header($content, $option = [])
    {
        return "<header>" . $content . "</header>";
    }

}