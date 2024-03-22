@section('content-header')
    <h1>
        {!! @z_language(["Phân quyền"]) !!}
        <small>it all starts here</small>
        <button type="button" class="btn btn-default btn-md" onclick="SaveData()">{!! @z_language(["Lưu lại"]) !!}</button>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>
    </ol>
@endsection
@section('content')
    @php
        $names = [
            'list'=>"Danh sách",'ajax'=>'Xử lý','store'=>'Xử lý','create'=>'Thêm','edit'=>'Sửa','delete'=>'Xóa','permission'=>'Phân quyền','show'=>'Danh sách'
        ];
        $descriptions = app()->getConfig()->acls['descriptions'];
     @endphp
    <!-- Default box -->
    <div class="col-md-10">
        <div class="box ">
            <div class="box-body no-padding">

                @if(isset($global_permissions->data[$guard]))
                    @foreach($global_permissions->data as $key=>$permissions)
                        <form action="" id="form_{!! $key !!}">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <th>{!! @z_language(["Giá trị Quyền"]) !!}</th>
                                    <th></th>
                                </tr>
                                @foreach($permissions as $aliases=>$permission)
                                    <tr>
                                        <td>
                                            {!! Form::checkbox($aliases, '1' , isset($user_permissions[$aliases])) !!}
                                            {!! z_language($aliases) !!}
                                        </td>
                                        <td>
                                            <table class="table table-bordered">
                                                <tr>
                                                {{--@foreach($permission as $k=>$val)--}}
                                                    {{--@if(isset($global_permissions->aliases[$val]))--}}
                                                            {{--@php $a = explode(":",$val); $k = $a[count($a)-1]; @endphp--}}
                                                            {{--<td> <span title="{!! $val !!}">{!! isset($descriptions[$val])?$descriptions[$val]:(isset($names[$k])?$names[$k]:$k) !!}</span></td>--}}

                                                    {{--@endif--}}
                                                {{--@endforeach--}}
                                                    </tr>
                                            </table>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </form>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script !src="">
      function SaveData() {
          let form = $("#form_backend");
          let data = form.zoe_inputs('get');
          console.log(data);
          form.loading({circles: 3, overlay: true, width: "5em", top: "35%", left: "50%"});
          $.ajax({
              type: 'POST',
              url: '{!! route('backend:user:role:permission',["id"=>$id,$guard]) !!}',
              data: {
                  data: data
              },
              success: function (data) {
                  console.log(data);
                  form.loading({destroy: true});
              }
          });
      }
    </script>
@endpush
