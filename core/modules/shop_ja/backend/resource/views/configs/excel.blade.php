@php
    $category =  get_category_type("shop-ja:product:category");


    $keys =  [
            ['timeCreate',3.29,'ngày đặt hàng'],
            ['payMethod',6.57,'Phương thức thanh toán'],//B
            ['phone',10.86,'Số điện thoại'],//C
            ['zipcode',6.57, 'Mã bưu điện'],//D
            ['province',5.14,'Tỉnh/TP'],//E
            ['address',28.71,'Địa chỉ giao hàng'],//F
            ['fullname',14.71,'Họ tên người nhận'],//G
            ['product_id',7,'Mã SP'],//H
            ['product_name',18.57,'Tên SP'],//I
            ['price',4.57, 'Giá nhập'],//J
            ['count',2.86, 'SL'],//K
            ['order_date',9,'Ngày nhận'],//L
            ['order_hours',10, 'Giờ nhận'],//M
            ['order_ship',4.71,'Phí ship'],//N
            ['order_total_price',6.43, 'Tổng giá nhập'],//O
            ['order_total_price_buy',8,'Total Bán'],//P
            ['order_ship_cou',3.43,'Phí giao hàng'],//Q
            ['order_price',5.43,'Lợi nhuận'],//R
            ['order_tracking',4.86,'Mã tracking'],//S
            ['order_info',8.57,'Thông tin chuyển khoản'],//T
            ['order_link',25,'Đường dẫn'],//U
            ['total_count',15,'Tổng Size'],//U
        ];
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
                                <td>{!! z_language($val[2]) !!} <input type="text" name="excel_width.{!! $value->name !!}.{!! $val[0] !!}" value="{!! $val[1] !!}"> </td>
                                @if($i==3)
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