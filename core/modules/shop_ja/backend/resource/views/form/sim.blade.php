<table class="table">
    <tr>
        <td style="width: 5%"><input type="text" class="form-control" readonly id="col-row-review"></td>
        <td style="width: 95%">
            <input type="text" class="form-control" id="value-review">
            <div id="zoe-dropdown-review" style="display: none"></div>
        </td>
    </tr>
</table>

<div id="spreadsheet"></div>
@if(isset($model))
    {!! Form::model($model, ['method' => 'POST','route' => ['backend:shop_ja:sim:store'],'id'=>'form_store']) !!}
    {!! Form::hidden('id') !!}
@else
    {!! Form::open(['method' => 'POST','route' => ['backend:shop_ja:sim:store'],'id'=>'form_store']) !!}
@endif
{!! Form::close() !!}
@section('extra-script')
    <script src="{{ asset('module/shop-ja/assets/jexcel/dist/jexcel.js?v='.time()) }}"></script>
    <script src="{{ asset('module/shop-ja/assets/jsuites/dist/jsuites.js?v='.time()) }}"></script>
    <link rel="stylesheet" href="{{ asset('module/shop-ja/assets/jsuites/dist/jsuites.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('module/shop-ja/assets/jexcel/dist/jexcel.css') }}" type="text/css" />
    <script src="{{asset('module/admin/assets/bootpopup/bootpopup.js')}}"></script>
    <script src="{{ asset('module/admin/assets/moment.min.js') }}"></script>

    <script src="{{ asset('module/admin/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('module/admin/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <script>
        let stringDate = '{!! isset($date_key)?$date_key:date('Y-m-d') !!}';

        let  dateNowAll = moment(stringDate);

    </script>
    <script>
        $(document).ready(function () {
            // $datepicker = $('#datepicker').datepicker({
            //     autoclose: true,
            // });
            // console.log(dateNowAll.format());
            // $datepicker.datepicker('setDate', new Date(dateNowAll.format()));
            $("#view").click(function () {
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
                        if(data.hasOwnProperty('link')){
                            window.location.replace(data.link);
                        }
                    }
                });
            });
        });
    </script>
    <style>
        .jexcel tbody tr:nth-child(even) {
            background-color: #EEE9F1 !important;
        }
        .cacheAction {
            background-color: green;
            color: #ffffff;
        }
        #spreadsheet > div:first-child{
            padding: 6px 0px 0px 7px;
            border: 1px solid #ccc;
            margin-bottom: 5px;
        }
        .error{
            color: red;
        }
        .jexcel_content::-webkit-scrollbar{
            width: 15px;
            height: 15px;
        }
        .pay-method-oke{
            color: #03a9f4;
        }
        .jexcel tbody tr.info {
            background-color: #8f2727 !important;
            color: #85ff00;
        }
        .jexcel tbody tr.info td:first-child{
            color: #000000;
        }
        .jexcel tbody tr.footer {
            background-color: #FFFF00 !important;
        }
        .jexcel tbody tr.group-cell td{

        }
        .jupload img{
            width: 100% !important;
            height:  100% !important;
        }
    </style>
    <script>
        function formatDate(date, format, utc) {
            var MMMM = ["\x00", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            var MMM = ["\x01", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            var dddd = ["\x02", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            var ddd = ["\x03", "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

            function ii(i, len) {
                var s = i + "";
                len = len || 2;
                while (s.length < len) s = "0" + s;
                return s;
            }

            var y = utc ? date.getUTCFullYear() : date.getFullYear();
            format = format.replace(/(^|[^\\])YYYY+/g, "$1" + y);
            format = format.replace(/(^|[^\\])YY/g, "$1" + y.toString().substr(2, 2));
            format = format.replace(/(^|[^\\])Y/g, "$1" + y);

            var M = (utc ? date.getUTCMonth() : date.getMonth()) + 1;
            format = format.replace(/(^|[^\\])MMMM+/g, "$1" + MMMM[0]);
            format = format.replace(/(^|[^\\])MMM/g, "$1" + MMM[0]);
            format = format.replace(/(^|[^\\])MM/g, "$1" + ii(M));
            format = format.replace(/(^|[^\\])M/g, "$1" + M);

            var d = utc ? date.getUTCDate() : date.getDate();
            format = format.replace(/(^|[^\\])DDDD+/g, "$1" + dddd[0]);
            format = format.replace(/(^|[^\\])DDD/g, "$1" + ddd[0]);
            format = format.replace(/(^|[^\\])DD/g, "$1" + ii(d));
            format = format.replace(/(^|[^\\])D/g, "$1" + d);

            var H = utc ? date.getUTCHours() : date.getHours();
            format = format.replace(/(^|[^\\])HH+/g, "$1" + ii(H));
            format = format.replace(/(^|[^\\])H/g, "$1" + H);

            var h = H > 12 ? H - 12 : H == 0 ? 12 : H;
            format = format.replace(/(^|[^\\])hh+/g, "$1" + ii(h));
            format = format.replace(/(^|[^\\])h/g, "$1" + h);

            var m = utc ? date.getUTCMinutes() : date.getMinutes();
            format = format.replace(/(^|[^\\])mm+/g, "$1" + ii(m));
            format = format.replace(/(^|[^\\])m/g, "$1" + m);

            var s = utc ? date.getUTCSeconds() : date.getSeconds();
            format = format.replace(/(^|[^\\])ss+/g, "$1" + ii(s));
            format = format.replace(/(^|[^\\])s/g, "$1" + s);

            var f = utc ? date.getUTCMilliseconds() : date.getMilliseconds();
            format = format.replace(/(^|[^\\])fff+/g, "$1" + ii(f, 3));
            f = Math.round(f / 10);
            format = format.replace(/(^|[^\\])ff/g, "$1" + ii(f));
            f = Math.round(f / 10);
            format = format.replace(/(^|[^\\])f/g, "$1" + f);

            var T = H < 12 ? "AM" : "PM";
            format = format.replace(/(^|[^\\])TT+/g, "$1" + T);
            format = format.replace(/(^|[^\\])T/g, "$1" + T.charAt(0));

            var t = T.toLowerCase();
            format = format.replace(/(^|[^\\])tt+/g, "$1" + t);
            format = format.replace(/(^|[^\\])t/g, "$1" + t.charAt(0));

            var tz = -date.getTimezoneOffset();
            var K = utc || !tz ? "Z" : tz > 0 ? "+" : "-";
            if (!utc) {
                tz = Math.abs(tz);
                var tzHrs = Math.floor(tz / 60);
                var tzMin = tz % 60;
                K += ii(tzHrs) + ":" + ii(tzMin);
            }
            format = format.replace(/(^|[^\\])K/g, "$1" + K);

            var day = (utc ? date.getUTCDay() : date.getDay()) + 1;
            format = format.replace(new RegExp(dddd[0], "g"), dddd[day]);
            format = format.replace(new RegExp(ddd[0], "g"), ddd[day]);

            format = format.replace(new RegExp(MMMM[0], "g"), MMMM[M]);
            format = format.replace(new RegExp(MMM[0], "g"), MMM[M]);

            format = format.replace(/\\(.)/g, "$1");

            return format;
        };
        let ListPayment =[
            "代金引換",
            "銀行振込",
            "決済不要"
        ];
        function getValuePayMethod(val) {
            let pay_method = "0";
            if(val === "Hoạt động"){
                pay_method = 1;
            }else  if(val === "Chưa thanh toán"){
                pay_method = 2;
            }else if(val === "Đã thông báo"){
                pay_method = 3;
            }else if(val === "Chờ xử lý"){
                pay_method = 4
            }else if(val === "Đã thanh toán"){
                pay_method = 5
            }
            return pay_method;
        }
        let columnsAll = {};
        let session_id = '{!! \Illuminate\Support\Str::random(20) !!}';

        // let onselection = function(instance, x1, y1, x2, y2, origin,columns) {
        //     var cellName1 = jexcel.getColumnNameFromId([x1, y1]);
        //     $("#value-review").val(instance.jexcel.getValue(cellName1)).focus();
        //
        //     $("#col-row-review").data({"x":x1,y:y1}).val(cellName1);
        //     console.log(columns);
        // };

        let config = {
            minDimensions:[30,15],
            tableWidth: '100%',
            tableHeight: '100%',
            defaultColWidth: 100,
            tableOverflow: true,
            getTypeColumns:function(){
                return "none";
            },

        };
        let datacache = {!! json_encode($excels_data,JSON_UNESCAPED_UNICODE ) !!}
            let dataproduct = {!! json_encode($products,JSON_UNESCAPED_UNICODE ) !!}
            let datamodel = {!! isset($model)?json_encode($model->detail,JSON_UNESCAPED_UNICODE ):'{}' !!};
        let dataship = {!! json_encode($ships,JSON_UNESCAPED_UNICODE) !!}
            let datadaibiki = {!! json_encode($daibiki,JSON_UNESCAPED_UNICODE) !!}
            let categorys = {!! json_encode($categorys,JSON_UNESCAPED_UNICODE) !!}
            let locks = {!! json_encode(isset($locks)?$locks:[],JSON_UNESCAPED_UNICODE) !!}
            let token = '{!! isset($model)?$model->token:"" !!}';
        function IF_End($val,$conf){
            if( $conf.equal_end === "<=" && $val <= $conf.value_end){
                return true;
            }else if( $conf.equal_end === ">=" && $val >= $conf.value_end){
                return true;
            }else if($conf.equal_end === ">" && $val > $conf.value_end){
                return true;
            } else if($conf.equal_end === "<" && $val < $conf.value_end){
                return true;
            }else if($conf.equal_end === "=" && $val === $conf.value_end){
                return true;
            }
            return false;
        }
        function IF_Start($val,$conf){
            try{
                if( $conf.equal_start === "<=" && $val <= $conf.value_start){
                    return true;
                }else if( $conf.equal_start === ">=" && $val >= $conf.value_start){
                    return true;
                }else if($conf.equal_start === ">" && $val > $conf.value_start){
                    return true;
                }else if($conf.equal_start === "<" && $val < $conf.value_start){
                    return true;
                }else if($conf.equal_start === "=" && $val == $conf.value_start){
                    return true;
                }
            }catch ($ex){

            }
            return false;
        }
        function setDefaultValue(i,columns, max) {
            if(columns[i].hasOwnProperty('value') && typeof(columns[i].value) == "object"){
                let self = null;
                if(columns[i].value[0] === "product"){
                    if(columns[i].value[1] === 'this'){
                        self = columns[i];
                    } else if(columns.hasOwnProperty(columns[i].value[1])){
                        self = columns[columns[i].value[1]];
                    }

                    if(self != null){
                        if(self.hasOwnProperty(columns[i].value[2])){
                            let v = self[columns[i].value[2]];
                            if(v){
                                if(columns[i].value.length > 3){
                                    v = v[columns[i].value[3]];
                                }
                                if(v && columns[i].value.length > 4){
                                    v = v[columns[i].value[4]];
                                }
                                if(v && columns[i].value.length > 5){
                                    v = v[columns[i].value[5]];
                                }
                                if(v)
                                    return [true,v];
                            }

                        }
                    }
                }else  if(columns[i].value[0] === "date"){
                    self = columns[i];
                    if(self != null){
                        if(columns[i].value[1] === "now"){
                            return [true,dateNowAll.format("YYYY-MM-DD")];
                        }else if(columns[i].value[1] === "nowEnd"){
                            let endTime = moment(dateNowAll.format("YYYY-MM-DD"));
                          //  return [true,endTime.add(1,'months').format("YYYY-MM-DD")];
                            return [true,endTime.endOf('month').format("YYYY-MM-DD")];
                        }
                    }
                }
            }else if(columns[i].hasOwnProperty('value')){
                return [true,columns[i].value];
            }
            return [false,""];
        }
        function InitData(data,config,columns_index,_sheetName) {
            let _data = [];
            let n =  data.length === 0 || config.minDimensions[1] > data.length?config.minDimensions[1]: data.length;

            for(let i=0; i < n ; i++){
                if(i < data.length)
                    _data[i] = data[i];
                else{
                    _data[i] = [];
                }
                for(let j=0 ; j < config.minDimensions[0] ; j++){

                    if(j < columns_index.length){

                        if(columns_index[j]){

                            if(columns_index[j].hasOwnProperty('value')){


                                let oke = true;
                                if(columns_index[j].hasOwnProperty('row')){
                                    oke = columns_index[j].row === i;
                                }
                                let okeValue = true;
                                if(typeof(_data[i]) === "undefined"){
                                    _data[i] = [];okeValue = false;
                                }
                                if(oke){
                                    if(typeof(_data[i][j]) === "undefined"){
                                        _data[i][j] = columns_index[j].value;
                                    }else{
                                        if(typeof(_data[i][j]) == "string" && _data[i][j].length === 0){
                                            if(_sheetName === "KOGYJA"){

                                            }else{
                                                _data[i][j] = columns_index[j].value;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return _data;
        }

        function SIM(sheetName,config,name) {

            let data = [];
            if(data.length === 0 && datamodel.hasOwnProperty(sheetName)){
                data = datamodel[sheetName];
            }
            let dropdown = dataproduct.hasOwnProperty(name)?dataproduct[name]:{};
            let index = 0;
            let columns = {
                status: {
                    type: 'checkbox',
                    title:'Status'
                },
                image:{
                    title:'Ảnh hóa đơn',
                    type:'image',
                    width:"50px",

                },
                image1:{
                    title:'Ảnh 1',
                    type:'image',
                    width:"50px",

                },
                timeCreate:{
                    title: 'Ngày tạo',//A ngày đặt hàng
                    type: 'calendar',
                    width:'100px',
                    options: { format:'DD/MM/YYYY' },
                    value:['date','now']
                },
                payMethod:{
                    title: 'Phương thức thanh toán',//B Phương thức thanh toán
                    type:'dropdown',
                    source:[
                        "Chưa thanh toán",
                        "Đã thông báo",
                        "Chờ xử lý",
                        "Đã thanh toán",
                    ],
                    width:'130px',
                    value:['product','this','source',0],
                },
                notification:{
                    title: 'Số lần báo',//C
                    type: 'text',
                    width:'100px',
                    value:"1",
                },
                phone:{
                    title: 'Số điện thoại',//C
                    type: 'text',
                    width:'100px',
                    value:"070-8409-5968",
                },
                zipcode:{
                    title: 'Mã bưu điện',//D
                    type: 'text',
                    width:'60px',
                    key:"demo",
                },
                province:{
                    title: 'Tỉnh/TP',//E
                    type: 'text',
                    width:'200px',
                    key:"demo",
                },
                address:{
                    title: 'Địa chỉ giao hàng',//F
                    type: 'text',
                    width:'250px',
                    key:"demo",
                },
                fullname:{
                    title: 'Họ tên người nhận',//G
                    type: 'text',
                    width:'150px',
                    key:"demo",
                },
                product_id:{
                    title: 'Mã SP',//H
                    type: 'text',
                    width:'100px',
                    read:true,
                    value:['product','product_name','source',0,'id'],
                },
                product_name:{
                    title: 'Tên SP',//I
                    type:'dropdown',
                    source:Object.values(dropdown),
                    autocomplete:true,
                    width:'140px',
                    value:['product','this','source',0,'id']
                },
                count:{
                    title: 'SL',//K
                    type: 'numeric',
                    width:'100px',
                    value:1
                },

                price:{
                    title: 'Giá nhập',//J
                    type: 'numeric',
                    width:'100px',
                    value:['product','product_name','source',0,'data','price'],
                },
                total_count:{
                    title: 'Đặt cọc',//K
                    type: 'numeric',
                    width:'100px',
                    value:0
                },
                auto:{
                    title: 'Chu kỳ gia hạn',//K
                    type: 'numeric',
                    width:'100px',
                    value:1
                },
                price_buy:{
                    title: '単価',//J Giá nhập
                    type: 'numeric',
                    width:'1px',
                    value:['product','product_name','source',0,'data','price_buy'],
                },
                order_date:{
                    title: 'Ngày bắt đầu',//L Ngày nhận
                    type:'calendar',
                    options: { },
                    value:['date','now'],
                    width:'100px',
                },
                order_hours:{
                    title: 'Ngày hết hạn',//L Ngày nhận
                    type:'calendar',
                    options: { },
                    value:['date','nowEnd'],
                    width:'100px',
                },
                order_ship:{
                    title: 'Số ngày hết hạn',//N
                    type: 'numeric',
                    width:'100px',
                    value:0
                },
                order_total_price:{
                    title: 'Tổng giá nhập',//O Tổng giá nhập
                    type: 'numeric',
                    width:'100px',
                    value:['product','product_name','source',0,'data','price'],
                },
                price_buy_sale:{
                    title: 'Tăng Giảm',//J Giá nhập
                    type: 'numeric',
                    width:'100px',
                    value:0,
                },
                order_total_price_buy:{
                    title: 'Total Bán',//P Giá bán
                    type: 'numeric',
                    width:'100px',
                    value:['product','product_name','source',0,'data','price_buy'],
                },
                order_ship_cou:{
                    title: 'Phí giao hàng',//P
                    type: 'numeric',
                    width:'100px',
                    value:0
                },
                order_price:{
                    title: 'Lợi nhuận',//P
                    type: 'numeric',
                    width:'100px',
                    value:0
                },
                order_tracking:{
                    title: 'tracking',//T Mã tracking
                    type: 'text',
                    width:'100px',
                    key:"demo",
                },
                order_link:{
                    title: 'Đường dẫn',//T Thông tin chuyển khoản
                    type: 'text',
                    width:'100px',
                },
                order_info:{
                    title: 'Thông tin chuyển khoản',//T Thông tin chuyển khoản
                    type: 'text',
                    width:'100px',
                    key:"demo",
                },
                id:{
                    title: 'ID',//T
                    type: 'text',
                    width:'100px',
                },
            };
            columnsAll[sheetName] = columns;
            for(var i in columns){
                columns[i].index = index;
                columns[i].key = i;
                let v = setDefaultValue(i,columns);
                if(v[0]){
                    columns[i].value = v[1];
                }else{
                    delete columns[i].value;
                }
                index++;

            }
            function update(instance, cell, c, r, value) {
                console.log("update call");

                let data = {
                    count:value.hasOwnProperty('count')?value.count:parseInt(instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.count.index, r]))),
                    id:value.hasOwnProperty('id')?value.id:instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.product_id.index, r])),
                    province:value.hasOwnProperty('province')?value.province:instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.province.index, r])),
                };
                let total_price_buy =  0;
                let total_price =  0;

                let valueRow =  instance.jexcel.getRowData(r);
                let payMethod = getValuePayMethod(valueRow[columns.payMethod.index]);
                data.payMethod = payMethod;
                data.sheetName = sheetName;
                console.log("payMethod:"+payMethod);
                let price_buy_sale = parseInt(valueRow[columns.price_buy_sale.index]);
                console.log("price_buy_sale:"+price_buy_sale);
                if(dropdown.hasOwnProperty(data.id)){

                    let product = dropdown[data.id];
                    let price_buy = 0;
                    let price = 0;

                    if(valueRow[columns.price_buy.index] > 0){
                        price_buy = valueRow[columns.price_buy.index];
                    }else{
                        price_buy = product.data.price_buy;
                    }
                    if(valueRow[columns.price.index] > 0 ){
                        price = valueRow[columns.price.index];
                    }else{
                        price = product.data.price;
                    }
                    console.log("price_buy:"+price_buy);
                    total_price = parseFloat(price) * data.count;

                    instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price.index, r]), total_price,true);

                    data.total_price = total_price;

                    total_price_buy = parseFloat(price_buy) * data.count + price_buy_sale;

                    data.total_price_buy = total_price_buy;

                }
                function setInterest(price_ship,order_ship_cou,total_price_buy){

                    price_ship = price_ship * data.count;
                    instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship.index, r]),price_ship);
                    total_price_buy = total_price_buy + price_ship;

                    if(total_price_buy ===0 || total_price == 0){ return;}
                    instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]), total_price_buy,false );

                    if(payMethod == 3){
                        instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]), 0);
                        instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]), 0);
                        instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price_buy.index, r]), 0);
                        instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]), 0);
                    }else if(payMethod == 2){
                        let a = (parseInt(total_price_buy) - parseInt(total_price) - parseInt(price_ship));

                        instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]),a,false);
                        instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]),0,false);
                    }else{
                        console.log("price_ship:"+price_ship);

                        let a = (parseInt(total_price_buy) - parseInt(total_price) - parseInt(price_ship) - order_ship_cou);

                        instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]),a,false);
                        instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]),order_ship_cou,false);
                    }
                }
                console.log("SEND");
                $.ajax({
                    type: "POST",
                    url:"{{ route('backend:shop_ja:order:excel:store') }}",
                    data:{act:'ship',data:data} ,
                    success: function (data) {
                        console.log(data);
                        if(data && data.length >0){
                            console.log("oke");
                            let price_ship = parseInt(data[0].data.price_ship)
                            let ship_cou = parseInt(data[0].data.ship_cou);
                            let total_price_buy = parseInt(data[0].data.total_price_buy);

                            setInterest(price_ship < 0? 0 : price_ship,ship_cou< 0 ?0:ship_cou,total_price_buy);
                        }
                    },
                });


                //    if(value.hasOwnProperty('lock') && value.lock.indexOf(columns.order_ship.index)){

                //     } else{
                //         setInterest(parseInt(valueRow[columns.order_ship.index]),parseInt(valueRow[columns.order_ship_cou.index]));
                //     }
            }
            let columns_index = Object.values(columns);
            let _data = InitData(data,config,columns_index);
            let change = {col:-1,row:-1};
            let nestedHeaders = [];
            if(locks.hasOwnProperty(sheetName)){
                let _lock  = locks [sheetName];
                let dateNow = '{!! date("Y-m-d"); !!}';
                if(_lock.action == 2 && _lock.date == dateNow ){
                    let count = columns_index.length+4;
                    count = count - 1;
                    nestedHeaders.push({
                        title: "Ngày xuất : "+_lock.date  ,
                        colspan: 3,
                    });
                    count = count - 1;
                    nestedHeaders.push({
                        title:  " Giờ xuất :"+_lock.hour ,
                        colspan: 2,

                    });
                    count = count - 1;
                    nestedHeaders.push({
                        title: "Ngày tạo :"+_lock.created_at  ,
                        colspan: 3,

                    });
                    count = count - 1;
                    nestedHeaders.push({
                        title: "Ngày Sửa :" + _lock.updated_at  ,
                        colspan:3,

                    });
                    nestedHeaders.push({
                        title:"" ,
                        colspan: count,
                        align:"left"
                    });
                }

            }
            if(nestedHeaders.length ==0){
                nestedHeaders = [
                    {
                        title: "Không khóa",
                        colspan: columns_index.length+4,
                        align:"left"
                    }
                ]
            }
            return {
                sheetName:sheetName,
                rowResize:true,
                columnDrag:true,
                columns:Object.values(columns),
                data:_data,
                nestedHeaders:[
                    nestedHeaders
                ],
                contextMenu: function(obj, x, y, e) {
                    var items = [];

                    if (y == null) {
                        // Insert a new column
                        if (obj.options.allowInsertColumn === true) {
                            items.push({
                                title:obj.options.text.insertANewColumnBefore,
                                onclick:function() {
                                    obj.insertColumn(1, parseInt(x), 1);
                                }
                            });
                        }
                        if (obj.options.allowInsertColumn === true) {
                            items.push({
                                title:obj.options.text.insertANewColumnAfter,
                                onclick:function() {
                                    obj.insertColumn(1, parseInt(x), 0);
                                }
                            });
                        }

                        // Delete a column
                        if (obj.options.allowDeleteColumn === true) {
                            items.push({
                                title:obj.options.text.deleteSelectedColumns,
                                onclick:function() {
                                    obj.deleteColumn(obj.getSelectedColumns().length ? undefined : parseInt(x));
                                }
                            });
                        }

                        // // Rename column
                        // if (obj.options.allowRenameColumn === true) {
                        //     items.push({
                        //         title:obj.options.text.renameThisColumn,
                        //         onclick:function() {
                        //             obj.setHeader(x);
                        //         }
                        //     });
                        // }

                        // Sorting
                        if (obj.options.columnSorting == true) {

                            items.push({ type:'line' });

                            items.push({
                                title:obj.options.text.orderAscending,
                                onclick:function() {
                                    obj.orderBy(x, 0);
                                }
                            });
                            items.push({
                                title:obj.options.text.orderDescending,
                                onclick:function() {
                                    obj.orderBy(x, 1);
                                }
                            });
                        }
                    } else {
                        // Insert new row
                        if (obj.options.allowInsertRow === true) {
                            items.push({
                                title:obj.options.text.insertANewRowBefore,
                                onclick:function() {
                                    obj.insertRow(1, parseInt(y), 1);
                                }
                            });

                            items.push({
                                title:obj.options.text.insertANewRowAfter,
                                onclick:function() {
                                    obj.insertRow(1, parseInt(y));
                                }
                            });
                        }

                        if (obj.options.allowDeleteRow === true) {
                            items.push({
                                title:obj.options.text.deleteSelectedRows,
                                onclick:function() {
                                    obj.deleteRow(obj.getSelectedRows().length ? undefined : parseInt(y));
                                }
                            });
                        }

                        if (x) {
                            if (obj.options.allowComments === true) {
                                items.push({ type:'line' });

                                var title = obj.records[y][x].getAttribute('title') || '';

                                items.push({
                                    title: title ? obj.options.text.editComments : obj.options.text.addComments,
                                    onclick:function() {
                                        obj.setComments([ x, y ], prompt(obj.options.text.comments, title));
                                    }
                                });

                                if (title) {
                                    items.push({
                                        title:obj.options.text.clearComments,
                                        onclick:function() {
                                            obj.setComments([ x, y ], '');
                                        }
                                    });
                                }
                            }
                        }
                    }
                    return items;
                },
                onselection:function (instance, x1, y1, x2, y2, origin) {

                    change = {col:x1,row:y1};

                    var cellName1 = jexcel.getColumnNameFromId([columns_index[x1].index, y1]);

                    $("#col-row-review").data({"x":x1,y:y1}).val(cellName1);
                    let val = instance.jexcel.getValue(cellName1);

                    if(columns_index[x1] && columns_index[x1].type === "dropdown"){
                        $("#value-review").hide();
                        $html = $("<div>");
                        $("#zoe-dropdown-review").show().html($html);

                        jSuites.dropdown($html[0], {
                            data:columns_index[x1].source,
                            autocomplete: columns_index[x1].hasOwnProperty('autocomplete'),
                            width:'100%',
                            onchange:function (el, a, oldValue, Value) {
                                console.log(Value);
                                instance.jexcel.setValue(jexcel.getColumnNameFromId([x1, y1]), Value);
                            },
                        }).setValue(val);

                    }else{
                        $("#value-review").show().val("");
                        $("#zoe-dropdown-review").hide();
                        $("#value-review").prop("disabled",false );
                        if(columns_index[x1] && columns_index[x1].type === "text"){
                            if(columns_index[x1].hasOwnProperty('read')){
                                $("#value-review").prop( "disabled", true );
                            }
                        }
                        $("#value-review").show().val(val);
                    }
                },
                updateTable: function (instance, cell, col, row, val, id) {
                    let c = parseInt(col);

                    if (c === columns.image.index && val.length>0) {
                        cell.innerHTML = '<img src="' + val + '" style="width:20px;height:20px">';
                    }

                    cell.style.overflow = 'hidden';

                    if(columns.id.index === c ){

                        let v = instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.order_ship.index, row]));
                        if(v == -1) instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.province.index, row])).classList.add('error');
                        else instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.province.index, row])).classList.remove('error');

                        let value = instance.jexcel.getRowData(row);

                        let count = 0;
                        for(let k in instance.jexcel.rows){
                            let _val  = instance.jexcel.getRowData(k);
                            if(value[columns.fullname.index].length > 0 && value[columns.fullname.index] === _val[columns.fullname.index]){
                                count++;
                            }
                        }
                        if(count > 1) instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])).classList.add('error');
                        else instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])).classList.remove('error');
                        let vvv = getValuePayMethod(value[columns.payMethod.index]);
                        let parent = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.payMethod.index, row]))).parent();
                        parent.removeClass('pay-method-oke');
                        if(vvv === 2){
                            parent.addClass('pay-method-oke');
                        }
                    }
                },
                onchange:function(instance, cell, c, r, value) {
                    c = parseInt(c);
                    console.log(change);
                    if (c === columns.product_name.index) {
                        if(dropdown[value] && dropdown[value].hasOwnProperty('data')){
                            instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.product_id.index, r]), dropdown[value].data.id);
                            instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price.index, r]),dropdown[value].data.price);
                            instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price_buy.index, r]),dropdown[value].data.price_buy);
                            if(change.col == c){
                                update(instance, cell, c, r,{
                                    count:1,
                                    id:dropdown[value].data.id
                                });
                            }
                        }
                    }else if(c === columns.count.index || c === columns.price_buy_sale.index ||
                        c === columns.order_ship.index || c === columns.order_ship_cou.index){
                        if(change.col == c){
                            update(instance, cell, c, r,{

                            });
                        }
                    }else if(c === columns.province.index){
                        if(change.col == c){
                            update(instance, cell, c, r,{});
                        }
                    }else if(c === columns.payMethod.index){
                        let v = getValuePayMethod(value);
                        let parent = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.payMethod.index, r]))).parent();

                        parent.removeClass('pay-method-oke');
                        if(v === 2){
                            parent.addClass('pay-method-oke');

                        }
                        if(change.col == c){
                            update(instance, cell, c, r,{});
                        }
                    }
                },

            };
        }
        function SIM1(sheetName,config,name) {

            let data = [];

            {{--if(datacache.hasOwnProperty(sheetName) &&  datacache[sheetName].data.data.length > 0){--}}

            {{--if(datacache[sheetName].data.token === token ||  "{!! isset($model)?"edit":"create" !!}" == "create"){--}}
            {{--data = datacache[sheetName].data.data;--}}
            {{--console.log("cache")--}}
            {{--}--}}
            {{--}--}}

            if(data.length === 0 && datamodel.hasOwnProperty(sheetName)){

                data = datamodel[sheetName];
            }

            let dropdown = dataproduct.hasOwnProperty(name)?dataproduct[name]:{};
            let index = 0;
            let columns = {
                status: {
                    type: 'checkbox',
                    title:'Status'
                },
                image:{
                    title:'Ảnh hóa đơn',
                    type:'image',
                    width:"50px",
                },
                image1:{
                    title:'Ảnh 1',
                    type:'image',
                    width:"50px",
                },
                image2:{
                    title:'Ảnh 2',
                    type:'image',
                    width:"50px",
                },
                image3:{
                    title:'Ảnh 3',
                    type:'image',
                    width:"50px",
                },
                image4:{
                    title:'Ảnh 4',
                    type:'image',
                    width:"50px",
                },
                timeCreate:{
                    title: 'Ngày tạo',//A ngày đặt hàng
                    type: 'calendar',
                    width:'100px',
                    options: { format:'DD/MM/YYYY' },
                    value:['date','now']
                },
                payMethod:{
                    title: 'Phương thức thanh toán',//B Phương thức thanh toán
                    type:'dropdown',
                    source:[
                        "Chưa thanh toán",
                        "Đã thông báo",
                        "Chờ xử lý",
                        "Đã thanh toán",
                    ],
                    width:'130px',
                    value:['product','this','source',0],
                },
                notification:{
                    title: 'Số lần báo',//C
                    type: 'text',
                    width:'100px',
                    value:"1",
                },
                phone:{
                    title: 'Số điện thoại',//C
                    type: 'text',
                    width:'100px',
                    value:"070-8409-5968",
                },
                zipcode:{
                    title: 'Mã bưu điện',//D
                    type: 'text',
                    width:'60px',
                    key:"demo",
                },
                province:{
                    title: 'Tỉnh/TP',//E
                    type: 'text',
                    width:'200px',
                    key:"demo",
                },
                address:{
                    title: 'Địa chỉ giao hàng',//F
                    type: 'text',
                    width:'250px',
                    key:"demo",
                },
                fullname:{
                    title: 'Họ tên người nhận',//G
                    type: 'text',
                    width:'150px',
                    key:"demo",
                },
                product_id:{
                    title: 'Mã SP',//H
                    type: 'text',
                    width:'100px',
                    read:true,
                    value:['product','product_name','source',0,'id'],
                },
                product_name:{
                    title: 'Tên SP',//I
                    type:'dropdown',
                    source:Object.values(dropdown),
                    autocomplete:true,
                    width:'140px',
                    value:['product','this','source',0,'id']
                },
                count:{
                    title: 'SL',//K
                    type: 'numeric',
                    width:'100px',
                    value:1
                },

                price:{
                    title: 'Giá nhập',//J
                    type: 'numeric',
                    width:'100px',
                    value:['product','product_name','source',0,'data','price'],
                },
                total_count:{
                    title: 'Đặt cọc',//K
                    type: 'numeric',
                    width:'100px',
                    value:0
                },
                auto:{
                    title: 'Chu kỳ gia hạn',//K
                    type: 'numeric',
                    width:'100px',
                    value:1
                },
                price_buy:{
                    title: '単価',//J Giá nhập
                    type: 'numeric',
                    width:'1px',
                    value:['product','product_name','source',0,'data','price_buy'],
                },
                order_date:{
                    title: 'Ngày bắt đầu',//L Ngày nhận
                    type:'calendar',
                    options: { },
                    value:['date','now'],
                    width:'100px',
                },
                order_hours:{
                    title: 'Ngày hết hạn',//L Ngày nhận
                    type:'calendar',
                    options: { },
                    value:['date','nowEnd'],
                    width:'100px',
                },
                order_ship:{
                    title: 'Số ngày hết hạn',//N
                    type: 'numeric',
                    width:'100px',
                    value:0
                },
                order_total_price:{
                    title: 'Tổng giá nhập',//O Tổng giá nhập
                    type: 'numeric',
                    width:'100px',
                    value:['product','product_name','source',0,'data','price'],
                },
                price_buy_sale:{
                    title: 'Tăng Giảm',//J Giá nhập
                    type: 'numeric',
                    width:'100px',
                    value:0,
                },
                order_total_price_buy:{
                    title: 'Total Bán',//P Giá bán
                    type: 'numeric',
                    width:'100px',
                    value:['product','product_name','source',0,'data','price_buy'],
                },
                order_ship_cou:{
                    title: 'Phí giao hàng',//P
                    type: 'numeric',
                    width:'100px',
                    value:0
                },
                order_price:{
                    title: 'Lợi nhuận',//P
                    type: 'numeric',
                    width:'100px',
                    value:0
                },
                order_tracking:{
                    title: 'tracking',//T Mã tracking
                    type: 'text',
                    width:'100px',
                    key:"demo",
                },
                order_link:{
                    title: 'Đường dẫn',//T Thông tin chuyển khoản
                    type: 'text',
                    width:'100px',
                },
                order_info:{
                    title: 'Thông tin chuyển khoản',//T Thông tin chuyển khoản
                    type: 'text',
                    width:'100px',
                    key:"demo",
                },
                id:{
                    title: 'ID',//T
                    type: 'text',
                    width:'100px',
                },
            };
            columnsAll[sheetName] = columns;
            for(var i in columns){
                columns[i].index = index;
                columns[i].key = i;
                let v = setDefaultValue(i,columns);


                if(v[0]){
                    columns[i].value = v[1];
                }else{
                    delete columns[i].value;
                }
                console.log(JSON.stringify(columns[i]) +  JSON.stringify(v));
                index++;
            }
            function update(instance, cell, c, r, value) {
                console.log("update call");

                let data = {
                    count:value.hasOwnProperty('count')?value.count:parseInt(instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.count.index, r]))),
                    id:value.hasOwnProperty('id')?value.id:instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.product_id.index, r])),
                    province:value.hasOwnProperty('province')?value.province:instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.province.index, r])),
                };
                let total_price_buy =  0;
                let total_price =  0;

                let valueRow =  instance.jexcel.getRowData(r);
                let payMethod = getValuePayMethod(valueRow[columns.payMethod.index]);
                data.payMethod = payMethod;
                data.sheetName = sheetName;
                console.log("payMethod:"+payMethod);
                let price_buy_sale = parseInt(valueRow[columns.price_buy_sale.index]);
                console.log("price_buy_sale:"+price_buy_sale);
                if(dropdown.hasOwnProperty(data.id)){

                    let product = dropdown[data.id];
                    let price_buy = 0;
                    let price = 0;

                    if(valueRow[columns.price_buy.index] > 0){
                        price_buy = valueRow[columns.price_buy.index];
                    }else{
                        price_buy = product.data.price_buy;
                    }
                    if(valueRow[columns.price.index] > 0 ){
                        price = valueRow[columns.price.index];
                    }else{
                        price = product.data.price;
                    }
                    console.log("price_buy:"+price_buy);
                    total_price = parseFloat(price) * data.count;

                    instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price.index, r]), total_price,true);

                    data.total_price = total_price;

                    total_price_buy = parseFloat(price_buy) * data.count + price_buy_sale;

                    data.total_price_buy = total_price_buy;

                }
                function setInterest(price_ship,order_ship_cou,total_price_buy){

                    price_ship = price_ship * data.count;
                    instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship.index, r]),price_ship);
                    total_price_buy = total_price_buy + price_ship;

                    if(total_price_buy ===0 || total_price == 0){ return;}
                    instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]), total_price_buy,false );

                    if(payMethod == 3){
                        instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]), 0);
                        instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]), 0);
                        instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price_buy.index, r]), 0);
                        instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]), 0);
                    }else if(payMethod == 2){
                        let a = (parseInt(total_price_buy) - parseInt(total_price) - parseInt(price_ship));

                        instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]),a,false);
                        instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]),0,false);
                    }else{
                        console.log("price_ship:"+price_ship);

                        let a = (parseInt(total_price_buy) - parseInt(total_price) - parseInt(price_ship) - order_ship_cou);

                        instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]),a,false);
                        instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]),order_ship_cou,false);
                    }
                }
                console.log("SEND");
                $.ajax({
                    type: "POST",
                    url:"{{ route('backend:shop_ja:order:excel:store') }}",
                    data:{act:'ship',data:data} ,
                    success: function (data) {
                        console.log(data);
                        if(data && data.length >0){
                            console.log("oke");
                            let price_ship = parseInt(data[0].data.price_ship)
                            let ship_cou = parseInt(data[0].data.ship_cou);
                            let total_price_buy = parseInt(data[0].data.total_price_buy);

                            setInterest(price_ship < 0? 0 : price_ship,ship_cou< 0 ?0:ship_cou,total_price_buy);
                        }
                    },
                });


                //    if(value.hasOwnProperty('lock') && value.lock.indexOf(columns.order_ship.index)){

                //     } else{
                //         setInterest(parseInt(valueRow[columns.order_ship.index]),parseInt(valueRow[columns.order_ship_cou.index]));
                //     }
            }
            let columns_index = Object.values(columns);

            let _data = InitData(data,config,columns_index);

            let change = {col:-1,row:-1};
            let nestedHeaders = [];
            if(locks.hasOwnProperty(sheetName)){
                let _lock  = locks [sheetName];
                let dateNow = '{!! date("Y-m-d"); !!}';
                if(_lock.action == 2 && _lock.date == dateNow ){
                    let count = columns_index.length+4;
                    count = count - 1;
                    nestedHeaders.push({
                        title: "Ngày xuất : "+_lock.date  ,
                        colspan: 3,
                    });
                    count = count - 1;
                    nestedHeaders.push({
                        title:  " Giờ xuất :"+_lock.hour ,
                        colspan: 2,

                    });
                    count = count - 1;
                    nestedHeaders.push({
                        title: "Ngày tạo :"+_lock.created_at  ,
                        colspan: 3,

                    });
                    count = count - 1;
                    nestedHeaders.push({
                        title: "Ngày Sửa :" + _lock.updated_at  ,
                        colspan:3,

                    });
                    nestedHeaders.push({
                        title:"" ,
                        colspan: count,
                        align:"left"
                    });
                }

            }
            if(nestedHeaders.length ==0){
                nestedHeaders = [
                    {
                        title: "Không khóa",
                        colspan: columns_index.length+4,
                        align:"left"
                    }
                ]
            }
            return {
                sheetName:sheetName,
                rowResize:true,
                columnDrag:true,
                columns:Object.values(columns),
                data:_data,
                nestedHeaders:[
                    nestedHeaders
                ],
                contextMenu: function(obj, x, y, e) {
                    var items = [];

                    if (y == null) {
                        // Insert a new column
                        if (obj.options.allowInsertColumn === true) {
                            items.push({
                                title:obj.options.text.insertANewColumnBefore,
                                onclick:function() {
                                    obj.insertColumn(1, parseInt(x), 1);
                                }
                            });
                        }
                        if (obj.options.allowInsertColumn === true) {
                            items.push({
                                title:obj.options.text.insertANewColumnAfter,
                                onclick:function() {
                                    obj.insertColumn(1, parseInt(x), 0);
                                }
                            });
                        }

                        // Delete a column
                        if (obj.options.allowDeleteColumn === true) {
                            items.push({
                                title:obj.options.text.deleteSelectedColumns,
                                onclick:function() {
                                    obj.deleteColumn(obj.getSelectedColumns().length ? undefined : parseInt(x));
                                }
                            });
                        }

                        // // Rename column
                        // if (obj.options.allowRenameColumn === true) {
                        //     items.push({
                        //         title:obj.options.text.renameThisColumn,
                        //         onclick:function() {
                        //             obj.setHeader(x);
                        //         }
                        //     });
                        // }

                        // Sorting
                        if (obj.options.columnSorting == true) {

                            items.push({ type:'line' });

                            items.push({
                                title:obj.options.text.orderAscending,
                                onclick:function() {
                                    obj.orderBy(x, 0);
                                }
                            });
                            items.push({
                                title:obj.options.text.orderDescending,
                                onclick:function() {
                                    obj.orderBy(x, 1);
                                }
                            });
                        }
                    } else {
                        // Insert new row
                        if (obj.options.allowInsertRow === true) {
                            items.push({
                                title:obj.options.text.insertANewRowBefore,
                                onclick:function() {
                                    obj.insertRow(1, parseInt(y), 1);
                                }
                            });

                            items.push({
                                title:obj.options.text.insertANewRowAfter,
                                onclick:function() {
                                    obj.insertRow(1, parseInt(y));
                                }
                            });
                        }

                        if (obj.options.allowDeleteRow === true) {
                            items.push({
                                title:obj.options.text.deleteSelectedRows,
                                onclick:function() {
                                    obj.deleteRow(obj.getSelectedRows().length ? undefined : parseInt(y));
                                }
                            });
                        }

                        if (x) {
                            if (obj.options.allowComments === true) {
                                items.push({ type:'line' });

                                var title = obj.records[y][x].getAttribute('title') || '';

                                items.push({
                                    title: title ? obj.options.text.editComments : obj.options.text.addComments,
                                    onclick:function() {
                                        obj.setComments([ x, y ], prompt(obj.options.text.comments, title));
                                    }
                                });

                                if (title) {
                                    items.push({
                                        title:obj.options.text.clearComments,
                                        onclick:function() {
                                            obj.setComments([ x, y ], '');
                                        }
                                    });
                                }
                            }
                        }
                    }
                    return items;
                },
                onselection:function (instance, x1, y1, x2, y2, origin) {

                    change = {col:x1,row:y1};

                    var cellName1 = jexcel.getColumnNameFromId([columns_index[x1].index, y1]);

                    $("#col-row-review").data({"x":x1,y:y1}).val(cellName1);
                    let val = instance.jexcel.getValue(cellName1);

                    if(columns_index[x1] && columns_index[x1].type === "dropdown"){
                        $("#value-review").hide();
                        $html = $("<div>");
                        $("#zoe-dropdown-review").show().html($html);

                        jSuites.dropdown($html[0], {
                            data:columns_index[x1].source,
                            autocomplete: columns_index[x1].hasOwnProperty('autocomplete'),
                            width:'100%',
                            onchange:function (el, a, oldValue, Value) {
                                console.log(Value);
                                instance.jexcel.setValue(jexcel.getColumnNameFromId([x1, y1]), Value);
                            },
                        }).setValue(val);

                    }else{
                        $("#value-review").show().val("");
                        $("#zoe-dropdown-review").hide();
                        $("#value-review").prop("disabled",false );
                        if(columns_index[x1] && columns_index[x1].type === "text"){
                            if(columns_index[x1].hasOwnProperty('read')){
                                $("#value-review").prop( "disabled", true );
                            }
                        }
                        $("#value-review").show().val(val);
                    }
                },
                updateTable: function (instance, cell, col, row, val, id) {
                    let c = parseInt(col);

                    if (c === columns.image.index && val.length>0) {
                        cell.innerHTML = '<img src="' + val + '" style="width:20px;height:20px">';
                    }
                    if (c === columns.image1.index && val.length>0) {
                        cell.innerHTML = '<img src="' + val + '" style="width:20px;height:20px">';
                    }
                    if (c === columns.image2.index && val.length>0) {
                        cell.innerHTML = '<img src="' + val + '" style="width:20px;height:20px">';
                    }
                    if (c === columns.image3.index && val.length>0) {
                        cell.innerHTML = '<img src="' + val + '" style="width:20px;height:20px">';
                    }
                    cell.style.overflow = 'hidden';

                    if(columns.id.index === c ){

                        let v = instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.order_ship.index, row]));
                        if(v == -1) instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.province.index, row])).classList.add('error');
                        else instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.province.index, row])).classList.remove('error');

                        let value = instance.jexcel.getRowData(row);

                        let count = 0;
                        for(let k in instance.jexcel.rows){
                            let _val  = instance.jexcel.getRowData(k);
                            if(value[columns.fullname.index].length > 0 && value[columns.fullname.index] === _val[columns.fullname.index]){
                                count++;
                            }
                        }
                        if(count > 1) instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])).classList.add('error');
                        else instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])).classList.remove('error');
                        let vvv = getValuePayMethod(value[columns.payMethod.index]);
                        let parent = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.payMethod.index, row]))).parent();
                        parent.removeClass('pay-method-oke');
                        if(vvv === 2){
                            parent.addClass('pay-method-oke');
                        }
                    }
                },
                onchange:function(instance, cell, c, r, value) {
                    c = parseInt(c);
                    console.log(change);
                    if (c === columns.product_name.index) {
                        if(dropdown[value] && dropdown[value].hasOwnProperty('data')){
                            instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.product_id.index, r]), dropdown[value].data.id);
                            instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price.index, r]),dropdown[value].data.price);
                            instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price_buy.index, r]),dropdown[value].data.price_buy);
                            if(change.col == c){
                                update(instance, cell, c, r,{
                                    count:1,
                                    id:dropdown[value].data.id
                                });
                            }
                        }
                    }else if(c === columns.count.index || c === columns.price_buy_sale.index ||
                        c === columns.order_ship.index || c === columns.order_ship_cou.index){
                        if(change.col == c){
                            update(instance, cell, c, r,{

                            });
                        }
                    }else if(c === columns.province.index){
                        if(change.col == c){
                            update(instance, cell, c, r,{});
                        }
                    }else if(c === columns.payMethod.index){
                        let v = getValuePayMethod(value);
                        let parent = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.payMethod.index, r]))).parent();

                        parent.removeClass('pay-method-oke');
                        if(v === 2){
                            parent.addClass('pay-method-oke');

                        }
                        if(change.col == c){
                            update(instance, cell, c, r,{});
                        }
                    }
                },

            };
        }

        let sheets = [
            Object.assign(SIM1("SOFTBANK",config,'SOFTBANK'),config ),
            Object.assign(SIM1("GTN",config,'GTN'),config ),
        ];

        console.log(sheets);
        var win = window,
            doc = document,
            docElem = doc.documentElement,
            body = doc.getElementsByTagName('body')[0],
            x = win.innerWidth || docElem.clientWidth || body.clientWidth,
            y = win.innerHeight|| docElem.clientHeight|| body.clientHeight;
        config.tableHeight = (y*0.65)+"px";



        for(let i = 0 ; i < sheets.length ; i++){
            sheets[i].minDimensions = [sheets[i].minDimensions[0],parseInt(sheets[i].minDimensions[1] *(y/1000))];
        }
        console.log(sheets);
        let spreadsheet =  document.getElementById('spreadsheet');
        let worksheets = jexcel.tabs(spreadsheet, sheets);

        setInterval(function () {
            //   Save(true);
        },5000);

        $(document).ready(function () {
            let col_row_review = $("#col-row-review");
            $("#value-review").on("input", function(){
                console.log($(this).val());
                let col_row = col_row_review.data();
                if(col_row.hasOwnProperty('x') && col_row.hasOwnProperty('y')){
                    let _spreadsheet = document.getElementById('spreadsheet').children[0].querySelector('.selected');
                    let  worksheet = _spreadsheet.getAttribute('data-spreadsheet');

                    let data = spreadsheet.jexcel[worksheet].options.data;
                    let name = _spreadsheet.textContent;

                    spreadsheet.jexcel[worksheet].setValue(jexcel.getColumnNameFromId([col_row.x, col_row.y]), $(this).val());
                }
            }).focus(function() {
                console.log("in");
                console.log(col_row_review.data());
            }).focusout(function(){
                console.log("out");
            });
        });
        function Save(status) {
            if(status === true){
                let _spreadsheet = document.getElementById('spreadsheet').children[0].querySelector('.selected');
                let  worksheet = _spreadsheet.getAttribute('data-spreadsheet');
                let data = spreadsheet.jexcel[worksheet].options.data;
                let name = _spreadsheet.textContent;
                let key = datacache.hasOwnProperty(name)?datacache[name].key:false;
                _spreadsheet.classList.add("cacheAction");
                $.ajax({
                    type: "POST",
                    url:"{{ route('backend:shop_ja:sim:store') }}",
                    data:{
                        data:JSON.stringify(data),
                        token:token,
                        act:"cache",key:key,name:name,'id':'0','type':'{{isset($model)?'edit':'create'}}'} ,
                    success: function (data) {
                        console.log(data);
                        _spreadsheet.classList.remove("cacheAction");
                    },
                });
            }else{
                let datas = {};
                $("#spreadsheet .jexcel_tab_link").each(function () {
                    let  worksheet = this.getAttribute('data-spreadsheet');
                    let data = spreadsheet.jexcel[worksheet].options.data;
                    let name = this.textContent;
                    let _columns = [];
                    for(let k in  columnsAll[name] ){
                        _columns.push(k);
                    }
                    datas[name] = {
                        data:data,
                        columns:_columns
                    };
                });

                let form_store = $("#form_store");
                let _spreadsheet = document.getElementById('spreadsheet').children[0].querySelector('.selected');
                let  worksheet = _spreadsheet.getAttribute('data-spreadsheet');
                let instance = spreadsheet.jexcel[worksheet];
                let name = _spreadsheet.textContent;
                let columns = columnsAll[name];
                console.log(columns);
                $.ajax({
                    type: "POST",
                    url:"{{ route('backend:shop_ja:sim:store') }}",
                    data:{
                        datas:JSON.stringify(datas),
                        info: form_store.zoe_inputs('get'),
                        act:"save",
                        token:token,
                        date_key:'{!! isset($date_key)?$date_key:date('Y-m-d') !!}',
                        'id':'0',
                        'type':'{{isset($model)?'edit':'create'}}'} ,
                    success: function (data) {
                        for(let k in data.ids){
                            if(data.ids.hasOwnProperty(k)){
                                instance.setValue(jexcel.getColumnNameFromId([columns.id.index, k]),data.ids[k])
                            }
                        }
                        if(data.hasOwnProperty('url')){
                            window.location.replace(data.url);
                        }else{
                            for(let k in data.error){
                                if(data.error.hasOwnProperty(k)){
                                    for(let kk in data.error[k]){
                                        if(data.error[k].hasOwnProperty(kk)){
                                            console.log(data.error[k][kk]);
                                            instance.getCell(jexcel.getColumnNameFromId([columns[kk].index, k])).classList.add('error');

                                        }
                                    }
                                }
                            }
                        }
                    },
                });
            }
        }
        function Export() {
            let _spreadsheet = document.getElementById('spreadsheet').children[0].querySelector('.selected');
            let  worksheet = _spreadsheet.getAttribute('data-spreadsheet');

            let data = spreadsheet.jexcel[worksheet].options.data;
            let name = _spreadsheet.textContent;
            console.log(name);
            console.log(data);
            let _columns = [];
            for(let k in  columnsAll[name] ){
                _columns.push(k);
            }
            $.ajax({
                type: "POST",
                url:"{{ route('backend:shop_ja:sim:export') }}",
                data: {
                    datas: JSON.stringify(data),
                    name:name,
                    columns:_columns,
                    date_key:'{!! isset($date_key)?$date_key:date('Y-m-d') !!}'
                },
                success: function (data) {
                    console.log(data);
                    if(data.hasOwnProperty('link')){
                        window.location.replace(data.link);
                    }
                }
            });
        }

    </script>
@endsection
