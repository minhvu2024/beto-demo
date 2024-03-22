@php
   $category =  get_category_type("shop-ja:product:category");
   $a = new stdClass();
   $a->name = "KURICHIKU1";
   $category[] = $a;
@endphp
<table class="table table-bordered">
    <tbody>
        @foreach($category as $value)
        <tr class="text-center">
            <th width="150">
                <label for="text" class="control-label">{!! $value->name !!}</label>
            </th>

            <th width="150">
                <input type="checkbox" name="company.{!! $value->name !!}.status" value="1">
            </th>
            <th width="150">
                <input type="radio" name="company.{!! $value->name !!}.type" value="1"> Thời gian
                <input type="radio" name="company.{!! $value->name !!}.type" value="2"> Thứ
            </th>
            <td>
                <div class="col-md-6 col-xs-12">
                    <input type="text" class="form-control daterange" name="company.{!! $value->name !!}.date">
                </div>
            </td>
            <th>
                <input type="checkbox" name="company.{!! $value->name !!}.week" value="1">{!! z_language('Thứ 2') !!}
                <input type="checkbox" name="company.{!! $value->name !!}.week" value="2">{!! z_language('Thứ 3') !!}
                <input type="checkbox" name="company.{!! $value->name !!}.week" value="3">{!! z_language('Thứ 4') !!}
                <input type="checkbox" name="company.{!! $value->name !!}.week" value="4">{!! z_language('Thứ 5') !!}
                <input type="checkbox" name="company.{!! $value->name !!}.week" value="5">{!! z_language('Thứ 6') !!}
                <input type="checkbox" name="company.{!! $value->name !!}.week" value="6">{!! z_language('Thứ 7') !!}
                <input type="checkbox" name="company.{!! $value->name !!}.week" value="0">{!! z_language('Chủ nhật') !!}
            </th>
        </tr>
        @endforeach
    </tbody>
</table>
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
    'order_rate' => 'Lợi nhuận CTV',
  )
 @endphp
<table class="table table-bordered">
    <tbody>
    @foreach($category as $value)
        <tr class="text-center">
            <th width="150">
                <label for="text" class="control-label">{!! $value->name !!}</label>
            </th>
            <th>
                <table class="table table-bordered">
                    @php $i=0; $close = false; @endphp
                @foreach($keys as $key=>$val)
                    @if($i++ == 0)
                        @php $close = true; @endphp
                        <tr>
                    @endif
                     <td> <input type="checkbox" name="excel.{!! $value->name !!}.{!! $key !!}" value="1"> {!! z_language($val) !!}</td>
                    @if($i==5)
                        @php $i=0; $close = false; @endphp
                        </tr>
                    @endif
                @endforeach
                @if($close)</tr>@endif
                </table>
            </th>
        </tr>
    @endforeach
    </tbody>
</table>


@push('links')
    <link rel="stylesheet" href="{{ asset('module/admin/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('module/admin/assets/moment.min.js') }}"></script>
    <script src="{{ asset('module/admin/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.daterange').daterangepicker({
                startDate: moment().now,
                endDate  : moment().now,
            }, function (start, end) {
                window.alert('You chose: ' + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

            });
        });
     </script>
@endpush