@php
    $optionGrid = [];
    $girds = array(
        array(
            'option'=>array(
                'cfg'=>array(
                    'compiler'=>[],
                    'tag'=>'none',
                    'status'=>1
                ),
                'stg'=>array(
                    'col'=>array('12'),
                    'type'=>'gird'
                ),
                'opt'=>$optionGrid
            )
        ),
        array(
            'option'=>array(
                'cfg'=>array(
                    'compiler'=>[],
                    'tag'=>'none',
                    'status'=>1
                ),
                'stg'=>array(
                    'col'=>array('6','6'),
                    'type'=>'gird'
                ),
                'opt'=>$optionGrid
            )
        ),
        array(
            'option'=>array(
                'cfg'=>array(
                     'compiler'=>[],
                     'tag'=>'none',
                     'status'=>1
                ),
                'stg'=>array(
                    'col'=>array('4','4','4'),
                    'type'=>'gird'
                ),
                'opt'=>$optionGrid
            )
        ),
        array(
            'option'=>array(
                'cfg'=>array(
                     'compiler'=>[],
                     'tag'=>'none',
                     'status'=>1
                ),
                'stg'=>array(
                    'col'=>array('9','3'),
                     'type'=>'gird'
                ),
                'opt'=>$optionGrid
            )
        )
    );
@endphp

@section('content-header')
    <h1>
        {!! @z_language(["Manager Layout"]) !!}
        <button onclick="SaveLayout(this)" url="{{route('backend:layout:ajax')}}" id="saveLayout" type="button"
                class="btn btn-default btn-md"> {!! @z_language(["Save"]) !!} </button>
    </h1>
@endsection

<div class="box box-solid box-primary">
    <div class="box-header">
        <h3 class="box-title"> {!! @z_language(["Layouts"]) !!}</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-xs-2">
                <div class="panel-group accordion sidebar-nav clearfix" id="accordion-grid">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="menu-item"
                                   data-toggle="collapse"
                                   data-parent="#accordion-grid"
                                   href="#menu-grid-accordion-grid">Grid</a>
                            </h4>
                        </div>
                        <div id="menu-grid-accordion-grid" class="panel-collapse collapse">
                            <div class="panel-body">
                                <?php  foreach ($girds as $gird): ?>
                                            <?php echo Admin\Lib\LayoutRender::rows($gird, false); ?>
                                        <?php
                                endforeach;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $components = app()->getComponents()->info;

                $components_config = app()->getComponents()->config;

                foreach (['layout' => 'Layout', 'theme' => 'Theme', 'widget' => 'Widget'] as $module=>$label):

                ?>
                <div class="panel-group accordion sidebar-nav clearfix" id="accordion-<?php echo $module; ?>">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="menu-item"
                                   data-toggle="collapse"
                                   data-parent="#accordion-<?php echo $module; ?>"
                                   href="#menu-accordion-<?php echo $module; ?>"><?php echo $label; ?></a>
                            </h4>
                        </div>
                        <div id="menu-accordion-<?php echo $module; ?>" class="panel-collapse collapse">
                            <?php
                            foreach ($components as $name => $component) {

                                $option = $component['option'];
                                $_cfg = new Zoe\Config($option["cfg"]);
                                $option["stg"]['name'] = $component['name'];
                                if (is_array($component['main'])) {
                                    $option["stg"]['main'] = $component['main'];
                                }
//                                $option["stg"]['system'] = $module;
                                if (isset($components_config[$name])) {
                                    //$option['cfg'] = array_merge();
                                    $_config = $components_config[$name]["configs"];
//                                    dump( $components_config[$name]);
                                    $_cfg->add($components_config[$name]["cfg"]);
                                    foreach ($_config as $__config) {
                                        $_prefix = "";
                                        if (isset($__config["prefix"])) {
                                            if (empty($__config["prefix"])) {
                                                $__data = $__config["data"];
                                            } else {
                                                $__data = [$__config["prefix"] => $__config["data"]];
                                            }
                                        } else {
                                            $__data = isset($__config["data"]) ? $__config["data"] : [];
                                        }
                                        $_cfg->add(["config" => $__data]);
                                    }
                                    if (isset($components_config[$name]["compiler"])) {
                                        $_cfg->add(["compiler" => $components_config[$name]["compiler"]]);
                                    }
                                    $_cfg->add(["data" => []]);
                                    $option["cfg"] = $_cfg->getArrayCopy();
                                    $option["opt"] = $components_config[$name]["data"];
                                }
                                if ($option['stg']["layout"] == $module) {

                                    echo \Admin\Lib\LayoutRender::plugin($option, false);
                                }

                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="col-xs-8 no-padding">
                <div class="col-xs-12" style="min-height: 500px;margin-top: 10px">
                    <div class="screen">
                        <div class="toolbar">
                            <div class="buttons clearfix">
                                <span class="left red"></span>
                                <span class="left yellow"></span>
                                <span class="left green"></span>
                            </div>
                        </div>
                        <div class="pull-right"
                             style="position: absolute;right: 20px;top: 6px;font-weight: bold;color: #000000;">

                            <button class="btn btn-xs"
                                    data-active="builder"
                                    data-source-label="{!! z_language("Source") !!}"
                                    data-builder-label="{!! z_language("Builder") !!}"
                                    type="button"
                                    onclick="SaveTogger(this)"
                            >{!! z_language("Builder") !!}</button>

                        </div>
                    </div>
                    <div style="margin-top: 7px;">
                        <form id="formInfo">
                            <input type="hidden" name="id" id="id" value="{{$model->id}}">
                            <input type="hidden" name="token" id="token" value="{{$model->token}}">
                            <table class="table table-bordered table-responsive">
                                <tr>
                                    <td class="text-center"><label for="layout name"
                                                                   class="control-label">Name</label>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="name">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"><label for="layout group"
                                                                   class="control-label">Group</label>
                                    </td>
                                    <td>
                                        @php $curent = "theme"; @endphp
                                        @foreach($group as $v=>$b)
                                            <input {{$curent == $v?"checked":""}} type="radio" name="type_group"
                                                   value="{{$v}}"> <strong> {{$b}} </strong>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"><label for="layout name" class="control-label">Type</label></td>
                                    <td>
                                        @php $curent = "layout"; @endphp
                                        @foreach($listsType as $v=>$b)
                                            <input {{$curent == $v?"checked":""}} type="radio" name="type"
                                                   value="{{$v}}"> <strong> {{$b}} </strong>
                                        @endforeach
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    <div class="builder">
                        <div id="layout" UriConfig="{{route('backend:layout:ajax:form_config')}}"
                             UriCom="{{route('backend:layout:ajax:get_com')}}">
                            <div class="demo" id="layout_demo" UrlSettingWidget="">
                                <?php echo \Admin\Lib\LayoutRender::render($content['data'], $content['widget']); ?>
                            </div>
                        </div>
                    </div>
                    <div class="source" style="display: none">
                        <div class="demo" style="overflow-y: scroll;padding: 2px 3px 0px;min-height: 550px">
                            <div id="snippet">
                                <pre>
                                    <code class="html">{{ $sources }}</code>
                                </pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-2">
                <?php
                $module = "Partial";
                ?>
                <div class="panel-group accordion sidebar-nav clearfix" id="accordion-<?php echo $module; ?>">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="menu-item"
                                   data-toggle="collapse"
                                   data-parent="#accordion-<?php echo $module; ?>"
                                   href="#menu-accordion-<?php echo $module; ?>"><?php echo $module; ?></a>
                            </h4>
                        </div>
                        <div id="menu-accordion-<?php echo $module; ?>" class="panel-collapse collapse">

                            <?php
                            foreach ($partials as $name => $partial) {
                                $option = $partial['option'];
                                $option["stg"]['name'] = $partial['name'];
                                echo \Admin\Lib\LayoutRender::plugin($option, false);
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <?php
                $module = "Component Cache";
                $key = md5($module);
                ?>
                <div class="panel-group accordion sidebar-nav clearfix" id="accordion-<?php echo $key; ?>">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="menu-item"
                                   data-toggle="collapse"
                                   data-parent="#accordion-<?php echo $key; ?>"
                                   href="#menu-accordion-<?php echo $key; ?>"><?php echo $module; ?></a>
                            </h4>
                        </div>
                        <div id="menu-accordion-<?php echo $key; ?>" class="panel-collapse collapse">
                            <?php
                            foreach ($db_components as $name => $partial) {
                                $option = $partial['option'];
                                echo \Admin\Lib\LayoutRender::plugin($option, true);
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .formSettingWidget, .formSettingGird {
        display: none;
    }

    .screen .start {
        position: absolute;
        width: 100%;
        left: 0px;
        top: 0px;
        z-index: 99;
    }

    .screen .start > span {
        display: block;
        background: #34495E;
        padding: 10px 20px;
        width: 450px;
        color: #fff;
        font-size: 13px;
        text-transform: uppercase;
        text-align: center;
        margin: 170px auto 0px;
    }

    .screen .toolbar {
        background: #3c8dbc;
        height: 35px;
        padding: 0px 0px 0px 11px;
        border-radius: 5px;
    }

    .screen .toolbar .buttons {
        float: left;
        height: 13px;
        margin-top: 11px;
    }

    .screen .toolbar .title {
        font-size: 14px;
        color: #000;
        text-align: center;
        margin-right: 80px;
        padding-top: 5px;
        font-weight: bold;
    }

    .screen .toolbar .left {
        display: block;
        width: 13px;
        height: 13px;
        float: left;
        border-radius: 13px;
        margin-right: 6px;
    }

    .screen .toolbar .left.red {
        background: #E74C3C;
    }

    .screen .toolbar .left.yellow {
        background: #F4A62A;
    }

    .screen .toolbar .left.green {
        background: #16A085;
    }

</style>
@push('scripts')
    <link rel="stylesheet"
          href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.10/styles/default.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.10/highlight.min.js"></script>

    <script src="{{asset('module/admin/bower_components/jquery-ui/jquery-ui.min.js')}}"></script>


    <script src="{{asset('module/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.2/codemirror.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.2/addon/mode/overlay.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.2/addon/runmode/runmode.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.2/mode/php/php.js"></script>

    <script src="{{asset("module/admin/assets/codemirror/mustache.js")}}"></script>
    <script src="{{asset("module/admin/assets/multi-select/js/jquery.multi-select.js")}}"></script>
    <script src="{{asset('module/admin/controller/layout/layout.js')}}"></script>
    <script>
        var editorSource;

        function SaveTogger(self) {
            var Jthis = $(self);
            var data = Jthis.data();
            if (data.active === "builder") {
                console.log(data.active);

                Jthis.text(data.sourceLabel);
                Jthis.data('active', 'source');

                $(".builder").hide();

                $(".source").show().find('.demo').height(($('.builder').height()*1.5) + "px");

//                var editorDom = document.getElementById('editorSource');
//                editorSource = CodeMirror.fromTextArea(editorDom, {
//                    lineNumbers: true,
//                    mode: "mustache"
//                });
//                editorSource.setSize("100%", "100%");
            } else {
                console.log(data.active);
                Jthis.data('active', 'builder');

                Jthis.text(data.builderLabel);
                $(".builder").show();
                $(".source").hide();
//                editorSource.toTextArea();
//                editorSource = null;
            }
        }

        $(document).ready(function () {
            $("#formInfo").zoe_inputs("set", @json($info));

        });
    </script>
@endpush
@push('links')
    <link rel="stylesheet" href="{{asset('module/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
    <link rel="stylesheet" href="{{asset('module/admin/controller/layout/style.css')}}">
    <link rel="stylesheet" href="{{asset("module/admin/assets/multi-select/css/multi-select.css")}}">
    <style>
        .CodeMirror {
            border: 1px solid black;
        }

        iframe {
            margin: 10px auto;
            display: block; /* iframes are inline by default */
            background: #ffffff;
            border: 1px solid #dedede; /* Reset default border */
            height: 50vh; /* Viewport-relative units */
            width: 100%;
        }</style>
@endpush