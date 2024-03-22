<div class="col-md-9">
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
            @if(isset($model))
                {!! Form::model($model, ['method' => 'POST','route' => ['backend:announce:store'],'id'=>'form_store']) !!}
                {!! Form::hidden('id') !!}
            @else
                {!! Form::open(['method' => 'POST','route' => ['backend:announce:store'],'id'=>'form_store']) !!}
            @endif

            <table class="table table-borderless">
                <tbody>
                <tr>
                    <td>
                        {!! Form::label('id_title', 'Tiều đề trang', ['class' => 'title']) !!}
                        {!! Form::text('title',null, ['class' => 'form-control','placeholder'=>'Tiều đề trang']) !!}
                    </td>
                </tr>

                @php $type = isset($model)?Form::value('type',1):1;   @endphp
                <tr>
                    <td>
                        {!! Form::label('type', 'Trạng thái', ['class' => 'description']) !!}<BR>
                        {!! Form::radio('type', '1' , $type == 1) !!} Tất cả
                        {!! Form::radio('type', '2',$type == 2) !!} Quản trị
                        {!! Form::radio('type', '3',$type == 3) !!} Nhóm quyền
                        {!! Form::hidden('action_id',null, ['id' => 'action_id']) !!}
                        @php $action_id = isset($model)?$model->action_id:0 @endphp
                    </td>
                </tr>
                <tr id="type_2" style="{!! $type == 2 ?"":"display:none"  !!}">
                    <td>
                        {!! Form::label('user', 'Quản trị', ['class' => 'id']) !!}
                        <select class="form-control" id="role_id">
                            @foreach($roles as $role)
                                <option {!! $action_id ==$role->id ?"selected":""  !!} value="{!! $role->id !!}">{!! $role->name !!}</option>
                            @endforeach
                        </select>

                    </td>
                </tr>
                <tr id="type_3" style="{!! $type == 3 ?"":"display:none"  !!}">
                    <td>
                        {!! Form::label('role', 'Nhóm quyền', ['class' => 'id']) !!}
                        <select class="form-control" id="admin_id">
                            @foreach($admins as $admin)
                                <option {!! $action_id ==$admin->id ?"selected":""  !!} value="{!! $admin->id !!}">{!! $admin->username !!}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        {!! Form::label('message', 'Nội dung', ['class' => 'message']) !!}
                        {!! Form::textarea('message',null, ['class' => 'form-control','placeholder'=>'Nội dung','cols'=>5,'rows'=>5]) !!}
                    </td>
                </tr>
                <tr>
                    <td>
                        {!! Form::label('date_start', 'Thời gian bắt đầu', ['class' => 'title']) !!}
                        {!! Form::text('date_start',null, ['class' => 'form-control datepicker1','placeholder'=>'Thời gian bắt đầu']) !!}
                    </td>
                </tr>
                <tr>
                    <td>
                        {!! Form::label('date_end', 'Thời gian kết thúc', ['class' => 'title']) !!}
                        {!! Form::text('date_end',null, ['class' => 'form-control datepicker2','placeholder'=>'Thời gian kết thúc']) !!}
                    </td>
                </tr>
                <tr>
                    <td>
                        {!! Form::label('id_status', 'Status', ['class' => 'description']) !!}
                        {!! Form::radio('status', '1' , true) !!} Yes
                        {!! Form::radio('status', '0',false) !!} No
                    </td>
                </tr>

                </tbody>
            </table>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@section('extra-script')
    <script src="{{ asset('module/admin/assets/moment.min.js') }}"></script>
    <script src="{{ asset('module/admin/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('module/admin/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <script>
        let stringDate = '{!! date('Y-m-d') !!}';
        let  date = moment(stringDate);
    </script>
    <script>
        $(document).ready(function () {
            $datepicker1 = $('.datepicker1').datepicker({
                autoclose: true,
            });
            @if(isset($model))
                let  date_start = moment('{!! $model->date_start !!}');
                $datepicker1.datepicker('setDate', new Date(date_start.format('MM/DD/YYYY')));
            @else
                $datepicker1.datepicker('setDate', new Date(date.format()));
            @endif
            $datepicker2 = $('.datepicker2').datepicker({
                autoclose: true,
            });
            @if(isset($model))
                let  date_end = moment('{!! $model->date_end !!}');
                $datepicker2.datepicker('setDate', new Date(date_end.format('MM/DD/YYYY')));
            @else
                $datepicker2.datepicker('setDate', new Date(date.add(1, 'days').format('MM/DD/YYYY')));
            @endif
            $('[name=\"type\"]').change(function(){
                let val = parseInt($(this).val());
                if(val === 2){
                    $("#type_2").show();
                    $("#type_3").hide();
                }else if(val === 3){
                    $("#type_3").show();
                    $("#type_2").hide();
                }else{
                    $("#type_3").hide();
                    $("#type_2").hide();
                }
            });
            $('#admin_id,#role_id').change(function(){
                $("#action_id").val($(this).val());
            });
        });
    </script>
@endsection