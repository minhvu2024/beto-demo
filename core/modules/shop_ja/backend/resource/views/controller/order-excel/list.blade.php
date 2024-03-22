@section('content-header')
    <h1>
        &starf; {!! @z_language(["Chức năng quản lý đơn hàng"]) !!}
        <small>it all starts here</small>
        <a  onclick="open_edit('now');"
           class="btn btn-default btn-md"><i class="fa fa-fw fa-plus"></i>
            {!! @z_language(["Đơn ngày :DATETIME",['DATETIME'=>date('d-m-Y')]]) !!}
        </a>
        <a  onclick="open_edit('next');"
           class="btn btn-default btn-md"><i class="fa fa-fw fa-plus"></i>
            {!! @z_language(["Đơn ngày :DATETIME",['DATETIME'=>date('d-m-Y',strtotime('+1 day'))]]) !!}
        </a>
        <a  onclick="open_edit('next_2');"
            class="btn btn-default btn-md"><i class="fa fa-fw fa-plus"></i>
            {!! @z_language(["Đơn ngày :DATETIME",['DATETIME'=>date('d-m-Y',strtotime('+2 day'))]]) !!}
        </a>
        @btn_option(["config"=>['name'=>'module:shop_ja:order:excel']])
        @slot('label')
            {{@z_language(["Cấu hình"])}}
        @endslot
        @slot('header')
            {{@z_language(["Cấu hình"])}}
        @endslot
        @endbtn_option
    </h1>
@endsection
@section('content')
    @breadcrumb()@endbreadcrumb
    @component('backend::layout.component.list',['name'=>'module:shop_ja:order:excel','models'=>$models,'callback'=>$callback])
        @slot("tool")
            {{--<div class="row">--}}
                {{--<div class="col-md-3">--}}
                    {{--<div class="form-group">--}}
                        {{--<label>Thời gian:</label>--}}
                        {{--<div class="input-group">--}}
                            {{--<div class="input-group-addon">--}}
                                {{--<i class="fa fa-calendar"></i>--}}
                            {{--</div>--}}
                            {{--<input type="text" class="form-control pull-right" id="reservation">--}}
                        {{--</div>--}}
                        {{--<!-- /.input group -->--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="result-analytics">--}}
                {{--<div class="row">--}}
                    {{--<div class="col-md-2 col-sm-6 col-xs-12">--}}
                        {{--<div class="info-box">--}}
                            {{--<span class="info-box-icon bg-aqua"><i class="ion ion-ios-cart-outline"></i></span>--}}

                            {{--<div class="info-box-content">--}}
                                {{--<span class="info-box-text">Tổng</span>--}}
                                {{--<span class="info-box-number">{!! $analytics['total'] !!}<small></small></span>--}}
                            {{--</div>--}}
                            {{--<!-- /.info-box-content -->--}}
                        {{--</div>--}}
                        {{--<!-- /.info-box -->--}}
                    {{--</div>--}}
                    {{--<div class="col-md-2 col-sm-6 col-xs-12">--}}
                        {{--<div class="info-box">--}}
                            {{--<span class="info-box-icon bg-aqua"><i class="ion ion-pricetag"></i></span>--}}

                            {{--<div class="info-box-content">--}}
                                {{--<span class="info-box-text">Lợi Nhuận</span>--}}
                                {{--<span class="info-box-number">{!! number_format($analytics['price']) !!}<small></small></span>--}}
                            {{--</div>--}}
                            {{--<!-- /.info-box-content -->--}}
                        {{--</div>--}}
                        {{--<!-- /.info-box -->--}}
                    {{--</div>--}}
                    {{--<!-- /.col -->--}}
                    {{--<div class="col-md-2 col-sm-6 col-xs-12">--}}
                        {{--<div class="info-box">--}}
                            {{--<span class="info-box-icon bg-red"><i class="ion ion-ios-cart-outline"></i></span>--}}

                            {{--<div class="info-box-content">--}}
                                {{--<span class="info-box-text">Thành công</span>--}}
                                {{--<span class="info-box-number">{!! $analytics['success'] !!}</span>--}}
                            {{--</div>--}}
                            {{--<!-- /.info-box-content -->--}}
                        {{--</div>--}}
                        {{--<!-- /.info-box -->--}}
                    {{--</div>--}}
                    {{--<!-- /.col -->--}}

                    {{--<!-- fix for small devices only -->--}}
                    {{--<div class="clearfix visible-sm-block"></div>--}}

                    {{--<div class="col-md-2 col-sm-6 col-xs-12">--}}
                        {{--<div class="info-box">--}}
                            {{--<span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>--}}

                            {{--<div class="info-box-content">--}}
                                {{--<span class="info-box-text">Hủy</span>--}}
                                {{--<span class="info-box-number">{!! $analytics['cancel'] !!}</span>--}}
                            {{--</div>--}}
                            {{--<!-- /.info-box-content -->--}}
                        {{--</div>--}}
                        {{--<!-- /.info-box -->--}}
                    {{--</div>--}}
                    {{--<!-- /.col -->--}}
                    {{--<div class="col-md-2 col-sm-6 col-xs-12">--}}
                        {{--<div class="info-box">--}}
                            {{--<span class="info-box-icon bg-yellow"><i class="ion ion-ios-cart-outline"></i></span>--}}

                            {{--<div class="info-box-content">--}}
                                {{--<span class="info-box-text">Trong ngày</span>--}}
                                {{--<span class="info-box-number">{!! $analytics['today'] !!}</span>--}}
                            {{--</div>--}}
                            {{--<!-- /.info-box-content -->--}}
                        {{--</div>--}}
                        {{--<!-- /.info-box -->--}}
                    {{--</div>--}}
                    {{--<!-- /.col -->--}}
                {{--</div>--}}
                {{--<div class="row">--}}
                    {{--@foreach($analytics['category'] as $category=>$count)--}}
                        {{--<div class="col-lg-4 col-xs-6">--}}
                            {{--<!-- small box -->--}}
                            {{--<div class="small-box bg-aqua">--}}
                                {{--<div class="inner">--}}
                                    {{--<h3>{!! $count !!}</h3>--}}

                                    {{--<p>{!! $category !!}</p>--}}
                                {{--</div>--}}
                                {{--<div class="icon">--}}
                                    {{--<i class="ion ion-bag"></i>--}}
                                {{--</div>--}}
                                {{--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                    {{--@endforeach--}}
                {{--</div>--}}
            {{--</div>--}}

            <div class="box-body">

                <div class="col-md-12" style="padding:0">
                    <div class="row">
                        <div class="col-sm-4" style="padding:0">
                            <div class="col-sm-4" style="padding:0;text-align: center;line-height: 2;">
                                <label>Code</label>
                            </div>
                            <div class="col-sm-8" style="padding:0;text-align: center;">
                                <input type="text" name="filter.code" class="form-control" id="filter.code" placeholder="Code">
                            </div>
                        </div>
                        <div class="col-sm-4" style="padding:0">
                            <div class="col-sm-4" style="padding:0;text-align: center;line-height: 2;">
                                <label>Name</label>
                            </div>
                            <div class="col-sm-8" style="padding:0;text-align: center;">
                                <input type="text" name="filter.search" class="form-control" id="filter.search"
                                       placeholder="Name">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <BR>
                        <button type="button" class="btnFilter btn btn-sm btn-primary pull-right">
                            Dữ liệu
                        </button>
                    </div>
                </div>
            </div>
{{--            @foreach(['YAMADA','KOGYJA','OHGA','FUKUI','KURICHIKU'] as $val)--}}
{{--                <a href="{!! route('backend:shop_ja:excel:'.$val) !!}">--}}
{{--                    <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">--}}
{{--                        <i class="fa fa-download"></i> {!! $val !!}--}}
{{--                    </button>--}}
{{--                </a>--}}
{{--           @endforeach--}}
        @endslot
    @endcomponent
@endsection
@push('links')
    <link rel="stylesheet" href="{{ asset('module/admin/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <style>
        .listMain .table tbody tr td.column{
            position: relative;
            padding: 5px 0px 5px 5px;
        }
        .label-text{
            text-align: center;
        }
        .listMain .company td{
            background: #dedede !important;
            padding: 5px;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('module/admin/assets/moment.min.js') }}"></script>
    <script src="{{ asset('module/admin/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script>
        function open_edit(type) {
            let date;

            if(type === "now"){
                 date = "{!! date('d-m-Y') !!}";
            }else if(type === "last"){
                date = "{!! date('d-m-Y',strtotime('-1 day')) !!}";
            }else if(type === "next_2"){
                date = "{!! date('d-m-Y',strtotime('+2 day')) !!}";
            }
            else{
                date = "{!! date('d-m-Y',strtotime('+1 day')) !!}";
            }
            let arrTimeDate = $("#sectionList .list-row .row-title");

            for(let i=0 ; i < arrTimeDate.length ; i++){
                let a = $(arrTimeDate[i]);
                let t = a.text();
                if(t === date){
                    window.location.href = a.closest('.column').find('.edit a').attr('href');
                    break;
                }
            }
        }
    </script>
@endpush
