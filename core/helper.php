<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Blade;

use Illuminate\Support\Facades\DB;


function ZoeExtension($file)
{
    $tmp = explode('.', $file);
    $file_extension = end($tmp);
    return $file_extension;
}
function str_replace_array($search, $replace, $subject ) {
    foreach ( $replace as $replacement ) {
        $subject = preg_replace("/\?/", $replacement,$subject, 1);
    }
    return $subject;
}
function ZoeImageResize($url, $resize_config = [], $action = true)
{
    $is_storage = false;
    $path = "";
    try {
        if (isset($resize_config['resize'])) {
            if (isset($resize_config['action']) && $resize_config['action'] != "src") {
                $filename = str_replace("/", "_", $url);
                $arr_img = [];
                $imgs = [

                ];
                if (isset($resize_config['platforms'])) {
                    $platforms = is_array($resize_config['platforms']) ? $resize_config['platforms'] : [$resize_config['platforms']];
                    foreach ($platforms as $platform) {
                        if (isset($resize_config[$platform])) {
                            $size = (int)$resize_config[$platform];
                            if ($size > 0) {
                                $imgs[$platform] = $size;
                            }
                        }
                    }
                }
                list($width_old, $height_old) = getimagesize(public_path($url));
                $arrImg = [];
                if (isset($resize_config['action'])) {
                    if ($resize_config['action'] == 'lazy') {
                        if (isset($resize_config['pc']) && (int)$resize_config['pc'] < 100) {
                            $imgs['pc'] = $resize_config['pc'];
                        } else {
                            $arr_img['data-src'] = $url;
                        }
                        $arr_img['lazyload'] = 'on';

                        if (isset($resize_config['lazy'])) {
                            $arr_img['lazytype'] = 'load';
                        } else {
                            $arr_img['lazytype'] = 'scroll';
                        }
                        $arr_img['src'] = ('/assets/image-blank.png');
                    } else if ($resize_config['action'] == 'php') {
                        $arrImg['src'] = $url;
                    } else {
                        $arr_img['src'] = $url;
                    }
                } else {
                    $arr_img['src'] = $url;
                }
                $srcset = [];
                $i = 0;
                $n = count($imgs);
                foreach ($imgs as $name => $v) {
                    $_v = ($v / 100);
                    $i++;
                    $w = $width_old * $_v;

                    if (substr($url, 0, 7) == '/theme/') {
                        $theme = config_get('theme', "active");
                        if ($is_storage) {
                            $path = storage_path('app/public' . '/themes/' . $theme);
                            $uri = '/storage/themes/' . $theme . '/thumb/' . $name . '/' . $v . '/' . $filename;
                        } else {
                            $path = public_path('resource' . '/themes/' . $theme);
                            $uri = '/resource/themes/' . $theme . '/thumb/' . $name . '/' . $v . '/' . $filename;
                        }
                    } else {
                        if ($is_storage) {
                            $path = storage_path('app/public/uploads');
                            $uri = '/storage/uploads/thumb/' . $name . '/' . $v . '/' . $filename;
                        } else {
                            $path = public_path('resource/uploads/');
                            $uri = '/resource/uploads/thumb/' . $name . '/' . $v . '/' . $filename;
                        }
                    }
                    if (!File::exists($path)) {
                        File::makeDirectory($path);
                    }
                    $path = $path . '/thumb';
                    if (!File::exists($path)) {
                        File::makeDirectory($path);
                    }

                    $path = $path . '/' . $name;
                    if (!File::exists($path)) {
                        File::makeDirectory($path);
                    }
                    $path = $path . '/' . $v;
                    if (!File::exists($path)) {
                        File::makeDirectory($path);
                    }
                    $pathFull = $path . '/' . $filename;
                    if (!file_exists($pathFull)) {
                        Image::make(public_path($url))->resize($w, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($pathFull);
                    }
                    if (isset($resize_config['action'])) {
                        if ($resize_config['action'] == 'lazy') {
                            $arr_img[$name == 'pc' ? 'data-src' : 'data-' . $name] = $uri;
                        } else {
                            $arrImg[$name == 'pc' ? 'data-src' : 'data-' . $name] = $uri;
                        }
                        if ($name != 'pc')
                            $srcset[] = $uri;
                        $srcset[] = ($name == 'mobile' ? ' 450w' : ' 750w') . (($i < $n) ? ", " : "");
                    }
                }
                if ($resize_config['action'] == "lazy") {
                    if (count($srcset)) {
                        $arr_img['data-srcset'] = $srcset;
                    }
                }
                if (count($arrImg) > 1) {
                    if (defined('build')) {
                        $arr_img['blade'] = '@src_img_platform(' . json_encode($arrImg) . ')';
                    } else {
                        $arr_img['src'] = ZoeSrcImgMobile($arrImg, false);
                    }
                } else {
                    if (!isset($arr_img['src'])) {
                        $arr_img['src'] = $url;
                    }
                }
                return $arr_img;
            }
        }
        return ["src" => $url];
    } catch (\Exception $ex) {
        return ["src" => $url, 'error' => $ex->getMessage() . ' ' . $path, 'line' => $ex->getLine()];
    }
}

function ConvertBase64($url)
{
    if (!empty($url)) {
        if (substr($url, 0, 9) == '/storage/') {
            $path = storage_path('/app/public/' . substr($url, 9));
        } else {
            $path = public_path($url);
        }
        $imageData = base64_encode(file_get_contents($path));
        return 'data: ' . mime_content_type($path) . ';base64,' . $imageData;
    }
    return '';
}

function ZoeImageConvertBase64($expr)
{
    $url = "";
    if (is_array($expr) && isset($expr['data-src'])) {
        $url = $expr['data-src'];
    } else if (substr($expr, 0, 1) == '{') {
        $expr = json_decode($expr, true);
        if (isset($expr['data-src'])) {
            $url = $expr['data-src'];
        }
    } else {
        $url = $expr;
    }
    if (!empty($url)) {
        $src = ' data-src="' . $url . '" src="' . ConvertBase64($url) . '" ';
        return $src;
    }
    return '';
}

function ZoeSrcImgMobile($arr, $isSrc = true)
{
    $_platform = 'pc';
    $src = "";
    $detect = app()->getAgent();
    if ($detect->isTablet()) {
        $_platform = 'tablet';
    } else if ($detect->isMobile()) {
        $_platform = 'mobile';
    }
    if ($_platform === 'mobile') {
        if (isset($arr['data-mobile'])) {
            $src = $arr['data-mobile'];
        } else {
            $_platform = 'tablet';
        }
    }
    if ($_platform === 'tablet') {
        if (isset($arr['data-tablet'])) {
            $src = $arr['data-tablet'];
        } else {
            $_platform = 'pc';
        }
    }
    if ($_platform === 'pc') {
        if (isset($arr['data-src'])) {
            $src = $arr['data-src'];
        } else {
            $src = $arr['src'];
        }
    }
    return $isSrc ? ' src=' . $src . ' php=true ' : $src;
}

function ZoeSrcImg($src, $option = [])
{
    $html = '';
    if (is_array($src)) {
        foreach ($src as $k => $_src) {
            if ($k == 'blade') {
                $html .= ' ' . $_src . ' ';
            } else if ($k == 'data-srcset' && is_array($_src)) {
                $html .= ' ' . $k . ' = "' . implode('', $_src) . '" ';
            } else {
                $html .= ' ' . $k . ' ="' . $_src . '" ';
            }
        }
    } else {
        $html = 'src="' . $src . '"';
    }
    if (isset($option['istag']) && $option['istag']) {
        return '<img ' . $html . ' ' . (isset($option['attrs']) ? attrs($option['attrs']) : "") . ' />';
    } else {
        return $html;
    }
}

function ZoeAssetImg($url, $option = [])
{
    return defined('build') ?
        isset($option['image']['base64']) ? '@Zoe_ImageBase64(' . (is_array($url) ? json_encode($url) : $url) . ')' :
            is_array($url) ? ZoeSrcImg($url, $option) : ZoeSrcImg(($url), $option) : (is_array($url) ? ZoeSrcImg($url, $option) : ZoeSrcImg(($url), $option));
}

function _ZoeImage($url, $attrs = [], $action = true, $istag = false, $option = [])
{
    $is_base64 = 0;

    $option['action'] = $action;
    if (!isset($option['attrs'])) {
        $option['attrs'] = $attrs;
    }
    $option['istag'] = $istag;

    $resize_config = isset($option['image']) ? $option['image'] : [];
    if ($is_base64 == 3) {
        return defined('build') ? '@Zoe_ImageBase64(' . json_encode(ZoeImageResize($url, $resize_config)) . ')' : ZoeImageConvertBase64(ZoeImageResize($url, $resize_config));
    } else if ($is_base64 == 1 || isset($resize_config['base641'])) {
        return defined('build') ? '@Zoe_ImageBase64(' . $url . ')' : ZoeImageConvertBase64($url);
    } else if (isset($option['image']['resize']) && $option['image']['resize'] == 1) {
        return ZoeAssetImg(ZoeImageResize($url, $resize_config), $option);
    } else {
        return ZoeAssetImg($url, $option);
    }
}

function ZoeImage($url, $option = [], $action = true)
{
    return _ZoeImage($url, [], $action, false, $option);
}

function ZoeLang($text)
{
    global $zlang;
    $text = e(preg_replace('/\s+/', ' ', str_replace("\r\n", "", $text)));
    return defined('build') ? '@zlang("' . $text . '")' : $zlang($text);
}

function layout_data($id)
{
    $rs = DB::table('layout')->where('id', $id)->first();
    if ($rs) {
        return unserialize(base64_decode($rs->data));
    }
    return [];
}

function layout_get($id)
{
    $rs = DB::table('layout')->where('id', $id)->first();
    if ($rs) {
        $rs->data = unserialize(base64_decode($rs->data));
    }
    return $rs;
}

function sort_type($sort, $col = "", $parameter = [])
{

    if (isset($parameter['order_by']['col'])) {
        if ($parameter['order_by']['col'] != $col) {
            return '<i data-col="' . $col . '" class="fa fa-sort"></i>';
        }
        if (isset($parameter['order_by']['type'])) {
            // fa-sort
            if ($sort == "alpha") {
                // fa-sort-alpha-desc  fa-sort-alpha-asc
                return '<i data-col="' . $col . '" class="fa ' . ($parameter['order_by']['type'] == "asc" ? "fa-sort-alpha-asc\" data-type=\"asc\"" : "fa-sort-alpha-desc\" data-type='desc'") . '></i>';
            } else if ($sort == "amount") {
                //fa-sort-amount-desc fa-sort-amount-asc
                return '<i  data-col="' . $col . '" class="fa ' . ($parameter['order_by']['type'] == "asc" ? "fa-sort-amount-asc\" data-type='asc'" : "fa-sort-amount-desc\" data-type='desc'") . '></i>';
            } else if ($sort == "numeric") {
                // fa-sort-numeric-desc  fa-sort-numeric-asc
                return '<i data-col="' . $col . '" class="fa ' . ($parameter['order_by']['type'] == "asc" ? "fa-sort-numeric-asc\" data-type='asc'" : "fa-sort-numeric-desc\" data-type='desc'") . '></i>';
            } else {
                //fa-sort-desc  fa-sort-asc
                return '<i data-col="' . $col . '" class="fa ' . ($parameter['order_by']['type'] == "asc" ? "fa-sort-asc\" data-type='asc'" : "fa-sort-desc\" data-type='desc'") . '></i>';

            }
        }
        return '<i class="fa fa-sort"></i>';
    }
    return '';
}
function attr_row($type, $columns){
    $attrs = "";
    if(isset($columns['column'][$type])){
        foreach ($columns['column'][$type] as $name_attr => $value_attr) {
            $attrs .= " " . $name_attr . " ='" . $value_attr . "'";
        }
    }
    return $attrs;
}
function list_label($val, $columns, $option, $model = null)
{
    $label = $val;
    if (isset($columns['type'])) {
        if ($columns['type'] == "image") {
            $attrs = "";
            if (isset($option['config']['config']['type']['image'])) {
                foreach ($option['config']['config']['type']['image'] as $name_attr => $value_attr) {
                    $attrs .= " " . $name_attr . " ='" . $value_attr . "'";
                }
            }
            return '<img src="' . asset($label) . '" ' . $attrs . '>';
        } else if ($columns['type'] == "status") {
            if (isset($option['config']['config']['type'][$columns['type']])) {
                $data = $option['config']['config']['type'][$columns['type']];

                if (isset($data['label'][$label])) {
                    $label = z_language($data['label'][$label]);
                }
//                dump($data);
                if (isset($data['type']['name'])) {
                    if ($data['type']['name'] == 'label') {
                        if (isset($data['type']['color'][$val])) {
                            $label = '<span class="label label-' . $data['type']['color'][$val] . '">' . $label . '</span>';
                        } else {
                            $label = '<span class="label label-default">' . $label . '</span>';
                        }
                    }
                }
                if(isset($columns['onClick'])){
                    $label = '<div class="text-center"><a data-status="'.$model->status.'" data-id="'.$model->id.'" href="javascript:void(0);" onclick="'.$columns['onClick'].'">' . $label . '</a></div>';
                }else{
                    $label = '<div class="text-center">' . $label . '</div>';
                }

            }
        }
        if (isset($columns['align'])) {
            $label = '<div class="text-' . $columns['align'] . '">' . $label . '</div>';
        }
    }
    return '<div class="label-text">' . $label . '</div>';
}

function list_text_aligin($columns)
{
    if (!isset($columns['text-aligin'])) {
        return "";
    }
    if ($columns['text-aligin'] == "center") {
        return "text-center";
    } else if ($columns['text-aligin'] == "right") {
        return "text-right";
    }
    return "";
}

function render_attr($option,$model){

    $html = "";$tag = "";$attr = "";
    if(isset($option['attr'])){
        if($option['attr']['type'] == "link"){
            $tag = 'a';
        }else  if($option['attr']['type'] == "button"){
            $tag = 'button';
        }
        $attr.= isset($option['attr']['class'])?' class="'.$option['attr']['class'].'"':"";
        $attr.= isset($option['attr']['id'])?' id="'.$option['attr']['id'].'"':"";
        $attr.= isset($option['attr']['style'])?' style="'.$option['attr']['style'].'"':"";
    }
    if(isset($option['label'])){
        $html = $option['label'];
    }
    if(isset($option['router']['name'])){
        $par = isset($option['router']['par'])?$option['router']['par']:[];
        foreach ($par as $k=>$v){
            $par[$k] = $model->{$v};
        }
        $attr.=' href="'.route($option['router']['name'],$par).'"';
    }else{
        $attr.=' href="#"';
    }
    return "<".$tag.$attr.">".$html."</".$tag.">";
}
function config_get($type, $name, $default = [])
{
    $rs = DB::table('config')->where(['type' => $type, 'name' => $name])->first();
    if (!$rs) return $default;
    $rs = unserialize($rs->data);
    return isset($rs['data']) ? $rs['data'] : $default;
}

function config_set($type, $name, $data)
{
    return DB::table('config')->updateOrInsert(
        [
            'name' => $name,
            'type' => $type
        ],
        ['data' => serialize($data)]);
}

function config_delete($type, $name)
{
    return DB::table('config')->where(
        ['name' => $name, 'type' => $type]
    )->delete();
}

function get_category_type($type)
{
    $rs = DB::table('categories')->where(['type' => $type])->get();
    $arr = [];
    foreach ($rs as $k => $v) {
        if(!empty($v->data) && ($v->data == 'b:0;' || @unserialize($v->data) !== false)){
            $v->data = unserialize($v->data);
        }else{
            $v->data = [];
        }
        $arr[$v->id] = $v;
    }
    return $arr;
}

function get_menu_type($type)
{
    $rs = DB::table('menu')->where(['type' => $type])->get();
    $arr = [];
    foreach ($rs as $k => $v) {
        $arr[$v->id] = $v;
    }
    return $arr;
}

function show_categories_nestable($nestable, $category, $parent_id = 0, $char = '')
{
    $html = "";
    foreach ($nestable as $key => $item) {
        $html .= '<option ' . (isset($category[$item["id"]]) ? "selected " : "") . 'value="' . $item["id"] . '">';
        $html .= $char . $item['name'];
        $html .= '</option>';
        if (isset($item["children"])) {
            $html .= show_categories_nestable($item["children"], $category, $item['id'], $char . '|---');
        }
    }
    return $html;
}

function show_categories_ul_li($categories, $parent_id = 0, $char = '')
{
    // BƯỚC 2.1: LẤY DANH SÁCH CATE CON
    $cate_child = array();
    foreach ($categories as $key => $item) {
        // Nếu là chuyên mục con thì hiển thị
        if ($item->parent_id == $parent_id) {
            $cate_child[] = $item;
//            unset($categories[$key]);
        }
    }

    // BƯỚC 2.2: HIỂN THỊ DANH SÁCH CHUYÊN MỤC CON NẾU CÓ
    if ($cate_child) {
        if ($parent_id == 0)
            echo '<ol class="dd-list">';
        else
            echo '<ol class="dd-list">';
        foreach ($cate_child as $key => $item) {
            // Hiển thị tiêu đề chuyên mục
            echo '<li class="dd-item dd3-item" data-id="' . $item->id . '">';
            echo '<div class="dd-handle dd3-handle"></div>
		        <div class="dd3-content">' . $item->name . '</div>';
            echo "<div class='dd3-tool'><button class='btn btn-primary btn-xs edit'>" . "<i class='fa fa-edit'></i>" . "</button><button class='btn  btn-default btn-xs delete'>" . "<i class='fa fa-remove'></i>" . "</button></div>";
            show_categories_ul_li($categories, $item->id, $char . '|---');
            echo '</li>';
        }
        echo '</ol>';
    }
}

function views_alise($view, $key = "backend")
{
    $alias = app()->getConfig()['views']['alias'];
    if (isset($alias[$key][$view])) {
        return $alias[$key][$view];
    } else {
        return $view;
    }
}

function gen_uuid()
{
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),

        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,

        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}
function z_language($key, $par = [], $__env = null)
{
    if (is_array($par)) {

        $_lang_name_ = app()->getLocale();

        $_langs_ = app()->getLanguage();

        $html = isset($_langs_[$_lang_name_][$key]) && !empty($_langs_[$_lang_name_][$key]) ? $_langs_[$_lang_name_][$key] : $key;
        if (is_array($par)) {
            foreach ($par as $k => $v) {
                $html = str_replace(":" . $k, $v, $html);
            }
        }
        return $html;
    }
    return $key;
}
function acl_alias($key){
    return "Acl:".$key;
}
function find_acl($string_blade, $sub_path, $string_find = "z_language"){
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
function lang_all_key(){
    return Cache::remember('lang_all_key:static', 60, function()
    {
        $results = [];
        $results = get_dir_contents(base_path('core'), '/\.php$/', $results);
        $file = new \Illuminate\Filesystem\Filesystem();
        $array = [

        ];
        $system_modules = config('zoe.modules');
        $modules = DB::table('module')
            ->select()->where('status', 1)->pluck('name')->all();
        $plugins = config_get('plugin', 'lists');
        foreach ($results as $_file) {
            $name = str_replace(base_path(), "", $_file);
            $sub_path = explode(DIRECTORY_SEPARATOR, trim($name, DIRECTORY_SEPARATOR));
            if (count($sub_path) > 2) {
                if (
                    $sub_path[1] == "modules" && !in_array($sub_path[2], $system_modules) && !in_array($sub_path[2], $modules) ||
                    $sub_path[1] == "plugins" && !isset($plugins[$sub_path[2]])
                ) {
                    continue;
                }
            }
            $string_blade = $file->get($_file);
            $array = array_merge($array, find_acl($string_blade, $sub_path,"z_language"));
        }
        return $array;
    });
}
function acl_all_key(){
    return Cache::remember('acl_all_key:static', 60, function()
    {
        $results = [];
        $results = get_dir_contents(base_path('core'), '/\.php$/', $results);
        $file = new \Illuminate\Filesystem\Filesystem();
        $array = [

        ];
        $system_modules = config('zoe.modules');
        $modules = DB::table('module')
            ->select()->where('status', 1)->pluck('name')->all();
        $plugins = config_get('plugin', 'lists');
        foreach ($results as $_file) {
            $name = str_replace(base_path(), "", $_file);
            $sub_path = explode(DIRECTORY_SEPARATOR, trim($name, DIRECTORY_SEPARATOR));
            if (count($sub_path) > 2) {
                if (
                    $sub_path[1] == "modules" && !in_array($sub_path[2], $system_modules) && !in_array($sub_path[2], $modules) ||
                    $sub_path[1] == "plugins" && !isset($plugins[$sub_path[2]])
                ) {
                    continue;
                }
            }
            $string_blade = $file->get($_file);
            $array = array_merge($array, find_acl($string_blade, $sub_path,"acl_alias"));
        }
        return $array;
    });
}
function get_dir_contents($dir, $filter = '', &$results = array())
{
    $files = scandir($dir);

    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            if (empty($filter) || preg_match($filter, $path)) $results[] = $path;
        } elseif ($value != "." && $value != "..") {
            get_dir_contents($path, $filter, $results);
        }
    }
    return $results;
}
function get_config_component($id, $config = [])
{
    return [];
}

function run_component($function, $config = [])
{
    return call_user_func($function, [$config]);
}

function create_router_group()
{
    return [];
}

function create_router_item()
{
    return [];
}

function component_create($module, $main = [], $cfg = [], $opt = [], $type = "component")
{
    $stg = array(
        'system' => "",
        'module' => $module,
        'type' => $type,
    );
    if (!isset($cfg['public'])) {
        $cfg['public'] = "0";
    }
    if (!isset($cfg['dynamic'])) {
        $cfg['dynamic'] = "0";
    }

    if (!isset($cfg['render'])) {
        $cfg['render'] = "blade";
    }

    if (!isset($cfg['status'])) {
        $cfg['status'] = "1";
    }
    if (!isset($cfg['view'])) {
        $cfg['view'] = "";
    }
    if (is_null($module)) {
        unset($stg["module"]);
    }
    return [
        "main" => $main,
        "option" => array(
            'cfg' => $cfg,
            'stg' => array(
                'system' => "",
                'module' => $module,
                'type' => $type,
            ),
            'opt' => $opt
        )
    ];
}

function component_config($_opt_, $data, $config, $views, $cfg = [], $compiler = [])
{
    return [
        "data" => $data,
        "configs" => $config,
        "views" => $views,
        "cfg" => $cfg,
        "compiler" => $compiler
    ];
}

function component_config_data($data)
{
    return $data;
}

function component_config_configs($data)
{

    if (!isset($data['temp'])) {
        $data['temp'] = ["template" => "template", "data" => ["count" => 3]];
    }
    return $data;
}

function component_config_views($data)
{
    return $data;
}

function parseMultipleArgs($expression)
{
    return collect(explode(',', $expression))->map(function ($item) {
        return trim($item);
    });
}

/**
 * Strip quotes.
 *
 * @param  string $expression
 * @return string
 */
function stripQuotes($expression)
{
    return str_replace(["'", '"'], '', $expression);
}

function attrs($attrs)
{
    $html = " ";
    foreach ($attrs as $name => $value) {
        $html .= $name . '="' . $value . '"';
    }
    return $html;
}

function Blade_ImgZoeImage($expr, $isAction = true, $option = [])
{
    $expression = parseMultipleArgs($expr);
    $isAction = $isAction ? 'true' : 'false';
    $isTag = 'true';
    if ($expression->count() == 1) {
        $par = $expr . ',[],' . $isAction . ',' . $isTag . ',$config';
    } else {
        $par = $expr . ',' . $isAction . ',' . $isTag . ',$config';
    }
    return '<?php  echo _ZoeImage(' . $par . ') ?>';
}

function getDirContents($dir, $filter = '', &$results = array())
{
    $files = scandir($dir);

    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            if (empty($filter) || preg_match($filter, $path)) $results[] = $path;
        } elseif ($value != "." && $value != "..") {
            getDirContents($path, $filter, $results);
        }
    }
    return $results;
}

function extract_namespace($file)
{
    $ns = NULL;
    $handle = fopen($file, "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            if (strpos($line, 'namespace') === 0) {
                $parts = explode(' ', $line);
                $ns = rtrim(trim($parts[1]), ';');
                break;
            }
        }
        fclose($handle);
    }
    return $ns;
}
function saveFile($path, $contents, $lock = false){
    if(empty($contents)){
        return false;
    }
   return \File::put($path, $contents,$lock);
}
/*
$myArray = array(
    'key1' => 'value1',
    'key2' => array(
        'subkey' => 'subkeyval'
    ),
    'key3' => 'value3',
    'key4' => array(
        'subkey4' => array(
            'subsubkey4' => 'subsubkeyval4',
            'subsubkey5' => 'subsubkeyval5',
        ),
        'subkey5' => 'subkeyval5'
    ),
    'key5'=>[
        1,2,3,4,["abc"=>["def"=>"ghj"]]
    ]
);
*/
function convertArrayToDot($myArray = []){
    $ritit = new RecursiveIteratorIterator(new RecursiveArrayIterator($myArray));
    $result = array();
    foreach ($ritit as $leafValue) {
        $keys = array();
        foreach (range(0, $ritit->getDepth()) as $depth) {
            $keys[] = $ritit->getSubIterator($depth)->key();
        }
        $result[ join('.', $keys) ] = $leafValue;
    }
    return $result;
}
function convertDotToArray($array) {
    $newArray = array();
    foreach($array as $key => $value) {
        $dots = explode(".", $key);
        if(count($dots) > 1) {
            $last = &$newArray[ $dots[0] ];
            foreach($dots as $k => $dot) {
                if($k == 0) continue;
                $last = &$last[$dot];
            }
            $last = $value;
        } else {
            $newArray[$key] = $value;
        }
    }
    return $newArray;
}
function logs_sql(){
    $sqls = "";
    foreach (DB::getQueryLog() as $k=>$v){
        $sql = $v['query'];
        foreach ($v['bindings'] as $binding) {
            if (is_string($binding)) {
                $binding = "'{$binding}'";
            } elseif ($binding === null) {
                $binding = 'NULL';
            } elseif ($binding instanceof Carbon) {
                $binding = "'{$binding->toDateTimeString()}'";
            } elseif ($binding instanceof DateTime) {
                $binding = "'{$binding->format('Y-m-d H:i:s')}'";
            }
            $sql = preg_replace("/\?/", $binding, $sql, 1);

        }
        $sqls.= $sql."<BR>";
    }
    return $sqls;
}