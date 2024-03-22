
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"> {!! @z_language(["Thông tin hóa đơn"]) !!} </a></li>
        <li><a href="#tab_2" data-toggle="tab" aria-expanded="true"> {!! @z_language(["Thông tin sản phẩm"]) !!} </a></li>

    </ul>
    <div class="tab-content">
        <div class="tab-pane active clearfix" id="tab_1">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div><br/>
            @endif
            @if(isset($model))
                {!! Form::model($model, ['method' => 'POST','route' => ['backend:shop_ja:order:store'],'id'=>'form_store',"enctype"=>"multipart/form-data"]) !!}
                {!! Form::hidden('id') !!}
            @else
                {!! Form::open(['method' => 'POST','route' => ['backend:shop_ja:order:store'],'id'=>'form_store',"enctype"=>"multipart/form-data"]) !!}
            @endif
            <div class="col-md-5">
                <table class="table table-borderless @if ($errors->any())table-error @endif ">
                    <tbody>
                    <tr>
                        <td colspan="3" @if($errors->any() && $errors->getBag("default")->hasAny("phone")) class="error" @endif>
                            {!! Form::label('fullname', z_language('Tên Khách hàng'), ['class' => 'fullname']) !!} (<span
                                class="req">*</span>):
                            {!! Form::text('fullname',null, ['class' => 'form-control','placeholder'=>z_language('Tên Khách hàng')]) !!}
                            @if ($errors->any())
                                <p class="text-error">
                                    @if($errors->any() && $errors->getBag("default")->hasAny("fullname"))
                                        @foreach ($errors->getBag("default")->get("fullname") as $error)
                                            {{ $error }}
                                        @endforeach
                                    @endif
                                </p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" @if($errors->any() && $errors->getBag("default")->hasAny("phone")) class="error" @endif>
                            {!! Form::label('phone', z_language('Số điện thoại'), ['class' => 'phone']) !!} (<span
                                class="req">*</span>):
                            {!! Form::text('phone',null, ['class' => 'form-control','placeholder'=>z_language('Số điện thoại')]) !!}
                            @if ($errors->any())
                                <p class="text-error">
                                    @if($errors->any() && $errors->getBag("default")->hasAny("phone"))
                                        @foreach ($errors->getBag("default")->get("phone") as $error)
                                            {{ $error }}<BR>
                                        @endforeach
                                    @endif
                                </p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            {!! Form::label('country', z_language('Quốc gia'), ['class' => '']) !!} (<span
                                class="req">*</span>):
                            {!! Form::select('country', array_merge(["japan"=>"Nhật Bản"]),null,['class'=>'form-control']); !!}
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3" @if($errors->any() && $errors->getBag("default")->hasAny("city")) class="error" @endif>
                            @php
                                $lists_ship = config('shop_ja.configs.lists_city');
                            @endphp
                            {!! Form::label('Tỉnh/Thành phố', z_language('Tỉnh/Thành phố'), ['class' => '']) !!} (<span
                                class="req">*</span>):
                            @php
                                $category_city  = config_get('shop_ja','category:city',[]);
                                $active = isset($model)?$model->city:"";
                             @endphp
                            <select name="city" id="city-select" class="select2 form-control">
                                    @foreach($category_city as $key=>$value)
                                        @if($active == $key)
                                        <option selected value="{!! $key !!}">{!! $key !!}</option>
                                        @else
                                            <option value="{!! $key !!}">{!! $key !!}</option>
                                        @endif
                                    @endforeach
                            </select>

                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" @if($errors->any() && $errors->getBag("default")->hasAny("address")) class="error" @endif>
                            {!! Form::label('address', z_language('Số nhà, đường'), ['class' => 'address']) !!} (<span
                                class="req">*</span>):
                            {!! Form::text('address',null, ['class' => 'form-control','placeholder'=>z_language('Số nhà, đường')]) !!}
                            @if ($errors->any())
                                <p class="text-error">
                                    @if($errors->any() && $errors->getBag("default")->hasAny("address"))
                                        @foreach ($errors->getBag("default")->get("address") as $error)
                                            {{ $error }}
                                        @endforeach
                                    @endif
                                </p>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3" @if($errors->any() && $errors->getBag("default")->hasAny("postal_code")) class="error" @endif>
                            {!! Form::label('postal_code', z_language('Mã bưu chính'), ['class' => 'postal_code']) !!} (<span
                                class="req">*</span>):
                            {!! Form::text('postal_code',null, ['class' => 'form-control','placeholder'=>z_language('Mã bưu chính')]) !!}
                            @if ($errors->any())
                                <p class="text-error">
                                    @if($errors->any() && $errors->getBag("default")->hasAny("postal_code"))
                                        @foreach ($errors->getBag("default")->get("postal_code") as $error)
                                            {{ $error }}
                                        @endforeach
                                    @endif
                                </p>
                            @endif
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
            <div class="col-md-7">
                <table class="table table-borderless @if ($errors->any())table-error @endif ">
                    <tr>
                        <td @if($errors->any() && $errors->getBag("default")->hasAny("pay_method")) class="error" @endif>
                            {!! Form::label('pay-method', z_language('Phương thức thanh toán'), ['class' => 'pay-method']) !!}
                            : <BR>

                            {!! Form::radio('pay_method', '1' , false) !!}  {!! @z_language(["Thanh toán khi giao hàng"]) !!}
                            {!! Form::radio('pay_method', '2',false) !!}  {!! @z_language(["Chuyển khoản ngân hàng"]) !!}
                            {!! Form::radio('pay_method', '3',false) !!}  {!! @z_language(["Không cần thanh toán"]) !!}
                            @if ($errors->any())
                                <p class="text-error">
                                    @if($errors->any() && $errors->getBag("default")->hasAny("pay_method"))
                                        @foreach ($errors->getBag("default")->get("pay_method") as $error)
                                            {{ $error }}
                                        @endforeach
                                    @endif
                                </p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" @if($errors->any() && $errors->getBag("default")->hasAny("time_ship")) class="error" @endif>
                            {!! Form::label('day_ship', z_language('Ngày nhận hàng'), ['class' => 'day_ship']) !!} (<span
                                class="req">*</span>):
                            {!! Form::date('day_ship',null, ['class' => 'form-control','placeholder'=>z_language('Ngày nhận hàng')]) !!}
                            @if ($errors->any())
                                <p class="text-error">
                                    @if($errors->any() && $errors->getBag("default")->hasAny("day_ship"))
                                        @foreach ($errors->getBag("default")->get("day_ship") as $error)
                                            {{ $error }}
                                        @endforeach
                                    @endif
                                </p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" @if($errors->any() && $errors->getBag("default")->hasAny("time_ship")) class="error" @endif>
                            @php $lists_ship = config('shop_ja.configs.times_ship');  @endphp :
                            {!! Form::label('time_ship', z_language('Giờ nhận'), ['class' => 'time_ship']) !!} (<span
                                class="req">*</span>):
                            {!! Form::select('time_ship', array_merge([""=>z_language('..:  Chọn  :..')],$lists_ship),null,['class'=>'form-control','onchange="change()"']); !!}
                            @if ($errors->any())
                                <p class="text-error">
                                    @if($errors->any() && $errors->getBag("default")->hasAny("time_ship"))
                                        @foreach ($errors->getBag("default")->get("time_ship") as $error)
                                            {{ $error }}
                                        @endforeach
                                    @endif
                                </p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {!! Form::label('bankinfo', z_language('Thông tin chuyển khoản'), ['class' => 'info']) !!} :
                            <table class="table table-bordered">
                                <tr>
                                    <td style="line-height: 30px;">依頼人名.</td>
                                    <td><input type="text" class="form-control bankinfo_1"></td>
                                    <td style="line-height: 30px;">様</td>
                                    <td><input type="text" class="form-control bankinfo_2"></td>
                                    <td style="line-height: 30px;">日に</td>
                                    <td><input type="text" class="form-control bankinfo_3"></td>
                                    <td>円入金済み</td>
                                </tr>
                            </table>
                            {!! Form::hidden('bank_info',null,['id'=>'bank_info']) !!}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" @if($errors->any() && $errors->getBag("default")->hasAny("link")) class="error" @endif>
                            {!! Form::label('link', z_language('Link Facebook'), ['class' => 'link']) !!} (<span
                                class="req">*</span>):
                            {!! Form::text('link',null, ['class' => 'form-control','placeholder'=>z_language('Link Facebook')]) !!}
                            @if ($errors->any())
                                <p class="text-error">
                                    @if($errors->any() && $errors->getBag("default")->hasAny("link"))
                                        @foreach ($errors->getBag("default")->get("link") as $error)
                                            {{ $error }}
                                        @endforeach
                                    @endif
                                </p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {!! Form::label('id_status', 'Status', ['class' => 'status']) !!} &nbsp;
                            {!! Form::radio('status', '1' , true) !!} {!! z_language('Bản nháp') !!}
                            {!! Form::radio('status', '2' , false) !!} {!! z_language('Lập đơn') !!}
                            {!! Form::radio('status', '3',false) !!} {!! z_language('Đã hoàn thành') !!}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {!! Form::label('info', z_language('Lưu ý'), ['class' => 'info']) !!} :
                            {!! Form::textarea('info',null, ['class' => 'form-control','placeholder'=>z_language('Lưu ý'),'cols'=>5,'rows'=>5]) !!}
                        </td>
                    </tr>
                </table>
                <div style="display: none">{!! Form::textarea('dataDetailOrder',null, ['id'=>'dataDetailOrder','class' => 'form-control','placeholder'=>z_language('Lưu ý'),'cols'=>5,'rows'=>5]) !!}</div>

            </div>
            {!! Form::close() !!}
        </div>
        <div class="tab-pane" id="tab_2">
            <form id="FromOrderDetail">
            <table class="table table-bordered">
                <tr>
                    <td>{!! Form::text('name',null, ['id'=>"search_data",'class' => 'form-control','placeholder'=>z_language('Tên sản phẩm')]) !!}</td>
                    <td>{!! Form::number('number',null, ['id'=>'number','class' => 'form-control','placeholder'=>z_language('Số lượng')]) !!}</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table class="table table-bordered" id="orderDetail">
                            <thead>
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Mã sản phẩm</th>
                                <th class="text-center">Tên Sản phẩm</th>
                                <th class="text-center">Công ty</th>
                                <th class="text-center">Vận chuyển</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-center">Hóa đơn</th>
                                <th class="text-center">Giá nhập</th>
                                <th class="text-center">Giá bán</th>
                                <th class="text-center">Phí ship</th>
                                <th class="text-center">Tổng giá nhập</th>
                                <th class="text-center">Tổng giá bán</th>
                                <th class="text-center"> &nbsp; </th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
            </form>
        </div>
    </div>
</div>



<div class="modal fade" id="elfinderShow">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div id="elfinder"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalOption" role="dialog">
    <form action="">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{!! @z_language(["Thêm sản phẩm"]) !!}</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Mã sản phẩm</th>
                            <td><input type="text" class="form-control" name="code_id" id="autocomplete"></td>
                        </tr>
                        <tr>
                            <th>Số lượng</th>
                            <td><input type="text" class="form-control" name="code_id"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btnSave btn btn-primary">{!! @z_language(["Thêm"]) !!}</button>
                </div>
            </div>
        </div>
    </form>
</div>
@section('extra-script')
    <style>
        .autocomplete-suggestions { -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; border: 1px solid #999; background: #FFF; cursor: default; overflow: auto; -webkit-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); -moz-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); }
        .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
        .autocomplete-no-suggestion { padding: 2px 5px;}
        .autocomplete-selected { background: #F0F0F0; }
        .autocomplete-suggestions strong { font-weight: bold; color: #000; }
        .autocomplete-group { padding: 2px 5px; font-weight: bold; font-size: 16px; color: #000; display: block; border-bottom: 1px solid #000; }

    </style>
{{--    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css"/>--}}
{{--    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>--}}


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" />

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('module/admin/bower_components/select2/dist/css/select2.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('module/admin/assets/elfinder/css/elfinder.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('module/admin/assets/elfinder/css/theme.css') }}">

    <script src="{{ asset('module/admin/assets/elfinder/js/elfinder.min.js') }}"></script>
    <script src="{{ asset('module/shop-ja/assets/autocomplete/jquery.autocomplete.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('module/shop-ja/assets/x-editable/css/bootstrap-editable.css') }}">
    <script src="{{ asset('module/shop-ja/assets/x-editable/js/bootstrap-editable.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('module/shop-ja/assets/x-editable/inputs-ext/typeaheadjs/lib/typeahead.js-bootstrap.css') }}">
    <script src="{{ asset('module/shop-ja/assets/x-editable/inputs-ext/typeaheadjs/lib/typeahead.js') }}"></script>
    <script src="{{ asset('module/shop-ja/assets/x-editable/inputs-ext/typeaheadjs/typeaheadjs.js') }}"></script>

    <script src="{{ asset('module/admin/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script !src="">
        $(function () {
            $('.select2').select2()
        });
        function change_type_order(self) {
           let _this = $(self);
           let val = _this.val();

           let data = {
               YAMADA:"Yamato",
               KOGYJA:"Yamato",
               KURICHIKU:"Sagawa",
               FUKUI:"Japan-Post",
               OHGA:"Japan-Post",
           };
           if(!data.hasOwnProperty(val)){
               data[val] = "";
           }
           console.log(data);
            let selected = null;
            $("#ship option").each(function () {
                $(this).removeAttr('selected');
                if($(this).attr('value') === data[val]){
                    selected = $(this);
                }
            });
            if(selected)
                selected.attr('selected',true);
        }
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        function remove(self){
            $(self).parent().parent().remove();
        }
        $(document).ready(function () {
            (function () {
                let AutosWidthHeight = $('.AutoWidthHeight');
                let max = 0;
                AutosWidthHeight.each(function () {
                    if ($(this).height() > max) {
                        max = $(this).height();
                    }
                });
                AutosWidthHeight.each(function () {
                    $(this).height(max);
                });
            })();
            var myModalOption = $("#myModalOption");
            // $("#AddProduct").click(function () {
            //     myModalOption.modal();
            //     let countries = [
            //         { value: 'Andorra', data: 'AD' },
            //         { value: 'Zimbabwe', data: 'ZZ' }
            //     ];
            //     myModalOption.find('#autocomplete').devbridgeAutocomplete({
            //         lookup: countries,
            //         onSelect: function (suggestion) {
            //             console.log('You selected: ' + suggestion.value + ', ' + suggestion.data);
            //         }
            //     });
            // });
            $('.number_count').editable({
                validate: function(value) {
                    if($.trim(value) == '') return 'This field is required';
                }
            });
            $('.code_product').each(function () {
                $(this).editable({
                    value: $(this).text(),
                    typeahead: {
                        name: 'state',
                        local: ["Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi","Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Dakota","North Carolina","Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island","South Carolina","South Dakota","Tennessee","Texas","Utah","Vermont","Virginia","Washington","West Virginia","Wisconsin","Wyoming"]
                    }
                });
            });

        });

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
                    $($(self).data().input).val(file.url);
                    var preview_image_wrapper = $( $(self).data().preview);
                    preview_image_wrapper.attr('src', file.url);
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
    <script>
        let dataImages = {};
        let url = '{!! route('backend:shop_ja:japan:category:show',['product_id' => "/"]) !!}';
        function readURL(self) {
            let k = $(self).attr('data-preview_image');
            dataImages[k] = window.URL.createObjectURL(self.files[0]);
            $("."+k).each(function () {
                this.src =  window.URL.createObjectURL(self.files[0]);
            })
        }
        function removeOrderDetail(self){
            $(self).closest('.rowOrderDetail').remove();
        }
        $(document).ready(function(){

            $("#search_data,#number").focus(function(){
                $(this).val('');
            });
            function IF(val,equals) {
                if(equals === "") return -1;
                for(let k in equals){
                    if(equals.hasOwnProperty(k)){
                        let _val = equals[k];
                        if( _val.equal === "<=" && val <= parseFloat(_val.text)){
                            return _val.value;
                            break;
                        }else if( _val.equal === ">=" && val >= parseFloat(_val.text)){
                            return _val.value;
                            break;
                        }else if( _val.equal === ">" && val > parseFloat(_val.text)){
                            return _val.value;
                            break;
                        } else if( _val.equal === "<" && val < parseFloat(_val.text)){
                            return _val.value;
                            break;
                        }else if( _val.equal === "=" && val === parseFloat(_val.text)){
                            return _val.value;
                            break;
                        }
                    }
                }
            }
            function update(data) {
                $.post("{!! route('backend:shop_ja:order:ajax') !!}" , data, function (response) {
                    if(response.length > 0 ){
                        let dom = $("#id_"+response[0].data.id);

                        dom.find('.price_ship').text(response[0].data.price_ship);

                        dom.find('.total_price').text(data.count*response[0].data.price);
                        dom.find('.total_price_buy').text(data.count*response[0].data.price_buy);
                        let number_count = dom.find('.number_count');
                        number_count.text(data.count);
                        number_count.editable('setValue',data.count);
                        number_count.parent().find('.itemVal').val(data.count);
                    }else{
                        dom.find('.price_ship').text("no");
                    }
                });
            }
            function insertRow(data,hidden,currentData){
                console.log(currentData);
                let orderDetail = $("#orderDetail tbody");
                let templateUpload = function(id,ship){
                    let img = "http://placehold.jp/100x150.png";
                    let ValImg = "";
                    let k = "preview-image-"+ship;
                    if(currentData.hasOwnProperty('image') && currentData.image.length > 0){
                        img = currentData.image;
                        ValImg = img;
                    }
                    let html = '<div class="image-wrapper"><div class="preview-image-wrapper"><img id="preview-image-'+id+'" class="preview-image-'+ship+'" style="width: 100px;height: 150px" src="'+img+'" alt="">  </div>\n' +
                        '                  <BR>\n' +
                        '                        <input data-preview="#preview-image-'+id+'" data-input="#file_input_'+id+'" onfocus="openElfinder(this)" type="text" id="file_input_'+id+'" class="form-control itemVal" value="'+ValImg+'"><BR>' +
                        '                        </div>';
                    return html;
                };
                let html = "<tr class='rowOrderDetail' id='id_"+data.id+"' data-id='"+data.id+"'>";
                html+="<td class=\"text-center\" data-key='id'><input type='hidden' class='itemVal' value='"+data.id+"'/><textarea class='data' style='display: none'>"+JSON.stringify(data)+"</textarea></td>";
                html+="<td class=\"text-center\" data-key='code'>"+data.code+"</td>";
                html+="<td class=\"text-center\"><a target=\"_blank\" href='"+url+"/"+data.id+"'>"+data.title+"<BR></a><i>"+data.description+"</i></td>";
                html+="<td class=\"text-center\" data-key='company'>"+data.company+"<input type='hidden' class='itemVal' value='"+hidden.company+"'/> </td>";
                html+="<td class=\"text-center\" data-key='ship'>"+data.ship+"<input type='hidden' class='itemVal' value='"+hidden.ship+"'/></td>";
                html+="<td  class=\"text-center count\" data-key='count'><a href=\"#\" class=\"number_count\" data-type=\"text\" data-pk=\"1\" data-title=\"Số lượng\">"+(currentData.hasOwnProperty('count')?currentData.count:data.count)+"</a><input type='hidden' class='itemVal' value='"+(currentData.hasOwnProperty('count')?currentData.count:data.count)+"'/> kg"+"</td>";
                html+="<td class=\"text-center\"  data-key='image'>"+templateUpload(data.id,data.ship)+"</td>";
                html+="<td class=\"text-center price\" data-key='price'>"+data.price+"</td>";
                html+="<td class=\"text-center price_buy\" data-key='price_buy'>"+data.price_buy+"</td>";
                html+="<td class=\"text-center price_ship\" data-key='price_ship'>"+data.price_ship+"</td>";
                html+="<td class=\"text-center total_price\" data-key='total_price'>"+(currentData.hasOwnProperty('total_price')?currentData.total_price:data.total_price)+"</td>";
                html+="<td class=\"text-center total_price_buy\" data-key='total_price_buy'>"+(currentData.hasOwnProperty('total_price_buy')?currentData.total_price_buy:data.total_price_buy)+"</td>";
                html+="<td class=\"text-center\"><button type=\"button\" class=\"remove btn btn-danger btn-xs\" onclick=\"removeOrderDetail(this)\">Xóa</button></td>";
                html+="</tr>";
                html = $(html);
                html.find('.number_count').editable({
                    validate: function(value) {
                        if($.trim(value) == '') return 'This field is required';
                    },
                    success: function(response, newValue) {
                        update({count:newValue,id:data.id,city:$("#city-select").val()});
                    }
                });
                orderDetail.prepend(html);
            }
            $(document).on("click",".btnSave",function () {
                let orderDetail = $("#orderDetail tbody tr");
                let data = [];
                $("#bank_info").val($(".bankinfo_1").val()+"|"+$(".bankinfo_2").val()+"|"+$(".bankinfo_3").val());
                orderDetail.each(function () {
                    let tds = $(this).find('td');
                    let td = {};
                    tds.each(function () {
                        let key = $(this).attr('data-key');
                        if(key !== undefined){
                            if(key === 'image' || key === "company" || key === "ship" || key === "count" || key==="id"){
                                td[key] = $(this).find('.itemVal').val();
                            }else{
                                td[key] = $(this).text();
                            }
                        }

                    });
                    data.push(td);
                });



                $("#dataDetailOrder").val(JSON.stringify(data));
                document.getElementById('form_store').submit();
            });
            $("#number").change(function () {
                update({count:$(this).val(),id:$(this).attr('data-id'),city:$("#city-select").val()});
            });

            $("#city-select").change(function () {
                let city = $(this).val();
                $("#orderDetail tbody tr").each(function () {
                   let count = $(this).find('.number_count').text();
                   let id = $(this).attr('data-id');
                    update({count:count,id:id,city:city});
                });
            });

            $('#search_data').autocomplete({
                source: function (request, response) {
                    request.city = $("#city-select").val();
                    $.post("{!! route('backend:shop_ja:order:ajax') !!}" , request, response);
                },
                minLength: 1,
                select: function(event, ui)
                {
                    $('#search_data').val(ui.item.value);
                    $("#number").attr('data-id',ui.item.data.id);
                    insertRow(ui.item.data,ui.item.hidden,{});
                }
            }).data('ui-autocomplete')._renderItem = function(ul, item){
                return $("<li class='ui-autocomplete-row'></li>")
                    .data("item.autocomplete", item)
                    .append(item.label)
                    .appendTo(ul);
            };
            (function (data) {
                console.log(data);
                let par = {};
                let sendData = {};
                let counts = {};
                for(let k in data){
                    if(data.hasOwnProperty(k)){
                        par[data[k].id] = ({id:data[k].id,count:data[k].count,index:k});
                    }
                }
                sendData.city = $("#city-select").val();
                sendData.lists = par;
                $.post("{!! route('backend:shop_ja:order:ajax') !!}" , sendData , function (ReponDatas) {
                    console.log(ReponDatas);
                    for(let k in ReponDatas){
                        if(ReponDatas.hasOwnProperty(k)){
                            insertRow(ReponDatas[k].data,ReponDatas[k].hidden,data[par[ReponDatas[k].data.id].index]);
                        }
                    }
                });
            })({!! isset($model)?$model->detailOrder:'[]' !!});
            (function (val) {
                if(val){
                    let _val =  val.split("|");
                    console.log(_val);
                    for(let k in _val){
                        $('.bankinfo_'+(parseInt(k)+1)).val(_val[k]);
                    }
                }

            })($("#bank_info").val())
        });
    </script>
@endsection
