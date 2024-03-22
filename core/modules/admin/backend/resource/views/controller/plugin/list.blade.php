@section('content-header')
    <h1>
        &starf; {!! @z_language(["Manager Plugins"]) !!}
        <small>it all starts here</small>
    </h1>
@endsection
@section('content')
    @breadcrumb()@endbreadcrumb
    <div class="plugins">
        @foreach($lists as $plugin=>$list)
            <div class="item col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="box {!! $plugin !!}">
                    <div class="box-header with-border">
                        <i class="fa fa-bookmark" @if(isset($lists_install[$plugin])) style="color: green" @endif></i>
                        <h3 class="box-title">{!! $plugin !!}</h3>
                        <div class="pull-right"> {!! $list['author'] !!}</div>
                    </div>
                    <div class="box-body">
                        <div class="icon col-lg-4">
                            <div style="position: relative">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAACWCAMAAAAL34HQAAAAAXNSR0IB2cksfwAAAAlwSFlzAAAuIwAALiMBeKU/dgAAAEtQTFRFMjIypqamfn5+cnJy////5eXlZWVlPz8/8vLys7OzWVlZpaWlzMzM2NjYi4uLTExMmZmZ2dnZmJiY8/PzjIyMf39/v7+/5ubmsrKykB+XXAAAAwhJREFUeJztmNGCmyAQRaUSEZYUXEXz/19aZka0Nd3Y3eomD/c8JAtBuc5cBtyqAgAAAAAAAAAAAAAAAAAAAAAAAAAAr4z68WwFf6PW+tI8W8QdptVa2+dqcD/evL/oq/8ZSlfMqjrzTE1vF73yLp0h/9m7xxeemWLnF0U2xui14m6b28PjK4O+1KfJGkhQ39EnByfxFwXL/8OV6ixZptf9YOzqb0tTxaLyAeN+mv+DWmVjT3mK2e3RyzLcXYX+TFWMy8GJ1Sor5XZ4eEVG6XSuKjJ4X4rBlJPYfRysZolQzUPCeUWEclbMa7On3Bwsc9WeVCxKGv9bWJnQXk/TteYseD1SeuhTTG1Iacu1woy0XkepG07UjGXF1sfvoHPOTH0THb3WVJGoAtS8OXKEcuxy4Q8myJO05KyGhNJ3rsjXg1XRvW83T7PyfpN1TJWkliIxiVZWZSl2I+UuNxpajdyXyqVHYteth4oFtUvtakQ0BWtWEPhHK+WWfsuFhW/QH6zKkBy6sR9Cabs1WGQoIxnt2Ghkw17caFmyPNbOVvVpIk1vmuVh5xwOa7CsiKSC65YoRXmAKWdwjHLJoWTv/FEX5xzm7q7i5UBxme1PzST5LZ0px2l7iwPIdu23Mhsp/IlrBaeyky8rg0epINTp8opsJM+H4jfngCAzDzxVXVwjfkt6iWSSY0Zq1VrnDsRtzwpJ5hgph6FV4rCKM5rK8UfWHTWvVp7saMNbsdBKXOqBqjrl5vz0uXPoZ781Un5tKVZepB9I2G5yJIvmyNFSt45LAc2s9PvYuTmjTuzvy2kojx3c7onjM6i7894gHa6XTLmJJRjfR3YaW7vjIV7UyfbUHxqvYbsOKX7yrUbRm5a1b8t5x3CA47JWQkoHr0R1t4iS/mCo0RsPnXkMbO6i/9FROO6/djwB2n/OewP7Mq8ZLLd11ktgLie+rH4ZOpt2+8O+D05c3a7vt6/BcHkf6V8608mv0J+loWPzFPcHfjsvlT4AAAAAAAAAAAAAAAAAAAAAAAAAgNfgF3lnHa/qgc/3AAAAAElFTkSuQmCC"
                                     class="img-thumbnail img-responsive img-rounded" alt="Responsive Image"/>
                                <span style="position: absolute;bottom: 5px;right: 13%;;color: #ffffff">v<strong>{!! $list['version'] !!}</strong></span>
                            </div>
                        </div>
                        <div class="info col-lg-8" style="position: relative">
                            <div class="description">
                                {!! $list['description'] !!}
                            </div>
                            @if(count($list['require']))
                                <div class="plugins">
                                    @php $i = 0 @endphp
                                    @foreach($list['require'] as $_plugin=>$require)
                                        <div>
                                            <td>
                                                <strong>{{ ++$i }}</strong> {!! $require==1?'<span class="plugin label label-success plu_'.$_plugin.'" data-plugin="'.$_plugin.'" data-status="1">'.$_plugin.'</span>':($require==2?'<span class="plugin label label-default" data-plugin="'.$_plugin.'" data-status="2">'.$_plugin.'</span>':'<span class="plugin label label-warning" data-plugin="'.$_plugin.'" data-status="0">'.$_plugin.'</span>') !!}
                                            </td>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="actions col-lg-12 text-right">
                            <div class="app-uninstall" @if(!isset($lists_install[$plugin])) style="display:none" @endif>
                                <a href="javascript:void(0);" class="btn btn-xs bg-orange btnAction pull-left"
                                   data-act="export"
                                   step="0"
                                   data-plugin="{!! $plugin !!}">
                                    <i class="fa fa-cloud-download"></i> {!! z_language('Export') !!}
                                    (<strong>0</strong>)
                                </a>
                                <a href="javascript:void(0);" class="btn btn-xs bg-navy btnAction pull-left"
                                   data-act="import"
                                   step="0"
                                   data-plugin="{!! $plugin !!}">
                                    <i class="fa fa-cloud-upload"></i> {!! z_language('Import') !!} (<strong>0</strong>)
                                </a>
                                <a href="javascript:void(0);" class="btn btn-xs btn-danger btnAction pull-right"
                                   data-act="uninstall"
                                   data-plugin="{!! $plugin !!}">
                                    <i class="fa fa-remove"></i> {!! z_language('UnInstall') !!}
                                </a>
                            </div>
                            <div class="app-install" @if(isset($lists_install[$plugin])) style="display:none" @endif>
                                <a href="javascript:void(0);" class="btn bg-orange btn-xs  btnAction" data-act="remove"
                                   data-plugin="{!! $plugin !!}"> <i
                                            class="fa fa-remove"></i> {!! z_language('Remove') !!}
                                </a>
                                <a href="javascript:void(0);" class="btn bg-navy btn-xs btnAction" data-act="install"
                                   data-plugin="{!! $plugin !!}"> <i
                                            class="fa fa-cogs"></i> {!! z_language('Install') !!}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="modules">

        @foreach($modules_install as $mod=>$item)
            @if(isset($item->data['require']))
                @foreach($item->data['require'] as $plugin)
                    <div class="mod_{!! $plugin !!}" data-module="{!! $mod !!}"></div>
                @endforeach
            @endif
        @endforeach
    </div>
@endsection
@push('links')
    <style>
        .item .box {
            border-top: 3px solid #0c0c0c;
            border-radius: 0px;
        }

        .item .box-title {
            color: #001f3f;
            font-weight: bold;
        }

        .item .icon {
            padding: 0;
        }

        .item .actions {
            padding: 0;
        }

        .actions .btn {
            margin-left: 5px;
        }
    </style>
@endpush
@push('scripts')
    <script>

        $(document).ready(function () {
            $(".btnAction").click(function () {
                var self = this;
                var box = $(this).closest('.box');
                if ($(self).attr('process') === 1) {
                    return;
                }

                var plugin = box.find('.plugin');
                console.log(plugin);
                var data = $(this).data();
                var error = [];

                if ($(this).attr('step')) {
                    data.step = parseInt($(this).attr('step'));
                }
                if (data.act === "uninstall") {
                    var listPlugin = $(".plu_" + data.plugin);
                    var msg = "";
                    $.each(listPlugin, function () {
                        var app_uninstall = $(this).closest('.box').find('.app-uninstall a');

                        if (app_uninstall.is(":visible")) {
                            msg = "<div>{!! z_language('Plugin :plugin use in :pluginUse !'); !!}</div>";
                            msg = msg.replace(":plugin", "<strong>" + data.plugin + "</strong>");
                            msg = msg.replace(":pluginUse", "<strong>" + app_uninstall.data().plugin + "</strong>");
                        }

                        if (msg.length > 0) {
                            error.push(msg);
                        }
                    });

                    var listPluginMod = $(".mod_" + data.plugin);
                    var msg = "";
                    $.each(listPluginMod, function () {
                        let _data = $(this).data();
                        console.log(_data);
                        msg = "<div>{!! z_language('Plugin :plugin use in module :modUse !'); !!}</div>";
                        msg = msg.replace(":plugin", "<strong>" + data.plugin + "</strong>");
                        msg = msg.replace(":modUse", "<strong>" + _data.module + "</strong>");
                        if (msg.length > 0) {
                            error.push(msg);
                        }
                    });
                } else {
                    $.each(plugin, function () {
                        let data = $(this).data();

                        var msg = "";
                        var _plugin = $("." + data.plugin);
                        console.log(_plugin);
                        if (_plugin.length === 0) {
                            msg = "{!! z_language('Plugin :plugin not exits!'); !!}";
                        } else if (_plugin.find('.app-uninstall').is(":visible") === false) {
                            msg = "{!! z_language('Plugin :plugin not install!'); !!}";
                        }
                        if (msg.length > 0) {
                            error.push(msg.replace(":plugin", "<strong>" + data.plugin + "</strong>"));
                        }
                    });
                }

                if (error.length === 0) {
                    $(self).attr('process', 1);
                    box.find('.icon').loading({circles: 3, overlay: true, width: "5em", top: "13%", left: "11%"});
                    var status = true;
                    ajax("{!! route('backend:plugin:ajax') !!}", function (json) {
                        box.find('.icon').loading({destroy: true});
                        if (json.status === true) {
                            if ((data.act === "uninstall") === status) {
                                box.find('.app-install').fadeIn(3000);
                                box.find('.app-uninstall').hide();
                            } else if ($(self).attr("step")) {
                                $(self).attr("step", 0);
                                $(self).find('strong').html(0);
                            } else {
                                box.find('.app-install').hide();
                                box.find('.app-uninstall').fadeIn(3000);
                            }
                        } else {
                            if (data.hasOwnProperty('step')) {
                                if (json.status.hasOwnProperty) {
                                    if (json.status.hasOwnProperty('error')) {
                                        $(self).notify(json.status.error, {position: "left"});
                                    } else {
                                        $(self).attr("step", data.step + 1);
                                        $(self).data("data", json.status);
                                        $(self).find('strong').html(data.step + 1);
                                        $(self).trigger('click');
                                    }
                                }
                            } else {
                                $(self).notify(json.status, {position: "left"});
                            }
                        }
                        $(self).attr('process', 0);
                    }, data);

                } else {

                    $(self).notify(error.join("\n"), {
                        position: "left",
                        style: 'htmlContent',
                    }, "error");

                }
            });

        });
    </script>
@endpush