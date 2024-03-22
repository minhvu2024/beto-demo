<?php
namespace User;
use Zoe\Module as ZModule;
class Module extends ZModule{
    public static $name = "User";
    public static $description = "User module";
    public function Init()
    {
        // TODO: Implement Init() method.
        $this->path = __DIR__;
    }

}