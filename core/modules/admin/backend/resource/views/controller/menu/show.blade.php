@section('content-header')
    <h1>
        &starf; {!! @z_language(["Manager Menu"]) !!}
        <small>it all starts here</small>
        <a href="#" id="btnCreate"
           class="btn btn-default btn-md"><i class="fa fa-fw fa-plus"></i> {!! @z_language(["Add New"]) !!} </a>
    </h1>
@endsection
@section('content')
    @breadcrumb()@endbreadcrumb
    <!-- Default box -->
    <div class="col-md-6">
        <div class="box box-zoe">
            <div class="box-header with-border">
                <h3 class="box-title">{!! @z_language(["Menu List"]) !!}</h3>

            </div>
            <div class="box-body">
                <menu id="nestable-menu">
                    <button class="btn btn-default" type="button" data-action="expand-all">Expand All</button> &nbsp;
                    <button class="btn btn-default" type="button" data-action="collapse-all">Collapse All</button>
                </menu>
                <div class="cf nestable-lists">
                    <div class="dd" id="nestable">
                        {!! $nestable  !!}
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button class="btn btn-default" id="btnSavePosition"><i class="fa fa-plus"></i> Save</button>
            </div>
        </div>
    </div>



    <div class="col-md-6">

        <div class="box box-zoe">
            <div class="box-header with-border">
                <h3 class="box-title" id="form-title">{{ z_language('Menu Create')}}</h3>

            </div>
            <div class="box-body">
                {!! Form::open(['method' => 'POST','id'=>'form_store']) !!}
                {!! Form::hidden('type',$type) !!}
                {!! Form::hidden('id',0) !!}
                <table class="table table-borderless">
                    <tbody>
                    <tr>
                        <td>
                            {!! Form::label('name', z_language('Name'), ['class' => 'name']) !!}
                            {!! Form::text('name',null, ['class' => 'form-control','placeholder'=>z_language('Name')]) !!}
                            <span class="error help-block"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {!! Form::label('Content Type', z_language('Content Type'), ['class' => 'name']) !!}
                            {!! Form::select('type',
                                array(
                                     '' => z_language('Select Content Type'),
                                     'page' => z_language('Content Page'),
                                     'router' => z_language('Router'),
                                     'link' => z_language('External Link'),
                                 ),
                                 '',['class'=>'form-control'])
                            !!}
                            <span class="error help-block"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {!! Form::label('Page', z_language('Page'), ['class' => 'name']) !!}
                            <select class="selectChange form-control">
                                @foreach($pages as $page)
                                    <option value="{!! $page['slug'] !!}">{!! $page['title'] !!}</option>
                                @endforeach
                            </select>
                            <span class="error help-block"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {!! Form::label('name', z_language('External Link'), ['class' => 'name']) !!}
                            {!! Form::text('link',null, ['class' => 'form-control','placeholder'=>z_language('External Link')]) !!}
                            <span class="error help-block"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {!! Form::label('name', z_language('Router'), ['class' => 'name']) !!}
                            @php
                                $routes = collect(\Route::getRoutes())->map(function ($route) { return [
                                    'uri'=> $route->uri(),
                                    'name'=> $route->getName(),
                                    'method'=> $route->methods
                                    ];
                                });

                                $type = 'frontend';
                            @endphp
                            <div class="input-group">
                                <select class="selectChange form-control">
                                    @foreach($routes as $route)
                                        @php
                                            $arr_name =  explode(':',$route['name']);
                                        @endphp
                                        @continue($type!=$arr_name[0] && $arr_name[0]!="login" && $arr_name[0]!="register")

                                        @continue(!in_array("GET",$route['method']))
                                        <option value="{!! $route['name'] !!}" data-uri="{!! $route['uri'] !!}">
                                            @php
                                                echo $route['uri'];
                                            @endphp
                                        </option>
                                    @endforeach
                                </select>
                                <span class="input-group-addon">
                                     <button type="button" class="btn btn-info btn-xs pull-right"
                                             onclick="ChangePar(this);">Param
                                    </button>
                                </span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {!! Form::label('description', z_language('Description'), ['class' => 'description']) !!}
                            {!! Form::textarea('description',null, ['class' => 'form-control','placeholder'=>'Mô tả','cols'=>5,'rows'=>5]) !!}
                            <span class="error help-block"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {!! Form::label('id_status', 'Status', ['class' => 'status']) !!}
                            {!! Form::radio('status', '1' , true) !!} Yes
                            {!! Form::radio('status', '0',false) !!} No
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {!! Form::label('featured', 'Featured', ['class' => 'featured']) !!}
                            {!! Form::radio('featured', '1' , false) !!} Yes
                            {!! Form::radio('featured', '0',true) !!} No

                        </td>
                    </tr>
                    <tr>
                        <td>
                            @includeIf($views)
                        </td>
                    </tr>
                    </tbody>
                </table>
                {!! Form::close() !!}
            </div>
            <div class="box-footer">
                <button type="button" id="btnSave" class="btn btn-default ">Save</button>
            </div>
        </div>
    </div>
@endsection
@push("links")
    <style>
        .error {
            display: none;
        }

        .has-error .error {
            display: inline-block;
        }

        .cf:after {
            visibility: hidden;
            display: block;
            font-size: 0;
            content: " ";
            clear: both;
            height: 0;
        }

        * html .cf {
            zoom: 1;
        }

        *:first-child + html .cf {
            zoom: 1;
        }

        /*html { margin: 0; padding: 0; }*/
        /*body { font-size: 100%; margin: 0; padding: 1.75em; font-family: 'Helvetica Neue', Arial, sans-serif; }*/

        /*h1 { font-size: 1.75em; margin: 0 0 0.6em 0; }*/

        /*a { color: #2996cc; }*/
        /*a:hover { text-decoration: none; }*/

        p {
            line-height: 1.5em;
        }

        .small {
            color: #666;
            font-size: 0.875em;
        }

        .large {
            font-size: 1.25em;
        }

        /**
         * Nestable
         */

        .dd {
            position: relative;
            display: block;
            margin: 0;
            padding: 0;
            list-style: none;
            font-size: 13px;
            line-height: 20px;
        }

        .dd-list {
            display: block;
            position: relative;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .dd-list .dd-list {
            padding-left: 30px;
        }

        .dd-collapsed .dd-list {
            display: none;
        }

        .dd-item,
        .dd-empty,
        .dd-placeholder {
            display: block;
            position: relative;
            margin: 0;
            padding: 0;
            min-height: 20px;
            font-size: 13px;
            line-height: 20px;
        }

        .dd-handle {
            display: block;
            height: 30px;
            margin: 5px 0;
            padding: 5px 10px;
            color: #333;
            text-decoration: none;
            font-weight: bold;
            border: 1px solid #ccc;
            background: #fafafa;
            background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
            background: -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
            background: linear-gradient(top, #fafafa 0%, #eee 100%);
            -webkit-border-radius: 3px;
            border-radius: 3px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .dd-handle:hover {
            color: #2ea8e5;
            background: #fff;
        }

        .dd-item > button {
            display: block;
            position: relative;
            cursor: pointer;
            float: left;
            width: 25px;
            height: 20px;
            margin: 5px 0;
            padding: 0;
            text-indent: 100%;
            white-space: nowrap;
            overflow: hidden;
            border: 0;
            background: transparent;
            font-size: 12px;
            line-height: 1;
            text-align: center;
            font-weight: bold;
        }

        .dd-item > button:before {
            content: '+';
            display: block;
            position: absolute;
            width: 100%;
            text-align: center;
            text-indent: 0;
            top: 20%;
        }

        .dd-item > button[data-action="collapse"]:before {
            content: '-';
        }

        .dd-placeholder,
        .dd-empty {
            margin: 5px 0;
            padding: 0;
            min-height: 30px;
            background: #f2fbff;
            border: 1px dashed #b6bcbf;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .dd-empty {
            border: 1px dashed #bbb;
            min-height: 100px;
            background-color: #e5e5e5;
            background-image: -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
            -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
            background-image: -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
            -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
            background-image: linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
            linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
            background-size: 60px 60px;
            background-position: 0 0, 30px 30px;
        }

        .dd-dragel {
            position: absolute;
            pointer-events: none;
            z-index: 9999;
        }

        .dd-dragel > .dd-item .dd-handle {
            margin-top: 0;
        }

        .dd-dragel .dd-handle {
            -webkit-box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, .1);
            box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, .1);
        }

        .nestable-lists {
            display: block;
            clear: both;
            padding: 15px 0;
            width: 100%;
            border: 0;
        }

        #nestable-menu {
            padding: 0;
            margin: 20px 0;
        }

        .dd-hover > .dd-handle {
            background: #2ea8e5 !important;
        }

        .dd3-content {
            display: block;
            height: 30px;
            margin: 5px 0;
            padding: 5px 10px 5px 40px;
            color: #333;
            text-decoration: none;
            font-weight: bold;
            border: 1px solid #ccc;
            background: #fafafa;
            background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
            background: -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
            background: linear-gradient(top, #fafafa 0%, #eee 100%);
            -webkit-border-radius: 3px;
            border-radius: 3px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .dd3-content:hover {
            color: #2ea8e5;
            background: #fff;
        }

        .dd-dragel > .dd3-item > .dd3-content {
            margin: 0;
        }

        .dd3-item > button {
            margin-left: 30px;
        }

        .dd3-handle {
            position: absolute;
            margin: 0;
            left: 0;
            top: 0;
            cursor: pointer;
            width: 30px;
            text-indent: 100%;
            white-space: nowrap;
            overflow: hidden;
            /*border: 1px solid #aaa;*/
            background: #444;
            /*background: -webkit-linear-gradient(top, #ddd 0%, #bbb 100%);*/
            /*background: -moz-linear-gradient(top, #ddd 0%, #bbb 100%);*/
            /*background: linear-gradient(top, #ddd 0%, #bbb 100%);*/
            /*border-top-right-radius: 0;*/
            /*border-bottom-right-radius: 0;*/
        }

        .dd3-handle:before {
            content: '≡';
            display: block;
            position: absolute;
            left: 0;
            width: 100%;
            text-align: center;
            text-indent: 0;
            color: #fff;
            font-size: 20px;
            font-weight: normal;
            top: 46%;
            transform: translateY(-60%);

        }

        .dd3-handle:hover {
            background: #444;
            color: red;
        }

        .dd3-handle {

            position: absolute;
            margin: 0;
            left: 0;
            top: 0;
            cursor: move;
            width: 2em;
            height: 100%;
            white-space: nowrap;
            overflow: hidden;

            line-height: 1;
        }

        .dd3-tool {
            position: absolute;
            right: 4px;
            width: 5em;
            top: 4px;
        }

        .dd3-tool .btn {
            margin-left: 5px;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{asset("http://wojoscripts.com/cmspro/assets/nestable.js")}}"></script>
    <script>

        $(document).ready(function () {
            function SavePosition(id, cb) {
                var e = $('#nestable').data('output', $('#nestable-output'));
                var list = e.length ? e : $(e.target), output = list.data('output');
                $("#nestable").loading({circles: 3, overlay: true, width: "5em", top: "20%", left: "50%"});
                $.ajax({
                    url: '{{@route('backend:menu:ajax')}}',
                    type: "POST",
                    data: {act: "position", data: {id: id, pos: list.nestable('serialize'), type: '{!! $type !!}'}},
                    success: function (data) {
                        $("#nestable").loading({destroy: true});
                        $.growl.notice({ message: '{!! z_language("Update Position Successfully") !!}' });
                        cb(data);
                    }
                });
            }
            function ResetNestable() {
                $("#nestable").loading({circles: 3, overlay: true, width: "5em", top: "30%", left: "50%"});
                $.ajax({
                    url: '{{@route('backend:menu:ajax')}}',
                    type: "POST",
                    data: {act: "nestable", data: {type: '{!! $type !!}'}},
                    success: function (html) {
                        $.growl.notice({ message: '{!! z_language("Reset Position Successfully") !!}' });
                        $("#nestable").html(html);
                        $("#nestable").loading({destroy: true});
                    }
                });
            }
            $("#btnCreate").click(function () {
                document.getElementById("form_store").reset();
                var label = "{{ z_language('Menu Create')}}";
                $("#form-title").html(label);
            });
            $("#nestable").on("click", ".edit", function () {
                var data_item = $(this).closest('.dd-item').data();
                var form_store = $("#form_store");
                form_store.loading({circles: 3, overlay: true, width: "5em", top: "30%", left: "50%"});
                $.ajax({
                    url: '{{@route('backend:menu:ajax')}}',
                    type: "POST",
                    data: {act: "edit", data: {id: data_item.id, type: '{!! $type !!}'}},
                    success: function (data) {
                        console.log(JSON.stringify(data));
                        document.getElementById("form_store").reset();
                        if (data.hasOwnProperty("data")) {
                            var label = "{{ z_language('Menu Edit : :Name')  }}";
                            $("#form-title").html(label.replace(":Name", data.data.name));
                            form_store.zoe_inputs('set', data.data);
                        } else {

                        }
                        form_store.loading({destroy: true});
                    }
                });
            });
            $("#nestable").on("click", '.delete', function () {





                var dd_item = $(this).closest('.dd-item');
                var children = dd_item.children('ol.dd-list');

                $.confirm({
                    title: '{!! z_language("Confirm") !!}',
                    content: '{!! z_language("Are you sure to delete this item?") !!}',
                    confirmButton: 'Proceed',
                    confirmButtonClass: 'btn-info',
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    top:0,
                    confirm: function () {
                        if (children.length > 0) {
                            var parent = dd_item.parent();
                            parent.append(children.html());
                            dd_item.remove();
                        } else {
                            dd_item.remove();
                        }
                        SavePosition(dd_item.data('id'), function (data) {
                            if (data.error == 0) {

                            } else {
                                ResetNestable();
                            }
                        });
                    }
                });
            });
            var updateOutput = function (e) {
                var list = e.length ? e : $(e.target),
                    output = list.data('output');
                console.log(window.JSON.stringify(list.nestable('serialize')));
            };
            $('#nestable').nestable({
                group: 1
            }).on('change', updateOutput);
            //$('.dd').nestable('collapseAll');
            //  updateOutput($('#nestable').data('output', $('#nestable-output')));
            $('#nestable-menu').on('click', function (e) {
                var target = $(e.target),
                    action = target.data('action');
                if (action === 'expand-all') {
                    $('.dd').nestable('expandAll');
                }
                if (action === 'collapse-all') {
                    $('.dd').nestable('collapseAll');
                }
            });
            $("#btnSave").click(function () {
                var form_store = $("#form_store");
                form_store.loading({circles: 3, overlay: true, width: "5em", top: "30%", left: "50%"});
                console.log('save');
                $.ajax({
                    url: '{{@route('backend:menu:ajax')}}',
                    type: "POST",
                    data: {"act": "info", data: form_store.zoe_inputs('get')},
                    success: function (data) {
                        form_store.find('.has-error').removeClass('has-error').find('.help-block').hide();
                        form_store.loading({destroy: true});
                        if (data.hasOwnProperty('error')) {
                            for (k in data.error) {
                                var parent;
                                if (data.hasOwnProperty('data_rules') && data.data_rules.hasOwnProperty(k)) {
                                    parent = $("#data-" + k).parent();
                                } else {
                                    parent = $("#" + k).parent();
                                }
                                parent.addClass('has-error');
                                parent.find('.error').html(data.error[k].join("\n"));
                            }
                            $.growl.error({ message:"{!! z_language('Error update failed') !!}" });
                        } else {
                            $.growl.notice({ message: "{!! z_language('Update Successfully') !!}" });
                            ResetNestable();
                        }
                    }
                });
            });
            $("#btnSavePosition").click(function () {
                SavePosition(0, function (data) {

                });
            });
        });
    </script>
@endpush