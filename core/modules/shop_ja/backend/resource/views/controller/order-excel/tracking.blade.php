@section('content-header')
    <h1>
        &starf; {!! @z_language(["Chức năng quản lý Mã kiểm tra"]) !!}
    </h1>
@endsection
@section('content')
    <div class="nav-tabs-custom">
        @php
            $active = "";
            $counts = [];
        @endphp
        @section('tab-content')
        <div class="tab-content">
            @foreach($datas as $key=>$value)
                @php $counts[$key] = []; @endphp
            <div class="tab-pane  @php if(empty($active)){$active = $key;echo "active";} @endphp" id="tab_{!! $key !!}">
                <table class="table table-bordered">
                     <tr>
                         <th>{!! z_language('Mã') !!}</th>
                         <th>{!! z_language('Mã Đơn') !!}</th>
                         <th>{!! z_language('Hàng Chuyển phát') !!}</th>
                         <th>{!! z_language('Mã Chuyển phát') !!}</th>
                         <th>{!! z_language('Trạng thái') !!}</th>
                         <th>{!! z_language('Kết quả') !!}</th>
                         <th>{!! z_language('Thời gian cập nhật') !!}</th>
                         <th>{!! z_language('Thời gian tạo') !!}</th>
                     </tr>
                    @foreach($value as $kk=>$vv)
                        @php $counts[$key][$kk] = 0; @endphp
                        <tr>
                            <th colspan="8" class="text-center" style="background: #dedede">{!! $kk== 0 ?z_language("ĐANG XỬ LÝ"):z_language("CHỜ XỬ LÝ") !!} ({!! count($vv) !!})</th>
                        </tr>

                         @if(count($vv))
                             @php  $counts[$key][$kk] = count($vv) @endphp
                         @foreach($vv as $k=>$v)

                             <tr>
                                 <td>{!! $v->id !!}</td>
                                 <td>{!! $v->order_id !!}</td>
                                 <td>{!! $v->type !!}</td>
                                 <td>{!! $v->tracking_id !!}</td>
                                 <td {!! $v->status !!}>{!! $v->status==1?z_language('Thành công'):($v->status==2?z_language('Đang kiểm tra'):($v->status==3?z_language('Đã kiểm tra'):z_language('Chưa kiểm tra'))) !!}</td>
                                 <td>
                                     @php
                                        $data = json_decode($v->data,true);
                                     @endphp
                                     <table class="table table-bordered">
                                         <td>{!! isset($data['Date'])?$data['Date']:z_language('Không xác định') !!}</td>
                                         <td>{!! isset($data['Text'])?$data['Text']:z_language('Không xác định') !!}</td>
                                     </table>
                                 </td>
                                 <td>{!! $v->updated_at !!}</td>
                                 <td>{!! $v->created_at !!}</td>
                             </tr>
                         @endforeach
                         @else
                             <tr>
                                 <th colspan="8">{!! z_language("Danh sách trống") !!}</th>
                             </tr>
                         @endif
                    @endforeach
                </table>
            </div>
            @endforeach
        </div>
        @endsection
        @section('tab-nav')

                <ul class="nav nav-tabs">
                    @foreach($datas as $key=>$value)
                        <li @php if(!empty($active)){$active = ""; echo "class='active'";} @endphp><a href="#tab_{!! $key !!}" data-toggle="tab">{!! $key  !!} [{!! $counts[$key][0].'/'.$counts[$key][1] !!}]</a></li>
                    @endforeach
                </ul>
        @endsection
        @yield('tab-nav')
        @yield('tab-content')
        <!-- /.tab-content -->
    </div>
    <!-- nav-tabs-custom -->
    </div>
@endsection
