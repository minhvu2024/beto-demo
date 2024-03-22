<?php
namespace Zoe;
class AssetManager{
    private $assets = [
        "css"=>[],
        "js"=>[]
    ];

    public function __construct($assets)
    {
        $this->assets = $assets;

    }
    public function css($name,...$libs)
    {
        if(!isset($this->assets['css'][$name])){
            $this->assets['css'][$name] = [];
        }
        foreach ($libs as $lib) {
            $this->assets['css'][$name][$lib] = 1;
        }
        return $this;
    }
    public function js($name,...$libs)
    {
        if(!isset($this->assets['js'][$name])){
            $this->assets['js'][$name] = [];
        }
        foreach ($libs as $lib) {
            $this->assets['js'][$name][$lib] = 1;
        }
        return $this;
    }

}