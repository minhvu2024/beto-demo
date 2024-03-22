@section('content-header')
    <h1>
        &starf; {!! @z_language(["QL Đường Dẫn Admin"]) !!}
        <small>it all starts here</small>

    </h1>
@endsection
@section('content')
    @breadcrumb()@endbreadcrumb
    <!-- Default box -->
    <div class="col-md-6">
        <div class="box box-zoe">
            <div class="box-header with-border">
                <h3 class="box-title">{!! @z_language(["Đường Dẫn"]) !!}</h3>
            </div>
            <div class="box-body">
                <menu id="nestable-menu">
                    <button type="button" data-action="expand-all">Expand All</button>
                    <button type="button" data-action="collapse-all">Collapse All</button>
                </menu>

                <div class="cf nestable-lists">
                    <div class="dd" id="nestable">
                        <ol class="dd-list">
                            <li class="dd-item" data-id="1">
                                <div class="dd-handle">Item 1</div>
                            </li>
                            <li class="dd-item" data-id="2">
                                <div class="dd-handle">Item 2</div>
                                <ol class="dd-list">
                                    <li class="dd-item" data-id="3"><div class="dd-handle">Item 3</div></li>
                                    <li class="dd-item" data-id="4"><div class="dd-handle">Item 4</div></li>
                                    <li class="dd-item" data-id="5">
                                        <div class="dd-handle">Item 5</div>
                                        <ol class="dd-list">
                                            <li class="dd-item" data-id="6"><div class="dd-handle">Item 6</div></li>
                                            <li class="dd-item" data-id="7"><div class="dd-handle">Item 7</div></li>
                                            <li class="dd-item" data-id="8"><div class="dd-handle">Item 8</div></li>
                                        </ol>
                                    </li>
                                    <li class="dd-item" data-id="9"><div class="dd-handle">Item 9</div></li>
                                    <li class="dd-item" data-id="10"><div class="dd-handle">Item 10</div></li>
                                </ol>
                            </li>
                            <li class="dd-item" data-id="11">
                                <div class="dd-handle">Item 11</div>
                            </li>
                            <li class="dd-item" data-id="12">
                                <div class="dd-handle">Item 12</div>
                            </li>
                        </ol>
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
                <h3 class="box-title">{!! @z_language(["Đường Dẫn"]) !!}</h3>
            </div>
            <div class="box-body">
                <menu id="nestable-menu">
                    <button type="button" data-action="expand-all">Expand All</button>
                    <button type="button" data-action="collapse-all">Collapse All</button>
                </menu>

                <div class="cf nestable-lists">
                    <div class="dd" id="nestable2">
                        <ol class="dd-list">
                            <li class="dd-item" data-id="13">
                                <div class="dd-handle">Item 13</div>
                            </li>
                            <li class="dd-item" data-id="14">
                                <div class="dd-handle">Item 14</div>
                            </li>
                            <li class="dd-item" data-id="15">
                                <div class="dd-handle">Item 15</div>
                                <ol class="dd-list">
                                    <li class="dd-item" data-id="16"><div class="dd-handle">Item 16</div></li>
                                    <li class="dd-item" data-id="17"><div class="dd-handle">Item 17</div></li>
                                    <li class="dd-item" data-id="18"><div class="dd-handle">Item 18</div></li>
                                </ol>
                            </li>
                        </ol>
                    </div>
                </div>

            </div>
            <div class="box-footer">
                <button class="btn btn-default" id="btnSavePosition"><i class="fa fa-plus"></i> Save</button>
            </div>
        </div>
    </div>
    <textarea id="nestable-output"></textarea>
    <textarea id="nestable2-output"></textarea>

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

        $(document).ready(function()
        {

            var updateOutput = function(e)
            {
                var list   = e.length ? e : $(e.target),
                    output = list.data('output');
                if (window.JSON) {
                    output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
                } else {
                    output.val('JSON browser support required for this demo.');
                }
            };
            // activate Nestable for list 1
            $('#nestable').nestable({
                group: 1
            })
                .on('change', updateOutput);

            // activate Nestable for list 2
            $('#nestable2').nestable({
                group: 1
            })
                .on('change', updateOutput);

            // output initial serialised data
            updateOutput($('#nestable').data('output', $('#nestable-output')));
            updateOutput($('#nestable2').data('output', $('#nestable2-output')));

            // $('#nestable-menu').on('click', function(e)
            // {
            //     var target = $(e.target),
            //         action = target.data('action');
            //     if (action === 'expand-all') {
            //         $('.dd').nestable('expandAll');
            //     }
            //     if (action === 'collapse-all') {
            //         $('.dd').nestable('collapseAll');
            //     }
            // });



        });
    </script>
@endpush