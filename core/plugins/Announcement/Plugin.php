<?php

namespace Announcement;

use Zoe\Module as ZModule;

class Plugin extends ZModule
{
    public static $require = ['Builder' => 'plugin', 'Gallery' => 'plugin'];

    public function Init()
    {

    }
}