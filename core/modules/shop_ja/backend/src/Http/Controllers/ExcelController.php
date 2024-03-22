<?php
namespace ShopJa\Http\Controllers;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use \ShopJa\Http\Models\OrderModel;
use \PhpOffice\PhpSpreadsheet;
use \PhpOffice\PhpSpreadsheet\Style\Border;
use \PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelController extends \Zoe\Http\ControllerBackend
{
    private function IF_End($val,$conf){
        if( $conf['equal_end'] === "<=" && $val <= $conf['text_end']){
            return true;
        }else if( $conf['equal_end'] === ">=" && $val >= $conf['text_end']){
            return true;
        }else if($conf['equal_end'] === ">" && $val > $conf['text_end']){
            return true;
        } else if($conf['equal_end'] === "<" && $val < $conf['text_end']){
            return true;
        }else if($conf['equal_end'] === "=" && $val === $conf['text_end']){
            return true;
        }
        return false;
    }
    private function IF_Start($val,$conf){
        if( $conf['equal_start'] === "<=" && $val <= $conf['text_start']){
            return true;
        }else if( $conf['equal_start'] === ">=" && $val >= $conf['text_start']){
            return true;
        }else if($conf['equal_start'] === ">" && $val > $conf['text_start']){
            return true;
        } else if($conf['equal_start'] === "<" && $val < $conf['text_start']){
            return true;
        }else if($conf['equal_start'] === "=" && $val === $conf['text_start']){
            return true;
        }
        return false;
    }

    public function init()
    {

    }
    public function getCrumb()
    {
        $this->breadcrumb(z_language("Quản lý đơn hàng"), route('backend:shop_ja:order:list'));
        return $this;
    }
    public function list(){

    }
    public function KOGYJA(){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->createSheet();
        $spreadsheet->getSheet(1)->setTitle('Sheet2');

        $sheet->setTitle("Sheet1");
        $spreadsheet->getProperties()
            ->setTitle('PHP Download Example')
            ->setSubject('A PHPExcel example')
            ->setDescription('A simple example for PhpSpreadsheet. This class replaces the PHPExcel class')
            ->setCreator('php-download.com')
            ->setLastModifiedBy('php-download.com');

        $sheet->setCellValue('B1', '株式会社コギ家　様 注文フォーマット');


        $styleArray = array(
            'font'  => array(
                'size'  => 9,
                'name' => 'Times New Roman'
            ));
        $style_header = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'color' => array('rgb'=>'FFE100'),
            ),
            'borders' => [
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_DOTTED,
                    'color' => array('rgb'=>'000000')
                ),
            ],
            'font' => array(
                'size' => 10
            )
        );
        $sheet->getStyle('B1')->applyFromArray($styleArray);
        $start=2;
        for($typeMethod = 0; $typeMethod< 2 ; $typeMethod++){
            $colums = [
                ["注文日",'dateCreate',10,9],//ngày đặt hàng
                ["支払区分",'payMethod',10,9],//Phương thức thanh toán
                ["配送先電話番号",'phone',10,9],//Số điện thoại
                ["配送先郵便番号",'postal_code',9,9],//Mã bưu điện
                ["配送先都道府県",'city',14,9],//Tỉnh/TP
                ["配送先住所",'address',18,9],//Địa chỉ giao hàng
                ["配送先氏名",'fullname',18,9],//Họ tên người nhận
                ["品番",['product'=>['product_id','code']],10,9],//Mã SP
                ["商品名",['product'=>['product_id','title']],18,9],//Tên SP
                ["単価",['product'=>['product_id','price']],15,9],//Giá nhập
                ["数量",'count',15,9],//SL
                ["到着希望日",'day_ship',15,9],//Ngày nhận
                ["配送希望時間帯",'time_ship',15,9],//Giờ nhận
                ["別途送料",'price_ship',15,9],//Phí ship
                ["仕入金額",['product'=>['product_id','price','totalPrice']],15,9],//Tổng giá nhập
                ["代引き請求金額",['product'=>['product_id','price_buy','totalPriceBuy']],15,9],//Giá bán
                ["代引き手数料",['product'=>['product_id','price_buy','totalCOU']],15,9],
                ["紹介料",['callback'=>function($index){return '=IF(J'.$index.'="","",P'.$index.'-J'.$index.'*K'.$index.'-N'.$index.'-Q'.$index.')';}],15,9],
                ["追跡番号",'tracking',15,9],
                ["振込み情報",'info',25,9],
            ];
            foreach($colums as $key=>$value){
                $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key+1);
                $sheet->setCellValue($nameCol.$start, $value[0])->getStyle($nameCol.$start)->applyFromArray(array(
                        'font'  => array(
                            'size'  => $value[3]
                        ),
                    )
                );
                if($value[2] > 0){
                    $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($value[2]);
                }
            }
            $sheet->getStyle('A'.$start.':'. PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)).$start)->applyFromArray( $style_header );

            $ship = 34;

            $datas = [];
            $company = "22";
            $orders = [

            ];

            if($typeMethod == 0){
                $start+=2;
                $sheet->setCellValue('N'.$start, '合計');
                $sheet->setCellValue('P'.$start, '合計代引き金額：　19330');

                $sheet->getStyle('N'.$start)->applyFromArray(array(
                        'font'  => array(
                            'size'  => 9,
                            'name' => 'Times New Roman',
                            'color' => array('rgb' => 'ff1100'),
                        ),
                    )
                );
                $sheet->getStyle('P'.$start)->applyFromArray(array(
                        'font'  => array(
                            'size'  => 9,
                            'name' => 'Times New Roman',
                            'color' => array('rgb' => 'ff1100'),
                        ),
                    )
                );

                $start++;
                $sheet->setCellValue('I'.$start, '※1キロずつの小分けをお願いします。');
                $sheet->getStyle('I'.$start)->applyFromArray(array(
                        'font'  => array(
                            'size'  => 9,
                            'name' => 'Times New Roman',
                            'color' => array('rgb' => 'ff1100'),
                        ),
                    )
                );
            }

            $products =  DB::table('shop_product')->get()->keyBy('id')->all();
            $results =  DB::table('shop_order')->where('status',2)->get()->all();

            foreach ($results as $result){
                $row = [
                    'info'=>$result
                ];
                $row['detail'] =  DB::table('shop_order_detail')
                    ->where('order_id',$result->id)->where('ship',$ship)->get()->toArray();
                if(count($row['detail']) > 0 ){
                    $datas[] = $row;
                    $orders[$result->id] = count($row['detail']);
                }
            }
            echo "<pre>";


            print_r($orders);
            print_r($datas);

            $start+=1;
            $dataRow = [];
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path().'/uploads/exports/KOGYJA.xlsx');
        return redirect()->route('backend:shop_ja:order:list');
    }

    public function KURICHIKU(){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->createSheet();
        $spreadsheet->getSheet(1)->setTitle('Sheet2');

        $sheet->setTitle("Sheet1");
        $spreadsheet->getProperties()
            ->setTitle('PHP Download Example')
            ->setSubject('A PHPExcel example')
            ->setDescription('A simple example for PhpSpreadsheet. This class replaces the PHPExcel class')
            ->setCreator('php-download.com')
            ->setLastModifiedBy('php-download.com');

        $sheet->setCellValue('B1', '株式会社コギ家　様 注文フォーマット');


        $styleArray = array(
            'font'  => array(
                'size'  => 9,
                'name' => 'Times New Roman'
            ));
        $style_header = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'color' => array('rgb'=>'FFE100'),
            ),
            'borders' => [
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_DOTTED,
                    'color' => array('rgb'=>'000000')
                ),
            ],
            'font' => array(
                'size' => 10
            )
        );
        $sheet->getStyle('B1')->applyFromArray($styleArray);
        $start=2;

        $category = config_get("category", "shop-ja:japan:category");
        $category_ship = get_category_type('shop-ja:japan:category:com-ship');

        for($typeMethod = 0; $typeMethod< 2 ; $typeMethod++){
            $colums = [
                ["注文日",'dateCreate',10,9],//ngày đặt hàng
                ["支払区分",'payMethod',10,9],//Phương thức thanh toán
                ["配送先電話番号",'phone',10,9],//Số điện thoại
                ["配送先郵便番号",'postal_code',9,9],//Mã bưu điện
                ["配送先都道府県",'city',14,9],//Tỉnh/TP
                ["配送先住所",'address',18,9],//Địa chỉ giao hàng
                ["配送先氏名",'fullname',18,9],//Họ tên người nhận
                ["品番",['product'=>['product_id','code']],10,9],//Mã SP
                ["商品名",['product'=>['product_id','title']],18,9],//Tên SP
                ["単価",['product'=>['product_id','price']],15,9],//Giá nhập
                ["数量",'count',15,9],//SL
                ["到着希望日",'day_ship',15,9],//Ngày nhận
                ["配送希望時間帯",'time_ship',15,9],//Giờ nhận
                ["別途送料",'price_ship',15,9],//Phí ship
                ["仕入金額",['product'=>['product_id','price','totalPrice']],15,9],//Tổng giá nhập
                ["代引き請求金額",['product'=>['product_id','price_buy','totalPriceBuy']],15,9],//Giá bán
                ["代引き手数料",['product'=>['product_id','price_buy','totalCOU']],15,9],// ship
                ["紹介料",['callback'=>function($index){return '=IF(P'.$index.'-N'.$index.'-O'.$index.'-Q'.$index.')';}],15,9],// lợi nhuận
                ["追跡番号",'tracking',15,9],
                ["振込み情報",'info',25,9],
            ];
            echo "<pre>";

            foreach($colums as $key=>$value){
                $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key+1);

                $sheet->setCellValue($nameCol.$start, $value[0])->getStyle($nameCol.$start)->applyFromArray(array(
                        'font'  => array(
                            'size'  => $value[3]
                        ),
                    )
                );
                if($value[2] > 0){
                    $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($value[2]);
                }
            }
            $sheet->getStyle('A'.$start.':'. PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)).$start)->applyFromArray( $style_header );

            $datas = [];
            $company = "35";

            $orders = [

            ];
            echo $start."-".$typeMethod."<BR>";
            $products =  DB::table('shop_product')->get()->keyBy('id')->all();

            if($typeMethod == 0)
            {
                $results =  DB::table('shop_order')->where('status','=',2)->get()->all();
                $start++;
            } else{
                $results =  DB::table('shop_order')->whereIn('status',[1,3])->get()->all();
                $start++;
            }
            echo $start."-".$typeMethod."<BR>";
            foreach ($results as $result){
                $row = [
                    'info'=>$result
                ];
                $row['detail'] =  DB::table('shop_order_detail')
                    ->where('order_id',$result->id)->where('company',$company)->get()->toArray();
                if(count($row['detail']) > 0 ){
                    $datas[] = $row;
                    $orders[$result->id] = count($row['detail']);
                }
            }
            var_dump( $datas);
            foreach ($datas as $info){

                $_tmpData = [
                    'dateCreate'=>date('d',strtotime($info['info']->created_at))."日",
                    'payMethod'=>$info['info']->pay_method,
                    'phone'=>$info['info']->phone,
                    'postal_code'=>$info['info']->postal_code,
                    'city'=>$info['info']->city,
                    'address'=>$info['info']->address,
                    'fullname'=>$info['info']->fullname,
                    'day_ship'=>date('d/m/Y',strtotime($info['info']->day_ship)) ,
                    'time_ship'=>$info['info']->time_ship,
                    'info'=>"",
                    'link'=>$info['info']->link,
                ];

                if($info['info']->pay_method == 1){
                    $_tmpData['payMethod'] = '代金引換';
                }else if($info['info']->pay_method == 2){
                    $_tmpData['payMethod'] = '銀行振込';
                }else{
                    $_tmpData['payMethod'] = '決済不要';
                }

                $bank_info = $info['info']->bank_info;
                $arr =  explode("|",$bank_info);
                $_tmpData['info'] = "依頼人名.".$arr[0]."様" .$arr[1]."日に" .$arr[2]. "円入金済み";

                foreach ($category as $_category){
                    if($_category['id'] ==$info['info']->city ){
                        $_tmpData['city'] =$_category['name']; break;
                    }
                }
                foreach($info['detail'] as $_=>$_value){
                    $rowData = [];
                    foreach($colums as $key=>$value){
                        $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key+1);

                        if(is_array($value[1])){
                            if(isset($value[1]['product'])){
                                $conf = $value[1]['product'];
                                if(property_exists($_value,$conf[0])){
                                    $_val  = "";
                                    if("product_id" == $conf[0]){
                                        $_key = $_value->{$conf[0]};
                                        if(isset($products[$_key]) && property_exists($products[$_key],$conf[1])){
                                            $_val = $products[$_key]->{$conf[1]};
                                            if(isset($conf[2])){
                                                if($conf[2] == "totalPrice" || $conf[2] == "totalPriceBuy"){
                                                    $_val = $_val*$_value->count;
                                                    $rowData[$conf[2]] = $_val;
                                                }else if($conf[2] == "totalCOU"){

                                                    if(isset($category_ship[$_value->ship])){

                                                        if($info['info']->pay_method == 2 ||$info['info']->pay_method == 3 ){
                                                            $_val =  0;
                                                        }else{
                                                            var_dump($rowData);
                                                            $_val = 0;
                                                            if(isset($rowData['totalPriceBuy'])){
                                                                foreach ($category_ship[$_value->ship]->data as $uty=>$conf){
                                                                    foreach ($conf as $_conf){

                                                                        if($this->IF_Start($rowData['totalPriceBuy'], $_conf) && $this->IF_End($rowData['totalPriceBuy'],$_conf) ){
                                                                            $_val = $_conf['value'];
                                                                            break;
                                                                        }
                                                                    }
                                                                    if($_val!=0) break;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    $sheet->setCellValue($nameCol.$start,$_val);
                                }else if($conf[0] == "callback"){
                                    $_val = call_user_func_array($conf[1],[$start]);
                                    $sheet->setCellValue($nameCol.$start,$_val);
                                }
                            }else if(isset($value[1]['callback'])){
                               // $conf = $value[1]['callback'];
                               // $_val = call_user_func_array($conf,[$start]);
                               // $sheet->setCellValue($nameCol.$start,$_val);
                            }
                        }else{
                            if(isset($_tmpData[$value[1]])){
                                $sheet->setCellValue($nameCol.$start,$_tmpData[$value[1]]);
                            }else{
                                $val = $_value->{$value[1]};
                                $sheet->setCellValue($nameCol.$start, $val);
                            }
                        }
                    }
                    if($typeMethod == 0){

                        $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums));
                        $sheet->getStyle('A'.$start.':'.$nameCol.$start)->applyFromArray(  array(
                            'font' => array(
                                'color' => array('rgb'=>'5B9BD5'),
                            ),
                        ) );

                        $start+=2;

                        $sheet->setCellValue('N'.$start, '合計');
                        $sheet->setCellValue('P'.$start, '合計代引き金額：　19330');

                        $sheet->getStyle('N'.$start)->applyFromArray(array(
                                'font'  => array(
                                    'size'  => 9,
                                    'name' => 'Times New Roman',
                                    'color' => array('rgb' => 'ff1100'),
                                ),
                            )
                        );

                        $sheet->getStyle('P'.$start)->applyFromArray(array(
                                'font'  => array(
                                    'size'  => 9,
                                    'name' => 'Times New Roman',
                                    'color' => array('rgb' => 'ff1100'),
                                ),
                            )
                        );
                        $start++;
                        $sheet->setCellValue('I'.$start, '※1キロずつの小分けをお願いします。');
                        $sheet->getStyle('I'.$start)->applyFromArray(array(
                                'font'  => array(
                                    'size'  => 9,
                                    'name' => 'Times New Roman',
                                    'color' => array('rgb' => 'ff1100'),
                                ),
                            )
                        );
                        //5B9BD5
                    }
                }
            }
             print_r($orders);
             print_r($datas);
            $start+=1;
            $dataRow = [];
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path().'/uploads/exports/KURICHIKU.xlsx');
        return redirect()->route('backend:shop_ja:order:list');
    }

    public function YAMADA(){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->createSheet();
        $spreadsheet->getSheet(1)->setTitle('Sheet2');
        $sheet->setTitle("Sheet1");
        $spreadsheet->getProperties()
            ->setTitle('PHP Download Example')
            ->setSubject('A PHPExcel example')
            ->setDescription('A simple example for PhpSpreadsheet. This class replaces the PHPExcel class')
            ->setCreator('php-download.com')
            ->setLastModifiedBy('php-download.com');
        $title1 = "株式会社ヤマダ 様 注文フォーマット";
        $title2 = "見本";

        $info = "依頼人名. VO HOANG 様 22日に 7410 円入金済み";

        $sheet->setCellValue('B1', $title1);
        $sheet->setCellValue('F2', $title2);
        $sheet->setCellValue('P2', $info);

        $styleArray = array(
            'font'  => array(
                'size'  => 9,
                'name' => 'Times New Roman'
            ));
        $style_header = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'color' => array('rgb'=>'FFE100'),
            ),
            'borders' => [
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_DOTTED,
                    'color' => array('rgb'=>'000000')
                ),
            ],
            'font' => array(
                'size' => 10
            )
        );
        $sheet->getStyle('B1')->applyFromArray($styleArray);
        $sheet->getStyle('F2')->applyFromArray($styleArray);
        $sheet->getStyle('P2')->applyFromArray(array(
                'font'  => array(
                    'size'  => 9,
                    'name' => 'Times New Roman',
                    'color' => array('rgb' => '0070c0'),
                ),
            )
        );
        $sheet->getStyle('A3:T3')->applyFromArray( $style_header );
        $colums = [
            ["注文日",'dateCreate',10,9],//A
            ["支払区分",'payMethod',10,9],//B
            ["配送先電話番号",'phone',10,9],//C
            ["配送先郵便番号",'postal_code',9,9],//D
            ["配送先都道府県",'city',14,9],//E
            ["配送先住所",'address',18,9],//F
            ["配送先氏名",'fullname',18,9],//G
            ["品番",['product'=>['product_id','code']],10,9],//H
            ["商品名",['product'=>['product_id','title']],18,9],//I
            ["単価",['product'=>['product_id','price']],15,9],//J
            ["数量",'count',15,9],//K
            ["到着希望日",'day_ship',15,9],//L
            ["配送希望時間帯",'time_ship',15,9],//M
            ["別途送料",'price_ship',15,9],//N
            ["仕入金額",['product'=>['product_id','price','totalPrice']],15,9],//O
            ["代引き請求金額",['product'=>['product_id','price_buy','totalPriceBuy']],15,9],//P
            ["代引き手数料",['product'=>['product_id','price_buy','totalCOU']],15,9],//Q
            ["紹介料",['callback'=>function($index){return '=IF(J'.$index.'="","",P'.$index.'-J'.$index.'*K'.$index.'-N'.$index.'-Q'.$index.')';}],15,9],//R
            ["追跡番号",'tracking',15,9],//S
            ["振込み情報",'info',25,9],//T
            ["",'link',25,9],//U
        ];
        $start=3;
        foreach($colums as $key=>$value){
            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key+1);

            $sheet->setCellValue($nameCol.$start, $value[0])->getStyle($nameCol.$start)->applyFromArray(array(
                    'font'  => array(
                        'size'  => $value[3]
                    ),
                )
            );

            if($value[2] > 0){
                $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($value[2]);
            }
        }
        $start++;
        $defaultStart = $start;
        $lastIndex = "";
        $datas = [];
        $company = "22";
        $orders = [

        ];
        $products =  DB::table('shop_product')->get()->keyBy('id')->all();
        $results =  DB::table('shop_order')->where('status',2)->get()->all();
            foreach ($results as $result){
               $row = [
                   'info'=>$result
               ];
               $row['detail'] =  DB::table('shop_order_detail')
                   ->where('order_id',$result->id)->where('company',$company)->get()->toArray();
               if(count($row['detail']) > 0 ){
                   $datas[] = $row;
                   $orders[$result->id] = count($row['detail']);
               }
            }
        echo "<pre>";

//            print_r($products);
        print_r($orders);
        print_r($datas);

        //支払い方法 Phương thức thanh toán
        //代金引換 Thanh toán khi giao hàng 1
        // 銀行振込 Chuyển khoản ngân hàng 2
        // 決済不要 Không cần thanh toán 3
        $category = config_get("category", "shop-ja:japan:category");
        $category_ship = get_category_type('shop-ja:japan:category:com-ship');
        foreach ($datas as $info){

            $_tmpData = [
                'dateCreate'=>date('d',strtotime($info['info']->created_at))."日",
                'payMethod'=>$info['info']->pay_method,
                'phone'=>$info['info']->phone,
                'postal_code'=>$info['info']->postal_code,
                'city'=>$info['info']->city,
                'address'=>$info['info']->address,
                'fullname'=>$info['info']->fullname,
                'day_ship'=>date('d/m/Y',strtotime($info['info']->day_ship)) ,
                'time_ship'=>$info['info']->time_ship,
                'info'=>"",
                'link'=>$info['info']->link,
            ];

            if($info['info']->pay_method == 1){
                $_tmpData['payMethod'] = '代金引換';
            }else if($info['info']->pay_method == 2){
                $_tmpData['payMethod'] = '銀行振込';
            }else{
                $_tmpData['payMethod'] = '決済不要';
            }

            $bank_info = $info['info']->bank_info;
            $arr =  explode("|",$bank_info);
            $_tmpData['info'] = "依頼人名.".$arr[0]."様" .$arr[1]."日に" .$arr[2]. "円入金済み";

            foreach ($category as $_category){
                if($_category['id'] ==$info['info']->city ){
                    $_tmpData['city'] =$_category['name']; break;
                }
            }

            foreach($info['detail'] as $_=>$_value){
                $rowData = [];
                foreach($colums as $key=>$value){
                    $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key+1);

                    if(is_array($value[1])){
                        if(isset($value[1]['product'])){
                            $conf = $value[1]['product'];
                            if(property_exists($_value,$conf[0])){
                                $_val  = "";
                                if("product_id" == $conf[0]){
                                    $_key = $_value->{$conf[0]};
                                    if(isset($products[$_key]) && property_exists($products[$_key],$conf[1])){
                                        $_val = $products[$_key]->{$conf[1]};
                                        if(isset($conf[2])){
                                            if($conf[2] == "totalPrice" || $conf[2] == "totalPriceBuy"){
                                                $_val = $_val*$_value->count;

                                                $rowData[$conf[2]] = $_val;

                                            }else if($conf[2] == "totalCOU"){

                                                 if(isset($category_ship[$_value->ship])){

                                                     if($info['info']->pay_method == 2 ||$info['info']->pay_method == 3 ){
                                                         $_val =  0;
                                                     }else{
                                                         var_dump($rowData);
                                                         $_val = 0;
                                                         if(isset($rowData['totalPriceBuy'])){
                                                             foreach ($category_ship[$_value->ship]->data as $uty=>$conf){
                                                                 foreach ($conf as $_conf){

                                                                     if($this->IF_Start($rowData['totalPriceBuy'], $_conf) && $this->IF_End($rowData['totalPriceBuy'],$_conf) ){
                                                                         $_val = $_conf['value'];
                                                                         break;
                                                                     }
                                                                 }
                                                                 if($_val!=0) break;
                                                             }
                                                         }
                                                     }
                                                 }
                                            }
                                        }
                                    }
                                }
                                $sheet->setCellValue($nameCol.$start,$_val);
                            }else if($conf[0] == "callback"){
                                $_val = call_user_func_array($conf[1],[$start]);
                                $sheet->setCellValue($nameCol.$start,$_val);
                            }
                        }else if(isset($value[1]['callback'])){
                             $conf = $value[1]['callback'];
                             $_val = call_user_func_array($conf,[$start]);
                             $sheet->setCellValue($nameCol.$start,$_val);
                        }
                    }else{
                        if(isset($_tmpData[$value[1]])){
                            $sheet->setCellValue($nameCol.$start,$_tmpData[$value[1]]);
                        }else{
                            $val = $_value->{$value[1]};
                            $sheet->setCellValue($nameCol.$start, $val);
                        }
                    }
                }
                $start++;
            }
        }
        $sheet->setCellValue("K".$start, "=SUM(K".$defaultStart.":K".($start-1).")");
        $sheet->setCellValue("P".$start, "=SUM(P".$defaultStart.":P".($start-1).")");
        $sheet->setCellValue("R".$start, "=SUM(R".$defaultStart.":R".($start-1).")");
        $sheet->setCellValue("Q".$start, "=SUM(Q".$defaultStart.":Q".($start-1).")");
        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path().'/uploads/exports/YAMADA.xlsx');
        return redirect()->route('backend:shop_ja:order:list');
    }
    public function Cocolala(){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->createSheet();
        $spreadsheet->getSheet(1)->setTitle('Sheet2');
        $sheet->setTitle("Sheet1");
        $spreadsheet->getProperties()
            ->setTitle('PHP Download Example')
            ->setSubject('A PHPExcel example')
            ->setDescription('A simple example for PhpSpreadsheet. This class replaces the PHPExcel class')
            ->setCreator('php-download.com')
            ->setLastModifiedBy('php-download.com');
        $title1 = "株式会社ヤマダ 様 注文フォーマット";
        $title2 = "見本";

        $info = "依頼人名. VO HOANG 様 22日に 7410 円入金済み";

        $sheet->setCellValue('B1', $title1);
        $sheet->setCellValue('F2', $title2);
        $sheet->setCellValue('P2', $info);

        $styleArray = array(
            'font'  => array(
                'size'  => 9,
                'name' => 'Times New Roman'
            ));
        $style_header = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'color' => array('rgb'=>'FFE100'),
            ),
            'borders' => [
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_DOTTED,
                    'color' => array('rgb'=>'000000')
                ),
            ],
            'font' => array(
                'size' => 10
            )
        );
        $sheet->getStyle('B1')->applyFromArray($styleArray);
        $sheet->getStyle('F2')->applyFromArray($styleArray);
        $sheet->getStyle('P2')->applyFromArray(array(
                'font'  => array(
                    'size'  => 9,
                    'name' => 'Times New Roman',
                    'color' => array('rgb' => '0070c0'),
                ),
            )
        );
        $sheet->getStyle('A3:T3')->applyFromArray( $style_header );
        $colums = [
            ["注文日",'dateCreate',10,9],//A
            ["支払区分",'payMethod',10,9],//B
            ["配送先電話番号",'phone',10,9],//C
            ["配送先郵便番号",'postal_code',9,9],//D
            ["配送先都道府県",'city',14,9],//E
            ["配送先住所",'address',18,9],//F
            ["配送先氏名",'fullname',18,9],//G
            ["品番",['product'=>['product_id','code']],10,9],//H
            ["商品名",['product'=>['product_id','title']],18,9],//I
            ["単価",['product'=>['product_id','price']],15,9],//J
            ["数量",'count',15,9],//K
            ["到着希望日",'day_ship',15,9],//L
            ["配送希望時間帯",'time_ship',15,9],//M
            ["別途送料",'price_ship',15,9],//N
            ["仕入金額",['product'=>['product_id','price','totalPrice']],15,9],//O
            ["代引き請求金額",['product'=>['product_id','price_buy','totalPriceBuy']],15,9],//P
            ["代引き手数料",['product'=>['product_id','price_buy','totalCOU']],15,9],//Q
            ["紹介料",['callback'=>function($index){return '=IF(J'.$index.'="","",P'.$index.'-J'.$index.'*K'.$index.'-N'.$index.'-Q'.$index.')';}],15,9],//R
            ["追跡番号",'tracking',15,9],//S
            ["振込み情報",'info',25,9],//T
            ["",'link',25,9],//U
        ];
        $start=3;
        foreach($colums as $key=>$value){
            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key+1);

            $sheet->setCellValue($nameCol.$start, $value[0])->getStyle($nameCol.$start)->applyFromArray(array(
                    'font'  => array(
                        'size'  => $value[3]
                    ),
                )
            );

            if($value[2] > 0){
                $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($value[2]);
            }
        }
        $start++;
        $defaultStart = $start;
        $lastIndex = "";
        $datas = [];
        $company = "22";
        $orders = [

        ];
        $products =  DB::table('shop_product')->get()->keyBy('id')->all();
        $results =  DB::table('shop_order')->where('status',2)->get()->all();
        foreach ($results as $result){
            $row = [
                'info'=>$result
            ];
            $row['detail'] =  DB::table('shop_order_detail')
                ->where('order_id',$result->id)->where('company',$company)->get()->toArray();
            if(count($row['detail']) > 0 ){
                $datas[] = $row;
                $orders[$result->id] = count($row['detail']);
            }
        }
        echo "<pre>";

//            print_r($products);
        print_r($orders);
        print_r($datas);

        //支払い方法 Phương thức thanh toán
        //代金引換 Thanh toán khi giao hàng 1
        // 銀行振込 Chuyển khoản ngân hàng 2
        // 決済不要 Không cần thanh toán 3
        $category = config_get("category", "shop-ja:japan:category");
        $category_ship = get_category_type('shop-ja:japan:category:com-ship');
        foreach ($datas as $info){

            $_tmpData = [
                'dateCreate'=>date('d',strtotime($info['info']->created_at))."日",
                'payMethod'=>$info['info']->pay_method,
                'phone'=>$info['info']->phone,
                'postal_code'=>$info['info']->postal_code,
                'city'=>$info['info']->city,
                'address'=>$info['info']->address,
                'fullname'=>$info['info']->fullname,
                'day_ship'=>date('d/m/Y',strtotime($info['info']->day_ship)) ,
                'time_ship'=>$info['info']->time_ship,
                'info'=>"",
                'link'=>$info['info']->link,
            ];

            if($info['info']->pay_method == 1){
                $_tmpData['payMethod'] = '代金引換';
            }else if($info['info']->pay_method == 2){
                $_tmpData['payMethod'] = '銀行振込';
            }else{
                $_tmpData['payMethod'] = '決済不要';
            }

            $bank_info = $info['info']->bank_info;
            $arr =  explode("|",$bank_info);
            $_tmpData['info'] = "依頼人名.".$arr[0]."様" .$arr[1]."日に" .$arr[2]. "円入金済み";

            foreach ($category as $_category){
                if($_category['id'] ==$info['info']->city ){
                    $_tmpData['city'] =$_category['name']; break;
                }
            }

            foreach($info['detail'] as $_=>$_value){
                $rowData = [];
                foreach($colums as $key=>$value){
                    $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key+1);

                    if(is_array($value[1])){
                        if(isset($value[1]['product'])){
                            $conf = $value[1]['product'];
                            if(property_exists($_value,$conf[0])){
                                $_val  = "";
                                if("product_id" == $conf[0]){
                                    $_key = $_value->{$conf[0]};
                                    if(isset($products[$_key]) && property_exists($products[$_key],$conf[1])){
                                        $_val = $products[$_key]->{$conf[1]};
                                        if(isset($conf[2])){
                                            if($conf[2] == "totalPrice" || $conf[2] == "totalPriceBuy"){
                                                $_val = $_val*$_value->count;

                                                $rowData[$conf[2]] = $_val;

                                            }else if($conf[2] == "totalCOU"){

                                                if(isset($category_ship[$_value->ship])){

                                                    if($info['info']->pay_method == 2 ||$info['info']->pay_method == 3 ){
                                                        $_val =  0;
                                                    }else{
                                                        var_dump($rowData);
                                                        $_val = 0;
                                                        if(isset($rowData['totalPriceBuy'])){
                                                            foreach ($category_ship[$_value->ship]->data as $uty=>$conf){
                                                                foreach ($conf as $_conf){

                                                                    if($this->IF_Start($rowData['totalPriceBuy'], $_conf) && $this->IF_End($rowData['totalPriceBuy'],$_conf) ){
                                                                        $_val = $_conf['value'];
                                                                        break;
                                                                    }
                                                                }
                                                                if($_val!=0) break;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                $sheet->setCellValue($nameCol.$start,$_val);
                            }else if($conf[0] == "callback"){
                                $_val = call_user_func_array($conf[1],[$start]);
                                $sheet->setCellValue($nameCol.$start,$_val);
                            }
                        }else if(isset($value[1]['callback'])){
                            $conf = $value[1]['callback'];
                            $_val = call_user_func_array($conf,[$start]);
                            $sheet->setCellValue($nameCol.$start,$_val);
                        }
                    }else{
                        if(isset($_tmpData[$value[1]])){
                            $sheet->setCellValue($nameCol.$start,$_tmpData[$value[1]]);
                        }else{
                            $val = $_value->{$value[1]};
                            $sheet->setCellValue($nameCol.$start, $val);
                        }
                    }
                }
                $start++;
            }
        }
        $sheet->setCellValue("K".$start, "=SUM(K".$defaultStart.":K".($start-1).")");
        $sheet->setCellValue("P".$start, "=SUM(P".$defaultStart.":P".($start-1).")");
        $sheet->setCellValue("R".$start, "=SUM(R".$defaultStart.":R".($start-1).")");
        $sheet->setCellValue("Q".$start, "=SUM(Q".$defaultStart.":Q".($start-1).")");
        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path().'/uploads/exports/Cocolala.xlsx');
        return redirect()->route('backend:shop_ja:order:list');
    }

    public function OHGA(){

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->createSheet();
        $spreadsheet->getSheet(1)->setTitle('Sheet2');

        $sheet->setTitle("Sheet1");
        $spreadsheet->getProperties()
            ->setTitle('PHP Download Example')
            ->setSubject('A PHPExcel example')
            ->setDescription('A simple example for PhpSpreadsheet. This class replaces the PHPExcel class')
            ->setCreator('php-download.com')
            ->setLastModifiedBy('php-download.com');

        $title1 = "株式会社ヤマダ 様 注文フォーマット";
        $title2 = "見本";

        $info = "依頼人名. VO HOANG 様 22日に 7410 円入金済み";

        $sheet->setCellValue('B1', $title1);
        $sheet->setCellValue('F2', $title2);
        $sheet->setCellValue('P2', $info);

        $styleArray = array(
            'font'  => array(
                'size'  => 9,
                'name' => 'Times New Roman'
            ));
        $style_header = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'color' => array('rgb'=>'FFE100'),
            ),
            'borders' => [
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_DOTTED,
                    'color' => array('rgb'=>'000000')
                ),
            ],
            'font' => array(
                'size' => 10
            )
        );
        $sheet->getStyle('B1')->applyFromArray($styleArray);
        $sheet->getStyle('F2')->applyFromArray($styleArray);
        $sheet->getStyle('P2')->applyFromArray(array(
                'font'  => array(
                    'size'  => 9,
                    'name' => 'Times New Roman',
                    'color' => array('rgb' => '0070c0'),
                ),
            )
        );
        $sheet->getStyle('A3:T3')->applyFromArray( $style_header );

        $colums = [
            ["注文日",'dateCreate',10,9],//A
            ["支払区分",'payMethod',10,9],//B
            ["配送先電話番号",'phone',10,9],//C
            ["配送先郵便番号",'postal_code',9,9],//D
            ["配送先都道府県",'city',14,9],//E
            ["配送先住所",'address',18,9],//F
            ["配送先氏名",'fullname',18,9],//G
            ["品番",['product'=>['product_id','code']],10,9],//H
            ["商品名",['product'=>['product_id','title']],18,9],//I
            ["単価",['product'=>['product_id','price']],15,9],//J
            ["数量",'count',15,9],//K
            ["到着希望日",'day_ship',15,9],//L
            ["配送希望時間帯",'time_ship',15,9],//M
            ["別途送料",'price_ship',15,9],//N
            ["仕入金額",['product'=>['product_id','price','totalPrice']],15,9],//O
            ["代引き請求金額",['product'=>['product_id','price_buy','totalPriceBuy']],15,9],//P
            ["代引き手数料",['product'=>['product_id','price_buy','totalCOU']],15,9],//Q
            ["紹介料",['callback'=>function($index){return '=IF(J'.$index.'="","",P'.$index.'-J'.$index.'*K'.$index.'-N'.$index.'-Q'.$index.')';}],15,9],//R
            ["追跡番号",'tracking',15,9],//S
            ["振込み情報",'info',25,9],//T
            ["",'link',25,9],//U
        ];
        $start=3;
        foreach($colums as $key=>$value){
            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key+1);
            $sheet->setCellValue($nameCol.$start, $value[0])->getStyle($nameCol.$start)->applyFromArray(array(
                    'font'  => array(
                        'size'  => $value[3]
                    ),
                )
            );
            if($value[2] > 0){
                $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($value[2]);
            }
        }
        $start++;
        $defaultStart = $start;
        $lastIndex = "";
        $datas = [];

        $company = "37";

        $orders = [

        ];

        $products =  DB::table('shop_product')->get()->keyBy('id')->all();
        $results =  DB::table('shop_order')->where('status',2)->get()->all();

        foreach ($results as $result){
            $row = [
                'info'=>$result
            ];
            $row['detail'] =  DB::table('shop_order_detail')
                ->where('order_id',$result->id)->where('company',$company)->get()->toArray();
            if(count($row['detail']) > 0 ){
                $datas[] = $row;
                $orders[$result->id] = count($row['detail']);
            }
        }

        echo "<pre>";

//            print_r($products);
        print_r($orders);
        print_r($datas);

        //支払い方法 Phương thức thanh toán
        //代金引換 Thanh toán khi giao hàng 1
        // 銀行振込 Chuyển khoản ngân hàng 2
        // 決済不要 Không cần thanh toán 3
        $category = config_get("category", "shop-ja:japan:category");
        $category_ship = get_category_type('shop-ja:japan:category:com-ship');

        foreach ($datas as $info){

            $_tmpData = [
                'dateCreate'=>date('d',strtotime($info['info']->created_at))."日",
                'payMethod'=>$info['info']->pay_method,
                'phone'=>$info['info']->phone,
                'postal_code'=>$info['info']->postal_code,
                'city'=>$info['info']->city,
                'address'=>$info['info']->address,
                'fullname'=>$info['info']->fullname,
                'day_ship'=>date('d/m/Y',strtotime($info['info']->day_ship)) ,
                'time_ship'=>$info['info']->time_ship,
                'info'=>"",
                'link'=>$info['info']->link,
            ];

            if($info['info']->pay_method == 1){
                $_tmpData['payMethod'] = '代金引換';
            }else if($info['info']->pay_method == 2){
                $_tmpData['payMethod'] = '銀行振込';
            }else{
                $_tmpData['payMethod'] = '決済不要';
            }

            $bank_info = $info['info']->bank_info;
            $arr =  explode("|",$bank_info);
            $_tmpData['info'] = "依頼人名.".$arr[0]."様" .$arr[1]."日に" .$arr[2]. "円入金済み";

            foreach ($category as $_category){
                if($_category['id'] ==$info['info']->city ){
                    $_tmpData['city'] =$_category['name']; break;
                }
            }

            foreach($info['detail'] as $_=>$_value){
                $rowData = [];
                foreach($colums as $key=>$value){
                    $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key+1);
                    if(is_array($value[1])){
                        if(isset($value[1]['product'])){
                            $conf = $value[1]['product'];
                            if(property_exists($_value,$conf[0])){
                                $_val  = "";
                                if("product_id" == $conf[0]){
                                    $_key = $_value->{$conf[0]};
                                    if(isset($products[$_key]) && property_exists($products[$_key],$conf[1])){
                                        $_val = $products[$_key]->{$conf[1]};
                                        if(isset($conf[2])){
                                            if($conf[2] == "totalPrice" || $conf[2] == "totalPriceBuy"){
                                                $_val = $_val*$_value->count;

                                                $rowData[$conf[2]] = $_val;

                                            }else if($conf[2] == "totalCOU"){

                                                if(isset($category_ship[$_value->ship])){

                                                    if($info['info']->pay_method == 2 ||$info['info']->pay_method == 3 ){
                                                        $_val =  0;
                                                    }else{

                                                        $_val = 0;

                                                        if(isset($rowData['totalPriceBuy'])){
                                                            foreach ($category_ship[$_value->ship]->data as $uty=>$conf){
                                                                foreach ($conf as $_conf){

                                                                    if($this->IF_Start($rowData['totalPriceBuy'], $_conf) && $this->IF_End($rowData['totalPriceBuy'],$_conf) ){
                                                                        $_val = $_conf['value'];
                                                                        break;
                                                                    }
                                                                }
                                                                if($_val!=0) break;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                $sheet->setCellValue($nameCol.$start,$_val);
                            }else if($conf[0] == "callback"){
                                $_val = call_user_func_array($conf[1],[$start]);
                                $sheet->setCellValue($nameCol.$start,$_val);
                            }
                        }else if(isset($value[1]['callback'])){
                            $conf = $value[1]['callback'];
                            $_val = call_user_func_array($conf,[$start]);
                            $sheet->setCellValue($nameCol.$start,$_val);
                        }
                    }else{
                        if(isset($_tmpData[$value[1]])){
                            $sheet->setCellValue($nameCol.$start,$_tmpData[$value[1]]);
                        }else{
                            $val = $_value->{$value[1]};
                            $sheet->setCellValue($nameCol.$start, $val);
                        }
                    }
                }
                $start++;
            }
        }

        $sheet->setCellValue("K".$start, "=SUM(K".$defaultStart.":K".($start-1).")");
        $sheet->setCellValue("P".$start, "=SUM(P".$defaultStart.":P".($start-1).")");
        $sheet->setCellValue("R".$start, "=SUM(R".$defaultStart.":R".($start-1).")");
        $sheet->setCellValue("Q".$start, "=SUM(Q".$defaultStart.":Q".($start-1).")");

        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path().'/uploads/exports/OHGA.xlsx');
        return redirect()->route('backend:shop_ja:order:list');
    }
    public function FUKUI(){

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->createSheet();
        $spreadsheet->getSheet(1)->setTitle('Sheet2');

        $sheet->setTitle("Sheet1");
        $spreadsheet->getProperties()
            ->setTitle('PHP Download Example')
            ->setSubject('A PHPExcel example')
            ->setDescription('A simple example for PhpSpreadsheet. This class replaces the PHPExcel class')
            ->setCreator('php-download.com')
            ->setLastModifiedBy('php-download.com');


        $titles = [
            ['A1','グエン様専用注文フォーマット'],
            ['G2','別途送料'],
            ['H2','北海道：800円'],
            ['H2','沖縄：1200円'],
            ['N5','VU THI 様 14 日に 6250  円入金済み'],
        ];
        foreach ($titles as $title){
            $sheet->setCellValue($title[0], $title[1]);
        }
        $titles = [
            '選択','入力','選択','入力','入力','入力','入力','入力','入力','不要','入力','入力','不要','不要','入力'
        ];
        $start = 6;
        foreach($titles as $key=>$value){
            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key+1);
            $sheet->setCellValue($nameCol.$start, $value);
        }

        $styleArray = array(
            'font'  => array(
                'size'  => 9,
                'name' => 'Times New Roman'
            ));
        $style_header = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'color' => array('rgb'=>'FFE100'),
            ),
            'borders' => [
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_DOTTED,
                    'color' => array('rgb'=>'000000')
                ),
            ],
            'font' => array(
                'size' => 10
            )
        );
        $sheet->getStyle('B1')->applyFromArray($styleArray);
        $sheet->getStyle('F2')->applyFromArray($styleArray);
        $sheet->getStyle('P2')->applyFromArray(array(
                'font'  => array(
                    'size'  => 9,
                    'name' => 'Times New Roman',
                    'color' => array('rgb' => '0070c0'),
                ),
            )
        );



//        $colums = [
//            ["支払区分",'payMethod',10,9],//A Phương thức thanh toán
//            ["注文日",'dateCreate',10,9],//B ngày giao hàng
//            ["配送先電話番号",'phone',10,9],//C //
//            ["配送先郵便番号",'postal_code',9,9],//D
//            ["配送先都道府県",'city',14,9],//E
//            ["配送先住所",'address',18,9],//F
//            ["配送先氏名",'fullname',18,9],//G
//            ["品番",['product'=>['product_id','code']],10,9],//H
//            ["商品名",['product'=>['product_id','title']],18,9],//I
//            ["単価",['product'=>['product_id','price']],15,9],//J
//            ["数量",'count',15,9],//K
//            ["到着希望日",'day_ship',15,9],//L
//            ["配送希望時間帯",'time_ship',15,9],//M
//            ["別途送料",'price_ship',15,9],//N
//            ["仕入金額",['product'=>['product_id','price','totalPrice']],15,9],//O
//            ["代引き請求金額",['product'=>['product_id','price_buy','totalPriceBuy']],15,9],//P
//            ["代引き手数料",['product'=>['product_id','price_buy','totalCOU']],15,9],//Q
//            ["紹介料",['callback'=>function($index){return '=IF(J'.$index.'="","",P'.$index.'-J'.$index.'*K'.$index.'-N'.$index.'-Q'.$index.')';}],15,9],//R
//            ["追跡番号",'tracking',15,9],//S
//            ["振込み情報",'info',25,9],//T
//            ["",'link',25,9],//U
//        ];
        $colums = [
            ["支払区分",'payMethod',10,9],//A Phương thức thanh toán
            ["到着希望日",'day_ship',15,9],//B ngày giao hàng
            ["配送希望時間帯",'time_ship',15,9],//C Giờ nhận
            ["配送先氏名",'fullname',18,9],//D Họ và tên khách hàng
            ["配送先郵便番号",'postal_code',9,9],// E Mã bưu điện
            ["配送先都道府県",'city',14,9], // F Tỉnh
            ["配送先住所",'address',18,9], // G Địa chỉ giao hàng
            ["配送先電話番号",'phone',10,9], // H Số điện thoại
            ["別途送料",'price_ship',15,9], //I Phí Ship
            ["紹介料",['callback'=>function($index){return '=IF(N'.$index.'="","",K'.$index.'-N'.$index.'*O'.$index.'-I'.$index.')';}],15,9],// Lợi nhuận J
            ["仕入金額",['product'=>['product_id','price','totalPrice']],15,9], // Tổng giá đơn hàng K
            ["品番",['product'=>['product_id','code']],10,9], // Mã sản phẩm L
            ["商品名",['product'=>['product_id','title']],18,9], // Tên sản phẩm M
            ["単価",['product'=>['product_id','price']],15,9], // Giá nhập N
            ["数量",'count',15,9], // Số lượng O
        ];
        $start=7;
        $sheet->getStyle('A'.$start.':'.PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)).$start)->applyFromArray( $style_header );
        foreach($colums as $key=>$value){
            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key+1);
            $sheet->setCellValue($nameCol.$start, $value[0])->getStyle($nameCol.$start)->applyFromArray(array(
                    'font'  => array(
                        'size'  => $value[3]
                    ),
                )
            );
            if($value[2] > 0){
                $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($value[2]);
            }
        }
        $start++;
        $defaultStart = $start;
        $lastIndex = "";
        $datas = [];

        $company = "36";

        $orders = [

        ];

        $products =  DB::table('shop_product')->get()->keyBy('id')->all();
        $results =  DB::table('shop_order')->where('status',2)->get()->all();

        foreach ($results as $result){
            $row = [
                'info'=>$result
            ];
            $row['detail'] =  DB::table('shop_order_detail')
                ->where('order_id',$result->id)->where('company',$company)->get()->toArray();
            if(count($row['detail']) > 0 ){
                $datas[] = $row;
                $orders[$result->id] = count($row['detail']);
            }
        }

        echo "<pre>";

//            print_r($products);
        print_r($orders);
        print_r($datas);

        //支払い方法 Phương thức thanh toán
        //代金引換 Thanh toán khi giao hàng 1
        // 銀行振込 Chuyển khoản ngân hàng 2
        // 決済不要 Không cần thanh toán 3
        $category = config_get("category", "shop-ja:japan:category");
        $category_ship = get_category_type('shop-ja:japan:category:com-ship');

        foreach ($datas as $info){

            $_tmpData = [
                'dateCreate'=>date('d',strtotime($info['info']->created_at))."日",
                'payMethod'=>$info['info']->pay_method,
                'phone'=>$info['info']->phone,
                'postal_code'=>$info['info']->postal_code,
                'city'=>$info['info']->city,
                'address'=>$info['info']->address,
                'fullname'=>$info['info']->fullname,
                'day_ship'=>date('d/m/Y',strtotime($info['info']->day_ship)) ,
                'time_ship'=>$info['info']->time_ship,
                'info'=>"",
                'link'=>$info['info']->link,
            ];

            if($info['info']->pay_method == 1){
                $_tmpData['payMethod'] = '代金引換';
            }else if($info['info']->pay_method == 2){
                $_tmpData['payMethod'] = '銀行振込';
            }else{
                $_tmpData['payMethod'] = '決済不要';
            }

            $bank_info = $info['info']->bank_info;
            $arr =  explode("|",$bank_info);
            $_tmpData['info'] = "依頼人名.".$arr[0]."様" .$arr[1]."日に" .$arr[2]. "円入金済み";

            foreach ($category as $_category){
                if($_category['id'] ==$info['info']->city ){
                    $_tmpData['city'] =$_category['name']; break;
                }
            }

            foreach($info['detail'] as $_=>$_value){
                $rowData = [];
                foreach($colums as $key=>$value){
                    $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key+1);
                    if(is_array($value[1])){
                        if(isset($value[1]['product'])){
                            $conf = $value[1]['product'];
                            if(property_exists($_value,$conf[0])){
                                $_val  = "";
                                if("product_id" == $conf[0]){
                                    $_key = $_value->{$conf[0]};
                                    if(isset($products[$_key]) && property_exists($products[$_key],$conf[1])){
                                        $_val = $products[$_key]->{$conf[1]};
                                        if(isset($conf[2])){
                                            if($conf[2] == "totalPrice" || $conf[2] == "totalPriceBuy"){
                                                $_val = $_val*$_value->count;

                                                $rowData[$conf[2]] = $_val;

                                            }else if($conf[2] == "totalCOU"){

                                                if(isset($category_ship[$_value->ship])){

                                                    if($info['info']->pay_method == 2 ||$info['info']->pay_method == 3 ){
                                                        $_val =  0;
                                                    }else{

                                                        $_val = 0;

                                                        if(isset($rowData['totalPriceBuy'])){
                                                            foreach ($category_ship[$_value->ship]->data as $uty=>$conf){
                                                                foreach ($conf as $_conf){

                                                                    if($this->IF_Start($rowData['totalPriceBuy'], $_conf) && $this->IF_End($rowData['totalPriceBuy'],$_conf) ){
                                                                        $_val = $_conf['value'];
                                                                        break;
                                                                    }
                                                                }
                                                                if($_val!=0) break;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                $sheet->setCellValue($nameCol.$start,$_val);
                            }else if($conf[0] == "callback"){
                                $_val = call_user_func_array($conf[1],[$start]);
                                $sheet->setCellValue($nameCol.$start,$_val);
                            }
                        }else if(isset($value[1]['callback'])){
                            $conf = $value[1]['callback'];
                            $_val = call_user_func_array($conf,[$start]);
                            $sheet->setCellValue($nameCol.$start,$_val);
                        }
                    }else{
                        if(isset($_tmpData[$value[1]])){
                            $sheet->setCellValue($nameCol.$start,$_tmpData[$value[1]]);
                        }else{
                            $val = $_value->{$value[1]};
                            $sheet->setCellValue($nameCol.$start, $val);
                        }
                    }
                }
                $start++;
            }
        }
        $sheet->getStyle('K6:K'.$start)->applyFromArray(array(
                'font'  => array(
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
        $writer->save(public_path().'/uploads/exports/FUKUI.xlsx');
        return redirect()->route('backend:shop_ja:order:list');
    }
}
