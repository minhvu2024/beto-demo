@if(isset($model))
    {!! Form::model($model, ['method' => 'POST','route' => ['backend:shop_ja:ship:store'],'id'=>'form_store']) !!}
    @if($act =="edit")
        {!! Form::hidden('id') !!}
    @endif
@else
    {!! Form::open(['method' => 'POST','route' => ['backend:shop_ja:ship:store'],'id'=>'form_store']) !!}
@endif
<div class="col-md-12">
    <div class="box box box-zoe">
        <div class="box-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div><br/>
            @endif
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div><br/>
                @endif
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"> {!! @z_language(["Thông tin"]) !!} </a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td colspan="4">
                                        {!! Form::label('category_id',z_language('Công Ty'), ['class' => 'Category']) !!} *
                                        {!! Form::CategoriesNestableOne($nestables,[Form::value('category_id')=>""],"category_id") !!}
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        {!! Form::label('value', z_language('Điều kiện Start'), ['class' => 'v']) !!}
                                        @php
                                            $lists_equal = ['='=>'=','>'=>'>','<'=>'<','>='=>'≥','<='=>'≤'];
                                        @endphp
                                        [SỐ LƯỢNG]  {!! Form::select('equal_start', $lists_equal,null,['class'=>'form-control','name'=>"equal_start"]); !!}
                                    </td>
                                    <td>
                                        {!! Form::label('value_start', z_language('Số lượng Start'), ['class' => 'v']) !!}
                                        {!! Form::text('value_start',null, ['class' => 'form-control','placeholder'=>z_language('Số lượng Start')]) !!}
                                    </td>

                                    <td>
                                        {!! Form::label('value', z_language('Điều kiện End'), ['class' => 'v']) !!}
                                        @php
                                            $lists_equal = ['='=>'=','>'=>'>','<'=>'<','>='=>'≥','<='=>'≤'];
                                        @endphp
                                      [SỐ LƯỢNG]  {!! Form::select('equal_end', $lists_equal,null,['class'=>'form-control','name'=>"equal_end"]); !!}
                                    </td>
                                    <td>
                                        {!! Form::label('value_end', z_language('Số lượng End'), ['class' => 'v']) !!}
                                        {!! Form::text('value_end',null, ['class' => 'form-control','placeholder'=>z_language('Số lượng End')]) !!}
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="4">
                                        {!! Form::label('unit', z_language('Đơn vị'), ['class' => 'unit']) !!} &nbsp;
                                        @php
                                            $lists_uint = array_merge(["Tất cả"],config('shop_ja.configs.lists_uint'));
                                            $active = isset($model)?$model->unit: 0;
                                        @endphp
                                        @foreach( $lists_uint as $key=>$value)
                                            {!! Form::radio('unit', $key ,$key == $active,['id'=>"id_".$key]) !!} {!! $value !!}
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <div style="display: none">{!! Form::textarea('config',null, ['id'=>'Data','class' => 'form-control','placeholder'=>z_language('Cấu hình'),'cols'=>5,'rows'=>5]) !!}</div>

                                        <table class="table table-bordered wrap_rows" id="wrap">
                                            <thead>
                                                <tr class="template" data-index="@INDEX@">
                                                    <td class="text-center">0</td>
                                                    <td><input data-key="text" data-name="data[@INDEX@].text"  class="data form-control text" placeholder="Các tỉnh" type="text"></td>
                                                    <td><input  data-key="value" data-name="data[@INDEX@].value" class="data form-control value" placeholder="Giá trị" type="text"></td>
                                                    <td class="text-center">
                                                        <button type="button" data-id="#wrap" class="add btn btn-success btn-xs" onclick="add(this)">Thêm</button>
                                                        <button style="display: none" type="button" data-id="#wrap" class="remove btn btn-danger btn-xs" onclick="remove(this)">Xóa</button>
                                                    </td>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{!! Form::close() !!}
<div class="modal fade" id="elfinderShow">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div id="elfinder"></div>
            </div>
        </div>
    </div>
</div>
@section('extra-script')
    <style>
        .SelectEdit{
            background: green;
        }
        .Error .form-control{
            border: 1px solid red;
        }
        .template .remove{
            display: none  !important;
        }
        .Element .remove{
            display: block  !important;
        }
        .template .add{
            display: block  !important;
        }
        .Element .add{
            display: none !important;
        }
    </style>
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css"/>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('module/admin/assets/elfinder/css/elfinder.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('module/admin/assets/elfinder/css/theme.css') }}">
    <script src="{{ asset('module/admin/assets/elfinder/js/elfinder.min.js') }}"></script>
    <script !src="">
        $(document).ready(function () {
            $('.btnSave').click(function () {

                $(".wrap_rows tbody").each(function () {
                    beforeSave($(this));
                });
                let form_store = $("#form_store");

                let data = form_store.zoe_inputs('get');

                if(!data.hasOwnProperty('data') || data.data.length === 0){
                    $("#Data").html('[]');
                }else{
                    $("#Data").html(JSON.stringify(data.data));
                }
                 document.getElementById('form_store').submit();
            });
        });
        function handlePaste (e) {
            var clipboardData, pastedData;

            // Stop data actually being pasted into div
            e.stopPropagation();
            e.preventDefault();

            // Get pasted data via clipboard API
            clipboardData = e.clipboardData || window.clipboardData;
            console.log(clipboardData);

        }

        $(document).on('paste', ".Element .text,.template .text",function(e) {
            let str = e.originalEvent.clipboardData.getData('text');
            str = $.trim(str).replace(/ +(?= )/g,'');

            let val = str.replace(/\s/g, '-');
            setTimeout(function () {
                $(e.target).val(val)
            },100);
        });
        $(document).on('paste', ".Element .value,.template .value",function(e) {
            let str = e.originalEvent.clipboardData.getData('text');
            str = $.trim(str).replace(/ +(?= )/g,'');
            let val = str.replace(/\D/g,'');
            setTimeout(function () {
                $(e.target).val(val)
            },100);
        });
        // $(".template .text").bind('paste', function(e) {
        //     let str = e.originalEvent.clipboardData.getData('text');
        //     str = $.trim(str).replace(/ +(?= )/g,'');
        //
        //     let val = str.replace(/\s/g, '-');
        //     setTimeout(function () {
        //         $(e.target).val(val)
        //     },100);
        // });

        String.prototype.trimRight = function(charlist) {
            if (charlist === undefined)
                charlist = "\s";
            return this.replace(new RegExp("[" + charlist + "]+$"), "");
        };
        function renderData(data) {
            if(data.length > 0){
                let _data = JSON.parse(data);
                $(".wrap_rows").find('tbody').empty();
                let index = 0;
                for(let k in _data){
                    template($("#wrap"),_data[k],index++);
                }
            }
        }
        (function () {
            renderData($("#Data").val() );
        })();
        function beforeSave(parent) {
            let trs = parent.find('tr.Element');
            let count = 1;
            trs.each(function () {
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
            console.log(parent);
            trs.each(function () {
                $(this).removeClass('Error');
            });
            let tr = parent.find('.template');
            let vals = {"text":tr.find('.text').val(),"value":tr.find('.value').val()};
            if((vals.text.length > 0 && vals.value.length > 0)){
                template(parent,vals,trs.length);
            }else{
                tr.addClass('Error');
            }
        }


        function openElfinder(self) {
            $('#elfinderShow').modal();
            $('#elfinder').elfinder({
                debug: false,
                width: '100%',
                height: '80%',
                cssAutoLoad: false,
                customData: {
                    _token: '{{ csrf_token() }}'
                },
                commandsOptions: {
                    getfile: {
                        onlyPath: true,
                        folders: false,
                        multiple: false,
                        oncomplete: 'destroy'
                    },
                    ui: 'uploadbutton'
                },
                mimeDetect: 'internal',
                onlyMimes: [
                    'image/jpeg',
                    'image/jpg',
                    'image/png',
                    'image/gif'
                ],
                ui: ['toolbar', 'path', 'stat'],
                rememberLastDir: false,
                url: '{{ route("backend:elfinder:showConnector") }}?image=1',
                soundPath: '{{ asset('module/admin/assets/elfinder/sounds') }}',
                getFileCallback: function (file) {
                    console.log(file);
                    console.log($($(self).data().input).val(file.url));
                    //$($(self).data().input).val(file.url);

                    var preview_image_wrapper = $(".preview-image-wrapper");
                    preview_image_wrapper.show();

                    preview_image_wrapper.find("img").attr('src', file.url);
                    preview_image_wrapper.find("[name='image']").val(file.url);

                    // var preview_image_wrapper = $( $(self).data().preview);
                    // preview_image_wrapper.attr('src', file.url);
                    // $('#elfinderShow').modal('hide');
                    $('#elfinderShow').modal('hide');
                },
                resizable: false,
                uiOptions: {
                    toolbar: [
                        ['home', 'up'],
                        ['upload'],
                        ['quicklook'],
                    ],
                    tree: {
                        openRootOnLoad: true,
                        syncTree: true
                    },
                    navbar: {
                        minWidth: 150,
                        maxWidth: 500
                    },
                    cwd: {
                        oldSchool: false
                    }
                }
            }).elfinder('instance');
        }
    </script>
@endsection
