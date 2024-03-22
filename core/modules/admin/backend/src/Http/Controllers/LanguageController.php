<?php

namespace Admin\Http\Controllers;

use Admin\Http\Models\Layout;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class LanguageController extends \Zoe\Http\ControllerBackend
{
    function getDirContents($dir, $filter = '', &$results = array())
    {
        $files = scandir($dir);
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                if (empty($filter) || preg_match($filter, $path)) $results[] = $path;
            } elseif ($value != "." && $value != "..") {
                $this->getDirContents($path, $filter, $results);
            }
        }
        return $results;
    }
    public function set_lang(Request $request){
        session(['lang'=>$request->lang]);
        $ref = $request->query('ref',false);
        return redirect($ref?base64_decode($ref):route('backend:dashboard:list'));
    }
    public function table()
    {
        return DB::table('config');
    }

    public function ajaxFormSave(Request $request)
    {
        $items = $request->all();
        $items = isset($items['data']) ? json_decode($items['data'], true) : [];

        $this->table()
            ->updateOrInsert(
                [
                    'name' => 'language',
                    'type' => 'data'
                ],
                ['data' => serialize($items)]);

        if (isset($items['lang'])) {
            $data = [];
            $dataLangs = [];
            foreach ($items['lang'] as $lang => $item) {
                foreach ($item as $key => $_item) {
                    if (!isset($data[$lang])) {
                        $data[$lang] = [];
                    }
                    $v = $_item['value'];
                    $k = $_item['name'];
                    $data[$lang][$k] = $v;
                    if (!isset($dataLangs[$_item['key']])) {
                        $dataLangs[$_item['key']] = [];
                    }
                    if (!isset($dataLangs[$_item['key']][$lang])) {
                        $dataLangs[$_item['key']][$lang] = [];
                    }
                    $dataLangs[$_item['key']][$lang][$key] = $_item;
                }
            }
            foreach ($dataLangs as $key => $item) {
                $this->table()
                    ->updateOrInsert(
                        [
                            'name' => $key,
                            'type' => 'language'
                        ],
                        ['data' => serialize($items)]);
            }
            echo json_encode($dataLangs);
            Cache::forever("language:data", $data);
        }
    }

    public static function lang($string_blade, $sub_path, $string_find = "z_language")
    {
        $array = [];

        preg_match_all('/' . $string_find . '\((.*?)\)/', $string_blade, $match);

        if (isset($match[1])) {
            foreach ($match[1] as $val) {
                $key_val = trim($val, "]");
                $key_val = trim($key_val, "[");
                $key_val = trim($key_val, '"\'');
//                $val = trim($val, '[false');
                $key_val = trim($key_val, '"\', ');

                if (substr($key_val, -5) == "false") {
                    $key_val = substr($key_val, 0, strlen($key_val) - 5);
                    $key_val = trim($key_val, '"\', ');
                }
                $key_val = trim($key_val);
                if (substr($key_val, 0, 1) == "$") {
                    continue;
                }
                $Arr = explode("',", $key_val);
                if (count($Arr) == 2) {
                    $key_val = $Arr[0];
                } else {
                    $Arr = explode("\",", $key_val);
                    if (count($Arr) == 2) {
                        $key_val = $Arr[0];
                    }
                }
                $key_val = trim($key_val, '"\', ');

                $key = md5($key_val.'-'.$string_find);
                $value = [
                    "value" => "",
                    "path" => $sub_path,
                    "name" => $key_val,
                    "key"=> md5($key)
                ];
                $array[md5($key)] = $value;
            }
        }

        return $array;
    }

    public function list()
    {
//        $results = $this->getDirContents(base_path('core'), '/\.php$/', $results);
        $language_data = config('zoe.language_data');
//        $file = new \Illuminate\Filesystem\Filesystem();
         $array = lang_all_key();

//        $system_modules = config('zoe.modules');
//        $modules = DB::table('module')
//            ->select()->where('status', 1)->pluck('name')->all();
//        $plugins = config_get('plugin', 'lists');
//
//        foreach ($results as $_file) {
//            $name = str_replace(base_path(), "", $_file);
//            $sub_path = explode(DIRECTORY_SEPARATOR, trim($name, DIRECTORY_SEPARATOR));
//            if (count($sub_path) > 2) {
//                if (
//                    $sub_path[1] == "modules" && !in_array($sub_path[2], $system_modules) && !in_array($sub_path[2], $modules) ||
//                    $sub_path[1] == "plugins" && !isset($plugins[$sub_path[2]])
//                ) {
//                    continue;
//                }
//            }
//
//            $string_blade = $file->get($_file);
//            $array = array_merge($array, static::lang($string_blade, $sub_path));
//
////            preg_match_all('/z_language\((.*?)\)/', $string_blade, $match);
////            if (isset($match[1])) {
////                foreach ($match[1] as $val) {
////                    $val = trim($val, "]");
////                    $val = trim($val, "[");
////                    $keywords = preg_split("/[,]+/", $val);
////
////                    $key_val = trim($keywords[0], '"\'');
////                    $key_val = trim($key_val);
////                    if (substr($key_val, 0, 1) == "$") {
////                        continue;
////                    }
////                    $key = md5($key_val);
////                    $value = [
////                        "value" => "",
////                        "path" => $sub_path,
////                        "name" => $key_val
////                    ];
////                    $array[md5($key)] = $value;
////                }
////            }
//        }

//        $rs = $this->table()->where([
//            'name' => 'language',
//            'type' => 'data'
//        ])->first();
//
//        $data = [];
//        if ($rs && !empty($rs->data)) {
//            $data = unserialize($rs->data);
//        }


        $rs = $this->table()->where([
            'type' => 'language'
        ])->get();

        $langs = new \Zoe\Config();

        foreach ($rs as $k => $v) {
            $langs->add(unserialize($v->data));
        }

        $data = $langs->getArrayCopy();

//        $datas = [];
//        foreach ($langs->lang as $lang => $langs) {
//            if (!isset($datas[$lang])) {
//                $datas[$lang] = [];
//            }
//            foreach ($langs as $val) {
//                if (!isset($datas[$lang][$val['key']])) {
//                    $datas[$lang][$val['key']] = [];
//                }
//                $datas[$lang][$val['key']] = $val;
//            }
//        }

        $tmp = [
            z_language('modules'),
            z_language('themes'),
            z_language('plugins'),
            z_language('acl'),
        ];

        usort($array, function($a, $b) {
            return $a['name'] > $b['name'];
        });

        $lists = [];

        foreach ($array as $k => $value) {
            $key = $value['path'][1] . "." . $value['path'][2];
            if (!isset($lists[$value['path'][1]])) {
                $lists[$value['path'][1]] = [];
            }
            if (!isset($lists[$value['path'][1]][$value['path'][2]])) {
                $lists[$value['path'][1]][$value['path'][2]] = [];
            }
            $lists[$value['path'][1]][$value['path'][2]] [$k] = $value;
        }

        $lists['acl']["Static"] = acl_all_key();
        $permisssionAll = app()->getPermissions();
        foreach ($permisssionAll->data as $name=>$permissions){
            $lists['acl'][$name] = [];
            foreach($permissions as $aliases=>$permission){
                $key = md5('acl-'.json_encode($permission).'-'.$aliases);
                $lists['acl'][$name][$key] = [
                    "value"=>"",
                    "path"=>["acl","router",$aliases],
                    'name'=>$aliases,
                    'key'=>$key
                ];
            }
        }
        return $this->render('language.list', ['langs' => $lists, 'lists' => $array, 'language_data' => $language_data, 'data' => $data]);
    }
}