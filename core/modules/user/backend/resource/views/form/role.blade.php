@if(isset($model))
    {!! Form::model($model, ['method' => 'POST','route' => ['backend:user:role:store'],'id'=>'form_store']) !!}
    {!! Form::hidden('id') !!}
@else
    {!! Form::open(['method' => 'POST','route' => ['backend:user:role:store'],'id'=>'form_store']) !!}
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
                    <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"> {!! @z_language(["Thông tin tài nhóm quyền"]) !!} </a></li>

                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <td>
                                    {!! Form::label('name', z_language('Tên'), ['class' => 'name']) !!}
                                    {!! Form::text('name',null, ['class' => 'form-control','placeholder'=>z_language('Tên')]) !!}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {!! Form::label('guard_name', z_language('Loại quyền'), ['class' => 'type']) !!} &nbsp;
                                    {!! Form::radio('guard_name', 'backend' , true) !!} Quản trị
                                    {!! Form::radio('guard_name', 'web',false) !!} Người dùng
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {!! Form::label('status', z_language('Trạng thái'), ['class' => 'status']) !!} &nbsp;
                                    {!! Form::radio('status', '1' , true) !!} {!! z_language('Đồng ý') !!}
                                    {!! Form::radio('status', '0',false) !!} {!! z_language('Không') !!}
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

@endsection
