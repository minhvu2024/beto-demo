<?php

namespace ZoeTheme;

use Zoe\Module as ZModule;

class Theme extends ZModule
{
    public static $require = [
        'blog' => 'module'
    ];

    public function Init()
    {
        // TODO: Implement Init() method.
        $this->path = __DIR__;
    }

}