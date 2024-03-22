<?php
namespace ShopJa\Http\Controllers;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \PhpOffice\PhpSpreadsheet;
use \PhpOffice\PhpSpreadsheet\Style\Border;
use \PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class DashboardController extends \Admin\Http\Controllers\DashboardController
{
    public function analytics(Request $request){
        $data = $request->all();
        $datas = [];
        \DB::enableQueryLog();
        $type = "";
        $response = [];
        if(isset($data['act'])){
            if($data['act'] == 'line'){
                if(isset($data['type'])){
                    $type = $data['type'];
                    DB::connection()->enableQueryLog();
                    $company = $data['conpany'];
                    $month = isset($data['month'])?$data['month']:date('m');
                    $date_start = isset($data['date_start'][0])?$data['date_start'][2].'-'.$data['date_start'][1].'-'.$data['date_start'][0]:"";
                    $date_end = isset($data['date_end'][0])?$data['date_end'][2].'-'.$data['date_end'][1].'-'.$data['date_end'][0]:"";

                    

                    $admin_id = base64_decode($data['user_id']);
                    $results = [];
                    if($company != 'KOGYJA'){
                        $excel = DB::table('shop_order_excel');
                        $excel->where('fullname','!=','');
                        $excel->where('public','1');
                        if(!empty($admin_id)){
                            $excel->where('admin_id',$admin_id);
                        }
                        if(!empty($company)){
                            $excel->where('company',$company);
                        }else {
                            $excel->where('company', '!=', 'KOGYJA');
                        }
                        if($type == 'week'){
                            $date_start = date("Y-m-d", strtotime('monday this week'));
                            $date_end = date("Y-m-d", strtotime('sunday this week'));

                            $excel->where('order_create_date','>=',$date_start." 00:00:00");
                            $excel->where('order_create_date','<=',$date_end." 23:59:59");
                        }
                        else if($type == 'month'){
                            if(!empty($date_start) && !empty($date_end)){
                                $excel->where('order_create_date','>=',$date_start." 00:00:00");
                                $excel->where('order_create_date','<=',$date_end." 23:59:59");
                            }
//                            $date_start = date('Y').'-01-01';
//                            $date_end = date('Y').'-12-31';;
//                            $excel->where('order_create_date','>=',$date_start." 00:00:00");
//                            $excel->where('order_create_date','<=',$date_end." 23:59:59");
                        } else if($type == 'year'){
                            if(!empty($date_start) && !empty($date_end)){
                                $excel->where('order_create_date','>=',$date_start." 00:00:00");
                                $excel->where('order_create_date','<=',$date_end." 23:59:59");
                            }
//                            $date_start = date('Y',strtotime('-5 year', time())).'-01-01';
//                            $date_end =  date('Y',strtotime('+5 year', time())).'-12-31';
//                            $excel->where('order_create_date','>=',$date_start." 00:00:00");
//                            $excel->where('order_create_date','<=',$date_end." 23:59:59");
                        }else{

                            if(!empty($date_start) && !empty($date_end)){
                                $excel->where('order_create_date','>=',$date_start." 00:00:00");
                                $excel->where('order_create_date','<=',$date_end." 23:59:59");
                            }

                            $type = "day";
                        }
                        $results = $excel->orderBy('order_create_date')->get()->all();

                    }
                    foreach ($results as $key=>$value){
                        $key = '';
                        if ($type == 'month') {
                            $key = date('Y-m', strtotime($value->order_create_date)) . "-01";
                            $score = strtotime($key);
                        } else if ($type == 'year') {
                            $key = date('Y', strtotime($value->order_create_date));
                            $score = strtotime($key);
                        }else if ($type == 'week') {
                            list($start_date, $end_date) = $this->x_week_range($value->order_create_date);
                            $key = $start_date.' '.$end_date;
                            $score = strtotime($start_date)+strtotime($end_date);
                        }
                        else{
                            $key = date('Y-m-d', strtotime($value->order_create_date));
                            $score = strtotime($key);
                        }
                        if(!isset($datas[$key])){
                            $datas[$key] = [
                                "count"=>0,
                                "rate"=>0,
                                $type =>$key,
                                "score"=>$score
                            ];
                        }
                        $datas[$key]["count"]++;
                        $datas[$key]["rate"]+= $value->order_price;
                    }

                    if(empty($company) || $company == 'KOGYJA') {
                        $excel = DB::table('shop_order_excel');
                        $excel->where('fullname', '!=', '');
                        $excel->where('public', '1');
                        $excel->where('type', 'Footer');
                        if (!empty($admin_id)) {
                            $excel->where('admin_id', $admin_id);
                        }
                        $excel->where('company', 'KOGYJA');
                        if($type == 'week'){

                            $date_start = date('Y').'-'.($month<10?"0".$month:$month).'-01';
                            $date_end = date('Y-m-d',strtotime('last day of this month', strtotime($date_start)));

                            $excel->where('order_create_date','>=',$date_start." 00:00:00");
                            $excel->where('order_create_date','<=',$date_end." 23:59:59");
                        }
                        else if($type == 'month'){
                            $date_start = date('Y').'-01-01';
                            $date_end = date('Y').'-12-31';
                            $excel->where('order_create_date','>=',$date_start." 00:00:00");
                            $excel->where('order_create_date','<=',$date_end." 23:59:59");
                        } else if($type == 'year'){
                            $date_start = date('Y',strtotime('-5 year', time())).'-01-01';
                            $date_end =  date('Y',strtotime('+5 year', time())).'-12-31';
                            $excel->where('order_create_date','>=',$date_start." 00:00:00");
                            $excel->where('order_create_date','<=',$date_end." 23:59:59");
                        }else{
                            if(!empty($date_start) && !empty($date_end)){
                                $excel->where('order_create_date','>=',$date_start." 00:00:00");
                                $excel->where('order_create_date','<=',$date_end." 23:59:59");
                            }
                            $excel->where('order_create_date','>=',$date_start." 00:00:00");
                            $excel->where('order_create_date','<=',$date_end." 23:59:59");
                            $type = "day";
                        }
                        $results = $excel->orderBy('order_create_date')->get()->all();

                        foreach ($results as $key => $value) {
                            $key = '';

                            if ($type == 'month') {
                                $key = date('Y-m', strtotime($value->order_create_date)) . "-01";
                                $score = strtotime($key);
                            } else if ($type == 'year') {
                                $key = date('Y', strtotime($value->order_create_date));
                                $score = strtotime($key);
                            }else if ($type == 'week') {
                                list($start_date, $end_date) = $this->x_week_range($value->order_create_date);
                                $key = $start_date.' '.$end_date;
                                $score = strtotime($start_date)+strtotime($end_date);
                            }
                            else{
                                $key = date('Y-m-d', strtotime($value->order_create_date));
                                $score = strtotime($key);
                            }
                            if (!isset($datas[$key])) {
                                $datas[$key] = [
                                    "count" => 0,
                                    "rate" => 0,
                                    $type => $key,
                                    "score"=>$score
                                ];
                            }
                            $datas[$key]["count"]++;
                            $datas[$key]["rate"] += $value->order_price;
                        }
                    }
                    usort($datas, function ($element1,$element2) use ($type){
                        return $element1["score"] - $element2["score"];
                    });
                    $response = [
                        "lists"=>$datas,
                        "sql"=>logs_sql(),
                        'xkey'=>$type
                    ];
                    if(isset($data['export']) && $data['export'] == "true"){
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
                        $roles = DB::table('admin')->get()->keyBy('id');
                        $titles = [
                            ['A1', "Danh sách"],
                            ['B1', empty($company)?"Tất cả":$company],
                            ['A2', "Người nhập đơn"],
                            ['B2',(isset($roles[$admin_id])?$roles[$admin_id]->username:"Tất cả")],
                            ['A3', "Ngày xuất"],
                            ['B3', $date_start.' - '.$date_end]
                        ];
                        $spreadsheet->getActiveSheet()->mergeCells("B1:C1" );
                        $spreadsheet->getActiveSheet()->mergeCells("B2:C2" );
                        $spreadsheet->getActiveSheet()->mergeCells("B3:C3" );
                        foreach ($titles as $title) {
                            $sheet->setCellValue($title[0], $title[1]);
                        }
                        $titles = [
                            'Ngày', 'Số lượng','Lợi nhuận'
                        ];
                        $start = 5;
                        foreach ($titles as $key => $value) {
                            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
                            $sheet->setCellValue($nameCol . $start, $value)->getStyle($nameCol . $start)->applyFromArray(array(
                                    'font' => array(
                                        'color' => array('rgb' => 'ff0000'),

                                        'name' => 'ＭＳ Ｐゴシック'
                                    ),
                                )
                            );
                            $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth(20);
                        }
                        $start++;
                        foreach ($datas as $key=>$rows){
                            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1);
                            $sheet->setCellValue($nameCol . $start, $rows['day']);
                            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(2);
                            $sheet->setCellValue($nameCol . $start, $rows['count']);
                            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(3);
                            $sheet->setCellValue($nameCol . $start, $rows['rate']);
                            $start++;
                        }
                        $writer = new Xlsx($spreadsheet);
                        $path = '/uploads/dashboard';

                        $file = new \Illuminate\Filesystem\Filesystem();
                        if (!$file->isDirectory(public_path() . $path)) {
                            $file->makeDirectory(public_path() . $path);
                        }
                        $path = $path . '/' . date('Y-m-d').'-'.$company.'-'.(isset($roles[$admin_id])?$roles[$admin_id]->username:"none");
                        $path2 = $path . '-date.xlsx';
                        $writer->save(public_path() . $path2);
                        $response['link'] = url($path2).'?time='.time();
                    }
                }
            }else if($data['act'] == 'circle'){
                if(isset($data['type'])){
                    $type = $data['type'];
                    $roles = DB::table('admin')->get()->keyBy('id');

                    $company = $data['conpany'];
                    $admin_id = "";
                    $results = [];
                    $totals = 0;
                    if($company != 'KOGYJA'){
                        $excel = DB::table('shop_order_excel');
                        $excel->where('fullname','!=','');
                        $excel->where('public','1');
                        if(!empty($admin_id)){
                            $excel->where('admin_id',$admin_id);
                        }
                        if(!empty($company)){
                            $excel->where('company',$company);
                        }else {
                            $excel->where('company', '!=', 'KOGYJA');
                        }
                        if($type == 'week'){
                            $date_start = date('Y-m').'-01';
                            $date_end = date('Y-m-d',strtotime('last day of this month', time()));
                            $excel->where('order_create_date','>=',$date_start." 00:00:00");
                            $excel->where('order_create_date','<=',$date_end." 23:59:59");
                        }
                        else if($type == 'month'){
                            $date_start = date('Y').'-01-01';
                            $date_end = date('Y').'-12-31';;
                            $excel->where('order_create_date','>=',$date_start." 00:00:00");
                            $excel->where('order_create_date','<=',$date_end." 23:59:59");
                        } else if($type == 'year'){
                            $date_start = date('Y',strtotime('-5 year', time())).'-01-01';
                            $date_end =  date('Y',strtotime('+5 year', time())).'-12-31';
                            $excel->where('order_create_date','>=',$date_start." 00:00:00");
                            $excel->where('order_create_date','<=',$date_end." 23:59:59");
                        }else{
                            $date_start = date('Y-m').'-01';
                            $date_end = date('Y-m-d',strtotime('last day of this month', time()));
                            $excel->where('order_create_date','>=',$date_start." 00:00:00");
                            $excel->where('order_create_date','<=',$date_end." 23:59:59");
                            $type = "day";
                        }
                        $results = $excel->orderBy('order_create_date')->get()->all();
                    }
                    foreach ($results as $key=>$value){
                        $key = $value->admin_id;

                        if(!isset($datas[$key])){
                            $datas[$key] = [
                                "count"=>0,
                                "rate"=>0,
                                $type =>$key
                            ];
                            $datas[$key]['name'] = $roles[$key]->username;
                        }
                        $totals++;
                        $datas[$key]["count"]++;
                        $datas[$key]["rate"]+= $value->order_price;
                    }
                    if(empty($company) || $company == 'KOGYJA') {
                        $excel = DB::table('shop_order_excel');
                        $excel->where('fullname', '!=', '');
                        $excel->where('public', '1');
                        $excel->where('type', 'Footer');
                        if (!empty($admin_id)) {
                            $excel->where('admin_id', $admin_id);
                        }
                        $excel->where('company', 'KOGYJA');
                        if($type == 'week'){
                            $date_start = date('Y-m').'-01';
                            $date_end = date('Y-m-d',strtotime('last day of this month', time()));
                            $excel->where('order_create_date','>=',$date_start." 00:00:00");
                            $excel->where('order_create_date','<=',$date_end." 23:59:59");
                        }
                        else if($type == 'month'){
                            $date_start = date('Y').'-01-01';
                            $date_end = date('Y').'-12-31';
                            $excel->where('order_create_date','>=',$date_start." 00:00:00");
                            $excel->where('order_create_date','<=',$date_end." 23:59:59");
                        } else if($type == 'year'){
                            $date_start = date('Y',strtotime('-5 year', time())).'-01-01';
                            $date_end =  date('Y',strtotime('+5 year', time())).'-12-31';
                            $excel->where('order_create_date','>=',$date_start." 00:00:00");
                            $excel->where('order_create_date','<=',$date_end." 23:59:59");
                        }else{
                            $date_start = date('Y-m').'-01';
                            $date_end = date('Y-m-d',strtotime('last day of this month', time()));
                            $excel->where('order_create_date','>=',$date_start." 00:00:00");
                            $excel->where('order_create_date','<=',$date_end." 23:59:59");
                            $type = "day";
                        }
                        $results = $excel->orderBy('order_create_date')->get()->all();

                        foreach ($results as $key => $value) {
                            $key = $value->admin_id;
                            if (!isset($datas[$key])) {
                                $datas[$key] = [
                                    "count" => 0,
                                    "rate" => 0,
                                    $type => $key,
                                ];
                                $datas[$key]['name'] = $roles[$key]->username;

                            }
                            $totals++;
                            $datas[$key]["count"]++;
                            $datas[$key]["rate"] += $value->order_price;
                        }
                    }
                    $response = [
                        "lists"=>$datas,
                        "sql"=>logs_sql(),
                        'totals'=>$totals
                    ];
                }
            }
        }
        return response()->json($response);
    }
    function x_week_range($date) {
        $ts = strtotime($date);
        $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
        $start = strtotime('+1 day',$start);
        return array(date('Y-m-d', $start), date('Y-m-d', strtotime('next sunday', $start)));
    }
    public function list(Request $request)
    {

        $date_start = $request->get('date_start',date('Y-m-d'));
        $date_end = $request->get('date_end',date('Y-m-d'));



        $categorys = config_get("category", "shop-ja:product:category");
        $this->data['analytics']['category'] = [];
        $user_id = null;
        \DB::enableQueryLog();
        if(!is_null($request->id)){
            $user_id = base64_decode($request->id);
            $this->breadcrumb(z_language("QL CTV"), route('backend:dashboard:list'));
            $this->breadcrumb(z_language("Thông tin"), "");
        }else if(!Auth::user()->IsAclCheck(acl_alias("dashboard:all"))){
            $user_id = Auth::user()->id;
        }
        $this->data['roles'] = DB::table('role')->get()->keyBy('id');
        $this->data['users'] = [];
        $admins = DB::table('admin')->get()->all();
        foreach ($admins as $admin){
            if(!isset($this->data['users'][$admin->role_id])){
                $this->data['users'][$admin->role_id] = [];
            }
            $this->data['users'][$admin->role_id][$admin->id] = $admin;
        }
        foreach($categorys as $category){

            $query = DB::table('shop_order_excel')
                ->where('fullname','!=','')
                ->where('public',1)
                ->where('company',$category['name']);
            if(!is_null($user_id) && !empty($user_id)){
                $query->where('admin_id',$user_id);
            }
            if($category['name'] == "KOGYJA"){
                $query->where('type',"Info");
            }
            if(!empty($date_start) && !empty($date_end)){
                $query->where('order_create_date','>=',$date_start." 00:00:00");
                $query->where('order_create_date','<=',$date_end." 23:59:59");
            }
            $this->data['analytics']['category'][$category['name']] = [];
            $this->data['analytics']['category'][$category['name']]['count'] = $query->count();

            if($category['name'] == "KOGYJA"){
                $price = DB::table('shop_order_excel')
                    ->where('fullname','!=','')
                    ->where('company',$category['name'])
                    ->where('type','Footer')
                    ->where('public','1');
                if(!empty($date_start) && !empty($date_end)) {
                    $price->where('order_create_date', '>=', $date_start . " 00:00:00");
                    $price->where('order_create_date', '<=', $date_end . " 23:59:59");
                }
                if(!is_null($user_id) && !empty($user_id)){
                    $price->where('admin_id',$user_id);
                }
            }else{
                $price = DB::table('shop_order_excel')
                    ->where('fullname','!=','')
                    ->where('company',$category['name'])
                    ->where('public','1');

                if(!empty($date_start) && !empty($date_end)) {
                    $price->where('order_create_date', '>=', $date_start . " 00:00:00");
                    $price->where('order_create_date', '<=', $date_end . " 23:59:59");
                }
                if(!is_null($user_id) && !empty($user_id)){
                    $price->where('admin_id',$user_id);
                }
            }
            $this->data['analytics']['category'][$category['name']]['price'] =  $price->sum('order_price');
        }

        $total = DB::table('shop_order_excel')
            ->where('fullname','!=','')
            ->where('company','!=','KOGYJA')
            ->where('public','1');

        if(!is_null($user_id) && !empty($user_id)){
            $total->where('admin_id',$user_id);
        }

        if(!empty($date_start) && !empty($date_end)){
            $total->where('order_create_date','>=',$date_start." 00:00:00");
            $total->where('order_create_date','<=',$date_end." 23:59:59");
        }
        $total = $total->count();

        $total1 = DB::table('shop_order_excel')
            ->where('fullname','!=','')
            ->where('type','Info')
            ->where('company','=','KOGYJA')
            ->where('public','1');

        if(!is_null($user_id) && !empty($user_id)){
            $total1->where('admin_id',$user_id);
        }
        if(!empty($date_start) && !empty($date_end)){
            $total1->where('order_create_date','>=',$date_start." 00:00:00");
            $total1->where('order_create_date','<=',$date_end." 23:59:59");
        }
        $total1 = $total1->count();

        $this->data['analytics']['today'] = $total1 + $total;

        $total = DB::table('shop_order_excel')
            ->where('fullname','!=','')
            ->where('company','!=','KOGYJA')
            ->where('public','1');

        if(!is_null($user_id) && !empty($user_id)){
            $total->where('admin_id',$user_id);
        }

//        if(!empty($date_start) && !empty($date_end)){
//            $total->where('order_create_date','>=',$date_start." 00:00:00");
//            $total->where('order_create_date','<=',$date_end." 23:59:59");
//        }
        $total = $total->count();

        $total1 = DB::table('shop_order_excel')
            ->where('fullname','!=','')
            ->where('type','Info')
            ->where('company','=','KOGYJA')
            ->where('public','1');

        if(!is_null($user_id) && !empty($user_id)){
            $total1->where('admin_id',$user_id);
        }
//        if(!empty($date_start) && !empty($date_end)){
//            $total1->where('order_create_date','>=',$date_start." 00:00:00");
//            $total1->where('order_create_date','<=',$date_end." 23:59:59");
//        }
        $total1 = $total1->count();

        $this->data['analytics']['total'] = $total1 + $total;



        $this->data['analytics']['success'] = DB::table('shop_order_excel')
            ->where('fullname','!=','')
            ->where('status',1)->where('public','1');
        if(!is_null($user_id) && !empty($user_id)){
            $this->data['analytics']['success']->where('admin_id',$user_id);
        }
        if(!empty($date_start) && !empty($date_end)) {

            $this->data['analytics']['success']->where('order_create_date', '>=', $date_start . " 00:00:00");
            $this->data['analytics']['success']->where('order_create_date', '<=', $date_end . " 23:59:59");
        }
        $this->data['analytics']['success'] =  $this->data['analytics']['success']->where('status',1)->count();

        $this->data['analytics']['padding'] = DB::table('shop_order_excel')->where('public',1)
            ->where('fullname','!=','')
           ;
        if(!is_null($user_id) && !empty($user_id)){
            $this->data['analytics']['padding']->where('admin_id',$user_id);
        }
        if(!empty($date_start) && !empty($date_end)) {

            $this->data['analytics']['padding']->where('order_create_date', '>=', $date_start . " 00:00:00");
            $this->data['analytics']['padding']->where('order_create_date', '<=', $date_end . " 23:59:59");
        }
        $this->data['analytics']['padding'] = $this->data['analytics']['padding']->where('status',2)->count();
        $this->data['analytics']['cancel'] = DB::table('shop_order_excel')
            ->where('fullname','!=','')
            ;
        if(!is_null($user_id) && !empty($user_id)){
            $this->data['analytics']['cancel']->where('admin_id',$user_id);
        }
        if(!empty($date_start) && !empty($date_end)) {
            $this->data['analytics']['cancel']->where('order_create_date', '>=', $date_start . " 00:00:00");
            $this->data['analytics']['cancel']->where('order_create_date', '<=', $date_end . " 23:59:59");
        }
        $this->data['analytics']['cancel'] = $this->data['analytics']['cancel']->where('status',2)->count();

//        $this->data['analytics']['today'] = DB::table('shop_order_excel')
//            ->where('fullname','!=','')
//            ->where('public',1)
//            ->where('updated_at','>=',date('Y-m-d')." 00:00:00")
//            ->where('updated_at','<=',date('Y-m-d')." 23:59:59");
//        if(!is_null($user_id) && !empty($user_id)){
//            $this->data['analytics']['today']->where('admin_id',$user_id);
//        }
//        $this->data['analytics']['today'] =  $this->data['analytics']['today']->count();

        $this->data['analytics']['price'] = DB::table('shop_order_excel')
            ->where('fullname','!=','')
            ->where('public',1)
            ->where('order_create_date','>=',date('Y-m-d')." 00:00:00")
            ->where('order_create_date','<=',date('Y-m-d')." 23:59:59");

        if(!is_null($user_id) && !empty($user_id)){
            $this->data['analytics']['price']->where('admin_id',$user_id);
        }
        $this->data['analytics']['price'] =  $this->data['analytics']['price']->sum('order_price');
        $this->data['analytics']['sql'] = logs_sql();
        return $this->render('dashboard.user',[]);
    }
    public  function GetData($results,$exportAll){
        $datas = [];

        $categorys = config_get("category", "shop-ja:product:category");
        $names  = [];
        foreach($categorys as $category){
            if(!empty($company) && $company !=$category['name']){
                continue;
            }
            $names[] = $category['name'];

            $shop_products = DB::table('shop_product')->where('category_id',$category['id'])->get()->all();

            $this->data['products'][$category['name']] = [];
            $lock =  DB::table('shop_order_excel_lock')->where('name',$category['name'])->limit(1)->orderBy('updated_at','desc')->get()->all();
            $this->data['locks'][$category['name']] = isset($lock[0])?$lock[0]:[];
            try{
                foreach($shop_products as $shop_product){

                    $this->data['products'][$category['name']][$shop_product->id] =
                        [
                            'id' => $shop_product->id,
                            'name'=>$shop_product->description,
                            'image'=>$shop_product->image,
                            'title'=>$shop_product->title,
                            'group'=>$category['name'],
                            'data'=>(array) $shop_product
                        ];
                }
            }catch (\Exception $ex){

            }

        }
        foreach ($results as $resultAll){
            foreach ($resultAll as $result){
                if(!isset($datas[$result->company])){
                    $datas[$result->company] = [];
                }
                if(isset( $this->data['products'][$result->company])){
                    $_product = $this->data['products'][$result->company];

                    if($result->company == "FUKUI"){
                        $pay_method = "";
                        if($result->pay_method == 1){
                            $pay_method = "代金引換";
                        }else  if($result->pay_method == 2){
                            $pay_method = "銀行振込";
                        }else if($result->pay_method == 3){
                            $pay_method = "決済不要";
                        }
                        if($exportAll == true)
                        {
                            if(empty($result->fullname)){
                                continue;
                            }
                        }

                        $order_profit = $result->order_price;

                        $price = $result->price;

                        $price_buy = $result->price_buy;

                        $total_price = $result->total_price;
                        $total_price_buy = $result->total_price_buy;

                        if(isset($_product[$result->product_id]['data']['price_buy'])){
                            if($price == 0)
                                $price = $_product[$result->product_id]['data']['price'];
                            if($price_buy == 0)
                                $price_buy = $_product[$result->product_id]['data']['price_buy'];
                        }
                        if($total_price == 0)
                            $total_price = $price * $result->count;
                        if($total_price_buy == 0)
                            $total_price_buy = $price_buy * $result->count + $result->order_ship + $result->order_ship_cou + $result->price_buy_sale;
                        if($order_profit == 0){
                            $order_profit = $total_price_buy - $total_price - $result->order_ship - $result->order_ship_cou;
                        }

                        $datas[$result->company][] = [
                            $result->public,
                            $result->order_image,
                            $result->order_create_date,
                            $pay_method,
                            $result->order_date,
                            $result->order_hours,
                            $result->fullname,
                            $result->zipcode,
                            $result->province,
                            $result->address,
                            $result->phone,
                            $result->product_id,
                            $result->product_id,
                            $result->count,
                            $price,
                            $price_buy,
                            $result->order_ship,
                            $total_price,
                            $result->price_buy_sale,
                            $total_price_buy,
                            $result->order_ship_cou,
                            $order_profit,
                            (int)$result->rate*(int)$result->count+(int)$result->price_buy_sale,
                            $result->order_tracking,
                            $result->order_link,
                            $result->order_info,
                            $result->one_address==1,
                            $result->id,
                            $result->session_id,
                            $result->export == 1,
                            $result->token,
                            $result->admin,
                        ];
                    }else  if($result->company == "KOGYJA"){
                        $pay_method = "";
                        if($result->pay_method == 1){
                            $pay_method = "代金引換";
                        }else  if($result->pay_method == 2){
                            $pay_method = "銀行振込";
                        }else if($result->pay_method == 3){
                            $pay_method = "決済不要";
                        }
                        $order_profit = $result->order_price;

                        $price = $result->price;
                        $price_buy = $result->price_buy;
                        $total_price = $result->total_price;
                        $total_price_buy = $result->total_price_buy;
                        $datas[$result->company][] = [
                            $result->public,
                            $result->order_image,
                            $result->order_create_date,
                            $pay_method,
                            $result->phone,
                            $result->zipcode,
                            $result->province,
                            $result->address,
                            $result->fullname,
                            $result->product_id,
                            $result->product_id,
                            $result->count,
                            $result->total_count,
                            $price,
                            $price_buy,
                            $result->order_date,
                            $result->order_hours,
                            $result->order_ship,
                            $total_price,
                            $result->price_buy_sale,
                            $total_price_buy,
                            $result->order_ship_cou,
                            $order_profit,
                            $result->type == "Info"?$result->rate:0,
                            $result->order_tracking,
                            $result->order_link,
                            $result->order_info,
                            $result->one_address==1,
                            $result->id,
                            $result->type,
                            $result->session_id,
                            $result->export == 1,
                            $result->token,
                            $result->order_index,
                            $result->admin,
                        ];
                    } else{
                        $pay_method = "";
                        if($result->pay_method == 1){
                            $pay_method = "代金引換";
                        }else  if($result->pay_method == 2){
                            $pay_method = "銀行振込";
                        }else if($result->pay_method == 3){
                            $pay_method = "決済不要";
                        }
                        if($exportAll == true)
                        {
                            if(empty($result->fullname)){
                                continue;
                            }
                        }

                        $order_profit = $result->order_price;

                        $price = $result->price;

                        $price_buy = $result->price_buy;

                        $total_price = $result->total_price;
                        $total_price_buy = $result->total_price_buy;

                        if(isset($_product[$result->product_id]['data']['price_buy'])){
                            if($price == 0)
                                $price = $_product[$result->product_id]['data']['price'];
                            if($price_buy == 0)
                                $price_buy = $_product[$result->product_id]['data']['price_buy'];
                        }
                        if($total_price == 0)
                            $total_price = $price * $result->count;
                        if($total_price_buy == 0)
                            $total_price_buy = $price_buy * $result->count + $result->order_ship + $result->order_ship_cou + $result->price_buy_sale;
                        if($order_profit == 0){
                            $order_profit = $total_price_buy - $total_price - $result->order_ship - $result->order_ship_cou;
                        }

                        $datas[$result->company][] = [
                            $result->public,
                            $result->order_image,
                            $result->order_create_date,
                            $pay_method,
                            $result->phone,
                            $result->zipcode,
                            $result->province,
                            $result->address,
                            $result->fullname,
                            $result->product_id,
                            $result->product_id,
                            $result->count,
                            $price,
                            $price_buy,
                            $result->order_date,
                            $result->order_hours,
                            $result->order_ship,
                            $total_price,
                            $result->price_buy_sale,
                            $total_price_buy,
                            $result->order_ship_cou,
                            $order_profit,
                            (int)$result->rate*(int)$result->count+(int)$result->price_buy_sale,
                            $result->order_tracking,
                            $result->order_link,
                            $result->order_info,
                            $result->one_address==1,
                            $result->id,
                            $result->session_id,
                            $result->export == 1,
                            $result->token,
                            "",
                        ];
                    }
                }
            }
        }
        return $datas;
    }
    public function export(Request $request){
        $user_id = null;
        $this->file = new \Illuminate\Filesystem\Filesystem();
        if(!Auth::user()->IsAcl("dashboard:all")){
            $user_id = Auth::user()->id;
        }
        $data = $request->all();
        $date_start = "";
        $date_end = "";

        if($data['company'] == "YAMADA" || "FUKUI" == $data['company']  || "OHGA" == $data['company']){
            $date_end = $data['year'].'-'.$data['month'].'-20';
            $date_start = $data['year'].'-'.date('m',strtotime('-1 month',strtotime($date_end))).'-21';
        }else{
            $date_end = $data['year'].'-'.$data['month'].'-31';
            $date_start = $data['year'].'-'.$data['month'].'-01';
        }

        if($data['company'] == "KOGYJA"){
            $price = DB::table('shop_order_excel')
                ->where('fullname','!=','')
                ->where('company',$data['company'])
                ->where('public','1')
                ->where('success','0')
                ->where('type','Footer')
                ->where('order_create_date','>=',$date_start." 00:00:00")
                ->where('order_create_date','<=',$date_end." 23:59:59");
        }else{
            $price = DB::table('shop_order_excel')
                ->where('fullname','!=','')
                ->where('company',$data['company'])
                ->where('public','1')
                ->where('success','0')
                ->where('order_create_date','>=',$date_start." 00:00:00")
                ->where('order_create_date','<=',$date_end." 23:59:59");
        }

        if(!is_null($user_id)){
            $price->where('admin_id',$user_id);
        }
        $total_price = 0;
        $arr_prices = [];
        $results = $price->get()->all();
        foreach ($results as $key=>$value){
            $k = date('Y/m/d',strtotime($value->order_create_date));
            if(!isset($arr_prices[$k])){
                $arr_prices[$k] = 0;
            }
            if(is_null($user_id)){
                $total_price+= $value->order_price;
                $arr_prices[$k]+=$value->order_price;

            }else{
                if($data['company'] == "KOGYJA"){
                    $total_price+= $value->rate;
                    $arr_prices[$k]+=$value->rate;
                }else{
                    $total_price+= (int)$value->rate * (int)$value->count;
                    $arr_prices[$k]+=(int)$value->rate* (int)$value->count;

                }
            }
        }

        if($data['company'] == "FUKUI"){
            $this->date = strtotime($date_end);
            $this->DataCol = [
                "FUKUI"=>[
                    ["支払区分",'payMethod',10,9],//A Phương thức thanh toán
                    ["到着希望日",'order_date1',15,9],//B ngày giao hàng
                    ["配送希望時間帯",'order_hours',15,9],//C Giờ nhận
                    ["配送先氏名",'fullname',18,9],//D Họ và tên khách hàng
                    ["配送先郵便番号",'zipcode',9,9],// E Mã bưu điện
                    ["配送先都道府県",'province',14,9], // F Tỉnh
                    ["配送先住所",'address',18,9], // G Địa chỉ giao hàng
                    ["配送先電話番号",'phone',10,9], // H Số điện thoại
                    ["別途送料",'order_ship',15,9], //I Phí Ship
                    ["紹介料",['callback'=>function($index,$value){return (int)$value;},'key'=>'order_price'],15,9],// Lợi nhuận J
                    ["仕入金額",'order_total_price_buy',15,9], // Tổng giá đơn hàng K
                    ["品番",['product'=>['product_id','code']],10,9], // Mã sản phẩm L
                    ["商品名",['product'=>['product_name','title']],18,9], // Tên sản phẩm M
                    ["単価",'order_total_price',15,9], // Giá nhập N
                    ["数量",'count',15,9], // Số lượng O
                    ["",'order_tracking',15,9],
                ]
            ];
            $a = [];
            $a[$data['company']] = $results;
           return $this->FUKUI(['total'=>$total_price,'datas'=>$this->GetData($a,false)[$data['company']],'']);
        }



        $json = [];

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
//                    $title1 = "株式会社ヤマダ 様 注文フォーマット";
//                    $title2 = "見本";
//                    $info = "依頼人名. VO HOANG 様 22日に 7410 円入金済み";
//                    $sheet->setCellValue('B1', $title1);
//                    $sheet->setCellValue('F2', $title2);
//                    $sheet->setCellValue('P2', $info);
//                    $styleArray = array(
//                        'font'  => array(
//                            'size'  => 9,
//                            'name' => 'Times New Roman'
//                        ));
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
//                    $sheet->getStyle('B1')->applyFromArray($styleArray);
//                    $sheet->getStyle('F2')->applyFromArray($styleArray);
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
            ["配送先電話番号",'status',10,9],//B1
            ["配送先住所",'order_link',10,9],//B2
            ["配送先氏名",'fullname',10,9],//B3
            ["品番",'address',10,9],//B4
            ["商品名",['product'=>['product_id','title']],18,9],//I5
            ["単価",'total_count',10,9],//B6
            ["数量",['callback'=>function($index,$date){return date("d-m-Y",strtotime($date));},'key'=>'order_date'],10,9],//B7
            ["配送希望日",['callback'=>function($index,$date){return date("d-m-Y",strtotime($date));},'key'=>'order_hours'],10,9],//B8
            ["配送希望時間帯",'notification',10,9],//B9
            ["別途送料",'0',10,9],//B10
            ["仕入金額",'id',10,9],//B11
            ["振込金額",'id',10,9],//B12
            ["余分金",'id',10,9],//B13
            ["追跡番号",'id',10,9],//B14
            ["振込み情報",'id',10,9],//B15
        ];

        $start=3;
        $nameColList  = [];

        foreach($colums as $key=>$value){
            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key+1);
            if(is_array($value[1])){
                if(isset($value[1]['product'])){
                    $conf = $value[1]['product'];
                    $nameColList[$conf[0]] = $key;
                }else if(isset($value[1]['callback']) && isset($value[1]['key'])){
                    $nameColList[$value[1]['key']] = $key;
                }
            }else{
                $nameColList[$value[1]] = $key;
            }
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
     //   $date_end = $data['year'].'-'.$data['month'].'-20';
      //  $date_start = $data['year'].'-'.date('m',strtotime('-1 month',strtotime($date_end))).'-21';
        $date_start1 = $date_start;
        $strtotime_date_start1 = strtotime($date_start1);
        while ($date_start1 != $date_end){
            $start++;
            $date = date('Y/m/d',$strtotime_date_start1);
            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(12);
            $sheet->getCell($nameCol.$start)->setDataType(PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue($nameCol.$start,$date);
            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(13);
            $sheet->getCell($nameCol.$start)->setDataType(PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue($nameCol.$start,isset($arr_prices[$date])?$arr_prices[$date]:0);
            $date_start1 = date('Y-m-d',$strtotime_date_start1);
            $strtotime_date_start1 = strtotime('+1 day',$strtotime_date_start1);

        }
        $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(12);
        $sheet->getCell($nameCol.$start)->setDataType(PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue($nameCol.$start,"合計");
        $sheet->getStyle($nameCol.$start.':'. PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)).''.$start)->applyFromArray( array(
            'font'  => array(

                'name' => 'Times New Roman',
                'color' => array('rgb' => 'ff0000'),
            ),
        ) );
        $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(13);
        $sheet->getStyle($nameCol.$start.':'. PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)).''.$start)->applyFromArray( array(
            'font'  => array(

                'name' => 'Times New Roman',
                'color' => array('rgb' => 'ff0000'),
            ),
        ) );
        $sheet->getCell($nameCol.$start)->setDataType(PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue($nameCol.$start,$total_price);



        $file = new \Illuminate\Filesystem\Filesystem();
        $date = time();
        $writer = new Xlsx($spreadsheet);
        if( !$file->isDirectory(public_path().'/uploads/dashboard')){
            $file->makeDirectory(public_path().'/uploads/dashboard');
        }
        $path = '/uploads/dashboard/'.str_replace(__CLASS__.'::',"",__METHOD__);
        if( !$file->isDirectory(public_path().$path)){
            $file->makeDirectory(public_path().$path);
        }

        $path = $path.'/'.date('Y-m-d',$date);
        if( !$file->isDirectory(public_path().$path)){
            $file->makeDirectory(public_path().$path);
        }
        $filename = $data['month'].'月の総合計 '.$total_price.'円';
        $path = $path.'/'.$filename;
        if( !$file->isDirectory(public_path().$path)){
            $file->makeDirectory(public_path().$path);
        }
        $path2 = $path.'/'.$filename.'.xlsx';
        $writer->save(public_path().$path2);
        $json['link'] = url($path2);



        return response()->json($json   );
    }
    public function FUKUI($datas){

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
        $colums = [
            ["支払区分",'payMethod',10,9],//A Phương thức thanh toán
            ["到着希望日",'order_date1',15,9],//B ngày giao hàng
            ["配送希望時間帯",'order_hours',15,9],//C Giờ nhận
            ["配送先氏名",'fullname',18,9],//D Họ và tên khách hàng
            ["配送先郵便番号",'zipcode',9,9],// E Mã bưu điện
            ["配送先都道府県",'province',14,9], // F Tỉnh
            ["配送先住所",'address',18,9], // G Địa chỉ giao hàng
            ["配送先電話番号",'phone1',10,9], // H Số điện thoại
            ["別途送料",'order_ship',15,9], //I Phí Ship
            ["紹介料",['callback'=>function($index,$value){return (int)$value;},'key'=>'order_price'],15,9],// Lợi nhuận J
            ["仕入金額",'order_total_price_buy',15,9], // Tổng giá đơn hàng K
            ["品番",['product'=>['product_id','code']],10,9], // Mã sản phẩm L
            ["商品名",['product'=>['product_id','title']],18,9], // Tên sản phẩm M
            ["単価",'order_total_price',15,9], // Giá nhập N
            ["数量",'count',15,9], // Số lượng O
        ];
        $start=7;
        $sheet->getStyle('A'.$start.':'.PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)).$start)->applyFromArray( $style_header );
        $nameColList = [];
        foreach($colums as $key=>$value){
            $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key+1);
            $sheet->setCellValue($nameCol.$start, $value[0])->getStyle($nameCol.$start)->applyFromArray(array(
                    'font'  => array(
                        'size'  => $value[3]
                    ),
                )
            );
            if(is_array($value[1])){
                if(isset($value[1]['product'])){
                    $conf = $value[1]['product'];
                    $nameColList[$conf[0]] = $key;
                }else if(isset($value[1]['callback']) && isset($value[1]['key'])){
                    $nameColList[$value[1]['key']] = $key;
                }
            }else{
                $nameColList[$value[1]] = $key;
            }
            if($value[2] > 0){
                $spreadsheet->getActiveSheet()->getColumnDimension($nameCol)->setWidth($value[2]);
            }
        }
        $start++;
        $defaultStart = $start;
        $lastIndex = "";
        $company = "36";

        $orders = [

        ];
        $columns_value = array_flip(["status", "image", "timeCreate", "payMethod", "phone", "zipcode", "province", "address", "fullname", "product_id", "product_name", "count", "price", "price_buy", "order_date", "order_hours", "order_ship", "order_total_price", "price_buy_sale", "order_total_price_buy", "order_ship_cou", "order_price", "order_tracking", "order_link", "order_info", "one_address", "id", "session_id", "export", "token", "admin"]);
        $products =  DB::table('shop_product')->get()->keyBy('id')->all();
        $images = [];
        $ids = [];
        $order_total_price_buy = 0;
        foreach ($datas['datas'] as $key=>$values){
            $payMethod = "";

            if(empty($values[$nameColList['fullname']])){
                continue;
            }

            $image =  (isset($columns_value['image'])?$values[$columns_value['image']]:"");
            $order_id =  (isset($columns_value['id'])?$values[$columns_value['id']]:"");
            $ids[$order_id] = 1;
            $order_info =  (isset($columns_value['order_info'])?$values[$columns_value['order_info']]:"");
            $images[] = [$image,$order_info];
            $order_total_price_buy+=(isset($columns_value['order_total_price_buy'])?$values[$columns_value['order_total_price_buy']]:0);

            foreach($colums as $key=>$value){
                $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key+1);
                if(is_array($value[1])){
                    if(isset($value[1]['product'])){
                        $conf = $value[1]['product'];
                        $id = (isset($columns_value[$conf[0]])?$values[$columns_value[$conf[0]]]:"");
                        $_val = "";
                        if(isset($products[$id]) && property_exists($products[$id],$conf[1])){
                            $_val = $products[$id]->{$conf[1]};
                        }
                        $sheet->setCellValue($nameCol.$start,$_val);
                    }else if(isset($value[1]['callback']) && isset($value[1]['key'])){
                        $conf = $value[1]['callback'];
                        $_val = call_user_func_array($conf,[$start,(isset($columns_value[$value[1]['key']])?$values[$columns_value[$value[1]['key']]]:""),$nameCol.$start]);
                        $sheet->setCellValue($nameCol.$start,$_val);
                    }
                }else{
                    $v = (isset($columns_value[$value[1]])?$values[$columns_value[$value[1]]]:"");
                    $sheet->setCellValue($nameCol.$start,$v);
                    if($value[1] == "payMethod"){
                        $payMethod = $v;
                    }
                }
            }
            if($payMethod == "銀行振込"){
                $sheet->getStyle('A'.$start.':'. PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)).''.$start)->applyFromArray( array(
                    'font'  => array(

                        'name' => 'Times New Roman',
                        'color' => array('rgb' => '0070c0'),
                    ),
                ) );
            }else  if($payMethod == "決済不要"){
                $sheet->getStyle('A'.$start.':'. PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)).''.$start)->applyFromArray( array(
                    'font'  => array(

                        'name' => 'Times New Roman',
                        'color' => array('rgb' => 'ff0000'),
                    ),
                ) );
            }
            $start++;
        }

        $sheet->getStyle('K6:K'.$start)->applyFromArray(array(
                'font'  => array(
                    'color' => array('rgb' => 'ff0000'),
                ),
            )
        );


        $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nameColList['order_ship']+2);
        $sheet->getStyle($nameCol.$start.':'. PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)).''.$start)->applyFromArray( array(
            'font'  => array(

                'name' => 'Times New Roman',
                'color' => array('rgb' => 'ff0000'),
            ),
        ) );
        $sheet->setCellValue($nameCol.$start,$datas['total']);

        $nameCol = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nameColList['order_total_price_buy']+1);
        $sheet->getStyle($nameCol.$start.':'. PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colums)).''.$start)->applyFromArray( array(
            'font'  => array(

                'name' => 'Times New Roman',
                'color' => array('rgb' => 'ff0000'),
            ),
        ) );
        $sheet->setCellValue($nameCol.$start,$order_total_price_buy);

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
        $path = '/uploads/exports/'.str_replace(__CLASS__.'::',"",__METHOD__);
        if( !$this->file->isDirectory(public_path().$path)){
            $this->file->makeDirectory(public_path().$path);
        }
        $path = $path.'/'.date('Y-m-d', $this->date);
        if( !$this->file->isDirectory(public_path().$path)){
            $this->file->makeDirectory(public_path().$path);
        }

        $filename = date('m',$this->date).'5月の総合計 '.$order_total_price_buy.' 円';


        $path = $path.'/'.$filename;
        if( !$this->file->isDirectory(public_path().$path)){
            $this->file->makeDirectory(public_path().$path);
        }
        $path2 = $path.'/'.$filename.'.xlsx';

        $writer->save(public_path().$path2);

        $files = [
            [$filename.'.xlsx',public_path().$path2]
        ];

        foreach ($images as $image){

            if(!empty($image[0]) && file_exists(public_path()."/".$image[0])){

                $pathinfo = pathinfo(public_path()."/".$image[0]);

                if(empty($image[1])){
                    $file_image = $pathinfo['filename'].'.'.$pathinfo['extension'];
                }else{
                    $file_image = $image[1].'.'.$pathinfo['extension'];
                }
                $newName = $path .'/'. $file_image;
                $this->file->copy(public_path()."/".$image[0],public_path().'/'.$newName );
                $files[] = [
                    $file_image,public_path().'/'.$newName
                ];
            }
        }
        $zipFileName = $filename.'.zip';
        $zip = new \ZipArchive();
        if($this->file->exists(public_path().'/'.$path . '/' . $zipFileName)){
            $this->file->delete(public_path().'/'.$path . '/' . $zipFileName);
        }
        if ($zip->open(public_path().'/'.$path . '/' . $zipFileName, \ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file){
                $zip->addFile($file[1],$file[0]);
            }

            $zip->close();
        }
        return ['link'=>url($path . '/' . $zipFileName),'images'=>$images,'ids'=>$ids];

    }
}