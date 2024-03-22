@section('content-header')
    <h1>
        &starf; {!! @z_language(["Công Ty"]) !!}
        <small>it all starts here</small>
        <a href="#" id="btnCreate"
           class="btn btn-default btn-md"><i class="fa fa-fw fa-plus"></i> {!! @z_language(["Thêm mới"]) !!} </a>
    </h1>
@endsection
@section('content')
    @breadcrumb()@endbreadcrumb
    <!-- Default box -->
    <div class="col-md-6">
        <div class="box box-zoe">
            <div class="box-header with-border">
                <h3 class="box-title">{!! @z_language(["Quản lý công ty"]) !!}</h3>

            </div>
            <div class="box-body">
                <menu id="nestable-menu">
                    <button class="btn btn-default" type="button" data-action="expand-all">Expand All</button> &nbsp;
                    <button class="btn btn-default" type="button" data-action="collapse-all">Collapse All</button>
                </menu>
                <div class="cf nestable-lists">
                    <div class="dd" id="nestable">
                        {!! $nestable  !!}
                        {{--<ol class="dd-list">--}}
                        {{--@foreach($category as $_category)--}}
                        {{--<li class="dd-item dd3-item" data-id="{{$_category->id}}">--}}
                        {{--<div class="dd-handle dd3-handle"></div>--}}
                        {{--<div class="dd3-content">{{$_category->name}}</div>--}}
                        {{--</li>--}}
                        {{--@endforeach--}}
                        {{--<li class="dd-item dd3-item" data-id="14">--}}
                        {{--<div class="dd-handle dd3-handle"></div><div class="dd3-content">Item 14</div>--}}
                        {{--</li>--}}
                        {{--<li class="dd-item dd3-item" data-id="15">--}}
                        {{--<div class="dd-handle dd3-handle"></div><div class="dd3-content">Item 15</div>--}}
                        {{--<ol class="dd-list">--}}
                        {{--<li class="dd-item dd3-item" data-id="16">--}}
                        {{--<div class="dd-handle dd3-handle"></div><div class="dd3-content">Item 16</div>--}}
                        {{--</li>--}}
                        {{--<li class="dd-item dd3-item" data-id="17">--}}
                        {{--<div class="dd-handle dd3-handle"></div><div class="dd3-content">Item 17</div>--}}
                        {{--</li>--}}
                        {{--<li class="dd-item dd3-item" data-id="18">--}}
                        {{--<div class="dd-handle dd3-handle"></div><div class="dd3-content">Item 18</div>--}}
                        {{--</li>--}}
                        {{--</ol>--}}
                        {{--</li>--}}
                        {{--</ol>--}}
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
                <h3 class="box-title" id="form-title">{{ z_language('Tạo chuyên mục')}}</h3>
            </div>
            <div class="box-body">
                {!! Form::open(['method' => 'POST','id'=>'form_store']) !!}
                {!! Form::hidden('type',$type) !!}
                {!! Form::hidden('is_slug',$is_slug) !!}
                {!! Form::hidden('id',0) !!}
                {!! Form::hidden('class_nestable',$class_nestable) !!}

                <table class="table table-borderless">
                    <tbody>
                    <tr>
                        <td>
                            {!! Form::label('name', z_language('Tên'), ['class' => 'name']) !!} (<span
                                class="req">*</span>):
                            {!! Form::text('name',null, ['class' => 'form-control','placeholder'=>'Tiêu đề']) !!}
                            <span class="error help-block"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {!! Form::label('description', z_language('Mô tả'), ['class' => 'description']) !!} (<span
                                class="req">*</span>):
                            {!! Form::textarea('description',null, ['class' => 'form-control','placeholder'=>'Mô tả','cols'=>5,'rows'=>5]) !!}
                            <span class="error help-block"></span>
                        </td>
                    </tr>
                    <tr>
                        <td  @if($errors->any() && $errors->getBag("default")->hasAny("ship")) class="error" @endif>
                            @php $lists_ship = config('shop_ja.configs.lists_ship');  @endphp
                            {!! Form::label('ship', z_language('Đơn vị giao hàng'), ['class' => '']) !!} (<span
                                class="req">*</span>):
                            @php $category = get_category_type('shop-ja:japan:category:com-ship'); @endphp :
                            <select name="data.ship" class="form-control">
                                @foreach($category as $k=>$val)
                                    <option value="{!! $val->id !!}">{!! $val->name !!}</option>
                                @endforeach
                            </select>
                            @if ($errors->any())
                                <p class="text-error">
                                    @if($errors->any() && $errors->getBag("default")->hasAny("data_ship"))
                                        @foreach ($errors->getBag("default")->get("data_ship") as $error)
                                            {{ $error }}
                                        @endforeach
                                    @endif
                                </p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Cấu hình: Daibiki</th>  
                    </tr>
                    <tr>

                        <td>

                            <table class="table table-bordered wrap_rows" id="wrap">
                                @php
                                    $lists_uint = array_merge([0=>"Default"],config('shop_ja.configs.lists_uint'));
                                @endphp
                                @foreach($lists_uint as $key=>$value)
                                    <tr>
                                        <td>
                                            <table class="table table-bordered wrap_rows" id="wrap_{!! $key !!}">
                                                <thead>
                                                <tr>
                                                    <td colspan="7">
                                                        {!! Form::label('data.'.$key, Illuminate\Support\Str::studly($value), ['class' => 'name']) !!}
                                                    </td>
                                                </tr>
                                                <tr class="template" data-index="@INDEX@">
                                                    <td class="text-center">
                                                        0
                                                    </td>
                                                    <td><input data-key="value_start" data-name="data[{!! $key !!}][@INDEX@].value_start"  class="data form-control text_start" placeholder="Giá trị 1" type="text"></td>
                                                    <td>
                                                        @php
                                                            $lists_equal = ['='=>'=','>'=>'>','<'=>'<','>='=>'≥','<='=>'≤'];
                                                        @endphp
                                                        {!! Form::select(null, array_merge($lists_equal),null,['class'=>'data form-control equal_start','data-key'=>'equal_start','data-name'=>"data[".$key."][@INDEX@].equal_start"]); !!}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $lists_equal = ['='=>'=','>'=>'>','<'=>'<','>='=>'≥','<='=>'≤'];
                                                        @endphp
                                                        {!! Form::select(null, array_merge($lists_equal),null,['class'=>'data form-control equal_end','data-key'=>'equal_end','data-name'=>"data[".$key."][@INDEX@].equal_end"]); !!}
                                                    </td>
                                                    <td><input data-key="value_end" data-name="data[{!! $key !!}][@INDEX@].value_end"  class="data form-control text_end" placeholder="Giá trị 2" type="text"></td>

                                                    <td>
                                                        @php
                                                            $lists_equal = ['count'=>'Count','totalPrice'=>'totalPrice'];
                                                        @endphp
                                                        {!! Form::select(null, array_merge($lists_equal),null,['class'=>'data form-control col','data-key'=>'col','data-name'=>"data[".$key."][@INDEX@].col"]); !!}
                                                    </td>
                                                    <td><input  data-key="value" data-name="data[{!! $key !!}][@INDEX@].value" class="data form-control value" placeholder="Giá trị" type="text"></td>


                                                    <td class="text-center">
                                                        <button type="button" data-id="#wrap_{!! $key !!}" class="add btn btn-success btn-xs" onclick="add(this)">Thêm</button>
                                                        <button style="display: none" type="button" data-id="#wrap_{!! $key !!}" class="remove btn btn-danger btn-xs" onclick="remove(this)">Xóa</button>
                                                    </td>
                                                </tr>
                                                </thead>
                                                <tbody>


                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
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
    <style>
        .SelectEdit{
            background: green;
        }
    </style>
    <script src="{{asset("http://wojoscripts.com/cmspro/assets/nestable.js")}}"></script>
    <script>

        String.prototype.trimRight = function(charlist) {
            if (charlist === undefined)
                charlist = "\s";
            return this.replace(new RegExp("[" + charlist + "]+$"), "");
        };
        function renderData(data) {
            $(".wrap_rows").find('tbody').empty();
            let index = 0;

            for(let k in data){
                template($("#wrap"),data[k],index++);
            }
        }
        function beforeSave(parent) {
            let trs = parent.find('tr.Element');
            let count = 1;
            trs.each(function () {
                console.log(this);
                if(!$(this).hasClass('template')){
                    let elements = $(this).find('.data');
                    let _index = "";
                    elements.each(function (index) {
                        if(this.hasAttribute('data-index')){
                            _index+= $(this).val().trim()+"_";
                        }
                    });
                    if(_index.length === 0)
                        _index = count++;
                    else
                        _index = _index.trimRight("_");
                    $(this).attr('data-index',_index);
                    $(this).find("td").first().empty().html(_index);
                    elements.each(function () {
                        $(this).attr('name',$(this).attr('data-name').replace("@INDEX@",_index))
                    });
                }
            });
        }
        function template(tbody,vals,index) {
            let template = tbody.find('.template').clone();
            template.removeClass('template');
            template.find("td").first().empty().html(index+1);
            template.addClass('Element');

            template.find('.data').each(function () {
                $(this).removeAttr('name');
                let key = $(this).attr('data-key');
                let tagName = ($(this).prop("tagName").toLowerCase());
                if(tagName === 'select'){
                    if(vals.hasOwnProperty(key)){
                        $(this).find('option').each(function () {
                            if($(this).attr('value') === vals[key])
                                $(this).attr('selected','selected');
                        });
                    }

                }else if(tagName === 'input'){
                    if(vals.hasOwnProperty(key)){
                        $(this).val(vals[key]);
                    }
                }
            });
            tbody.append(template);

            beforeSave(tbody);

            tbody.find('.template').find('.data').each(function () {
                $(this).removeAttr('name');

                if($(this).hasClass('uint')){
                    // $(this).find('option').first().attr('selected',true);
                }else{
                    $(this).val('');
                }
            });
        }
        function remove(self) {
            let _this = $(self);
            let parent = _this.parent().parent();
            let wrap = parent.closest(".wrap_rows").find('tbody');
            parent.remove();
            beforeSave(wrap);
        }
        function add(self){
            let _this = $(self);
            let parent = _this.closest(_this.attr('data-id'));
            let tbody = parent.find('tbody');
            let trs = tbody.find("tr");

            trs.each(function () {
                $(this).removeClass('Error');
            });
            let tr = parent.find('.template');
            let vals = {"text":tr.find('.text').val(),"value":tr.find('.value').val(),'equal':tr.find('.equal').val(),'col':tr.find('.col').val()};
            if((vals.text.length > 0 && vals.value.length > 0)){
                template(parent,vals,trs.length);
            }else{
                tr.addClass('Error');
            }
        }

        $(document).ready(function () {
            function SavePosition(id, cb) {
                var e = $('#nestable').data('output', $('#nestable-output'));
                var list = e.length ? e : $(e.target), output = list.data('output');
                $("#nestable").loading({circles: 3, overlay: true, width: "5em", top: "20%", left: "50%"});
                $.ajax({
                    url: '{{@route('backend:category:ajax')}}',
                    type: "POST",
                    data: {act: "position", data: {id: id, pos: list.nestable('serialize'), type: '{!! $type !!}'}},
                    success: function (data) {
                        $("#nestable").loading({destroy: true});
                        $.growl.notice({ message: '{!! z_language("Cập nhật vị trí thành công") !!}' });
                        cb(data);
                    }
                });
            }
            function ResetNestable() {
                $("#nestable").loading({circles: 3, overlay: true, width: "5em", top: "30%", left: "50%"});
                $.ajax({
                    url: '{{@route('backend:category:ajax')}}',
                    type: "POST",
                    data: {act: "nestable", data: {id:$("input:hidden[name=id]").val(),type: '{!! $type !!}',nestable:'{!! $class_nestable !!}' } },
                    success: function (html) {
                        $.growl.notice({ message: '{!! z_language("Đặt lại vị trí thành công") !!}' });
                        $("#nestable").html(html);
                        $("#nestable").loading({destroy: true});
                    }
                });
            }
            $("#btnCreate").click(function () {
                document.getElementById("form_store").reset();
                var label = "{{ z_language('Tạo chuyên mục')}}";
                $("#form_store input:hidden[name=id]").val(0);
                $("#form-title").html(label);
            });
            $("#nestable").on("click", ".edit", function () {
                var data_item = $(this).closest('.dd-item').data();
                var form_store = $("#form_store");
                $("#nestable").find('.SelectEdit').removeClass('SelectEdit');
                $(this).parent().parent().children('.dd-handle').addClass('SelectEdit');
                form_store.loading({circles: 3, overlay: true, width: "5em", top: "30%", left: "50%"});
                $.ajax({
                    url: '{{@route('backend:category:ajax')}}',
                    type: "POST",
                    data: {act: "edit", data: {id: data_item.id, type: '{!! $type !!}'}},
                    success: function (data) {

                        document.getElementById("form_store").reset();
                        if (data.hasOwnProperty("data")) {
                            var label = "{{ z_language('Sửa chuyên mục : :Name')  }}";
                            $("#form-title").html(label.replace(":Name", data.data.name));
                            console.log(data.data.data);

                            if(data.data.data.hasOwnProperty('config')){
                                renderData(data.data.data.config);
                            }
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
                    title: '{!! z_language("Xác nhận") !!}',
                    content: '{!! z_language("Bạn có chắc chắn để xóa mục này?") !!}',
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
                    url: '{{@route('backend:category:ajax')}}',
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
                            $.growl.error({ message:"{!! z_language('Lỗi cập nhật không thành công') !!}" });
                        } else {
                            $.growl.notice({ message: "{!! z_language('Cập nhật thành công') !!}" });
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
