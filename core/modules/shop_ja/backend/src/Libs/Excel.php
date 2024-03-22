<?php

namespace ShopJa\Libs;

use \PhpOffice\PhpSpreadsheet;
use \PhpOffice\PhpSpreadsheet\Style\Border;
use \PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;

class Excel
{
    protected $file;
    public $DataCol = [

    ];
    public $date = 0;
    public $config = [];

    public function __construct($date, $date_export)
    {
        $this->file = new \Illuminate\Filesystem\Filesystem();
        $this->date = strtotime($date);

        if ($date_export == 0) {
            $this->date_export = $this->date;
        } else {
            $this->date_export = strtotime($date_export);
        }
        $this->config = config_get('config', 'shop_ja');
        $this->DataCol = [
            "FUKUI" => [
                ["支払区分", 'payMethod', 10, 9],//A Phương thức thanh toán
                ["到着希望日", 'order_date1', 15, 9],//B ngày giao hàng
                ["配送希望時間帯", 'order_hours', 15, 9],//C Giờ nhận
                ["配送先氏名", 'fullname', 18, 9],//D Họ và tên khách hàng
                ["配送先郵便番号", 'zipcode', 9, 9],// E Mã bưu điện
                ["配送先都道府県", 'province', 14, 9], // F Tỉnh
                ["配送先住所", 'address', 18, 9], // G Địa chỉ giao hàng
                ["配送先電話番号", 'phone', 10, 9], // H Số điện thoại
                ["別途送料", 'order_ship', 15, 9], //I Phí Ship
                ["余分金", ['callback' => function ($index, $value) {
                    return (int)$value;
                }, 'key' => 'order_price'], 15, 9],// Lợi nhuận J
                ["仕入金額", 'order_total_price_buy', 15, 9], // Tổng giá đơn hàng K
                ["品番", ['product' => ['product_id', 'code']], 10, 9], // Mã sản phẩm L
                ["商品名", ['product' => ['product_name', 'title']], 18, 9], // Tên sản phẩm M
                ["単価", 'order_total_price', 15, 9], // Giá nhập N
                ["数量", 'count', 15, 9], // Số lượng O
                ["", 'order_tracking', 15, 9],
            ],
            "OHGA" => [
                ["注文日", ['callback' => function ($index, $date) {
                    return date("d", strtotime($date)) . '日';
                }, 'key' => 'timeCreate'], 10, 9],//A
                ["支払区分", 'payMethod', 10, 9],//B
                ["配送先電話番号", 'phone', 10, 9],//C
                ["配送先郵便番号", 'zipcode', 9, 9],//D
                ["配送先都道府県", 'province', 14, 9],//E
                ["配送先住所", 'address', 18, 9],//F
                ["配送先氏名", 'fullname', 18, 9],//G
                ["品番", ['product' => ['product_id', 'code']], 10, 9],//H
                ["商品名", ['product' => ['product_name', 'title']], 18, 9],//I
                ["単価", 'price', 15, 9],//J
                ["数量", 'count', 15, 9],//K
                ["到着希望日", 'order_date', 15, 9],//L
                ["配送希望時間帯", 'order_hours', 15, 9],//M
                ["別途送料", 'order_ship', 15, 9],//N
                ["仕入金額", 'order_total_price', 15, 9],//O
                ["代引き請求金額", 'order_total_price_buy', 15, 9],//P
                ["代引き手数料", 'order_ship_cou', 15, 9],//Q
                ["余分金", 'order_price', 15, 9],//R
                ["追跡番号", 'order_tracking', 15, 9],//S
                ["振込み情報", 'order_info', 25, 9],//T
                ["", 'comment', 15, 9], // Số lượng O
//                ["", 'order_link', 25, 9],//U
            ],
            "YAMADA" => [
                ["注文日", ['callback' => function ($index, $date) {
                    return date("d", strtotime($date)) . '日';
                }, 'key' => 'timeCreate'], 10, 9],//A
                ["支払区分", 'payMethod', 10, 9],//B
                ["配送先電話番号", 'phone', 10, 9],//C
                ["配送先郵便番号", 'zipcode', 9, 9],//D
                ["配送先都道府県", 'province', 14, 9],//E
                ["配送先住所", 'address', 18, 9],//F
                ["配送先氏名", 'fullname', 18, 9],//G
                ["品番", ['product' => ['product_id', 'code']], 10, 9],//H
                ["商品名", ['product' => ['product_name', 'title']], 18, 9],//I
                ["単価", 'price', 15, 9],//J
                ["数量", 'count', 15, 9],//K
                ["到着希望日", 'order_date', 15, 9],//L
                ["配送希望時間帯", 'order_hours', 15, 9],//M
                ["別途送料", 'order_ship', 15, 9],//N
                ["仕入金額", 'order_total_price', 15, 9],//O
                ["代引き請求金額", 'order_total_price_buy', 15, 9],//P
                ["代引き手数料", 'order_ship_cou', 15, 9],//Q
                ["余分金", 'order_price', 15, 9],//R
                ["追跡番号", 'order_tracking', 15, 9],//S
                ["振込み情報", 'order_info', 25, 9],//T
                ["", 'order_link', 25, 9],//U
            ],
            "Cocolala" => [
                ["注文日", ['callback' => function ($index, $date) {
                    return date("d", strtotime($date)) . '日';
                }, 'key' => 'timeCreate'], 10, 9],//A
                ["支払区分", 'payMethod', 10, 9],//B
                ["配送先電話番号", 'phone', 10, 9],//C
                ["配送先郵便番号", 'zipcode', 9, 9],//D
                ["配送先都道府県", 'province', 14, 9],//E
                ["配送先住所", 'address', 18, 9],//F
                ["配送先氏名", 'fullname', 18, 9],//G
                ["品番", ['product' => ['product_id', 'code']], 10, 9],//H
                ["商品名", ['product' => ['product_name', 'title']], 18, 9],//I
                ["単価", 'price', 15, 9],//J
                ["数量", 'count', 15, 9],//K
                ["到着希望日", 'order_date', 15, 9],//L
                ["配送希望時間帯", 'order_hours', 15, 9],//M
                ["別途送料", 'order_ship', 15, 9],//N
                ["仕入金額", 'order_total_price', 15, 9],//O
                ["代引き請求金額", 'order_total_price_buy', 15, 9],//P
                ["代引き手数料", 'order_ship_cou', 15, 9],//Q
                ["余分金", 'order_price', 15, 9],//R
                ["追跡番号", 'order_tracking', 15, 9],//S
                ["振込み情報", 'order_info', 25, 9],//T
                ["", 'order_link', 25, 9],//U
            ],
            "KOGYJA" => [
                ["注文日", ['callback' => function ($index, $date) {
                    return date("d", strtotime($date)) . '日';
                }, 'key' => 'timeCreate'], 10, 9],//A
                ["支払区分", 'payMethod', 10, 9],//Phương thức thanh toán
                ["配送先電話番号", 'phone', 10, 9],//Số điện thoại
                ["郵便番号", 'zipcode', 9, 9],//Mã bưu điện
                ["配送先都道府県", 'province', 14, 9],//Tỉnh/TP
                ["配送先住所", 'address', 18, 9],//Địa chỉ giao hàng
                ["配送先氏名", 'fullname', 18, 9],//Họ tên người nhận
                ["品番", ['product' => ['product_id', 'code']], 10, 9],//H
                ["商品名", ['product' => ['product_name', 'title']], 18, 9],//I
                ["単価", 'price', 15, 9],//Giá nhập
                ["数量", 'count', 15, 9],//SL
                ["到着希望日", 'order_date', 15, 9],//Ngày nhận
                ["配送希望時間帯", 'order_hours', 15, 9],//Giờ nhận
                ["送料", 'order_ship', 15, 9],//Phí ship
                ["梱包材", 'total_count', 15, 9],//Tổng giá nhập
                ["仕入金額", 'order_total_price', 15, 9],//Giá bán
                ["振込み金額", 'order_total_price_buy', 15, 9],//Giá bán
                ["手数料", 'order_ship_cou', 15, 9],
                ["余分金", 'order_price', 15, 9],
                ["追跡番号", 'order_tracking', 15, 9],
                ["振込み情報", 'order_info', 25, 9],
            ],
            'KURICHIKU' => [
                ["注文日", ['callback' => function ($index, $date) {
                    return date("d", strtotime($date)) . '日';
                }, 'key' => 'timeCreate'], 10, 9],//A
                ["支払区分", 'payMethod', 10, 9],//Phương thức thanh toán
                ["配送先電話番号", 'phone', 10, 9],//Số điện thoại
                ["郵便番号", 'zipcode', 9, 9],//Mã bưu điện
                ["配送先都道府県", 'province', 14, 9],//Tỉnh/TP
                ["配送先住所", 'address', 18, 9],//Địa chỉ giao hàng
                ["配送先氏名", 'fullname', 18, 9],//Họ tên người nhận
                ["品番", ['product' => ['product_id', 'code']], 10, 9],//H
                ["商品名", ['product' => ['product_name', 'title']], 18, 9],//I
                ["単価", 'price', 15, 9],//Giá nhập
                ["数量", 'count', 15, 9],//SL
                ["到着希望日", 'order_date', 15, 9],//Ngày nhận
                ["配送希望時間帯", 'order_hours', 15, 9],//Giờ nhận
                ["送料", 'order_ship', 15, 9],//Phí ship

                ["仕入金額", 'order_total_price', 15, 9],//Giá bán
                ["振込み金額", 'order_total_price_buy', 15, 9],//Giá bán
                ["手数料", 'order_ship_cou', 15, 9],
                ["余分金", 'order_price', 15, 9],
                ["追跡番号", 'order_tracking', 15, 9],
                ["振込み情報", 'order_info', 25, 9],
            ],
            'BANH_CHUNG' => [
                ["注文日", ['callback' => function ($index, $date) {
                    return date("d", strtotime($date)) . '日';
                }, 'key' => 'timeCreate'], 10, 9],//A
                ["支払区分", 'payMethod', 10, 9],//Phương thức thanh toán
                ["配送先電話番号", 'phone', 10, 9],//Số điện thoại
                ["郵便番号", 'zipcode', 9, 9],//Mã bưu điện
                ["配送先都道府県", 'province', 14, 9],//Tỉnh/TP
                ["配送先住所", 'address', 18, 9],//Địa chỉ giao hàng
                ["配送先氏名", 'fullname', 18, 9],//Họ tên người nhận
                ["品番", ['product' => ['product_id', 'code']], 10, 9],//H
                ["商品名", ['product' => ['product_name', 'title']], 18, 9],//I
                ["単価", 'price', 15, 9],//Giá nhập
                ["数量", 'count', 15, 9],//SL
                ["到着希望日", 'order_date', 15, 9],//Ngày nhận
                ["配送希望時間帯", 'order_hours', 15, 9],//Giờ nhận
                ["送料", 'order_ship', 15, 9],//Phí ship

                ["仕入金額", 'order_total_price', 15, 9],//Giá bán
                ["振込み金額", 'order_total_price_buy', 15, 9],//Giá bán
                ["手数料", 'order_ship_cou', 15, 9],
                ["余分金", 'order_price', 15, 9],
                ["追跡番号", 'order_tracking', 15, 9],
                ["振込み情報", 'order_info', 25, 9],
            ],
            "MISHIMA" => [
                ["注文日", ['callback' => function ($index, $date) {
                    return date("d", strtotime($date)) . '日';
                }, 'key' => 'timeCreate'], 10, 9],//A
                ["支払区分", 'payMethod', 10, 9],//B
                ["配送先電話番号", 'phone', 10, 9],//C
                ["配送先郵便番号", 'zipcode', 9, 9],//D
                ["配送先都道府県", 'province', 14, 9],//E
                ["配送先住所", 'address', 18, 9],//F
                ["配送先氏名", 'fullname', 18, 9],//G
                ["品番", ['product' => ['product_id', 'code']], 10, 9],//H
                ["商品名", ['product' => ['product_name', 'title']], 18, 9],//I
                ["単価", 'price', 15, 9],//J
                ["数量", 'count', 15, 9],//K
                ["到着希望日", 'order_date', 15, 9],//L
                ["配送希望時間帯", 'order_hours', 15, 9],//M
                ["別途送料", 'order_ship', 15, 9],//N
                ["仕入金額", 'order_total_price', 15, 9],//O
                ["代引き請求金額", 'order_total_price_buy', 15, 9],//P
                ["代引き手数料", 'order_ship_cou', 15, 9],//Q
                ["余分金", 'order_price', 15, 9],//R
                ["追跡番号", 'order_tracking', 15, 9],//S
                ["振込み情報", 'order_info', 25, 9],//T
                ["", 'order_link', 25, 9],//U
            ],
            "GAO_ST25" => [
                ["注文日", ['callback' => function ($index, $date) {
                    return date("d", strtotime($date)) . '日';
                }, 'key' => 'timeCreate'], 10, 9],//A
                ["支払区分", 'payMethod', 10, 9],//B
                ["配送先電話番号", 'phone', 10, 9],//C
                ["配送先郵便番号", 'zipcode', 9, 9],//D
                ["配送先都道府県", 'province', 14, 9],//E
                ["配送先住所", 'address', 18, 9],//F
                ["配送先氏名", 'fullname', 18, 9],//G
                ["品番", ['product' => ['product_id', 'code']], 10, 9],//H
                ["商品名", ['product' => ['product_name', 'title']], 18, 9],//I
                ["単価", 'price', 15, 9],//J
                ["数量", 'count', 15, 9],//K
                ["到着希望日", 'order_date', 15, 9],//L
                ["配送希望時間帯", 'order_hours', 15, 9],//M
                ["別途送料", 'order_ship', 15, 9],//N
                ["仕入金額", 'order_total_price', 15, 9],//O
                ["代引き請求金額", 'order_total_price_buy', 15, 9],//P
                ["代引き手数料", 'order_ship_cou', 15, 9],//Q
                ["余分金", 'order_price', 15, 9],//R
                ["追跡番号", 'order_tracking', 15, 9],//S
                ["振込み情報", 'order_info', 25, 9],//T
                ["", 'order_link', 25, 9],//U
            ],
        ];
    }

    function getValuePayMethod($val)
    {
        if ($val === "代金引換") return 1;
        if ($val === "銀行振込") return 2;
        if ($val === "決済不要") return 3;
        return 0;
    }
    function NumberToStringPayMethod($val){
        if ($val == 1) return '代金引換';
        if ($val == 2) return '銀行振込';
        if ($val == 3) return '決済不要';
        return 0;
    }

    public function Read($OriginalName, $inputFileName, $inputFileType)
    {
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($inputFileName);
        $sheet = $spreadsheet->getSheet(0);

        $sheet1 = $spreadsheet->getSheet(1);

        $datas1 = $sheet1->toArray();

        $datas = $sheet->toArray();

        $type = $this->checkTypeCom($OriginalName);

        $html = "";
        $dataRowDatabase = [];

        foreach ($datas1 as $k=>$_row){
            if(!is_null($_row[0])){
                $dataRowDatabase[$_row[0]] = DB::table('shop_order_excel')->where('id',$_row[0])->get()->all();
            }
        }
        if (isset($this->DataCol[$type])) {
            $colums = $this->DataCol[$type];
            foreach ($colums as $key => $value) {
                if (is_array($value[1])) {
                    if (isset($value[1]['product'])) {
                        $conf = $value[1]['product'];

                        $nameColList[$conf[0]] = $key;
                    } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                        $nameColList[$value[1]['key']] = $key;
                    }
                } else {
                    $nameColList[$value[1]] = $key;
                }
            }

            $n = count($datas);
            $html = "";
            $results = [];

            if ($type == "YAMADA"|| $type == "GAO_ST25" || $type == "MISHIMA" || $type == "FUKUI" || $type == "OHGA" || $type == "KOGYJA" || $type == "KURICHIKU" || $type == "BANH_CHUNG") {
                $i = 3;
                if($type == "KURICHIKU" || $type == "BANH_CHUNG" ){
                    $i = 2;
                }
                $order_tracking_index = $nameColList['order_tracking'];
                if ($type == "FUKUI") {
                    $i = 7;
                   // $order_tracking_index+=2;
                }

                for (; $i < $n; $i++) {
                    if(!isset($datas[$i][$order_tracking_index])) continue;

                    $order_tracking = trim(rtrim($datas[$i][$order_tracking_index]));

                    $count = (int)trim(rtrim($datas[$i][$nameColList['count']]));

                    if (!empty($order_tracking)) {

                        $payMethod = trim(rtrim($datas[$i][$nameColList['payMethod']]));
                        $fullname = trim(rtrim($datas[$i][$nameColList['fullname']]));
                        $fullname = preg_replace('/\s+/', ' ', $fullname);
                        $address = trim(rtrim($datas[$i][$nameColList['address']]));
                        $address = preg_replace('/\s+/', '', $address);
                        if (!empty($fullname) && $fullname != "配送先氏名") {
                            $item = [
                                'data' => $datas[$i],
                                'checking' => [$order_tracking],
                                'ids'=>[]
                            ];
                            $id_tracking = isset($datas1[$i][0])?$datas1[$i][0]:0;
                            if(isset($dataRowDatabase[$id_tracking][0])){
                                if(
                                    $dataRowDatabase[$id_tracking][0]->fullname == $fullname &&
                                    $dataRowDatabase[$id_tracking][0]->address == $address &&
                                    $payMethod == $this->NumberToStringPayMethod($dataRowDatabase[$id_tracking][0]->pay_method)
                                ){
                                    $item['ids'][] = $id_tracking;
                                    $item['database'] = (array)$dataRowDatabase[$id_tracking][0];
                                    $item['='] = 1;
                                    unset($dataRowDatabase[$id_tracking]);
                                }
                            }else{
                                $id_tracking = isset($datas1[$i+1][0])?$datas1[$i+1][0]:0;
                                if(isset($dataRowDatabase[$id_tracking][0])){
                                    if(
                                        $dataRowDatabase[$id_tracking][0]->fullname == $fullname &&
                                        $dataRowDatabase[$id_tracking][0]->address == $address &&
                                        $payMethod == $this->NumberToStringPayMethod($dataRowDatabase[$id_tracking][0]->pay_method)
                                    ){
                                        $item['ids'][] = $id_tracking;
                                        $item['database'] = (array)$dataRowDatabase[$id_tracking][0];
                                        $item['='] = 1;
                                        unset($dataRowDatabase[$id_tracking]);
                                    }
                                }else{
                                    $id_tracking = isset($datas1[$i-1][0])?$datas1[$i-1][0]:0;
                                    if(isset($dataRowDatabase[$id_tracking][0])){
                                        if(
                                            $dataRowDatabase[$id_tracking][0]->fullname == $fullname &&
                                            $dataRowDatabase[$id_tracking][0]->address == $address &&
                                            $payMethod == $this->NumberToStringPayMethod($dataRowDatabase[$id_tracking][0]->pay_method)
                                        ){
                                            $item['ids'][] = $id_tracking;
                                            $item['database'] = (array)$dataRowDatabase[$id_tracking][0];
                                            $item['='] = 1;
                                            unset($dataRowDatabase[$id_tracking]);
                                        }
                                    }
                                }
                            }

                            if(!isset($item['ids'][0])){
                                foreach ($dataRowDatabase as $_tran_id=>$_value){
                                    if(isset($_value[0]) && $_value[0]->fullname == $fullname &&
                                        $_value[0]->address == $address &&
                                        $payMethod == $this->NumberToStringPayMethod($_value[0]->pay_method)){
                                        $item['ids'][] = $_tran_id;
                                        $item['database'] = (array)$_value[0];
                                        $item['foreach'] = 1;
                                        unset($dataRowDatabase[$_tran_id]);
                                        break;
                                    }
                                }
                            }
                            for ($j = $i + 1; $j < $count + $i; $j++) {
                                if(!isset($datas[$j][$nameColList['fullname']])) continue;
                                $_fullname = trim(rtrim($datas[$j][$nameColList['fullname']]));
                                $_fullname = preg_replace('/\s+/', ' ', $_fullname);

                                if (strlen($_fullname) > 0) {
                                    break;
                                } else {
                                    $order_tracking = trim(rtrim($datas[$j][$nameColList['order_tracking']]));
                                    if (strlen($order_tracking) > 0)
                                        $item['checking'][] = $order_tracking;
                                        $item['ids'][] =isset($datas1[$i][0])?$datas1[$i][0]:0;
                                }
                            }
                            $results[] = $item;
                        }
                    }
                }

                $category = get_category_type("shop-ja:product:category");

                $ship = get_category_type("shop-ja:japan:category:com-ship");
                $nameShip = "";

                foreach ($category as $item) {
                    if ($item->name == $type) {
                        if (isset($ship[$item->data["ship"]])) {
                            $nameShip = $ship[$item->data["ship"]]->name;
                        }
                    }
                }

                $html = "<table class='table table-bordered'>";
                $html .= "<tr>";
                $html .= "<td class='text-center'><h2>Công Ty</h2></td>";
                $html .= "<td class='text-center'><h2 id='company'>" . $type . "</h2></td>";
                $html .= "<td class='text-center'><h2>Đơn vị vận chuyển</h2></td>";
                $html .= "<td class='text-center'><h2 id='ship'>" . $nameShip . "</h2></td>";
                $html .= "</tr>";
                $html .= "</table>";
                $html .= "<div style=\"overflow:scroll; min-height:100px;\"><table class='table table-bordered'>";

                foreach ($results as $key => $value) {
                    $fullname = trim(rtrim($value['data'][$nameColList['fullname']]));
                    $fullname = preg_replace('/\s+/', ' ', $fullname);
                    $address = preg_replace('/\s+/', '', trim(rtrim($value['data'][$nameColList['address']])));

                    $where = [
                        'fullname' => $fullname,
                        'address' => $address,
                        'company' => $type,
                        'phone' => trim(rtrim($value['data'][$nameColList['phone']])),
                        'zipcode' => trim(rtrim($value['data'][$nameColList['zipcode']])),
                        'province' => trim(rtrim($value['data'][$nameColList['province']])),
                        'pay_method' => $this->getValuePayMethod(trim(rtrim($value['data'][$nameColList['payMethod']]))),
                        'count' => trim(rtrim($value['data'][$nameColList['count']])),
                    ];


                    $_result = DB::table('shop_order_excel')->where($where)
                        ->where('order_create_date', '>=', date('Y-m-d', $this->date_export) . ' 00:00:00')
                        ->where('order_create_date', '<=', date('Y-m-d', $this->date_export) . ' 23:59:59')
                        ->where('export', 1)
                        ->get()->all();


                    $countSum = 0;
                    foreach ($value['checking'] as $k=>$checking){
                        if(isset($value['ids'][$k])){
                            $countSum++;
                        }
                    }
                    $count = count($value['checking']);
                    $class = ($countSum == $count || count($_result)>0 ? 'update' : (($count == 0) ? 'empty' : 'two'));

//                    if ($class == "update") {
//                        if (!empty($_result[0]->order_tracking)) {
//                            if (json_encode($value['checking']) == $_result[0]->order_tracking) {
//                                $class = 'oke';
//                            } else {
//                                $class = 'conflict';
//                            }
//                        }
//                    }
                    $html .= "<tr class='" . $class . "' >";
                   // if ($class == "update") {
                        $html .= "<td>[" . $count . "]<div style='display: none'><textarea class='value'>" .
                            json_encode([
                                'create' => date('Y-m-d H:i:s'),
                                'checking' => $value['checking'],
                                'ids'=> $value['ids'],

                            ]) . "</textarea></div></td>";
                   // } else {
                        $html .= "<td>[" . $count . "]</td>";
                 //   }
//                    $html .= "<td>" . json_encode($where,JSON_UNESCAPED_UNICODE ) . "</td>";
                    foreach ($value['data'] as $k => $val) {
                        $html .= "<td>" . $val . "</td>";
                    }
                    $html .= "</tr>";
                }
                $html .= "</table></div>";
            }
        }
        return $html;
    }

    public function checkTypeCom($name)
    {
        if (strpos($name, "大賀商店のお米の注文分") !== false) {
            return "OHGA";
        } else if (strpos($name, "株式会社クリチク") !== false) {
            return "KURICHIKU";
        } else if (strpos($name, "株式会社コギ家") !== false) {
            return "KOGYJA";
        } else if (strpos($name, "株式会社ヤマダ-様-のお米の注文分") !== false) {
            return "YAMADA";
        } else if (strpos($name, 'の注文分-福井精米様御中')) {
            return "FUKUI";
        }else if (strpos($name, "BANH_CHUNGの注文分") !== false) {
            return "BANH_CHUNG";
        } else if (strpos($name, "株式会社三島食鶏-様") !== false) {
            return "MISHIMA";
        } else if (strpos($name, "株式会社スパイスハウス") !== false) {
            return "GAO_ST25";
        }
    }

    public function FUKUI($datas)
    {
        $name = "FUKUI";
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->createSheet();
        $sheet1 = $spreadsheet->getSheet(1);
        $sheet1->setTitle('Sheet2');

        $sheet->setTitle("Sheet1");
        $spreadsheet->getProperties()
            ->setTitle('PHP Download Example')
            ->setSubject('A PHPExcel example')
            ->setDescription('A simple example for PhpSpreadsheet. This class replaces the PHPExcel class')
            ->setCreator('php-download.com')
            ->setLastModifiedBy('php-download.com');


        $titles = [
            ['A1', 'グエン様専用注文フォーマット'],
            ['G2', '別途送料'],
            ['H2', '北海道：800円'],
            ['H2', '沖縄：1200円'],
            ['N5', ''],
        ];
        $titles = [

        ];
        foreach ($titles as $title) {
            $sheet->setCellValue($title[0], $title[1]);
        }
        $titles = [
            '選択', '入力', '選択', '入力', '入力', '入力', '入力', '入力', '入力', '不要', '入力', '入力', '不要', '不要', '入力'
        ];
        $start = 6;
        foreach ($titles as $key => $value) {
            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
            $sheet->setCellValue($nameCol . $start, $value);
        }

        $styleArray = array(
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            ));
        $style_header = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'color' => array('rgb' => 'ffff00'),
            ),
            'borders' => [
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_DOTTED,
                    'color' => array('rgb' => '000000')
                ),
            ],
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            )
        );
        $sheet->getStyle('B1')->applyFromArray($styleArray);
        $sheet->getStyle('F2')->applyFromArray($styleArray);
        $sheet->getStyle('P2')->applyFromArray(array(
                'font' => array(
                    'size' => 9,
                    'name' => 'ＭＳ Ｐゴシック',
                    'color' => array('rgb' => '0070c0'),
                ),
            )
        );
        $columns_value = array_flip($datas['columns']);
        $colums = [
            ["支払区分", 'payMethod', 10, 9],//A Phương thức thanh toán
            ["到着希望日",['callback' => function ($index, $value) {
                return empty($value)?"":date('Y/m/d',strtotime($value));
            }, 'key' => 'order_date'], 15, 9],//B ngày giao hàng
            ["配送希望時間帯", ['callback' => function ($index, $value) {
                return "8:00 ~ 12:00" == $value ? "午前中" : $value;
            }, 'key' => 'order_hours'], 15, 9],//C Giờ nhận
            ["配送先氏名", ['callback' => function ($index, $value) {
                return preg_replace('/\s+/', ' ', $value);
            }, 'key' => 'fullname'], 18, 9],//D Họ và tên khách hàng
            ["配送先郵便番号", 'zipcode', 9, 9],// E Mã bưu điện
            ["配送先都道府県", 'province', 14, 9], // F Tỉnh
            ["配送先住所", ['callback' => function ($index, $value) {
                return preg_replace('/\s+/', '', $value);
            }, 'key' => 'address'], 18, 9], // G Địa chỉ giao hàng
            ["配送先電話番号", 'phone', 10, 9], // H Số điện thoại
            ["別途送料", 'order_ship', 15, 9], //I Phí Ship
            ["余分金", [
//                'callback' => function ($index, $value, $a, $values) use ($columns_value) {
//                    return $values[$columns_value['payMethod']] == "銀行振込" ? $value : (int)$value + 330;
//                }
                'callback' => function ($index, $value, $a, $values) use ($columns_value) {
                    return '=IF(N'.$index.'="","",K'.$index.'-N'.$index.'*O'.$index.'-I'.$index.')';//$values[$columns_value['payMethod']] == "銀行振込" ? $value : (int)$value + 330;
                }
                ,
                'key' => 'order_price'],
                15, 9],// Lợi nhuận J
            ["仕入金額", 'order_total_price_buy', 15, 9], // Tổng giá đơn hàng K
            ["品番", ['product' => ['product_id', 'code']], 10, 9], // Mã sản phẩm L
            ["商品名", ['product' => ['product_id', 'title']], 18, 9], // Tên sản phẩm M
            ["単価", 'price', 15, 9], // Giá nhập N
            ["数量", 'count', 15, 9], // Số lượng O
            ["追跡番号", '', 15, 9], // Số lượng O
            ["", 'order_info', 15, 9], // Số lượng O
            ["", 'comment', 15, 9], // Số lượng O
        ];
        $start = 7;
        $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . $start)->applyFromArray($style_header);
        $nameColList = [];

       // $sheet->getStyle(PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(7))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_FILL);

        foreach ($colums as $key => $value) {
            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);

            // $sheet->getColumnDimension($nameCol)->setAutoSize(true);

            //$sheet->getStyle($nameCol)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_FILL);

            //

            $keyCol = "";
            $sheet->setCellValue($nameCol . $start, $value[0])->getStyle($nameCol . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                    ),
                )
            );

            if (is_array($value[1])) {
                if (isset($value[1]['product'])) {
                    $conf = $value[1]['product'];
                    $nameColList[$conf[0]] = $key;
                    $keyCol = $conf[1];
                } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                    $nameColList[$value[1]['key']] = $key;
                    $keyCol = $value[1]['key'];
                }
            } else {
                $nameColList[$value[1]] = $key;
                $keyCol = $value[1];
            }

            if($keyCol == "title"){
                $keyCol = "product_name";
            }else  if($keyCol == "code"){
                $keyCol = "product_id";
            }
            if (isset($this->config["excel_width"][$name][$keyCol])) {
                $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($this->config["excel_width"][$name][$keyCol]+0.72);

            } else if ($value[2] > 0) {
                $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($value[2]+0.72);
            }
        }
        $start++;
        $defaultStart = $start;
        $lastIndex = "";
        $company = "36";

        $orders = [

        ];

        $products = DB::table('shop_product')->get()->keyBy('id')->all();
        $images = [];
        $ids = [];
        foreach ($datas['datas'] as $key => $values) {
            $payMethod = "";

            if (empty($values[$columns_value['fullname']])) {
                continue;
            }

            $image = (isset($columns_value['image']) ? $values[$columns_value['image']] : "");
            $order_id = (isset($columns_value['id']) ? $values[$columns_value['id']] : "");
            $ids[$order_id] = 1;
            $order_info = (isset($columns_value['order_info']) ? $values[$columns_value['order_info']] : "");
            if (!empty($image)) {
                $images[] = [str_replace(url('/'), "", $image), $order_info];
            }

            $count = (isset($columns_value['count']) ? $values[$columns_value['count']] : "0");
            $sheet1->setCellValue("A".$start,$order_id);
            $sheet1->setCellValue("B".$start,$count);

            foreach ($colums as $key => $value) {
                $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
                if (is_array($value[1])) {
                    if (isset($value[1]['product'])) {
                        $conf = $value[1]['product'];
                        $id = (isset($columns_value[$conf[0]]) ? $values[$columns_value[$conf[0]]] : "");
                        $_val = "";
                        if (isset($products[$id]) && property_exists($products[$id], $conf[1])) {
                            $_val = $products[$id]->{$conf[1]};
                        }
                        $sheet->setCellValue($nameCol . $start, trim($_val));
                    } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                        $conf = $value[1]['callback'];
                        $_val = call_user_func_array($conf, [$start, (isset($columns_value[$value[1]['key']]) ? $values[$columns_value[$value[1]['key']]] : ""), $nameCol . $start, $values]);
                        $sheet->setCellValue($nameCol . $start, trim($_val));
                    }
                } else {
                    $v = (isset($columns_value[$value[1]]) ? $values[$columns_value[$value[1]]] : "");
                    $sheet->setCellValue($nameCol . $start, trim($v));
                    if ($value[1] == "payMethod") {
                        $payMethod = $v;
                    }
                }
            }
            if ($payMethod == "銀行振込" || $payMethod == "決済不要") {
                $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                    'font' => array(

                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => '0070c0'),
                    ),
                ));
            } else if ($payMethod == "決済不要") {
                $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                ));
            } else {
                $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',

                    ),
                ));
            }
            $start++;
        }

        $sheet->getStyle('K6:K' . $start)->applyFromArray(array(
                'font' => array(
                    'size' => 9,
                    'name' => 'ＭＳ Ｐゴシック',
                    'color' => array('rgb' => 'ff0000'),
                ),
            )
        );
//        $sheet->getStyle('K6:K'.$start)->getStyleArray(array(
//                'fill' => array(
//                    'fillType' => Fill::FILL_SOLID,
//                    'color' => array('rgb'=>'c00010'),
//                ),
//            )
//        );
        // $sheet->setCellValue("J".$start, "=SUM(K".$defaultStart.":K".($start-1).")");
//        $sheet->setCellValue("P".$start, "=SUM(P".$defaultStart.":P".($start-1).")");
//        $sheet->setCellValue("R".$start, "=SUM(R".$defaultStart.":R".($start-1).")");
//        $sheet->setCellValue("Q".$start, "=SUM(Q".$defaultStart.":Q".($start-1).")");

        $writer = new Xlsx($spreadsheet);
        if($datas['type'] != "demo"){
            $path = '/uploads/exports/' . str_replace(__CLASS__ . '::', "", __METHOD__);
        }else{
            $path = '/uploads/demo/' . str_replace(__CLASS__ . '::', "", __METHOD__);
        }
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }
        $path = $path . '/' . date('Y-m-d', $this->date_export);
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        $filename = date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日の注文分-福井精米様御中';


        $path = $path . '/' . $filename;
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        $pathZip = $path . '/zip';
        if ($this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->deleteDirectory(public_path() . $pathZip, true);
        }
        if (!$this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->makeDirectory(public_path() . $pathZip);
        }
        $path2 = $pathZip . '/' . $filename . '.xlsx';
        $writer->save(public_path() . $path2);
        $files = [
            [$filename . '.xlsx', public_path() . $path2]
        ];
        foreach ($images as $image) {

            if (!empty($image[0]) && file_exists(public_path() . "/" . $image[0])) {

                $pathinfo = pathinfo(public_path() . "/" . $image[0]);

                if (empty($image[1])) {
                    $file_image = $pathinfo['filename'] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $pathinfo['filename'] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    $file_image = $image[1] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $image[1] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                }

                $this->file->copy(public_path() . "/" . $image[0], public_path() . '/' . $newName);
                $files[] = [
                    $file_image, public_path() . '/' . $newName
                ];
            }
        }
        $zipFileName = $filename . '.zip';
        $zip = new \ZipArchive();
        if ($this->file->exists(public_path() . '/' . $path . '/' . $zipFileName)) {
            $this->file->delete(public_path() . '/' . $path . '/' . $zipFileName);
        }
        if ($zip->open(public_path() . '/' . $path . '/' . $zipFileName, \ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $zip->addFile($file[1], $file[0]);
            }
            $zip->close();
        }
//        if( $this->file->isDirectory(public_path().$pathZip)){
//            $this->file->deleteDirectory(public_path().$pathZip,true);
//        }
        return ['link' => url($path . '/' . $zipFileName).'?time='.time().'-'.rand(1000,9999), 'images' => $images, 'ids' => $ids];

    }

    public function OHGA($datas)
    {
        $name = "OHGA";
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->createSheet();
        $sheet1 = $spreadsheet->getSheet(1);
        $sheet1->setTitle('Sheet2');

        $sheet->setTitle("Sheet1");
        $spreadsheet->getProperties()
            ->setTitle('PHP Download Example')
            ->setSubject('A PHPExcel example')
            ->setDescription('A simple example for PhpSpreadsheet. This class replaces the PHPExcel class')
            ->setCreator('php-download.com')
            ->setLastModifiedBy('php-download.com');

        $title1 = "大賀商店様 注文フォーマット";//大賀商店様 注文フォーマット
        $title2 = "見本";

        $info = "";

        $sheet->setCellValue('B1', $title1);
        $sheet->setCellValue('F2', $title2);
        $sheet->setCellValue('P2', $info);

        $styleArray = array(
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            ));
        $style_header = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'color' => array('rgb' => 'ffff00'),
            ),
            'borders' => [
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_DOTTED,
                    'color' => array('rgb' => '000000')
                ),
            ],
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            )
        );
        $sheet->getStyle('B1')->applyFromArray($styleArray);
        $sheet->getStyle('F2')->applyFromArray($styleArray);
        $sheet->getStyle('P2')->applyFromArray(array(
                'font' => array(
                    'size' => 9,
                    'name' => 'ＭＳ Ｐゴシック',
                    'color' => array('rgb' => '0070c0'),
                ),
            )
        );
        $sheet->getStyle('A3:T3')->applyFromArray($style_header);


        $date_export = new \stdClass();
        $date_export->date = $this->date_export;

        $_dateNhan = new \stdClass();
        $_dateNhan->date = $this->date;
        $colums = [
            ["注文日", ['callback' => function ($index, $date) use ($date_export) {
                return date("d", $date_export->date) . '日';
            }, 'key' => 'timeCreate'], 10, 9],//A
            ["支払区分", 'payMethod', 10, 9],//B
            ["配送先電話番号", 'phone', 10, 9],//C
            ["配送先郵便番号", 'zipcode', 9, 9],//D
            ["配送先都道府県", 'province', 14, 9],//E
            ["配送先住所", ['callback' => function ($index, $value) {
                return preg_replace('/\s+/', '', $value);
            }, 'key' => 'address'], 18, 9],//F
            ["配送先氏名", ['callback' => function ($index, $value) {
                return preg_replace('/\s+/', ' ', $value);
            }, 'key' => 'fullname'], 18, 9],//G
            ["品番", ['product' => ['product_id', 'code']], 10, 9],//H
            ["商品名", ['product' => ['product_name', 'title']], 18, 9],//I
            ["単価", 'price', 15, 9],//J
            ["数量", 'count', 15, 9],//K
            ["到着希望日", ['callback' => function ($index, $date) use ($_dateNhan) {
                return date("Y/m/d", strtotime($date));
            }, 'key' => 'order_date'], 15, 9],
            ["配送希望時間帯", ['callback' => function ($index, $value) {
                return "8:00 ~ 12:00" == $value ? "午前中" : $value;
            }, 'key' => 'order_hours'], 15, 9],//M
            ["別途送料", 'order_ship', 15, 9],//N
            ["仕入金額", 'order_total_price', 15, 9],//O
            ["代引き請求金額", 'order_total_price_buy', 15, 9],//P
            ["代引き手数料", 'order_ship_cou', 15, 9],//Q
            ["余分金",['callback' => function ($index, $date) use ($date_export) {
                return "=P$index-J$index*K$index-N$index-Q$index";
            }, 'key' => 'order_price'] , 15, 9],//R
            ["追跡番号", 'order_tracking', 15, 9],//S
            ["振込み情報", 'order_info', 25, 9],//T
            ["", 'comment', 15, 9], // Số lượng O
//            ["", 'order_link', 25, 9],//U
        ];
        $start = 3;
        $nameColList = [];
        foreach ($colums as $key => $value) {
            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
            $keyCol = "";
            $sheet->setCellValue($nameCol . $start, $value[0])->getStyle($nameCol . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック'
                    ),
                )
            );
            if (is_array($value[1])) {
                if (isset($value[1]['product'])) {
                    $conf = $value[1]['product'];
                    $nameColList[$conf[0]] = $key;
                    $keyCol = $conf[1];
                } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                    $nameColList[$value[1]['key']] = $key;
                    $keyCol = $value[1]['key'];
                }
            } else {
                $nameColList[$value[1]] = $key;
                $keyCol = $value[1];
            }
            if($keyCol == "title"){
                $keyCol = "product_name";
            }else  if($keyCol == "code"){
                $keyCol = "product_id";
            }
            if (isset($this->config["excel_width"][$name][$keyCol])) {
                $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($this->config["excel_width"][$name][$keyCol]+0.72);
            } else if ($value[2] > 0) {
                $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($value[2]+0.72);
            }
        }
        $sheet->getStyle("P" . $start)->applyFromArray(array(
            'font' => array(
                'color' => array('rgb' => 'ff0000'),
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック',
            ),
        ));
        $start++;
        $defaultStart = $start;
        $lastIndex = "";

        $company = "37";

        $orders = [

        ];

        $products = DB::table('shop_product')->get()->keyBy('id')->all();

        $category = config_get("category", "shop-ja:japan:category");
        $category_ship = get_category_type('shop-ja:japan:category:com-ship');

        $columns_value = array_flip($datas['columns']);
        $products = DB::table('shop_product')->get()->keyBy('id')->all();
        $images = [];
        $ids = [];
        foreach ($datas['datas'] as $key => $values) {
            $payMethod = "";
            if (empty($values[$columns_value['fullname']])) {
                continue;
            }

            $image = (isset($columns_value['image']) ? $values[$columns_value['image']] : "");
            $order_id = (isset($columns_value['id']) ? $values[$columns_value['id']] : "");
            $ids[$order_id] = 1;
            $order_info = (isset($columns_value['order_info']) ? $values[$columns_value['order_info']] : "");
            if (!empty($image)) {
                $images[] = [str_replace(url('/'), "", $image), $order_info];
            }
            $count = (isset($columns_value['count']) ? $values[$columns_value['count']] : "0");
            $sheet1->setCellValue("A".$start,$order_id);
            $sheet1->setCellValue("B".$start,$count);
            foreach ($colums as $key => $value) {
                $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
                if (is_array($value[1])) {
                    if (isset($value[1]['product'])) {
                        $conf = $value[1]['product'];
                        $id = (isset($columns_value[$conf[0]]) ? $values[$columns_value[$conf[0]]] : "");
                        $_val = "";
                        if (isset($products[$id]) && property_exists($products[$id], $conf[1])) {
                            $_val = $products[$id]->{$conf[1]};
                        }
                        $sheet->setCellValue($nameCol . $start, trim($_val));
                    } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                        $conf = $value[1]['callback'];
                        $_val = call_user_func_array($conf, [$start, (isset($columns_value[$value[1]['key']]) ? $values[$columns_value[$value[1]['key']]] : ""), $nameCol . $start]);
                        $sheet->setCellValue($nameCol . $start, trim($_val));
                    }
                } else {
                    $v = (isset($columns_value[$value[1]]) ? $values[$columns_value[$value[1]]] : "");
                    $sheet->setCellValue($nameCol . $start, trim($v));
                    if ($value[1] == "payMethod") {
                        $payMethod = $v;
                    }
                }
            }

            $sheet->getStyle("P" . $start)->applyFromArray(array(
                'font' => array(
                    'color' => array('rgb' => 'ff0000'),
                    'size' => 9,
                    'name' => 'ＭＳ Ｐゴシック',
                ),
            ));
            if ($payMethod == "銀行振込" || $payMethod == "決済不要") {
                $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => '0070c0'),
                    ),
                ));
            } else if ($payMethod == "決済不要") {
                $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                ));
            } else {
                $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',

                    ),
                ));
            }
            $start++;
        }

        $sheet->setCellValue("K" . $start, "=SUM(K" . $defaultStart . ":K" . ($start - 1) . ")");
        $sheet->setCellValue("P" . $start, "=SUM(P" . $defaultStart . ":P" . ($start - 1) . ")");
        $sheet->setCellValue("R" . $start, "=SUM(R" . $defaultStart . ":R" . ($start - 1) . ")");
        $sheet->setCellValue("Q" . $start, "=SUM(Q" . $defaultStart . ":Q" . ($start - 1) . ")");


        foreach (["K", "P", "R", "Q"] as $col) {
            $sheet->getStyle($col . $start)->applyFromArray(array(
                'font' => array(
                    'color' => array('rgb' => 'ff0000'),
                    'size' => 9,
                    'name' => 'ＭＳ Ｐゴシック',
                ),
            ));
        }

        $writer = new Xlsx($spreadsheet);
        if($datas['type'] != "demo"){
            $path = '/uploads/exports/' . str_replace(__CLASS__ . '::', "", __METHOD__);
        }else{
            $path = '/uploads/demo/' . str_replace(__CLASS__ . '::', "", __METHOD__);
        }
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }
        $path = $path . '/' . date('Y-m-d', $this->date_export);
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        $filename = '大賀商店のお米の注文分' . date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日';


        $path = $path . '/' . $filename;
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        $pathZip = $path . '/zip';
        if ($this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->deleteDirectory(public_path() . $pathZip, true);
        }
        if (!$this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->makeDirectory(public_path() . $pathZip);
        }
        $path2 = $pathZip . '/' . $filename . '.xlsx';
        $writer->save(public_path() . $path2);
        $files = [
            [$filename . '.xlsx', public_path() . $path2]
        ];
        foreach ($images as $image) {

            if (!empty($image[0]) && file_exists(public_path() . "/" . $image[0])) {

                $pathinfo = pathinfo(public_path() . "/" . $image[0]);

                if (empty($image[1])) {
                    $file_image = $pathinfo['filename'] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $pathinfo['filename'] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    $file_image = $image[1] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $image[1] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                }

                $this->file->copy(public_path() . "/" . $image[0], public_path() . '/' . $newName);
                $files[] = [
                    $file_image, public_path() . '/' . $newName
                ];
            }
        }
        $zipFileName = $filename . '.zip';
        $zip = new \ZipArchive();
        if ($this->file->exists(public_path() . '/' . $path . '/' . $zipFileName)) {
            $this->file->delete(public_path() . '/' . $path . '/' . $zipFileName);
        }
        if ($zip->open(public_path() . '/' . $path . '/' . $zipFileName, \ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $zip->addFile($file[1], $file[0]);
            }
            $zip->close();
        }
//        if( $this->file->isDirectory(public_path().$pathZip)){
//            $this->file->deleteDirectory(public_path().$pathZip,true);
//        }
        return ['link' => url($path . '/' . $zipFileName).'?time='.time().'-'.rand(1000,9999), 'images' => $images, 'ids' => $ids];

    }

    public function YAMADA($datas, $name, $formatFileName)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->createSheet();

        $sheet->setTitle("Sheet1");

        $sheet1 = $spreadsheet->getSheet(1);
        $sheet1->setTitle('Sheet2');

        $spreadsheet->getProperties()
            ->setTitle('PHP Download Example')
            ->setSubject('A PHPExcel example')
            ->setDescription('A simple example for PhpSpreadsheet. This class replaces the PHPExcel class')
            ->setCreator('php-download.com')
            ->setLastModifiedBy('php-download.com');
        $title1 = "株式会社ヤマダ 様 注文フォーマット";
        $title2 = "見本";
        $info = "";
        $sheet->setCellValue('B1', $title1);
        $sheet->setCellValue('F2', $title2);
        $sheet->setCellValue('P2', $info);
        $styleArray = array(
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            ));
        $style_header = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'color' => array('rgb' => 'ffff00'),
            ),
            'borders' => [
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_DOTTED,
                    'color' => array('rgb' => '000000')
                ),
            ],
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            )
        );
        $sheet->getStyle('B1')->applyFromArray($styleArray);
        $sheet->getStyle('F2')->applyFromArray($styleArray);
        $sheet->getStyle('P2')->applyFromArray(array(
                'font' => array(
                    'size' => 9,
                    'name' => 'ＭＳ Ｐゴシック',
                    'color' => array('rgb' => '0070c0'),
                ),
            )
        );
        $sheet->getStyle('A3:T3')->applyFromArray($style_header);
        $date_export = new \stdClass();
        $date_export->date = $this->date_export;
        $date_nhan = new \stdClass();
        $date_nhan->date = $this->date;
        $columns_value = array_flip($datas['columns']);
        $colums = [
            ["注文日", ['callback' => function ($index, $date) use ($date_export) {
                return date("d", $date_export->date) . '日';
            }, 'key' => 'timeCreate'], 3.29, 9],//A
            ["支払区分", 'payMethod', 6.57, 9],//B
            ["配送先電話番号", 'phone', 10.86, 9],//C
            ["配送先郵便番号", 'zipcode', 6.57, 9],//D
            ["配送先都道府県", 'province', 5.14, 9],//E
            ["配送先住所", ['callback' => function ($index, $value) {
                return preg_replace('/\s+/', '', $value);
            }, 'key' => 'address'], 28.71, 9],//F
            ["配送先氏名", ['callback' => function ($index, $value) {
                return preg_replace('/\s+/', ' ', $value);
            }, 'key' => 'fullname'], 14.71, 9],//G
            ["品番", ['product' => ['product_id', 'code']], 7, 9],//H
            ["商品名", ['product' => ['product_name', 'title']], 18.57, 9],//I
            ["単価", 'price', 4.57, 9],//J
            ["数量", 'count', 2.86, 9],//K
            ["到着希望日", ['callback' => function ($index, $date) use ($date_nhan) {
                return date("Y/m/d", strtotime($date));
            }, 'key' => 'order_date'], 9, 9],//L
            ["配送希望時間帯", ['callback' => function ($index, $value) {
                return "8:00 ~ 12:00" == $value ? "午前中" : $value;
            }, 'key' => 'order_hours'], 10, 9],//M
            ["別途送料", 'order_ship', 4.71, 9],//N
            ["仕入金額", 'order_total_price', 6.43, 9],//O
            ["代引き請求金額", 'order_total_price_buy', 8, 9],//P
            ["代引き手数料", 'order_ship_cou', 3.43, 9],//Q
            ["余分金", ['callback' => function ($index, $date,$a,$val) use ($date_export,$columns_value) {
                if($val[$columns_value['payMethod']] == "決済不要"){
                    return "=0-O$index";
                }else{
                    return "=P$index-J$index*K$index-N$index-Q$index";
                }

            }, 'key' => 'order_price'], 5.43, 9],//R =IF(J4="","",P4-J4*K4-N4-Q4) P4-J4*K4-N4-Q4
            ["追跡番号", 'order_tracking', 4.86, 9],//S
            ["振込み情報",['callback' => function ($index, $value) use ($date_export) {
                return str_replace('依頼人名.','',$value);
            }, 'key' => 'order_info'], 8.57, 9],//T
            ["", 'comment', 15, 9], // Số lượng O
//            ["",'order_link',25,9],//U
        ];
        $start = 3;
        $nameColList = [];
        foreach ($colums as $key => $value) {
            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
            $keyCol = "";
            if (is_array($value[1])) {
                if (isset($value[1]['product'])) {
                    $conf = $value[1]['product'];
                    $nameColList[$conf[0]] = $key;
                    $keyCol = $conf[1];
                } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                    $nameColList[$value[1]['key']] = $key;
                    $keyCol = $value[1]['key'];
                }
            } else {
                $nameColList[$value[1]] = $key;
                $keyCol = $value[1];
            }
            $sheet->setCellValue($nameCol . $start, $value[0])->getStyle($nameCol . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック'
                    ),
                )
            );
            if($keyCol == "title"){
                $keyCol = "product_name";
            }else  if($keyCol == "code"){
                $keyCol = "product_id";
            }
            if (isset($this->config["excel_width"][$name][$keyCol])) {
                $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($this->config["excel_width"][$name][$keyCol]+0.72);
            } else if ($value[2] > 0) {
                $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($value[2]+0.72);
            }

        }
        $start++;
        $defaultStart = $start;
        $lastIndex = "";

        $company = "22";
        $orders = [

        ];


        $products = DB::table('shop_product')->get()->keyBy('id')->all();
        $images = [];
        $ids = [];
        foreach ($datas['datas'] as $key => $values) {
            $payMethod = "";
            if (empty($values[$columns_value['fullname']])) {
                continue;
            }
            $image = (isset($columns_value['image']) ? $values[$columns_value['image']] : "");
            $order_id = (isset($columns_value['id']) ? $values[$columns_value['id']] : "");

            $ids[$order_id] = 1;
            $order_info = (isset($columns_value['order_info']) ? $values[$columns_value['order_info']] : "");
            if (!empty($image)) {
                $images[] = [str_replace(url('/'), "", $image), str_replace('依頼人名.','',$order_info)];
            }

            $count = (isset($columns_value['count']) ? $values[$columns_value['count']] : "0");
            $sheet1->setCellValue("A".$start,$order_id);
            $sheet1->setCellValue("B".$start,$count);

            foreach ($colums as $key => $value) {
                $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
                if (is_array($value[1])) {
                    if (isset($value[1]['product'])) {
                        $conf = $value[1]['product'];
                        $id = (isset($columns_value[$conf[0]]) ? $values[$columns_value[$conf[0]]] : "");
                        $_val = "";
                        if (isset($products[$id]) && property_exists($products[$id], $conf[1])) {
                            $_val = $products[$id]->{$conf[1]};
                        }
                        if ($_val == "0") {
                            //$_val = "";
                        }
                        $sheet->setCellValue($nameCol . $start, trim($_val));
                    } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                        $conf = $value[1]['callback'];
                        $_val = call_user_func_array($conf, [$start, (isset($columns_value[$value[1]['key']]) ? $values[$columns_value[$value[1]['key']]] : ""), $nameCol . $start,$values]);
                        if ($_val == "0") {
                           // $_val = "";
                        }
                        $sheet->setCellValue($nameCol . $start, trim($_val));
                    }
                } else {
                    $v = (isset($columns_value[$value[1]]) ? $values[$columns_value[$value[1]]] : "");
                    if ($v == "0") {
                      //  $v = "";
                    }
                    $sheet->setCellValue($nameCol . $start, trim($v));
                    if ($value[1] == "payMethod") {
                        $payMethod = $v;
                    }
                }
            }
            if ($payMethod == "銀行振込" || $payMethod == "決済不要") {
                $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => '0070c0'),
                    ),
                ));
            } else if ($payMethod == "決済不要") {
                $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                ));
            } else {
                $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',

                    ),
                ));
            }
            $start++;
        }

        $sheet->setCellValue("K" . $start, "=SUM(K" . $defaultStart . ":K" . ($start - 1) . ")");
        $sheet->setCellValue("P" . $start, "=SUM(P" . $defaultStart . ":P" . ($start - 1) . ")");
        $sheet->setCellValue("R" . $start, "=SUM(R" . $defaultStart . ":R" . ($start - 1) . ")");
        $sheet->setCellValue("Q" . $start, "=SUM(Q" . $defaultStart . ":Q" . ($start - 1) . ")");

        $sheet->getStyle("K" . $start)->applyFromArray(array(
                'font' => array(
                    'size' => 9,
                    'name' => 'ＭＳ Ｐゴシック',
                    'color' => array('rgb' => 'ff0000'),
                ),
            )
        );

        $sheet->getStyle("P" . $start)->applyFromArray(array(
                'font' => array(
                    'size' => 9,
                    'name' => 'ＭＳ Ｐゴシック',
                    'color' => array('rgb' => 'ff0000'),
                ),
            )
        );
        $sheet->getStyle("R" . $start)->applyFromArray(array(
                'font' => array(
                    'size' => 9,
                    'name' => 'ＭＳ Ｐゴシック',
                    'color' => array('rgb' => 'ff0000'),
                ),
            )
        );
        $sheet->getStyle("Q" . $start)->applyFromArray(array(
                'font' => array(
                    'size' => 9,
                    'name' => 'ＭＳ Ｐゴシック',
                    'color' => array('rgb' => 'ff0000'),
                ),
            )
        );
        $writer = new Xlsx($spreadsheet);
        if($datas['type'] != "demo"){
            if ($name == "AMAZON") {
                $path = '/uploads/exports/AMAZON';
            }else{
                $path = '/uploads/exports/' . str_replace(__CLASS__ . '::', "", __METHOD__);
            }

        }else{
            if ($name == "AMAZON")
                $path = '/uploads/demo/AMAZON' ;
            else
                $path = '/uploads/demo/' . str_replace(__CLASS__ . '::', "", __METHOD__);
        }
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }
        $path = $path . '/' . date('Y-m-d', $this->date_export);
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        if ($name == "AMAZON") {//AMAZONの注文分[MONTH]月[DAY]日
            $filename = 'の注文分' . date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日';
        } else {
            $filename = '株式会社ヤマダ-様-のお米の注文分' . date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日';
        }

        $path = $path . '/' . $filename;
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        $pathZip = $path . '/zip';
        if ($this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->deleteDirectory(public_path() . $pathZip, true);
        }
        if (!$this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->makeDirectory(public_path() . $pathZip);
        }
        $path2 = $pathZip . '/' . $filename . '.xlsx';
        $writer->save(public_path() . $path2);
        $files = [
            [$filename . '.xlsx', public_path() . $path2]
        ];
        foreach ($images as $image) {
            if (!empty($image[0]) && file_exists(public_path() . "/" . $image[0])) {

                $pathinfo = pathinfo(public_path() . "/" . $image[0]);

                if (empty($image[1])) {
                    $file_image = $pathinfo['filename'] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $pathinfo['filename'] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    $file_image = $image[1] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $image[1] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                }

                $this->file->copy(public_path() . "/" . $image[0], public_path() . '/' . $newName);
                $files[] = [
                    $file_image, public_path() . '/' . $newName
                ];
            }
        }
        $zipFileName = $filename . '.zip';
        $zip = new \ZipArchive();
        if ($this->file->exists(public_path() . '/' . $path . '/' . $zipFileName)) {
            $this->file->delete(public_path() . '/' . $path . '/' . $zipFileName);
        }
        if ($zip->open(public_path() . '/' . $path . '/' . $zipFileName, \ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $zip->addFile($file[1], $file[0]);
            }
            $zip->close();
        }
//        if( $this->file->isDirectory(public_path().$pathZip)){
//            $this->file->deleteDirectory(public_path().$pathZip,true);
//        }
        return ['link' => url($path . '/' . $zipFileName).'?time='.time().'-'.rand(1000,9999), 'images' => $images, "ids" => $ids];

    }

    public function Cocolala($datas, $name, $formatFileName)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->createSheet();

        $sheet->setTitle("Sheet1");

        $sheet1 = $spreadsheet->getSheet(1);
        $sheet1->setTitle('Sheet2');

        $spreadsheet->getProperties()
            ->setTitle('PHP Download Example')
            ->setSubject('A PHPExcel example')
            ->setDescription('A simple example for PhpSpreadsheet. This class replaces the PHPExcel class')
            ->setCreator('php-download.com')
            ->setLastModifiedBy('php-download.com');
        $title1 = "株式会社ココナッツリー様注文フォーマット";
        $title2 = "見本";
        $info = "";
        $sheet->setCellValue('B1', $title1);
        $sheet->setCellValue('F2', $title2);
        $sheet->setCellValue('P2', $info);
        $styleArray = array(
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            ));
        $style_header = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'color' => array('rgb' => 'ffff00'),
            ),
            'borders' => [
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_DOTTED,
                    'color' => array('rgb' => '000000')
                ),
            ],
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            )
        );
        $sheet->getStyle('B1')->applyFromArray($styleArray);
        $sheet->getStyle('F2')->applyFromArray($styleArray);
        $sheet->getStyle('P2')->applyFromArray(array(
                'font' => array(
                    'size' => 9,
                    'name' => 'ＭＳ Ｐゴシック',
                    'color' => array('rgb' => '0070c0'),
                ),
            )
        );

        $date_export = new \stdClass();
        $date_export->date = $this->date_export;
        $date_nhan = new \stdClass();
        $date_nhan->date = $this->date;
        $columns_value = array_flip($datas['columns']);

        $images = [];
        $postions = [2,1];
        $start = 3;
        $logs = [];
        $ids = [];
        for ($typeMethod1 = 0; $typeMethod1 < count($postions); $typeMethod1++) {
            $typeMethod = $postions[$typeMethod1];
            $sheet->getStyle('A'.$start.':T'.$start)->applyFromArray($style_header);

            $colums = [
                ["注文日", ['callback' => function ($index, $date) use ($date_export) {
                    return date("d", $date_export->date) . '日';
                }, 'key' => 'timeCreate'], 3.29, 9],//A
                ["支払区分", 'payMethod', 6.57, 9],//B
                ["配送先電話番号", 'phone', 10.86, 9],//C
                ["配送先郵便番号", 'zipcode', 6.57, 9],//D
                ["配送先都道府県", 'province', 5.14, 9],//E
                ["配送先住所", ['callback' => function ($index, $value) {
                    return preg_replace('/\s+/', '', $value);
                }, 'key' => 'address'], 28.71, 9],//F
                ["配送先氏名", ['callback' => function ($index, $value) {
                    return preg_replace('/\s+/', ' ', $value);
                }, 'key' => 'fullname'], 14.71, 9],//G
                ["品番", ['product' => ['product_id', 'code']], 7, 9],//H
                ["商品名", ['product' => ['product_name', 'title']], 18.57, 9],//I
                ["単価", 'price', 4.57, 9],//J
                ["数量", 'count', 2.86, 9],//K
                ["到着希望日", ['callback' => function ($index, $date) use ($date_nhan) {
                    return date("Y/m/d", strtotime($date));
                }, 'key' => 'order_date'], 9, 9],//L
                ["配送希望時間帯", ['callback' => function ($index, $value) {
                    return "8:00 ~ 12:00" == $value ? "午前中" : $value;
                }, 'key' => 'order_hours'], 10, 9],//M
                ["別途送料", 'order_ship', 4.71, 9],//N
                ["仕入金額", 'order_total_price', 6.43, 9],//O
                ["振込請求金額", 'order_total_price_buy', 8, 9],//P 代引き請求金額
                ["代引き手数料", 'order_ship_cou', 3.43, 9],//Q
                ["余分金", ['callback' => function ($index, $date, $a, $val) use ($date_export, $columns_value) {
                    if ($val[$columns_value['payMethod']] == "決済不要") {
                        return "=0-O$index";
                    } else {
                        return "=P$index-J$index*K$index-N$index-Q$index";
                    }

                }, 'key' => 'order_price'], 5.43, 9],//R =IF(J4="","",P4-J4*K4-N4-Q4) P4-J4*K4-N4-Q4
                ["追跡番号", 'order_tracking', 4.86, 9],//S
                ["振込み情報", ['callback' => function ($index, $value) use ($date_export) {
                    return str_replace('依頼人名.', '', $value);
                }, 'key' => 'order_info'], 8.57, 9],//T
                ["", 'comment', 15, 9], // Số lượng O
//            ["",'order_link',25,9],//U
            ];

            $nameColList = [];
            foreach ($colums as $key => $value) {
                $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
                $keyCol = "";
                if (is_array($value[1])) {
                    if (isset($value[1]['product'])) {
                        $conf = $value[1]['product'];
                        $nameColList[$conf[0]] = $key;
                        $keyCol = $conf[1];
                    } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                        $nameColList[$value[1]['key']] = $key;
                        $keyCol = $value[1]['key'];
                    }
                } else {
                    $nameColList[$value[1]] = $key;
                    $keyCol = $value[1];
                }
                $sheet->setCellValue($nameCol . $start, $value[0])->getStyle($nameCol . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック'
                        ),
                    )
                );
                if ($keyCol == "title") {
                    $keyCol = "product_name";
                } else if ($keyCol == "code") {
                    $keyCol = "product_id";
                }
                if (isset($this->config["excel_width"][$name][$keyCol])) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($this->config["excel_width"][$name][$keyCol] + 0.72);
                } else if ($value[2] > 0) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($value[2] + 0.72);
                }

            }
            $start++;

            $defaultStart = $start;
            $lastIndex = "";

            $company = "22";
            $orders = [

            ];


            $products = DB::table('shop_product')->get()->keyBy('id')->all();


            foreach ($datas['datas'] as $key => $values) {
                $payMethod = (isset($columns_value['payMethod']) ? $values[$columns_value['payMethod']] : "");
                if (empty($values[$columns_value['fullname']])) {
                    continue;
                }
                if(isset($logs[$key])){
                    continue;
                }
                $_checktypeMethod = $this->getValuePayMethod($payMethod);
                if ($typeMethod != $_checktypeMethod) {
                    if( $typeMethod == 2 && $_checktypeMethod == 3){

                    }else{
                        continue;
                    }
                }
                $logs[$key] = 1;

                $image = (isset($columns_value['image']) ? $values[$columns_value['image']] : "");
                $order_id = (isset($columns_value['id']) ? $values[$columns_value['id']] : "");

                $ids[$order_id] = 1;
                $order_info = (isset($columns_value['order_info']) ? $values[$columns_value['order_info']] : "");
                if (!empty($image)) {
                    $images[] = [str_replace(url('/'), "", $image), str_replace('依頼人名.', '', $order_info)];
                }

                $count = (isset($columns_value['count']) ? $values[$columns_value['count']] : "0");
                $sheet1->setCellValue("A" . $start, $order_id);
                $sheet1->setCellValue("B" . $start, $count);

                foreach ($colums as $key => $value) {
                    $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
                    if (is_array($value[1])) {
                        if (isset($value[1]['product'])) {
                            $conf = $value[1]['product'];
                            $id = (isset($columns_value[$conf[0]]) ? $values[$columns_value[$conf[0]]] : "");
                            $_val = "";
                            if (isset($products[$id]) && property_exists($products[$id], $conf[1])) {
                                $_val = $products[$id]->{$conf[1]};
                            }
                            if ($_val == "0") {
                                //$_val = "";
                            }
                            $sheet->setCellValue($nameCol . $start, trim($_val));
                        } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                            $conf = $value[1]['callback'];
                            $_val = call_user_func_array($conf, [$start, (isset($columns_value[$value[1]['key']]) ? $values[$columns_value[$value[1]['key']]] : ""), $nameCol . $start, $values]);
                            if ($_val == "0") {
                                // $_val = "";
                            }
                            $sheet->setCellValue($nameCol . $start, trim($_val));
                        }
                    } else {
                        $v = (isset($columns_value[$value[1]]) ? $values[$columns_value[$value[1]]] : "");
                        if ($v == "0") {
                            //  $v = "";
                        }
                        $sheet->setCellValue($nameCol . $start, trim($v));
                        if ($value[1] == "payMethod") {
                            $payMethod = $v;
                        }
                    }
                }
                if ($payMethod == "銀行振込" || $payMethod == "決済不要") {
                    $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',
                            'color' => array('rgb' => '0070c0'),
                        ),
                    ));
                } else if ($payMethod == "決済不要") {
                    $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',
                            'color' => array('rgb' => 'ff0000'),
                        ),
                    ));
                } else {
                    $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',

                        ),
                    ));
                }
                $start++;
            }
            $sheet->setCellValue("O" . $start, "=SUM(O" . $defaultStart . ":O" . ($start - 1) . ")");
            $sheet->setCellValue("N" . $start, "=SUM(N" . $defaultStart . ":N" . ($start - 1) . ")");

            $sheet->setCellValue("K" . $start, "=SUM(K" . $defaultStart . ":K" . ($start - 1) . ")");
            $sheet->setCellValue("P" . $start, "=SUM(P" . $defaultStart . ":P" . ($start - 1) . ")");
            $sheet->setCellValue("R" . $start, "=SUM(R" . $defaultStart . ":R" . ($start - 1) . ")");
            $sheet->setCellValue("Q" . $start, "=SUM(Q" . $defaultStart . ":Q" . ($start - 1) . ")");

            $sheet->getStyle("O" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $sheet->getStyle("N" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );

            $sheet->getStyle("K" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );

            $sheet->getStyle("P" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $sheet->getStyle("R" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $sheet->getStyle("Q" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $start++;
            $start++;
        }

        $writer = new Xlsx($spreadsheet);
        if($datas['type'] != "demo"){
            if ($name == "AMAZON") {
                $path = '/uploads/exports/AMAZON';
            }else{
                $path = '/uploads/exports/' . str_replace(__CLASS__ . '::', "", __METHOD__);
            }

        }else{
            if ($name == "AMAZON")
                $path = '/uploads/demo/AMAZON' ;
            else
                $path = '/uploads/demo/' . str_replace(__CLASS__ . '::', "", __METHOD__);
        }
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }
        $path = $path . '/' . date('Y-m-d', $this->date_export);
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        if ($name == "AMAZON") {//AMAZONの注文分[MONTH]月[DAY]日
            $filename = 'の注文分' . date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日';
        } else {
            //株式会社三島食鶏-様-07月01日注文分
            $filename = '株式会社ココナッツリー注文分' . date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日注文分';
        }

        $path = $path . '/' . $filename;
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        $pathZip = $path . '/zip';
        if ($this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->deleteDirectory(public_path() . $pathZip, true);
        }
        if (!$this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->makeDirectory(public_path() . $pathZip);
        }
        $path2 = $pathZip . '/' . $filename . '.xlsx';
        $writer->save(public_path() . $path2);
        $files = [
            [$filename . '.xlsx', public_path() . $path2]
        ];
        foreach ($images as $image) {
            if (!empty($image[0]) && file_exists(public_path() . "/" . $image[0])) {

                $pathinfo = pathinfo(public_path() . "/" . $image[0]);

                if (empty($image[1])) {
                    $file_image = $pathinfo['filename'] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $pathinfo['filename'] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    $file_image = $image[1] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $image[1] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                }

                $this->file->copy(public_path() . "/" . $image[0], public_path() . '/' . $newName);
                $files[] = [
                    $file_image, public_path() . '/' . $newName
                ];
            }
        }
        $zipFileName = $filename . '.zip';
        $zip = new \ZipArchive();
        if ($this->file->exists(public_path() . '/' . $path . '/' . $zipFileName)) {
            $this->file->delete(public_path() . '/' . $path . '/' . $zipFileName);
        }
        if ($zip->open(public_path() . '/' . $path . '/' . $zipFileName, \ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $zip->addFile($file[1], $file[0]);
            }
            $zip->close();
        }
//        if( $this->file->isDirectory(public_path().$pathZip)){
//            $this->file->deleteDirectory(public_path().$pathZip,true);
//        }
        return ['link' => url($path . '/' . $zipFileName).'?time='.time().'-'.rand(1000,9999), 'images' => $images, "ids" => $ids];

    }

    public function BANH_CHUNG($datas,$name, $formatFileName)
    {
        //$name = "KURICHIKU";
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->createSheet();
        $sheet1 = $spreadsheet->getSheet(1);
        $sheet1->setTitle('Sheet2');

        $sheet->setTitle("Sheet1");
        $spreadsheet->getProperties()
            ->setTitle('PHP Download Example')
            ->setSubject('A PHPExcel example')
            ->setDescription('A simple example for PhpSpreadsheet. This class replaces the PHPExcel class')
            ->setCreator('php-download.com')
            ->setLastModifiedBy('php-download.com');

        //$sheet->setCellValue('B1', '株式会社クリチク　様 注文フォーマット');


        $styleArray = array(
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            ));
        $style_header = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'color' => array('rgb' => 'ffff00'),
            ),
            'borders' => [
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_DOTTED,
                    'color' => array('rgb' => '000000')
                ),
            ],
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            )
        );
        $sheet->getStyle('B1')->applyFromArray($styleArray);
        $start = 2;
        $products = DB::table('shop_product')->get()->keyBy('id')->all();
        $images = [];
        $ids = [];
        $date_export = new \stdClass();
        $date_export->date = $this->date_export;
        $_dateNhan = new \stdClass();
        $_dateNhan->date = $this->date;
        $postions = [2,1];
        $logs = [];
        for ($typeMethod1 = 0; $typeMethod1 < count($postions); $typeMethod1++) {
            $typeMethod = $postions[$typeMethod1];
            $total_order_ship = 0;
            $total_order_total_price = 0;
            $total_order_total_price_buy = 0;
            $total_ship_cou = 0;
            $total_order_price = 0;
            $columns_value = array_flip($datas['columns']);
            $colums = [
                ["注文日",['callback'=>function($index,$date) use($date_export){return date("d", $date_export->date).'日';},'key'=>'timeCreate'],10,9],//A
                ["支払区分",'payMethod',10,9],//Phương thức thanh toán
                ["配送先電話番号",'phone',10,9],//Số điện thoại
                ["郵便番号",'zipcode',9,9],//Mã bưu điện
                ["配送先都道府県",'province',14,9],//Tỉnh/TP
                ["配送先住所",['callback'=>function($index,$value){return preg_replace('/\s+/', '',$value );},'key'=>'address'],18,9],//Địa chỉ giao hàng
                ["配送先氏名",['callback'=>function($index,$value){return preg_replace('/\s+/', ' ',$value );},'key'=>'fullname'],18,9],//Họ tên người nhận
                ["品番",['callback'=>
                    function($index,$product_id,$a,$values) use($products,$columns_value){
                        try{
                            $array_product = explode(";",$product_id);
                        }catch (\Exception $ex) {
                            $array_product = [];
                        }
                        $product_code = "";$product_title = "";
                        $count = (isset($columns_value["count"])?$values[$columns_value["count"]]:"");
                        try{
                            $array_count = json_decode($count,true);
                        }catch (\Exception $ex) {
                            $array_count = [];
                        }
                        foreach ($array_product as $pro_id){
                            if(isset( $products[$pro_id]) && isset($array_count[$pro_id]) && $array_count[$pro_id] > 0){
                                $product_code.= $products[$pro_id]->code.",";
                                $product_title.= $products[$pro_id]->title.",";
                            }
                        }
                        return rtrim($product_code,',');
                    },'key'=>'product_id'],10,9
                ],//H
                ["商品名",
                    ['callback' => function ($index, $product_id, $a, $values) use ($products, $columns_value) {
                        try {
                            $array_product = explode(";", $product_id);
                        } catch (\Exception $ex) {
                            $array_product = [];
                        }
                        $count = (isset($columns_value["count"]) ? $values[$columns_value["count"]] : "");
                        try {
                            $array_count = json_decode($count, true);
                        } catch (\Exception $ex) {
                            $array_count = [];
                        }
                        $product_title = "";
                        foreach ($array_product as $pro_id) {
                            if (isset($products[$pro_id]) && isset($array_count[$pro_id]) && $array_count[$pro_id] > 0) {
                                $kg = 0;
                                if (isset($array_count[$pro_id])) {
                                    $kg = $array_count[$pro_id];
                                }
                                if ($products[$pro_id]->unit == 1) {
                                    $product_title .= $products[$pro_id]->title . " " . $kg . " cái" . '、';
                                }else if ($products[$pro_id]->unit == 5) {
                                    $product_title .= str_replace('鶏羽', "鶏" . $kg . "羽", $products[$pro_id]->title) . '、';
                                } else if ($products[$pro_id]->unit == 4) {
                                    $product_title .= $products[$pro_id]->title . '、';
                                } else {
                                    $product_title .= $products[$pro_id]->title . " x" . $kg . "" . '、';
                                }
                            }
                        }
                        return rtrim($product_title, '、');

                    }, 'key' => 'product_name'], 18, 9],//I
                ["単価", 'price', 15, 9],//Giá nhập
                ["数量", "total_count", 15, 9],//SL
                ["到着希望日", ['callback' => function ($index, $date) use ($_dateNhan) {
                    return date("Y/m/d", strtotime($date));
                }, 'key' => 'order_date'], 15, 9],//Ngày nhận
                ["配送希望時間帯", ['callback' => function ($index, $value) {
                    return "8:00 ~ 12:00" == $value ? "午前中" : $value;
                }, 'key' => 'order_hours'], 15, 9],//Giờ nhận
                ["送料", 'order_ship', 15, 9],//Phí ship
                ["仕入金額", 'order_total_price', 15, 9],//Giá bán 仕入金額
                [$typeMethod == 2?"振込み金額":"代引き金額", 'order_total_price_buy', 15, 9],//Giá bán 振込み金額
                ["手数料", 'order_ship_cou', 15, 9],
                ["余分金", ['callback' => function ($index, $val) use ($_dateNhan) {
                    return "=P$index-N$index-O$index-Q$index";//$val;
                }, 'key' => 'order_price'], 15, 9],
                ["追跡番号", 'order_tracking', 15, 9],
                ["振込み情報", 'order_info', 25, 9],
                ["", 'comment', 15, 9], // Số lượng O
            ];
            $nameColList = [];

            foreach ($colums as $key => $value) {
                $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
                $keyCol = "";
                if (is_array($value[1])) {
                    if (isset($value[1]['product'])) {
                        $conf = $value[1]['product'];
                        $nameColList[$conf[0]] = $key;
                        $keyCol = $conf[1];
                    } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                        $nameColList[$value[1]['key']] = $key;
                        $keyCol = $value[1]['key'];
                    }
                } else {
                    $nameColList[$value[1]] = $key;
                    $keyCol = $value[1];
                }
                $sheet->setCellValue($nameCol . $start, $value[0])->getStyle($nameCol . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック'
                        ),
                    )
                );
                if($keyCol == "title"){
                    $keyCol = "product_name";
                }else  if($keyCol == "code"){
                    $keyCol = "product_id";
                }
                if (isset($this->config["excel_width"][$name][$keyCol])) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($this->config["excel_width"][$name][$keyCol]+0.72);
                } else if ($value[2] > 0) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($value[2]+0.72);
                }

            }

            $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . $start)->applyFromArray($style_header);
//            $ship = 34;
//            $datas = [];
//            $company = "22";
//            $orders = [
//
//            ];
            $sheet->getStyle("P" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $start++;

            $products = DB::table('shop_product')->get()->keyBy('id')->all();
            $startIndex = $start;
            foreach ($datas['datas'] as $key => $values) {
                $payMethod = (isset($columns_value['payMethod']) ? $values[$columns_value['payMethod']] : "");

                if (empty($values[$columns_value['fullname']])) {
                    continue;
                }
                if(isset($logs[$key])){
                    continue;
                }
                $_checktypeMethod = $this->getValuePayMethod($payMethod);
                if ($typeMethod != $_checktypeMethod) {
                    if( $typeMethod == 2 && $_checktypeMethod == 3){

                    }else{
                        continue;
                    }
                }
                $logs[$key] = 1;
                $order_id = (isset($columns_value['id']) ? $values[$columns_value['id']] : "");
                $ids[$order_id] = 1;
                $image = (isset($columns_value['image']) ? $values[$columns_value['image']] : "");

                $order_info = (isset($columns_value['order_info']) ? $values[$columns_value['order_info']] : "");

                if (!empty($image)) {
                    $images[] = [str_replace(url('/'), "", $image), $order_info];
                }
                $total_order_ship += (int)(isset($columns_value['order_ship']) ? $values[$columns_value['order_ship']] : "0");
                $total_order_total_price += (int)(isset($columns_value['order_total_price']) ? $values[$columns_value['order_total_price']] : "0");
                $total_order_total_price_buy += (int)(isset($columns_value['order_total_price_buy']) ? $values[$columns_value['order_total_price_buy']] : "0");
                $total_ship_cou += (int)(isset($columns_value['order_ship_cou']) ? $values[$columns_value['order_ship_cou']] : "0");
                $total_order_price += (int)(isset($columns_value['order_price']) ? $values[$columns_value['order_price']] : "0");

                $count = (isset($columns_value['count']) ? $values[$columns_value['total_count']] : "0");
                $sheet1->setCellValue("A".$start,$order_id);
                $sheet1->setCellValue("B".$start,$count);

                foreach ($colums as $key1 => $value) {
                    $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key1 + 1);
                    $_val = "";
                    if (is_array($value[1])) {
                        if (isset($value[1]['product'])) {
                            $conf = $value[1]['product'];
                            $id = (isset($columns_value[$conf[0]]) ? $values[$columns_value[$conf[0]]] : "");

                            if (isset($products[$id]) && property_exists($products[$id], $conf[1])) {
                                $_val = $products[$id]->{$conf[1]};
                            }
                            $sheet->setCellValue($nameCol . $start, trim($_val));
                        } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                            $conf = $value[1]['callback'];
                            $_val = call_user_func_array($conf, [$start, (isset($columns_value[$value[1]['key']]) ? $values[$columns_value[$value[1]['key']]] : ""), $nameCol . $start, $values]);
                            $sheet->setCellValue($nameCol . $start, trim($_val));
                        }
                    } else {
                        $v = (isset($columns_value[$value[1]]) ? $values[$columns_value[$value[1]]] : "");
                        $sheet->setCellValue($nameCol . $start, trim($v));
                        $_val = $v;
                        if ($value[1] == "payMethod") {
                            $payMethod = $v;
                        }
                    }


                }

                $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                    ),
                ));

                if ($payMethod == "銀行振込" || $payMethod == "決済不要") {
                    foreach (["B", "H", "I", "J", "S"] as $col) {
                        $sheet->getStyle($col . $start)->applyFromArray(array(
                            'font' => array(
                                'color' => array('rgb' => '0070c0'),
                            ),
                        ));
                    }

                } else if ($payMethod == "決済不要") {
                    foreach (["B", "H", "I", "J", "S"] as $col) {
                        $sheet->getStyle($col . $start)->applyFromArray(array(
                            'font' => array(
                                'color' => array('rgb' => 'ff0000'),
                            ),
                        ));
                    }

                } else {
                    foreach (["B", "H", "I", "J", "S"] as $col) {
                        $sheet->getStyle($col . $start)->applyFromArray(array(
                            'font' => array(
                                'color' => array('rgb' => '0070c0'),
                            ),
                        ));
                    }
                }
                $start++;
            }

            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nameColList['order_ship'] + 1);
            $sheet->setCellValue($nameCol . $start, "=SUM($nameCol$startIndex:".$nameCol.($start-1).")");
            $sheet->getStyle($nameCol . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nameColList['order_total_price'] + 1);
            $sheet->setCellValue($nameCol . $start, "=SUM($nameCol$startIndex:".$nameCol.($start-1).")");
            $sheet->getStyle($nameCol . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nameColList['order_total_price_buy'] + 1);
            $sheet->setCellValue($nameCol . $start, "=SUM($nameCol$startIndex:".$nameCol.($start-1).")");
            $sheet->getStyle($nameCol . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );

            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nameColList['order_ship_cou'] + 1);
            $sheet->setCellValue($nameCol . $start, "=SUM($nameCol$startIndex:".$nameCol.($start-1).")");
            $sheet->getStyle($nameCol . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nameColList['order_price'] + 1);
            $sheet->setCellValue($nameCol . $start, "=SUM($nameCol$startIndex:".$nameCol.($start-1).")");
            $sheet->getStyle($nameCol . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );

            $start++;
            if ($typeMethod == 0) {
//                $start+=2;
//                $sheet->setCellValue('N'.$start, '合計');
//                $sheet->setCellValue('P'.$start, '合計代引き金額：　19330');

//                $sheet->getStyle('N'.$start)->applyFromArray(array(
//                        'font'  => array(
//                            'size'  => 9,
//                            'name' => 'Times New Roman',
//                            'color' => array('rgb' => 'ff1100'),
//                        ),
//                    )
//                );
//                $sheet->getStyle('P'.$start)->applyFromArray(array(
//                        'font'  => array(
//                            'size'  => 9,
//                            'name' => 'Times New Roman',
//                            'color' => array('rgb' => 'ff1100'),
//                        ),
//                    )
//                );
                $start++;
                $sheet->setCellValue('I' . $start, '※1キロずつの小分けをお願いします。');
                $sheet->getStyle('I' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',
                            'color' => array('rgb' => 'ff1100'),
                        ),
                    )
                );
            }
            $start += 1;
            $dataRow = [];
        }

        $writer = new Xlsx($spreadsheet);
        if($name == "BANH_CHUNG"){
            if($datas['type'] != "demo"){
                $path = '/uploads/exports/BANH_CHUNG';
            }else{
                $path = '/uploads/demo/BANH_CHUNG';
            }
        }else{
            if($datas['type'] != "demo"){
                $path = '/uploads/exports/' . str_replace(__CLASS__ . '::', "", __METHOD__);
            }else{
                $path = '/uploads/demo/' . str_replace(__CLASS__ . '::', "", __METHOD__);
            }
        }

        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        $path = $path . '/' . date('Y-m-d', $this->date_export);
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }
        // 株式会社クリチク-様-11月03日注文分
        //  $filename ='株式会社クリチク-様-'.date('m',$this->date).'月'.date('d',$this->date).'日注文分';
        if($name == "BANH_CHUNG"){//BANH_CHUNGの注文分[MONTH]月[DAY]日
            $filename = '/BANH_CHUNGの注文分' . date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日';
        }else{
            $filename = '株式会社クリチク-様-' . date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日注文分';
        }


        $path = $path . '/' . $filename;
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }
        $pathZip = $path . '/zip';
        if ($this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->deleteDirectory(public_path() . $pathZip, true);
        }
        if (!$this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->makeDirectory(public_path() . $pathZip);
        }
        $path2 = $pathZip . '/' . $filename . '.xlsx';
        $writer->save(public_path() . $path2);
        $files = [
            [$filename . '.xlsx', public_path() . $path2]
        ];
        foreach ($images as $image) {

            if (!empty($image[0]) && file_exists(public_path() . "/" . $image[0])) {

                $pathinfo = pathinfo(public_path() . "/" . $image[0]);

                if (empty($image[1])) {
                    $file_image = $pathinfo['filename'] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $pathinfo['filename'] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    $file_image = $image[1] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $image[1] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                }

                $this->file->copy(public_path() . "/" . $image[0], public_path() . '/' . $newName);
                $files[] = [
                    $file_image, public_path() . '/' . $newName
                ];
            }
        }
        $zipFileName = $filename . '.zip';
        $zip = new \ZipArchive();
        if ($this->file->exists(public_path() . '/' . $path . '/' . $zipFileName)) {
            $this->file->delete(public_path() . '/' . $path . '/' . $zipFileName);
        }
        if ($zip->open(public_path() . '/' . $path . '/' . $zipFileName, \ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $zip->addFile($file[1], $file[0]);
            }
            $zip->close();
        }
//        if( $this->file->isDirectory(public_path().$pathZip)){
//            $this->file->deleteDirectory(public_path().$pathZip,true);
//        }
        return ['link' => url($path . '/' . $zipFileName).'?time='.time().'-'.rand(1000,9999), 'images' => $images, 'ids' => $ids];


    }
    public function MISHIMA($datas, $name, $formatFileName)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->createSheet();

        $sheet->setTitle("Sheet1");

        $sheet1 = $spreadsheet->getSheet(1);
        $sheet1->setTitle('Sheet2');

        $spreadsheet->getProperties()
            ->setTitle('PHP Download Example')
            ->setSubject('A PHPExcel example')
            ->setDescription('A simple example for PhpSpreadsheet. This class replaces the PHPExcel class')
            ->setCreator('php-download.com')
            ->setLastModifiedBy('php-download.com');
        $title1 = "株式会社三島食鶏様 注文フォーマット";
        $title2 = "見本";
        $info = "";
        $sheet->setCellValue('B1', $title1);
        $sheet->setCellValue('F2', $title2);
        $sheet->setCellValue('P2', $info);
        $styleArray = array(
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            ));
        $style_header = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'color' => array('rgb' => 'ffff00'),
            ),
            'borders' => [
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_DOTTED,
                    'color' => array('rgb' => '000000')
                ),
            ],
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            )
        );
        $sheet->getStyle('B1')->applyFromArray($styleArray);
        $sheet->getStyle('F2')->applyFromArray($styleArray);
        $sheet->getStyle('P2')->applyFromArray(array(
                'font' => array(
                    'size' => 9,
                    'name' => 'ＭＳ Ｐゴシック',
                    'color' => array('rgb' => '0070c0'),
                ),
            )
        );

        $date_export = new \stdClass();
        $date_export->date = $this->date_export;
        $date_nhan = new \stdClass();
        $date_nhan->date = $this->date;
        $columns_value = array_flip($datas['columns']);

        $images = [];
        $postions = [2,1];
        $start = 3;
        $logs = [];
        $ids = [];
        for ($typeMethod1 = 0; $typeMethod1 < count($postions); $typeMethod1++) {
            $typeMethod = $postions[$typeMethod1];
            $sheet->getStyle('A'.$start.':T'.$start)->applyFromArray($style_header);

            $colums = [
                ["注文日", ['callback' => function ($index, $date) use ($date_export) {
                    return date("d", $date_export->date) . '日';
                }, 'key' => 'timeCreate'], 3.29, 9],//A
                ["支払区分", 'payMethod', 6.57, 9],//B
                ["配送先電話番号", 'phone', 10.86, 9],//C
                ["配送先郵便番号", 'zipcode', 6.57, 9],//D
                ["配送先都道府県", 'province', 5.14, 9],//E
                ["配送先住所", ['callback' => function ($index, $value) {
                    return preg_replace('/\s+/', '', $value);
                }, 'key' => 'address'], 28.71, 9],//F
                ["配送先氏名", ['callback' => function ($index, $value) {
                    return preg_replace('/\s+/', ' ', $value);
                }, 'key' => 'fullname'], 14.71, 9],//G
                ["品番", ['product' => ['product_id', 'code']], 7, 9],//H
                ["商品名", ['product' => ['product_name', 'title']], 18.57, 9],//I
                ["単価", 'price', 4.57, 9],//J
                ["数量", 'count', 2.86, 9],//K
                ["到着希望日", ['callback' => function ($index, $date) use ($date_nhan) {
                    return date("Y/m/d", strtotime($date));
                }, 'key' => 'order_date'], 9, 9],//L
                ["配送希望時間帯", ['callback' => function ($index, $value) {
                    return "8:00 ~ 12:00" == $value ? "午前中" : $value;
                }, 'key' => 'order_hours'], 10, 9],//M
                ["別途送料", 'order_ship', 4.71, 9],//N
                ["仕入金額", 'order_total_price', 6.43, 9],//O
                ["振込請求金額", 'order_total_price_buy', 8, 9],//P
                ["代引き手数料", 'order_ship_cou', 3.43, 9],//Q
                ["余分金", ['callback' => function ($index, $date, $a, $val) use ($date_export, $columns_value) {
                    if ($val[$columns_value['payMethod']] == "決済不要") {//決済不要
                        return "=0-O$index-N$index";
                    } else {
                        return "=P$index-J$index*K$index-N$index-Q$index";
                    }

                }, 'key' => 'order_price'], 5.43, 9],//R =IF(J4="","",P4-J4*K4-N4-Q4) P4-J4*K4-N4-Q4
                ["追跡番号", 'order_tracking', 4.86, 9],//S
                ["振込み情報", ['callback' => function ($index, $value) use ($date_export) {
                    return str_replace('依頼人名.', '', $value);
                }, 'key' => 'order_info'], 8.57, 9],//T
                ["", 'comment', 15, 9], // Số lượng O
//            ["",'order_link',25,9],//U
            ];

            $nameColList = [];
            foreach ($colums as $key => $value) {
                $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
                $keyCol = "";
                if (is_array($value[1])) {
                    if (isset($value[1]['product'])) {
                        $conf = $value[1]['product'];
                        $nameColList[$conf[0]] = $key;
                        $keyCol = $conf[1];
                    } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                        $nameColList[$value[1]['key']] = $key;
                        $keyCol = $value[1]['key'];
                    }
                } else {
                    $nameColList[$value[1]] = $key;
                    $keyCol = $value[1];
                }
                $sheet->setCellValue($nameCol . $start, $value[0])->getStyle($nameCol . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック'
                        ),
                    )
                );
                if ($keyCol == "title") {
                    $keyCol = "product_name";
                } else if ($keyCol == "code") {
                    $keyCol = "product_id";
                }
                if (isset($this->config["excel_width"][$name][$keyCol])) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($this->config["excel_width"][$name][$keyCol] + 0.72);
                } else if ($value[2] > 0) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($value[2] + 0.72);
                }

            }
            $start++;

            $defaultStart = $start;
            $lastIndex = "";

            $company = "22";
            $orders = [

            ];


            $products = DB::table('shop_product')->get()->keyBy('id')->all();


            foreach ($datas['datas'] as $key => $values) {
                $payMethod = (isset($columns_value['payMethod']) ? $values[$columns_value['payMethod']] : "");
                if (empty($values[$columns_value['fullname']])) {
                    continue;
                }
                if(isset($logs[$key])){
                    continue;
                }
                $_checktypeMethod = $this->getValuePayMethod($payMethod);
                if ($typeMethod != $_checktypeMethod) {
                    if( $typeMethod == 2 && $_checktypeMethod == 3){

                    }else{
                        continue;
                    }
                }
                $logs[$key] = 1;

                $image = (isset($columns_value['image']) ? $values[$columns_value['image']] : "");
                $order_id = (isset($columns_value['id']) ? $values[$columns_value['id']] : "");

                $ids[$order_id] = 1;
                $order_info = (isset($columns_value['order_info']) ? $values[$columns_value['order_info']] : "");
                if (!empty($image)) {
                    $images[] = [str_replace(url('/'), "", $image), str_replace('依頼人名.', '', $order_info)];
                }

                $count = (isset($columns_value['count']) ? $values[$columns_value['count']] : "0");
                $sheet1->setCellValue("A" . $start, $order_id);
                $sheet1->setCellValue("B" . $start, $count);

                foreach ($colums as $key => $value) {
                    $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
                    if (is_array($value[1])) {
                        if (isset($value[1]['product'])) {
                            $conf = $value[1]['product'];
                            $id = (isset($columns_value[$conf[0]]) ? $values[$columns_value[$conf[0]]] : "");
                            $_val = "";
                            if (isset($products[$id]) && property_exists($products[$id], $conf[1])) {
                                $_val = $products[$id]->{$conf[1]};
                            }
                            if ($_val == "0") {
                                //$_val = "";
                            }
                            $sheet->setCellValue($nameCol . $start, trim($_val));
                        } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                            $conf = $value[1]['callback'];
                            $_val = call_user_func_array($conf, [$start, (isset($columns_value[$value[1]['key']]) ? $values[$columns_value[$value[1]['key']]] : ""), $nameCol . $start, $values]);
                            if ($_val == "0") {
                                // $_val = "";
                            }
                            $sheet->setCellValue($nameCol . $start, trim($_val));
                        }
                    } else {
                        $v = (isset($columns_value[$value[1]]) ? $values[$columns_value[$value[1]]] : "");
                        if ($v == "0") {
                            //  $v = "";
                        }
                        $sheet->setCellValue($nameCol . $start, trim($v));
                        if ($value[1] == "payMethod") {
                            $payMethod = $v;
                        }
                    }
                }
                if ($payMethod == "銀行振込" || $payMethod == "決済不要") {
                    $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',
                            'color' => array('rgb' => '0070c0'),
                        ),
                    ));
                } else if ($payMethod == "決済不要") {
                    $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',
                            'color' => array('rgb' => 'ff0000'),
                        ),
                    ));
                } else {
                    $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',

                        ),
                    ));
                }
                $start++;
            }
            $sheet->setCellValue("O" . $start, "=SUM(O" . $defaultStart . ":O" . ($start - 1) . ")");
            $sheet->setCellValue("N" . $start, "=SUM(N" . $defaultStart . ":N" . ($start - 1) . ")");

            $sheet->setCellValue("K" . $start, "=SUM(K" . $defaultStart . ":K" . ($start - 1) . ")");
            $sheet->setCellValue("P" . $start, "=SUM(P" . $defaultStart . ":P" . ($start - 1) . ")");
            $sheet->setCellValue("R" . $start, "=SUM(R" . $defaultStart . ":R" . ($start - 1) . ")");
            $sheet->setCellValue("Q" . $start, "=SUM(Q" . $defaultStart . ":Q" . ($start - 1) . ")");

            $sheet->getStyle("O" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $sheet->getStyle("N" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );

            $sheet->getStyle("K" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );

            $sheet->getStyle("P" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $sheet->getStyle("R" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $sheet->getStyle("Q" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $start++;
            $start++;
        }

        $writer = new Xlsx($spreadsheet);
        if($datas['type'] != "demo"){
            if ($name == "AMAZON") {
                $path = '/uploads/exports/AMAZON';
            }else{
                $path = '/uploads/exports/' . str_replace(__CLASS__ . '::', "", __METHOD__);
            }

        }else{
            if ($name == "AMAZON")
                $path = '/uploads/demo/AMAZON' ;
            else
                $path = '/uploads/demo/' . str_replace(__CLASS__ . '::', "", __METHOD__);
        }
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }
        $path = $path . '/' . date('Y-m-d', $this->date_export);
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        if ($name == "AMAZON") {//AMAZONの注文分[MONTH]月[DAY]日
            $filename = 'の注文分' . date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日';
        } else {
            //株式会社三島食鶏-様-07月01日注文分
            $filename = '株式会社三島食鶏-様-' . date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日注文分';
        }

        $path = $path . '/' . $filename;
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        $pathZip = $path . '/zip';
        if ($this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->deleteDirectory(public_path() . $pathZip, true);
        }
        if (!$this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->makeDirectory(public_path() . $pathZip);
        }
        $path2 = $pathZip . '/' . $filename . '.xlsx';
        $writer->save(public_path() . $path2);
        $files = [
            [$filename . '.xlsx', public_path() . $path2]
        ];
        foreach ($images as $image) {
            if (!empty($image[0]) && file_exists(public_path() . "/" . $image[0])) {

                $pathinfo = pathinfo(public_path() . "/" . $image[0]);

                if (empty($image[1])) {
                    $file_image = $pathinfo['filename'] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $pathinfo['filename'] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    $file_image = $image[1] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $image[1] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                }

                $this->file->copy(public_path() . "/" . $image[0], public_path() . '/' . $newName);
                $files[] = [
                    $file_image, public_path() . '/' . $newName
                ];
            }
        }
        $zipFileName = $filename . '.zip';
        $zip = new \ZipArchive();
        if ($this->file->exists(public_path() . '/' . $path . '/' . $zipFileName)) {
            $this->file->delete(public_path() . '/' . $path . '/' . $zipFileName);
        }
        if ($zip->open(public_path() . '/' . $path . '/' . $zipFileName, \ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $zip->addFile($file[1], $file[0]);
            }
            $zip->close();
        }
//        if( $this->file->isDirectory(public_path().$pathZip)){
//            $this->file->deleteDirectory(public_path().$pathZip,true);
//        }
        return ['link' => url($path . '/' . $zipFileName).'?time='.time().'-'.rand(1000,9999), 'images' => $images, "ids" => $ids];

    }
    public function GAO_ST25($datas, $name, $formatFileName)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->createSheet();

        $sheet->setTitle("Sheet1");

        $sheet1 = $spreadsheet->getSheet(1);
        $sheet1->setTitle('Sheet2');

        $spreadsheet->getProperties()
            ->setTitle('PHP Download Example')
            ->setSubject('A PHPExcel example')
            ->setDescription('A simple example for PhpSpreadsheet. This class replaces the PHPExcel class')
            ->setCreator('php-download.com')
            ->setLastModifiedBy('php-download.com');
        $title1 = "株式会社三島食鶏様 注文フォーマット";
        $title2 = "見本";
        $info = "";
        $sheet->setCellValue('B1', $title1);
        $sheet->setCellValue('F2', $title2);
        $sheet->setCellValue('P2', $info);
        $styleArray = array(
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            ));
        $style_header = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'color' => array('rgb' => 'ffff00'),
            ),
            'borders' => [
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_DOTTED,
                    'color' => array('rgb' => '000000')
                ),
            ],
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            )
        );
        $sheet->getStyle('B1')->applyFromArray($styleArray);
        $sheet->getStyle('F2')->applyFromArray($styleArray);
        $sheet->getStyle('P2')->applyFromArray(array(
                'font' => array(
                    'size' => 9,
                    'name' => 'ＭＳ Ｐゴシック',
                    'color' => array('rgb' => '0070c0'),
                ),
            )
        );

        $date_export = new \stdClass();
        $date_export->date = $this->date_export;
        $date_nhan = new \stdClass();
        $date_nhan->date = $this->date;
        $columns_value = array_flip($datas['columns']);

        $images = [];
        $postions = [2,1];
        $start = 3;
        $logs = [];
        $ids = [];
        for ($typeMethod1 = 0; $typeMethod1 < count($postions); $typeMethod1++) {
            $typeMethod = $postions[$typeMethod1];
            $sheet->getStyle('A'.$start.':T'.$start)->applyFromArray($style_header);

            $colums = [
                ["注文日", ['callback' => function ($index, $date) use ($date_export) {
                    return date("d", $date_export->date) . '日';
                }, 'key' => 'timeCreate'], 3.29, 9],//A
                ["支払区分", 'payMethod', 6.57, 9],//B
                ["配送先電話番号", 'phone', 10.86, 9],//C
                ["配送先郵便番号", 'zipcode', 6.57, 9],//D
                ["配送先都道府県", 'province', 5.14, 9],//E
                ["配送先住所", ['callback' => function ($index, $value) {
                    return preg_replace('/\s+/', '', $value);
                }, 'key' => 'address'], 28.71, 9],//F
                ["配送先氏名", ['callback' => function ($index, $value) {
                    return preg_replace('/\s+/', ' ', $value);
                }, 'key' => 'fullname'], 14.71, 9],//G
                ["品番", ['product' => ['product_id', 'code']], 7, 9],//H
                ["商品名", ['product' => ['product_name', 'title']], 18.57, 9],//I
                ["単価", 'price', 4.57, 9],//J
                ["数量", 'count', 2.86, 9],//K
                ["到着希望日", ['callback' => function ($index, $date) use ($date_nhan) {
                    return date("Y/m/d", strtotime($date));
                }, 'key' => 'order_date'], 9, 9],//L
                ["配送希望時間帯", ['callback' => function ($index, $value) {
                    return "8:00 ~ 12:00" == $value ? "午前中" : $value;
                }, 'key' => 'order_hours'], 10, 9],//M
                ["別途送料", 'order_ship', 4.71, 9],//N
                ["仕入金額", 'order_total_price', 6.43, 9],//O
                ["振込請求金額", 'order_total_price_buy', 8, 9],//P
                ["代引き手数料", 'order_ship_cou', 3.43, 9],//Q
                ["余分金", ['callback' => function ($index, $date, $a, $val) use ($date_export, $columns_value) {
                    if ($val[$columns_value['payMethod']] == "決済不要") {//決済不要
                        return "=0-O$index-N$index";
                    } else {
                        return "=P$index-J$index*K$index-N$index-Q$index";
                    }

                }, 'key' => 'order_price'], 5.43, 9],//R =IF(J4="","",P4-J4*K4-N4-Q4) P4-J4*K4-N4-Q4
                ["追跡番号", 'order_tracking', 4.86, 9],//S
                ["振込み情報", ['callback' => function ($index, $value) use ($date_export) {
                    return str_replace('依頼人名.', '', $value);
                }, 'key' => 'order_info'], 8.57, 9],//T
                ["", 'comment', 15, 9], // Số lượng O
//            ["",'order_link',25,9],//U
            ];

            $nameColList = [];
            foreach ($colums as $key => $value) {
                $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
                $keyCol = "";
                if (is_array($value[1])) {
                    if (isset($value[1]['product'])) {
                        $conf = $value[1]['product'];
                        $nameColList[$conf[0]] = $key;
                        $keyCol = $conf[1];
                    } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                        $nameColList[$value[1]['key']] = $key;
                        $keyCol = $value[1]['key'];
                    }
                } else {
                    $nameColList[$value[1]] = $key;
                    $keyCol = $value[1];
                }
                $sheet->setCellValue($nameCol . $start, $value[0])->getStyle($nameCol . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック'
                        ),
                    )
                );
                if ($keyCol == "title") {
                    $keyCol = "product_name";
                } else if ($keyCol == "code") {
                    $keyCol = "product_id";
                }
                if (isset($this->config["excel_width"][$name][$keyCol])) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($this->config["excel_width"][$name][$keyCol] + 0.72);
                } else if ($value[2] > 0) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($value[2] + 0.72);
                }

            }
            $start++;

            $defaultStart = $start;
            $lastIndex = "";

            $company = "22";
            $orders = [

            ];


            $products = DB::table('shop_product')->get()->keyBy('id')->all();


            foreach ($datas['datas'] as $key => $values) {
                $payMethod = (isset($columns_value['payMethod']) ? $values[$columns_value['payMethod']] : "");
                if (empty($values[$columns_value['fullname']])) {
                    continue;
                }
                if(isset($logs[$key])){
                    continue;
                }
                $_checktypeMethod = $this->getValuePayMethod($payMethod);
                if ($typeMethod != $_checktypeMethod) {
                    if( $typeMethod == 2 && $_checktypeMethod == 3){

                    }else{
                        continue;
                    }
                }
                $logs[$key] = 1;

                $image = (isset($columns_value['image']) ? $values[$columns_value['image']] : "");
                $order_id = (isset($columns_value['id']) ? $values[$columns_value['id']] : "");

                $ids[$order_id] = 1;
                $order_info = (isset($columns_value['order_info']) ? $values[$columns_value['order_info']] : "");
                if (!empty($image)) {
                    $images[] = [str_replace(url('/'), "", $image), str_replace('依頼人名.', '', $order_info)];
                }

                $count = (isset($columns_value['count']) ? $values[$columns_value['count']] : "0");
                $sheet1->setCellValue("A" . $start, $order_id);
                $sheet1->setCellValue("B" . $start, $count);

                foreach ($colums as $key => $value) {
                    $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
                    if (is_array($value[1])) {
                        if (isset($value[1]['product'])) {
                            $conf = $value[1]['product'];
                            $id = (isset($columns_value[$conf[0]]) ? $values[$columns_value[$conf[0]]] : "");
                            $_val = "";
                            if (isset($products[$id]) && property_exists($products[$id], $conf[1])) {
                                $_val = $products[$id]->{$conf[1]};
                            }
                            if ($_val == "0") {
                                //$_val = "";
                            }
                            $sheet->setCellValue($nameCol . $start, trim($_val));
                        } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                            $conf = $value[1]['callback'];
                            $_val = call_user_func_array($conf, [$start, (isset($columns_value[$value[1]['key']]) ? $values[$columns_value[$value[1]['key']]] : ""), $nameCol . $start, $values]);
                            if ($_val == "0") {
                                // $_val = "";
                            }
                            $sheet->setCellValue($nameCol . $start, trim($_val));
                        }
                    } else {
                        $v = (isset($columns_value[$value[1]]) ? $values[$columns_value[$value[1]]] : "");
                        if ($v == "0") {
                            //  $v = "";
                        }
                        $sheet->setCellValue($nameCol . $start, trim($v));
                        if ($value[1] == "payMethod") {
                            $payMethod = $v;
                        }
                    }
                }
                if ($payMethod == "銀行振込" || $payMethod == "決済不要") {
                    $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',
                            'color' => array('rgb' => '0070c0'),
                        ),
                    ));
                } else if ($payMethod == "決済不要") {
                    $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',
                            'color' => array('rgb' => 'ff0000'),
                        ),
                    ));
                } else {
                    $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',

                        ),
                    ));
                }
                $start++;
            }
            $sheet->setCellValue("O" . $start, "=SUM(O" . $defaultStart . ":O" . ($start - 1) . ")");
            $sheet->setCellValue("N" . $start, "=SUM(N" . $defaultStart . ":N" . ($start - 1) . ")");

            $sheet->setCellValue("K" . $start, "=SUM(K" . $defaultStart . ":K" . ($start - 1) . ")");
            $sheet->setCellValue("P" . $start, "=SUM(P" . $defaultStart . ":P" . ($start - 1) . ")");
            $sheet->setCellValue("R" . $start, "=SUM(R" . $defaultStart . ":R" . ($start - 1) . ")");
            $sheet->setCellValue("Q" . $start, "=SUM(Q" . $defaultStart . ":Q" . ($start - 1) . ")");

            $sheet->getStyle("O" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $sheet->getStyle("N" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );

            $sheet->getStyle("K" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );

            $sheet->getStyle("P" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $sheet->getStyle("R" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $sheet->getStyle("Q" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $start++;
            $start++;
        }

        $writer = new Xlsx($spreadsheet);
        if($datas['type'] != "demo"){
            if ($name == "AMAZON") {
                $path = '/uploads/exports/AMAZON';
            }else{
                $path = '/uploads/exports/' . str_replace(__CLASS__ . '::', "", __METHOD__);
            }

        }else{
            if ($name == "AMAZON")
                $path = '/uploads/demo/AMAZON' ;
            else
                $path = '/uploads/demo/' . str_replace(__CLASS__ . '::', "", __METHOD__);
        }
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }
        $path = $path . '/' . date('Y-m-d', $this->date_export);
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        if ($name == "AMAZON") {//AMAZONの注文分[MONTH]月[DAY]日
            $filename = 'の注文分' . date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日';
        } else {
            //株式会社三島食鶏-様-07月01日注文分
            $filename = '株式会社スパイスハウス-様-' . date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日注文分';
        }

        $path = $path . '/' . $filename;
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        $pathZip = $path . '/zip';
        if ($this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->deleteDirectory(public_path() . $pathZip, true);
        }
        if (!$this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->makeDirectory(public_path() . $pathZip);
        }
        $path2 = $pathZip . '/' . $filename . '.xlsx';
        $writer->save(public_path() . $path2);
        $files = [
            [$filename . '.xlsx', public_path() . $path2]
        ];
        foreach ($images as $image) {
            if (!empty($image[0]) && file_exists(public_path() . "/" . $image[0])) {

                $pathinfo = pathinfo(public_path() . "/" . $image[0]);

                if (empty($image[1])) {
                    $file_image = $pathinfo['filename'] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $pathinfo['filename'] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    $file_image = $image[1] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $image[1] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                }

                $this->file->copy(public_path() . "/" . $image[0], public_path() . '/' . $newName);
                $files[] = [
                    $file_image, public_path() . '/' . $newName
                ];
            }
        }
        $zipFileName = $filename . '.zip';
        $zip = new \ZipArchive();
        if ($this->file->exists(public_path() . '/' . $path . '/' . $zipFileName)) {
            $this->file->delete(public_path() . '/' . $path . '/' . $zipFileName);
        }
        if ($zip->open(public_path() . '/' . $path . '/' . $zipFileName, \ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $zip->addFile($file[1], $file[0]);
            }
            $zip->close();
        }
//        if( $this->file->isDirectory(public_path().$pathZip)){
//            $this->file->deleteDirectory(public_path().$pathZip,true);
//        }
        return ['link' => url($path . '/' . $zipFileName).'?time='.time().'-'.rand(1000,9999), 'images' => $images, "ids" => $ids];

    }

    public function DO_KHO($datas, $name, $formatFileName)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->createSheet();

        $sheet->setTitle("Sheet1");

        $sheet1 = $spreadsheet->getSheet(1);
        $sheet1->setTitle('Sheet2');

        $spreadsheet->getProperties()
            ->setTitle('PHP Download Example')
            ->setSubject('A PHPExcel example')
            ->setDescription('A simple example for PhpSpreadsheet. This class replaces the PHPExcel class')
            ->setCreator('php-download.com')
            ->setLastModifiedBy('php-download.com');
        $title1 = "株式会社三島食鶏様 注文フォーマット";
        $title2 = "見本";
        $info = "";
        $sheet->setCellValue('B1', $title1);
        $sheet->setCellValue('F2', $title2);
        $sheet->setCellValue('P2', $info);
        $styleArray = array(
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            ));
        $style_header = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'color' => array('rgb' => 'ffff00'),
            ),
            'borders' => [
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_DOTTED,
                    'color' => array('rgb' => '000000')
                ),
            ],
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            )
        );
        $sheet->getStyle('B1')->applyFromArray($styleArray);
        $sheet->getStyle('F2')->applyFromArray($styleArray);
        $sheet->getStyle('P2')->applyFromArray(array(
                'font' => array(
                    'size' => 9,
                    'name' => 'ＭＳ Ｐゴシック',
                    'color' => array('rgb' => '0070c0'),
                ),
            )
        );

        $date_export = new \stdClass();
        $date_export->date = $this->date_export;
        $date_nhan = new \stdClass();
        $date_nhan->date = $this->date;
        $columns_value = array_flip($datas['columns']);

        $images = [];
        $postions = [2,1];
        $start = 3;
        $logs = [];
        $ids = [];
        for ($typeMethod1 = 0; $typeMethod1 < count($postions); $typeMethod1++) {
            $typeMethod = $postions[$typeMethod1];
            $sheet->getStyle('A'.$start.':T'.$start)->applyFromArray($style_header);

            $colums = [
                ["注文日", ['callback' => function ($index, $date) use ($date_export) {
                    return date("d", $date_export->date) . '日';
                }, 'key' => 'timeCreate'], 3.29, 9],//A
                ["支払区分", 'payMethod', 6.57, 9],//B
                ["配送先電話番号", 'phone', 10.86, 9],//C
                ["配送先郵便番号", 'zipcode', 6.57, 9],//D
                ["配送先都道府県", 'province', 5.14, 9],//E
                ["配送先住所", ['callback' => function ($index, $value) {
                    return preg_replace('/\s+/', '', $value);
                }, 'key' => 'address'], 28.71, 9],//F
                ["配送先氏名", ['callback' => function ($index, $value) {
                    return preg_replace('/\s+/', ' ', $value);
                }, 'key' => 'fullname'], 14.71, 9],//G
                ["品番", ['product' => ['product_id', 'code']], 7, 9],//H
                ["商品名", ['product' => ['product_name', 'title']], 18.57, 9],//I
                ["単価", 'price', 4.57, 9],//J
                ["数量", 'count', 2.86, 9],//K
                ["到着希望日", ['callback' => function ($index, $date) use ($date_nhan) {
                    return date("Y/m/d", strtotime($date));
                }, 'key' => 'order_date'], 9, 9],//L
                ["配送希望時間帯", ['callback' => function ($index, $value) {
                    return "8:00 ~ 12:00" == $value ? "午前中" : $value;
                }, 'key' => 'order_hours'], 10, 9],//M
                ["別途送料", 'order_ship', 4.71, 9],//N
                ["仕入金額", 'order_total_price', 6.43, 9],//O
                ["振込請求金額", 'order_total_price_buy', 8, 9],//P
                ["代引き手数料", 'order_ship_cou', 3.43, 9],//Q
                ["余分金", ['callback' => function ($index, $date, $a, $val) use ($date_export, $columns_value) {
                    if ($val[$columns_value['payMethod']] == "決済不要") {
                        return "=0-O$index";
                    } else {
                        return "=P$index-J$index*K$index-N$index-Q$index";
                    }

                }, 'key' => 'order_price'], 5.43, 9],//R =IF(J4="","",P4-J4*K4-N4-Q4) P4-J4*K4-N4-Q4
                ["追跡番号", 'order_tracking', 4.86, 9],//S
                ["振込み情報", ['callback' => function ($index, $value) use ($date_export) {
                    return str_replace('依頼人名.', '', $value);
                }, 'key' => 'order_info'], 8.57, 9],//T
                ["", 'comment', 15, 9], // Số lượng O
//            ["",'order_link',25,9],//U
            ];

            $nameColList = [];
            foreach ($colums as $key => $value) {
                $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
                $keyCol = "";
                if (is_array($value[1])) {
                    if (isset($value[1]['product'])) {
                        $conf = $value[1]['product'];
                        $nameColList[$conf[0]] = $key;
                        $keyCol = $conf[1];
                    } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                        $nameColList[$value[1]['key']] = $key;
                        $keyCol = $value[1]['key'];
                    }
                } else {
                    $nameColList[$value[1]] = $key;
                    $keyCol = $value[1];
                }
                $sheet->setCellValue($nameCol . $start, $value[0])->getStyle($nameCol . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック'
                        ),
                    )
                );
                if ($keyCol == "title") {
                    $keyCol = "product_name";
                } else if ($keyCol == "code") {
                    $keyCol = "product_id";
                }
                if (isset($this->config["excel_width"][$name][$keyCol])) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($this->config["excel_width"][$name][$keyCol] + 0.72);
                } else if ($value[2] > 0) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($value[2] + 0.72);
                }

            }
            $start++;

            $defaultStart = $start;
            $lastIndex = "";

            $company = "22";
            $orders = [

            ];


            $products = DB::table('shop_product')->get()->keyBy('id')->all();


            foreach ($datas['datas'] as $key => $values) {
                $payMethod = (isset($columns_value['payMethod']) ? $values[$columns_value['payMethod']] : "");
                if (empty($values[$columns_value['fullname']])) {
                    continue;
                }
                if(isset($logs[$key])){
                    continue;
                }
                $_checktypeMethod = $this->getValuePayMethod($payMethod);
                if ($typeMethod != $_checktypeMethod) {
                    if( $typeMethod == 2 && $_checktypeMethod == 3){

                    }else{
                        continue;
                    }
                }
                $logs[$key] = 1;

                $image = (isset($columns_value['image']) ? $values[$columns_value['image']] : "");
                $order_id = (isset($columns_value['id']) ? $values[$columns_value['id']] : "");

                $ids[$order_id] = 1;
                $order_info = (isset($columns_value['order_info']) ? $values[$columns_value['order_info']] : "");
                if (!empty($image)) {
                    $images[] = [str_replace(url('/'), "", $image), str_replace('依頼人名.', '', $order_info)];
                }

                $count = (isset($columns_value['count']) ? $values[$columns_value['count']] : "0");
                $sheet1->setCellValue("A" . $start, $order_id);
                $sheet1->setCellValue("B" . $start, $count);

                foreach ($colums as $key => $value) {
                    $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
                    if (is_array($value[1])) {
                        if (isset($value[1]['product'])) {
                            $conf = $value[1]['product'];
                            $id = (isset($columns_value[$conf[0]]) ? $values[$columns_value[$conf[0]]] : "");
                            $_val = "";
                            if (isset($products[$id]) && property_exists($products[$id], $conf[1])) {
                                $_val = $products[$id]->{$conf[1]};
                            }
                            if ($_val == "0") {
                                //$_val = "";
                            }
                            $sheet->setCellValue($nameCol . $start, trim($_val));
                        } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                            $conf = $value[1]['callback'];
                            $_val = call_user_func_array($conf, [$start, (isset($columns_value[$value[1]['key']]) ? $values[$columns_value[$value[1]['key']]] : ""), $nameCol . $start, $values]);
                            if ($_val == "0") {
                                // $_val = "";
                            }
                            $sheet->setCellValue($nameCol . $start, trim($_val));
                        }
                    } else {
                        $v = (isset($columns_value[$value[1]]) ? $values[$columns_value[$value[1]]] : "");
                        if ($v == "0") {
                            //  $v = "";
                        }
                        $sheet->setCellValue($nameCol . $start, trim($v));
                        if ($value[1] == "payMethod") {
                            $payMethod = $v;
                        }
                    }
                }
                if ($payMethod == "銀行振込" || $payMethod == "決済不要") {
                    $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',
                            'color' => array('rgb' => '0070c0'),
                        ),
                    ));
                } else if ($payMethod == "決済不要") {
                    $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',
                            'color' => array('rgb' => 'ff0000'),
                        ),
                    ));
                } else {
                    $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',

                        ),
                    ));
                }
                $start++;
            }
            $sheet->setCellValue("O" . $start, "=SUM(O" . $defaultStart . ":O" . ($start - 1) . ")");
            $sheet->setCellValue("N" . $start, "=SUM(N" . $defaultStart . ":N" . ($start - 1) . ")");

            $sheet->setCellValue("K" . $start, "=SUM(K" . $defaultStart . ":K" . ($start - 1) . ")");
            $sheet->setCellValue("P" . $start, "=SUM(P" . $defaultStart . ":P" . ($start - 1) . ")");
            $sheet->setCellValue("R" . $start, "=SUM(R" . $defaultStart . ":R" . ($start - 1) . ")");
            $sheet->setCellValue("Q" . $start, "=SUM(Q" . $defaultStart . ":Q" . ($start - 1) . ")");

            $sheet->getStyle("O" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $sheet->getStyle("N" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );

            $sheet->getStyle("K" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );

            $sheet->getStyle("P" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $sheet->getStyle("R" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $sheet->getStyle("Q" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $start++;
            $start++;
        }

        $writer = new Xlsx($spreadsheet);
        if($datas['type'] != "demo"){
            if ($name == "AMAZON") {
                $path = '/uploads/exports/AMAZON';
            }else{
                $path = '/uploads/exports/' . str_replace(__CLASS__ . '::', "", __METHOD__);
            }

        }else{
            if ($name == "AMAZON")
                $path = '/uploads/demo/AMAZON' ;
            else
                $path = '/uploads/demo/' . str_replace(__CLASS__ . '::', "", __METHOD__);
        }
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }
        $path = $path . '/' . date('Y-m-d', $this->date_export);
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        if ($name == "AMAZON") {//AMAZONの注文分[MONTH]月[DAY]日
            $filename = 'の注文分' . date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日';
        } else {
            //株式会社三島食鶏-様-07月01日注文分
            $filename = 'スパイス-' . date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日注文分';
        }

        $path = $path . '/' . $filename;
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        $pathZip = $path . '/zip';
        if ($this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->deleteDirectory(public_path() . $pathZip, true);
        }
        if (!$this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->makeDirectory(public_path() . $pathZip);
        }
        $path2 = $pathZip . '/' . $filename . '.xlsx';
        $writer->save(public_path() . $path2);
        $files = [
            [$filename . '.xlsx', public_path() . $path2]
        ];
        foreach ($images as $image) {
            if (!empty($image[0]) && file_exists(public_path() . "/" . $image[0])) {

                $pathinfo = pathinfo(public_path() . "/" . $image[0]);

                if (empty($image[1])) {
                    $file_image = $pathinfo['filename'] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $pathinfo['filename'] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    $file_image = $image[1] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $image[1] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                }

                $this->file->copy(public_path() . "/" . $image[0], public_path() . '/' . $newName);
                $files[] = [
                    $file_image, public_path() . '/' . $newName
                ];
            }
        }
        $zipFileName = $filename . '.zip';
        $zip = new \ZipArchive();
        if ($this->file->exists(public_path() . '/' . $path . '/' . $zipFileName)) {
            $this->file->delete(public_path() . '/' . $path . '/' . $zipFileName);
        }
        if ($zip->open(public_path() . '/' . $path . '/' . $zipFileName, \ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $zip->addFile($file[1], $file[0]);
            }
            $zip->close();
        }
//        if( $this->file->isDirectory(public_path().$pathZip)){
//            $this->file->deleteDirectory(public_path().$pathZip,true);
//        }
        return ['link' => url($path . '/' . $zipFileName).'?time='.time().'-'.rand(1000,9999), 'images' => $images, "ids" => $ids];

    }
    public function LICH_2022($datas, $name, $formatFileName)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->createSheet();

        $sheet->setTitle("Sheet1");

        $sheet1 = $spreadsheet->getSheet(1);
        $sheet1->setTitle('Sheet2');

        $spreadsheet->getProperties()
            ->setTitle('PHP Download Example')
            ->setSubject('A PHPExcel example')
            ->setDescription('A simple example for PhpSpreadsheet. This class replaces the PHPExcel class')
            ->setCreator('php-download.com')
            ->setLastModifiedBy('php-download.com');
        $title1 = "株式会社三島食鶏様 注文フォーマット";
        $title2 = "見本";
        $info = "";
        $sheet->setCellValue('B1', $title1);
        $sheet->setCellValue('F2', $title2);
        $sheet->setCellValue('P2', $info);
        $styleArray = array(
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            ));
        $style_header = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'color' => array('rgb' => 'ffff00'),
            ),
            'borders' => [
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_DOTTED,
                    'color' => array('rgb' => '000000')
                ),
            ],
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            )
        );
        $sheet->getStyle('B1')->applyFromArray($styleArray);
        $sheet->getStyle('F2')->applyFromArray($styleArray);
        $sheet->getStyle('P2')->applyFromArray(array(
                'font' => array(
                    'size' => 9,
                    'name' => 'ＭＳ Ｐゴシック',
                    'color' => array('rgb' => '0070c0'),
                ),
            )
        );

        $date_export = new \stdClass();
        $date_export->date = $this->date_export;
        $date_nhan = new \stdClass();
        $date_nhan->date = $this->date;
        $columns_value = array_flip($datas['columns']);

        $images = [];
        $postions = [2,1];
        $start = 3;
        $logs = [];
        $ids = [];
        for ($typeMethod1 = 0; $typeMethod1 < count($postions); $typeMethod1++) {
            $typeMethod = $postions[$typeMethod1];
            $sheet->getStyle('A'.$start.':T'.$start)->applyFromArray($style_header);

            $colums = [
                ["注文日", ['callback' => function ($index, $date) use ($date_export) {
                    return date("d", $date_export->date) . '日';
                }, 'key' => 'timeCreate'], 3.29, 9],//A
                ["支払区分", 'payMethod', 6.57, 9],//B
                ["配送先電話番号", 'phone', 10.86, 9],//C
                ["配送先郵便番号", 'zipcode', 6.57, 9],//D
                ["配送先都道府県", 'province', 5.14, 9],//E
                ["配送先住所", ['callback' => function ($index, $value) {
                    return preg_replace('/\s+/', '', $value);
                }, 'key' => 'address'], 28.71, 9],//F
                ["配送先氏名", ['callback' => function ($index, $value) {
                    return preg_replace('/\s+/', ' ', $value);
                }, 'key' => 'fullname'], 14.71, 9],//G
                ["品番", ['product' => ['product_id', 'code']], 7, 9],//H
                ["商品名", ['product' => ['product_name', 'title']], 18.57, 9],//I
                ["単価", 'price', 4.57, 9],//J
                ["数量", 'count', 2.86, 9],//K
                ["到着希望日", ['callback' => function ($index, $date) use ($date_nhan) {
                    return date("Y/m/d", strtotime($date));
                }, 'key' => 'order_date'], 9, 9],//L
                ["配送希望時間帯", ['callback' => function ($index, $value) {
                    return "8:00 ~ 12:00" == $value ? "午前中" : $value;
                }, 'key' => 'order_hours'], 10, 9],//M
                ["別途送料", 'order_ship', 4.71, 9],//N
                ["仕入金額", 'order_total_price', 6.43, 9],//O
                ["振込請求金額", 'order_total_price_buy', 8, 9],//P
                ["代引き手数料", 'order_ship_cou', 3.43, 9],//Q
                ["余分金", ['callback' => function ($index, $date, $a, $val) use ($date_export, $columns_value) {
                    if ($val[$columns_value['payMethod']] == "決済不要") {
                        return "=0-O$index";
                    } else {
                        return "=P$index-J$index*K$index-N$index-Q$index";
                    }

                }, 'key' => 'order_price'], 5.43, 9],//R =IF(J4="","",P4-J4*K4-N4-Q4) P4-J4*K4-N4-Q4
                ["追跡番号", 'order_tracking', 4.86, 9],//S
                ["振込み情報", ['callback' => function ($index, $value) use ($date_export) {
                    return str_replace('依頼人名.', '', $value);
                }, 'key' => 'order_info'], 8.57, 9],//T
                ["", 'comment', 15, 9], // Số lượng O
//            ["",'order_link',25,9],//U
            ];

            $nameColList = [];
            foreach ($colums as $key => $value) {
                $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
                $keyCol = "";
                if (is_array($value[1])) {
                    if (isset($value[1]['product'])) {
                        $conf = $value[1]['product'];
                        $nameColList[$conf[0]] = $key;
                        $keyCol = $conf[1];
                    } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                        $nameColList[$value[1]['key']] = $key;
                        $keyCol = $value[1]['key'];
                    }
                } else {
                    $nameColList[$value[1]] = $key;
                    $keyCol = $value[1];
                }
                $sheet->setCellValue($nameCol . $start, $value[0])->getStyle($nameCol . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック'
                        ),
                    )
                );
                if ($keyCol == "title") {
                    $keyCol = "product_name";
                } else if ($keyCol == "code") {
                    $keyCol = "product_id";
                }
                if (isset($this->config["excel_width"][$name][$keyCol])) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($this->config["excel_width"][$name][$keyCol] + 0.72);
                } else if ($value[2] > 0) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($value[2] + 0.72);
                }

            }
            $start++;

            $defaultStart = $start;
            $lastIndex = "";

            $company = "22";
            $orders = [

            ];


            $products = DB::table('shop_product')->get()->keyBy('id')->all();


            foreach ($datas['datas'] as $key => $values) {
                $payMethod = (isset($columns_value['payMethod']) ? $values[$columns_value['payMethod']] : "");
                if (empty($values[$columns_value['fullname']])) {
                    continue;
                }
                if(isset($logs[$key])){
                    continue;
                }
                $_checktypeMethod = $this->getValuePayMethod($payMethod);
                if ($typeMethod != $_checktypeMethod) {
                    if( $typeMethod == 2 && $_checktypeMethod == 3){

                    }else{
                        continue;
                    }
                }
                $logs[$key] = 1;

                $image = (isset($columns_value['image']) ? $values[$columns_value['image']] : "");
                $order_id = (isset($columns_value['id']) ? $values[$columns_value['id']] : "");

                $ids[$order_id] = 1;
                $order_info = (isset($columns_value['order_info']) ? $values[$columns_value['order_info']] : "");
                if (!empty($image)) {
                    $images[] = [str_replace(url('/'), "", $image), str_replace('依頼人名.', '', $order_info)];
                }

                $count = (isset($columns_value['count']) ? $values[$columns_value['count']] : "0");
                $sheet1->setCellValue("A" . $start, $order_id);
                $sheet1->setCellValue("B" . $start, $count);

                foreach ($colums as $key => $value) {
                    $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
                    if (is_array($value[1])) {
                        if (isset($value[1]['product'])) {
                            $conf = $value[1]['product'];
                            $id = (isset($columns_value[$conf[0]]) ? $values[$columns_value[$conf[0]]] : "");
                            $_val = "";
                            if (isset($products[$id]) && property_exists($products[$id], $conf[1])) {
                                $_val = $products[$id]->{$conf[1]};
                            }
                            if ($_val == "0") {
                                //$_val = "";
                            }
                            $sheet->setCellValue($nameCol . $start, trim($_val));
                        } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                            $conf = $value[1]['callback'];
                            $_val = call_user_func_array($conf, [$start, (isset($columns_value[$value[1]['key']]) ? $values[$columns_value[$value[1]['key']]] : ""), $nameCol . $start, $values]);
                            if ($_val == "0") {
                                // $_val = "";
                            }
                            $sheet->setCellValue($nameCol . $start, trim($_val));
                        }
                    } else {
                        $v = (isset($columns_value[$value[1]]) ? $values[$columns_value[$value[1]]] : "");
                        if ($v == "0") {
                            //  $v = "";
                        }
                        $sheet->setCellValue($nameCol . $start, trim($v));
                        if ($value[1] == "payMethod") {
                            $payMethod = $v;
                        }
                    }
                }
                if ($payMethod == "銀行振込" || $payMethod == "決済不要") {
                    $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',
                            'color' => array('rgb' => '0070c0'),
                        ),
                    ));
                } else if ($payMethod == "決済不要") {
                    $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',
                            'color' => array('rgb' => 'ff0000'),
                        ),
                    ));
                } else {
                    $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',

                        ),
                    ));
                }
                $start++;
            }
            $sheet->setCellValue("O" . $start, "=SUM(O" . $defaultStart . ":O" . ($start - 1) . ")");
            $sheet->setCellValue("N" . $start, "=SUM(N" . $defaultStart . ":N" . ($start - 1) . ")");

            $sheet->setCellValue("K" . $start, "=SUM(K" . $defaultStart . ":K" . ($start - 1) . ")");
            $sheet->setCellValue("P" . $start, "=SUM(P" . $defaultStart . ":P" . ($start - 1) . ")");
            $sheet->setCellValue("R" . $start, "=SUM(R" . $defaultStart . ":R" . ($start - 1) . ")");
            $sheet->setCellValue("Q" . $start, "=SUM(Q" . $defaultStart . ":Q" . ($start - 1) . ")");

            $sheet->getStyle("O" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $sheet->getStyle("N" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );

            $sheet->getStyle("K" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );

            $sheet->getStyle("P" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $sheet->getStyle("R" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $sheet->getStyle("Q" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $start++;
            $start++;
        }

        $writer = new Xlsx($spreadsheet);
        if($datas['type'] != "demo"){
            if ($name == "AMAZON") {
                $path = '/uploads/exports/AMAZON';
            }else{
                $path = '/uploads/exports/' . str_replace(__CLASS__ . '::', "", __METHOD__);
            }

        }else{
            if ($name == "AMAZON")
                $path = '/uploads/demo/AMAZON' ;
            else
                $path = '/uploads/demo/' . str_replace(__CLASS__ . '::', "", __METHOD__);
        }
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }
        $path = $path . '/' . date('Y-m-d', $this->date_export);
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        if ($name == "AMAZON") {//AMAZONの注文分[MONTH]月[DAY]日
            $filename = 'の注文分' . date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日';
        } else {
            //株式会社三島食鶏-様-07月01日注文分
            $filename = 'スパイス-' . date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日注文分';
        }

        $path = $path . '/' . $filename;
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        $pathZip = $path . '/zip';
        if ($this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->deleteDirectory(public_path() . $pathZip, true);
        }
        if (!$this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->makeDirectory(public_path() . $pathZip);
        }
        $path2 = $pathZip . '/' . $filename . '.xlsx';
        $writer->save(public_path() . $path2);
        $files = [
            [$filename . '.xlsx', public_path() . $path2]
        ];
        foreach ($images as $image) {
            if (!empty($image[0]) && file_exists(public_path() . "/" . $image[0])) {

                $pathinfo = pathinfo(public_path() . "/" . $image[0]);

                if (empty($image[1])) {
                    $file_image = $pathinfo['filename'] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $pathinfo['filename'] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    $file_image = $image[1] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $image[1] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                }

                $this->file->copy(public_path() . "/" . $image[0], public_path() . '/' . $newName);
                $files[] = [
                    $file_image, public_path() . '/' . $newName
                ];
            }
        }
        $zipFileName = $filename . '.zip';
        $zip = new \ZipArchive();
        if ($this->file->exists(public_path() . '/' . $path . '/' . $zipFileName)) {
            $this->file->delete(public_path() . '/' . $path . '/' . $zipFileName);
        }
        if ($zip->open(public_path() . '/' . $path . '/' . $zipFileName, \ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $zip->addFile($file[1], $file[0]);
            }
            $zip->close();
        }
//        if( $this->file->isDirectory(public_path().$pathZip)){
//            $this->file->deleteDirectory(public_path().$pathZip,true);
//        }
        return ['link' => url($path . '/' . $zipFileName).'?time='.time().'-'.rand(1000,9999), 'images' => $images, "ids" => $ids];

    }

    public function HOA_QUA($datas, $name, $formatFileName)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->createSheet();

        $sheet->setTitle("Sheet1");

        $sheet1 = $spreadsheet->getSheet(1);
        $sheet1->setTitle('Sheet2');

        $spreadsheet->getProperties()
            ->setTitle('PHP Download Example')
            ->setSubject('A PHPExcel example')
            ->setDescription('A simple example for PhpSpreadsheet. This class replaces the PHPExcel class')
            ->setCreator('php-download.com')
            ->setLastModifiedBy('php-download.com');
        $title1 = "株式会社三島食鶏様 注文フォーマット";
        $title2 = "見本";
        $info = "";
        $sheet->setCellValue('B1', $title1);
        $sheet->setCellValue('F2', $title2);
        $sheet->setCellValue('P2', $info);
        $styleArray = array(
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            ));
        $style_header = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'color' => array('rgb' => 'ffff00'),
            ),
            'borders' => [
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_DOTTED,
                    'color' => array('rgb' => '000000')
                ),
            ],
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            )
        );
        $sheet->getStyle('B1')->applyFromArray($styleArray);
        $sheet->getStyle('F2')->applyFromArray($styleArray);
        $sheet->getStyle('P2')->applyFromArray(array(
                'font' => array(
                    'size' => 9,
                    'name' => 'ＭＳ Ｐゴシック',
                    'color' => array('rgb' => '0070c0'),
                ),
            )
        );

        $date_export = new \stdClass();
        $date_export->date = $this->date_export;
        $date_nhan = new \stdClass();
        $date_nhan->date = $this->date;
        $columns_value = array_flip($datas['columns']);

        $images = [];
        $postions = [2,1];
        $start = 3;
        $logs = [];
        $ids = [];
        for ($typeMethod1 = 0; $typeMethod1 < count($postions); $typeMethod1++) {
            $typeMethod = $postions[$typeMethod1];
            $sheet->getStyle('A'.$start.':T'.$start)->applyFromArray($style_header);

            $colums = [
                ["注文日", ['callback' => function ($index, $date) use ($date_export) {
                    return date("d", $date_export->date) . '日';
                }, 'key' => 'timeCreate'], 3.29, 9],//A
                ["支払区分", 'payMethod', 6.57, 9],//B
                ["配送先電話番号", 'phone', 10.86, 9],//C
                ["配送先郵便番号", 'zipcode', 6.57, 9],//D
                ["配送先都道府県", 'province', 5.14, 9],//E
                ["配送先住所", ['callback' => function ($index, $value) {
                    return preg_replace('/\s+/', '', $value);
                }, 'key' => 'address'], 28.71, 9],//F
                ["配送先氏名", ['callback' => function ($index, $value) {
                    return preg_replace('/\s+/', ' ', $value);
                }, 'key' => 'fullname'], 14.71, 9],//G
                ["品番", ['product' => ['product_id', 'code']], 7, 9],//H
                ["商品名", ['product' => ['product_name', 'title']], 18.57, 9],//I
                ["単価", 'price', 4.57, 9],//J
                ["数量", 'count', 2.86, 9],//K
                ["到着希望日", ['callback' => function ($index, $date) use ($date_nhan) {
                    return date("Y/m/d", strtotime($date));
                }, 'key' => 'order_date'], 9, 9],//L
                ["配送希望時間帯", ['callback' => function ($index, $value) {
                    return "8:00 ~ 12:00" == $value ? "午前中" : $value;
                }, 'key' => 'order_hours'], 10, 9],//M
                ["別途送料", 'order_ship', 4.71, 9],//N
                ["仕入金額", 'order_total_price', 6.43, 9],//O
                ["振込請求金額", 'order_total_price_buy', 8, 9],//P
                ["代引き手数料", 'order_ship_cou', 3.43, 9],//Q
                ["余分金", ['callback' => function ($index, $date, $a, $val) use ($date_export, $columns_value) {
                    if ($val[$columns_value['payMethod']] == "決済不要") {
                        return "=0-O$index";
                    } else {
                        return "=P$index-J$index*K$index-N$index-Q$index";
                    }

                }, 'key' => 'order_price'], 5.43, 9],//R =IF(J4="","",P4-J4*K4-N4-Q4) P4-J4*K4-N4-Q4
                ["追跡番号", 'order_tracking', 4.86, 9],//S
                ["振込み情報", ['callback' => function ($index, $value) use ($date_export) {
                    return str_replace('依頼人名.', '', $value);
                }, 'key' => 'order_info'], 8.57, 9],//T
                ["", 'comment', 15, 9], // Số lượng O
//            ["",'order_link',25,9],//U
            ];

            $nameColList = [];
            foreach ($colums as $key => $value) {
                $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
                $keyCol = "";
                if (is_array($value[1])) {
                    if (isset($value[1]['product'])) {
                        $conf = $value[1]['product'];
                        $nameColList[$conf[0]] = $key;
                        $keyCol = $conf[1];
                    } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                        $nameColList[$value[1]['key']] = $key;
                        $keyCol = $value[1]['key'];
                    }
                } else {
                    $nameColList[$value[1]] = $key;
                    $keyCol = $value[1];
                }
                $sheet->setCellValue($nameCol . $start, $value[0])->getStyle($nameCol . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック'
                        ),
                    )
                );
                if ($keyCol == "title") {
                    $keyCol = "product_name";
                } else if ($keyCol == "code") {
                    $keyCol = "product_id";
                }
                if (isset($this->config["excel_width"][$name][$keyCol])) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($this->config["excel_width"][$name][$keyCol] + 0.72);
                } else if ($value[2] > 0) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($value[2] + 0.72);
                }

            }
            $start++;

            $defaultStart = $start;
            $lastIndex = "";

            $company = "22";
            $orders = [

            ];


            $products = DB::table('shop_product')->get()->keyBy('id')->all();


            foreach ($datas['datas'] as $key => $values) {
                $payMethod = (isset($columns_value['payMethod']) ? $values[$columns_value['payMethod']] : "");
                if (empty($values[$columns_value['fullname']])) {
                    continue;
                }
                if(isset($logs[$key])){
                    continue;
                }
                $_checktypeMethod = $this->getValuePayMethod($payMethod);
                if ($typeMethod != $_checktypeMethod) {
                    if( $typeMethod == 2 && $_checktypeMethod == 3){

                    }else{
                        continue;
                    }
                }
                $logs[$key] = 1;

                $image = (isset($columns_value['image']) ? $values[$columns_value['image']] : "");
                $order_id = (isset($columns_value['id']) ? $values[$columns_value['id']] : "");

                $ids[$order_id] = 1;
                $order_info = (isset($columns_value['order_info']) ? $values[$columns_value['order_info']] : "");
                if (!empty($image)) {
                    $images[] = [str_replace(url('/'), "", $image), str_replace('依頼人名.', '', $order_info)];
                }

                $count = (isset($columns_value['count']) ? $values[$columns_value['count']] : "0");
                $sheet1->setCellValue("A" . $start, $order_id);
                $sheet1->setCellValue("B" . $start, $count);

                foreach ($colums as $key => $value) {
                    $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
                    if (is_array($value[1])) {
                        if (isset($value[1]['product'])) {
                            $conf = $value[1]['product'];
                            $id = (isset($columns_value[$conf[0]]) ? $values[$columns_value[$conf[0]]] : "");
                            $_val = "";
                            if (isset($products[$id]) && property_exists($products[$id], $conf[1])) {
                                $_val = $products[$id]->{$conf[1]};
                            }
                            if ($_val == "0") {
                                //$_val = "";
                            }
                            $sheet->setCellValue($nameCol . $start, trim($_val));
                        } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                            $conf = $value[1]['callback'];
                            $_val = call_user_func_array($conf, [$start, (isset($columns_value[$value[1]['key']]) ? $values[$columns_value[$value[1]['key']]] : ""), $nameCol . $start, $values]);
                            if ($_val == "0") {
                                // $_val = "";
                            }
                            $sheet->setCellValue($nameCol . $start, trim($_val));
                        }
                    } else {
                        $v = (isset($columns_value[$value[1]]) ? $values[$columns_value[$value[1]]] : "");
                        if ($v == "0") {
                            //  $v = "";
                        }
                        $sheet->setCellValue($nameCol . $start, trim($v));
                        if ($value[1] == "payMethod") {
                            $payMethod = $v;
                        }
                    }
                }
                if ($payMethod == "銀行振込" || $payMethod == "決済不要") {
                    $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',
                            'color' => array('rgb' => '0070c0'),
                        ),
                    ));
                } else if ($payMethod == "決済不要") {
                    $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',
                            'color' => array('rgb' => 'ff0000'),
                        ),
                    ));
                } else {
                    $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',

                        ),
                    ));
                }
                $start++;
            }
            $sheet->setCellValue("O" . $start, "=SUM(O" . $defaultStart . ":O" . ($start - 1) . ")");
            $sheet->setCellValue("N" . $start, "=SUM(N" . $defaultStart . ":N" . ($start - 1) . ")");

            $sheet->setCellValue("K" . $start, "=SUM(K" . $defaultStart . ":K" . ($start - 1) . ")");
            $sheet->setCellValue("P" . $start, "=SUM(P" . $defaultStart . ":P" . ($start - 1) . ")");
            $sheet->setCellValue("R" . $start, "=SUM(R" . $defaultStart . ":R" . ($start - 1) . ")");
            $sheet->setCellValue("Q" . $start, "=SUM(Q" . $defaultStart . ":Q" . ($start - 1) . ")");

            $sheet->getStyle("O" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $sheet->getStyle("N" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );

            $sheet->getStyle("K" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );

            $sheet->getStyle("P" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $sheet->getStyle("R" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $sheet->getStyle("Q" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $start++;
            $start++;
        }

        $writer = new Xlsx($spreadsheet);
        if($datas['type'] != "demo"){
            if ($name == "AMAZON") {
                $path = '/uploads/exports/AMAZON';
            }else{
                $path = '/uploads/exports/' . str_replace(__CLASS__ . '::', "", __METHOD__);
            }

        }else{
            if ($name == "AMAZON")
                $path = '/uploads/demo/AMAZON' ;
            else
                $path = '/uploads/demo/' . str_replace(__CLASS__ . '::', "", __METHOD__);
        }
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }
        $path = $path . '/' . date('Y-m-d', $this->date_export);
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        if ($name == "AMAZON") {//AMAZONの注文分[MONTH]月[DAY]日
            $filename = 'の注文分' . date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日';
        } else {
            //株式会社三島食鶏-様-07月01日注文分
            $filename = '果物-' . date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日注文分';
        }

        $path = $path . '/' . $filename;
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        $pathZip = $path . '/zip';
        if ($this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->deleteDirectory(public_path() . $pathZip, true);
        }
        if (!$this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->makeDirectory(public_path() . $pathZip);
        }
        $path2 = $pathZip . '/' . $filename . '.xlsx';
        $writer->save(public_path() . $path2);
        $files = [
            [$filename . '.xlsx', public_path() . $path2]
        ];
        foreach ($images as $image) {
            if (!empty($image[0]) && file_exists(public_path() . "/" . $image[0])) {

                $pathinfo = pathinfo(public_path() . "/" . $image[0]);

                if (empty($image[1])) {
                    $file_image = $pathinfo['filename'] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $pathinfo['filename'] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    $file_image = $image[1] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $image[1] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                }

                $this->file->copy(public_path() . "/" . $image[0], public_path() . '/' . $newName);
                $files[] = [
                    $file_image, public_path() . '/' . $newName
                ];
            }
        }
        $zipFileName = $filename . '.zip';
        $zip = new \ZipArchive();
        if ($this->file->exists(public_path() . '/' . $path . '/' . $zipFileName)) {
            $this->file->delete(public_path() . '/' . $path . '/' . $zipFileName);
        }
        if ($zip->open(public_path() . '/' . $path . '/' . $zipFileName, \ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $zip->addFile($file[1], $file[0]);
            }
            $zip->close();
        }
//        if( $this->file->isDirectory(public_path().$pathZip)){
//            $this->file->deleteDirectory(public_path().$pathZip,true);
//        }
        return ['link' => url($path . '/' . $zipFileName).'?time='.time().'-'.rand(1000,9999), 'images' => $images, "ids" => $ids];

    }

    public function QUAT($datas, $name, $formatFileName)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->createSheet();

        $sheet->setTitle("Sheet1");

        $sheet1 = $spreadsheet->getSheet(1);
        $sheet1->setTitle('Sheet2');

        $spreadsheet->getProperties()
            ->setTitle('PHP Download Example')
            ->setSubject('A PHPExcel example')
            ->setDescription('A simple example for PhpSpreadsheet. This class replaces the PHPExcel class')
            ->setCreator('php-download.com')
            ->setLastModifiedBy('php-download.com');
        $title1 = "株式会社三島食鶏様 注文フォーマット";
        $title2 = "見本";
        $info = "";
        $sheet->setCellValue('B1', $title1);
        $sheet->setCellValue('F2', $title2);
        $sheet->setCellValue('P2', $info);
        $styleArray = array(
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            ));
        $style_header = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'color' => array('rgb' => 'ffff00'),
            ),
            'borders' => [
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_DOTTED,
                    'color' => array('rgb' => '000000')
                ),
            ],
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            )
        );
        $sheet->getStyle('B1')->applyFromArray($styleArray);
        $sheet->getStyle('F2')->applyFromArray($styleArray);
        $sheet->getStyle('P2')->applyFromArray(array(
                'font' => array(
                    'size' => 9,
                    'name' => 'ＭＳ Ｐゴシック',
                    'color' => array('rgb' => '0070c0'),
                ),
            )
        );

        $date_export = new \stdClass();
        $date_export->date = $this->date_export;
        $date_nhan = new \stdClass();
        $date_nhan->date = $this->date;
        $columns_value = array_flip($datas['columns']);

        $images = [];
        $postions = [2,1];
        $start = 3;
        $logs = [];
        $ids = [];
        for ($typeMethod1 = 0; $typeMethod1 < count($postions); $typeMethod1++) {
            $typeMethod = $postions[$typeMethod1];
            $sheet->getStyle('A'.$start.':T'.$start)->applyFromArray($style_header);

            $colums = [
                ["注文日", ['callback' => function ($index, $date) use ($date_export) {
                    return date("d", $date_export->date) . '日';
                }, 'key' => 'timeCreate'], 3.29, 9],//A
                ["支払区分", 'payMethod', 6.57, 9],//B
                ["配送先電話番号", 'phone', 10.86, 9],//C
                ["配送先郵便番号", 'zipcode', 6.57, 9],//D
                ["配送先都道府県", 'province', 5.14, 9],//E
                ["配送先住所", ['callback' => function ($index, $value) {
                    return preg_replace('/\s+/', '', $value);
                }, 'key' => 'address'], 28.71, 9],//F
                ["配送先氏名", ['callback' => function ($index, $value) {
                    return preg_replace('/\s+/', ' ', $value);
                }, 'key' => 'fullname'], 14.71, 9],//G
                ["品番", ['product' => ['product_id', 'code']], 7, 9],//H
                ["商品名", ['product' => ['product_name', 'title']], 18.57, 9],//I
                ["単価", 'price', 4.57, 9],//J
                ["数量", 'count', 2.86, 9],//K
                ["到着希望日", ['callback' => function ($index, $date) use ($date_nhan) {
                    return date("Y/m/d", strtotime($date));
                }, 'key' => 'order_date'], 9, 9],//L
                ["配送希望時間帯", ['callback' => function ($index, $value) {
                    return "8:00 ~ 12:00" == $value ? "午前中" : $value;
                }, 'key' => 'order_hours'], 10, 9],//M
                ["別途送料", 'order_ship', 4.71, 9],//N
                ["仕入金額", 'order_total_price', 6.43, 9],//O
                ["振込請求金額", 'order_total_price_buy', 8, 9],//P
                ["代引き手数料", 'order_ship_cou', 3.43, 9],//Q
                ["余分金", ['callback' => function ($index, $date, $a, $val) use ($date_export, $columns_value) {
                    if ($val[$columns_value['payMethod']] == "決済不要") {
                        return "=0-O$index";
                    } else {
                        return "=P$index-J$index*K$index-N$index-Q$index";
                    }

                }, 'key' => 'order_price'], 5.43, 9],//R =IF(J4="","",P4-J4*K4-N4-Q4) P4-J4*K4-N4-Q4
                ["追跡番号", 'order_tracking', 4.86, 9],//S
                ["振込み情報", ['callback' => function ($index, $value) use ($date_export) {
                    return str_replace('依頼人名.', '', $value);
                }, 'key' => 'order_info'], 8.57, 9],//T
                ["", 'comment', 15, 9], // Số lượng O
//            ["",'order_link',25,9],//U
            ];

            $nameColList = [];
            foreach ($colums as $key => $value) {
                $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
                $keyCol = "";
                if (is_array($value[1])) {
                    if (isset($value[1]['product'])) {
                        $conf = $value[1]['product'];
                        $nameColList[$conf[0]] = $key;
                        $keyCol = $conf[1];
                    } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                        $nameColList[$value[1]['key']] = $key;
                        $keyCol = $value[1]['key'];
                    }
                } else {
                    $nameColList[$value[1]] = $key;
                    $keyCol = $value[1];
                }
                $sheet->setCellValue($nameCol . $start, $value[0])->getStyle($nameCol . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック'
                        ),
                    )
                );
                if ($keyCol == "title") {
                    $keyCol = "product_name";
                } else if ($keyCol == "code") {
                    $keyCol = "product_id";
                }
                if (isset($this->config["excel_width"][$name][$keyCol])) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($this->config["excel_width"][$name][$keyCol] + 0.72);
                } else if ($value[2] > 0) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($value[2] + 0.72);
                }

            }
            $start++;

            $defaultStart = $start;
            $lastIndex = "";

            $company = "22";
            $orders = [

            ];


            $products = DB::table('shop_product')->get()->keyBy('id')->all();


            foreach ($datas['datas'] as $key => $values) {
                $payMethod = (isset($columns_value['payMethod']) ? $values[$columns_value['payMethod']] : "");
                if (empty($values[$columns_value['fullname']])) {
                    continue;
                }
                if(isset($logs[$key])){
                    continue;
                }
                $_checktypeMethod = $this->getValuePayMethod($payMethod);
                if ($typeMethod != $_checktypeMethod) {
                    if( $typeMethod == 2 && $_checktypeMethod == 3){

                    }else{
                        continue;
                    }
                }
                $logs[$key] = 1;

                $image = (isset($columns_value['image']) ? $values[$columns_value['image']] : "");
                $order_id = (isset($columns_value['id']) ? $values[$columns_value['id']] : "");

                $ids[$order_id] = 1;
                $order_info = (isset($columns_value['order_info']) ? $values[$columns_value['order_info']] : "");
                if (!empty($image)) {
                    $images[] = [str_replace(url('/'), "", $image), str_replace('依頼人名.', '', $order_info)];
                }

                $count = (isset($columns_value['count']) ? $values[$columns_value['count']] : "0");
                $sheet1->setCellValue("A" . $start, $order_id);
                $sheet1->setCellValue("B" . $start, $count);

                foreach ($colums as $key => $value) {
                    $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
                    if (is_array($value[1])) {
                        if (isset($value[1]['product'])) {
                            $conf = $value[1]['product'];
                            $id = (isset($columns_value[$conf[0]]) ? $values[$columns_value[$conf[0]]] : "");
                            $_val = "";
                            if (isset($products[$id]) && property_exists($products[$id], $conf[1])) {
                                $_val = $products[$id]->{$conf[1]};
                            }
                            if ($_val == "0") {
                                //$_val = "";
                            }
                            $sheet->setCellValue($nameCol . $start, trim($_val));
                        } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                            $conf = $value[1]['callback'];
                            $_val = call_user_func_array($conf, [$start, (isset($columns_value[$value[1]['key']]) ? $values[$columns_value[$value[1]['key']]] : ""), $nameCol . $start, $values]);
                            if ($_val == "0") {
                                // $_val = "";
                            }
                            $sheet->setCellValue($nameCol . $start, trim($_val));
                        }
                    } else {
                        $v = (isset($columns_value[$value[1]]) ? $values[$columns_value[$value[1]]] : "");
                        if ($v == "0") {
                            //  $v = "";
                        }
                        $sheet->setCellValue($nameCol . $start, trim($v));
                        if ($value[1] == "payMethod") {
                            $payMethod = $v;
                        }
                    }
                }
                if ($payMethod == "銀行振込" || $payMethod == "決済不要") {
                    $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',
                            'color' => array('rgb' => '0070c0'),
                        ),
                    ));
                } else if ($payMethod == "決済不要") {
                    $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',
                            'color' => array('rgb' => 'ff0000'),
                        ),
                    ));
                } else {
                    $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',

                        ),
                    ));
                }
                $start++;
            }
            $sheet->setCellValue("O" . $start, "=SUM(O" . $defaultStart . ":O" . ($start - 1) . ")");
            $sheet->setCellValue("N" . $start, "=SUM(N" . $defaultStart . ":N" . ($start - 1) . ")");

            $sheet->setCellValue("K" . $start, "=SUM(K" . $defaultStart . ":K" . ($start - 1) . ")");
            $sheet->setCellValue("P" . $start, "=SUM(P" . $defaultStart . ":P" . ($start - 1) . ")");
            $sheet->setCellValue("R" . $start, "=SUM(R" . $defaultStart . ":R" . ($start - 1) . ")");
            $sheet->setCellValue("Q" . $start, "=SUM(Q" . $defaultStart . ":Q" . ($start - 1) . ")");

            $sheet->getStyle("O" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $sheet->getStyle("N" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );

            $sheet->getStyle("K" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );

            $sheet->getStyle("P" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $sheet->getStyle("R" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $sheet->getStyle("Q" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $start++;
            $start++;
        }

        $writer = new Xlsx($spreadsheet);
        if($datas['type'] != "demo"){
            if ($name == "AMAZON") {
                $path = '/uploads/exports/AMAZON';
            }else{
                $path = '/uploads/exports/' . str_replace(__CLASS__ . '::', "", __METHOD__);
            }

        }else{
            if ($name == "AMAZON")
                $path = '/uploads/demo/AMAZON' ;
            else
                $path = '/uploads/demo/' . str_replace(__CLASS__ . '::', "", __METHOD__);
        }
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }
        $path = $path . '/' . date('Y-m-d', $this->date_export);
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        if ($name == "AMAZON") {//AMAZONの注文分[MONTH]月[DAY]日
            $filename = 'の注文分' . date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日';
        } else {
            //株式会社三島食鶏-様-07月01日注文分
            $filename = '嵩原竹子 様 (沖縄シークヮーサー)' . date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日注文分';
        }

        $path = $path . '/' . $filename;
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        $pathZip = $path . '/zip';
        if ($this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->deleteDirectory(public_path() . $pathZip, true);
        }
        if (!$this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->makeDirectory(public_path() . $pathZip);
        }
        $path2 = $pathZip . '/' . $filename . '.xlsx';
        $writer->save(public_path() . $path2);
        $files = [
            [$filename . '.xlsx', public_path() . $path2]
        ];
        foreach ($images as $image) {
            if (!empty($image[0]) && file_exists(public_path() . "/" . $image[0])) {

                $pathinfo = pathinfo(public_path() . "/" . $image[0]);

                if (empty($image[1])) {
                    $file_image = $pathinfo['filename'] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $pathinfo['filename'] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    $file_image = $image[1] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $image[1] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                }

                $this->file->copy(public_path() . "/" . $image[0], public_path() . '/' . $newName);
                $files[] = [
                    $file_image, public_path() . '/' . $newName
                ];
            }
        }
        $zipFileName = $filename . '.zip';
        $zip = new \ZipArchive();
        if ($this->file->exists(public_path() . '/' . $path . '/' . $zipFileName)) {
            $this->file->delete(public_path() . '/' . $path . '/' . $zipFileName);
        }
        if ($zip->open(public_path() . '/' . $path . '/' . $zipFileName, \ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $zip->addFile($file[1], $file[0]);
            }
            $zip->close();
        }
//        if( $this->file->isDirectory(public_path().$pathZip)){
//            $this->file->deleteDirectory(public_path().$pathZip,true);
//        }
        return ['link' => url($path . '/' . $zipFileName).'?time='.time().'-'.rand(1000,9999), 'images' => $images, "ids" => $ids];

    }



    public function KURICHIKU($datas,$name, $formatFileName)
    {
        //$name = "KURICHIKU";
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->createSheet();
        $sheet1 = $spreadsheet->getSheet(1);
        $sheet1->setTitle('Sheet2');

        $sheet->setTitle("Sheet1");
        $spreadsheet->getProperties()
            ->setTitle('PHP Download Example')
            ->setSubject('A PHPExcel example')
            ->setDescription('A simple example for PhpSpreadsheet. This class replaces the PHPExcel class')
            ->setCreator('php-download.com')
            ->setLastModifiedBy('php-download.com');

        //$sheet->setCellValue('B1', '株式会社クリチク　様 注文フォーマット');


        $styleArray = array(
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            ));
        $style_header = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'color' => array('rgb' => 'ffff00'),
            ),
            'borders' => [
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_DOTTED,
                    'color' => array('rgb' => '000000')
                ),
            ],
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            )
        );
        $sheet->getStyle('B1')->applyFromArray($styleArray);
        $start = 2;
        $products = DB::table('shop_product')->get()->keyBy('id')->all();
        $images = [];
        $ids = [];
        $date_export = new \stdClass();
        $date_export->date = $this->date_export;
        $_dateNhan = new \stdClass();
        $_dateNhan->date = $this->date;
        $postions = [2,1];

        $logs = [];
        for ($typeMethod1 = 0; $typeMethod1 < count($postions); $typeMethod1++) {
            $typeMethod = $postions[$typeMethod1];
            $total_order_ship = 0;
            $total_order_total_price = 0;
            $total_order_total_price_buy = 0;
            $total_ship_cou = 0;
            $total_order_price = 0;
            $columns_value = array_flip($datas['columns']);
            $colums = [
                ["注文日",['callback'=>function($index,$date) use($date_export){return date("d", $date_export->date).'日';},'key'=>'timeCreate'],10,9],//A
                ["支払区分",'payMethod',10,9],//Phương thức thanh toán
                ["配送先電話番号",'phone',10,9],//Số điện thoại
                ["郵便番号",'zipcode',9,9],//Mã bưu điện
                ["配送先都道府県",'province',14,9],//Tỉnh/TP
                ["配送先住所",['callback'=>function($index,$value){return preg_replace('/\s+/', '',$value );},'key'=>'address'],18,9],//Địa chỉ giao hàng
                ["配送先氏名",['callback'=>function($index,$value){return preg_replace('/\s+/', ' ',$value );},'key'=>'fullname'],18,9],//Họ tên người nhận
                ["品番",['callback'=>
                    function($index,$product_id,$a,$values) use($products,$columns_value){
                    try{
                        $array_product = explode(";",$product_id);
                    }catch (\Exception $ex) {
                        $array_product = [];
                    }
                    $product_code = "";$product_title = "";
                    $count = (isset($columns_value["count"])?$values[$columns_value["count"]]:"");
                    try{
                        $array_count = json_decode($count,true);
                    }catch (\Exception $ex) {
                        $array_count = [];
                    }
                    foreach ($array_product as $pro_id){
                            if(isset( $products[$pro_id]) && isset($array_count[$pro_id]) && $array_count[$pro_id] > 0){
                                $product_code.= $products[$pro_id]->code.",";
                                $product_title.= $products[$pro_id]->title.",";
                            }
                    }
                    return rtrim($product_code,',');
                    },'key'=>'product_id'],10,9
                ],//H
                ["商品名",
                    ['callback' => function ($index, $product_id, $a, $values) use ($products, $columns_value) {
                        try {
                            $array_product = explode(";", $product_id);
                        } catch (\Exception $ex) {
                            $array_product = [];
                        }
                        $count = (isset($columns_value["count"]) ? $values[$columns_value["count"]] : "");
                        try {
                            $array_count = json_decode($count, true);
                        } catch (\Exception $ex) {
                            $array_count = [];
                        }
                        $product_title = "";

                        foreach ($array_product as $pro_id) {
                            if (isset($products[$pro_id]) && isset($array_count[$pro_id]) && $array_count[$pro_id] > 0) {
                                $kg = 0;
                                if (isset($array_count[$pro_id])) {
                                    $kg = $array_count[$pro_id];
                                }
                                if ($products[$pro_id]->unit == 1) {
                                    $product_title .= $products[$pro_id]->title . " " . $kg . " cái" . '、';
                                }else if ($products[$pro_id]->unit == 5) {
                                    $product_title .= str_replace('鶏羽', "鶏" . $kg . "羽", $products[$pro_id]->title) . '、';
                                } else if ($products[$pro_id]->unit == 4) {
                                    $product_title .= $products[$pro_id]->title . '、';
                                } else {
                                    $product_title .= $products[$pro_id]->title . " " . $kg . "kg" . '、';
                                }
                            }
                        }

                        return rtrim($product_title, '、');
                    }, 'key' => 'product_name'], 18, 9],//I
                ["単価", 'price', 15, 9],//Giá nhập
                ["数量", "total_count", 15, 9],//SL
                ["到着希望日", ['callback' => function ($index, $date) use ($_dateNhan) {
                    return date("Y/m/d", strtotime($date));
                }, 'key' => 'order_date'], 15, 9],//Ngày nhận
                ["配送希望時間帯", ['callback' => function ($index, $value) {
                    return "8:00 ~ 12:00" == $value ? "午前中" : $value;
                }, 'key' => 'order_hours'], 15, 9],//Giờ nhận
                ["送料", 'order_ship', 15, 9],//Phí ship
                ["仕入金額", 'order_total_price', 15, 9],//Giá bán
                ["代引き請求金額", 'order_total_price_buy', 15, 9],//Giá bán  振込み金額
                ["手数料", 'order_ship_cou', 15, 9],
                ["余分金", ['callback' => function ($index, $val) use ($_dateNhan) {
                    return "=P$index-N$index-O$index-Q$index";//$val;
                }, 'key' => 'order_price'], 15, 9],
                ["追跡番号", 'order_tracking', 15, 9],
                ["振込み情報",['callback' => function ($index, $value) use ($date_export) {
                    return str_replace('依頼人名.','',$value);
                }, 'key' => 'order_info'], 25, 9],
                ["", 'comment', 15, 9], // Số lượng O
            ];
            $nameColList = [];

            foreach ($colums as $key => $value) {
                $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
                $keyCol = "";
                if (is_array($value[1])) {
                    if (isset($value[1]['product'])) {
                        $conf = $value[1]['product'];
                        $nameColList[$conf[0]] = $key;
                        $keyCol = $conf[1];
                    } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                        $nameColList[$value[1]['key']] = $key;
                        $keyCol = $value[1]['key'];
                    }
                } else {
                    $nameColList[$value[1]] = $key;
                    $keyCol = $value[1];
                }
                $sheet->setCellValue($nameCol . $start, $value[0])->getStyle($nameCol . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック'
                        ),
                    )
                );
                if($keyCol == "title"){
                    $keyCol = "product_name";
                }else  if($keyCol == "code"){
                    $keyCol = "product_id";
                }
                if (isset($this->config["excel_width"][$name][$keyCol])) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($this->config["excel_width"][$name][$keyCol]+0.72);
                } else if ($value[2] > 0) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($value[2]+0.72);
                }

            }

            $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . $start)->applyFromArray($style_header);
//            $ship = 34;
//            $datas = [];
//            $company = "22";
//            $orders = [
//
//            ];
            $sheet->getStyle("P" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $start++;

            $products = DB::table('shop_product')->get()->keyBy('id')->all();
            $startIndex = $start;
            foreach ($datas['datas'] as $key => $values) {
                $payMethod = (isset($columns_value['payMethod']) ? $values[$columns_value['payMethod']] : "");

                if (empty($values[$columns_value['fullname']])) {
                    continue;
                }
                if(isset($logs[$key])){
                    continue;
                }
                $_checktypeMethod = $this->getValuePayMethod($payMethod);
                if ($typeMethod != $_checktypeMethod) {
                    if( $typeMethod == 2 && $_checktypeMethod == 3){

                    }else{
                        continue;
                    }
                }
                $logs[$key] = 1;
                $order_id = (isset($columns_value['id']) ? $values[$columns_value['id']] : "");
                $ids[$order_id] = 1;
                $image = (isset($columns_value['image']) ? $values[$columns_value['image']] : "");

                $order_info = (isset($columns_value['order_info']) ? $values[$columns_value['order_info']] : "");

                if (!empty($image)) {
                    $images[] = [str_replace(url('/'), "", $image), str_replace('依頼人名.','',$order_info)];
                }
                $total_order_ship += (int)(isset($columns_value['order_ship']) ? $values[$columns_value['order_ship']] : "0");
                $total_order_total_price += (int)(isset($columns_value['order_total_price']) ? $values[$columns_value['order_total_price']] : "0");
                $total_order_total_price_buy += (int)(isset($columns_value['order_total_price_buy']) ? $values[$columns_value['order_total_price_buy']] : "0");
                $total_ship_cou += (int)(isset($columns_value['order_ship_cou']) ? $values[$columns_value['order_ship_cou']] : "0");
                $total_order_price += (int)(isset($columns_value['order_price']) ? $values[$columns_value['order_price']] : "0");

                $count = (isset($columns_value['count']) ? $values[$columns_value['total_count']] : "0");
                $sheet1->setCellValue("A".$start,$order_id);
                $sheet1->setCellValue("B".$start,$count);

                foreach ($colums as $key1 => $value) {
                    $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key1 + 1);
                    $_val = "";
                    if (is_array($value[1])) {
                        if (isset($value[1]['product'])) {
                            $conf = $value[1]['product'];
                            $id = (isset($columns_value[$conf[0]]) ? $values[$columns_value[$conf[0]]] : "");

                            if (isset($products[$id]) && property_exists($products[$id], $conf[1])) {
                                $_val = $products[$id]->{$conf[1]};
                            }
                            $sheet->setCellValue($nameCol . $start, trim($_val));
                        } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                            $conf = $value[1]['callback'];
                            $_val = call_user_func_array($conf, [$start, (isset($columns_value[$value[1]['key']]) ? $values[$columns_value[$value[1]['key']]] : ""), $nameCol . $start, $values]);

                            $sheet->setCellValue($nameCol . $start, trim($_val));
                        }
                    } else {
                        $v = (isset($columns_value[$value[1]]) ? $values[$columns_value[$value[1]]] : "");
                        $sheet->setCellValue($nameCol . $start, trim($v));
                        $_val = $v;
                        if ($value[1] == "payMethod") {
                            $payMethod = $v;
                        }
                    }


                }

                $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                    ),
                ));

                if ($payMethod == "銀行振込" || $payMethod == "決済不要") {
                    foreach (["B", "H", "I", "J", "S"] as $col) {
                        $sheet->getStyle($col . $start)->applyFromArray(array(
                            'font' => array(
                                'color' => array('rgb' => '0070c0'),
                            ),
                        ));
                    }

                } else if ($payMethod == "決済不要") {
                    foreach (["B", "H", "I", "J", "S"] as $col) {
                        $sheet->getStyle($col . $start)->applyFromArray(array(
                            'font' => array(
                                'color' => array('rgb' => 'ff0000'),
                            ),
                        ));
                    }

                } else {
                    foreach (["B", "H", "I", "J", "S"] as $col) {
                        $sheet->getStyle($col . $start)->applyFromArray(array(
                            'font' => array(
                                'color' => array('rgb' => '0070c0'),
                            ),
                        ));
                    }
                }
                $start++;
            }

            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nameColList['order_ship'] + 1);
            $sheet->setCellValue($nameCol . $start, "=SUM($nameCol$startIndex:".$nameCol.($start-1).")");
            $sheet->getStyle($nameCol . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nameColList['order_total_price'] + 1);
            $sheet->setCellValue($nameCol . $start, "=SUM($nameCol$startIndex:".$nameCol.($start-1).")");
            $sheet->getStyle($nameCol . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nameColList['order_total_price_buy'] + 1);
            $sheet->setCellValue($nameCol . $start, "=SUM($nameCol$startIndex:".$nameCol.($start-1).")");
            $sheet->getStyle($nameCol . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );

            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nameColList['order_ship_cou'] + 1);
            $sheet->setCellValue($nameCol . $start, "=SUM($nameCol$startIndex:".$nameCol.($start-1).")");
            $sheet->getStyle($nameCol . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nameColList['order_price'] + 1);
            $sheet->setCellValue($nameCol . $start, "=SUM($nameCol$startIndex:".$nameCol.($start-1).")");
            $sheet->getStyle($nameCol . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );

            $start++;
            if ($typeMethod == 0) {
//                $start+=2;
//                $sheet->setCellValue('N'.$start, '合計');
//                $sheet->setCellValue('P'.$start, '合計代引き金額：　19330');

//                $sheet->getStyle('N'.$start)->applyFromArray(array(
//                        'font'  => array(
//                            'size'  => 9,
//                            'name' => 'Times New Roman',
//                            'color' => array('rgb' => 'ff1100'),
//                        ),
//                    )
//                );
//                $sheet->getStyle('P'.$start)->applyFromArray(array(
//                        'font'  => array(
//                            'size'  => 9,
//                            'name' => 'Times New Roman',
//                            'color' => array('rgb' => 'ff1100'),
//                        ),
//                    )
//                );
                $start++;
                $sheet->setCellValue('I' . $start, '※1キロずつの小分けをお願いします。');
                $sheet->getStyle('I' . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => 9,
                            'name' => 'ＭＳ Ｐゴシック',
                            'color' => array('rgb' => 'ff1100'),
                        ),
                    )
                );
            }
            $start += 1;
            $dataRow = [];
        }

        $writer = new Xlsx($spreadsheet);
        if($name == "BANH_CHUNG"){
            if($datas['type'] != "demo"){
                $path = '/uploads/exports/BANH_CHUNG';
            }else{
                $path = '/uploads/demo/BANH_CHUNG';
            }
        }else{
            if($datas['type'] != "demo"){
                $path = '/uploads/exports/' . str_replace(__CLASS__ . '::', "", __METHOD__);
            }else{
                $path = '/uploads/demo/' . str_replace(__CLASS__ . '::', "", __METHOD__);
            }
        }

        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        $path = $path . '/' . date('Y-m-d', $this->date_export);
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }
        // 株式会社クリチク-様-11月03日注文分
        //  $filename ='株式会社クリチク-様-'.date('m',$this->date).'月'.date('d',$this->date).'日注文分';
        if($name == "BANH_CHUNG"){//BANH_CHUNGの注文分[MONTH]月[DAY]日
            $filename = '/BANH_CHUNGの注文分' . date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日';
        }else{
            $filename = '株式会社クリチク-様-' . date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日注文分';
        }


        $path = $path . '/' . $filename;
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }
        $pathZip = $path . '/zip';
        if ($this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->deleteDirectory(public_path() . $pathZip, true);
        }
        if (!$this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->makeDirectory(public_path() . $pathZip);
        }
        $path2 = $pathZip . '/' . $filename . '.xlsx';
        $writer->save(public_path() . $path2);
        $files = [
            [$filename . '.xlsx', public_path() . $path2]
        ];
        foreach ($images as $image) {

            if (!empty($image[0]) && file_exists(public_path() . "/" . $image[0])) {

                $pathinfo = pathinfo(public_path() . "/" . $image[0]);

                if (empty($image[1])) {
                    $file_image = $pathinfo['filename'] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $pathinfo['filename'] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    $file_image = $image[1] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $image[1] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                }

                $this->file->copy(public_path() . "/" . $image[0], public_path() . '/' . $newName);
                $files[] = [
                    $file_image, public_path() . '/' . $newName
                ];
            }
        }
        $zipFileName = $filename . '.zip';
        $zip = new \ZipArchive();
        if ($this->file->exists(public_path() . '/' . $path . '/' . $zipFileName)) {
            $this->file->delete(public_path() . '/' . $path . '/' . $zipFileName);
        }
        if ($zip->open(public_path() . '/' . $path . '/' . $zipFileName, \ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $zip->addFile($file[1], $file[0]);
            }
            $zip->close();
        }
//        if( $this->file->isDirectory(public_path().$pathZip)){
//            $this->file->deleteDirectory(public_path().$pathZip,true);
//        }
        return ['link' => url($path . '/' . $zipFileName).'?time='.time().'-'.rand(1000,9999), 'images' => $images, 'ids' => $ids];


    }

    public function KOGYJA($datas)
    {
        $name = "KOGYJA";
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->createSheet();
        $sheet1 = $spreadsheet->getSheet(1);
        $sheet1->setTitle('Sheet2');

        $sheet->setTitle("Sheet1");
        $spreadsheet->getProperties()
            ->setTitle('PHP Download Example')
            ->setSubject('A PHPExcel example')
            ->setDescription('A simple example for PhpSpreadsheet. This class replaces the PHPExcel class')
            ->setCreator('php-download.com')
            ->setLastModifiedBy('php-download.com');


        $style_header = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'color' => array('rgb' => 'ffff00'),
            ),
            'borders' => [
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_DOTTED,
                    'color' => array('rgb' => '000000')
                ),
            ],
            'font' => array(
                'size' => 9,
                'name' => 'ＭＳ Ｐゴシック'
            )
        );

        $start = 2;
        $products = DB::table('shop_product')->get()->keyBy('id')->all();
        $date_export = new \stdClass();
        $date_export->date = $this->date_export;
        $_dateNhan = new \stdClass();
        $_dateNhan->date = $this->date;
        $images = [];
        $ids = [];
        for ($typeMethod = 2; $typeMethod >= 1; $typeMethod--) {
            $styleArray = array(
                'font' => array(
                    'size' => 9,
                    'name' => 'ＭＳ Ｐゴシック',
                    'color' => array('rgb' => 'ff0000'),
                ));
            $sheet->setCellValue( "I".$start, '※1キロずつの小分けをお願いします。');
            $sheet->getStyle("I".$start)->applyFromArray($styleArray);
            $columns_value = array_flip($datas['columns']);
            $start++;
            $colums = [
                ["注文日", ['callback' => function ($index, $date) use ($date_export) {
                    return date("d", $date_export->date) . '日';
                }, 'key' => 'timeCreate'], 10, 9],//A
                ["支払区分", 'payMethod', 10, 9],//Phương thức thanh toán
                ["配送先電話番号", 'phone', 10, 9],//Số điện thoại
                ["郵便番号", 'zipcode', 9, 9],//Mã bưu điện
                ["配送先都道府県", 'province', 14, 9],//Tỉnh/TP
                ["配送先住所", ['callback' => function ($index, $value) {
                    return preg_replace('/\s+/', '', $value);
                }, 'key' => 'address'], 18, 9],//Địa chỉ giao hàng
                ["配送先氏名", ['callback' => function ($index, $value) {
                    return preg_replace('/\s+/', ' ', $value);
                }, 'key' => 'fullname'], 18, 9],//Họ tên người nhận
                ["品番", ['product' => ['product_id', 'code']], 10, 9],//H
                ["商品名", ['product' => ['product_name', 'title']], 18, 9],//I
                ["単価", 'price', 15, 9],//Giá nhập
                ["数量", 'count', 15, 9],//SL
                ["到着希望日", ['callback' => function ($index, $date) use ($_dateNhan) {
                    return date("Y/m/d", strtotime($date));
                }, 'key' => 'order_date'], 15, 9],
                ["配送希望時間帯", ['callback' => function ($index, $value) {
                    return "8:00 ~ 12:00" == $value ? "午前中" : $value;
                }, 'key' => 'order_hours'], 15, 9],//Giờ nhận
                ["送料", 'order_ship', 15, 9],//Phí ship
                ["梱包材",['callback' => function ($index, $value) use ($date_export) {
                    return empty($value)?0:$value;
                }, 'key' => 'total_count'] , 15, 9],//Tổng giá nhập
                ["仕入金額", 'order_total_price', 15, 9],//Giá bán
                ["代引き請求金額", 'order_total_price_buy', 15, 9],//Giá bán // 振込み金額
                ["手数料",  ['callback' => function ($index, $value) use ($date_export) {
                    return empty($value)?0:$value;
                }, 'key' => 'order_ship_cou'], 15, 9],
                ["余分金", ['callback' => function ($index, $value,$a,$vals) use ($columns_value) {
                    return "=Q$index-N$index-P$index-R$index-O$index";
                }, 'key' => 'order_price'], 15, 9],//
                ["追跡番号", 'order_tracking', 15, 9],
                ["振込み情報", 'order_info', 25, 9],
                ["", 'comment', 15, 9], // Số lượng O
            ];

            $nameColList = [];
            foreach ($colums as $key => $value) {
                $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
                $keyCol = "";
                if (is_array($value[1])) {
                    if (isset($value[1]['product'])) {
                        $conf = $value[1]['product'];
                        $nameColList[$conf[0]] = $key;
                        $keyCol = $conf[1];
                    } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                        $nameColList[$value[1]['key']] = $key;
                        $keyCol = $value[1]['key'];
                    }
                } else {
                    $nameColList[$value[1]] = $key;
                    $keyCol = $value[1];
                }
                $sheet->setCellValue($nameCol . $start, $value[0])->getStyle($nameCol . $start)->applyFromArray(array(
                        'font' => array(
                            'size' => $value[3]
                        ),
                    )
                );
                if($keyCol == "title"){
                    $keyCol = "product_name";
                }else  if($keyCol == "code"){
                    $keyCol = "product_id";
                }
                if (isset($this->config["excel_width"][$name][$keyCol])) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($this->config["excel_width"][$name][$keyCol]+0.72);
                } else if ($value[2] > 0) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($value[2]+0.72);
                }
            }
            $sheet->getStyle('A' . $start . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . $start)->applyFromArray($style_header);
//            $ship = 34;
//            $datas = [];
//            $company = "22";
//            $orders = [
//
//            ];
            $sheet->getStyle("Q" . $start)->applyFromArray(array(
                    'font' => array(
                        'size' => 9,
                        'name' => 'ＭＳ Ｐゴシック',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                )
            );
            $start++;



            foreach ($datas['datas'] as $key => $_values) {
                $type = ((isset($columns_value['type']) ? $_values[$columns_value['type']] : ""));

                if ($type == "Info") {
                    $payMethod = isset($columns_value['payMethod']) ? $_values[$columns_value['payMethod']] : "";
                    $pay_Method = $this->getValuePayMethod($payMethod);

                    $image = (isset($columns_value['image']) ? $_values[$columns_value['image']] : "");
                    $order_info = (isset($columns_value['order_info']) ? $_values[$columns_value['order_info']] : "");
                    $order_id = (isset($columns_value['id']) ? $_values[$columns_value['id']] : "");

                    if (!empty($image)) {
                        $images[] = [str_replace(url('/'), "", $image), $order_info];
                    }

                    $count = (isset($columns_value['count']) ? $_values[$columns_value['count']] : "0");
                    $sheet1->setCellValue("A".$start,$order_id);
                    $sheet1->setCellValue("B".$start,$count);

                    if ($pay_Method == $typeMethod) {

                        $startRow = $start;
                        $endRow = $key;
                        $count = 0;

                        for ($i = $key; $i < count($datas['datas']); $i++) {
                            $count++;
                            $values = $datas['datas'][$i];
                            $oke = true;

                            $type = (isset($columns_value['type']) ? $values[$columns_value['type']] : "");
                            if ($type == "Footer") {
                               //$start = $start - 1;
                            }
                            $order_id = (isset($columns_value['id']) ? $values[$columns_value['id']] : "");
                            $ids[$order_id] = 1;

                            foreach ($colums as $key1 => $value) {
                                $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key1 + 1);
                                $oke = true;
                                if (false) {
                                    $oke = false;
                                } else {
                                    if (is_array($value[1])) {
                                        $_val = "";
                                        if (isset($value[1]['product'])) {
                                            $conf = $value[1]['product'];
                                            $id = (isset($columns_value[$conf[0]]) ? $values[$columns_value[$conf[0]]] : "");
                                            $_val = "";
                                            if (isset($products[$id]) && property_exists($products[$id], $conf[1])) {
                                                $_val = $products[$id]->{$conf[1]};
                                            }
                                        } else if (isset($value[1]['callback']) && isset($value[1]['key'])) {
                                            $conf = $value[1]['callback'];
                                            $_val = call_user_func_array($conf, [$start, (isset($columns_value[$value[1]['key']]) ? $values[$columns_value[$value[1]['key']]] : ""), $nameCol . $start,$values]);
                                            if (($value[1]['key'] == "timeCreate" || $value[1]['key'] == "order_date") && $type != "Info") {
                                                $_val = "";
                                            }
                                        }
                                        //if ($_val == "0") $_val = "";
                                        $sheet->setCellValue($nameCol . $start, trim($_val));
                                    } else {
                                        if ($type == "Footer") {
                                            if (!($value[1] == "count" || $value[1] == "order_price" || $value[1] == "order_total_price")) continue;
                                            $sheet->getStyle($nameCol . $start)->applyFromArray(array(
                                                'font' => array(
                                                    'size' => 9,
                                                    'name' => 'ＭＳ Ｐゴシック',
                                                    'color' => array('rgb' => 'ff0000'),
                                                ),
                                            ));
                                            $v = (isset($columns_value[$value[1]]) ? $values[$columns_value[$value[1]]] : "");
                                        } else {
                                            $v = (isset($columns_value[$value[1]]) ? $values[$columns_value[$value[1]]] : "");
                                        }
                                        if ($value[1] == "payMethod") {
                                            $payMethod = $v;
                                        }
                                       // if ($v == "0") $v = "";
                                        if(isset($value[4])){
                                            $sheet->setCellValueExplicit($nameCol . $start, trim($v),$value[4]);
                                        }else{
                                            $sheet->setCellValue($nameCol . $start, trim($v));
                                        }

                                    }
                                }
                            }
                            if ($oke) {
                                $sheet->getStyle("Q" . $start)->applyFromArray(array(
                                        'font' => array(
                                            'size' => 9,
                                            'name' => 'ＭＳ Ｐゴシック',
                                            'color' => array('rgb' => 'ff0000'),
                                        ),
                                    )
                                );

                                $sheet->getStyle('A' . ($start) . ':' . PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)) . '' . $start)->applyFromArray(array(
                                    'font' => array(
                                        'size' => 9,
                                        'name' => 'ＭＳ Ｐゴシック',
//                                        'color' => array('rgb' => '0070c0'),
                                    ),
                                ));
                                if ($payMethod == "銀行振込" || $payMethod == "決済不要") {
//                                    $sheet->getStyle('A'.($start).':'. PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)).''.$start)->applyFromArray( array(
//                                        'font'  => array(
//                                            'size'  => 9,
//                                            'name' => 'ＭＳ Ｐゴシック',
//                                            'color' => array('rgb' => '0070c0'),
//                                        ),
//                                    ) );
                                    foreach (["B", "U"] as $col) {

                                        $sheet->getStyle($col . $start)->applyFromArray(array(
                                            'font' => array(
                                                'size' => 9,
                                                'color' => array('rgb' => '0070c0'),
                                            ),
                                        ));
                                    }
                                } else if ($payMethod == "決済不要") {
//                                    $sheet->getStyle('A'.$start.':'. PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)).''.$start)->applyFromArray( array(
//                                        'font'  => array(
//                                            'size'  => 9,
//                                            'name' => 'ＭＳ Ｐゴシック',
//                                            'color' => array('rgb' => 'ff0000'),
//                                        ),
//                                    ) );
                                } else {
//                                    $sheet->getStyle('A'.$start.':'. PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)).''.$start)->applyFromArray( array(
//                                        'font'  => array(
//                                            'size'  => 9,
//                                            'name' => 'ＭＳ Ｐゴシック',
//
//                                        ),
//                                    ) );
//                                    foreach (["B", "U"] as $col) {
//                                        $sheet->getStyle($col . $start)->applyFromArray(array(
//                                            'font' => array(
//                                                'color' => array('rgb' => '0000'),
//                                            ),
//                                        ));
//                                    }
                                }
                                $start++;
                            }
                            if ($type == "Footer") {
                                break;
                            }
                        }
                        if ($startRow != $start) {
                            $_1 = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nameColList["product_id"] + 1) . $startRow;
                            $_2 = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nameColList["count"] + 1) . $startRow;
                            $sheet->getStyle($_1 . ':' . $_2)->applyFromArray(array(
//                                'borders' => [
//                                    'allBorders' => array(
//                                        'borderStyle' => Border::BORDER_DOTTED,
//                                        'color' => array('rgb'=>'000000')
//                                    ),
//                                ],
                                'font' => array(
//                                    'color' => array('rgb' => '0070c0'),
                                    'size' => 9,
                                    'name' => 'ＭＳ Ｐゴシック',
                                )
                            ));
                            $sheet->setCellValue("S" .($start-1),"=SUM(S".$startRow . ":S".($start - 2).")");
                            $sheet->setCellValue("K" .($start-1),"=SUM(K".$startRow . ":K".($start - 2).")");
                            $sheet->setCellValue("P" .($start-1),"=SUM(P".$startRow . ":P".($start - 2).")");

                            $sheet->getStyle("S" . ($start-1))->applyFromArray(array(
                                    'font' => array(
                                        'size' => 9,
                                        'name' => 'ＭＳ Ｐゴシック',
                                        'color' => array('rgb' => 'ff0000'),
                                    ),
                                )
                            );


                            foreach (["timeCreate", "fullname", "payMethod", 'zipcode', 'province', 'address', 'phone', 'order_date', 'order_hours'] as $col) {
                                $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nameColList[$col] + 1);
                                $spreadsheet->getActiveSheet()->mergeCells($nameCol . $startRow . ":" . $nameCol . ($start - 2));
                                $styleArray = [
                                    'alignment' => [
                                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                    ],
                                ];
                                $spreadsheet->getActiveSheet()->getStyle($nameCol . $startRow)->applyFromArray($styleArray);
                            }
                            $start++;
                            $sheet->setCellValue('I' . $start, '※1キロずつの小分けをお願いします。');
                            $sheet->getStyle('I' . $start)->applyFromArray(array(
                                    'font' => array(

                                        'name' => 'Times New Roman',
                                        'color' => array('rgb' => 'ff1100'),
                                    ),
                                )
                            );
                            $start++;
                        }
                    }
                }
            }
            if ($typeMethod == 0) {
//                $start+=2;
//                $sheet->setCellValue('N'.$start, '合計');
//                $sheet->setCellValue('P'.$start, '合計代引き金額：　19330');

//                $sheet->getStyle('N'.$start)->applyFromArray(array(
//                        'font'  => array(
//                            'size'  => 9,
//                            'name' => 'Times New Roman',
//                            'color' => array('rgb' => 'ff1100'),
//                        ),
//                    )
//                );
//                $sheet->getStyle('P'.$start)->applyFromArray(array(
//                        'font'  => array(
//                            'size'  => 9,
//                            'name' => 'Times New Roman',
//                            'color' => array('rgb' => 'ff1100'),
//                        ),
//                    )
//                );
                $start++;
                $sheet->setCellValue('I' . $start, '※1キロずつの小分けをお願いします。');
                $sheet->getStyle('I' . $start)->applyFromArray(array(
                        'font' => array(

                            'name' => 'Times New Roman',
                            'color' => array('rgb' => 'ff1100'),
                        ),
                    )
                );
            }
            $start += 2;
            $dataRow = [];
        }

        $sheet->setCellValue('I' . ($start-3), '');

        $writer = new Xlsx($spreadsheet);

        if($datas['type'] != "demo"){
            $path = '/uploads/exports/' . str_replace(__CLASS__ . '::', "", __METHOD__);
        }else{
            $path = '/uploads/demo/' . str_replace(__CLASS__ . '::', "", __METHOD__);
        }

        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        $path = $path . '/' . date('Y-m-d', $this->date_export);
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }
        $filename = '株式会社コギ家-様-' . date('m', $this->date_export) . '月' . date('d', $this->date_export) . '日注文分';
        //株式会社コギ家-様-10月17日注文分

        $path = $path . '/' . $filename;
        if (!$this->file->isDirectory(public_path() . $path)) {
            $this->file->makeDirectory(public_path() . $path);
        }

        $pathZip = $path . '/zip';
        if ($this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->deleteDirectory(public_path() . $pathZip, true);
        }

        if (!$this->file->isDirectory(public_path() . $pathZip)) {
            $this->file->makeDirectory(public_path() . $pathZip);
        }
        $path2 = $pathZip . '/' . $filename . '.xlsx';
        $writer->save(public_path() . $path2);
        $files = [
            [$filename . '.xlsx', public_path() . $path2]
        ];
        $md5 = [];
        foreach ($images as $image) {

            if (!empty($image[0]) && file_exists(public_path() . "/" . $image[0])) {

                $pathinfo = pathinfo(public_path() . "/" . $image[0]);

                if (empty($image[1])) {
                    $file_image = $pathinfo['filename'] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {

                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $pathinfo['filename'] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    $file_image = $image[1] . '.' . $pathinfo['extension'];
                    $newName = $pathZip . '/' . $file_image;
                    if ($this->file->exists(public_path() . '/' . $newName)) {
                        if(md5_file(public_path() . '/' . $newName) != md5_file(public_path() . "/" . $image[0])){
                            for ($i = 1; $i < 100; $i++) {
                                $file_image = $image[1] . '(' . $i . ')' . '.' . $pathinfo['extension'];
                                $newName = $pathZip . '/' . $file_image;
                                if (!$this->file->exists(public_path() . '/' . $newName)) {
                                    break;
                                }
                            }
                        }
                    }
                }
                $this->file->copy(public_path() . "/" . $image[0], public_path() . '/' . $newName);
                $files[] = [
                    $file_image, public_path() . '/' . $newName
                ];
            }
        }
        $zipFileName = $filename . '.zip';
        $zip = new \ZipArchive();
        if ($this->file->exists(public_path() . '/' . $path . '/' . $zipFileName)) {
            $this->file->delete(public_path() . '/' . $path . '/' . $zipFileName);
        }
        if ($zip->open(public_path() . '/' . $path . '/' . $zipFileName, \ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $zip->addFile($file[1], $file[0]);
            }
            $zip->close();
        }
//        if( $this->file->isDirectory(public_path().$pathZip)){
//            $this->file->deleteDirectory(public_path().$pathZip,true);
//        }
        return ['link' => url($path . '/' . $zipFileName).'?time='.time().'-'.rand(1000,9999), 'images' => $images, 'ids' => $ids];
    }
}
