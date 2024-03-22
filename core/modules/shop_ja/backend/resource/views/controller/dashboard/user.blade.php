@section('content')
    @breadcrumb()@endbreadcrumb
    <div style="display: none">
        {!! $analytics['sql'] !!}
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label>Thời gian:</label>
                <div class="input-group">
                    <div class="input-group-addon" style="padding: 0;margin: 0;">
                        <button type="button" class="btn daterange pull-left">
                            <i class="fa fa-calendar"></i></button>
                    </div>
                    <input type="text" class="form-control pull-right" id="reservation">
                </div>
            </div>
        </div>
        @if(Auth::user()->IsAcl("dashboard:all"))
        <div class="col-md-3">
            <div class="form-group">
                <label>{!! z_language("Tài khoản") !!}:</label>
                <div class="input-group">
                    <select id="user_id" class="form-control" onchange="ChangeAction(this)">
                        <option value="0">Tổng</option>
                        @foreach($users as $key=>$groups)
                            <optgroup label="{!! $roles[$key]?$roles[$key]->name:z_language('Không xác định') !!}">
                                @foreach($groups as $group)
                                    <option value="{!! base64_encode($group->id) !!}">{!! $group->username !!}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="result-analytics">
                <div class="row">
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-cart-outline"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Tổng</span>
                                <span class="info-box-number">{!! $analytics['total'] !!}<small></small></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="ion ion-pricetag"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Lợi Nhuận</span>
                                <span class="info-box-number">{!! number_format($analytics['price']) !!}<small></small></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-red"><i class="ion ion-ios-cart-outline"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Thành công</span>
                                <span class="info-box-number">{!! $analytics['success'] !!}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <!-- fix for small devices only -->
                    <div class="clearfix visible-sm-block"></div>

                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Hủy</span>
                                <span class="info-box-number">{!! $analytics['cancel'] !!}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-cart-outline"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Trong ngày</span>
                                <span class="info-box-number">{!! $analytics['today'] !!}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row">
                    @foreach($analytics['category'] as $category=>$values)
                        <div class="col-lg-4 col-xs-6">
                            <div class="info-box bg-green">
                                <span class="info-box-icon"><i class="ion ion-ios-cart-outline"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{!! $category !!}</span>
                                    <span class="info-box-number"> {!! number_format($values['price']) !!} ¥</span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 100%"></div>
                                    </div>
                                    <span class="progress-description">
                                   {!! $values['count'] !!} đơn
                                  </span>
                                 </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @if(Auth::user()->IsAcl("dashboard:all"))

    <div class="box box-solid">
        <div class="box-header">
            <i class="fa fa-th"></i>
            <h3 class="box-title">{!! z_language('Thông kê đơn') !!}</h3>
            <div class="box-tools pull-right">
                 <button type="button" class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button> &nbsp;
                 <button type="button" class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body border-radius-none">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>{!! z_language("Công ty") !!}:</label>
                        <div class="input-group1">
                            <select id="conpany" class="form-control" onchange="charts_line()">
                                <option value="">{!! z_language("Tổng") !!}</option>
                                @foreach($analytics['category'] as $category=>$values)
                                    <option value="{!! $category !!}">{!! $category !!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>{!! z_language("Ctv Admin") !!}:</label>
                        <div>
                            @php  $roles = DB::table('admin')->get()->keyBy('id'); @endphp
                            <select id="user_id_line" class="form-control" onchange="charts_line()">
                                <option value="{!! base64_encode(''); !!}">{!! z_language("Tổng") !!}</option>
                                @foreach($roles as $values)
                                    <option value="{!! base64_encode($values->id) !!}">{!! $values->username !!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>{!! z_language("Ngày bắt đầu ") !!}:</label>
                        <div class="form-group">
                            <div class="form-group">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="datepicker_start" value="{!! date("d/m/Y", strtotime("first day of this month")) !!}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>{!! z_language("Ngày kết thúc ") !!}:</label>
                        <div class="form-group">
                            <div class="form-group">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="datepicker_end" value="{!! date("d/m/Y", strtotime("last day of this month")) !!}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>{!! z_language("Tháng") !!}:</label>
                        <div>
                            <select id="month_change" class="form-control" onchange="charts_line()">
                                <option value="0">{!! z_language("Start - End") !!}</option>
                                @for($i=1 ; $i<13 ; $i++)
                                    <option value="{!! $i !!}">Tháng {!! $i !!}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>{!! z_language("Kiểu Ngày") !!}:</label>
                        <div class="input-group">
                            <input checked name="type" type="radio" value="day" onchange="charts_line()"> {!! z_language('Ngày') !!}
                            <input name="type" type="radio" value="week" onchange="charts_line()"> {!! z_language('Tuần') !!}
                            <input name="type" type="radio" value="month" onchange="charts_line()"> {!! z_language('Tháng') !!}
                            <input name="type" type="radio" value="year" onchange="charts_line()"> {!! z_language('Năm') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary" onclick="charts_line(true)">Xuất</button>
                </div>
            </div>
            <div class="chart" id="line-chart-reward_free" style="height: 250px;"></div>
        </div>
    </div>

    <div class="box box-primary">

        <div class="box-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>{!! z_language("Công ty") !!}:</label>
                        <div class="input-group">
                            <select id="circle_conpany" class="form-control" onchange="charts_circle(this)">
                                <option value="">{!! z_language("Tổng") !!}</option>
                                @foreach($analytics['category'] as $category=>$values)
                                    <option value="{!! $category !!}">{!! $category !!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>{!! z_language("Kiểu Ngày") !!}:</label>
                        <div class="input-group">
                            <input  name="circle_type" type="radio" value="week" onchange="charts_circle()"> {!! z_language('Tuần') !!}
                            <input checked name="circle_type" type="radio" value="month" onchange="charts_circle()"> {!! z_language('Tháng') !!}
                            <input name="circle_type" type="radio" value="year" onchange="charts_circle()"> {!! z_language('Năm') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div id="donut-chart" style="height: 600px;"></div>
        </div>
        <!-- /.box-body-->
    </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Xuất Excel</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <form action="" id="formAction">
                    <table class="table">
                        <tr>
                            <td>
                                <select name="company" class="form-control">
                                    @foreach($analytics['category'] as $category=>$values)
                                        <option value="{!! $category !!}">{!! $category !!}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                @php $month = date('m'); @endphp
                                <select name="month" class="form-control">
                                    <option value="0">Chọn tháng</option>
                                    @foreach(["01","02","03","04","05","06","07","08","09","10","11","12"] as $m)
                                        <option {!! $m == $month ?"selected":""!!} value="{!! $m<0?'0'.$m:$m !!}">Tháng {!! $m<0?'0'.$m:$m !!}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                @php $date = date('Y'); @endphp
                                <select name="year" class="form-control">
                                    <option value="">Chọn Năm</option>
                                    @for($i=10;$i>0;$i--)
                                        <option value="{!! date("Y", strtotime("+".$i." year")) !!}">Năm {!! date("Y", strtotime("-".$i." year")) !!}</option>
                                    @endfor
                                    @for($i=0;$i<=10;$i++)
                                        @php $d = date("Y", strtotime("+".$i." year")); @endphp;
                                        <option {!! $date == $d ?"selected":""!!} value="{!! $d !!}">Năm {!! $d !!}</option>
                                    @endfor
                                </select>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-block" type="button" id="action"> Hành động </button>
                            </td>
                        </tr>
                    </table>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('links')
    <link rel="stylesheet" href="{{ asset('module/admin/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('module/admin/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('module/admin/assets/moment.min.js') }}"></script>
    <script src="{{ asset('module/admin/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>


    <script src="{{ asset('module/admin/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>


    @if(Auth::user()->IsAcl("dashboard:all"))

    <link rel="stylesheet" href="{{ asset('/module/shop-ja/assets/morris.js/morris.css') }}">
    <script src="{{ asset('/module/shop-ja/assets/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('/module/shop-ja/assets/morris.js/morris.min.js') }}"></script>

    <!-- FLOT CHARTS -->
    <script src="{{ asset('module/admin/bower_components/Flot/jquery.flot.js') }}"></script>
    <!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
    <script src="{{ asset('module/admin/bower_components/Flot/jquery.flot.resize.js') }}"></script>
    <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
    <script src="{{ asset('module/admin/bower_components/Flot/jquery.flot.pie.js') }}"></script>
    <!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
    <script src="{{ asset('module/admin/bower_components/Flot/jquery.flot.categories.js') }}"></script>

    <script>
        @php
            $data_date_company = [
                'KOGYJA'=>[
                   'start'=>date("d/m/Y", strtotime("first day of this month")),
                   'end'=>date("d/m/Y", strtotime("last day of this month"))
                ],
                'KURICHIKU'=>[
                   'start'=>date("d/m/Y", strtotime("first day of this month")),
                   'end'=>date("d/m/Y", strtotime("last day of this month"))
                ],
                'YAMADA'=>[
                   'start'=>date("d/m/Y", strtotime("first day of this month")),
                   'end'=>date("d/m/Y", strtotime("last day of this month"))
                ],
                'Cocolala'=>[
                   'start'=>date("d/m/Y", strtotime("first day of this month")),
                   'end'=>date("d/m/Y", strtotime("last day of this month"))
                ],
                'FUKUI'=>[
                   'start'=>date("d/m/Y", strtotime("first day of this month")),
                   'end'=>date("d/m/Y", strtotime("last day of this month"))
                ],
                'OHGA'=>[
                   'start'=>date("d/m/Y", strtotime("first day of this month")),
                   'end'=>date("d/m/Y", strtotime("last day of this month"))
                ],
                 'AMAZON'=>[
                   'start'=>date("d/m/Y", strtotime("first day of this month")),
                   'end'=>date("d/m/Y", strtotime("last day of this month"))
                ],
            ];
        @endphp
        let dataConpany = {!! json_encode($data_date_company) !!};

        function labelFormatter(label, series) {
            return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
                + label
                + '<br>'
                + Math.round(series.percent) + '%</div>'
        }

        function charts_line(export_excel) {

            let reservation = $("#reservation").val();
            let dates = reservation.split("-");

            for(let i=0;i<dates.length;i++){
                dates[i] = dates[i].trim().split("/");
            }

            let user_id = $("#user_id_line").val();
            let conpany = $("#conpany").val();




            let month = parseInt($("#month_change").val());

            if(month > 0){
                let emonth = month > 9 ?month:'0'+month;
                let smonth = month-1 > 9 ?month-1:'0'+(month-1);
                if(conpany === "KOGYJA" || conpany === "KURICHIKU"){
                    $("#datepicker_start").val('01/'+emonth+'/{!! date('Y') !!}');
                    $("#datepicker_end").val('31/'+emonth+'/{!! date('Y') !!}');
                }else{

                    if(month === 1){
                        $("#datepicker_start").val('21/'+(12)+'/{!! date('Y', strtotime('-1 years')) !!}');
                    }else{
                        $("#datepicker_start").val('21/'+(smonth)+'/{!! date('Y') !!}');
                    }
                    $("#datepicker_end").val('20/'+emonth+'/{!! date('Y') !!}');
                }
            }
            let date_start = $("#datepicker_start").val().split("/");
            let date_end = $("#datepicker_end").val().split("/");
            $.ajax({
                type: "POST",
                url:'{!! route('backend:dashboard:analytics') !!}',
                data: {
                    date_start:date_start,
                    date_end:date_end,
                    user_id:user_id,
                    month:0,
                    conpany:conpany,
                    type:$('input[name=type]:checked').val(),
                    export:export_excel === true,
                    act:"line"
                },
                success: function (datas) {
                    $("#line-chart-reward_free").empty();

                    new Morris.Line({
                        element: 'line-chart-reward_free',
                        // resize: true,
                        data:  Object.values(datas.lists),
                        xkey: datas.xkey,
                        ykeys: ["count","rate"],
                        labels: ["Tổng đơn","lợi nhuận"],
                        lineColors: ['#00c0ef', '#00a65a'],
                        parseTime: false,
                        lineWidth: 1,
                        hideHover: 'auto',
                        gridTextColor: '#000000',
                        gridStrokeWidth: 0.4,
                        pointSize: 5,
                        pointStrokeColors: ['#efefef'],
                        gridLineColor: '#efefef',
                        gridTextFamily: 'Open Sans',
                        gridTextSize: 10,
                        xLabelFormat: function (x) {
                            return x.src[datas.xkey];
                        }
                        // dateFormat: function(x) {
                        //     var month = months[new Date(x).getMonth()];
                        //     return month;
                        // },
                    });
                    if(datas.hasOwnProperty('link')){
                        window.location.replace(datas.link);
                    }
                },
            });
        }


        function ChangeAction(self){
            let val  = $(self).val();
            let reservation = $("#reservation").val();

            let url = '{!! route('backend:dashboard:list') !!}';

            let dates = reservation.split("-");

            for(let i=0;i<dates.length;i++){
                dates[i] = dates[i].trim().split("/");
            }

            $.ajax({
                type: "GET",
                url:url+"?id="+val,
                data: {
                    date_start:dates[0][2]+'-'+dates[0][0]+'-'+dates[0][1],
                    date_end:dates[1][2]+'-'+dates[1][0]+'-'+dates[1][1],
                },
                success: function (data) {
                    let content = $(data.views.content);
                    let result_analytics = content.find('.result-analytics');

                    $(".result-analytics").html(result_analytics.html());
                },
            });
        }
        function charts_circle() {

            let reservation = $("#reservation").val();
            let dates = reservation.split("-");

            for(let i=0;i<dates.length;i++){
                dates[i] = dates[i].trim().split("/");
            }
            let conpany = $("#circle_conpany").val();
            $.ajax({
                type: "POST",
                url:'{!! route('backend:dashboard:analytics') !!}',
                data: {
                    date_start:dates[0][2]+'-'+dates[0][0]+'-'+dates[0][1],
                    date_end:dates[1][2]+'-'+dates[1][0]+'-'+dates[1][1],
                    conpany:conpany,
                    type:$('input[name=circle_type]:checked').val(),
                    act:"circle"
                },
                success: function (datas) {

                    var donutData = [
                        // { label: 'Series2', data: 30,  },
                        // { label: 'Series3', data: 20,  },
                        // { label: 'Series4', data: 50,  }
                    ];
                    for(let key in datas.lists){
                        donutData.push(
                            { label:  datas.lists[key].name, data: datas.lists[key].count/datas.totals }
                        );
                    }
                    if(donutData.length == 0){
                        donutData.push(
                            { label:  "{!! z_language("Trống") !!}", data: 100 }
                        );
                    }
                    $.plot('#donut-chart', donutData, {
                        series: {
                            pie: {
                                show       : true,
                                radius     : 1,
                                innerRadius: 0.5,
                                label      : {
                                    show     : true,
                                    radius   : 2 / 3,
                                    formatter: labelFormatter,
                                    threshold: 0.1
                                }

                            }
                        },
                        legend: {
                            show: false
                        }
                    })
                },
            });
        }
        $(document).ready(function () {
            charts_line();
            charts_circle();


        });
        </script>
        @endif
        <script>
        $('.daterange').daterangepicker({
            ranges   : {
                '{!! z_language('Hôm nay') !!}'       : [moment(), moment()],
                '{!! z_language('Một năm') !!}'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '{!! z_language('7 ngày qua') !!}' : [moment().subtract(6, 'days'), moment()],
                '{!! z_language('30 ngày qua') !!}': [moment().subtract(29, 'days'), moment()],
                '{!! z_language('Tháng này') !!}'  : [moment().startOf('month'), moment().endOf('month')],
                '{!! z_language('Tháng trước') !!}'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate  : moment(),

            locale: {
                format: 'MMMM D, YYYY'
            }
        }, function (start, end) {
           // window.alert('You chose: ' + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $("#reservation").val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            $.ajax({
                type: "GET",
                data: {
                    date_start:start.format('YYYY-MM-DD'),
                    date_end:end.format('YYYY-MM-DD'),
                },
                success: function (data) {
                    let content = $(data.views.content);
                    let result_analytics = content.find('.result-analytics');
                    $(".result-analytics").html(result_analytics.html());
                },
            });
        });
        $('#reservation').daterangepicker({
            //singleDatePicker: true,
            //showDropdowns: true
        }, function(start, end, label) {

            let url = '{!! route('backend:dashboard:list') !!}';
            let val = $("#user_id").val();

            $.ajax({
                type: "GET",
                url:url+"?id="+val,
                data: {
                    date_start:start.format('YYYY-MM-DD'),
                    date_end:end.format('YYYY-MM-DD'),
                },
                success: function (data) {
                    let content = $(data.views.content);
                    let result_analytics = content.find('.result-analytics');
                    $(".result-analytics").html(result_analytics.html());
                },
            });
        });
        $(document).ready(function () {

            var $datepicker_start = $('#datepicker_start').datepicker({
                autoclose: true,
                format: 'dd/mm/yyyy',
                onSelect: function() {
                    charts_line();
                }
            }).on('changeDate', function(ev){
               $("#month_change").val("0");
                charts_line();
            });
            $datepicker_start.datepicker('setDate', '{!! date("d/m/Y", strtotime("first day of this month")) !!}');

            var $datepicker_end = $('#datepicker_end').datepicker({
                autoclose: true,
                format: 'dd/mm/yyyy',
                onSelect: function() {
                    charts_line();
                }
            }).on('changeDate', function(ev){
               $("#month_change").val("0");
                charts_line();
            });
            $datepicker_end.datepicker('setDate', '{!! date("d/m/Y", strtotime("last day of this month")) !!}');
        });
        $(document).ready(function () {

            $("#action").click(function () {
                let formAction = $("#formAction").zoe_inputs('get');
                let val = $("#user_id").val();
                formAction.user_id = val;
                $.ajax({
                    type: "POST",
                    url: "{!! route('backend:dashboard:export') !!}",
                    data: formAction,
                    success: function (data) {

                        if(data.hasOwnProperty('link')){
                            window.location.href = data.link
                        }
                    },
                    error: function (xhr, error) {

                    }
                });
            });
        });
    </script>

@endpush
