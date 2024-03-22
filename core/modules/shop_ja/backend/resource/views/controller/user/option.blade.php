@section('content-header')
    <h1>
        {!! @z_language(["Cấu hình"]) !!}
        <button type="button" class="btn btn-default btn-md" onclick="Save()"> Save </button>
    </h1>
@endsection
@section('content')
    @breadcrumb()@endbreadcrumb
    @php
        $category =  get_category_type("shop-ja:product:category");
       $a = new stdClass();
    $a->name = "KURICHIKU1";
    $category[] = $a;
    @endphp
    <div class="box box-default box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Thông báo</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div></div>
        <div class="box-body" style="">

                @php
                    $keys = array (
                    'status' => 'Status',
                    'image' => 'Image',
                    'timeCreate' => 'ngày đặt hàng',
                    'payMethod' => 'Phương thức thanh toán',
                    'phone' => 'Số điện thoại',
                    'zipcode' => 'Mã bưu điện',
                    'province' => 'Tỉnh/TP',
                    'address' => 'Địa chỉ giao hàng',
                    'fullname' => 'Họ tên người nhận',
                    'product_id' => 'Mã SP',
                    'product_name' => 'Tên SP',
                    'count' => 'SL',
                    'price' => 'Giá nhập',
                    'price_buy' => 'Giá bán',
                    'order_date' => 'Ngày nhận',
                    'order_hours' => 'Giờ nhận',
                    'order_ship' => 'Phí ship',
                    'order_total_price' => 'Tổng giá nhập',
                    'price_buy_sale' => 'Tăng Giảm',
                    'order_total_price_buy' => 'Total Bán',
                    'order_ship_cou' => 'Phí giao hàng',
                    'order_price' => 'Lợi nhuận',
                    'order_tracking' => 'Mã tracking',
                    'order_link' => 'Đường dẫn',
                    'order_info' => ' Thông tin chuyển khoản',
                    'one_address' => 'Cùng địa chỉ',
                    'id' => 'ID',
                    'session_id' => 'SessionId',
                    'export' => 'Export',
                    'token' => 'token',
                    'position' => 'position',
                    'admin' => 'Người đăng',
                    'type' => 'Kiểu',
                  )
                @endphp
                <form action="" id="fromOption">
                    <table class="table table-bordered">
                    <tbody>
                    @foreach($category as $value)
                        <tr class="text-center">
                            <th width="150">
                                <label for="text" class="control-label">{!! $value->name !!}</label> &nbsp;
                            </th>
                            <th>
                                <table>
                                    <tr>
                                        <td> Trạng thái </td>
                                        <td> <input type="checkbox" name="{!! $value->name !!}.status" value="1"> </td>
                                    </tr>
                                    <tr>
                                        <td>Chiết Khấu</td>
                                        <td><input type="text" class="form-control" name="{!! $value->name !!}.rate"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <table class="table table-bordered">
                                                @php $i=0; $close = false; @endphp
                                                @foreach($keys as $key=>$val)
                                                    @if($i++ == 0)
                                                        @php $close = true; @endphp
                                                        <tr>
                                                            @endif
                                                            <td> <input type="checkbox" name="{!! $value->name !!}.colums.{!! $key !!}" value="1"> {!! z_language($val) !!}</td>
                                                            @if($i==5)
                                                                @php $i=0; $close = false; @endphp
                                                        </tr>
                                                        @endif
                                                        @endforeach
                                                        @if($close)</tr>@endif
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function Save() {

            let oke =  confirm('Bạn muốn lưu');

            if(oke){
                var form = $("#fromOption");
                var data = form.zoe_inputs('get');
                console.log(data);
                form.loading({circles: 3, overlay: true, width: "5em", top: "35%", left: "50%"});
                $.ajax({
                    type: 'POST',
                    data: {
                        data: data,
                        act:"save"
                    },
                    success: function (data) {
                        console.log(data);
                        form.loading({destroy: true});
                    }
                });
            }
        }
        $(document).ready(function () {
            $("#fromOption").zoe_inputs('set', {!! json_encode($options,JSON_UNESCAPED_UNICODE) !!});
        });
    </script>
@endpush
