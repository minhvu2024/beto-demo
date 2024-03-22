<?php

namespace Zoe;
abstract class Module
{
    public $path = "";
    public static $name = "";
    public static $description = "";
    public static $version = "1.0.0";
    public static $author = "Manh Trung";
    public static $require = [];
    public static $dev = false;

    public function __construct()
    {
        $this->Init();
        $this->LoadModule();
        $this->GetResource("backend");
        $this->GetResource("frontend");
    }

    public abstract function Init();

    private function LoadModule()
    {

    }

    public function FileConfig()
    {
        return ["configs", "router", "sidebar", "providers", "component", "language"];
    }

    public function GetClassMap()
    {
        return [];
    }

    public function Helper()
    {

    }

    public function GetResource($prefix)
    {
//        $_path = $this->path.'/'.$prefix."/resource/configs";
//        $this->loadResource($_path."/configs.php");
//        $this->loadResource($_path."/router.php");
//        $this->loadResource($_path."/sidebar.php");
//        $this->loadResource($_path."/providers.php");
//        $this->loadResource($_path."/component.php");
    }

    public function loadResource($path)
    {
        if (file_exists($path)) {
            $data = include $path;
            if (is_array($data)) {
                app()->getConfig()->add($data);
            }
        }
    }

    public function install()
    {
        return true;
    }

    public function import($step = true, $settings = [], $datas = [])
    {
        return true;
    }

    public function uninstall()
    {

        return true;
    }

    public function export($step = true, $settings = [], $datas = [])
    {
        return true;
    }

    public function saveContent($content)
    {

    }

    protected function CreateRow()
    {

    }

    protected function CreateFileTable()
    {

    }

    protected function CreateRowTable()
    {

    }
}