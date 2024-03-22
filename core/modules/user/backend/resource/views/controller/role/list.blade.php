@section('content-header')
    <h1>
        {!! @z_language(["Manager role"]) !!}
        <small>it all starts here</small>
        <a href="{{route('backend:user:role:create')}}"
           class="btn btn-default btn-md"><i class="fa fa-fw fa-plus"></i>{!! @z_language(["Thêm mới"]) !!}
        </a>
    </h1>
@endsection
@section('content')
    <!-- Default box -->
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">{!! @z_language(["Danh sách quyền"]) !!}</h3>
            <div class="box-tools">

            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
            <table class="table">
                <tbody>
                <tr>
                    <th>{!! @z_language(["ID"]) !!}</th>
                    <th>{!! @z_language(["Tên"]) !!}</th>
                    <th>{!! @z_language(["Loại quyền"]) !!}</th>
                    <th>{!! @z_language(["Ngày tạo"]) !!}</th>
                    <th>{!! @z_language(["Ngày sửa"]) !!} </th>
                </tr>
                @foreach($lists as $list)
                    <tr>
                        <td>{{$list->id}}.</td>
                        <td>{{$list->name}}</td>
                        <td>{{$list->guard_name}}</td>
                        <td>{{$list->created_at}}</td>
                        <td>{{$list->updated_at}}</td>
                        <td>
                            <a href="{!! route('backend:user:role:permission',["id"=>$list->id,'guard'=>$list->guard_name]) !!}" class="btn btn-primary">{!! z_language('Xét quyền') !!}</a>
                        </td>
                        <td>
                            <a href="{!! route('backend:user:role:edit',["id"=>$list->id]) !!}" class="btn btn-primary">{!! z_language('Sửa') !!}</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.box -->
@endsection
