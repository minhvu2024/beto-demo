<?php

namespace Admin\Lib;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery\Exception;

class Database
{
    public static $pathSql = "";
    public static $pathModule = "";
    public static $path = "";

    public static function Init($pathModule)
    {
        if (!\File::exists($pathModule)) {
            \File::makeDirectory($pathModule);
        }
        $configs = ['folders' => []];
        if (\File::exists($pathModule . '/configs.json')) {
            $configs = json_decode(\File::get($pathModule . '/configs.json'), true);
        }

        $date = date('Y-m-d') . "-" . (date('H')) . "h";

        $configs['folder'] = $date;
        $configs['folders'][$date] = $date;

        $path = $pathModule . '/' . $date;

        static::$pathModule = $pathModule;
        static::$path = $path;
        static::$pathSql = $path . '/sql';

        if (!\File::exists($path)) {
            \File::makeDirectory($path);
        }
        if (!\File::exists(static::$pathSql)) {
            \File::makeDirectory(static::$pathSql);
        }
        return $configs;
    }

    public static function SaveFile($fileName, $data)
    {
        saveFile(static::$pathSql . '/' . $fileName, $data);
    }

    public static function lists_table()
    {
        $tables = [];
        $db_tables = DB::select('SHOW TABLES');
        foreach ($db_tables as $table) {
            foreach ($table as $_table) {
                $tables[$_table] = 1;
            }
        }
        return $tables;
    }

    public static function row($table, $datas, $cols = ['id'])
    {
        $StrValue = "(";
        $StrCol = "(";
        foreach ($datas as $k => $v) {
            foreach ($v as $key => $value) {
                if (!in_array($key, $cols)) {
                    $value = addslashes($value);
                    $value = str_replace("\n", "\\n", $value);
                    $StrValue .= '"' . $value . '",';
                    $StrCol .= "`" . $key . "`,";
                }
            }
        }
        $StrCol = trim($StrCol, ',');
        $StrValue = trim($StrValue, ',');
        $StrCol .= ")";
        $StrValue .= ");\n";
        return "INSERT INTO " . $table . $StrCol . " VALUES(" . $StrValue . ")";
    }

    public static function rows($rows, $cols = [], $table = "")
    {
        $StrCol = "";
        $isCol = false;
        $StrValue = "";
        foreach ($rows as $_rows) {
            foreach ($_rows as $row) {
                $_StrValue = "(";
                foreach ($row as $col => $value) {
                    if (!in_array($col, $cols)) {
                        $value = addslashes($value);
                        $value = str_replace("\n", "\\n", $value);
                        $_StrValue .= '"' . $value . '",';
                        if ($isCol == false) {
                            $StrCol .= "`" . $col . "`,";
                        }
                    }
                }
                $StrValue .= trim($_StrValue, ',');
                $StrValue .= "),";
                $isCol = true;
            }
        }
        $StrCol = trim($StrCol, ',');
        $StrValue = trim($StrValue, ',') . ';';
        if (empty($StrCol)) {
            return "";
        }
        return (!empty($table) ? "INSERT INTO " . $table . " " : "") . "(" . $StrCol . ") VALUES " . $StrValue;
    }

    public static function insert($table, $datas, $cols = ['id'])
    {
        $StrValue = "";
        $StrCol = "";
        $arrValue = [];
        foreach ($datas as $k => $v) {
            foreach ($v as $key => $value) {
                if (!in_array($key, $cols)) {
                    $StrValue .= '?,';
                    $StrCol .= "`" . $key . "`,";
                    $arrValue[] = $value;
                }
            }
        }
        $StrCol = trim($StrCol, ',');
        $StrValue = trim($StrValue, ',');
        $php = '<?php DB::insert("insert into `".DB::getTablePrefix()."' . $table . '` (' . $StrCol . ') values (' . $StrValue . ')", ' . var_export($arrValue, true) . '); ?>';
        return $php;
    }

    public static function createTable($datas)
    {
        if (is_array($datas)) {
            $lists_table = static::lists_table();
            foreach ($datas as $table => $sql) {
                if (!isset($lists_table[DB::getTablePrefix() . $table])) {
                    $action[$table] = str_replace("@TABLE@", ' IF NOT EXISTS `' . DB::getTablePrefix() . $table . '`', $sql);
                    DB::statement($action[$table]);
                }
            }
            return true;
        }
        return false;
    }

    public static function createFileTable($tables, $file = "create_table.php")
    {
        $sql = [];
        $errors = "";

        $lists_table = static::lists_table();
        $settings = [];
        foreach ($tables as $table) {
            $_table = DB::getTablePrefix() . $table;
            if (isset($lists_table[$_table])) {
                $rs = DB::select("SHOW CREATE TABLE " . $_table);
                foreach ($rs[0] as $key => $value) {
                    if ($key != "Table") {
                        $sql[$table] = str_replace('`' . $_table . '`', "@TABLE@", $value);
                    }
                }
            } else {
                $errors .= "<strong>" . $table . "</strong> not exits!<BR>";
            }
        }
        saveFile(static::$path . '/' . $file, '<?php return ' . var_export($sql, true) . ';');
        return [
            'errors' => $errors,
            'settings' => [
                'fileName' => $file,
                'Action' => __FUNCTION__
            ]
        ];
    }

    public static function createRowTable($tables, $configs = [], $settings = [])
    {
        $lists_table = static::lists_table();

        $data = [
            'errors' => '',
            'sqls' => [],
            'tables' => [],
            'logs' => [],
            "total_records" => [],
            'settings' => [
                "Action" => __FUNCTION__,
                "Lists" => []
            ]
        ];
        $itemCount = 0;
        foreach ($tables as $table => $callback) {
            if (is_numeric($table)) {
                $table = $callback;
            }
            $_table = DB::getTablePrefix() . $table;

            if (isset($lists_table[$_table])) {

                if (isset($configs['tables'][$table])) {
                    $current_page = (int)$configs['tables'][$table];
                } else {
                    $current_page = 1;
                }

                if ($current_page > 0) {
                    $limit = isset($settings['item']) ? (int)$settings['item'] : 5000;

                    if (is_callable($callback)) {
                        $db = $callback(DB::table($table));
                    } else {
                        $db = DB::table($table);
                    }

                    if (isset($configs['total_pages'][$table])) {
                        $total_records = $configs['total_records'][$table];
                    } else {

                        $total_records = $db->count();
                        $data['total_records'][$table] = $total_records;
                    }

                    $total_page = ceil($total_records / $limit);
                    $start = ($current_page - 1) * $limit;
                    $rs = $db->skip($start)->take($limit)->get();

                    $data['sqls'][$table] = "REPLACE";

                    if (!\File::exists(static::$pathSql . '/' . $table)) {
                        \File::makeDirectory(static::$pathSql . '/' . $table);
                    }

                    saveFile(static::$pathSql . '/' . $table . '/' . $table . '-' . $current_page . '.sql', \Admin\Lib\Database::rows([$rs]));
                    if ($current_page <= $total_page) {
                        $data['tables'][$table] = $current_page + 1;
                        $itemCount++;
                    } else {
                        $data['tables'][$table] = 0;

                        saveFile(static::$pathSql . '/' . $table . '/config.json', json_encode([
                            'setting' => $settings,
                            'config' => [
                                'total_records' => $total_records,
                                'limit' => $limit,
                                'type' => isset($settings['type']) ? $settings['type'] : "REPLACE",
                            ],
                            'time' => date('Y-m-d H:i:s')
                        ]));
                        $data['settings']["Lists"][$table] = 1;
                    }
                    $data['logs'][$table] = [
                        'total_page' => $total_page,
                        'current_page' => $current_page
                    ];
                } else {
                    $data['tables'][$table] = 0;
                }
            } else {
                $data['errors'] .= "table " . $table . ' not exits!';
                break;
            }
        }
        $data['step'] = $itemCount == 0 ? 1 : 0;

        return $data;
    }

    public
    static function addFileRow($fileSql, $table, $typeInsert = "REPLACE")
    {
        if (\File::exists($fileSql)) {
            if ($typeInsert == 'REPLACE') {
                $typeInsert = "REPLACE INTO ";
            } else {
                $typeInsert = "INSERT INTO ";
            }
            $_table = DB::getTablePrefix() . $table;
            return DB::statement($typeInsert . $_table . \File::get($fileSql));
        }
    }

    public static function addFileRows($path, $tables, $configs = [], $settings = [])
    {
        $lists_table = static::lists_table();
        $data = ['errors' => '', 'sqls' => [], 'tables' => [], 'logs' => [], "total_records" => []];
        $itemCount = 0;
        foreach ($tables as $table) {
            $_table = DB::getTablePrefix() . $table;

            if (isset($lists_table[$_table])) {

                $FileConfigs = $path . '/' . $table . '/config.json';
                if (isset($configs['tables'][$table])) {
                    $current_page = (int)$configs['tables'][$table];
                } else {
                    $current_page = 1;
                }

                if ($current_page > 0) {
                    $configData = [];
                    if (!isset($configs['tables'][$table]['configs'])) {
                        if (\File::exists($FileConfigs)) {
                            $data['tables'][$table]['configs'] = json_decode(\File::get($FileConfigs), true);
                            $configData = $data['tables'][$table]['configs'];
                        }
                    } else {
                        $configData = $configs['tables'][$table]['configs'];
                    }
                    $limit = isset($configData["config"]['limit']) ? (int)$configData["config"]['limit'] : 1;
                    $type = isset($configData["config"]['type']) ? (int)$configData["config"]['type'] : "REPLACE";
                    if (isset($configData['config']['total_records'])) {
                        $total_records = $configData['config']['total_records'];
                        $total_page = ceil($total_records / $limit);
                        if ($current_page <= $total_page) {
                            $data['sqls'][] = $path . '/' . $table . '/' . $table . '-' . $current_page . '.sql';
                            if (\File::exists($path . '/' . $table . '/' . $table . '-' . $current_page . '.sql')) {
                                static::addFileRow($path . '/' . $table . '/' . $table . '-' . $current_page . '.sql', $table, $type);
                            }
                            $data['tables'][$table] = $current_page + 1;
                            $itemCount++;
                        } else {
                            $data['tables'][$table] = 0;
                        }
                        $data['logs'][$table] = [
                            'total_page' => $total_page,
                            'current_page' => $current_page
                        ];
                    } else {
                        $data['errors'] .= "total_records exits!";
                    }
                } else {
                    $data['tables'][$table] = 0;
                }
            } else {
                $data['errors'] .= "table " . $table . ' not exits!';
                break;
            }
        }
        $data['step'] = $itemCount == 0 ? 1 : 0;

        return $data;
    }

    static function dropIfExists($tables)
    {
        $lists_table = static::lists_table();

        try {
            foreach ($tables as $table) {
                $_table = DB::getTablePrefix() . $table;

                if (isset($lists_table[$_table])) {
                    DB::statement("DROP TABLE `" . $_table . "`;");
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

    }

    static function SaveRows($lists)
    {
        $setting = [
            "Action" => __FUNCTION__,
            "Lists" => []
        ];
        foreach ($lists as $filename => $config) {
            if (isset($config['table'])) {
                $db = DB::table($config['table']);
                $rows = [];
                if (isset($config['lists'])) {
                    if (is_callable($config['lists'])) {
                        $rows[] = $config['lists']($db);
                    } else if (is_array($config['lists'])) {
                        foreach ($config['lists'] as $callback) {
                            $rows[] = $callback($db);
                        }
                    }
                }
                if (count($rows) > 0) {
                    $setting["Lists"][$filename] = $filename . '.sql';
                    static::SaveFile($filename . '.sql', static::rows($rows));
                }
            }
        }
        return $setting;
    }

}