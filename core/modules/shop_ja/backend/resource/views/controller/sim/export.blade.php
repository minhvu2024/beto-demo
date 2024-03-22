@section('content-header')
    <h1>
        {!! @z_language(["Chức năng quản xuất Excel"]) !!}

    </h1>
@endsection
@section('content')
    @breadcrumb()@endbreadcrumb
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Xuất Excel</h3>
        </div>
        <div class="box-body">

                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th colspan="2">Thời gian hết hạn</th>
                    </tr>
                    <tr>
                        <th style="width: 80%">
                            <select id="month" class="form-control">
                                <option value="0">Hết trong tháng này</option>
                                @foreach([2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24] as $month)
                                    <option value="{!! $month-1 !!}">Hết {!! $month-1 !!} tháng </option>
                                @endforeach
                            </select>
                        </th>
                        <th>
                            <button class="btn btn-primary btn-block" type="button" id="btnExport"> Xuất File </button>
                        </th>
                    </tr>
                    </tbody>
                </table>

        </div>
    </div>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Nhập Excel</h3>
        </div>
        <div class="box-body">
            <div class="container1">
                <div class="row">
                    <div class="col-md-6 offset-6">
                        <div class="card">

                            <div class="card-body">
                                <div class="alert alert-danger print-error-msg" style="display:none">
                                    <ul></ul>
                                </div>
                                <div class="success alert alert-success" style="display:none">
                                    Upload Successfully
                                </div>
                                <form enctype="multipart/form-data" id="imageUpload">
                                    <div class="form-group">
                                        <label><strong>File Excel : </strong></label>
                                        <input type="file" name="image" class="form-control">
                                    </div>
                                    <div class="form-group text-center">
                                        <button class="btn btn-success">Đẩy lên</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            {{--<form enctype="multipart/form-data" id="imageUpload">--}}
                {{--<table class="table table-bordered">--}}
                    {{--<tbody>--}}
                    {{--<tr>--}}
                        {{--<th colspan="2">File</th>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<th style="width: 80%">--}}
                            {{--<input type="file" name="image" class="form-control">--}}
                        {{--</th>--}}
                        {{--<th>--}}
                            {{--<button class="btn btn-primary btn-block"> Nhập File </button>--}}
                        {{--</th>--}}
                    {{--</tr>--}}
                    {{--</tbody>--}}
                {{--</table>--}}
            {{--</form>--}}
        </div>
    </div>
@endsection
@section('extra-script')

    <script src="{{ asset('module/admin/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('module/admin/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

    <script src="{{ asset('module/admin/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('module/admin/plugins/timepicker/bootstrap-timepicker.min.css') }}">

    <script>

        $('#imageUpload').on('submit',(function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type:'POST',

                data:formData,
                cache:false,
                contentType: false,
                processData: false,

                complete: function(response)
                {
                    console.log(response);
                    if($.isEmptyObject(response.responseJSON.error)){
                        $('.success').show();
                        setTimeout(function(){
                            $('.success').hide();
                        }, 5000);
                        $("#results").html(response.responseJSON.html);
                    }else{
                        printErrorMsg(response.responseJSON.error);
                    }
                }

            });
        }));
        function printErrorMsg(msg){
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display','block');
            $.each( msg, function( key, value ) {
                $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
            });
        }
        $(document).ready(function () {


            $('.success').hide();
            $("#btnExport").click(function () {
                let url = '{!! route('backend:shop_ja:sim:show',['month'=>"MONTH"]) !!}';
                window.location.href =  url.replace('MONTH',$("#month").val());
            });
            // $datepicker = $('#datepicker').datepicker({
            //     autoclose: true,
            //     format: 'mm/dd/yyyy',
            // });
            // $datepicker.datepicker('setDate', new Date());
            // $("#view").click(function () {
            //     console.log(1);
            //
            //     let data = {
            //         dateview:$("#datepicker").val(),
            //         name:$("#company").val(),
            //         time:$("#timepicker").val(),
            //         action: $("input[name='action']:checked").val(),
            //         view:true
            //     };
            //     $.ajax({
            //         type: "POST",
            //
            //         data: data,
            //         success: function (data) {
            //             console.log(data);
            //             if(data.hasOwnProperty('link')){
            //                 window.location.replace(data.link);
            //             }
            //         }
            //     });
            // });
            // $timepicker = $('#timepicker').timepicker({
            //     showInputs: false,
            //     showMeridian: false,
            //     minuteStep: 1,
            // });

        });
    </script>
@endsection
