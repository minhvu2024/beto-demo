
 <script src="{{ asset('module/shop-ja/assets/jexcel/dist/jexcel.js?v='.time()) }}"></script>
 <script src="{{ asset('module/shop-ja/assets/jsuites/dist/jsuites.js?v='.time()) }}"></script>
 <link rel="stylesheet" href="{{ asset('module/shop-ja/assets/jsuites/dist/jsuites.css') }}" type="text/css" />
 <link rel="stylesheet" href="{{ asset('module/shop-ja/assets/jexcel/dist/jexcel.css') }}" type="text/css" />
 <script src="{{asset('module/admin/assets/bootpopup/bootpopup.js')}}"></script>
 <script src="{{ asset('module/admin/assets/moment.min.js') }}"></script>
 <script src="{{ asset('module/admin/assets/jQuery-MD5/jquery.md5.js') }}"></script>
 <script src="{{ asset('module/admin/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
 <link rel="stylesheet" href="{{ asset('module/admin/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
 <style>

  .jexcel > tbody > tr > td.jexcel_dropdown{
   white-space: nowrap !important;
  }
 </style>
 <script>
  let stringDate = '{!! date('Y-m-d',strtotime($model?$model->key_date:time())) !!}';
  let  date = moment(stringDate);
  //
  console.log = function () {

  };

 </script>

 <style>
  .bor-error{
   border: 1px dotted red !important;
  }
  .bg-error{
   background: red !important;
  }
  .error-conflict{

  }
  .error-line-order{
   background: red !important;
  }
  .open_popup{
   cursor: pointer;
   color: #dd8c1a;
   font-weight: bold;
  }
  .jexcel thead .jexcel_nested td[data-column="0,1,2"] ,  .jexcel thead .jexcel_nested td[data-column="3,4"]{
   background: red !important;
   color: #ffffff;
  }
  .has_error td:first-child {
   background: red !important;
   color: #ffffff;
  }
  .has_export td:first-child {
   background: green !important;
   color: #ffffff;
  }
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
   background-color: #FFFF00 !important;

  }
  .jexcel tbody tr.info td:first-child{
   color: #000000;
  }
  .jexcel tbody tr.footer {
      background-color: #0002ff !important;
      color: #ffffff;
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
   if(val === "代金引換") return 1;
   if(val === "銀行振込") return 2;
   if(val === "決済不要") return 3;
   return 0;
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
   minDimensions:[40,200],
   tableWidth: '100%',
   tableHeight: '100%',
   defaultColWidth: 100,
   tableOverflow: true,
   allowExport:false,
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
          let locks = {!! json_encode($locks,JSON_UNESCAPED_UNICODE) !!}
          let token = '{!! isset($model)?$model->token:"" !!}';
  let exporsId = {!! json_encode($exports->getArrayCopy(),JSON_UNESCAPED_UNICODE) !!}
          let statusCompnay = {!! json_encode($status,JSON_UNESCAPED_UNICODE) !!}
          let hideprototy = {!! json_encode($hide,JSON_UNESCAPED_UNICODE) !!}
          let info_admin = {!! json_encode($admin,JSON_UNESCAPED_UNICODE) !!}
          let options = {!! json_encode($options,JSON_UNESCAPED_UNICODE) !!}

          let columnsWidth = {
   status: {
    width:"30px",
   },
   image:{
    width:"50px",
   },
   timeCreate:{
    width:'90px',
   },
   payMethod:{
    width:'90px',
   },
   phone:{
    width:'90px',
   },
   zipcode:{
    width:'60px',
   },
   province:{
    width:'150px',
   },
   address:{
    width:'150px',
   },
   fullname:{
    width:'150px',
   },
   product_id:{
    width:'50px',
   },
   product_name:{
    width:'140px',
   },
   count:{
    width:'50px',
   },
   price:{
    width:'70px',
   },
   price_buy:{
    width:'70px',
   },
   order_date:{
    width:'80px',
   },
   order_hours:{
    width:'100px',
   },
   order_ship:{
    width:'80px',
   },
   order_total_price:{
    width:'100px',
   },
   price_buy_sale:{
    width:'80px',
   },
   order_total_price_buy:{
    width:'100px',
   },
   order_ship_cou:{
    width:'100px',
   },
   order_price:{
    width:'100px',
   },
   order_rate:{
    width:'80px',
   },
   order_tracking:{
    width:'100px',
   },
   order_link:{
    width:'100px',
   },
   order_info:{
    width:'100px',
   },
   one_address: {
    width:'30px',
   },
   id:{
    width:'100px',
   },
   session_id:{
    width:'1px',
   },
   export:{
    width:'50px',
   },
  };


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
     //['date','this','options','format'],
     self = columns[i];
     if(self != null){
      if(self.hasOwnProperty('options') && self.options.hasOwnProperty('format')){
       if(columns[i].value[1] === "now")
        return [true,date.format("YYYY-MM-DD")+" 00:00:00"];
       else if(columns[i].value[1] === "nowSum"){

        let tmpDate =  moment(date.format("YYYY-MM-DD"));

        return [true,tmpDate.add(columns[i].value[2],'days').format("YYYY-MM-DD")+" 00:00:00"];
       }

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
     if(j < columns_index.length){//columns_index.length < config.minDimensions[0]
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

  function InitData1(data,config,columns_index) {
   let _data = [];
   let n =  data.length === 0 || config.minDimensions[1] > data.length?config.minDimensions[1]: data.length;

   for(let i=0; i < n ; i++){
    if(i < data.length)
     _data[i] = data[i];
    else{
     _data[i] = [];
    }
    for(let j=0 ; j < config.minDimensions[0] ; j++){

     if(columns_index.length < config.minDimensions[0]){

      if(columns_index[j]){

       if(columns_index[j].hasOwnProperty('value')){

        let oke = true;

        if(columns_index[j].hasOwnProperty('row')){
         oke = columns_index[j].row === i;
        }
        if(typeof(_data[i]) === "undefined"){
         _data[i] = [];
        }
        console.log(columns_index[j].row + " "+ columns_index[j].value + " "+ oke);
        if(oke){

         if(typeof(_data[i][j]) === "undefined"){
          _data[i][j] = columns_index[j].value;
         }else{
          if(typeof(_data[i][j]) == "string" && _data[i][j].length === 0){
           _data[i][j] = columns_index[j].value;
          }
         }
        }
       }
      }
      // if(columns_index[j].hasOwnProperty('value')){
      //     if(columns_index[j]){
      //         if(typeof(_data[i][j]) === "undefined"){
      //             _data[i][j] = columns_index[j].value;
      //         }else{
      //             if(typeof(_data[i][j]) == "string" && _data[i][j].length === 0){
      //                 _data[i][j] = columns_index[j].value;
      //             }else{
      //                 _data[i][j] = columns_index[j].value;
      //             }
      //         }
      //     }else{
      //         if(j > _data[i].length ||  typeof(_data[i][j]) === "undefined"){
      //             _data[i][j] =  columns_index[j].value;
      //         }
      //     }
      // }
     }else{
      // if(columns_index[j].hasOwnProperty('value')){
      //     _data[i][j] = columns_index[j].value;
      // }else{
      //     _data[i][j] = "";
      // }
     }
    }
   }
   return _data;
  }

  function FUKUI(config) {
   let  sheetName  =  'FUKUI';
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

   let dropdown = dataproduct.hasOwnProperty(sheetName)?dataproduct[sheetName]:{};
   let index = 0;

   let columns = {
    status: {
     type: 'checkbox',
     title:'{!! z_language('Trạng thái') !!}'
    },
    image:{
     title:'{!! z_language('Ảnh') !!}',
     type:'image',
     width:"50px",
     hide:true,
    },
       order_info:{
           title: '{!! z_language("Thông tin chuyển khoản") !!}',//T Thông tin chuyển khoản
           type: 'text',
           width:'100px',
           key:"demo",
       },
    timeCreate:{
     title: '{!! z_language("Ngày đặt hàng") !!}',//A ngày đặt hàng
     type: 'calendar',
     width:'100px',
     options: { format:'DD/MM/YYYY' },
     value:['date','now']
    },
    payMethod:{
     title: '{!! z_language("Phương thức thanh toán") !!}',//B Phương thức thanh toán
     type:'dropdown',
     source:[
      "代金引換",
      "銀行振込",
      "決済不要",
     ],
     width:'130px',
     value:['product','this','source',0],
    },
    order_date:{
     title: '{!! z_language("Ngày nhận") !!}',//L Ngày nhận
     type:'calendar',
     options: { format:'DD/MM/YYYY'},
        value:"",
     // value:['date','nowSum',2],
     width:'100px',

    },
    order_hours:{
     title: '{!! z_language("Giờ nhận") !!}',//M Giờ nhận
     type: 'dropdown',
     source:['8:00 ~ 12:00','12:00～14:00','14:00～16:00','16:00～18:00','18:00～20:00','19:00～21:00','20:00～21:00'],
     value:['product','this','source',5],
     width:'150px',
    },
    fullname:{
     title: '{!! z_language("Họ tên người nhận") !!}',//G Họ tên người nhận
     type: 'text',
     width:'150px',
     key:"demo",
    },
    zipcode:{
     title: '{!! z_language("Mã bưu điện") !!}',//D Mã bưu điện
     type: 'text',
     width:'60px',
     key:"demo",
    },
    province:{
     title: '{!! z_language("Tỉnh/TP") !!}',//E Tỉnh/TP
     type: 'text',
     width:'200px',
     key:"demo",
    },
    address:{
     title: '{!! z_language("Địa chỉ giao hàng") !!}',//F Địa chỉ giao hàng
     type: 'text',
     width:'250px',
     key:"demo",
    },
    phone:{
     title: '{!! z_language("Số điện thoại") !!}',//C Số điện thoại
     type: 'text',
     width:'100px',
     value:"",
    },
       order_link:{
           title: '{!! z_language("Đường dẫn") !!}',//T Thông tin chuyển khoản
           type: 'text',
           width:'100px',
       },
    product_id:{
     title: '{!! z_language("Mã SP") !!}',//H Mã SP
     type: 'text',
     width:'100px',
     read:true,
     value:['product','product_name','source',0,'id'],
    },
    product_name:{
     title: '{!! z_language("Tên SP") !!}',//I Tên SP
     type:'dropdown',
     source:Object.values(dropdown),
     autocomplete:true,
     width:'140px',
     value:['product','this','source',0,'id']
    },
    count:{
     title: '{!! z_language("SL") !!}',//K SL
     type: 'numeric',
     width:'100px',
     value:1
    },
    price:{
     title: '{!! z_language("Giá nhập") !!}',//J Giá nhập
     type: 'numeric',
     width:'100px',
     value:['product','product_name','source',0,'data','price'],
    },
    price_buy:{
     title: '{!! z_language("Giá bán") !!}',//J Giá nhập
     type: 'numeric',
     width:'100px',
     value:['product','product_name','source',0,'data','price_buy'],

    },
    order_ship:{
     title: '{!! z_language("Phí ship") !!}',//N Phí ship
     type: 'numeric',
     width:'100px',
     value:0
    },
    order_total_price:{
     title: '{!! z_language("Tổng giá nhập") !!}',//O Tổng giá nhập
     type: 'numeric',
     width:'100px',
     value:['product','product_name','source',0,'data','price'],
    },
    price_buy_sale:{
     title: '{!! z_language("Tăng Giảm") !!}',//J Giá nhập
     type: 'numeric',
     width:'100px',
     value:0,
    },
    order_total_price_buy:{
     title: '{!! z_language("Total Bán") !!}',//P Giá bán
     type: 'numeric',
     width:'100px',
     value:['product','product_name','source',0,'data','price_buy'],
    },
    order_ship_cou:{
     title: '{!! z_language("Phí giao hàng") !!}',//P Phí giao hàng
     type: 'numeric',
     width:'100px',
     value:330
    },
    order_price:{
     title: '{!! z_language("Lợi nhuận") !!}',//P Lợi nhuận
     type: 'numeric',
     width:'100px',
     value:0
    },
    order_rate:{
     title: '{!! z_language("Lợi nhuận CTV") !!}',//P
     type: 'numeric',
     width:'100px',
     value:0
    },
    order_tracking:{
     title: '{!! z_language("Mã tracking") !!}',//T Mã tracking
     type: 'text',
     width:'100px',
     key:"demo",
    },


    one_address: {
     type: 'checkbox',
     title:'{!! z_language("Cùng địa chỉ") !!}'
    },
    id:{
     title: '{!! z_language("ID") !!}',//T
     type: 'text',
     width:'100px',
    },
    session_id:{
     title: '{!! z_language("SessionId") !!}',//T
     type: 'text',
     width:'1px',
    },
    export:{
     title: '{!! z_language("Xuất") !!}',//T
     type: 'checkbox',
     width:'1px',
    },
    token:{
     title: '{!! z_language("Token") !!}',//T
     type: 'text',
     width:'0px',
    },
    admin:{
     title: '{!! z_language("Admin") !!}',//T
     type: 'text',
     width:'100px',
    },
    group:{
     title: '{!! z_language("group") !!}',//T
     type: 'text',
     width:'1px',
    },
    comment:{
     title: '{!! z_language("Ghi chú") !!}',//T
     type: 'text',
     width:'100px',
    },
       order_product_link:{
           title: '{!! z_language("Link SP") !!}',//T
           type: 'text',
           width:'100px',
       },
   };

   columnsAll[sheetName] = columns;
   let hide = hideprototy.hasOwnProperty(sheetName)?hideprototy[sheetName]:{};
   let _option = options.hasOwnProperty(sheetName)?options[sheetName]:{};
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
    if(hide.hasOwnProperty(i)){
     columns[i].width =hide[i]+"px";
    }else if(_option.hasOwnProperty('colums')){
     if(_option.colums.hasOwnProperty(i)){
      columns[i].width = "1px";
     }
    }else if(columnsWidth.hasOwnProperty(i)){
     columns[i].width =columnsWidth[i].width;
    }
   }
   function update(instance, cell, c, r, value) {
    console.log("update call");

    let data = {
     count:value.hasOwnProperty('count')?value.count:parseInt(instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.count.index, r]))),
     id:value.hasOwnProperty('id')?value.id:instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.product_id.index, r])),
     province:value.hasOwnProperty('province')?value.province:instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.province.index, r])),
    };
    data.province = data.province.trim();
    let total_price_buy =  0;
    let total_price =  0;

    let valueRow =  instance.jexcel.getRowData(r);
    let payMethod = getValuePayMethod(valueRow[columns.payMethod.index]);
    data.payMethod = payMethod;
    data.sheetName = sheetName;
    console.log("payMethod:"+payMethod);
    let price_buy_sale = parseInt(valueRow[columns.price_buy_sale.index]);
    console.log("price_buy_sale:"+price_buy_sale);
    let total_price_buy_all = 0;
    let Row = -1;

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

     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price.index, r]), total_price,false );
     data.total_price = total_price;

     total_price_buy = parseFloat(price_buy) * data.count + price_buy_sale;
     console.log(total_price_buy);

     data.total_price_buy = total_price_buy;


     let token = parseInt(valueRow[columns.token.index]);
     console.log("valueRow[columns.one_address.index]:"+valueRow[columns.one_address.index]);
     console.log("token:"+token);
     if(!valueRow[columns.one_address.index]){

      total_price_buy_all = total_price_buy;

      for(let iii = r+1;iii<10;iii++){
       let _data = instance.jexcel.getRowData(iii);
       if(_data && _data[columns.one_address.index]){
        console.log(_data);
        let total = parseInt(_data[columns.order_total_price_buy.index]);
        total_price_buy_all+= isNaN(total)?0:total;
       }else{
        break
       }
      }
      console.log(11111111111111);
      if(!isNaN(token)){
       Row = token;
      }
      console.log("Row:"+Row);
      token = "";
     }else{
      for(let iii = r-1;iii>=0;iii--){
       let _data = instance.jexcel.getRowData(iii);
       console.log(data);
       if(_data && !_data[columns.one_address.index]){
        token = iii;
        Row = token;
        break
       }
      }
     }
     console.log("token:"+token);
     console.log("Row:"+Row);
     data.total_price_buy = total_price_buy;

     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.token.index, r]), token);

     console.log("total_price_buy_all:"+total_price_buy_all);

     let confShipCou = GetShip(product.data,product.data.category_id,data.count,data.province,data.total_price_buy, data.payMethod,total_price_buy_all);

     console.log(confShipCou);

     setInterest(confShipCou.order_ship,confShipCou.order_ship_cou,confShipCou.total_price_buy);


    }
    function GetShip($product,$category_id,$count,$province,$total_price_buy,payMethod,total_price_buy_all) {

     let configShip = dataship.hasOwnProperty("cate_"+$category_id)?dataship["cate_"+$category_id]:[];
     if(total_price_buy_all == 0){
      total_price_buy_all = $total_price_buy;
     }
     console.log(configShip);
     console.log("$count:"+$count);
     console.log("$province:"+$province);
     console.log("$total_price_buy:"+$total_price_buy);

     let $ship_cou = -1;

     if(sheetName === "YAMADA"){
      if(payMethod === 1){
       if(valueRow[columns.one_address.index]){
        $ship_cou = 0;
       }else{
        $total_price_buy =  $total_price_buy + 330;
        total_price_buy_all =  total_price_buy_all + 330;
       }
      }else{
       $ship_cou = 0;
      }
     }else  if(sheetName === "OHGA"){
      if(payMethod === 1){
       if(valueRow[columns.one_address.index]){
        $ship_cou = 0;
       }else{
        $total_price_buy =  $total_price_buy + 330;
        $ship_cou = 330;
       }

      }else{
       $ship_cou = 0;
      }
     }else  if(sheetName === "FUKUI"){
      if(payMethod === 1){
       if(valueRow[columns.one_address.index]){
        $ship_cou = 0;
       }else{
        $total_price_buy =  $total_price_buy + 330;
        $ship_cou = 330;
       }
      }else{
       $ship_cou = 0;
      }
     }else if(sheetName === "KURICHIKU"){
      if(payMethod === 1){
       if(valueRow[columns.one_address.index]){
        $ship_cou = 0;
       }else{
        $total_price_buy =  $total_price_buy + 330;
        $ship_cou = 330;
       }
      }else{
       $ship_cou = 0;
      }
     }
     let arr_ship = [];
     for(let i in configShip){
      $is_IF_Start = IF_Start($count,configShip[i]);
      $is_IF_End =  IF_End($count,configShip[i]);
      if($is_IF_Start && $is_IF_End){
       $conf  =  configShip[i].config;
       for (let ii in $conf){
        $val = $conf[ii];
        $arr = $val['text'].split("-");
        for (let iii in $arr){
         $v = $arr[iii];
         if($province == $v){
          arr_ship.push([configShip[i],$val])
         }
        }
       }
      }
     }
     console.log(arr_ship);
     let $price_ship_default  = -1;
     let $price_ship  = -1;

     for (i in arr_ship){
      let $val = arr_ship[i];
      if($val[0].unit == 0 && $price_ship_default==-1){
       $price_ship_default =  $val[1]['value'];
      }else if($val[0].unit == $product.unit && $price_ship == -1){
       $price_ship = $val[1]['value'];
      }
     }

     console.log('$price_ship_default:'+$price_ship_default);
     console.log('$price_ship:'+$price_ship);

     let $ship = categorys[$category_id]?( categorys[$category_id].data.hasOwnProperty('ship'))?categorys[$category_id].data.ship:"-1":"-1";

     if( payMethod == 2 || payMethod == 3 ){
      $ship_cou = 0;
     }else{
      for (let i in datadaibiki){
       let $_val  = datadaibiki[i];
       if($ship == $_val.id){
        for(let ii in $_val.data){
         $units = $_val.data[ii];
         for(let iii in $units){
          $_unit = $units[iii];
          if($_unit){
           $is_IF_Start = IF_Start(total_price_buy_all,$_unit);
           $is_IF_End = IF_End(total_price_buy_all,$_unit);
           if($is_IF_Start && $is_IF_End){
            $ship_cou = $_unit.value;
           }
          }
         }
        }
       }
       if($ship_cou != -1){
        break;
       }
      }
     }

     let price_ship =  $price_ship!=-1?$price_ship:$price_ship_default;
     $ship_cou = $ship_cou == -1?0:$ship_cou;
     return {order_ship:parseInt(price_ship == -1?0:price_ship),order_ship_cou:parseInt($ship_cou),total_price_buy:$total_price_buy,total_price_buy_all:total_price_buy_all};
    }

    function setInterest(price_ship,order_ship_cou,total_price_buy){
     price_ship = price_ship * data.count;
     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship.index, r]),price_ship);

     // total_price = total_price+price_ship;
     total_price_buy = total_price_buy+price_ship;

     if(total_price_buy ===0 || total_price == 0){ return;}

     let one_address = instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.one_address.index, r]));
     if(one_address){
      //payMethod = 2;
     }
     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]), total_price_buy,false );
     if(payMethod == 3){
      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]), 0);
      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]), 0);
      // instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price_buy.index, r]), 0);
      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]), 0);
     }else if(payMethod == 2){
      let a = (parseInt(total_price_buy) - parseInt(total_price) - parseInt(price_ship));
      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]),a,false);
      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]),0,false);
     }else{
      if(one_address){
       let a = (parseInt(total_price_buy) - parseInt(total_price) - parseInt(price_ship));
       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]),a,false);
       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]),0,false);
      }else{
       let a = (parseInt(total_price_buy) - parseInt(total_price) - parseInt(price_ship) - order_ship_cou);
       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]),a,false);
       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]),order_ship_cou,false);
      }
     }
     if(Row>-1)
     {
      console.log("Update:Row"+Row);
      update(instance,cell, c, Row, {});
     }
    }
    console.log("SEND");

    {{--$.ajax({--}}
    {{--type: "POST",--}}
    {{--url:"{{ route('backend:shop_ja:order:excel:store') }}",--}}
    {{--data:{act:'ship',data:data} ,--}}
    {{--success: function (data) {--}}
    {{--console.log(data);--}}
    {{--if(data && data.length >0){--}}
    {{--console.log("oke");--}}
    {{--let price_ship = parseInt(data[0].data.price_ship);--}}

    {{--let ship_cou = parseInt(data[0].data.ship_cou);--}}

    {{--let total_price_buy = parseInt(data[0].data.total_price_buy);--}}

    {{--setInterest(price_ship < 0? 0 : price_ship,ship_cou< 0 ?0:ship_cou,total_price_buy);--}}
    {{--}--}}
    {{--},--}}
    {{--});--}}


    //    if(value.hasOwnProperty('lock') && value.lock.indexOf(columns.order_ship.index)){

    //     } else{
    //         setInterest(parseInt(valueRow[columns.order_ship.index]),parseInt(valueRow[columns.order_ship_cou.index]));
    //     }
   }
   let columns_index = Object.values(columns);

   let _data = InitData(data,config,columns_index,sheetName);
   let change = {col:-1,row:-1};
   let nestedHeaders = [];
   if(locks .hasOwnProperty(sheetName)){
    let _lock  = locks [sheetName];

    let dateNow = stringDate;
    if(_lock.action == 2 && _lock.date == dateNow ){
     let count = columns_index.length+4;
     count = count - 1;
     nestedHeaders.push({
      title: "Ngày xuất : "+_lock.date  ,
      colspan: 3,
      class:"demo"
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
    onload: function (e) {
     let td  = $(e).find('.jexcel tbody tr').find('td:first-child');
     td.click(function () {
      let parent = $(this).parent().data();
      if(parent.hasOwnProperty('info')){
       let info = parent.info;
       let company = parent.company;
       for(let tab in info){
        let dom = $("#tab_"+tab);
        let table = "<table class=\"table table-bordered\">";
        table+="<tr>";
        // table+='<td>Mã</td>';
        // table+='<td>Phiên</td>';
        table+='<td>{!! z_language('Người lập') !!}</td>';
        table+='<td>{!! z_language("Ho Tên") !!}</td>';
        table+='<td>{!! z_language("Địa chỉ") !!}</td>';
        table+='<td>{!! z_language("Tỉnh") !!}</td>';
        table+='<td>{!! z_language("Ngày tạo") !!}</td>';
        table+='<td>{!! z_language("Số điện thoại") !!}</td>';
        table+='<td>{!! z_language("Công ty") !!}</td>';
        table+='<td>{!! z_language("Sản phẩm") !!}</td>';

        table+='<td>{!! z_language("Trạng thái") !!}</td>';
        table+='<td>{!! z_language("Xuất") !!}</td>';
        table+='<td>{!! z_language("Đường dẫn") !!}</td>';
        table+="</tr>";
        for(let pos in info){
         for(let com in info[pos]){
          if(com!=company) continue;
          for(let index in info[pos][com]){
           let val = info[pos][com][index];
           table+="<tr>";
           table+='<td>'+(info_admin.hasOwnProperty(val.admin_id)?info_admin[val.admin_id].name:"Không xác định")+'</td>';
           table+='<td>'+val.fullname+'</td>';
           table+='<td>'+val.address+'</td>';
           table+='<td>'+val.province+'</td>';
           table+='<td>'+val.order_create_date+'</td>';
           table+='<td>'+val.phone+'</td>';
           table+='<td>'+val.company+'</td>';
           table+= "<td><table class=\"table table-bordered\">";
           table+='<td>'+val.product_title+'</td>';
           table+='<td>'+val.count+'</td>';
           if(val.hasOwnProperty('items')){
            for(let _index in val.items){
             table+="<tr>";
             table+='<td>'+val.items[_index].product_title+'</td>';
             table+='<td>'+val.items[_index].count+'</td>';
             table+="</tr>";
            }
           }
           table+="</table></td>";
           table+='<td>'+(val.public == 1?"Đơn":"Nháp")+'</td>';
           table+='<td>'+(val.export == 1?"Xuất":"Chưa")+'</td>';
           table+='<td>'+val.order_link+'</td>';
           table+="</tr>";
          }
         }
        }

        table+="</table>";
        dom.html(table);
       }
       $('#modal-default').modal('show');
      }
     });
    },
    nestedHeaders:[
     nestedHeaders
    ],
    contextMenu: function(obj, x, y, e) {
     var items = [];
        return items;
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
     $('.onselection').hide();
     $("#col-row-review").data({"x":x1,y:y1}).val(cellName1);
     let val = instance.jexcel.getValue(cellName1);

     if(columns_index[x1] && columns_index[x1].type === "dropdown"){
      $("#value-review").hide();
      $html = $("<div>");
      $("#zoe-dropdown-review").show().html($html);

      // jSuites.dropdown($html[0], {
      //     data:columns_index[x1].source,
      //     autocomplete: columns_index[x1].hasOwnProperty('autocomplete'),
      //     width:'100%',
      //     onchange:function (el, a, oldValue, Value) {
      //         console.log(Value);
      //         instance.jexcel.setValue(jexcel.getColumnNameFromId([x1, y1]), Value);
      //     },
      // }).setValue(val);

     }else if(columns_index[x1].key === "order_info"){

      //NGUYEN V 様 00 日に 0000 円入金済み
      let vals = val.trim().split(' ');
      console.log(vals);

      let a = val.trim()+"";
      let b = ["様","日に","円入金済み"];

      vals=[];
      if(a.length > 0){

       for(let i = 0; i<b.length;i++){
        let results = a.split(b[i]);
        if(results.length == 0) break;
        a = results[1];
        let val = results[0].trim();
        if(val.length > 0){
         vals.push(val);
        }
       }
      }


      $("#info_payment").show();
      let html  = "<table>";
      html+="<tr>";

      html+="<th>";
      html+='<input type="text" class="form-control name" value="'+(vals[0]?vals[0]:"")+'">';
      html+="</th>";

      html+="<th>";
      html+='様';
      html+="</th>";
      html+="<th>";
      html+='<input type="text" class="form-control date" value="'+(vals[1]?vals[1]:"")+'">';
      html+="</th>";

      html+="<th>";
      html+='日に';
      html+="</th>";

      html+="<th>";
      html+='<input type="text" class="form-control price" value="'+(vals[2]?vals[2]:"")+'">';
      html+="</th>";

      html+="<th>";
      html+='円入金済み';
      html+="</th>";

      html+="<td>";
      html+='&nbsp;<button type="button" class="btn">{!! z_language('Lưu') !!}</button>';
      html+="</td>";
      html+="</tr>";
      html+="</table>";
      var dom = $(html);
      dom.find('button').click(function () {
       let th = dom.find('tr th');
       let vals = "";
       console.log(th);
       for(let i = 0; i< th.length ; i++){
        if(i%2===0){
         vals+=" "+$(th[i]).find('input').val().trim();
        }else{
         vals+=" "+$(th[i]).text().trim();
        }
       }
       setTimeout(function () {
        instance.jexcel.setValue(jexcel.getColumnNameFromId([x1, y1]), vals.trim());
       },1000);
      });
      $("#info_payment").html(dom);
     }
     else{
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
      let countAddress = 0;
      for(let k in instance.jexcel.rows){
       let _val  = instance.jexcel.getRowData(k);
       if(value[columns.fullname.index].length > 0 && value[columns.fullname.index].replace(/\s+/g, ' ') === _val[columns.fullname.index].replace(/\s+/g, ' ')){
        count++;
       }
       if(value[columns.address.index].length > 0 && value[columns.address.index].replace(/\s/g, '') === _val[columns.address.index].replace(/\s/g, '')){
        countAddress++;
       }
      }
      instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])).classList.remove('error');
      if(count > 1) instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])).classList.add('error');

      instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.address.index, row])).classList.remove('error');
      if(countAddress > 1) instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.address.index, row])).classList.add('error');

      let vvv = getValuePayMethod(value[columns.payMethod.index]);
      let parent = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.payMethod.index, row]))).parent();
      parent.removeClass('pay-method-oke');
      if(vvv === 2){
       parent.addClass('pay-method-oke');
      }

      parent.removeClass('has_error');
      if(vvv === 2){
       let img = value[columns.image.index];
       let order_info = value[columns.order_info.index];
       let self = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.image.index, row]))).parent();
       if(img.length === 0 || order_info.length === 0){
        self.addClass('has_error');
       }
      }

      let _province = value[columns.province.index]+"".trim();
      let _address = value[columns.address.index]+"".replace(/\s/g, '').trim();
      let _fullname = value[columns.fullname.index]+"".replace(/\s+/g, ' ').trim();

      $col = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.province.index, row])));

      $col.removeClass('bor-error');
      if(_province.length <= 0){
       $col.addClass('bor-error')
      }
      $col = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.address.index, row])));
      $col.removeClass('bor-error')
      if(_address.length <= 0){
       $col.addClass('bor-error')
      }
      $col = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])));
      $col.removeClass('bor-error');

      $colorder_link = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.order_link.index, row])));
      $colorder_link.removeClass('bor-error');


      if(_fullname.length <= 0){
       $col.addClass('bor-error')
      }else{

          let order_link = value[columns.order_link.index]+"".trim();
          if(order_link.length === 0){
              $colorder_link.addClass('bor-error');
              if(!parent.hasClass('has_error')){
                  parent.addClass('has_error');
              }
          }
      }

      parent.removeClass('has_export');
      let _export = value[columns.export.index];
      if((_export+"").toString().length > 0){
       if(_export){
        parent.addClass('has_export');
       }
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
       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.count.index, r]),1);
       if(change.col == c){
        change = {col:-1,row:-1};
        update(instance, cell, c, r,{
         count:1,
         id:dropdown[value].data.id
        });
       }
      }
     }else if(c === columns.count.index || c === columns.fullname.index || c === columns.zipcode.index || c === columns.price_buy_sale.index ||
             c === columns.order_ship.index || c === columns.order_ship_cou.index || c === columns.one_address.index){
      if(change.col == c){
       if(c === columns.fullname.index || c === columns.zipcode.index){
        change = {col:columns.province.index,row:r};
       }else{
        change = {col:-1,row:-1};
        update(instance, cell, c, r,{

        });
       }
      }
     }else if(c === columns.province.index){
      if(change.col == c){
       change = {col:-1,row:-1};
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
       change = {col:-1,row:-1};
       update(instance, cell, c, r,{});
      }
     }
    },
   };
  }

  function KOGYJA() {
   let sheetName = "KOGYJA";
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
   let dropdown = dataproduct.hasOwnProperty(sheetName)?dataproduct[sheetName]:{};
   let index = 0;

   let columns = {
    status: {
     type: 'checkbox',
     title:'{!! z_language('Trạng thái') !!}'
    },
    image:{
     title:'{!! z_language('Ảnh') !!}',
     type:'image',
     width:"50px",
     hide:true,
    },
       order_info:{
           title: '{!! z_language("Thông tin chuyển khoản") !!}',//T Thông tin chuyển khoản
           type: 'text',
           width:'100px',
           key:"demo",
       },
    timeCreate:{
     title: '{!! z_language("Ngày đặt hàng") !!}',//A ngày đặt hàng
     type: 'calendar',
     width:'100px',
     options: { format:'DD/MM/YYYY' },
     value:['date','now'],
     // row:"0",
    },
    payMethod:{
     title: '{!! z_language("Phương thức thanh toán") !!}',//B Phương thức thanh toán
     type:'dropdown',
     source:[
      "代金引換",
      "銀行振込",
      "決済不要",
     ],
     width:'130px',
     value:['product','this','source',0],
     // row:"0",
    },
    phone:{
     title: '{!! z_language("Số điện thoại") !!}',//C Số điện thoại
     type: 'text',
     width:'100px',
     value:"070-8409-5968",
     // row:"0",
    },
    zipcode:{
     title: '{!! z_language("Mã bưu điện") !!}',//D Mã bưu điện
     type: 'text',
     width:'60px',

    },
    province:{
     title: '{!! z_language("Tỉnh/TP") !!}',//E Tỉnh/TP
     type: 'text',
     width:'200px',

    },
    address:{
     title: '{!! z_language("Địa chỉ giao hàng") !!}',//F Địa chỉ giao hàng
     type: 'text',
     width:'250px',
     key:"demo",
    },
    fullname:{
     title: '{!! z_language("Họ tên người nhận") !!}',//G Họ tên người nhận
     type: 'text',
     width:'150px',
     key:"demo",
    },
       order_link:{
           title: '{!! z_language("Đường dẫn") !!}',//T Thông tin chuyển khoản
           type: 'text',
           width:'100px',
       },
    product_id:{
     title: '{!! z_language("Mã SP") !!}',//H Mã SP
     type: 'text',
     width:'100px',
     read:true,
     value:"0",
     // row:"0",
    },
    product_name:{
     title: '{!! z_language("Tên SP") !!}',//I Tên SP
     type:'dropdown',
     source:Object.values(dropdown),
     autocomplete:true,
     width:'140px',
     value:"0",
     // row:"0",
    },
    count:{
     title: '{!! z_language("SL Size") !!}',//K SL
     type: 'numeric',
     width:'100px',
     value:1,
     // row:"0",
    },
    total_count:{
     title: '{!! z_language("SL") !!}',//K SL
     type: 'numeric',
     width:'100px',
     value:37,
     // row:"0",
    },
    price:{
     title: '{!! z_language("Giá nhập") !!}',//J Giá nhập
     type: 'numeric',
     width:'100px',
     value:0,
     // row:"0",
    },
    price_buy:{
     title: '{!! z_language("Giá bán") !!}',//J Giá nhập
     type: 'numeric',
     width:'100px',
     value:0,
     // row:"0",
    },
    order_date:{
     title: '{!! z_language("Ngày nhận") !!}',//L Ngày nhận
     type:'calendar',
     options: { format:'DD/MM/YYYY'},
     value:['date','nowSum',2],
     width:'100px',
     // row:"0",
    },
    order_hours:{
     title: '{!! z_language("Giờ nhận") !!}',//M Giờ nhận
     type: 'dropdown',
     source:['8:00 ~ 12:00','14:00～16:00','16:00～18:00','18:00～20:00','19:00～21:00'],
     value:['product','this','source',4],
     width:'150px',
     // row:"0",
    },
    order_ship:{
     title: '{!! z_language("Phí ship") !!}',//N Phí ship
     type: 'numeric',
     width:'100px',
     value:0,
     // row:"0",
    },
    order_total_price:{
     title: '{!! z_language("Tổng giá nhập") !!}',//O Tổng giá nhập
     type: 'numeric',
     width:'100px',
     value:0,
     // row:"0",
    },
    price_buy_sale:{
     title: '{!! z_language("Tăng Giảm") !!}',//J Giá nhập
     type: 'numeric',
     width:'100px',
     value:0,
     // row:"0",
    },
    order_total_price_buy:{
     title: '{!! z_language("Total Bán") !!}',//P Giá bán
     type: 'numeric',
     width:'100px',
     value:0,
     // row:"0",
    },
    order_ship_cou:{
     title: '{!! z_language("Phí giao hàng") !!}',//P Phí giao hàng
     type: 'numeric',
     width:'100px',
     value:0,
     // row:"0",
    },
    order_price:{
     title: '{!! z_language("Lợi nhuận") !!}',//P Lợi nhuận
     type: 'numeric',
     width:'100px',
     value:0,
     // row:"0",
    },
    order_rate:{
     title: '{!! z_language("Lợi nhuận CTV") !!}',//P
     type: 'numeric',
     width:'100px',
     value:0
    },
    order_tracking:{
     title: '{!! z_language("Mã tracking") !!}',//T Mã tracking
     type: 'text',
     width:'100px',
     key:"demo",
     row:"0",
    },


    one_address: {
     type: 'checkbox',
     title:'{!! z_language("Cùng địa chỉ") !!}'
    },
    id:{
     title: '{!! z_language("ID") !!}ID',//T
     type: 'text',
     width:'100px',
    },
    type:{
     title: 'Type',//T
     type: 'text',
     width:'100px',
    },
    session_id:{
     title: '{!! z_language("SessionId") !!}',//T
     type: 'text',
     width:'100px',
    },
    export:{
     title: '{!! z_language("Xuất") !!}',//T
     type: 'checkbox',
     width:'100px',
    },
    token:{
     title: '{!! z_language("Token") !!}',//T title: '',//T
     type: 'text',
     width:'100px',
    },
    position:{
     title: '{!! z_language("Vị trí") !!}',//T title: '',//T title: 'Position',//T
     type: 'text',
     width:'100px',
    },
    admin:{
     title: '{!! z_language("Người tạo") !!}',//T title: '',//T title: 'Position',//T
     type: 'text',
     width:'100px',
    },
    group:{
     title: '{!! z_language("Đơn gộp") !!}',//T title: '',//T title: 'Position',//T
     type: 'text',
     width:'1px',
    },
    comment:{
     title: '{!! z_language("Ghi chú") !!}',//T title: '',//T title: 'Position',//T
     type: 'text',
     width:'100px',
    },
       order_product_link:{
           title: '{!! z_language("Link SP") !!}',//T
           type: 'text',
           width:'100px',
       },
   };
   columnsAll[sheetName] = columns;
   let hide = hideprototy.hasOwnProperty(sheetName)?hideprototy[sheetName]:{};

   let _option = options.hasOwnProperty(sheetName)?options[sheetName]:{};
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
    if(hide.hasOwnProperty(i)){
     columns[i].width =hide[i]+"px";
    }else if(_option.hasOwnProperty('colums')){
     if(_option.colums.hasOwnProperty(i)){
      columns[i].width = "1px";
     }
    }else if(columnsWidth.hasOwnProperty(i)){
     columns[i].width =columnsWidth[i].width;
    }
   }

   function GetShip($product,$category_id,$count,$province,$total_price_buy,payMethod,total_price_buy_all) {

    let configShip = dataship.hasOwnProperty("cate_"+$category_id)?dataship["cate_"+$category_id]:[];
    console.log(configShip);
    console.log("$count:"+$count);
    console.log("$province:"+$province);
    console.log("$total_price_buy:"+$total_price_buy);

    let arr_ship = [];
    if(total_price_buy_all == 0){
     total_price_buy_all = $total_price_buy;
    }
    console.log("total_price_buy_all:"+total_price_buy_all);
    for(let i in configShip){
     $is_IF_Start = IF_Start($count,configShip[i]);
     $is_IF_End =  IF_End($count,configShip[i]);
     if($is_IF_Start && $is_IF_End){
      $conf  =  configShip[i].config;
      for (let ii in $conf){
       $val = $conf[ii];
       $arr = $val['text'].split("-");
       for (let iii in $arr){
        $v = $arr[iii];
        if($province == $v){
         arr_ship.push([configShip[i],$val])
        }
       }
      }
     }
    }

    console.log(arr_ship);

    $price_ship_default  = -1;
    $price_ship  = -1;

    for (i in arr_ship){
     $val = arr_ship[i];
     if($val[0].unit == 0 && $price_ship_default==-1){
      $price_ship_default =  $val[1]['value'];
     }else if($val[0].unit == $product.unit && $price_ship == -1){
      $price_ship = $val[1]['value'];
     }
    }

    console.log('$price_ship_default:'+$price_ship_default);
    console.log('$price_ship:'+$price_ship);

    let $ship = categorys[$category_id]?( categorys[$category_id].data.hasOwnProperty('ship'))?categorys[$category_id].data.ship:"-1":"-1";

    let $ship_cou = -1;

    if( payMethod == 2 || payMethod == 3 ){
     $ship_cou = 0;
    }else{
     for (let i in datadaibiki){
      $_val  = datadaibiki[i];

      if($ship == $_val.id){
       for(let ii in $_val.data){
        $units = $_val.data[ii];
        for(let iii in $units){
         $_unit = $units[iii];
         if($_unit){
          $is_IF_Start = IF_Start(total_price_buy_all,$_unit);
          $is_IF_End = IF_End(total_price_buy_all,$_unit);
          if($is_IF_Start && $is_IF_End){
           $ship_cou = $_unit.value;
          }
         }
        }
       }
      }
      if($ship_cou != -1){
       break;
      }
     }
    }
    let price_ship =  $price_ship!=-1?$price_ship:$price_ship_default;
    $ship_cou = $ship_cou == -1?0:$ship_cou;
    return {order_ship:parseInt(price_ship == -1?0:price_ship),order_ship_cou:parseInt($ship_cou)};
   }


   function update(instance, cell, c, r, value,cb) {
    console.log("update call:"+r);

    let data = {
     count:parseInt(instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.count.index, r]))),
     id:instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.product_id.index, r])),
     province:instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.province.index, r])),
    };

    data.province =   data.province.trim();
    console.log(data);
    let valueRow =  instance.jexcel.getRowData(r);
    if(isNaN(data.count) || data.count === 0 ){
      if(
              (valueRow[columns.product_id.index]+"").trim().length > 0 &&
              (valueRow[columns.product_name.index]+"").trim().length > 0 &&
              (valueRow[columns.product_name.index]+"").trim()!="0" &&
              (valueRow[columns.product_id.index]+"").trim()!="0"){
       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.count.index, r]),1);
       data.count = 1;
      }else{
       data.count = 1;
          instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.count.index, r]),1);
      }
     }
     if(isNaN(data.count) || data.count < 1 ){
         data.count = 1;
         instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.count.index, r]),1);
     }

    let total_price_buy =  0;
    let total_price =  0;


    // let total_count = instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.count.index, index]));

    //let order_total_price_buy = instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, index]));

    // if(isNaN(total_count)){
    //     total_count = 1;
    // }



    let payMethod = getValuePayMethod(valueRow[columns.payMethod.index]);
    data.payMethod = payMethod;
    data.sheetName = sheetName;
    console.log("payMethod:"+payMethod)

    let price_buy_sale = parseInt(valueRow[columns.price_buy_sale.index]);

    if(isNaN(price_buy_sale)){
     price_buy_sale = 0;
     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price_buy_sale.index, r]),price_buy_sale);
    }
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
     total_price = parseFloat(price) * data.count ;
     total_price_buy = parseFloat(price_buy) * data.count + price_buy_sale;

     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]),0);
     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price.index, r]),total_price);

     data.total_price_buy = total_price_buy;
     data.total_price = total_price;




    }else{
     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price_buy.index, r]),0);
     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price.index, r]),0);
     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]),0);
     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price.index, r]),0);
    }

    cb();

   }

   function isInfo(instance,r) {
    return instance.getValue(jexcel.getColumnNameFromId([columns.type.index, r])) == "Info";
    //return $(instance.getCell(jexcel.getColumnNameFromId([columns.product_name.index, r]))).parent().hasClass('info');
   }
   function isFooter(instance,r) {
    // return $(instance.getCell(jexcel.getColumnNameFromId([columns.product_name.index, r]))).parent().hasClass('footer');
    return instance.getValue(jexcel.getColumnNameFromId([columns.type.index, r])) == "Footer";
   }
   function indexFist(instance,r) {//instance.jexcel
    r = parseInt(r);
    let _r1 = r;
    let result = {start:0,end:0,count:0,row:r};
    do{
     if(isInfo(instance,_r1)){
      result.start = parseInt(_r1);
      result.count++;
      break;
     }
     if(isFooter(instance,_r1-1)){
      result.start = parseInt(_r1);
      result.count++;
      break;
     }
     _r1--;
    }while (_r1 >= 0);
    let _r2 = r;

    while (_r2 < instance.rows.length){
     if(isFooter(instance,_r2)){
      result.end = parseInt(_r2);
      result.count++;
      break;
     }
     _r2++;
    }
    return result;
   }
   function isRow(_data) {
    return (_data[columns.fullname.index].length > 0 ||
            _data[columns.payMethod.index].length >0 ||
            _data[columns.address.index].length >0 ||
            _data[columns.zipcode.index].length >0 ||
            _data[columns.phone.index].length >0);
   }
   function isProduct(_data) {
    return ((_data[columns.product_id.index]+"").trim().length >0 &&(_data[columns.product_name.index]+"").trim().length > 0  &&(_data[columns.count.index]+"").trim().length > 0 );
   }
   function IsEmpty(_data) {
    return ((_data[columns.product_id.index]+"").trim() === "0" || (_data[columns.product_id.index]+"").trim() =="" || (_data[columns.product_name.index]+"").trim() === "0" || (_data[columns.product_name.index]+"").trim() === "");
   }
   function update_count(instance, cell, c, r, value) {
    let totalCount = 0;
    let totalCountAll = 0;
    let totalPriceBuy = 0;
    let totalPrice = 0;
    let product_id = 0;
    let totalPriceBuyStart = 0;
    let totalLoiNhuan = 0;
    let keyGroup = [{!! auth()->user()->id!!}];

    for(let i = value.start ; i < value.end ; i++){
     let _data =  instance.jexcel.getRowData(i);
     if(i == value.start){
      keyGroup.push([_data[columns.fullname.index],_data[columns.address.index],_data[columns.province.index]]);
     }
     if(
             (_data[columns.product_id.index]+"").trim().length > 0 &&
             (_data[columns.product_name.index]+"").trim().length > 0 &&
             (_data[columns.product_name.index]+"").trim()!="0" &&
             (_data[columns.product_id.index]+"").trim()!="0"){
      let _count = parseInt(_data[columns.count.index]);

      let product = dropdown[parseInt(_data[columns.product_name.index])];

      if(!isNaN(_count)){
       totalCountAll+= _count * parseInt(product.data['value']);
       totalCount+= _count;
      }else{
       _count = 0;
      }
      let price_buy_sale = parseInt(_data[columns.price_buy_sale.index]);
      if(isNaN(price_buy_sale)){
       price_buy_sale = 0;
      }
      let _price_buy = parseInt(_data[columns.price_buy.index]) * _count + price_buy_sale;
      if(!isNaN(_price_buy)){
       totalPriceBuy+=_price_buy;
      }else{
       _price_buy = 0;
      }
      let _price = parseInt(_data[columns.price.index]) * _count;
      if(!isNaN(_price)){
       totalPrice+=_price;
      }else{
       _price = 0;
      }
      if(product_id === 0 || isNaN(product_id)){
       product_id = parseInt(_data[columns.product_name.index]);
      }
      if(i > value.start){
       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, i]),_price*-1);
       totalLoiNhuan+=_price*-1;
      }
      keyGroup.push([product_id,_count , i]);
     }
    }
    let stringKey = $.md5(JSON.stringify(keyGroup));
    let position=0;
    for(let i = value.start ; i <= value.end ; i++){
     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.position.index, i]),position++);
     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.token.index, i]),stringKey);
    }
    console.log(stringKey);
    console.log(keyGroup);
    let confShipCou = {order_ship:0,order_ship_cou:0};

    console.log("product_id:"+product_id);

    let province = (instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.province.index, value.start]))+"").trim();

    let payMethod = getValuePayMethod(instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.payMethod.index, value.start])));
    let one_address = instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.one_address.index, value.start]));

    let total_price_buy_all = 0;
    let Row = -1;


    let Group = parseInt(instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.group.index, value.start])));

    console.log(columns.group.index+'_one_addressL:'+one_address);
    console.log(columns.group.index+':Group:'+Group);

    console.log("totalPriceBuy:"+totalPriceBuy);

    if(one_address){// true
     for(let iii = r-1;iii>=0;iii--){
      let _data = instance.jexcel.getRowData(iii);

      if(_data && !_data[columns.one_address.index] && _data[columns.type.index] === "Info"){
       console.log(_data);
       Group = iii;
       Row = Group;
       break
      }
     }
    }else{
     total_price_buy_all = totalPriceBuy;

     for(let iii = r+1;iii<100;iii++){// bỏ cùng địa chỉ
      let _data = instance.jexcel.getRowData(iii);
      if(_data && _data[columns.type.index] === "Info"){
       if(_data[columns.one_address.index]){
        console.log(_data);
        let total = parseInt(_data[columns.order_total_price_buy.index]);
        total_price_buy_all+= isNaN(total)?0:total;
       }else{
        break
       }
      }

     }

     if(!isNaN(Group)){
      Row = Group;
     }

     console.log("Row:"+Row);
     Group = "";
    }

    console.log("total_price_buy_all:"+total_price_buy_all);
    console.log("Group:"+Group);

    instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.group.index, value.start]), isNaN(Group)?"":Group);

    if(dropdown.hasOwnProperty(product_id)){
     confShipCou = GetShip(dropdown[product_id].data,dropdown[product_id].data.category_id,totalCountAll,province,totalPriceBuy,payMethod,total_price_buy_all);
    }
    let v = 0;

    if(totalCountAll >=1 ){
     if( totalCountAll <= 5){
      v = 37;
     }else if( totalCountAll <= 10){
      v = 74;
     }else if( totalCountAll > 10){
      v = 142;
     }
    }
    console.log("totalPriceBuy:"+totalPriceBuy);

    if(payMethod == 3){
     totalPriceBuy = 0;
    }else{
     if(one_address){
      confShipCou.order_ship_cou = 0;
     }else{
      totalPriceBuy = totalPriceBuy + confShipCou.order_ship_cou;
     }
    }

    instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.count.index, value.end]),totalCount);
    instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.total_count.index, value.start]),v);
    instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price.index, value.end]),totalPrice);

    instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, value.start]),totalPriceBuy);

    instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship.index, value.start]),confShipCou.order_ship);

    instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, value.start]),confShipCou.order_ship_cou);
    _data =  instance.jexcel.getRowData(value.start);
    let order_total_price = parseInt( _data[columns.order_total_price.index]);

    console.log(confShipCou);
    console.log("order_total_price:"+order_total_price);
    console.log("totalCount:"+totalCount);
    console.log("totalPriceBuy:"+totalPriceBuy);
    console.log("totalPrice:"+totalPrice);
    let order_price = totalPriceBuy - (isNaN(order_total_price)?0:order_total_price) - confShipCou.order_ship - confShipCou.order_ship_cou - v;

    instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, value.start]),order_price);
    instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, value.end]),order_price+totalLoiNhuan);

    if(Row > -1){

     let index = indexFist(instance.jexcel,Row);
     update(instance, null, columns.one_address.index, Row ,{},function () {
      update_count(instance, null ,columns.one_address.index, Row,index);
     });
    }
   }

   let columns_index = Object.values(columns);
   //    console.log(data);
   console.log(config);
   let _data = InitData(data,config,columns_index,sheetName);
   console.log(columns_index);
   console.log(_data);
   let change = {col:-1,row:-1};

   let lock = {};
   let nestedHeaders = [];
   if(locks .hasOwnProperty(sheetName)){
    let _lock  = locks [sheetName];
    let dateNow = stringDate;
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
    minDimensions:[35,42],
    columns:Object.values(columns),
    data:_data,
    onload: function (e) {
     let td  = $(e).find('.jexcel tbody tr').find('td:first-child');
     td.click(function () {
      let parent = $(this).parent().data();

      if(parent.hasOwnProperty('info')){
       let info = parent.info;
       let company = parent.company;



       for(let tab in info){
        let dom = $("#tab_"+tab);
        let table = "<table class=\"table table-bordered\">";
        table+="<tr>";
        // table+='<td>Mã</td>';
        // table+='<td>Phiên</td>';
        table+='<td>{!! z_language('Người lập') !!}</td>';
        table+='<td>{!! z_language("Ho Tên") !!}</td>';
        table+='<td>{!! z_language("Địa chỉ") !!}</td>';
        table+='<td>{!! z_language("Tỉnh") !!}</td>';
        table+='<td>{!! z_language("Ngày tạo") !!}</td>';
        table+='<td>{!! z_language("Số điện thoại") !!}</td>';
        table+='<td>{!! z_language("Công ty") !!}</td>';
        table+='<td>{!! z_language("Sản phẩm") !!}</td>';

        table+='<td>{!! z_language("Trạng thái") !!}</td>';
        table+='<td>{!! z_language("Xuất") !!}</td>';
        table+='<td>{!! z_language("Đường dẫn") !!}</td>';
        table+="</tr>";
        for(let pos in info){
         for(let com in info[pos]){
          if(com!=company) continue;
          for(let index in info[pos][com]){
           let val = info[pos][com][index];
           table+="<tr>";
           table+='<td>'+(info_admin.hasOwnProperty(val.admin_id)?info_admin[val.admin_id].name:"Không xác định")+'</td>';
           table+='<td>'+val.fullname+'</td>';
           table+='<td>'+val.address+'</td>';
           table+='<td>'+val.province+'</td>';
           table+='<td>'+val.order_create_date+'</td>';
           table+='<td>'+val.phone+'</td>';
           table+='<td>'+val.company+'</td>';
           table+= "<td><table class=\"table table-bordered\">";
           table+='<td>'+val.product_title+'</td>';
           table+='<td>'+val.count+'</td>';
           if(val.hasOwnProperty('items')){
            for(let _index in val.items){
             table+="<tr>";
             table+='<td>'+val.items[_index].product_title+'</td>';
             table+='<td>'+val.items[_index].count+'</td>';
             table+="</tr>";
            }
           }
           table+="</table></td>";
           table+='<td>'+(val.public == 1?"Đơn":"Nháp")+'</td>';
           table+='<td>'+(val.export == 1?"Xuất":"Chưa")+'</td>';
           table+='<td>'+val.order_link+'</td>';
           table+="</tr>";
          }
         }
        }

        table+="</table>";
        dom.html(table);
       }
       $('#modal-default').modal('show');
      }
     });
    },
    nestedHeaders:[
     nestedHeaders
    ],
    contextMenu: function(obj, x, y, e) {
     var items = [];
        return items;
     if (y == null) {
      // Insert a new column
      // if (obj.options.allowInsertColumn === true) {
      //     items.push({
      //         title:obj.options.text.insertANewColumnBefore,
      //         onclick:function() {
      //             obj.insertColumn(1, parseInt(x), 1);
      //         }
      //     });
      // }
      // if (obj.options.allowInsertColumn === true) {
      //     items.push({
      //         title:obj.options.text.insertANewColumnAfter,
      //         onclick:function() {
      //             obj.insertColumn(1, parseInt(x), 0);
      //         }
      //     });
      // }

      // Delete a column
      // if (obj.options.allowDeleteColumn === true) {
      //     items.push({
      //         title:obj.options.text.deleteSelectedColumns,
      //         onclick:function() {
      //             obj.deleteColumn(obj.getSelectedColumns().length ? undefined : parseInt(x));
      //
      //
      //         }
      //     });
      // }

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
      // if (obj.options.columnSorting == true) {
      //
      //     items.push({ type:'line' });
      //
      //     items.push({
      //         title:obj.options.text.orderAscending,
      //         onclick:function() {
      //             obj.orderBy(x, 0);
      //         }
      //     });
      //     items.push({
      //         title:obj.options.text.orderDescending,
      //         onclick:function() {
      //             obj.orderBy(x, 1);
      //         }
      //     });
      // }
     } else {
      // Insert new row
      // if (obj.options.allowInsertRow === true) {
      //     items.push({
      //         title:obj.options.text.insertANewRowBefore,
      //         onclick:function() {
      //             obj.insertRow(1, parseInt(y), 1);
      //         }
      //     });
      //
      //     items.push({
      //         title:obj.options.text.insertANewRowAfter,
      //         onclick:function() {
      //             obj.insertRow(1, parseInt(y));
      //
      //
      //         }
      //     });
      // }

      if (obj.options.allowDeleteRow === true) {
       items.push({
        title:obj.options.text.deleteSelectedRows,
        onclick:function() {

         let index = indexFist(obj,y);

         if(index.start ===  index.end){
          obj.deleteRow(obj.getSelectedRows().length ? undefined : parseInt(y));

         }else{
          let type = obj.getValue(jexcel.getColumnNameFromId([columns.type.index, y]));
          console.log(type);
          change = {col:-1,row:-1};
          if(type === "Info" || type ==="Footer"){
           let count = 0;
           for(let i = index.start; i <= index.end;i++){
            count++;
           }
           obj.deleteRow(index.start,count);
          }else{
           let count = parseInt( obj.getValue(jexcel.getColumnNameFromId([columns.count.index, y])));
           if(!isNaN(count)){
            obj.deleteRow(obj.getSelectedRows().length ? undefined : parseInt(y));
            let _instance = {
             jexcel:obj
            };
            index.end = index.end - 1;
            update(_instance, null, columns.count.index, index.start ,{},function () {
             update_count(_instance, null ,columns.count.index, index.start,index);
            });
           }
          }
         }
        }
       });
      }

      // if (x) {
      //     if (obj.options.allowComments === true) {
      //         items.push({ type:'line' });
      //
      //         var title = obj.records[y][x].getAttribute('title') || '';
      //
      //         items.push({
      //             title: title ? obj.options.text.editComments : obj.options.text.addComments,
      //             onclick:function() {
      //                 obj.setComments([ x, y ], prompt(obj.options.text.comments, title));
      //
      //             }
      //         });
      //
      //         if (title) {
      //             items.push({
      //                 title:obj.options.text.clearComments,
      //                 onclick:function() {
      //                     obj.setComments([ x, y ], '');
      //                 }
      //             });
      //         }
      //     }
      // }
      // items.push({
      //     title:"Reset",
      //     onclick:function() {
      //         let parentRow = $(obj.getCell(jexcel.getColumnNameFromId([columns.product_name.index, y]))).parent();
      //
      //         parentRow.removeClass('action');
      //         parentRow.removeClass('footer');
      //
      //         obj.setValue(jexcel.getColumnNameFromId([columns.fullname.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.address.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.payMethod.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.phone.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.province.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.zipcode.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.product_id.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.product_name.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.count.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.total_count.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.order_date.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.order_hours.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.price_buy_sale.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.price.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.price_buy.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.order_ship.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.order_total_price.index, y]),"");
      //
      //
      //     }
      // });
      // items.push({
      //     title:"Set Info",
      //     onclick:function() {
      //         console.log(obj);
      //         obj.setValue(jexcel.getColumnNameFromId([columns.fullname.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.address.index, y]),"");
      //
      //         if(columns_index[columns.payMethod.index] && columns_index[columns.payMethod.index].hasOwnProperty('value')){
      //             obj.setValue(jexcel.getColumnNameFromId([columns.payMethod.index, y]),columns_index[columns.payMethod.index].value);
      //         }
      //         if(columns_index[columns.payMethod.index] && columns_index[columns.phone.index].hasOwnProperty('value')){
      //             obj.setValue(jexcel.getColumnNameFromId([columns.phone.index, y]),columns_index[columns.phone.index].value);
      //         }
      //
      //         obj.setValue(jexcel.getColumnNameFromId([columns.province.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.zipcode.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.product_id.index, y]),"0");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.product_name.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.count.index, y]),"0");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.order_ship.index, y]),"0");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, y]),"0");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, y]),"0");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.order_total_price.index, y]),"0");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.order_price.index, y]),"0");
      //
      //         if(columns_index[columns.order_date.index] && columns_index[columns.order_date.index].hasOwnProperty('value')){
      //             obj.setValue(jexcel.getColumnNameFromId([columns.order_date.index, y]),columns_index[columns.order_date.index].value);
      //         }
      //         if(columns_index[columns.order_hours.index] && columns_index[columns.order_hours.index].hasOwnProperty('value')){
      //             obj.setValue(jexcel.getColumnNameFromId([columns.order_hours.index, y]),columns_index[columns.order_hours.index].value);
      //         }
      //         let parentRow = $(obj.getCell(jexcel.getColumnNameFromId([columns.product_name.index, y]))).parent();
      //         parentRow.addClass('group-row');
      //         parentRow.removeClass('group-cell');
      //
      //         obj.insertRow(1, parseInt(y));
      //
      //     }
      // });
      // items.push({
      //     title:"Set Product",
      //     onclick:function() {
      //         obj.setValue(jexcel.getColumnNameFromId([columns.fullname.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.address.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.payMethod.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.phone.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.province.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.zipcode.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.product_id.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.product_name.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.count.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.total_count.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.order_date.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.order_hours.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.price_buy_sale.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.price.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.price_buy.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.order_ship.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, y]),"");
      //         obj.setValue(jexcel.getColumnNameFromId([columns.order_total_price.index, y]),"");
      //         let parentRow = $(obj.getCell(jexcel.getColumnNameFromId([columns.product_name.index, y]))).parent();
      //         parentRow.addClass('group-cell');
      //         parentRow.removeClass('group-row');
      //
      //         // if(columns_index[columns.product_name.index]){
      //         //     console.log(columns_index[columns.product_name.index]);
      //         //     change = {c:columns.product_name.index,r:y};
      //         //     obj.setValue(jexcel.getColumnNameFromId([columns.product_name.index, y]),columns_index[columns.product_name.index].value);
      //         // }
      //     }
      // });
     }

     return items;
    },
    onselection:function (instance, x1, y1, x2, y2, origin) {
     change = {col:x1,row:y1};

     console.log(change);
     var cellName1 = jexcel.getColumnNameFromId([columns_index[x1].index, y1]);
     $('.onselection').hide();
     $("#col-row-review").data({"x":x1,y:y1}).val(cellName1);
     let val = instance.jexcel.getValue(cellName1);

     if(columns_index[x1] && columns_index[x1].type === "dropdown"){
      $("#value-review").hide();
      $html = $("<div>");
      $("#zoe-dropdown-review").show().html($html);
      // jSuites.dropdown($html[0], {
      //     data:columns_index[x1].source,
      //     autocomplete: columns_index[x1].hasOwnProperty('autocomplete'),
      //     width:'100%',
      //     onchange:function (el, a, oldValue, Value) {
      //       //  lock[y1] = 1;
      //        // instance.jexcel.setValue(jexcel.getColumnNameFromId([x1, y1]), Value);
      //     },
      // }).setValue(val);
     }else if(columns_index[x1].key === "order_info"){

      //NGUYEN V 様 00 日に 0000 円入金済み
      let vals = val.trim().split(' ');
      let a = val.trim()+"";
      let b = ["様","日に","円入金済み"];
      vals = [];
      if(a.length > 0){

        for(let i = 0; i<b.length;i++){
         let results = a.split(b[i]);
         if(results.length == 0) break;
         a = results[1];
         let val = results[0].trim();
         if(val.length > 0){
          vals.push(val);
         }
        }
      }
      $("#info_payment").show();
      let html  = "<table>";
      html+="<tr>";

      html+="<th>";
      html+='<input type="text" class="form-control name" value="'+(vals[0]?vals[0]:"")+'">';
      html+="</th>";

      html+="<th>";
      html+='様';
      html+="</th>";
      html+="<th>";
      html+='<input type="text" class="form-control date" value="'+(vals[1]?vals[1]:"")+'">';
      html+="</th>";

      html+="<th>";
      html+='日に';
      html+="</th>";

      html+="<th>";
      html+='<input type="text" class="form-control price" value="'+(vals[2]?vals[2]:"")+'">';
      html+="</th>";

      html+="<th>";
      html+='円入金済み';
      html+="</th>";

      html+="<td>";
      html+='&nbsp;<button type="button" class="btn">{!! z_language('Lưu') !!}</button>';
      html+="</td>";
      html+="</tr>";
      html+="</table>";
      var dom = $(html);
      dom.find('button').click(function () {
       let th = dom.find('tr th');
       let vals = "";
       console.log(th);
       for(let i = 0; i< th.length ; i++){
        if(i%2===0){
         vals+=" "+$(th[i]).find('input').val().trim();
        }else{
         vals+=" "+$(th[i]).text().trim();
        }
       }
       setTimeout(function () {
        instance.jexcel.setValue(jexcel.getColumnNameFromId([x1, y1]), vals.trim());
       },1000);
      });
      $("#info_payment").html(dom);
     }
     else{
      lock[y1] = 1;
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

     cell.style.overflow = 'hidden';
        if (c === columns.image.index && val.length>0) {
            cell.innerHTML = '<img src="' + val + '" style="width:20px;height:20px">';
        }
     if(columns.id.index === c ){


      let v = instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.order_ship.index, row]));
      if(v == -1) instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.province.index, row])).classList.add('error');
      else instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.province.index, row])).classList.remove('error');

      let value = instance.jexcel.getRowData(row);
      let parent = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.payMethod.index, row]))).parent();

      let pos_start_end = indexFist(instance.jexcel,row);

      parent.removeClass('error-line-order');

      if(pos_start_end.end  <  pos_start_end.start){
       let value = instance.jexcel.getRowData(pos_start_end.start);
       if(value[columns.type.index] == "Info"){
        parent.addClass('error-line-order');
       }

      }

      if(value[columns.type.index] == "Info"){

       let count = 0;
       let countAddress = 0;
       for(let k in instance.jexcel.rows){
        let _val  = instance.jexcel.getRowData(k);
        if(value[columns.fullname.index].length > 0 && value[columns.fullname.index].replace(/\s+/g, ' ') === _val[columns.fullname.index].replace(/\s+/g, ' ')){
         count++;
        }
        if(value[columns.address.index].length > 0 && value[columns.address.index].replace(/\s/g, '') === _val[columns.address.index].replace(/\s/g, '')){
         countAddress++;
        }
       }
       instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])).classList.remove('error');
       if(count > 1) instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])).classList.add('error');

       instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.address.index, row])).classList.remove('error');
       if(countAddress > 1) instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.address.index, row])).classList.add('error');






       let vvv = getValuePayMethod(value[columns.payMethod.index]);

       parent.removeClass('pay-method-oke');
       if(vvv === 2){
        parent.addClass('pay-method-oke');

       }
       parent.removeClass('has_error');
       if(vvv === 2){
        let img = value[columns.image.index];
        let order_info = value[columns.order_info.index];
        let self = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.image.index, row]))).parent();
        if(img.length === 0 || order_info.length === 0){
         self.addClass('has_error');
        }
       }

       let _province = value[columns.province.index]+"".trim();
       let _address = value[columns.address.index]+"".replace(/\s/g, '').trim();
       let _fullname = value[columns.fullname.index]+"".replace(/\s+/g, ' ').trim();

       let $col = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.province.index, row])));

       $col.removeClass('bor-error');
       if(_province.length <= 0){
        $col.addClass('bor-error')
       }
       $col = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.address.index, row])));
       $col.removeClass('bor-error')
       if(_address.length <= 0){
        $col.addClass('bor-error')
       }
       $col = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])));
       $col.removeClass('bor-error');
          $colorder_link = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.order_link.index, row])));
          $colorder_link.removeClass('bor-error');

       if(_fullname.length <= 0){
        $col.addClass('bor-error')
       }else{
           let order_link = value[columns.order_link.index]+"".trim();
           if(order_link.length === 0){
               $colorder_link.addClass('bor-error');
               if(!parent.hasClass('has_error')){
                   parent.addClass('has_error');
               }
           }
       }
      }
      parent.removeClass('has_export');
      let _export = value[columns.export.index];
      if((_export+"").toString().length > 0){
       if(_export){
        parent.addClass('has_export');
       }
      }
      let type = value[columns.type.index];

      let parentType = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.type.index, row]))).parent();

      if(!parentType.hasClass('action')){
       if(type === "Info"){
        parent.addClass('info');
       }else if(type === "Footer"){
        parentType.addClass('footer')
       }else{
        parentType.addClass('item');
        parentType.addClass('action')
       }
      }
      //indexFist(instance.jexcel,row);
     }
    },

    onchange:function(instance, cell, c, r, value) {
     // console.log("c:"+c);
     // console.log("r:"+r);
     //   console.log("value:"+value);
     //  console.log(columns_index[c]);
     // console.log(change);
     if( (value+"").trim().length === 0){
      if(lock.hasOwnProperty(r)){
       // update_count(instance, cell, c, r,{});
      }
      // instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.fullname.index, row]),"");
      // instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.address.index, row]),"");
      // instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.payMethod.index, row]),"");
      // instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.phone.index, row]),"");
      // instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.province.index, row]),"");
      // instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.zipcode.index, row]),"");
      // instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.product_id.index, row]),"");
      // instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.product_name.index, row]),"");
      // instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.count.index, row]),"");

      return;
     }

     //    console.log("=>>>>>>>>>>>>>>>>>>>>>>>>>"+value);
     c = parseInt(c);
     if (c === columns.product_name.index) {
      if(dropdown[value] && dropdown[value].hasOwnProperty('data')){
       if(change.col == c){
        change.col =  {col:-1,row:-1};
        console.log("Change:"+r);
        let thisRow = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([c, r]))).parent();
        if(thisRow.hasClass('footer')){
         instance.jexcel.setValue(jexcel.getColumnNameFromId([c, r]),"");
         return;
        }
        instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.product_id.index, r]), dropdown[value].data.id);
        instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price.index, r]),dropdown[value].data.price);
        instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price_buy.index, r]),dropdown[value].data.price_buy);

        r = parseInt(r);
        let index = indexFist(instance.jexcel,r);



        if(index.start < r && index.end > r){
         instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.type.index, r]),"Item");
        }

        let parentRowEnd = null;
        let parentRow = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.product_name.index, index.start]))).parent();

        if(!parentRow.hasClass('info')){
         parentRow.addClass('info');
         parentRow.addClass('action');
         instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.type.index, index.start]),"Info");
        }
        let insert = true;
        if( index.end === 0){
         index.end =  parseInt(r)+1;
         instance.jexcel.insertRow(1, parseInt(index.end));

         parentRowEnd = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.product_name.index,  index.end ]))).parent();
         if(!parentRow.hasClass('footer')){
          parentRowEnd.addClass('action');
          parentRowEnd.addClass('footer');
          instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.type.index, index.end]),"Footer");
         }
         instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.total_count.index,index.end]),1);
         instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.count.index, index.end]),1);
        }

        for(let i = index.start; i < index.end; i++ ){
         let valueRow =  instance.jexcel.getRowData(i);

         if(IsEmpty(valueRow)){
          insert = false;
          break;
         }
        }
        if(insert){
         instance.jexcel.insertRow(1, parseInt(index.end-1));
         index.end++;
        }
        update(instance, cell, c, r ,index,function () {
         update_count(instance, cell, c, r,index);
        });
       }
      }
     }else if(c === columns.count.index || c === columns.zipcode.index || c === columns.price_buy_sale.index ||
             c === columns.order_ship.index || c === columns.order_ship_cou.index || c === columns.product_id.index || c === columns.one_address.index){
      if(change.col == c){
       if(c === columns.zipcode.index){
        change = {col:columns.province.index,row:r};
       }else{
        change.col =  {col:-1,row:-1};
        let index = indexFist(instance.jexcel,r);
        update(instance, cell, c, r,index,function () {
         update_count(instance, cell, c, r,index);
        });
       }
      }
     }else if(c === columns.fullname.index || c === columns.address.index ||
             c === columns.zipcode.index || c === columns.payMethod.index || c === columns.phone.index ){

      if(change.col == c && (value+"").length > 0){
       change.col =  {col:-1,row:-1};
       let index = indexFist(instance.jexcel,r);
       update(instance, cell, c, r,index,function () {
        update_count(instance, cell, c, r,index);
       });

      }
     }else if(c === columns.province.index){
      if(change.col == c){
       change.col =  {col:-1,row:-1};
       let index = indexFist(instance.jexcel,r);
       update(instance, cell, c, r,index,function () {
        update_count(instance, cell, c, r,index);
       });
      }
     }else if(c === columns.payMethod.index){
      let v = getValuePayMethod(value);
      let parent = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.payMethod.index, r]))).parent();
      parent.removeClass('pay-method-oke');
      if(v === 2){
       parent.addClass('pay-method-oke');
      }
      if(change.col == c){
       change.col =  {col:-1,row:-1};
       let index = indexFist(instance.jexcel,r);
       update(instance, cell, c, r,index,function () {
        update_count(instance, cell, c, r,index);
       });
      }
     }else if(c === columns.total_count.index){
      console.log('count');
     }
    },
   };
  }

  function KURICHIKU() {
   let  sheetName  =  'KURICHIKU';
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
   let dropdown = dataproduct.hasOwnProperty(sheetName)?dataproduct[sheetName]:{};
   let index = 0;
   let change = {col:-1,row:-1};
   let oldData = "";
   let customColumn = {
    closeEditor : function(cell, save) {
     console.log("closeEditor");

     return oldData;
    },
    openEditor : function(cell) {

     let dom = $(cell);

     let _spreadsheet = document.getElementById('spreadsheet').children[0].querySelector('.selected');
     let  worksheet = _spreadsheet.getAttribute('data-spreadsheet');

     let _jexcel = spreadsheet.jexcel[worksheet];

     var cellName1 = jexcel.getColumnNameFromId([parseInt(dom.attr('data-x'))-1, dom.attr('data-y')]);
     var cellName2 = jexcel.getColumnNameFromId([parseInt(dom.attr('data-x')), dom.attr('data-y')]);

     oldData = (_jexcel.getValue(cellName2)).toString();
     let valsProduct = (_jexcel.getValue(cellName1)).toString().split(";");

     valsProduct.sort(function(a, b){
      if(dropdown.hasOwnProperty(a)){
       return dropdown[b].data.order_index - dropdown[a].data.order_index;
      }
     });

     let valsCount = {};
     try{
      valsCount = JSON.parse(oldData);
     }catch (e) {
      valsCount = {};
     }

     let $html = "<table  class='table table-bordered config_count'>";
     $html+="<tr><th>Tên</th><th>Tên</th><th style='width: 80px;'>Số lượng</th><th></th><th></th><th></th><<th></th><th>Tên</th><th>Tên</th><th style='width: 80px;'>Số lượng</th></tr>";



     let open = false;
     let count = 0;
     for (let i in valsProduct){
      if(dropdown.hasOwnProperty(valsProduct[i])){
       let item = dropdown[valsProduct[i]];
       if(open === false && count%2 === 0){
        $html+="<tr>";open = true;
       }
       $html+="<td>"+item.name+"</td>";
       $html+="<td>"+item.title+"</td>";
       $html+="<td><input min=\"1\" max=\"10\" class=\"form-control count\" type='number' data-id='"+valsProduct[i]+"' value='"+(valsCount.hasOwnProperty(valsProduct[i])?valsCount[valsProduct[i]]:0)+"'></td>";
       if(count === 0){
        $html+="<td colspan=4></td>";
       }
       count++;

       if(open === true && count === 2){

        $html+="</tr>";
        open = false;
        count = 0;
       }
      }
     }
     if(open === true){
      $html+="</tr>";
      open = false;
      count = 0;
     }
     $html+= "<table>";

     let action = function () {
      let parent = $('.config_count').find('.count');
      let vals = {};
      parent.each(function () {
       let _dom = $(this);
       let v = parseInt( _dom.val());
       vals[_dom.attr('data-id')] = isNaN(v)?0:v;
      });
      let data = {};
      for (let i in valsProduct){
       if(dropdown.hasOwnProperty(valsProduct[i])){
        if(vals.hasOwnProperty(valsProduct[i])){
         data[valsProduct[i]] = vals[valsProduct[i]];
        }else{
         data[valsProduct[i]] = 1;
        }
       }
      }
      cell.innerHTML =JSON.stringify(data);
      oldData = cell.innerHTML;
      _jexcel.setValue(cellName2,cell.innerHTML);
     };

     bootpopup({
      title: "Custom HTML",
      size: "large",
      showclose: false,
      content: [$html],
      ok: action,
      cancel:function () {
       console.log('bootpopup:cancel');

      },
      dismiss:function () {
       console.log('bootpopup:dismiss')
      },
      before: function () {
       console.log('bootpopup:before')
      }
     });
    },
    getValue : function(cell) {
     return cell.innerHTML;
    },
    setValue : function(cell, value) {
     cell.innerHTML = value;
    }
   };
   let columns = {
    status: {
     type: 'checkbox',
     title:'{!! z_language('Trạng thái') !!}'
    },
    image:{
     title:'{!! z_language('Ảnh') !!}',
     type:'image',
     width:"50px",
     hide:true,
    },
     order_info:{
           title: '{!! z_language("Thông tin chuyển khoản") !!}',//T Thông tin chuyển khoản
           type: 'text',
           width:'100px',
           key:"demo",
     },
    timeCreate:{
     title: '{!! z_language("Ngày đặt hàng") !!}',//A ngày đặt hàng
     type: 'calendar',
     width:'100px',
     options: { format:'DD/MM/YYYY' },
     value:['date','now']
    },
    payMethod:{
     title: '{!! z_language("Phương thức thanh toán") !!}',//B Phương thức thanh toán
     type:'dropdown',
     source:[
      "代金引換",
      "銀行振込",
      "決済不要",
     ],
     width:'130px',
     value:['product','this','source',0],
    },
    phone:{
     title: '{!! z_language("Số điện thoại") !!}',//C Số điện thoại
     type: 'text',
     width:'100px',
     value:"070-8409-5968",
    },
    zipcode:{
     title: '{!! z_language("Mã bưu điện") !!}',//D Mã bưu điện
     type: 'text',
     width:'60px',
     key:"demo",
    },
    province:{
     title: '{!! z_language("Tỉnh/TP") !!}',//E Tỉnh/TP
     type: 'text',
     width:'200px',
     key:"demo",
    },
    address:{
     title: '{!! z_language("Địa chỉ giao hàng") !!}',//F Địa chỉ giao hàng
     type: 'text',
     width:'250px',
     key:"demo",
    },
    fullname:{
     title: '{!! z_language("Họ tên người nhận") !!}',//G Họ tên người nhận
     type: 'text',
     width:'150px',
     key:"demo",
    },
       order_link:{
           title: '{!! z_language("Đường dẫn") !!}',//T Thông tin chuyển khoản
           type: 'text',
           width:'100px',
       },
    product_id:{
     title: '{!! z_language("Mã SP") !!}',//H Mã SP
     type: 'text',
     width:'100px',
     read:true,
     value:(function () {
      $id = "";
      for(let index in dropdown){
       $id+=dropdown[index].id+";";
      }
      return $id.trim(";");
     })(),
    },
    product_name:{
     title: '{!! z_language("Tên SP") !!}',//I Tên SP
     type:'dropdown',
     source:Object.values(dropdown),
     autocomplete:true,
     multiple: true,
     width:'160px',
     value:(function () {
      $id = "";
      for(let index in dropdown){
       $id+=dropdown[index].id+";";
      }
      return $id.trim(";");
     })()
    },
    count:{
     title: '{!! z_language("Loại thịt + SL") !!}',//K SL
     type: 'text',
     width:'150px',
     multiple: true,
     value:(function () {
      let _count = {};
      let _id = 0;
      for(let index in dropdown){
       if(_id === 0){
        _id = dropdown[index].id;
        _count[dropdown[index].id] = 1;
       }else{
        _count[dropdown[index].id] = 0;
       }

      }
      return  JSON.stringify(_count);
     })()
    },
    total_count:{
     title: '{!! z_language("SL Tổng") !!}',//K SL  title: '',//K SL Tổng
     type: 'numeric',
     width:'60px',
     value:1,
    },
    price:{
     title: '{!! z_language("Giá nhập") !!}',//J Giá nhập
     type: 'numeric',
     width:'100px',
     value:['product','product_name','source',0,'data','price'],
    },
    price_buy:{
     title: '{!! z_language("Giá bán") !!}',//J Giá nhập
     type: 'numeric',
     width:'100px',
     value:['product','product_name','source',0,'data','price_buy'],
    },
    order_date:{
     title: '{!! z_language("Ngày nhận") !!}',//L Ngày nhận
     type:'calendar',
     options: { format:'DD/MM/YYYY'},
     value:['date','nowSum',2],
     width:'100px',

    },
    order_hours:{
     title: '{!! z_language("Giờ nhận") !!}',//M Giờ nhận
     type: 'dropdown',
     source:['8:00 ~ 12:00','14:00～16:00','16:00～18:00','18:00～20:00','19:00～21:00'],
     value:['product','this','source',4],
     width:'150px',
    },
    order_ship:{
     title: '{!! z_language("Phí ship") !!}',//N Phí ship
     type: 'numeric',
     width:'100px',
     value:0
    },
    order_total_price:{
     title: '{!! z_language("Tổng giá nhập") !!}',//O Tổng giá nhập
     type: 'numeric',
     width:'100px',
     value:['product','product_name','source',0,'data','price'],
    },
    price_buy_sale:{
     title: '{!! z_language("Tăng Giảm") !!}',//J Giá nhập
     type: 'numeric',
     width:'100px',
     value:0,
    },
    order_total_price_buy:{
     title: '{!! z_language("Total Bán") !!}',//P Giá bán
     type: 'numeric',
     width:'100px',
     value:['product','product_name','source',0,'data','price_buy'],
    },
    order_ship_cou:{
     title: '{!! z_language("Phí giao hàng") !!}',//P Phí giao hàng
     type: 'numeric',
     width:'100px',
     value:0
    },
    order_price:{
     title: '{!! z_language("Lợi nhuận") !!}',//P Lợi nhuận
     type: 'numeric',
     width:'100px',
     value:0
    },
    order_rate:{
     title: '{!! z_language("Lợi nhuận CTV") !!}',//P
     type: 'numeric',
     width:'100px',
     value:0
    },
    order_tracking:{
     title: '{!! z_language("Mã tracking") !!}',//T Mã tracking
     type: 'text',
     width:'100px',
     key:"demo",
    },


    one_address: {
     type: 'checkbox',
     title:'{!! z_language("Cùng địa chỉ") !!}'
    },
    id:{
     title: '{!! z_language("ID") !!}ID',//T
     type: 'text',
     width:'100px',
    },
    session_id:{
     title: '{!! z_language("SessionId") !!}',//T
     type: 'text',
     width:'1px',
    },
    export:{
     title: '{!! z_language("Xuất") !!}',//T
     type: 'checkbox',
     width:'1px',
    },
    token:{
     title: '{!! z_language("Token") !!}',//T
     type: 'text',
     width:'1px',
    },
    admin:{
     title: '{!! z_language("Admin") !!}',//T
     type: 'text',
     width:'100px',
    },
    group:{
     title: '{!! z_language("group") !!}',//T
     type: 'text',
     width:'1px',
    },
    comment:{
     title: '{!! z_language("Ghi chú") !!}',//T
     type: 'text',
     width:'100px',
    },
       order_product_link:{
           title: '{!! z_language("Link SP") !!}',//T
           type: 'text',
           width:'100px',
       },
   };
   columns.count.editor = customColumn;
   columnsAll[sheetName] = columns;
   let columns_index = Object.values(columns);
   let hide = hideprototy.hasOwnProperty(sheetName)?hideprototy[sheetName]:{};
   let _option = options.hasOwnProperty(sheetName)?options[sheetName]:{};
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
    if(hide.hasOwnProperty(i)){
     columns[i].width =hide[i]+"px";
    }else if(_option.hasOwnProperty('colums')){
     if(_option.colums.hasOwnProperty(i)){
      columns[i].width = "1px";
     }
    }else if(columnsWidth.hasOwnProperty(i) && i!="count"){
     columns[i].width =columnsWidth[i].width;
    }
   }
   function update(instance, cell, c, r, value) {
    console.log("update call");
    let data = {
     count:instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.count.index, r])),
     id:value.hasOwnProperty('id')?value.id.toString():instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.product_id.index, r])).toString(),
     province:value.hasOwnProperty('province')?value.province:instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.province.index, r])),
    };
    data.province = data.province.trim();
    data.id = data.id.split(';');

    console.log(data);

    let count = parseInt(data.count);

    if(!isNaN(count)){
     data.count = {};
     data.count[data.id[0]] = count;
    }else{
     try{
      data.count = JSON.parse(data.count);
     }catch (e) {
      data.count = {};
      data.count[data.id[0]] = 1;
     }
    }



    let valueRow =  instance.jexcel.getRowData(r);
    let payMethod = getValuePayMethod(valueRow[columns.payMethod.index]);

    data.payMethod = payMethod;
    data.sheetName = sheetName;

    console.log("payMethod:"+payMethod);

    let price_buy_sale = parseInt(valueRow[columns.price_buy_sale.index]);
    console.log("price_buy_sale:"+price_buy_sale);
    let price_buy=0;
    let price=0;

    let total_price_buy =  0;
    let total_price =  0;
    let countNew = {};
    let totalCount = 0;

    for(let i in data.id){
     if(data.id.hasOwnProperty(i)){
      if(dropdown.hasOwnProperty(data.id[i])){
       let product = dropdown[data.id[i]];

       let _price = parseInt(product.data.price);
       let _price_buy = parseInt(product.data.price_buy);


       if(!data.count.hasOwnProperty(data.id[i])){
        data.count[data.id[i]] = 1;
       }else{
        data.count[data.id[i]] = parseInt(data.count[data.id[i]]);
       }

       price+=_price*data.count[data.id[i]];
       price_buy+=_price_buy*data.count[data.id[i]];

       totalCount+=data.count[data.id[i]];
       countNew[data.id[i]] = data.count[data.id[i]];

       total_price_buy+= _price_buy * data.count[data.id[i]];
       total_price+=_price * data.count[data.id[i]];
      }

     }
    }
    let product_id = data.id[0];


    instance.jexcel.getCell(
            jexcel.getColumnNameFromId([columns.count.index, r])).innerHTML = JSON.stringify(countNew);

    let total_count  = parseInt(valueRow[columns.total_count.index]);

    data.total_price = total_price * total_count;
    data.total_price_buy = total_price_buy * total_count + price_buy_sale;

    total_price_buy =  data.total_price_buy;
    total_price = data.total_price;

    let total_price_buy_all = 0;
    let Row = -1;
    let token = parseInt(valueRow[columns.token.index]);
    console.log("valueRow[columns.one_address.index]:"+valueRow[columns.one_address.index]);
    console.log("token:"+token);
    if(!valueRow[columns.one_address.index]){
     total_price_buy_all = total_price_buy;
     for(let iii = r+1;iii<10;iii++){
      let _data = instance.jexcel.getRowData(iii);
      if(_data && _data[columns.one_address.index]){
       console.log(_data);
       let total = parseInt(_data[columns.order_total_price_buy.index]);
       total_price_buy_all+= isNaN(total)?0:total;
      }else{
       break
      }
     }
     console.log(11111111111111);
     if(!isNaN(token)){
      Row = token;
     }
     console.log("Row:"+Row);
     token = "";
    }else{
     for(let iii = r-1;iii>=0;iii--){
      let _data = instance.jexcel.getRowData(iii);
      console.log(data);
      if(_data && !_data[columns.one_address.index]){
       token = iii;
       Row = token;
       break
      }
     }
    }

    console.log("token:"+token);
    console.log("Row:"+Row);
    console.log("total_price_buy_all:"+total_price_buy_all);

    instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.token.index, r]), token);

    total_price_buy =  data.total_price_buy;
    total_price = data.total_price;




    instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price.index, r]), price);
    instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price_buy.index, r]), price_buy);

    instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price.index, r]),  data.total_price);

    function GetShip($product,$category_id,$count,$province,$total_price_buy,payMethod,total_price_buy_all) {
     if(total_price_buy_all == 0){
      total_price_buy_all = $total_price_buy;
     }
     let configShip = dataship.hasOwnProperty("cate_"+$category_id)?dataship["cate_"+$category_id]:[];
     console.log(configShip);
     console.log("$count:"+$count);
     console.log("$province:"+$province);
     console.log("$total_price_buy:"+$total_price_buy);
     let arr_ship = [];
     for(let i in configShip){
      $is_IF_Start = IF_Start($count,configShip[i]);
      $is_IF_End =  IF_End($count,configShip[i]);
      if($is_IF_Start && $is_IF_End){
       $conf  =  configShip[i].config;
       for (let ii in $conf){
        $val = $conf[ii];
        $arr = $val['text'].split("-");
        for (let iii in $arr){
         $v = $arr[iii];
         if($province == $v){
          arr_ship.push([configShip[i],$val])
         }
        }
       }
      }
     }
     console.log(arr_ship);
     $price_ship_default  = -1;
     $price_ship  = -1;
     for (i in arr_ship){
      $val = arr_ship[i];
      if($val[0].unit == 0 && $price_ship_default==-1){
       $price_ship_default =  $val[1]['value'];
      }else if($val[0].unit == $product.unit && $price_ship == -1){
       $price_ship = $val[1]['value'];
      }
     }

     console.log('$price_ship_default:'+$price_ship_default);
     console.log('$price_ship:'+$price_ship);
     let $ship = categorys[$category_id]?( categorys[$category_id].data.hasOwnProperty('ship'))?categorys[$category_id].data.ship:"-1":"-1";

     let $ship_cou = -1;

     let one_address = instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.one_address.index, r]));
     if(one_address){
      payMethod = 2;
     }
     if( payMethod == 2 || payMethod == 3 ){
      $ship_cou = 0;
     }else{
      for (let i in datadaibiki){
       $_val  = datadaibiki[i];

       if($ship == $_val.id){
        for(let ii in $_val.data){
         $units = $_val.data[ii];
         for(let iii in $units){
          $_unit = $units[iii];
          if($_unit){
           $is_IF_Start = IF_Start(total_price_buy_all,$_unit);
           $is_IF_End = IF_End(total_price_buy_all,$_unit);
           if($is_IF_Start && $is_IF_End){
            $ship_cou = $_unit.value;
           }
          }
         }
        }
       }
       if($ship_cou != -1){
        break;
       }
      }
     }
     let price_ship =  $price_ship!=-1?$price_ship:$price_ship_default;
     $ship_cou = $ship_cou == -1?0:$ship_cou;
     return {
      order_ship:parseInt(price_ship === -1 ? 0 :price_ship),
      order_ship_cou:parseInt($ship_cou)
     };
    }
    function setInterest(price_ship,order_ship_cou,total_price_buy,total_count){

     price_ship = price_ship * total_count;
     console.log("price_ship:"+price_ship);
     console.log("total_price_buy:"+total_price_buy);
     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship.index, r]),price_ship);
     // total_price_buy = total_price_buy;
     console.log(total_price_buy);
     if(total_price_buy === 0 || total_price === 0){ return;}

     if(payMethod == 3){
      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]), 0);
      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]), 0);
      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price_buy.index, r]), 0);
      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]), 0);
      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]), total_price_buy,false );
     }else if(payMethod == 2){
      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]), total_price_buy-330,false );
      let a = (parseInt(total_price_buy) - parseInt(total_price) - parseInt(price_ship)) - 330;
      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]),a,false);
      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]),0,false);
     }else{
      let one_address = instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.one_address.index, r]));
      if(one_address){

       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]), total_price_buy-330,false );
       let a = (parseInt(total_price_buy)-330- parseInt(total_price) - parseInt(price_ship)) - 330;

       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]),a,false);
       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]),0,false);
      }else{
       console.log("price_ship:"+price_ship);
       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]), total_price_buy,false );
       let a = (parseInt(total_price_buy) - parseInt(total_price) - parseInt(price_ship) - order_ship_cou);

       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]),a,false);
       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]),order_ship_cou,false);
      }

     }
     if(Row>-1)
     {
      console.log("Update:Row"+Row);
      update(instance,cell, c, Row, {});
     }
    }
    data.count = totalCount;
    if(dropdown.hasOwnProperty(product_id)){
     let confShipCou = GetShip(dropdown[product_id].data,dropdown[product_id].data.category_id,totalCount,data.province,total_price_buy,payMethod,total_price_buy_all);

     setInterest(confShipCou.order_ship   , confShipCou.order_ship_cou,total_price_buy + 330,total_count)
    }

    //    if(value.hasOwnProperty('lock') && value.lock.indexOf(columns.order_ship.index)){

    //     } else{
    //         setInterest(parseInt(valueRow[columns.order_ship.index]),parseInt(valueRow[columns.order_ship_cou.index]));
    //     }
   }
   let _data = InitData(data,config,columns_index,sheetName);

   let click = false;

   let nestedHeaders = [];
   if(locks .hasOwnProperty(sheetName)){
    let _lock  = locks [sheetName];
    let dateNow = stringDate;
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
    onload: function (e) {
     let td  = $(e).find('.jexcel tbody tr').find('td:first-child');
     td.click(function () {
      let parent = $(this).parent().data();

      if(parent.hasOwnProperty('info')){
       let info = parent.info;
       let company = parent.company;



       for(let tab in info){
        let dom = $("#tab_"+tab);
        let table = "<table class=\"table table-bordered\">";
        table+="<tr>";
        // table+='<td>Mã</td>';
        // table+='<td>Phiên</td>';
        table+='<td>{!! z_language('Người lập') !!}</td>';
        table+='<td>{!! z_language("Ho Tên") !!}</td>';
        table+='<td>{!! z_language("Địa chỉ") !!}</td>';
        table+='<td>{!! z_language("Tỉnh") !!}</td>';
        table+='<td>{!! z_language("Ngày tạo") !!}</td>';
        table+='<td>{!! z_language("Số điện thoại") !!}</td>';
        table+='<td>{!! z_language("Công ty") !!}</td>';
        table+='<td>{!! z_language("Sản phẩm") !!}</td>';

        table+='<td>{!! z_language("Trạng thái") !!}</td>';
        table+='<td>{!! z_language("Xuất") !!}</td>';
        table+='<td>{!! z_language("Đường dẫn") !!}</td>';
        table+="</tr>";
        for(let pos in info){
         for(let com in info[pos]){
          if(com!=company) continue;
          for(let index in info[pos][com]){
           let val = info[pos][com][index];
           table+="<tr>";
           table+='<td>'+(info_admin.hasOwnProperty(val.admin_id)?info_admin[val.admin_id].name:"Không xác định")+'</td>';
           table+='<td>'+val.fullname+'</td>';
           table+='<td>'+val.address+'</td>';
           table+='<td>'+val.province+'</td>';
           table+='<td>'+val.order_create_date+'</td>';
           table+='<td>'+val.phone+'</td>';
           table+='<td>'+val.company+'</td>';
           table+= "<td><table class=\"table table-bordered\">";
           table+='<td>'+val.product_title+'</td>';
           table+='<td>'+val.count+'</td>';
           if(val.hasOwnProperty('items')){
            for(let _index in val.items){
             table+="<tr>";
             table+='<td>'+val.items[_index].product_title+'</td>';
             table+='<td>'+val.items[_index].count+'</td>';
             table+="</tr>";
            }
           }
           table+="</table></td>";
           table+='<td>'+(val.public == 1?"Đơn":"Nháp")+'</td>';
           table+='<td>'+(val.export == 1?"Xuất":"Chưa")+'</td>';
           table+='<td>'+val.order_link+'</td>';
           table+="</tr>";
          }
         }
        }

        table+="</table>";
        dom.html(table);
       }
       $('#modal-default').modal('show');
      }
     });
    },
    nestedHeaders:[
     nestedHeaders
    ],
    contextMenu: function(obj, x, y, e) {
     var items = [];
        return items;
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
     if(click === true){
      return;
     }
     click = true;

     console.log("x1"+x1 +" y1: "+ y1);

     setTimeout(function () {
      click = false;

      var cellName1 = jexcel.getColumnNameFromId([columns_index[x1].index, y1]);
      let cell = instance.jexcel.getCell(cellName1);
      $(".onselection").hide();
      let val = instance.jexcel.getValue(cellName1);
      $("#col-row-review").data({"x":x1,y:y1}).val(cellName1);
      console.log("ACTION");
      if(columns_index[x1] && columns_index[x1].type === "dropdown"){
       if(cell.classList.contains('editor')){
        console.log("val:"+val);
        change = {col:x1,row:y1};
        $("#value-review").hide();
        $html = $("<div>");
        $("#zoe-dropdown-review").show().html($html);
        let init = true;
        change = {col:x1,row:y1};
        init = false;
        // jSuites.dropdown($html[0], {
        //     data:columns_index[x1].source,
        //     value:val,
        //     autocomplete: columns_index[x1].hasOwnProperty('autocomplete'),
        //     multiple: columns_index[x1].hasOwnProperty('multiple'),
        //     width:'100%',
        //     onchange:function (el, a, oldValue, Value) {
        //         //instance.jexcel.setValue(jexcel.getColumnNameFromId([x1, y1]), Value);
        //     },
        // }).setValue(val);
       }
      }else if(columns_index[x1].key === "order_info"){

       //NGUYEN V 様 00 日に 0000 円入金済み
       let vals = val.trim().split(' ');
       let a = val.trim()+"";
       let b = ["様","日に","円入金済み"];
       vals=[];
       if(a.length > 0){

        for(let i = 0; i<b.length;i++){
         let results = a.split(b[i]);
         if(results.length == 0) break;
         a = results[1];
         let val = results[0].trim();
         if(val.length > 0){
          vals.push(val);
         }
        }
       }
       $("#info_payment").show();
       let html  = "<table>";
       html+="<tr>";

       html+="<th>";
       html+='<input type="text" class="form-control name" value="'+(vals[0]?vals[0]:"")+'">';
       html+="</th>";

       html+="<th>";
       html+='様';
       html+="</th>";
       html+="<th>";
       html+='<input type="text" class="form-control date" value="'+(vals[1]?vals[1]:"")+'">';
       html+="</th>";

       html+="<th>";
       html+='日に';
       html+="</th>";

       html+="<th>";
       html+='<input type="text" class="form-control price" value="'+(vals[2]?vals[2]:"")+'">';
       html+="</th>";

       html+="<th>";
       html+='円入金済み';
       html+="</th>";

       html+="<td>";
       html+='&nbsp;<button type="button" class="btn">{!! z_language('Lưu') !!}</button>';
       html+="</td>";
       html+="</tr>";
       html+="</table>";
       var dom = $(html);
       dom.find('button').click(function () {
        let th = dom.find('tr th');
        let vals = "";
        console.log(th);
        for(let i = 0; i< th.length ; i++){
         if(i%2===0){
          vals+=" "+$(th[i]).find('input').val().trim();
         }else{
          vals+=" "+$(th[i]).text().trim();
         }
        }
        setTimeout(function () {
         instance.jexcel.setValue(jexcel.getColumnNameFromId([x1, y1]), vals.trim());
        },1000);
       });
       $("#info_payment").html(dom);
      }
      else{
       change = {col:x1,row:y1};
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
     },500);
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
      let countAddress = 0;
      for(let k in instance.jexcel.rows){
       let _val  = instance.jexcel.getRowData(k);
       if(value[columns.fullname.index].length > 0 && value[columns.fullname.index].replace(/\s+/g, ' ') === _val[columns.fullname.index].replace(/\s+/g, ' ')){
        count++;
       }
       if(value[columns.address.index].length > 0 && value[columns.address.index].replace(/\s/g, '') === _val[columns.address.index].replace(/\s/g, '')){
        countAddress++;
       }
      }
      instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])).classList.remove('error');
      if(count > 1) instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])).classList.add('error');

      instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.address.index, row])).classList.remove('error');
      if(countAddress > 1) instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.address.index, row])).classList.add('error');

      let vvv = getValuePayMethod(value[columns.payMethod.index]);
      let parent = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.payMethod.index, row]))).parent();
      parent.removeClass('pay-method-oke');
      if(vvv === 2){
       parent.addClass('pay-method-oke');
      }
      parent.removeClass('has_error');
      if(vvv === 2){
       let img = value[columns.image.index];
       let order_info = value[columns.order_info.index];
       let self = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.image.index, row]))).parent();
       if(img.length === 0 || order_info.length === 0){
        self.addClass('has_error');
       }
      }

      let _province = value[columns.province.index]+"".trim();
      let _address = value[columns.address.index]+"".replace(/\s/g, '').trim();
      let _fullname = value[columns.fullname.index]+"".replace(/\s+/g, ' ').trim();

      $col = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.province.index, row])));

      $col.removeClass('bor-error');
      if(_province.length <= 0){
       $col.addClass('bor-error')
      }
      $col = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.address.index, row])));
      $col.removeClass('bor-error')
      if(_address.length <= 0){
       $col.addClass('bor-error')
      }
      $col = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])));
      $col.removeClass('bor-error');

         $colorder_link = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.order_link.index, row])));
         $colorder_link.removeClass('bor-error');

      if(_fullname.length <= 0){
       $col.addClass('bor-error')
      }else{
          let order_link = value[columns.order_link.index]+"".trim();
          if(order_link.length === 0){
              $colorder_link.addClass('bor-error');
              if(!parent.hasClass('has_error')){
                  parent.addClass('has_error');
              }
          }
      }
      parent.removeClass('has_export');
      let _export = value[columns.export.index];
      if((_export+"").toString().length > 0){
       if(_export){
        parent.addClass('has_export');
       }
      }
     }
    },
    onchange:function(instance, cell, c, r, value) {

     c = parseInt(c);
     console.log(change);
     console.log();
     console.log("["+c +" "+ r+"] = "+value);
     if(value.toString().length == 0){
      return;
     }
     if (c === columns.product_name.index) {
      // if(dropdown[value] && dropdown[value].hasOwnProperty('data')){
      if(change.col === c){
       change.col = {col: -1, row: -1};
       console.log("=>>>>>"+value);
       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.product_id.index, r]), value);

       //   instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price.index, r]),dropdown[value].data.price);

       //  instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price_buy.index, r]),dropdown[value].data.price_buy);
       change.col =  {col:-1,row:-1};
       update(instance, cell, c, r,{

       });

      }
      //  }
     }else if(c === columns.total_count.index|| columns.zipcode.index || c === columns.count.index || c === columns.price_buy_sale.index ||
             c === columns.order_ship.index || c === columns.order_ship_cou.index || c === columns.one_address.index){
      if(change.col == c){
       if(c === columns.zipcode.index){
        change = {col:columns.province.index,row:r};
       }else{
        change.col =  {col:-1,row:-1};
        update(instance, cell, c, r,{

        });
       }
      }
     }else if(c === columns.province.index){
      if(change.col == c){
       change.col =  {col:-1,row:-1};
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
       change.col =  {col:-1,row:-1};
       update(instance, cell, c, r,{});
      }
     }
    },

   };
  }

  function BANH_CHUNG() {
      let  sheetName  =  'BANH_CHUNG';
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
      let dropdown = dataproduct.hasOwnProperty(sheetName)?dataproduct[sheetName]:{};
      let index = 0;
      let change = {col:-1,row:-1};
      let oldData = "";
      let customColumn = {
          closeEditor : function(cell, save) {
              console.log("closeEditor");

              return oldData;
          },
          openEditor : function(cell) {

              let dom = $(cell);

              let _spreadsheet = document.getElementById('spreadsheet').children[0].querySelector('.selected');
              let  worksheet = _spreadsheet.getAttribute('data-spreadsheet');

              let _jexcel = spreadsheet.jexcel[worksheet];

              var cellName1 = jexcel.getColumnNameFromId([parseInt(dom.attr('data-x'))-1, dom.attr('data-y')]);
              var cellName2 = jexcel.getColumnNameFromId([parseInt(dom.attr('data-x')), dom.attr('data-y')]);

              oldData = (_jexcel.getValue(cellName2)).toString();
              let valsProduct = (_jexcel.getValue(cellName1)).toString().split(";");

              valsProduct.sort(function(a, b){
                  if(dropdown.hasOwnProperty(a)){
                      return dropdown[b].data.order_index - dropdown[a].data.order_index;
                  }
              });

              let valsCount = {};
              try{
                  valsCount = JSON.parse(oldData);
              }catch (e) {
                  valsCount = {};
              }

              let $html = "<table  class='table table-bordered config_count'>";
              $html+="<tr><th>Tên</th><th>Tên</th><th style='width: 80px;'>Số lượng</th><th></th><th></th><th></th><<th></th><th>Tên</th><th>Tên</th><th style='width: 80px;'>Số lượng</th></tr>";



              let open = false;
              let count = 0;
              for (let i in valsProduct){
                  if(dropdown.hasOwnProperty(valsProduct[i])){
                      let item = dropdown[valsProduct[i]];
                      if(open === false && count%2 === 0){
                          $html+="<tr>";open = true;
                      }
                      $html+="<td>"+item.name+"</td>";
                      $html+="<td>"+item.title+"</td>";
                      $html+="<td><input min=\"1\" max=\"10\" class=\"form-control count\" type='number' data-id='"+valsProduct[i]+"' value='"+(valsCount.hasOwnProperty(valsProduct[i])?valsCount[valsProduct[i]]:0)+"'></td>";
                      if(count === 0){
                          $html+="<td colspan=4></td>";
                      }
                      count++;

                      if(open === true && count === 2){

                          $html+="</tr>";
                          open = false;
                          count = 0;
                      }
                  }
              }
              if(open === true){
                  $html+="</tr>";
                  open = false;
                  count = 0;
              }
              $html+= "<table>";

              let action = function () {
                  let parent = $('.config_count').find('.count');
                  let vals = {};
                  parent.each(function () {
                      let _dom = $(this);
                      let v = parseInt( _dom.val());
                      vals[_dom.attr('data-id')] = isNaN(v)?0:v;
                  });
                  let data = {};
                  for (let i in valsProduct){
                      if(dropdown.hasOwnProperty(valsProduct[i])){
                          if(vals.hasOwnProperty(valsProduct[i])){
                              data[valsProduct[i]] = vals[valsProduct[i]];
                          }else{
                              data[valsProduct[i]] = 1;
                          }
                      }
                  }
                  cell.innerHTML =JSON.stringify(data);
                  oldData = cell.innerHTML;
                  _jexcel.setValue(cellName2,cell.innerHTML);
              };

              bootpopup({
                  title: "Custom HTML",
                  size: "large",
                  showclose: false,
                  content: [$html],
                  ok: action,
                  cancel:function () {
                      console.log('bootpopup:cancel');

                  },
                  dismiss:function () {
                      console.log('bootpopup:dismiss')
                  },
                  before: function () {
                      console.log('bootpopup:before')
                  }
              });
          },
          getValue : function(cell) {
              return cell.innerHTML;
          },
          setValue : function(cell, value) {
              cell.innerHTML = value;
          }
      };
      let columns = {
          status: {
              type: 'checkbox',
              title:'{!! z_language('Trạng thái') !!}'
          },
          image:{
              title:'{!! z_language('Ảnh') !!}',
              type:'image',
              width:"50px",
              hide:true,
          },
          order_info:{
              title: '{!! z_language("Thông tin chuyển khoản") !!}',//T Thông tin chuyển khoản
              type: 'text',
              width:'100px',
              key:"demo",
          },
          timeCreate:{
              title: '{!! z_language("Ngày đặt hàng") !!}',//A ngày đặt hàng
              type: 'calendar',
              width:'100px',
              options: { format:'DD/MM/YYYY' },
              value:['date','now']
          },
          payMethod:{
              title: '{!! z_language("Phương thức thanh toán") !!}',//B Phương thức thanh toán
              type:'dropdown',
              source:[
                  "代金引換",
                  "銀行振込",
                  "決済不要",
              ],
              width:'130px',
              value:['product','this','source',0],
          },
          phone:{
              title: '{!! z_language("Số điện thoại") !!}',//C Số điện thoại
              type: 'text',
              width:'100px',
              value:"070-8409-5968",
          },
          zipcode:{
              title: '{!! z_language("Mã bưu điện") !!}',//D Mã bưu điện
              type: 'text',
              width:'60px',
              key:"demo",
          },
          province:{
              title: '{!! z_language("Tỉnh/TP") !!}',//E Tỉnh/TP
              type: 'text',
              width:'200px',
              key:"demo",
          },
          address:{
              title: '{!! z_language("Địa chỉ giao hàng") !!}',//F Địa chỉ giao hàng
              type: 'text',
              width:'250px',
              key:"demo",
          },
          fullname:{
              title: '{!! z_language("Họ tên người nhận") !!}',//G Họ tên người nhận
              type: 'text',
              width:'150px',
              key:"demo",
          },
          order_link:{
              title: '{!! z_language("Đường dẫn") !!}',//T Thông tin chuyển khoản
              type: 'text',
              width:'100px',
          },
          product_id:{
              title: '{!! z_language("Mã SP") !!}',//H Mã SP
              type: 'text',
              width:'100px',
              read:true,
              value:(function () {
                  $id = "";
                  for(let index in dropdown){
                      $id+=dropdown[index].id+";";
                  }
                  return $id.trim(";");
              })(),
          },
          product_name:{
              title: '{!! z_language("Tên SP") !!}',//I Tên SP
              type:'dropdown',
              source:Object.values(dropdown),
              autocomplete:true,
              multiple: true,
              width:'160px',
              value:(function () {
                  $id = "";
                  for(let index in dropdown){
                      $id+=dropdown[index].id+";";
                  }
                  return $id.trim(";");
              })()
          },
          count:{
              title: '{!! z_language("Loại thịt + SL") !!}',//K SL
              type: 'text',
              width:'150px',
              multiple: true,
              value:(function () {
                  let _count = {};
                  let _id = 0;
                  for(let index in dropdown){
                      if(_id === 0){
                          _id = dropdown[index].id;
                          _count[dropdown[index].id] = 1;
                      }else{
                          _count[dropdown[index].id] = 0;
                      }

                  }
                  return  JSON.stringify(_count);
              })()
          },
          total_count:{
              title: '{!! z_language("SL Tổng") !!}',//K SL  title: '',//K SL Tổng
              type: 'numeric',
              width:'60px',
              value:1,
          },
          price:{
              title: '{!! z_language("Giá nhập") !!}',//J Giá nhập
              type: 'numeric',
              width:'100px',
              value:['product','product_name','source',0,'data','price'],
          },
          price_buy:{
              title: '{!! z_language("Giá bán") !!}',//J Giá nhập
              type: 'numeric',
              width:'100px',
              value:['product','product_name','source',0,'data','price_buy'],
          },
          order_date:{
              title: '{!! z_language("Ngày nhận") !!}',//L Ngày nhận
              type:'calendar',
              options: { format:'DD/MM/YYYY'},
              value:['date','nowSum',2],
              width:'100px',

          },
          order_hours:{
              title: '{!! z_language("Giờ nhận") !!}',//M Giờ nhận
              type: 'dropdown',
              source:['8:00 ~ 12:00','12:00～14:00','14:00～16:00','16:00～18:00','18:00～20:00','19:00～21:00','20:00～21:00'],
              value:['product','this','source',5],
              width:'150px',
          },
          order_ship:{
              title: '{!! z_language("Phí ship") !!}',//N Phí ship
              type: 'numeric',
              width:'100px',
              value:0
          },
          order_total_price:{
              title: '{!! z_language("Tổng giá nhập") !!}',//O Tổng giá nhập
              type: 'numeric',
              width:'100px',
              value:['product','product_name','source',0,'data','price'],
          },
          price_buy_sale:{
              title: '{!! z_language("Tăng Giảm") !!}',//J Giá nhập
              type: 'numeric',
              width:'100px',
              value:0,
          },
          order_total_price_buy:{
              title: '{!! z_language("Total Bán") !!}',//P Giá bán
              type: 'numeric',
              width:'100px',
              value:['product','product_name','source',0,'data','price_buy'],
          },
          order_ship_cou:{
              title: '{!! z_language("Phí giao hàng") !!}',//P Phí giao hàng
              type: 'numeric',
              width:'100px',
              value:0
          },
          order_price:{
              title: '{!! z_language("Lợi nhuận") !!}',//P Lợi nhuận
              type: 'numeric',
              width:'100px',
              value:0
          },
          order_rate:{
              title: '{!! z_language("Lợi nhuận CTV") !!}',//P
              type: 'numeric',
              width:'100px',
              value:0
          },
          order_tracking:{
              title: '{!! z_language("Mã tracking") !!}',//T Mã tracking
              type: 'text',
              width:'100px',
              key:"demo",
          },


          one_address: {
              type: 'checkbox',
              title:'{!! z_language("Cùng địa chỉ") !!}'
          },
          id:{
              title: '{!! z_language("ID") !!}ID',//T
              type: 'text',
              width:'100px',
          },
          session_id:{
              title: '{!! z_language("SessionId") !!}',//T
              type: 'text',
              width:'1px',
          },
          export:{
              title: '{!! z_language("Xuất") !!}',//T
              type: 'checkbox',
              width:'1px',
          },
          token:{
              title: '{!! z_language("Token") !!}',//T
              type: 'text',
              width:'1px',
          },
          admin:{
              title: '{!! z_language("Admin") !!}',//T
              type: 'text',
              width:'100px',
          },
          group:{
              title: '{!! z_language("group") !!}',//T
              type: 'text',
              width:'1px',
          },
          comment:{
              title: '{!! z_language("Ghi chú") !!}',//T
              type: 'text',
              width:'100px',
          },
          order_product_link:{
              title: '{!! z_language("Link SP") !!}',//T
              type: 'text',
              width:'100px',
          },
      };
      columns.count.editor = customColumn;
      columnsAll[sheetName] = columns;
      let columns_index = Object.values(columns);
      let hide = hideprototy.hasOwnProperty(sheetName)?hideprototy[sheetName]:{};
      let _option = options.hasOwnProperty(sheetName)?options[sheetName]:{};
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
          if(hide.hasOwnProperty(i)){
              columns[i].width =hide[i]+"px";
          }else if(_option.hasOwnProperty('colums')){
              if(_option.colums.hasOwnProperty(i)){
                  columns[i].width = "1px";
              }
          }else if(columnsWidth.hasOwnProperty(i) && i!="count"){
              columns[i].width =columnsWidth[i].width;
          }
      }
      function update(instance, cell, c, r, value) {
          console.log("update call");
          let data = {
              count:instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.count.index, r])),
              id:value.hasOwnProperty('id')?value.id.toString():instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.product_id.index, r])).toString(),
              province:value.hasOwnProperty('province')?value.province:instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.province.index, r])),
          };
          data.province = data.province.trim();
          data.id = data.id.split(';');

          console.log(data);

          let count = parseInt(data.count);

          if(!isNaN(count)){
              data.count = {};
              data.count[data.id[0]] = count;
          }else{
              try{
                  data.count = JSON.parse(data.count);
              }catch (e) {
                  data.count = {};
                  data.count[data.id[0]] = 1;
              }
          }



          let valueRow =  instance.jexcel.getRowData(r);
          let payMethod = getValuePayMethod(valueRow[columns.payMethod.index]);

          data.payMethod = payMethod;
          data.sheetName = sheetName;

          console.log("payMethod:"+payMethod);

          let price_buy_sale = parseInt(valueRow[columns.price_buy_sale.index]);
          console.log("price_buy_sale:"+price_buy_sale);
          let price_buy=0;
          let price=0;

          let total_price_buy =  0;
          let total_price =  0;
          let countNew = {};
          let totalCount = 0;

          for(let i in data.id){
              if(data.id.hasOwnProperty(i)){
                  if(dropdown.hasOwnProperty(data.id[i])){
                      let product = dropdown[data.id[i]];

                      let _price = parseInt(product.data.price);
                      let _price_buy = parseInt(product.data.price_buy);


                      if(!data.count.hasOwnProperty(data.id[i])){
                          data.count[data.id[i]] = 1;
                      }else{
                          data.count[data.id[i]] = parseInt(data.count[data.id[i]]);
                      }

                      price+=_price*data.count[data.id[i]];
                      price_buy+=_price_buy*data.count[data.id[i]];

                      totalCount+=data.count[data.id[i]];
                      countNew[data.id[i]] = data.count[data.id[i]];

                      total_price_buy+= _price_buy * data.count[data.id[i]];
                      total_price+=_price * data.count[data.id[i]];
                  }

              }
          }
          let product_id = data.id[0];


          instance.jexcel.getCell(
              jexcel.getColumnNameFromId([columns.count.index, r])).innerHTML = JSON.stringify(countNew);

          let total_count  = parseInt(valueRow[columns.total_count.index]);

          data.total_price = total_price * total_count;
          data.total_price_buy = total_price_buy * total_count + price_buy_sale;

          total_price_buy =  data.total_price_buy;
          total_price = data.total_price;

          let total_price_buy_all = 0;
          let Row = -1;
          let token = parseInt(valueRow[columns.token.index]);
          console.log("valueRow[columns.one_address.index]:"+valueRow[columns.one_address.index]);
          console.log("token:"+token);
          if(!valueRow[columns.one_address.index]){
              total_price_buy_all = total_price_buy;
              for(let iii = r+1;iii<10;iii++){
                  let _data = instance.jexcel.getRowData(iii);
                  if(_data && _data[columns.one_address.index]){
                      console.log(_data);
                      let total = parseInt(_data[columns.order_total_price_buy.index]);
                      total_price_buy_all+= isNaN(total)?0:total;
                  }else{
                      break
                  }
              }
              console.log(11111111111111);
              if(!isNaN(token)){
                  Row = token;
              }
              console.log("Row:"+Row);
              token = "";
          }else{
              for(let iii = r-1;iii>=0;iii--){
                  let _data = instance.jexcel.getRowData(iii);
                  console.log(data);
                  if(_data && !_data[columns.one_address.index]){
                      token = iii;
                      Row = token;
                      break
                  }
              }
          }

          console.log("token:"+token);
          console.log("Row:"+Row);
          console.log("total_price_buy_all:"+total_price_buy_all);

          instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.token.index, r]), token);

          total_price_buy =  data.total_price_buy;
          total_price = data.total_price;




          instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price.index, r]), price);
          instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price_buy.index, r]), price_buy);

          instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price.index, r]),  data.total_price);

          function GetShip($product,$category_id,$count,$province,$total_price_buy,payMethod,total_price_buy_all) {
              if(total_price_buy_all == 0){
                  total_price_buy_all = $total_price_buy;
              }
              let configShip = dataship.hasOwnProperty("cate_"+$category_id)?dataship["cate_"+$category_id]:[];
              console.log(configShip);
              console.log("$count:"+$count);
              console.log("$province:"+$province);
              console.log("$total_price_buy:"+$total_price_buy);
              let arr_ship = [];
              for(let i in configShip){
                  $is_IF_Start = IF_Start($count,configShip[i]);
                  $is_IF_End =  IF_End($count,configShip[i]);
                  if($is_IF_Start && $is_IF_End){
                      $conf  =  configShip[i].config;
                      for (let ii in $conf){
                          $val = $conf[ii];
                          $arr = $val['text'].split("-");
                          for (let iii in $arr){
                              $v = $arr[iii];
                              if($province == $v){
                                  arr_ship.push([configShip[i],$val])
                              }
                          }
                      }
                  }
              }
              console.log(arr_ship);
              $price_ship_default  = -1;
              $price_ship  = -1;
              for (i in arr_ship){
                  $val = arr_ship[i];
                  if($val[0].unit == 0 && $price_ship_default==-1){
                      $price_ship_default =  $val[1]['value'];
                  }else if($val[0].unit == $product.unit && $price_ship == -1){
                      $price_ship = $val[1]['value'];
                  }
              }

              console.log('$price_ship_default:'+$price_ship_default);
              console.log('$price_ship:'+$price_ship);
              let $ship = categorys[$category_id]?( categorys[$category_id].data.hasOwnProperty('ship'))?categorys[$category_id].data.ship:"-1":"-1";

              let $ship_cou = -1;

              let one_address = instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.one_address.index, r]));
              if(one_address){
                  payMethod = 2;
              }
              if( payMethod == 2 || payMethod == 3 ){
                  $ship_cou = 0;
              }else{
                  for (let i in datadaibiki){
                      $_val  = datadaibiki[i];

                      if($ship == $_val.id){
                          for(let ii in $_val.data){
                              $units = $_val.data[ii];
                              for(let iii in $units){
                                  $_unit = $units[iii];
                                  if($_unit){
                                      $is_IF_Start = IF_Start(total_price_buy_all,$_unit);
                                      $is_IF_End = IF_End(total_price_buy_all,$_unit);
                                      if($is_IF_Start && $is_IF_End){
                                          $ship_cou = $_unit.value;
                                      }
                                  }
                              }
                          }
                      }
                      if($ship_cou != -1){
                          break;
                      }
                  }
              }
              let price_ship =  $price_ship!=-1?$price_ship:$price_ship_default;
              $ship_cou = $ship_cou == -1?0:$ship_cou;
              return {
                  order_ship:parseInt(price_ship === -1 ? 0 :price_ship),
                  order_ship_cou:parseInt($ship_cou)
              };
          }
          function setInterest(price_ship,order_ship_cou,total_price_buy,total_count){

              price_ship = price_ship * total_count;
              console.log("price_ship:"+price_ship);
              console.log("total_price_buy:"+total_price_buy);
              instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship.index, r]),price_ship);
              // total_price_buy = total_price_buy;
              console.log(total_price_buy);
              if(total_price_buy === 0 || total_price === 0){ return;}

              if(payMethod == 3){
                  instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]), 0);
                  instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]), 0);
                  instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price_buy.index, r]), 0);
                  instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]), 0);
                  instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]), total_price_buy,false );
              }else if(payMethod == 2){
                  instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]), total_price_buy-330,false );
                  let a = (parseInt(total_price_buy) - parseInt(total_price) - parseInt(price_ship)) - 330;
                  instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]),a,false);
                  instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]),0,false);
              }else{
                  let one_address = instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.one_address.index, r]));
                  if(one_address){

                      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]), total_price_buy-330,false );
                      let a = (parseInt(total_price_buy)-330- parseInt(total_price) - parseInt(price_ship)) - 330;

                      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]),a,false);
                      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]),0,false);
                  }else{
                      console.log("price_ship:"+price_ship);
                      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]), total_price_buy,false );
                      let a = (parseInt(total_price_buy) - parseInt(total_price) - parseInt(price_ship) - order_ship_cou);

                      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]),a,false);
                      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]),order_ship_cou,false);
                  }

              }
              if(Row>-1)
              {
                  console.log("Update:Row"+Row);
                  update(instance,cell, c, Row, {});
              }
          }
          data.count = totalCount;
          if(dropdown.hasOwnProperty(product_id)){
              let confShipCou = GetShip(dropdown[product_id].data,dropdown[product_id].data.category_id,totalCount,data.province,total_price_buy,payMethod,total_price_buy_all);

              setInterest(confShipCou.order_ship   , confShipCou.order_ship_cou,total_price_buy + 330,total_count)
          }

          //    if(value.hasOwnProperty('lock') && value.lock.indexOf(columns.order_ship.index)){

          //     } else{
          //         setInterest(parseInt(valueRow[columns.order_ship.index]),parseInt(valueRow[columns.order_ship_cou.index]));
          //     }
      }
      let _data = InitData(data,config,columns_index,sheetName);

      let click = false;

      let nestedHeaders = [];
      if(locks .hasOwnProperty(sheetName)){
          let _lock  = locks [sheetName];
          let dateNow = stringDate;
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
          onload: function (e) {
              let td  = $(e).find('.jexcel tbody tr').find('td:first-child');
              td.click(function () {
                  let parent = $(this).parent().data();

                  if(parent.hasOwnProperty('info')){
                      let info = parent.info;
                      let company = parent.company;



                      for(let tab in info){
                          let dom = $("#tab_"+tab);
                          let table = "<table class=\"table table-bordered\">";
                          table+="<tr>";
                          // table+='<td>Mã</td>';
                          // table+='<td>Phiên</td>';
                          table+='<td>{!! z_language('Người lập') !!}</td>';
                          table+='<td>{!! z_language("Ho Tên") !!}</td>';
                          table+='<td>{!! z_language("Địa chỉ") !!}</td>';
                          table+='<td>{!! z_language("Tỉnh") !!}</td>';
                          table+='<td>{!! z_language("Ngày tạo") !!}</td>';
                          table+='<td>{!! z_language("Số điện thoại") !!}</td>';
                          table+='<td>{!! z_language("Công ty") !!}</td>';
                          table+='<td>{!! z_language("Sản phẩm") !!}</td>';

                          table+='<td>{!! z_language("Trạng thái") !!}</td>';
                          table+='<td>{!! z_language("Xuất") !!}</td>';
                          table+='<td>{!! z_language("Đường dẫn") !!}</td>';
                          table+="</tr>";
                          for(let pos in info){
                              for(let com in info[pos]){
                                  if(com!=company) continue;
                                  for(let index in info[pos][com]){
                                      let val = info[pos][com][index];
                                      table+="<tr>";
                                      table+='<td>'+(info_admin.hasOwnProperty(val.admin_id)?info_admin[val.admin_id].name:"Không xác định")+'</td>';
                                      table+='<td>'+val.fullname+'</td>';
                                      table+='<td>'+val.address+'</td>';
                                      table+='<td>'+val.province+'</td>';
                                      table+='<td>'+val.order_create_date+'</td>';
                                      table+='<td>'+val.phone+'</td>';
                                      table+='<td>'+val.company+'</td>';
                                      table+= "<td><table class=\"table table-bordered\">";
                                      table+='<td>'+val.product_title+'</td>';
                                      table+='<td>'+val.count+'</td>';
                                      if(val.hasOwnProperty('items')){
                                          for(let _index in val.items){
                                              table+="<tr>";
                                              table+='<td>'+val.items[_index].product_title+'</td>';
                                              table+='<td>'+val.items[_index].count+'</td>';
                                              table+="</tr>";
                                          }
                                      }
                                      table+="</table></td>";
                                      table+='<td>'+(val.public == 1?"Đơn":"Nháp")+'</td>';
                                      table+='<td>'+(val.export == 1?"Xuất":"Chưa")+'</td>';
                                      table+='<td>'+val.order_link+'</td>';
                                      table+="</tr>";
                                  }
                              }
                          }

                          table+="</table>";
                          dom.html(table);
                      }
                      $('#modal-default').modal('show');
                  }
              });
          },
          nestedHeaders:[
              nestedHeaders
          ],
          contextMenu: function(obj, x, y, e) {
              var items = [];
              return items;
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
              if(click === true){
                  return;
              }
              click = true;

              console.log("x1"+x1 +" y1: "+ y1);

              setTimeout(function () {
                  click = false;

                  var cellName1 = jexcel.getColumnNameFromId([columns_index[x1].index, y1]);
                  let cell = instance.jexcel.getCell(cellName1);
                  $(".onselection").hide();
                  let val = instance.jexcel.getValue(cellName1);
                  $("#col-row-review").data({"x":x1,y:y1}).val(cellName1);
                  console.log("ACTION");
                  if(columns_index[x1] && columns_index[x1].type === "dropdown"){
                      if(cell.classList.contains('editor')){
                          console.log("val:"+val);
                          change = {col:x1,row:y1};
                          $("#value-review").hide();
                          $html = $("<div>");
                          $("#zoe-dropdown-review").show().html($html);
                          let init = true;
                          change = {col:x1,row:y1};
                          init = false;
                          // jSuites.dropdown($html[0], {
                          //     data:columns_index[x1].source,
                          //     value:val,
                          //     autocomplete: columns_index[x1].hasOwnProperty('autocomplete'),
                          //     multiple: columns_index[x1].hasOwnProperty('multiple'),
                          //     width:'100%',
                          //     onchange:function (el, a, oldValue, Value) {
                          //         //instance.jexcel.setValue(jexcel.getColumnNameFromId([x1, y1]), Value);
                          //     },
                          // }).setValue(val);
                      }
                  }else if(columns_index[x1].key === "order_info"){

                      //NGUYEN V 様 00 日に 0000 円入金済み
                      let vals = val.trim().split(' ');
                   let a = val.trim()+"";
                   let b = ["様","日に","円入金済み"];

                   vals=[];
                   if(a.length > 0){

                    for(let i = 0; i<b.length;i++){
                     let results = a.split(b[i]);
                     if(results.length == 0) break;
                     a = results[1];
                     let val = results[0].trim();
                     if(val.length > 0){
                      vals.push(val);
                     }
                    }
                   }
                      $("#info_payment").show();
                      let html  = "<table>";
                      html+="<tr>";

                      html+="<th>";
                      html+='<input type="text" class="form-control name" value="'+(vals[0]?vals[0]:"")+'">';
                      html+="</th>";

                      html+="<th>";
                      html+='様';
                      html+="</th>";
                      html+="<th>";
                      html+='<input type="text" class="form-control date" value="'+(vals[1]?vals[1]:"")+'">';
                      html+="</th>";

                      html+="<th>";
                      html+='日に';
                      html+="</th>";

                      html+="<th>";
                      html+='<input type="text" class="form-control price" value="'+(vals[2]?vals[2]:"")+'">';
                      html+="</th>";

                      html+="<th>";
                      html+='円入金済み';
                      html+="</th>";

                      html+="<td>";
                      html+='&nbsp;<button type="button" class="btn">{!! z_language('Lưu') !!}</button>';
                      html+="</td>";
                      html+="</tr>";
                      html+="</table>";
                      var dom = $(html);
                      dom.find('button').click(function () {
                          let th = dom.find('tr th');
                          let vals = "";
                          console.log(th);
                          for(let i = 0; i< th.length ; i++){
                              if(i%2===0){
                                  vals+=" "+$(th[i]).find('input').val().trim();
                              }else{
                                  vals+=" "+$(th[i]).text().trim();
                              }
                          }
                          setTimeout(function () {
                              instance.jexcel.setValue(jexcel.getColumnNameFromId([x1, y1]), vals.trim());
                          },1000);
                      });
                      $("#info_payment").html(dom);
                  }
                  else{
                      change = {col:x1,row:y1};
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
              },500);
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
                  let countAddress = 0;
                  for(let k in instance.jexcel.rows){
                      let _val  = instance.jexcel.getRowData(k);
                      if(value[columns.fullname.index].length > 0 && value[columns.fullname.index].replace(/\s+/g, ' ') === _val[columns.fullname.index].replace(/\s+/g, ' ')){
                          count++;
                      }
                      if(value[columns.address.index].length > 0 && value[columns.address.index].replace(/\s/g, '') === _val[columns.address.index].replace(/\s/g, '')){
                          countAddress++;
                      }
                  }
                  instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])).classList.remove('error');
                  if(count > 1) instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])).classList.add('error');

                  instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.address.index, row])).classList.remove('error');
                  if(countAddress > 1) instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.address.index, row])).classList.add('error');

                  let vvv = getValuePayMethod(value[columns.payMethod.index]);
                  let parent = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.payMethod.index, row]))).parent();
                  parent.removeClass('pay-method-oke');
                  if(vvv === 2){
                      parent.addClass('pay-method-oke');
                  }
                  parent.removeClass('has_error');
                  if(vvv === 2){
                      let img = value[columns.image.index];
                      let order_info = value[columns.order_info.index];
                      let self = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.image.index, row]))).parent();
                      if(img.length === 0 || order_info.length === 0){
                          self.addClass('has_error');
                      }
                  }

                  let _province = value[columns.province.index]+"".trim();
                  let _address = value[columns.address.index]+"".replace(/\s/g, '').trim();
                  let _fullname = value[columns.fullname.index]+"".replace(/\s+/g, ' ').trim();

                  $col = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.province.index, row])));

                  $col.removeClass('bor-error');
                  if(_province.length <= 0){
                      $col.addClass('bor-error')
                  }
                  $col = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.address.index, row])));
                  $col.removeClass('bor-error')
                  if(_address.length <= 0){
                      $col.addClass('bor-error')
                  }
                  $col = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])));
                  $col.removeClass('bor-error');

                  $colorder_link = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.order_link.index, row])));
                  $colorder_link.removeClass('bor-error');

                  if(_fullname.length <= 0){
                      $col.addClass('bor-error')
                  }else{
                      let order_link = value[columns.order_link.index]+"".trim();
                      if(order_link.length === 0){
                          $colorder_link.addClass('bor-error');
                          if(!parent.hasClass('has_error')){
                              parent.addClass('has_error');
                          }
                      }
                  }
                  parent.removeClass('has_export');
                  let _export = value[columns.export.index];
                  if((_export+"").toString().length > 0){
                      if(_export){
                          parent.addClass('has_export');
                      }
                  }
              }
          },
          onchange:function(instance, cell, c, r, value) {

              c = parseInt(c);
              console.log(change);
              console.log();
              console.log("["+c +" "+ r+"] = "+value);
              if(value.toString().length == 0){
                  return;
              }
              if (c === columns.product_name.index) {
                  // if(dropdown[value] && dropdown[value].hasOwnProperty('data')){
                  if(change.col === c){
                      change.col = {col: -1, row: -1};
                      console.log("=>>>>>"+value);
                      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.product_id.index, r]), value);

                      //   instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price.index, r]),dropdown[value].data.price);

                      //  instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price_buy.index, r]),dropdown[value].data.price_buy);
                      change.col =  {col:-1,row:-1};
                      update(instance, cell, c, r,{

                      });

                  }
                  //  }
              }else if(c === columns.total_count.index|| columns.zipcode.index || c === columns.count.index || c === columns.price_buy_sale.index ||
                  c === columns.order_ship.index || c === columns.order_ship_cou.index || c === columns.one_address.index){
                  if(change.col == c){
                      if(c === columns.zipcode.index){
                          change = {col:columns.province.index,row:r};
                      }else{
                          change.col =  {col:-1,row:-1};
                          update(instance, cell, c, r,{

                          });
                      }
                  }
              }else if(c === columns.province.index){
                  if(change.col == c){
                      change.col =  {col:-1,row:-1};
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
                      change.col =  {col:-1,row:-1};
                      update(instance, cell, c, r,{});
                  }
              }
          },

      };
  }
  function OHGA() {
   let  sheetName  =  'OHGA';
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

   let dropdown = dataproduct.hasOwnProperty(sheetName)?dataproduct[sheetName]:{};
   let index = 0;

   let columns = {
    status: {
     type: 'checkbox',
     title:'{!! z_language('Trạng thái') !!}'
    },
    image:{
     title:'{!! z_language('Ảnh') !!}',
     type:'image',
     width:"50px",
     hide:true,
    },

       order_info:{
           title: '{!! z_language("Thông tin chuyển khoản") !!}',//T Thông tin chuyển khoản
           type: 'text',
           width:'100px',
           key:"demo",
       },
    timeCreate:{
     title: '{!! z_language("Ngày đặt hàng") !!}',//A ngày đặt hàng
     type: 'calendar',
     width:'100px',
     options: { format:'DD/MM/YYYY' },
     value:['date','now']
    },
    payMethod:{
     title: '{!! z_language("Phương thức thanh toán") !!}',//B Phương thức thanh toán
     type:'dropdown',
     source:[
      "代金引換",
      "銀行振込",
      "決済不要",
     ],
     width:'130px',
     value:['product','this','source',0],
    },
    phone:{
     title: '{!! z_language("Số điện thoại") !!}',//C Số điện thoại
     type: 'text',
     width:'100px',
     value:"070-8409-5968",
    },
    zipcode:{
     title: '{!! z_language("Mã bưu điện") !!}',//D Mã bưu điện
     type: 'text',
     width:'60px',
     key:"demo",
    },
    province:{
     title: '{!! z_language("Tỉnh/TP") !!}',//E Tỉnh/TP
     type: 'text',
     width:'200px',
     key:"demo",
    },
    address:{
     title: '{!! z_language("Địa chỉ giao hàng") !!}',//F Địa chỉ giao hàng
     type: 'text',
     width:'250px',
     key:"demo",
    },
    fullname:{
     title: '{!! z_language("Họ tên người nhận") !!}',//G Họ tên người nhận
     type: 'text',
     width:'150px',
     key:"demo",
    },
       order_link:{
           title: '{!! z_language("Đường dẫn") !!}',//T Thông tin chuyển khoản
           type: 'text',
           width:'100px',
       },
    product_id:{
     title: '{!! z_language("Mã SP") !!}',//H Mã SP
     type: 'text',
     width:'100px',
     read:true,
     value:['product','product_name','source',0,'id'],
    },
    product_name:{
     title: '{!! z_language("Tên SP") !!}',//I Tên SP
     type:'dropdown',
     source:Object.values(dropdown),
     autocomplete:true,
     width:'140px',
     value:['product','this','source',0,'id']
    },
    count:{
     title: '{!! z_language("SL") !!}',//I Tên SP
     type: 'numeric',
     width:'100px',
     value:1
    },
    price:{
     title: '{!! z_language("Giá nhập") !!}',//J Giá nhập
     type: 'numeric',
     width:'100px',
     value:['product','product_name','source',0,'data','price'],
    },
    price_buy:{
     title: '{!! z_language("Giá bán") !!}',//J Giá nhập
     type: 'numeric',
     width:'100px',
     value:['product','product_name','source',0,'data','price_buy'],
    },
    order_date:{
     title: '{!! z_language("Ngày nhận") !!}',//L Ngày nhận
     type:'calendar',
     options: { format:'DD/MM/YYYY'},
     value:['date','nowSum',2],
     width:'100px',

    },
    order_hours:{
     title: '{!! z_language("Giờ nhận") !!}',//M Giờ nhận
     type: 'dropdown',
     source:['8:00 ~ 12:00','12:00～14:00','14:00～16:00','16:00～18:00','18:00～20:00','19:00～21:00','20:00～21:00'],
     value:['product','this','source',5],
     width:'150px',
    },
    order_ship:{
     title: '{!! z_language("Phí ship") !!}',//N Phí ship
     type: 'numeric',
     width:'100px',
     value:0
    },
    order_total_price:{
     title: '{!! z_language("Tổng giá nhập") !!}',//O Tổng giá nhập
     type: 'numeric',
     width:'100px',
     value:['product','product_name','source',0,'data','price'],
    },
    price_buy_sale:{
     title: '{!! z_language("Tăng Giảm") !!}',//J Giá nhập
     type: 'numeric',
     width:'100px',
     value:0,
    },
    order_total_price_buy:{
     title: '{!! z_language("Total Bán") !!}',//P Giá bán
     type: 'numeric',
     width:'100px',
     value:['product','product_name','source',0,'data','price_buy'],
    },
    order_ship_cou:{
     title: '{!! z_language("Phí giao hàng") !!}',//P Phí giao hàng
     type: 'numeric',
     width:'100px',
     value:330
    },
    order_price:{
     title: '{!! z_language("Lợi nhuận") !!}',//P Lợi nhuận
     type: 'numeric',
     width:'100px',
     value:0
    },
    order_rate:{
     title: '{!! z_language("Lợi nhuận CTV") !!}',//P
     type: 'numeric',
     width:'100px',
     value:0
    },
    order_tracking:{
     title: '{!! z_language("Mã tracking") !!}',//T Mã tracking
     type: 'text',
     width:'100px',
     key:"demo",
    },

    one_address: {
     type: 'checkbox',
     title:'{!! z_language("Cùng địa chỉ") !!}'
    },
    id:{
     title: '{!! z_language("ID") !!}ID',//T
     type: 'text',
     width:'100px',
    },
    session_id:{
     title: '{!! z_language("SessionId") !!}',//T
     type: 'text',
     width:'1px',
    },
    export:{
     title: '{!! z_language("Xuất") !!}',//T
     type: 'checkbox',
     width:'1px',
    },
    token:{
     title: '{!! z_language("Token") !!}',//T
     type: 'text',
     width:'1px',
    },
    admin:{
     title: '{!! z_language("Admin") !!}',//T
     type: 'text',
     width:'100px',
    },
    group:{
     title: '{!! z_language("group") !!}',//T
     type: 'text',
     width:'1px',
    },
    comment:{
     title: '{!! z_language("Ghi chú") !!}',//T
     type: 'text',
     width:'100px',
    },
       order_product_link:{
           title: '{!! z_language("Link SP") !!}',//T
           type: 'text',
           width:'100px',
       },
   };

   columnsAll[sheetName] = columns;
   let hide = hideprototy.hasOwnProperty(sheetName)?hideprototy[sheetName]:{};
   let _option = options.hasOwnProperty(sheetName)?options[sheetName]:{};
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
    if(hide.hasOwnProperty(i)){
     columns[i].width =hide[i]+"px";
    }else if(_option.hasOwnProperty('colums')){
     if(_option.colums.hasOwnProperty(i)){
      columns[i].width = "1px";
     }
    }else if(columnsWidth.hasOwnProperty(i)){
     columns[i].width =columnsWidth[i].width;
    }
   }

   function update(instance, cell, c, r, value) {
    console.log("update call");

    let data = {
     count:value.hasOwnProperty('count')?value.count:parseInt(instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.count.index, r]))),
     id:value.hasOwnProperty('id')?value.id:instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.product_id.index, r])),
     province:value.hasOwnProperty('province')?value.province:instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.province.index, r])),
    };
    data.province = data.province.trim();
    let total_price_buy =  0;
    let total_price =  0;

    let valueRow =  instance.jexcel.getRowData(r);
    let payMethod = getValuePayMethod(valueRow[columns.payMethod.index]);
    data.payMethod = payMethod;
    data.sheetName = sheetName;
    console.log("payMethod:"+payMethod);
    let price_buy_sale = parseInt(valueRow[columns.price_buy_sale.index]);
    console.log("price_buy_sale:"+price_buy_sale);
    let total_price_buy_all = 0;
    let Row = -1;
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
     console.log(total_price_buy);

     data.total_price_buy = total_price_buy;

     let token = parseInt(valueRow[columns.token.index]);
     console.log("valueRow[columns.one_address.index]:"+valueRow[columns.one_address.index]);
     console.log("token:"+token);
     if(!valueRow[columns.one_address.index]){

      total_price_buy_all = total_price_buy;

      for(let iii = r+1;iii<10;iii++){
       let _data = instance.jexcel.getRowData(iii);
       if(_data && _data[columns.one_address.index]){
        console.log(_data);
        let total = parseInt(_data[columns.order_total_price_buy.index]);
        total_price_buy_all+= isNaN(total)?0:total;
       }else{
        break
       }
      }
      console.log(11111111111111);
      if(!isNaN(token)){
       Row = token;
      }
      console.log("Row:"+Row);
      token = "";
     }else{
      for(let iii = r-1;iii>=0;iii--){
       let _data = instance.jexcel.getRowData(iii);
       console.log(data);
       if(_data && !_data[columns.one_address.index]){
        token = iii;
        Row = token;
        break
       }
      }
     }
     console.log("token:"+token);
     console.log("Row:"+Row);
     data.total_price_buy = total_price_buy;

     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.token.index, r]), token);

     console.log("total_price_buy_all:"+total_price_buy_all);

     let confShipCou = GetShip(product.data,product.data.category_id,data.count,data.province,data.total_price_buy, data.payMethod,total_price_buy_all);

     console.log(confShipCou);

     setInterest(confShipCou.order_ship,confShipCou.order_ship_cou,confShipCou.total_price_buy);

    }
    function GetShip($product,$category_id,$count,$province,$total_price_buy,payMethod,total_price_buy_all) {

     let configShip = dataship.hasOwnProperty("cate_"+$category_id)?dataship["cate_"+$category_id]:[];
     if(total_price_buy_all == 0){
      total_price_buy_all = $total_price_buy;
     }
     console.log(configShip);
     console.log("$count:"+$count);
     console.log("$province:"+$province);
     console.log("$total_price_buy:"+$total_price_buy);

     let $ship_cou = -1;

     if(sheetName === "YAMADA"){
      if(payMethod === 1){
       if(valueRow[columns.one_address.index]){
        $ship_cou = 0;
       }else{
        $total_price_buy =  $total_price_buy + 330;
        total_price_buy_all =  total_price_buy_all + 330;
       }
      }else{
       $ship_cou = 0;
      }
     }else  if(sheetName === "OHGA"){
      if(payMethod === 1){
       if(valueRow[columns.one_address.index]){
        $ship_cou = 0;
       }else{
        $ship_cou = 330;
        $total_price_buy =  $total_price_buy + 330;
        total_price_buy_all =  total_price_buy_all + 330;
       }
      }else{
       $ship_cou = 0;
      }
     }else  if(sheetName === "FUKUI"){
      if(payMethod === 1){
       if(valueRow[columns.one_address.index]){
        $ship_cou = 0;
       }else{
        $total_price_buy =  $total_price_buy + 330;
        $ship_cou = 330;
       }
      }else{
       $ship_cou = 0;
      }
     }else if(sheetName === "KURICHIKU"){
      if(payMethod === 1){
       if(valueRow[columns.one_address.index]){
        $ship_cou = 0;
       }else{
        $total_price_buy =  $total_price_buy + 330;
        $ship_cou = 330;
       }
      }else{
       $ship_cou = 0;
      }
     }
     let arr_ship = [];
     for(let i in configShip){
      $is_IF_Start = IF_Start($count,configShip[i]);
      $is_IF_End =  IF_End($count,configShip[i]);
      if($is_IF_Start && $is_IF_End){
       $conf  =  configShip[i].config;
       for (let ii in $conf){
        $val = $conf[ii];
        $arr = $val['text'].split("-");
        for (let iii in $arr){
         $v = $arr[iii];
         if($province == $v){
          arr_ship.push([configShip[i],$val])
         }
        }
       }
      }
     }
     console.log(arr_ship);
     let $price_ship_default  = -1;
     let $price_ship  = -1;

     for (i in arr_ship){
      let $val = arr_ship[i];
      if($val[0].unit == 0 && $price_ship_default==-1){
       $price_ship_default =  $val[1]['value'];
      }else if($val[0].unit == $product.unit && $price_ship == -1){
       $price_ship = $val[1]['value'];
      }
     }

     console.log('$price_ship_default:'+$price_ship_default);
     console.log('$price_ship:'+$price_ship);

     let $ship = categorys[$category_id]?( categorys[$category_id].data.hasOwnProperty('ship'))?categorys[$category_id].data.ship:"-1":"-1";

     if( payMethod == 2 || payMethod == 3 ){
      $ship_cou = 0;
     }else{
      for (let i in datadaibiki){
       let $_val  = datadaibiki[i];
       if($ship == $_val.id){
        for(let ii in $_val.data){
         $units = $_val.data[ii];
         for(let iii in $units){
          $_unit = $units[iii];
          if($_unit){
           $is_IF_Start = IF_Start(total_price_buy_all,$_unit);
           $is_IF_End = IF_End(total_price_buy_all,$_unit);
           if($is_IF_Start && $is_IF_End){
            $ship_cou = $_unit.value;
           }
          }
         }
        }
       }
       if($ship_cou != -1){
        break;
       }
      }
     }

     let price_ship =  $price_ship!=-1?$price_ship:$price_ship_default;
     $ship_cou = $ship_cou == -1?0:$ship_cou;
     return {order_ship:parseInt(price_ship == -1?0:price_ship),order_ship_cou:parseInt($ship_cou),total_price_buy:$total_price_buy,total_price_buy_all:total_price_buy_all};
    }
    function setInterest(price_ship,order_ship_cou,total_price_buy){
     price_ship = price_ship*data.count;
     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship.index, r]),price_ship);


     total_price_buy = total_price_buy+price_ship;

     if(total_price_buy ===0 || total_price == 0){ return;}
     let one_address = instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.one_address.index, r]));
     if(one_address){
      // payMethod = 2;
     }
     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]), total_price_buy,false );
     if(payMethod == 3){
      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]), 0);
      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]), 0);
      // instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price_buy.index, r]), 0);
      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]), 0);
     }else if(payMethod == 2){
      let a = (parseInt(total_price_buy) - parseInt(total_price) - parseInt(price_ship));

      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]),a,false);
      instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]),0,false);
     }else{
      if(one_address){
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
     if(Row>-1)
     {
      console.log("Update:Row"+Row);
      update(instance,cell, c, Row, {});
     }
    }
    console.log("SEND");
    {{--$.ajax({--}}
    {{--type: "POST",--}}
    {{--url:"{{ route('backend:shop_ja:order:excel:store') }}",--}}
    {{--data:{act:'ship',data:data} ,--}}
    {{--success: function (data) {--}}
    {{--console.log(data);--}}
    {{--if(data && data.length >0){--}}
    {{--console.log("oke");--}}
    {{--let price_ship = parseInt(data[0].data.price_ship);--}}

    {{--let ship_cou = parseInt(data[0].data.ship_cou);--}}

    {{--let total_price_buy = parseInt(data[0].data.total_price_buy);--}}

    {{--setInterest(price_ship < 0? 0 : price_ship,ship_cou< 0 ?0:ship_cou,total_price_buy);--}}
    {{--}--}}
    {{--},--}}
    {{--});--}}


    //    if(value.hasOwnProperty('lock') && value.lock.indexOf(columns.order_ship.index)){

    //     } else{
    //         setInterest(parseInt(valueRow[columns.order_ship.index]),parseInt(valueRow[columns.order_ship_cou.index]));
    //     }
   }
   let columns_index = Object.values(columns);

   let _data = InitData(data,config,columns_index,sheetName);
   let change = {col:-1,row:-1};
   let nestedHeaders = [];
   if(locks .hasOwnProperty(sheetName)){
    let _lock  = locks [sheetName];

    let dateNow = stringDate;
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

    onload: function (e) {
     let td  = $(e).find('.jexcel tbody tr').find('td:first-child');
     td.click(function () {
      let parent = $(this).parent().data();

      if(parent.hasOwnProperty('info')){
       let info = parent.info;
       let company = parent.company;



       for(let tab in info){
        let dom = $("#tab_"+tab);
        let table = "<table class=\"table table-bordered\">";
        table+="<tr>";
        // table+='<td>Mã</td>';
        // table+='<td>Phiên</td>';
        table+='<td>{!! z_language('Người lập') !!}</td>';
        table+='<td>{!! z_language("Ho Tên") !!}</td>';
        table+='<td>{!! z_language("Địa chỉ") !!}</td>';
        table+='<td>{!! z_language("Tỉnh") !!}</td>';
        table+='<td>{!! z_language("Ngày tạo") !!}</td>';
        table+='<td>{!! z_language("Số điện thoại") !!}</td>';
        table+='<td>{!! z_language("Công ty") !!}</td>';
        table+='<td>{!! z_language("Sản phẩm") !!}</td>';

        table+='<td>{!! z_language("Trạng thái") !!}</td>';
        table+='<td>{!! z_language("Xuất") !!}</td>';
        table+='<td>{!! z_language("Đường dẫn") !!}</td>';
        table+="</tr>";
        for(let pos in info){
         for(let com in info[pos]){
          if(com!=company) continue;
          for(let index in info[pos][com]){
           let val = info[pos][com][index];
           table+="<tr>";
           table+='<td>'+(info_admin.hasOwnProperty(val.admin_id)?info_admin[val.admin_id].name:"Không xác định")+'</td>';
           table+='<td>'+val.fullname+'</td>';
           table+='<td>'+val.address+'</td>';
           table+='<td>'+val.province+'</td>';
           table+='<td>'+val.order_create_date+'</td>';
           table+='<td>'+val.phone+'</td>';
           table+='<td>'+val.company+'</td>';
           table+= "<td><table class=\"table table-bordered\">";
           table+='<td>'+val.product_title+'</td>';
           table+='<td>'+val.count+'</td>';
           if(val.hasOwnProperty('items')){
            for(let _index in val.items){
             table+="<tr>";
             table+='<td>'+val.items[_index].product_title+'</td>';
             table+='<td>'+val.items[_index].count+'</td>';
             table+="</tr>";
            }
           }
           table+="</table></td>";
           table+='<td>'+(val.public == 1?"Đơn":"Nháp")+'</td>';
           table+='<td>'+(val.export == 1?"Xuất":"Chưa")+'</td>';
           table+='<td>'+val.order_link+'</td>';
           table+="</tr>";
          }
         }
        }

        table+="</table>";
        dom.html(table);
       }
       $('#modal-default').modal('show');
      }
     });
    },
    nestedHeaders:[
     nestedHeaders
    ],
    contextMenu: function(obj, x, y, e) {
     var items = [];
        return items;
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
     change = {col:-1,row:-1};
     var cellName1 = jexcel.getColumnNameFromId([columns_index[x1].index, y1]);
     $('.onselection').hide();
     $("#col-row-review").data({"x":x1,y:y1}).val(cellName1);
     let val = instance.jexcel.getValue(cellName1);

     if(columns_index[x1] && columns_index[x1].type === "dropdown"){
      $("#value-review").hide();
      $html = $("<div>");
      $("#zoe-dropdown-review").empty().show().html($html);

      change = {col:x1,row:y1};

      // jSuites.dropdown($html[0], {
      //     data:columns_index[x1].source,
      //     autocomplete: columns_index[x1].hasOwnProperty('autocomplete'),
      //     width:'100%',
      //     onchange:function (el, a, oldValue, Value) {
      //         if(oldValue != Value){
      //             change = {col:x1,row:y1};
      //             instance.jexcel.setValue(jexcel.getColumnNameFromId([x1, y1]), Value);
      //         }
      //     },
      // }).setValue(val);

     }else if(columns_index[x1].key === "order_info"){

      //NGUYEN V 様 00 日に 0000 円入金済み
      let vals = val.trim().split(' ');
      let a = val.trim()+"";
      let b = ["様","日に","円入金済み"];

      vals=[];
      if(a.length > 0){

       for(let i = 0; i<b.length;i++){
        let results = a.split(b[i]);
        if(results.length == 0) break;
        a = results[1];
        let val = results[0].trim();
        if(val.length > 0){
         vals.push(val);
        }
       }
      }
      $("#info_payment").show();
      let html  = "<table>";
      html+="<tr>";

      html+="<th>";
      html+='<input type="text" class="form-control name" value="'+(vals[0]?vals[0]:"")+'">';
      html+="</th>";

      html+="<th>";
      html+='様';
      html+="</th>";
      html+="<th>";
      html+='<input type="text" class="form-control date" value="'+(vals[1]?vals[1]:"")+'">';
      html+="</th>";

      html+="<th>";
      html+='日に';
      html+="</th>";

      html+="<th>";
      html+='<input type="text" class="form-control price" value="'+(vals[2]?vals[2]:"")+'">';
      html+="</th>";

      html+="<th>";
      html+='円入金済み';
      html+="</th>";

      html+="<td>";
      html+='&nbsp;<button type="button" class="btn">{!! z_language('Lưu') !!}</button>';
      html+="</td>";
      html+="</tr>";
      html+="</table>";
      var dom = $(html);
      dom.find('button').click(function () {
       let th = dom.find('tr th');
       let vals = "";
       console.log(th);
       for(let i = 0; i< th.length ; i++){
        if(i%2===0){
         vals+=" "+$(th[i]).find('input').val().trim();
        }else{
         vals+=" "+$(th[i]).text().trim();
        }
       }
       setTimeout(function () {
        change = {col:x1,row:y1};
        instance.jexcel.setValue(jexcel.getColumnNameFromId([x1, y1]), vals.trim());
       },1000);
      });
      $("#info_payment").html(dom);
     }
     else{
      change = {col:x1,row:y1};
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
      let countAddress = 0;
      for(let k in instance.jexcel.rows){
       let _val  = instance.jexcel.getRowData(k);
       if(value[columns.fullname.index].length > 0 && value[columns.fullname.index].replace(/\s+/g, ' ') === _val[columns.fullname.index].replace(/\s+/g, ' ')){
        count++;
       }
       if(value[columns.address.index].length > 0 && value[columns.address.index].replace(/\s/g, '') === _val[columns.address.index].replace(/\s/g, '')){
        countAddress++;
       }
      }
      instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])).classList.remove('error');
      if(count > 1) instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])).classList.add('error');

      instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.address.index, row])).classList.remove('error');
      if(countAddress > 1) instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.address.index, row])).classList.add('error');

      let vvv = getValuePayMethod(value[columns.payMethod.index]);
      let parent = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.payMethod.index, row]))).parent();
      parent.removeClass('pay-method-oke');
      if(vvv === 2){
       parent.addClass('pay-method-oke');
      }
      parent.removeClass('has_error');
      if(vvv === 2){
       let img = value[columns.image.index];
       let order_info = value[columns.order_info.index];
       let self = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.image.index, row]))).parent();
       if(img.length === 0 || order_info.length === 0){
        self.addClass('has_error');
       }
      }

      let _province = value[columns.province.index]+"".trim();
      let _address = value[columns.address.index]+"".trim();
      let _fullname = value[columns.fullname.index]+"".replace(/\s+/g, ' ').trim();

      $col = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.province.index, row])));

      $col.removeClass('bor-error');
      if(_province.length <= 0){
       $col.addClass('bor-error')
      }
      $col = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.address.index, row])));
      $col.removeClass('bor-error')
      if(_address.length <= 0){
       $col.addClass('bor-error')
      }
      $col = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])));
      $col.removeClass('bor-error');

         $colorder_link = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.order_link.index, row])));
         $colorder_link.removeClass('bor-error');

      if(_fullname.length <= 0){
       $col.addClass('bor-error')
      }else{
          let order_link = value[columns.order_link.index]+"".trim();
          if(order_link.length === 0){
              $colorder_link.addClass('bor-error');
              if(!parent.hasClass('has_error')){
                  parent.addClass('has_error');
              }
          }
      }
      parent.removeClass('has_export');
      let _export = value[columns.export.index];
      if((_export+"").toString().length > 0){
       if(_export){
        parent.addClass('has_export');
       }
      }
     }
    },
    onchange:function(instance, cell, c, r, value) {
     c = parseInt(c);
     console.dir(JSON.stringify([change,value]));
     if (c === columns.product_name.index) {
      if(dropdown[value] && dropdown[value].hasOwnProperty('data')){

       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.product_id.index, r]), dropdown[value].data.id);

       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price.index, r]),dropdown[value].data.price);
       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price_buy.index, r]),dropdown[value].data.price_buy);
       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.count.index, r]),1);
       if(change.col === c){
        change = {col:-1,row:-1};
        update(instance, cell, c, r,{
         count:1,
         id:dropdown[value].data.id
        });
       }
      }
     }else if(c === columns.count.index || columns.zipcode.index || c === columns.price_buy_sale.index ||
             c === columns.order_ship.index || c === columns.order_ship_cou.index || c === columns.one_address.index){
      if(change.col == c){
       if(c === columns.zipcode.index){
        change = {col:columns.province.index,row:r};
       }else{
        change.col =  {col:-1,row:-1};
        update(instance, cell, c, r,{

        });
       }
      }
     }else if(c === columns.province.index){
      if(change.col == c){
       change = {col:-1,row:-1};
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
       change = {col:-1,row:-1};
       update(instance, cell, c, r,{});
      }
     }
    },
   };
  }
  function YAMADA(sheetName) {

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

   let dropdown = dataproduct.hasOwnProperty(sheetName)?dataproduct[sheetName]:{};
   let index = 0;

   let columns = {
    status: {
     type: 'checkbox',
     title:'{!! z_language('Trạng thái') !!}'
    },
    image:{
     title:'{!! z_language('Ảnh') !!}',
     type:'image',
     width:"50px",
     hide:true,
    },
       order_info:{
           title: '{!! z_language("Thông tin chuyển khoản") !!}',//T Thông tin chuyển khoản
           type: 'text',
           width:'100px',
           key:"demo",
       },
    timeCreate:{
     title: '{!! z_language("Ngày đặt hàng") !!}',//A ngày đặt hàng
     type: 'calendar',
     width:'90px',
     options: { format:'DD/MM/YYYY' },
     value:['date','now']
    },
    payMethod:{
     title: '{!! z_language("Phương thức thanh toán") !!}',//B Phương thức thanh toán
     type:'dropdown',
     source:[
      "代金引換",
      "銀行振込",
      "決済不要",
     ],
     width:'90px',
     value:['product','this','source',0],
    },
    phone:{
     title: '{!! z_language("Số điện thoại") !!}',//C Số điện thoại
     type: 'text',
     width:'90px',
     value:"070-8409-5968",
    },
    zipcode:{
     title: '{!! z_language("Mã bưu điện") !!}',//D Mã bưu điện
     type: 'text',
     width:'60px',
     key:"demo",
    },
    province:{
     title: '{!! z_language("Tỉnh/TP") !!}',//E Tỉnh/TP
     type: 'text',
     width:'150px',
     key:"demo",
    },
    address:{
     title: '{!! z_language("Địa chỉ giao hàng") !!}',//F Địa chỉ giao hàng
     type: 'text',
     width:'150px',
     key:"demo",
    },
    fullname:{
     title: '{!! z_language("Họ tên người nhận") !!}',//G Họ tên người nhận
     type: 'text',
     width:'150px',
     key:"demo",
    },
       order_link:{
           title: '{!! z_language("Đường dẫn") !!}',//T Thông tin chuyển khoản
           type: 'text',
           width:'100px',
       },
    product_id:{
     title: '{!! z_language("Mã SP") !!}',//H Mã SP
     type: 'text',
     width:'50px',
     read:true,
     value:['product','product_name','source',0,'id'],
    },
    product_name:{
     title: '{!! z_language("Tên SP") !!}',//I Tên SP
     type:'dropdown',
     source:Object.values(dropdown),
     autocomplete:true,
     width:'140px',
     value:['product','this','source',0,'id']
    },
    count:{
     title: '{!! z_language("SL") !!}',//I Tên SP
     type: 'numeric',
     width:'50px',
     value:1
    },
    price:{
     title: '{!! z_language("Giá nhập") !!}',//J Giá nhập
     type: 'numeric',
     width:'70px',
     value:['product','product_name','source',0,'data','price'],
    },
    price_buy:{
     title: '{!! z_language("Giá bán") !!}',//J Giá nhập
     type: 'numeric',
     width:'70px',
     value:['product','product_name','source',0,'data','price_buy'],
    },
    order_date:{
     title: '{!! z_language("Ngày nhận") !!}',//L Ngày nhận
     type:'calendar',
     options: { format:'DD/MM/YYYY'},
     value:['date','nowSum',2],
     width:'80px',

    },
    order_hours:{
     title: '{!! z_language("Giờ nhận") !!}',//M Giờ nhận
     type: 'dropdown',
     source:['8:00 ~ 12:00','14:00～16:00','16:00～18:00','18:00～20:00','19:00～21:00'],
     value:['product','this','source',4],
     width:'100px',
    },
    order_ship:{
     title: '{!! z_language("Phí ship") !!}',//N Phí ship
     type: 'numeric',
     width:'80px',
     value:0
    },
    order_total_price:{
     title: '{!! z_language("Tổng giá nhập") !!}',//O Tổng giá nhập
     type: 'numeric',
     width:'100px',
     value:['product','product_name','source',0,'data','price'],
    },
    price_buy_sale:{
     title: '{!! z_language("Tăng Giảm") !!}',//J Giá nhập
     type: 'numeric',
     width:'80px',
     value:0,
    },
    order_total_price_buy:{
     title: '{!! z_language("Total Bán") !!}',//P Giá bán
     type: 'numeric',
     width:'100px',
     value:['product','product_name','source',0,'data','price_buy'],
    },
    order_ship_cou:{
     title: '{!! z_language("Phí giao hàng") !!}',//P Phí giao hàng
     type: 'numeric',
     width:'100px',
     value:0
    },
    order_price:{
     title: '{!! z_language("Lợi nhuận") !!}',//P Lợi nhuận
     type: 'numeric',
     width:'100px',
     value:0
    },

    order_rate:{
     title: '{!! z_language("Lợi nhuận CTV") !!}',//P
     type: 'numeric',
     width:'80px',
     value:0
    },
    order_tracking:{
     title: '{!! z_language("Mã tracking") !!}',//T Mã tracking
     type: 'text',
     width:'100px',
     key:"demo",
    },


    one_address: {
     type: 'checkbox',
     title:'{!! z_language("Cùng địa chỉ") !!}',
     width:'30px',
    },
    id:{
     title: '{!! z_language("ID") !!}ID',//T
     type: 'text',
     width:'100px',
    },
    session_id:{
     title: '{!! z_language("SessionId") !!}',//T
     type: 'text',
     width:'1px',
    },
    export:{
     title: '{!! z_language("Xuất") !!}',//T
     type: 'checkbox',
     width:'50px',
    },
    token:{
     title: '{!! z_language("Token") !!}',//T
     type: 'text',
     width:'1px',
    },
    admin:{
     title: '{!! z_language("Admin") !!}',//T
     type: 'text',
     width:'100px',
    },
    group:{
     title: '{!! z_language("group") !!}',//T
     type: 'text',
     width:'1px',
    },
    comment:{
     title: '{!! z_language("Ghi chú") !!}',//T
     type: 'text',
     width:'100px',
    },
       order_product_link:{
           title: '{!! z_language("Link SP") !!}',//T
           type: 'text',
           width:'100px',
       },
   };
   columnsAll[sheetName] = columns;

   let hide = hideprototy.hasOwnProperty(sheetName)?hideprototy[sheetName]:{};

   let _option = options.hasOwnProperty(sheetName)?options[sheetName]:{};
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
    if(hide.hasOwnProperty(i)){
     columns[i].width =hide[i]+"px";
    }else if(_option.hasOwnProperty('colums')){
     if(_option.colums.hasOwnProperty(i)){
      columns[i].width = "1px";
     }
    }else if(columnsWidth.hasOwnProperty(i)){
     columns[i].width =columnsWidth[i].width;
    }

   }
   function update(instance, cell, c, r, value) {
    console.log("update call");
    r = parseInt(r);
    let data = {
     count:value.hasOwnProperty('count')?value.count:parseInt(instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.count.index, r]))),
     id:value.hasOwnProperty('id')?value.id:instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.product_id.index, r])),
     province:value.hasOwnProperty('province')?value.province:instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.province.index, r])),
    };
    data.province = data.province.trim();
    let total_price_buy =  0;
    let total_price =  0;

    let valueRow =  instance.jexcel.getRowData(r);
    let payMethod = getValuePayMethod(valueRow[columns.payMethod.index]);
    data.payMethod = payMethod;
    data.sheetName = sheetName;
    console.log("payMethod:"+payMethod);
    let price_buy_sale = parseInt(valueRow[columns.price_buy_sale.index]);
    console.log("price_buy_sale:"+price_buy_sale);
    let total_price_buy_all = 0;
    let Row = -1;
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

     let token = parseInt(valueRow[columns.token.index]);
     console.log("valueRow[columns.one_address.index]:"+valueRow[columns.one_address.index]);
     console.log("token:"+token);
     if(!valueRow[columns.one_address.index]){
      total_price_buy_all = total_price_buy;
      for(let iii = r+1;iii<10;iii++){
       let _data = instance.jexcel.getRowData(iii);
       if(_data && _data[columns.one_address.index]){
        console.log(_data);
        let total = parseInt(_data[columns.order_total_price_buy.index]);
        total_price_buy_all+= isNaN(total)?0:total;
       }else{
        break
       }
      }
      console.log(11111111111111);
      if(!isNaN(token)){
       Row = token;
      }
      console.log("Row:"+Row);
      token = "";
     }else{
      for(let iii = r-1;iii>=0;iii--){
       let _data = instance.jexcel.getRowData(iii);
       console.log(data);
       if(_data && !_data[columns.one_address.index]){
        token = iii;
        Row = token;
        break
       }
      }
     }
     console.log("token:"+token);
     console.log("Row:"+Row);
     data.total_price_buy = total_price_buy;

     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.token.index, r]), token);

     console.log(total_price_buy_all);

     let confShipCou = GetShip(product.data,product.data.category_id,data.count,data.province,data.total_price_buy, data.payMethod,total_price_buy_all);

     console.log(confShipCou);

     setInterest(confShipCou.order_ship,confShipCou.order_ship_cou,confShipCou.total_price_buy);
    }

    function GetShip($product,$category_id,$count,$province,$total_price_buy,payMethod,total_price_buy_all) {

     let configShip = dataship.hasOwnProperty("cate_"+$category_id)?dataship["cate_"+$category_id]:[];
     if(total_price_buy_all == 0){
      total_price_buy_all = $total_price_buy;
     }
     console.log(configShip);
     console.log("$count:"+$count);
     console.log("$province:"+$province);
     console.log("$total_price_buy:"+$total_price_buy);

     let $ship_cou = -1;

     if(sheetName === "YAMADA"){
      if(payMethod === 1){
       if(valueRow[columns.one_address.index]){
        $ship_cou = 0;
       }else{
        $total_price_buy =  $total_price_buy + 330;
        total_price_buy_all =  total_price_buy_all + 330;
       }
      }else{
       $ship_cou = 0;
      }
     }else  if(sheetName === "OHGA"){
      if(payMethod === 1){
       if(valueRow[columns.one_address.index]){
        $ship_cou = 0;
       }else{
        $total_price_buy =  $total_price_buy + 330;
        $ship_cou = 330;
       }

      }else{
       $ship_cou = 0;
      }
     }else  if(sheetName === "FUKUI"){
      if(payMethod === 1){
       if(valueRow[columns.one_address.index]){
        $ship_cou = 0;
       }else{
        $total_price_buy =  $total_price_buy + 330;
        $ship_cou = 330;
       }
      }else{
       $ship_cou = 0;
      }
     }else if(sheetName === "KURICHIKU"){
      if(payMethod === 1){
       if(valueRow[columns.one_address.index]){
        $ship_cou = 0;
       }else{
        $total_price_buy =  $total_price_buy + 330;
        $ship_cou = 330;
       }
      }else{
       $ship_cou = 0;
      }
     }
     let arr_ship = [];
     for(let i in configShip){
      $is_IF_Start = IF_Start($count,configShip[i]);
      $is_IF_End =  IF_End($count,configShip[i]);
      if($is_IF_Start && $is_IF_End){
       $conf  =  configShip[i].config;
       for (let ii in $conf){
        $val = $conf[ii];
        $arr = $val['text'].split("-");
        for (let iii in $arr){
         $v = $arr[iii];
         if($province == $v){
          arr_ship.push([configShip[i],$val])
         }
        }
       }
      }
     }
     console.log(arr_ship);
     let $price_ship_default  = -1;
     let $price_ship  = -1;

     for (i in arr_ship){
      let $val = arr_ship[i];
      if($val[0].unit == 0 && $price_ship_default==-1){
       $price_ship_default =  $val[1]['value'];
      }else if($val[0].unit == $product.unit && $price_ship == -1){
       $price_ship = $val[1]['value'];
      }
     }

     console.log('$price_ship_default:'+$price_ship_default);
     console.log('$price_ship:'+$price_ship);

     let $ship = categorys[$category_id]?( categorys[$category_id].data.hasOwnProperty('ship'))?categorys[$category_id].data.ship:"-1":"-1";

     if( payMethod == 2 || payMethod == 3 ){
      $ship_cou = 0;
     }else{
      for (let i in datadaibiki){
       let $_val  = datadaibiki[i];
       if($ship == $_val.id){
        for(let ii in $_val.data){
         $units = $_val.data[ii];
         for(let iii in $units){
          $_unit = $units[iii];
          if($_unit){
           $is_IF_Start = IF_Start(total_price_buy_all,$_unit);
           $is_IF_End = IF_End(total_price_buy_all,$_unit);
           if($is_IF_Start && $is_IF_End){
            $ship_cou = $_unit.value;
           }
          }
         }
        }
       }
       if($ship_cou != -1){
        break;
       }
      }
     }

     let price_ship =  $price_ship!=-1?$price_ship:$price_ship_default;
     $ship_cou = $ship_cou == -1?0:$ship_cou;
     return {order_ship:parseInt(price_ship === -1?0:price_ship),order_ship_cou:parseInt($ship_cou),total_price_buy:$total_price_buy,total_price_buy_all:total_price_buy_all};
    }

    function setInterest(price_ship,order_ship_cou,total_price_buy){
     price_ship = price_ship * data.count;
     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship.index, r]),price_ship);
     total_price_buy = total_price_buy + price_ship;
     if(total_price_buy ===0 || total_price == 0){ return;}
     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]), total_price_buy,false );
     let one_address = instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.one_address.index, r]));

     // if(one_address){
     //     //payMethod = 2;
     // }



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
      if(one_address){

       let a = (parseInt(total_price_buy) - parseInt(total_price) - parseInt(price_ship));

       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]),a,false);
       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]),0,false);
      }else{
       let a = (parseInt(total_price_buy) - parseInt(total_price) - parseInt(price_ship) - order_ship_cou);
       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]),a,false);
       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]),order_ship_cou,false);
      }
     }
     if(Row>-1)
     {
      console.log("Update:Row"+Row);
      update(instance,cell, c, Row, {});
     }
    }
    console.log("SEND");
    console.log(data);
    {{--$.ajax({--}}
    {{--type: "POST",--}}
    {{--url:"{{ route('backend:shop_ja:order:excel:store') }}",--}}
    {{--data:{act:'ship',data:data} ,--}}
    {{--success: function (data) {--}}
    {{--console.log(data);--}}
    {{--if(data && data.length >0){--}}
    {{--let price_ship = parseInt(data[0].data.price_ship)--}}
    {{--let ship_cou = parseInt(data[0].data.ship_cou);--}}
    {{--let total_price_buy = parseInt(data[0].data.total_price_buy);--}}
    {{--setInterest(price_ship < 0? 0 : price_ship,ship_cou< 0 ?0:ship_cou,total_price_buy);--}}

    {{--}--}}
    {{--},--}}
    {{--});--}}
   }
   let columns_index = Object.values(columns);

   let _data = InitData(data,config,columns_index,sheetName);
   let change = {col:-1,row:-1};

   let nestedHeaders = [];
   if(locks.hasOwnProperty(sheetName)){
    let _lock  = locks [sheetName];
    let dateNow = stringDate;
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
   if(nestedHeaders.length == 0){
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
    onload: function (e) {
     let td  = $(e).find('.jexcel tbody tr').find('td:first-child');
     td.click(function () {
      let parent = $(this).parent().data();

      if(parent.hasOwnProperty('info')){
       let info = parent.info;
       let company = parent.company;



       for(let tab in info){
        let dom = $("#tab_"+tab);
        let table = "<table class=\"table table-bordered\">";
        table+="<tr>";
        // table+='<td>Mã</td>';
        // table+='<td>Phiên</td>';
        table+='<td>{!! z_language('Người lập') !!}</td>';
        table+='<td>{!! z_language("Ho Tên") !!}</td>';
        table+='<td>{!! z_language("Địa chỉ") !!}</td>';
        table+='<td>{!! z_language("Tỉnh") !!}</td>';
        table+='<td>{!! z_language("Ngày tạo") !!}</td>';
        table+='<td>{!! z_language("Số điện thoại") !!}</td>';
        table+='<td>{!! z_language("Công ty") !!}</td>';
        table+='<td>{!! z_language("Sản phẩm") !!}</td>';

        table+='<td>{!! z_language("Trạng thái") !!}</td>';
        table+='<td>{!! z_language("Xuất") !!}</td>';
        table+='<td>{!! z_language("Đường dẫn") !!}</td>';
        table+="</tr>";
        for(let pos in info){
         for(let com in info[pos]){
          if(com!=company) continue;
          for(let index in info[pos][com]){
           let val = info[pos][com][index];
           table+="<tr>";
           table+='<td>'+(info_admin.hasOwnProperty(val.admin_id)?info_admin[val.admin_id].name:"Không xác định")+'</td>';
           table+='<td>'+val.fullname+'</td>';
           table+='<td>'+val.address+'</td>';
           table+='<td>'+val.province+'</td>';
           table+='<td>'+val.order_create_date+'</td>';
           table+='<td>'+val.phone+'</td>';
           table+='<td>'+val.company+'</td>';
           table+= "<td><table class=\"table table-bordered\">";
           table+='<td>'+val.product_title+'</td>';
           table+='<td>'+val.count+'</td>';
           if(val.hasOwnProperty('items')){
            for(let _index in val.items){
             table+="<tr>";
             table+='<td>'+val.items[_index].product_title+'</td>';
             table+='<td>'+val.items[_index].count+'</td>';
             table+="</tr>";
            }
           }
           table+="</table></td>";
           table+='<td>'+(val.public == 1?"Đơn":"Nháp")+'</td>';
           table+='<td>'+(val.export == 1?"Xuất":"Chưa")+'</td>';
           table+='<td>'+val.order_link+'</td>';
           table+="</tr>";
          }
         }
        }

        table+="</table>";
        dom.html(table);
       }
       $('#modal-default').modal('show');
      }
     });
    },
    nestedHeaders:[
     nestedHeaders
    ],
    contextMenu: function(obj, x, y, e) {
     var items = [];  return items;
     return items;
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

     var cellName1 = jexcel.getColumnNameFromId([columns_index[x1].index, y1]);

     $("#col-row-review").data({"x":x1,y:y1}).val(cellName1);
     let val = instance.jexcel.getValue(cellName1);

     $('.onselection').hide();
     if(columns_index[x1] && columns_index[x1].type === "dropdown"){
      $("#value-review").hide();
      $html = $("<div>");
      $("#zoe-dropdown-review").show().html($html);
      change = {col:x1,row:y1};
      // jSuites.dropdown($html[0], {
      //     data:columns_index[x1].source,
      //     autocomplete: columns_index[x1].hasOwnProperty('autocomplete'),
      //     width:'100%',
      //     onchange:function (el, a, oldValue, Value) {
      //
      //         instance.jexcel.setValue(jexcel.getColumnNameFromId([x1, y1]), Value);
      //     },
      // }).setValue(val);

     }else if(columns_index[x1].key === "order_info"){

      //依頼人名. NGUYEN V 様  00 日に 0000 円入金済み

      let vals = val.trim().split(' ');
      let a = val.trim()+"";
      let b = ["依頼人名.","様","日に","円入金済み"];

      vals=[];
      if(a.length > 0){

       for(let i = 0; i<b.length;i++){
        let results = a.split(b[i]);
        if(results.length == 0) break;
        a = results[1];
        let val = results[0].trim();
        if(val.length > 0){
         vals.push(val);
        }
       }
      }
      $("#info_payment").show();
      let html  = "<table>";
      html+="<tr>";
      html+="<th>";
      html+='依頼人名.';
      html+="</th>";
      html+="<th>";
      html+='<input type="text" class="form-control name" value="'+(vals[0]?vals[0]:"")+'">';
      html+="</th>";
      html+="<th>";
      html+='様';
      html+="</th>";
      html+="<th>";
      html+='<input type="text" class="form-control date" value="'+(vals[1]?vals[1]:"")+'">';
      html+="</th>";
      html+="<th>";
      html+='日に';
      html+="</th>";
      html+="<th>";
      html+='<input type="text" class="form-control price" value="'+(vals[2]?vals[2]:"")+'">';
      html+="</th>";
      html+="<th>";
      html+='円入金済み';
      html+="</th>";
      html+="<td>";
      html+='&nbsp;<button type="button" class="btn btn-xs">{!! z_language('Lưu') !!}</button>';
      html+="</td>";
      html+="</tr>";
      html+="</table>";
      var dom = $(html);
      dom.find('button').click(function () {
       let th = dom.find('tr th');
       let vals = "";
       console.log(th);
       for(let i = 0; i< th.length ; i++){
        if(i%2===1){
         vals+=" "+$(th[i]).find('input').val().trim();
        }else{
         vals+=" "+$(th[i]).text().trim();
        }
       }
       setTimeout(function () {
        change = {col:x1,row:y1};
        instance.jexcel.setValue(jexcel.getColumnNameFromId([x1, y1]), vals);
       },1000);
      });
      $("#info_payment").html(dom);

     }
     else{
      change = {col:x1,row:y1};
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
      let countAddress = 0;
      for(let k in instance.jexcel.rows){
       let _val  = instance.jexcel.getRowData(k);
       if(value[columns.fullname.index].length > 0 && value[columns.fullname.index].replace(/\s+/g, ' ') === _val[columns.fullname.index].replace(/\s+/g, ' ')){
        count++;
       }
       if(value[columns.address.index].length > 0 && value[columns.address.index].replace(/\s/g, '') === _val[columns.address.index].replace(/\s/g, '')){
        countAddress++;
       }
      }
      instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])).classList.remove('error');
      if(count > 1) instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])).classList.add('error');

      instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.address.index, row])).classList.remove('error');
      if(countAddress > 1) instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.address.index, row])).classList.add('error');
      let vvv = getValuePayMethod(value[columns.payMethod.index]);

      let parent = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.payMethod.index, row]))).parent();
      parent.removeClass('pay-method-oke');
      if(vvv === 2){
       parent.addClass('pay-method-oke');
      }
      parent.removeClass('has_error');
      if(vvv === 2){
       let img = value[columns.image.index];
       let order_info = value[columns.order_info.index];
       let self = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.image.index, row]))).parent();
       if(img.length === 0 || order_info.length === 0){
        self.addClass('has_error');
       }
      }

      let _product_id = value[columns.product_id.index];

      //if(parseInt(_product_id)>0){

      let _province = value[columns.province.index]+"".trim();
      let _address = value[columns.address.index]+"".replace(/\s/g, '').trim();
      let _fullname = value[columns.fullname.index]+"".replace(/\s+/g, ' ').trim();

      $col = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.province.index, row])));

      $col.removeClass('bor-error');
      if(_province.length <= 0){
       $col.addClass('bor-error')
      }
      $col = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.address.index, row])));
      $col.removeClass('bor-error')
      if(_address.length <= 0){
       $col.addClass('bor-error')
      }
      $col = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])));
      $col.removeClass('bor-error');
         $colorder_link = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.order_link.index, row])));
         $colorder_link.removeClass('bor-error');

      if(_fullname.length <= 0){
       $col.addClass('bor-error')
      }else{
          let order_link = value[columns.order_link.index]+"".trim();
          if(order_link.length === 0){
              $colorder_link.addClass('bor-error');
              if(!parent.hasClass('has_error')){
                  parent.addClass('has_error');
              }
          }
      }
      // }

      parent.removeClass('has_export');
      let _export = value[columns.export.index];
      if((_export+"").toString().length > 0){
       if(_export){
        parent.addClass('has_export');
       }
      }
      change = {x:-1,y:-1};
     }
    },
    onchange:function(instance, cell, c, r, value) {
     c = parseInt(c);
     console.log(change);
     if (c === columns.product_name.index) {
      if(dropdown[value] && dropdown[value].hasOwnProperty('data')){

       if(change.col == c){
           change = {col:-1,row:-1};
           instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.product_id.index, r]), dropdown[value].data.id);
           instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price.index, r]),dropdown[value].data.price);
           instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price_buy.index, r]),dropdown[value].data.price_buy);

          // instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.count.index, r]), instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.count.index, r])));

           update(instance, cell, c, r,{

            id:dropdown[value].data.id
           });
       }
      }
     }else if(c === columns.count.index || c === columns.zipcode.index || c === columns.price_buy_sale.index ||
             c === columns.order_ship.index || c === columns.order_ship_cou.index || c === columns.one_address.index){
      if(change.col == c){
       if(c === columns.zipcode.index){
        change = {col:columns.province.index,row:r};
       }else{
        change = {col:-1,row:-1};
        update(instance, cell, c, r,{

        });
       }
      }
     }else if(c === columns.province.index){
      if(change.col == c){
       change = {col:-1,row:-1};
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
       change = {col:-1,row:-1};
       update(instance, cell, c, r,{});
      }
     }
    },
   };
  }

  function MISHIMA(sheetName) {

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

   let dropdown = dataproduct.hasOwnProperty(sheetName)?dataproduct[sheetName]:{};
   let index = 0;

   let columns = {
    status: {
     type: 'checkbox',
     title:'{!! z_language('Trạng thái') !!}'
    },
    image:{
     title:'{!! z_language('Ảnh') !!}',
     type:'image',
     width:"50px",
     hide:true,
    },
    order_info:{
     title: '{!! z_language("Thông tin chuyển khoản") !!}',//T Thông tin chuyển khoản
     type: 'text',
     width:'100px',
     key:"demo",
    },
    timeCreate:{
     title: '{!! z_language("Ngày đặt hàng") !!}',//A ngày đặt hàng
     type: 'calendar',
     width:'90px',
     options: { format:'DD/MM/YYYY' },
     value:['date','now']
    },
    payMethod:{
     title: '{!! z_language("Phương thức thanh toán") !!}',//B Phương thức thanh toán
     type:'dropdown',
     source:[
      "代金引換",
      "銀行振込",
      "決済不要",
     ],
     width:'90px',
     value:['product','this','source',0],
    },
    phone:{
     title: '{!! z_language("Số điện thoại") !!}',//C Số điện thoại
     type: 'text',
     width:'90px',
     value:"070-8409-5968",
    },
    zipcode:{
     title: '{!! z_language("Mã bưu điện") !!}',//D Mã bưu điện
     type: 'text',
     width:'60px',
     key:"demo",
    },
    province:{
     title: '{!! z_language("Tỉnh/TP") !!}',//E Tỉnh/TP
     type: 'text',
     width:'150px',
     key:"demo",
    },
    address:{
     title: '{!! z_language("Địa chỉ giao hàng") !!}',//F Địa chỉ giao hàng
     type: 'text',
     width:'150px',
     key:"demo",
    },
    fullname:{
     title: '{!! z_language("Họ tên người nhận") !!}',//G Họ tên người nhận
     type: 'text',
     width:'150px',
     key:"demo",
    },
    order_link:{
     title: '{!! z_language("Đường dẫn") !!}',//T Thông tin chuyển khoản
     type: 'text',
     width:'100px',
    },
    product_id:{
     title: '{!! z_language("Mã SP") !!}',//H Mã SP
     type: 'text',
     width:'50px',
     read:true,
     value:['product','product_name','source',0,'id'],
    },
    product_name:{
     title: '{!! z_language("Tên SP") !!}',//I Tên SP
     type:'dropdown',
     source:Object.values(dropdown),
     autocomplete:true,
     width:'140px',
     value:['product','this','source',0,'id']
    },
    count:{
     title: '{!! z_language("SL") !!}',//I Tên SP
     type: 'numeric',
     width:'50px',
     value:1
    },
    price:{
     title: '{!! z_language("Giá nhập") !!}',//J Giá nhập
     type: 'numeric',
     width:'70px',
     value:['product','product_name','source',0,'data','price'],
    },
    price_buy:{
     title: '{!! z_language("Giá bán") !!}',//J Giá nhập
     type: 'numeric',
     width:'70px',
     value:['product','product_name','source',0,'data','price_buy'],
    },
    order_date:{
     title: '{!! z_language("Ngày nhận") !!}',//L Ngày nhận
     type:'calendar',
     options: { format:'DD/MM/YYYY'},
     value:['date','nowSum',2],
     width:'80px',

    },
    order_hours:{
     title: '{!! z_language("Giờ nhận") !!}',//M Giờ nhận
     type: 'dropdown',
     source:['8:00 ~ 12:00','14:00～16:00','16:00～18:00','18:00～20:00','19:00～21:00'],
     value:['product','this','source',4],
     width:'100px',
    },
    order_ship:{
     title: '{!! z_language("Phí ship") !!}',//N Phí ship
     type: 'numeric',
     width:'80px',
     value:0
    },
    order_total_price:{
     title: '{!! z_language("Tổng giá nhập") !!}',//O Tổng giá nhập
     type: 'numeric',
     width:'100px',
     value:['product','product_name','source',0,'data','price'],
    },
    price_buy_sale:{
     title: '{!! z_language("Tăng Giảm") !!}',//J Giá nhập
     type: 'numeric',
     width:'80px',
     value:0,
    },
    order_total_price_buy:{
     title: '{!! z_language("Total Bán") !!}',//P Giá bán
     type: 'numeric',
     width:'100px',
     value:['product','product_name','source',0,'data','price_buy'],
    },
    order_ship_cou:{
     title: '{!! z_language("Phí giao hàng") !!}',//P Phí giao hàng
     type: 'numeric',
     width:'100px',
     value:0
    },
    order_price:{
     title: '{!! z_language("Lợi nhuận") !!}',//P Lợi nhuận
     type: 'numeric',
     width:'100px',
     value:0
    },

    order_rate:{
     title: '{!! z_language("Lợi nhuận CTV") !!}',//P
     type: 'numeric',
     width:'80px',
     value:0
    },
    order_tracking:{
     title: '{!! z_language("Mã tracking") !!}',//T Mã tracking
     type: 'text',
     width:'100px',
     key:"demo",
    },


    one_address: {
     type: 'checkbox',
     title:'{!! z_language("Cùng địa chỉ") !!}',
     width:'30px',
    },
    id:{
     title: '{!! z_language("ID") !!}ID',//T
     type: 'text',
     width:'100px',
    },
    session_id:{
     title: '{!! z_language("SessionId") !!}',//T
     type: 'text',
     width:'1px',
    },
    export:{
     title: '{!! z_language("Xuất") !!}',//T
     type: 'checkbox',
     width:'50px',
    },
    token:{
     title: '{!! z_language("Token") !!}',//T
     type: 'text',
     width:'1px',
    },
    admin:{
     title: '{!! z_language("Admin") !!}',//T
     type: 'text',
     width:'100px',
    },
    group:{
     title: '{!! z_language("group") !!}',//T
     type: 'text',
     width:'1px',
    },
    comment:{
     title: '{!! z_language("Ghi chú") !!}',//T
     type: 'text',
     width:'100px',
    },
       order_product_link:{
           title: '{!! z_language("Link SP") !!}',//T
           type: 'text',
           width:'100px',
       },
   };
   columnsAll[sheetName] = columns;

   let hide = hideprototy.hasOwnProperty(sheetName)?hideprototy[sheetName]:{};

   let _option = options.hasOwnProperty(sheetName)?options[sheetName]:{};
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
    if(hide.hasOwnProperty(i)){
     columns[i].width =hide[i]+"px";
    }else if(_option.hasOwnProperty('colums')){
     if(_option.colums.hasOwnProperty(i)){
      columns[i].width = "1px";
     }
    }else if(columnsWidth.hasOwnProperty(i)){
     columns[i].width =columnsWidth[i].width;
    }

   }
   function update(instance, cell, c, r, value) {
    console.log("update call");
    r = parseInt(r);
    let data = {
     count:value.hasOwnProperty('count')?value.count:parseInt(instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.count.index, r]))),
     id:value.hasOwnProperty('id')?value.id:instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.product_id.index, r])),
     province:value.hasOwnProperty('province')?value.province:instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.province.index, r])),
    };
    data.province = data.province.trim();
    let total_price_buy =  0;
    let total_price =  0;

    let valueRow =  instance.jexcel.getRowData(r);
    let payMethod = getValuePayMethod(valueRow[columns.payMethod.index]);
    data.payMethod = payMethod;
    data.sheetName = sheetName;
    console.log("payMethod:"+payMethod);
    let price_buy_sale = parseInt(valueRow[columns.price_buy_sale.index]);
    console.log("price_buy_sale:"+price_buy_sale);
    let total_price_buy_all = 0;
    let Row = -1;
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

     let token = parseInt(valueRow[columns.token.index]);
     console.log("valueRow[columns.one_address.index]:"+valueRow[columns.one_address.index]);
     console.log("token:"+token);
     if(!valueRow[columns.one_address.index]){
      total_price_buy_all = total_price_buy;
      var two = 0;
      for(let iii = r+1;iii<10;iii++){
       let _data = instance.jexcel.getRowData(iii);
       if(_data && _data[columns.one_address.index]){
        console.log(_data);
        let total = parseInt(_data[columns.order_total_price_buy.index]);
        total_price_buy_all+= isNaN(total)?0:total;
        two++;
       }else{
        break
       }
      }
      console.log(11111111111111);
      if(!isNaN(token)){
       Row = token;
      }
      if(sheetName =="MISHIMA" && two > 0){
        total_price_buy_all-=330*two;
      }
      console.log("Row:"+Row);
      token = "";
     }else{
      for(let iii = r-1;iii>=0;iii--){
       let _data = instance.jexcel.getRowData(iii);
       console.log(data);
       if(_data && !_data[columns.one_address.index]){
        token = iii;
        Row = token;
        break
       }
      }
     }
     console.log("token:"+token);
     console.log("Row:"+Row);
     data.total_price_buy = total_price_buy;

     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.token.index, r]), token);

     console.log(total_price_buy_all);

     let confShipCou = GetShip(product.data,product.data.category_id,data.count,data.province,data.total_price_buy, data.payMethod,total_price_buy_all);

     console.log(confShipCou);

     setInterest(confShipCou.order_ship,confShipCou.order_ship_cou,confShipCou.total_price_buy);
    }

    function GetShip($product,$category_id,$count,$province,$total_price_buy,payMethod,total_price_buy_all) {

     let configShip = dataship.hasOwnProperty("cate_"+$category_id)?dataship["cate_"+$category_id]:[];
     if(total_price_buy_all == 0){
      total_price_buy_all = $total_price_buy;
     }
     console.log(configShip);
     console.log("$count:"+$count);
     console.log("$province:"+$province);
     console.log("$total_price_buy:"+$total_price_buy);

     let $ship_cou = -1;

     if(sheetName === "YAMADA"){
      if(payMethod === 1){
       if(valueRow[columns.one_address.index]){
        $ship_cou = 0;
       }else{
        $total_price_buy =  $total_price_buy + 330;
        total_price_buy_all =  total_price_buy_all + 330;
       }
      }else{
       $ship_cou = 0;
      }
     }else  if(sheetName === "OHGA"){
      if(payMethod === 1){
       if(valueRow[columns.one_address.index]){
        $ship_cou = 0;
       }else{
        $total_price_buy =  $total_price_buy + 330;
        $ship_cou = 330;
       }

      }else{
       $ship_cou = 0;
      }
     }else  if(sheetName === "FUKUI"){
      if(payMethod === 1){
       if(valueRow[columns.one_address.index]){
        $ship_cou = 0;
       }else{
        $total_price_buy =  $total_price_buy + 330;
        $ship_cou = 330;
       }
      }else{
       $ship_cou = 0;
      }
     }else if(sheetName === "KURICHIKU"){
      if(payMethod === 1){
       if(valueRow[columns.one_address.index]){
        $ship_cou = 0;
       }else{
        $total_price_buy =  $total_price_buy + 330;
        $ship_cou = 330;
       }
      }else{
       $ship_cou = 0;
      }
     }
     let arr_ship = [];
     for(let i in configShip){
      $is_IF_Start = IF_Start($count,configShip[i]);
      $is_IF_End =  IF_End($count,configShip[i]);
      if($is_IF_Start && $is_IF_End){
       $conf  =  configShip[i].config;
       for (let ii in $conf){
        $val = $conf[ii];
        $arr = $val['text'].split("-");
        for (let iii in $arr){
         $v = $arr[iii];
         if($province == $v){
          arr_ship.push([configShip[i],$val])
         }
        }
       }
      }
     }
     console.log(arr_ship);
     let $price_ship_default  = -1;
     let $price_ship  = -1;

     for (i in arr_ship){
      let $val = arr_ship[i];
      if($val[0].unit == 0 && $price_ship_default==-1){
       $price_ship_default =  $val[1]['value'];
      }else if($val[0].unit == $product.unit && $price_ship == -1){
       $price_ship = $val[1]['value'];
      }
     }

     console.log('$price_ship_default:'+$price_ship_default);
     console.log('$price_ship:'+$price_ship);

     let $ship = categorys[$category_id]?( categorys[$category_id].data.hasOwnProperty('ship'))?categorys[$category_id].data.ship:"-1":"-1";

     if( payMethod == 2 || payMethod == 3 ){
      $ship_cou = 0;
     }else{
      for (let i in datadaibiki){
       let $_val  = datadaibiki[i];
       if($ship == $_val.id){
        for(let ii in $_val.data){
         $units = $_val.data[ii];
         for(let iii in $units){
          $_unit = $units[iii];
          if($_unit){
           $is_IF_Start = IF_Start(total_price_buy_all,$_unit);
           $is_IF_End = IF_End(total_price_buy_all,$_unit);
           if($is_IF_Start && $is_IF_End){
            $ship_cou = $_unit.value;
           }
          }
         }
        }
       }
       if($ship_cou != -1){
        break;
       }
      }
     }

     let price_ship =  $price_ship!=-1?$price_ship:$price_ship_default;
     $ship_cou = $ship_cou == -1?0:$ship_cou;
     return {order_ship:parseInt(price_ship === -1?0:price_ship),order_ship_cou:parseInt($ship_cou),total_price_buy:$total_price_buy,total_price_buy_all:total_price_buy_all};
    }

    function setInterest(price_ship,order_ship_cou,total_price_buy){
     price_ship = price_ship * data.count;
     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship.index, r]),price_ship);
     total_price_buy = total_price_buy + price_ship;
     if(total_price_buy ===0 || total_price == 0){ return;}
     instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]), total_price_buy,false );
     let one_address = instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.one_address.index, r]));

     // if(one_address){
     //     //payMethod = 2;
     // }



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
      if(one_address){

       let a = (parseInt(total_price_buy) - parseInt(total_price) - parseInt(price_ship));

       if(sheetName =="MISHIMA"){
            instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_total_price_buy.index, r]),total_price_buy-330,false);// new
            instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]),a-330,false);
        }else{
            instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]),a,false);
        }


       //instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]),a,false);
       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]),0,false);
      }else{
       let a = (parseInt(total_price_buy) - parseInt(total_price) - parseInt(price_ship) - order_ship_cou);
       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_price.index, r]),a,false);
       instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.order_ship_cou.index, r]),order_ship_cou,false);
      }
     }
     if(Row>-1)
     {
      console.log("Update:Row"+Row);
      update(instance,cell, c, Row, {});
     }
    }
    console.log("SEND");
    console.log(data);
    {{--$.ajax({--}}
    {{--type: "POST",--}}
    {{--url:"{{ route('backend:shop_ja:order:excel:store') }}",--}}
    {{--data:{act:'ship',data:data} ,--}}
    {{--success: function (data) {--}}
    {{--console.log(data);--}}
    {{--if(data && data.length >0){--}}
    {{--let price_ship = parseInt(data[0].data.price_ship)--}}
    {{--let ship_cou = parseInt(data[0].data.ship_cou);--}}
    {{--let total_price_buy = parseInt(data[0].data.total_price_buy);--}}
    {{--setInterest(price_ship < 0? 0 : price_ship,ship_cou< 0 ?0:ship_cou,total_price_buy);--}}

    {{--}--}}
    {{--},--}}
    {{--});--}}
   }
   let columns_index = Object.values(columns);

   let _data = InitData(data,config,columns_index,sheetName);
   let change = {col:-1,row:-1};

   let nestedHeaders = [];
   if(locks.hasOwnProperty(sheetName)){
    let _lock  = locks [sheetName];
    let dateNow = stringDate;
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
   if(nestedHeaders.length == 0){
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
    onload: function (e) {
     let td  = $(e).find('.jexcel tbody tr').find('td:first-child');
     td.click(function () {
      let parent = $(this).parent().data();

      if(parent.hasOwnProperty('info')){
       let info = parent.info;
       let company = parent.company;



       for(let tab in info){
        let dom = $("#tab_"+tab);
        let table = "<table class=\"table table-bordered\">";
        table+="<tr>";
        // table+='<td>Mã</td>';
        // table+='<td>Phiên</td>';
        table+='<td>{!! z_language('Người lập') !!}</td>';
        table+='<td>{!! z_language("Ho Tên") !!}</td>';
        table+='<td>{!! z_language("Địa chỉ") !!}</td>';
        table+='<td>{!! z_language("Tỉnh") !!}</td>';
        table+='<td>{!! z_language("Ngày tạo") !!}</td>';
        table+='<td>{!! z_language("Số điện thoại") !!}</td>';
        table+='<td>{!! z_language("Công ty") !!}</td>';
        table+='<td>{!! z_language("Sản phẩm") !!}</td>';

        table+='<td>{!! z_language("Trạng thái") !!}</td>';
        table+='<td>{!! z_language("Xuất") !!}</td>';
        table+='<td>{!! z_language("Đường dẫn") !!}</td>';
        table+="</tr>";
        for(let pos in info){
         for(let com in info[pos]){
          if(com!=company) continue;
          for(let index in info[pos][com]){
           let val = info[pos][com][index];
           table+="<tr>";
           table+='<td>'+(info_admin.hasOwnProperty(val.admin_id)?info_admin[val.admin_id].name:"Không xác định")+'</td>';
           table+='<td>'+val.fullname+'</td>';
           table+='<td>'+val.address+'</td>';
           table+='<td>'+val.province+'</td>';
           table+='<td>'+val.order_create_date+'</td>';
           table+='<td>'+val.phone+'</td>';
           table+='<td>'+val.company+'</td>';
           table+= "<td><table class=\"table table-bordered\">";
           table+='<td>'+val.product_title+'</td>';
           table+='<td>'+val.count+'</td>';
           if(val.hasOwnProperty('items')){
            for(let _index in val.items){
             table+="<tr>";
             table+='<td>'+val.items[_index].product_title+'</td>';
             table+='<td>'+val.items[_index].count+'</td>';
             table+="</tr>";
            }
           }
           table+="</table></td>";
           table+='<td>'+(val.public == 1?"Đơn":"Nháp")+'</td>';
           table+='<td>'+(val.export == 1?"Xuất":"Chưa")+'</td>';
           table+='<td>'+val.order_link+'</td>';
           table+="</tr>";
          }
         }
        }

        table+="</table>";
        dom.html(table);
       }
       $('#modal-default').modal('show');
      }
     });
    },
    nestedHeaders:[
     nestedHeaders
    ],
    contextMenu: function(obj, x, y, e) {
     var items = [];  return items;
     return items;
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

     var cellName1 = jexcel.getColumnNameFromId([columns_index[x1].index, y1]);

     $("#col-row-review").data({"x":x1,y:y1}).val(cellName1);
     let val = instance.jexcel.getValue(cellName1);

     $('.onselection').hide();
     if(columns_index[x1] && columns_index[x1].type === "dropdown"){
      $("#value-review").hide();
      $html = $("<div>");
      $("#zoe-dropdown-review").show().html($html);
      change = {col:x1,row:y1};
      // jSuites.dropdown($html[0], {
      //     data:columns_index[x1].source,
      //     autocomplete: columns_index[x1].hasOwnProperty('autocomplete'),
      //     width:'100%',
      //     onchange:function (el, a, oldValue, Value) {
      //
      //         instance.jexcel.setValue(jexcel.getColumnNameFromId([x1, y1]), Value);
      //     },
      // }).setValue(val);

     }else if(columns_index[x1].key === "order_info"){

      //依頼人名. NGUYEN V 様  00 日に 0000 円入金済み

      let vals = val.trim().split(' ');
      let a = val.trim()+"";
      let b = ["依頼人名.","様","日に","円入金済み"];

      vals=[];
      if(a.length > 0){

       for(let i = 0; i<b.length;i++){
        let results = a.split(b[i]);
        if(results.length == 0) break;
        a = results[1];
        let val = results[0].trim();
        if(val.length > 0){
         vals.push(val);
        }
       }
      }
      $("#info_payment").show();
      let html  = "<table>";
      html+="<tr>";
      html+="<th>";
      html+='依頼人名.';
      html+="</th>";
      html+="<th>";
      html+='<input type="text" class="form-control name" value="'+(vals[0]?vals[0]:"")+'">';
      html+="</th>";
      html+="<th>";
      html+='様';
      html+="</th>";
      html+="<th>";
      html+='<input type="text" class="form-control date" value="'+(vals[1]?vals[1]:"")+'">';
      html+="</th>";
      html+="<th>";
      html+='日に';
      html+="</th>";
      html+="<th>";
      html+='<input type="text" class="form-control price" value="'+(vals[2]?vals[2]:"")+'">';
      html+="</th>";
      html+="<th>";
      html+='円入金済み';
      html+="</th>";
      html+="<td>";
      html+='&nbsp;<button type="button" class="btn btn-xs">{!! z_language('Lưu') !!}</button>';
      html+="</td>";
      html+="</tr>";
      html+="</table>";
      var dom = $(html);
      dom.find('button').click(function () {
       let th = dom.find('tr th');
       let vals = "";
       console.log(th);
       for(let i = 0; i< th.length ; i++){
        if(i%2===1){
         vals+=" "+$(th[i]).find('input').val().trim();
        }else{
         vals+=" "+$(th[i]).text().trim();
        }
       }
       setTimeout(function () {
        change = {col:x1,row:y1};
        instance.jexcel.setValue(jexcel.getColumnNameFromId([x1, y1]), vals);
       },1000);
      });
      $("#info_payment").html(dom);

     }
     else{
      change = {col:x1,row:y1};
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
      let countAddress = 0;
      for(let k in instance.jexcel.rows){
       let _val  = instance.jexcel.getRowData(k);
       if(value[columns.fullname.index].length > 0 && value[columns.fullname.index].replace(/\s+/g, ' ') === _val[columns.fullname.index].replace(/\s+/g, ' ')){
        count++;
       }
       if(value[columns.address.index].length > 0 && value[columns.address.index].replace(/\s/g, '') === _val[columns.address.index].replace(/\s/g, '')){
        countAddress++;
       }
      }
      instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])).classList.remove('error');
      if(count > 1) instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])).classList.add('error');

      instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.address.index, row])).classList.remove('error');
      if(countAddress > 1) instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.address.index, row])).classList.add('error');
      let vvv = getValuePayMethod(value[columns.payMethod.index]);

      let parent = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.payMethod.index, row]))).parent();
      parent.removeClass('pay-method-oke');
      if(vvv === 2){
       parent.addClass('pay-method-oke');
      }
      parent.removeClass('has_error');
      if(vvv === 2){
       let img = value[columns.image.index];
       let order_info = value[columns.order_info.index];
       let self = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.image.index, row]))).parent();
       if(img.length === 0 || order_info.length === 0){
        self.addClass('has_error');
       }
      }

      let _product_id = value[columns.product_id.index];

      //if(parseInt(_product_id)>0){

      let _province = value[columns.province.index]+"".trim();
      let _address = value[columns.address.index]+"".replace(/\s/g, '').trim();
      let _fullname = value[columns.fullname.index]+"".replace(/\s+/g, ' ').trim();

      $col = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.province.index, row])));

      $col.removeClass('bor-error');
      if(_province.length <= 0){
       $col.addClass('bor-error')
      }
      $col = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.address.index, row])));
      $col.removeClass('bor-error')
      if(_address.length <= 0){
       $col.addClass('bor-error')
      }
      $col = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.fullname.index, row])));
      $col.removeClass('bor-error');
      $colorder_link = $(instance.jexcel.getCell(jexcel.getColumnNameFromId([columns.order_link.index, row])));
      $colorder_link.removeClass('bor-error');

      if(_fullname.length <= 0){
       $col.addClass('bor-error')
      }else{
       let order_link = value[columns.order_link.index]+"".trim();
       if(order_link.length === 0){
        $colorder_link.addClass('bor-error');
        if(!parent.hasClass('has_error')){
         parent.addClass('has_error');
        }
       }
      }
      // }

      parent.removeClass('has_export');
      let _export = value[columns.export.index];
      if((_export+"").toString().length > 0){
       if(_export){
        parent.addClass('has_export');
       }
      }
      change = {x:-1,y:-1};
     }
    },
    onchange:function(instance, cell, c, r, value) {
     c = parseInt(c);
     console.log(change);
     if (c === columns.product_name.index) {
      if(dropdown[value] && dropdown[value].hasOwnProperty('data')){

       if(change.col == c){
        change = {col:-1,row:-1};
        instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.product_id.index, r]), dropdown[value].data.id);
        instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price.index, r]),dropdown[value].data.price);
        instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.price_buy.index, r]),dropdown[value].data.price_buy);

        // instance.jexcel.setValue(jexcel.getColumnNameFromId([columns.count.index, r]), instance.jexcel.getValue(jexcel.getColumnNameFromId([columns.count.index, r])));

        update(instance, cell, c, r,{

         id:dropdown[value].data.id
        });
       }
      }
     }else if(c === columns.count.index || c === columns.zipcode.index || c === columns.price_buy_sale.index ||
             c === columns.order_ship.index || c === columns.order_ship_cou.index || c === columns.one_address.index){
      if(change.col == c){
       if(c === columns.zipcode.index){
        change = {col:columns.province.index,row:r};
       }else{
        change = {col:-1,row:-1};
        update(instance, cell, c, r,{

        });
       }
      }
     }else if(c === columns.province.index){
      if(change.col == c){
       change = {col:-1,row:-1};
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
       change = {col:-1,row:-1};
       update(instance, cell, c, r,{});
      }
     }
    },
   };
  }
  var win = window,
          doc = document,
          docElem = doc.documentElement,
          body = doc.getElementsByTagName('body')[0],
          x = win.innerWidth || docElem.clientWidth || body.clientWidth,
          y = win.innerHeight|| docElem.clientHeight|| body.clientHeight;
  config.tableHeight = (y*0.65)+"px";

  let sheets = [

  ];

  if(!statusCompnay.hasOwnProperty('AMAZON')){
   if(options.hasOwnProperty('AMAZON') && options.AMAZON.status == 1){
    sheets.push(Object.assign(YAMADA("AMAZON",config),config ));
   }

  }
  if(!statusCompnay.hasOwnProperty('FUKUI')){
   if(options.hasOwnProperty('FUKUI') && options.FUKUI.status == 1)
    sheets.push( Object.assign(FUKUI(config),config));
  }
  if(!statusCompnay.hasOwnProperty('KOGYJA')){
   if(options.hasOwnProperty('KOGYJA') && options.KOGYJA.status == 1)
    sheets.push(Object.assign(KOGYJA(config),Object.assign(config,{})),);
  }
  if(!statusCompnay.hasOwnProperty('KURICHIKU')){
   if(options.hasOwnProperty('KURICHIKU') && options.KURICHIKU.status == 1)
    sheets.push( Object.assign(KURICHIKU(config),config));
  }

  if(!statusCompnay.hasOwnProperty('BANH_CHUNG')){
   if(options.hasOwnProperty('BANH_CHUNG') && options.BANH_CHUNG.status == 1)
    sheets.push( Object.assign(BANH_CHUNG(config),config));
  }
  if(!statusCompnay.hasOwnProperty('OHGA')){
   if(options.hasOwnProperty('OHGA') && options.OHGA.status == 1)
    sheets.push( Object.assign(OHGA(config),config));
  }
  if(!statusCompnay.hasOwnProperty('YAMADA')){
   if(options.hasOwnProperty('YAMADA') && options.YAMADA.status == 1)
    sheets.push(Object.assign(YAMADA("YAMADA",config),config ));
  }
  if(!statusCompnay.hasOwnProperty('Cocolala')){
   if(options.hasOwnProperty('Cocolala') && options.Cocolala.status == 1)
    sheets.push(Object.assign(MISHIMA("Cocolala",config),config ));
  }

  if(!statusCompnay.hasOwnProperty('DO_KHO')){
      if(options.hasOwnProperty('DO_KHO') && options.DO_KHO.status == 1)
          sheets.push(Object.assign(MISHIMA("DO_KHO",config),config ));
  }
  if(!statusCompnay.hasOwnProperty('LICH_2022')){
      if(options.hasOwnProperty('LICH_2022') && options.LICH_2022.status == 1)
          sheets.push(Object.assign(MISHIMA("LICH_2022",config),config ));
  }
   
  if(!statusCompnay.hasOwnProperty('GAO_ST25')){
        if(options.hasOwnProperty('GAO_ST25') && options.GAO_ST25.status == 1){
            let conf = Object.assign(MISHIMA("GAO_ST25",config),config );
            conf.minDimensions = [conf.minDimensions[0],200];
            sheets.push(conf);
        }
    }
  if(!statusCompnay.hasOwnProperty('HOA_QUA')){
      if(options.hasOwnProperty('HOA_QUA') && options.HOA_QUA.status == 1)
          sheets.push(Object.assign(MISHIMA("HOA_QUA",config),config ));
  }
  if(!statusCompnay.hasOwnProperty('QUAT')){
      if(options.hasOwnProperty('QUAT') && options.QUAT.status == 1)
          sheets.push(Object.assign(MISHIMA("QUAT",config),config ));
  }

  if(!statusCompnay.hasOwnProperty('MISHIMA')){
   if(options.hasOwnProperty('MISHIMA') && options.MISHIMA.status == 1)
    sheets.push(Object.assign(MISHIMA("MISHIMA",config),config ));
  }
  for(let i = 0 ; i < sheets.length ; i++){
   sheets[i].minDimensions = [sheets[i].minDimensions[0],parseInt(sheets[i].minDimensions[1] *(y/1000))];
  }
  let spreadsheet =  document.getElementById('spreadsheet');
  let worksheets = jexcel.tabs(spreadsheet, sheets);
</script>
