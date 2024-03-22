<?php
namespace Zoe\Hooks;
abstract class AbstractHookEvent
{
    protected $_listeners = [];
    public function addListener($hook, $callback, $priority = 25)
    {
        $this->_listeners[$hook][$priority] = ['callback'=>$callback];
    }
    public function getListeners()
    {
        foreach ($this->_listeners as $key => &$listeners) {
            uksort($listeners, function ($par1, $par2) {
                return strnatcmp($par1, $par2);
            });
        }
        return $this->_listeners;
    }
    protected function getFunction($callback)
    {
        if (is_string($callback)) {
            if (strpos($callback, '@')) {
                $callback = explode('@', $callback);
                return [app('\\' . $callback[0]), $callback[1]];
            } else {
                return $callback;
            }
        } elseif ($callback instanceof \Closure) {
            return $callback;
        } elseif (is_array($callback) && sizeof($callback) > 1) {
            if (is_object($callback[0])) {
                return $callback;
            }
            return [app('\\' . $callback[0]), $callback[1]];
        }
        return false;
    }
    abstract function fire($action, array $args);
}