<?php

namespace Admin\Lib;
class Layout
{
    public $stringFunction = "";
    public $stringInclude = [];

    protected function func($content, $arr_par = [], $group = true)
    {

        $par = '';
        $val = '';
        foreach ($arr_par as $k => $v) {
            $par .= $k . ',';
            $val .= $v . ',';
        }
        $par .=  '$zlang';
        $val .=  '$zlang';
//        $par = trim($par, ',');
//        $val = trim($val, ',');

        $func = rand(1000, 9999) . "_" . rand(1000, 9999);
        $t = time();
        $html = '
        @function(func_' . $t . '_' . $func . ' (' . $par . '))
            ' . $content . '
        @endfunction';
        if ($group) {
            $this->addFunc($html);
            return '@func_' . $t . '_' . $func . '(' . $val . ')' . PHP_EOL;
        } else {
            return $html . PHP_EOL . '@func_' . $t . '_' . $func . '(' . $val . ')' . PHP_EOL;
        }
    }

    public function addFunc($func)
    {
        $this->stringFunction .= $func . "\n";
    }

    public function GetFunc()
    {

        return $this->stringFunction;
    }

    public function addInclude($inc)
    {
        if (empty($inc)) return;
        $this->stringInclude[$inc] = $inc;
    }

    public function GetStringInclude()
    {
        $string = PHP_EOL;
        foreach ($this->stringInclude as $inc) {
            $string .= "@z_include(" . $inc . ")" . PHP_EOL;
        }
        $string .= PHP_EOL;
        return $string;
    }

}