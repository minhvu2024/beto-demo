<?php

namespace Admin\Lib;
class LayoutRender
{
    public static $datas = array();
    public static $widgets = array();

    public static function render($data, $widgets = array())
    {
        static::$datas = $data;
        static::$widgets = $widgets;
        // 87ebd29d-92da-ef9d-0bdc-8274c91bef7d
        $html = '';
        if (!is_array($data) || !count($data)) {
            return $html;
        }
        foreach (static::$datas as $rows) {
            if (isset($rows['row'])) {
                $html .= static::rows($rows['row']);
            }
        }
        echo $html;
    }

    public static function row($option, $preview = '', $row = '', $layout = true)
    {
        $html = '';
        $data_id = $layout ? ' data-id="' . (isset($option['cfg']['id']) ? $option['cfg']['id'] : "") . '"' : '';
        $draggable = $layout ? "ui-draggable" : '';
        $html .= '<div class="grid ' . $draggable . '" ' . $data_id . '>';
        $html .= '<div class="tool">';
        if (isset($option['stg']['status']) && $option['stg']['status']):
            $html .= '<span class="status btn btn-success btn-xs"><i class="fa fa-check-square"></i></span>';
        else:
            $html .= '<span class="status btn btn-success btn-xs"><i class="fa fa fa-square"></i></span>';
        endif;
        $html .= '
                <span class="remove btn btn-danger btn-xs"><i class="fa fa-times"></i></span>
                <span class="configuration btn btn-info btn-xs"><i class="fa fa-gear"></i></span>
                <span class="drag btn btn btn-primary btn-xs"><i class="fa fa-arrows"></i></span>
            </div>
            <div class="option">
                <div class="value">
                    <textarea>' . json_encode($option, JSON_FORCE_OBJECT) . '</textarea></div></div>
                    <div class="preview">
                        ' . $preview . '
                    </div>
                <div class="view">
                    <div class="row clearfix">' .
            $row . '
                    </div>
                </div>
                </div>';
        return $html;
    }

    public static function plugin($option, $layout = true)
    {

        $html = '';
        if (isset($option['stg']['module'])):
            $data_id = $layout ? ' data-id="' . (isset($option['cfg']['id']) ? $option['cfg']['id'] : "") . '"' : '';
            $draggable = $layout ? "ui-draggable" : '';
            $html .= '<div class="box-pluign ' . $draggable . '" ' . $data_id . '>';
            $html .= '<div class="tool">';
            if (isset($option['stg']['status']) && $option['stg']['status']):
                $html .= '<span class="status btn btn-success btn-xs"><i class="fa fa-check-square"></i></span>';
            else:
                $html .= '<span class="status btn btn-success btn-xs"><i class="fa fa fa-square"></i></span>';
            endif;
            $label = isset($option['stg']['plugin']) ? $option['stg']['plugin'] : (isset($option['stg']['name']) ? $option['stg']['name'] : "");
            $html .= '
                    <span class="remove btn btn-danger btn-xs"><i class="fa fa-times"></i></span>
                    <span class="configuration btn btn-info btn-xs"><i class="fa fa-gear"></i></span>
                    <span class="drag btn btn btn-primary btn-xs"><i class="fa fa-arrows"></i></span>
                </div><div class="option">
                    <div class="value"><textarea>' . json_encode(
                    $option
                ) . '</textarea></div>
                </div>
                <div class="preview">' . ((isset($option['opt']['title']) && !empty($option['opt']['title'])) ? $option['opt']['title'] : $label) . '</div>
                <div class="view">
                    <div class="plugin ' . ($option['stg']['type']) . '" data-module="' . $option['stg']['module'] . '" data-widget="' . $option['stg']['name'] . '" data-type="' . $option['stg']['type'] . '" data-system="' . (isset($option['stg']['system']) ? $option['stg']['system'] : 0) . '">' . ((isset($option['opt']['title']) && !empty($option['opt']['title'])) ? $option['opt']['title'] : $option['stg']['module'] . ":" . $label) . '</div>
                </div>
            </div>
       ';
        else:

        endif;
        return $html;
    }

    public static function partial($option, $layout = true)
    {
        $html = '';
        if (isset($option['stg']['module'])):
            $preview =
            $data_id = $layout ? ' data-id="' . (isset($option['cfg']['id']) ? $option['cfg']['id'] : "") . '"' : '';
            $draggable = $layout ? "ui-draggable" : '';
            $html .= '<div class="box-pluign ' . $draggable . '" ' . $data_id . '>';
            $html .= '<div class="tool">';
            if (isset($option['stg']['status']) && $option['stg']['status']):
                $html .= '<span class="status btn btn-success btn-xs"><i class="fa fa-check-square"></i></span>';
            else:
                $html .= '<span class="status btn btn-success btn-xs"><i class="fa fa fa-square"></i></span>';
            endif;
            $label = isset($option['stg']['plugin']) ? $option['stg']['plugin'] : (isset($option['stg']['name']) ? $option['stg']['name'] : "");
            $html .= '
                    <span class="remove btn btn-danger btn-xs"><i class="fa fa-times"></i></span>
                    <span class="configuration btn btn-info btn-xs"><i class="fa fa-gear"></i></span>
                    <span class="drag btn btn btn-primary btn-xs"><i class="fa fa-arrows"></i></span>
                </div><div class="option">
                    <div class="value"><textarea>' . json_encode(
                    $option
                ) . '</textarea></div>
                </div>
                <div class="preview">' . ((isset($option['opt']['title']) && !empty($option['opt']['title'])) ? $option['opt']['title'] : $label) . '</div>
                <div class="view">
                    <div class="plugin ' . ($option['stg']['type']) . '" data-module="' . $option['stg']['module'] . '" data-widget="' . $option['stg']['name'] . '" data-type="' . $option['stg']['type'] . '" data-system="' . (isset($option['stg']['system']) ? $option['stg']['system'] : 0) . '">' . ((isset($option['opt']['title']) && !empty($option['opt']['title'])) ? $option['opt']['title'] : $option['stg']['module'] . ":" . $label) . '</div>
                </div>
            </div>
       ';
        else:

        endif;
        return $html;
    }

    public static function rows($row, $layout = true)
    {
        $preview = '<span>';
        $rowHtml = '';
        $pace = false;
//        dump($row);
        foreach ($row['option']['stg']['col'] as $key => $gird) {
            $preview .= $gird . '-';
            $clazz = 'col-md-' . $gird;
            $_content = '';
            $pace = isset($row['option']['stg']['place'][$key]) ? $row['option']['stg']['place'][$key] : rand();
            if (isset($row['view'][$key]) && is_array($row['view'][$key])) {
                foreach ($row['view'][$key] as $_row) {
                    if (isset($_row[0]['row'])) {
                        $_content .= static::rows($_row[0]['row']);
                    } else {
                        if (isset(static::$widgets[$_row])) {

                            if (isset(static::$widgets[$_row]['stg']['type'])) {
                                if (static::$widgets[$_row]['stg']['type'] == "components") {
                                    $_content .= static::plugin(static::$widgets[$_row], true);
                                } else {
                                    $_content .= static::partial(static::$widgets[$_row], true);
                                }
                            }
                        }
                    }
                }
            }
            $rowHtml .= '<div class="' . $clazz . ' column" place="' . $pace . '">' . $_content . '</div>';
        }
        return static::row($row['option'], trim($preview,'-') . "</span>", $rowHtml, $layout);
    }

    public static function plugins($plugins)
    {
        return '';
    }
}