<?php
namespace PluginLayout\Controllers;
class IndexController extends \Zoe\Http\ControllerBackend
{
    public function list()
    {
        $this->layout = "pluginLayout::layout";
        return $this->render('index.list', [], 'pluginLayout');
    }
}