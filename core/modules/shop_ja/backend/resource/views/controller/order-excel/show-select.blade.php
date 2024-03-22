@section('content-header')
    <h1>
        {!! @z_language(["Chức năng quản xuất Excel"]) !!} {!! $date !!}

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
                    <th style="width: 10px">#</th>
                    <th>
                        <!-- Date -->
                        <label>Ngày Xem:</label>
                        <div class="form-group">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="datepicker">
                            </div>
                            <!-- /.input group -->
                        </div>
                        <!-- /.form group -->
                    </th>
                    <th>
                        <label>Công ty:</label>
                        <select id="company" name="company" class="form-control">
                            @foreach($compays as $compay)
                            <option value="{{$compay['name']}}">{{$compay['name']}}</option>
                            @endforeach
                        </select>
                    </th>
                    <th>
                        <label>Giờ:</label>
                        <div class="input-group">
                            <input type="text" class="form-control timepicker" value="11:00" id="timepicker">

                            <div class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                    </th>
                    <th>
                        <label>Hành động:</label><BR>
                        <input type="radio" name="action" value="1" > Mở Khóa Xuất
                        <input type="radio" name="action" value="2"> Khóa Xuất
                        <input type="radio" name="action" value="3" checked>  Xem
                    </th>
                    <th>
                        <BR>
                        <button class="btn btn-primary btn-block" type="button" id="view"> Hành động </button>
                    </th>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
@endsection
@section('extra-script')

    <script src="{{ asset('module/admin/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('module/admin/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

    <script src="{{ asset('module/admin/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('module/admin/plugins/timepicker/bootstrap-timepicker.min.css') }}">

    <script>
       $(document).ready(function () {
           $datepicker = $('#datepicker').datepicker({
               autoclose: true,
               format: 'dd/mm/yyyy',
           });
           $datepicker.datepicker('setDate', new Date());
           $("#view").click(function () {
                console.log(1);

                let data = {
                    dateview:$("#datepicker").val(),
                    name:$("#company").val(),
                    time:$("#timepicker").val(),
                    action: $("input[name='action']:checked").val(),
                    view:true
                };
                $.ajax({
                   type: "POST",

                   data: data,
                   success: function (data) {
                       console.log(data);
                       if(data.hasOwnProperty('link')){
                           window.location.replace(data.link);
                       }
                   }
                });
           });
          $timepicker = $('#timepicker').timepicker({
                showInputs: false,
                showMeridian: false,
                minuteStep: 1,
           });

       });
    </script>
@endsection
