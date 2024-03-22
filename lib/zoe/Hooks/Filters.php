<?php
namespace Zoe\Hooks;
class Filters extends AbstractHookEvent
{
    public function fire($action, array $args)
    {
        $value = isset($args[0]) ? $args[0] : '';
        if ($this->getListeners()) {
            $listeners = $this->getListeners();
            foreach ($listeners as $hook => $listeners) {
                if ($hook === $action) {
                    foreach ($listeners as $arguments) {
                        $args[0] = $value;
                        $value = call_user_func_array($this->getFunction($arguments['callback']), $args);
                    }
                }
            }
        }
        return $value;
    }
    function add_filter($hook, $callback, $priority = 25)
    {
        self::addListener($hook, $callback, $priority);
    }
    function do_filter($hookName, ...$args)
    {
        return self::fire($hookName, $args);
    }
    function get_filters($name = null)
    {
        $listeners = self::getListeners();
        if (empty($name)) {
            return $listeners;
        }
        return array_get($listeners, $name);
    }
}