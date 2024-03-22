<?php

namespace Zoe;

use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Application as App;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class Application extends App
{
    public $_modules = [];

    public $is_admin = false;


    private $_configs;
    private $_permissions = [
        'aliases' => [],
        'data' => [],
    ];
    private $_components;
    private $_language = [

    ];
    private $file;

    private $cache = false;
    public $time_start = 0;

    public $key = "";
    private $_agent;
    public $_theme;
    public $site_language;
    public function __construct(?string $basePath = null)
    {
        $this->_agent = new Agent();

        $this->time_start = microtime(true);
        $this->file = new \Illuminate\Filesystem\Filesystem();
        $this->_configs = new \stdClass();
        $this->_configs->cache = 0;
        $this->_configs->data = new Config();

        $this->_components = new \stdClass();
        $this->_components->cache = 0;
        $this->_components->data = new \stdClass();
        $this->_components->data->info = new Config();
        $this->_components->data->config = new Config();
        $this->_components->data->theme = new Config();
        $this->_components->data->template = new Config();

        $this->_permissions = new \stdClass();
        $this->_permissions->cache = 0;
        $this->_permissions->data = new \stdClass();
        $this->_permissions->data->aliases = [];
        $this->_permissions->data->data = [];
        parent::__construct($basePath);

    }

    public function InitLanguage()
    {
        $this->_language = Cache::rememberForever("language:data", function () {
            $rs = DB::table('config')->where([
                'name' => 'language',
                'type' => 'data'
            ])->first();
            $data = [];
            if ($rs && !empty($rs->data)) {
                $data = unserialize($rs->data);
            }
            return $data;
        });
        $config = config_get("config", "system");
        $this->site_language = isset($config['core']['site_language'])?$config['core']['site_language']:'vi';
    }
    public function getLocale(){
        return  session('lang', $this->site_language);
    }
    public function getTheme()
    {
        return $this->_theme;
    }

    public function getAgent()
    {
        return $this->_agent;
    }

    public function getLanguage()
    {
        return $this->_language;
    }

    public function getComponents($all = false)
    {
        if ($all == false) {
            return $this->_components->data;
        }
        return $this->_components;
    }

    public function getConfig($all = false)
    {
        if ($all == false) {
            return $this->_configs->data;
        }
        return $this->_configs;
    }

    public function getPermissions($all = false)
    {
        if ($all == false) {
            return $this->_permissions->data;
        }
        return $this->_permissions;
    }

    public function ReadCache()
    {
        if ($this->cache == false) {
            return;
        }
        if (Cache::has("zoe:cache")) {
            if (time() - Cache::get("zoe:cache") > 6000) {
                return;
            }
        }
        if ($this->file->isFile(base_path('bootstrap/zoe/config/configs.php'))) {
            $data = $this->file->get(base_path('bootstrap/zoe/config/configs.php'));
            try {
                $this->_configs = unserialize($data);
            } catch (\Exception $ex) {

            }
        }
        if ($this->file->isFile(base_path('bootstrap/zoe/config/components.php'))) {
            $data = $this->file->get(base_path('bootstrap/zoe/config/components.php'));
            try {
                $this->_components = unserialize($data);
            } catch (\Exception $ex) {

            }
        }
        if ($this->file->isFile(base_path('bootstrap/zoe/config/permissions.php'))) {
            $data = $this->file->get(base_path('bootstrap/zoe/config/permissions.php'));
            try {
                $this->_permissions = unserialize($data);
            } catch (\Exception $ex) {

            }
        }

    }

    public function WriteCache()
    {
        if ($this->cache == false) {
            return;
        }
        if ($this->_configs->cache == 0) {
            $this->file->put(base_path('bootstrap/zoe/config/configs.php'), serialize($this->setTimeCache($this->_configs)));
        }
        if ($this->_components->cache == 0) {
            $this->file->put(base_path('bootstrap/zoe/config/components.php'), serialize($this->setTimeCache($this->_components)));
        }
        if ($this->_permissions->cache == 0) {
            $this->file->put(base_path('bootstrap/zoe/config/permissions.php'), serialize($this->setTimeCache($this->_permissions)));
        }
        Cache::add("zoe:cache", time(), 120);
    }

    public function setTimeCache($data)
    {
        $data->cache = time();
        return $data;
    }

    public function getKey($name = "")
    {
        return $this->key . $name;
    }
}