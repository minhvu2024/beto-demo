<?php
namespace Zoe\Hooks;

class Actions extends AbstractHookEvent
{
    public function fire($action, array $args)
    {
        if ($this->getListeners()) {
            foreach ($this->getListeners() as $hook => $listeners) {
                if ($hook === $action) {
                    foreach ($listeners as $arguments) {
                        $func = $this->getFunction($arguments['callback']);
                        if($func != false)
                        return call_user_func_array($func, $args);
                    }
                }
            }
        }
        return null;
    }
    function add_action($hook, $callback, $priority = 20)
    {
        self::addListener($hook, $callback, $priority);
    }
    function do_action($hookName, ...$args)
    {
        return self::fire($hookName, $args);
    }
    public static function has_action($name){
        $listeners = self::getListeners();
        return isset($listeners[$name]);
    }
    function get_actions($name = null)
    {
        $listeners = self::getListeners();
        if (empty($name)) {
            return $listeners;
        }
        return array_get($listeners, $name);
    }
}
