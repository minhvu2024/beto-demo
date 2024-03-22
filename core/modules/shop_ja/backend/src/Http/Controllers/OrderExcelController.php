<?php
namespace ShopJa\Http\Controllers;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use \ShopJa\Http\Models\OrderExcelModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use \PhpOffice\PhpSpreadsheet;
use \PhpOffice\PhpSpreadsheet\Style\Border;
use \PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Zoe\Config;

class OrderExcelController extends \Zoe\Http\ControllerBackend
{
    private function IF_End($val,$conf){

        if( $conf->equal_end === "<=" && $val <= $conf->value_end){
            return true;
        }else if( $conf->equal_end === ">=" && $val >= $conf->value_end){
            return true;
        }else if($conf->equal_end === ">" && $val > $conf->value_end){
            return true;
        } else if($conf->equal_end === "<" && $val < $conf->value_end){
            return true;
        }else if($conf->equal_end === "=" && $val === $conf->value_end){
            return true;
        }
        return false;
    }
    private function IF_Start($val,$conf){
        try{
            if( $conf->equal_start === "<=" && $val <= $conf->value_start){
                return true;
            }else if( $conf->equal_start === ">=" && $val >= $conf->value_start){
                return true;
            }else if($conf->equal_start === ">" && $val > $conf->value_start){
                return true;
            } else if($conf->equal_start === "<" && $val < $conf->value_start){
                return true;
            }else if($conf->equal_start === "=" && $val == $conf->value_start){
                return true;
            }
        }catch (\Exception $ex){
            var_dump($conf);
            echo $ex->getMessage();
            die();
        }
        return false;
    }
    private function GetToken(){
        if(Cookie::has('token_order')){
            $token = Cookie::get('token_order');
        }else{
            $token = rand();
            Cookie::queue('token_order', $token, 60*1000);
        }
        return $token;
    }
    public function init()
    {
        $this->data['language'] = config('zoe.language');
        $this->data['nestables'] = config_get("category", "shop-ja:japan:category");
        $this->data['configs'] = config_get("config", "shopja");
        $this->data['current_language'] = isset($this->data['configs']['shopja']['language']['default']) ? $this->data['configs']['shopja']['language']['default'] : "en";
        $this->file = new \Illuminate\Filesystem\Filesystem();

        $this->data['version'] =10;
    }
    public function getCrumb()
    {
        $this->breadcrumb(z_language("Quản lý đơn hàng"), route('backend:shop_ja:order:excel:list'));
        return $this;
    }
    function is_base64($s)
    {
        return (bool) preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s);
    }
    function base64ToImage($imageData,$file){
        if(empty($imageData)){
            return "";
        }
        if (strpos($imageData, 'uploads') !== false) {
           return $imageData;
        }
        list($type, $imageData) = explode(';', $imageData);

        list(,$extension) = explode('/',$type);
        list(,$imageData)      = explode(',', $imageData);

        $path = '/uploads/images';
        if( !$this->file->isDirectory(public_path().$path)){
            $this->file->makeDirectory(public_path().$path);
        }
        $path = $path.'/'.$file;
        if( !$this->file->isDirectory(public_path().$path)){
            $this->file->makeDirectory(public_path().$path);
        }
        $fileName =$path.'/'.md5($imageData).'.'.$extension;
        if(!file_exists(public_path().$fileName)){
            $imageData = base64_decode($imageData);
            file_put_contents(public_path().$fileName, $imageData);
        }
        return $fileName;
    }

    public function store(Request $request){
        $data = $request->all();
        if(isset($data['act'])){
            if($data['act'] == "cache"){
                $k = Auth::user()->id.':'.$data['type'].':'.$data['name'].':'.$data['id'].date('d-m-Y H:i');
                $k_lists = Auth::user()->id.':'.$data['type'].':'.$data['name'].':'.$data['id'];
                $values_lists = Cache::get($k_lists,[]);
                $values_lists[$k] = 1;
                if(Cache::put($k,json_encode(['data'=>json_decode($data['data'],true)]) , 60*60*20)){
                    Cache::put($k_lists,$values_lists,60*60*20);
                    return response()->json(['values_lists'=>$values_lists,'key'=>$k,'data'=>json_decode(Cache::get($k),true)]);
                }
            }elseif($data['act'] == "saveShow"){
                $datas = json_decode($data['datas'],true);
                $usernames = DB::table('admin')->select('id','username')->get()->keyBy('username')->toArray();
                $this->InitOption();
                $date_time = date('Y-m-d H:i:s');
                foreach ($datas as $name=>$order){

                    $logs[$name] = [];

                    $check =  [
                        'fullname' => 'required',
                    ];
                    if($name == "FUKUI"){
                        $check =  [
                            'fullname' => 'required',
                        ];
                    }else if($name == "KOGYJA"){
                        $check =  [
                            'count' => 'required|numeric|gt:0',
                        ];
                    }
                    $columns = [];

                    foreach ($order['columns'] as $k=>$v){
                        $columns[$v] = $k;
                    }
                    $this->GetCache('create',0);

                    $_product = $this->data['products'][$name];

                    if($name== "KOGYJA"){
                        try{

//                            foreach ($order['data'] as $key=>$values){
//                                if($values[$columns["type"]] == "Info"){
//                                    $oke = false;
//                                    foreach ($order['data'] as $key1=>$values1){
//                                        if ($key1!=$key && $values1[$columns["token"]] == $values[$columns["token"]]) {
//                                            $order['data'][$key1][$columns["status"]] =  $values[$columns["status"]];
//                                            $order['data'][$key1][$columns["timeCreate"]] = $values[$columns["timeCreate"]];
//                                            $order['data'][$key1][$columns["session_id"]] = $values[$columns["session_id"]];
//                                            $order['data'][$key1][$columns["fullname"]] = $values[$columns["fullname"]];
//                                            $order['data'][$key1][$columns["address"]] = $values[$columns["address"]];
//                                            $order['data'][$key1][$columns["phone"]] = $values[$columns["phone"]];
//                                            $order['data'][$key1][$columns["province"]] = $values[$columns["province"]];
//                                            $order['data'][$key1][$columns["payMethod"]] = $values[$columns["payMethod"]];
//                                            $order['data'][$key1][$columns["zipcode"]] = $values[$columns["zipcode"]];
//                                            $order['data'][$key1][$columns["order_date"]] = $values[$columns["order_date"]];
//                                            $order['data'][$key1][$columns["admin"]] = $values[$columns["admin"]];
//                                        }
//                                    }
//                                }
//                            }
                            $dataNew = [];
                            foreach ($order['data'] as $key=>$values){
                                if($values[$columns["type"]] == "Info"){
                                    $oke = false;

                                    $dateNewRow = [];
                                    $dateNewRow[$key] = $values;
                                    $id = $values[$columns["id"]];
                                    $error = 0;

                                    $_count = (int)(isset($columns["count"]) ? $values[$columns["count"]] : null);

                                    if($_count <= 0 ){
                                        $error++;
                                    }
                                    if(is_numeric($id)){
                                        $idsOrder[$values[$columns["id"]]] = "id";
                                    }else{
                                        $idsOrder[$values[$columns["id"]]] = "key";
                                    }

                                    foreach ($order['data'] as $key1=>$values1){
                                        if ($key1!=$key && $values1[$columns["token"]] == $values[$columns["token"]]) {
                                            $order['data'][$key1][$columns["status"]] =  $values[$columns["status"]];
                                            $order['data'][$key1][$columns["timeCreate"]] = $values[$columns["timeCreate"]];
                                            $order['data'][$key1][$columns["session_id"]] = $values[$columns["session_id"]];
                                            $order['data'][$key1][$columns["fullname"]] = $values[$columns["fullname"]];
                                            $order['data'][$key1][$columns["address"]] = $values[$columns["address"]];
                                            $order['data'][$key1][$columns["phone"]] = $values[$columns["phone"]];
                                            $order['data'][$key1][$columns["province"]] = $values[$columns["province"]];
                                            $order['data'][$key1][$columns["payMethod"]] = $values[$columns["payMethod"]];
                                            $order['data'][$key1][$columns["zipcode"]] = $values[$columns["zipcode"]];
                                            $order['data'][$key1][$columns["order_date"]] = $values[$columns["order_date"]];

                                            if($values1[$columns["type"]] == "Footer"){
                                                $oke = true;
                                                $dateNewRow[$key1] =  $order['data'][$key1];
                                                $_count = (int)(isset($columns["count"]) ? $values1[$columns["count"]] : null);
                                                if($_count < 0 ){
                                                    $error++;
                                                }
                                            }else if(isset($columns["type"]) && isset($values1[$columns["type"]]) && !empty(trim($values1[$columns["type"]]))){
                                                $dateNewRow[$key1] = $order['data'][$key1];
                                                $_count = (int)(isset($columns["count"]) ? $values1[$columns["count"]] : null);
                                                if($_count < 0 ){
                                                    $error++;
                                                }
                                            }
                                            $id = $values1[$columns["id"]];
                                            if(is_numeric($id)){
                                                $idsOrder[$values1[$columns["id"]]] = "id";
                                            }else{
                                                $idsOrder[$values1[$columns["id"]]] = "key";
                                            }
                                        }
                                    }
                                    if($error == 0){
                                        $dataNew[$key] = $dateNewRow;
                                    }else{
                                        $logs[$name][] = "Có lỗi sai số lượng";
                                    }
                                }
                            }
                            foreach ($dataNew as $valuesNew) {
                                foreach ($valuesNew as $key => $values) {
                                $pay_method = 0;
                                if($values[$columns["payMethod"]] == "代金引換"){
                                    $pay_method = 1;
                                }else if($values[$columns["payMethod"]] == "銀行振込"){
                                    $pay_method = 2;
                                }else if($values[$columns["payMethod"]] == "決済不要"){
                                    $pay_method =3;
                                }

                                foreach ($values as $kkkkk=>$valllll){
                                    $values[$kkkkk] = rtrim(trim($valllll));
                                }

                                $product_title = "";$product_code = "";

                                $product_id = (isset($columns["product_id"])?(int)$values[$columns["product_id"]]:0);

                                if(isset( $_product[$product_id]['data']['price_buy'])){
                                    $product_code = $_product[$product_id]['data']['code'];
                                    $product_title = $_product[$product_id]['data']['title'];
                                }
                                $admin_id = isset($columns["admin"])?$values[$columns["admin"]]:"";
                                $_data = [
//                                    "order_create_date"=>isset($columns["timeCreate"])?$values[$columns["timeCreate"]]:"",
//                                    "company"=>$name,
//                                    "session_id"=> isset($columns["session_id"])?$values[$columns["session_id"]]:"",
//                                    "admin_id"=>isset($usernames[$admin_id])?$usernames[$admin_id]->id:"",
                                    "fullname"=>isset($columns["fullname"])?preg_replace('/\s+/', ' ', $values[$columns["fullname"]]):"",
                                    "address"=> isset($columns["address"])?preg_replace('/\s+/', '',$values[$columns["address"]] ):"",
                                    "phone"=>isset($columns["phone"])?$values[$columns["phone"]]:"",
                                    "zipcode"=>isset($columns["zipcode"])?$values[$columns["zipcode"]]:"",
                                    "province"=>isset($columns["province"])?preg_replace('/\s+/', '',$values[$columns["province"]] ):"",
                                    "pay_method"=>$pay_method,
                                    "product_id"=>$product_id,
                                    "product_code"=>$product_code,
                                    "product_title"=>$product_title,
                                    "price"=>(int)(isset($columns["price"])?$values[$columns["price"]]:""),
                                    "price_buy"=>(int)(isset($columns["price_buy"])?$values[$columns["price_buy"]]:""),
                                    "total_price"=>(int)(isset($columns["order_total_price"])?$values[$columns["order_total_price"]]:""),
                                    "price_buy_sale"=>(int)(isset($columns["price_buy_sale"])?$values[$columns["price_buy_sale"]]:""),
                                    "total_price_buy"=>(int)(isset($columns["order_total_price_buy"])?$values[$columns["order_total_price_buy"]]:""),
                                    "count"=>(int)(isset($columns["count"])?$values[$columns["count"]]:null),
                                    "total_count"=>(int)(isset($columns["total_count"])?$values[$columns["total_count"]]:""),
                                    "order_image"=>$this->base64ToImage(isset($columns["image"])?$values[$columns["image"]]:"",$name),
                                    "order_date"=>isset($columns["order_date"])?$values[$columns["order_date"]]:"",
                                    "order_hours"=>isset($columns["order_hours"])?$values[$columns["order_hours"]]:"",
                                    "order_ship"=>(int) (isset($columns["order_ship"])?$values[$columns["order_ship"]]:""),
                                    "order_price"=>(int) (isset($columns["order_price"])?$values[$columns["order_price"]]:""),
                                    "order_ship_cou"=>(int)(isset($columns["order_ship_cou"])?$values[$columns["order_ship_cou"]]:""),
                                    "order_tracking"=>isset($columns["order_tracking"])?$values[$columns["order_tracking"]]:"",
                                    "order_info"=>isset($columns["order_info"])?$values[$columns["order_info"]]:"",
                                    "order_link"=>isset($columns["order_link"])?$values[$columns["order_link"]]:"",
//                                    "updated_at"=>$date_time,
                                    "type"=>isset($columns["type"]) && !empty($values[$columns["type"]])?$values[$columns["type"]]:"Item",
                                    "one_address"=>isset($columns["one_address"])?(int)$values[$columns["one_address"]]:"0",
                                    "public"=> isset($columns["status"])? (int)$values[$columns["status"]]:"0",
                                    "token"=> isset($columns["token"])? $values[$columns["token"]]:"",
                                    "order_index"=> isset($columns["position"])? (int)$values[$columns["position"]]:0,
                                    "rate"=> isset($this->data['options'][$name]['rate'])? (int)$this->data['options'][$name]['rate']:"0",
                                    "group"=> isset($columns["group"])? $values[$columns["group"]]:"",
                                    "comment"=> isset($columns["comment"])? $values[$columns["comment"]]:"",
                                    "order_product_link"=> isset($columns["order_product_link"])? $values[$columns["order_product_link"]]:"",
                                ];
//                                $_data['order_create_date'] = date('Y-m-d',strtotime($_data['order_create_date']))." ".date(' H:i:s');
                                $_ = [$values,$_data,$columns];

                                if ($product_id > 0 && $_data['type'] == "Item" || $_data['type'] == "Footer" || $_data['type'] == "Info") {
                                    if(isset($columns["id"]) && !empty($values[$columns["id"]])){
                                        $where = ['id'=>$values[$columns["id"]]];

                                        $status = DB::table('shop_order_excel')->where($where)->update($_data);
                                        $logs[$name][] = [$status,$_data];
                                        $this->log('shop_js:excel:change',"edit",[
                                            'id'=>$values[$columns["id"]],
                                            'company'=>$name,
                                            'username'=>$admin_id,
                                            'create_date'=>isset($columns["timeCreate"])?$values[$columns["timeCreate"]]:"",
                                            'change'=>$order['oldData'][$key],
                                        ]);
                                    }
                                }
                            }
                          }
                        }catch (\Exception $ex){
                            $logs[$name][] = $ex->getMessage() .' '.$ex->getLine();
                        }
                    }else{
                        try{
                            foreach ($order['data'] as $key=>$values){
                                $pay_method = 0;
                                if($values[$columns["payMethod"]] == "代金引換"){
                                    $pay_method = 1;
                                }else  if($values[$columns["payMethod"]] == "銀行振込"){
                                    $pay_method = 2;
                                }else  if($values[$columns["payMethod"]] == "決済不要"){
                                    $pay_method = 3;
                                }
                                foreach ($values as $kkkkk=>$valllll){
                                    $values[$kkkkk] = rtrim(trim($valllll));
                                }
                                $product_title = "";$product_code = "";$count = 0;
                                if($name== "KURICHIKU" || $name== "BANH_CHUNG"){
                                    $product_id = (isset($columns["product_id"])?$values[$columns["product_id"]]:"");
                                    $count = (isset($columns["count"])?$values[$columns["count"]]:"");

                                    try{
                                        $array_count = json_decode($count,true);
                                    }catch (\Exception $ex) {
                                        $array_count = [];
                                    }


                                    try{
                                        $array_product = explode(";",$product_id);
                                    }catch (\Exception $ex) {
                                        $array_product = [];
                                    }

                                    $product_code = "";$product_title = "";
                                    foreach ($array_product as $pro_id){
                                        if(isset( $_product[$pro_id]['data']['price_buy'])){
                                            $_kg = 0;
                                            if(isset($array_count[$pro_id])){
                                                $_kg = $array_count[$pro_id];
                                            }else{
                                                $_kg = $array_count;
                                            }
                                            $product_code.= $_product[$pro_id]['data']['code'].",";
                                            $product_title.= $_product[$pro_id]['data']['title'].$_kg."kg,";
                                        }
                                    }
                                    $product_code = rtrim($product_code,',');
                                    $product_title = rtrim($product_title,',');
                                    $product_title = "";
                                    $total_count = isset($columns["total_count"])?(int)$values[$columns["total_count"]]:"1";

//                                    if(is_array($array_count)){
//                                        foreach ($array_count as $pro_id=>$_count){
//                                            if(isset( $_product[$pro_id]['data']['price_buy'])){
//                                                $total_count+=(int)$_count;
//                                            }
//                                        }
//                                    }else{
//                                        $total_count = $count;
//                                    }

                                }else{
                                    $product_id = (int)(isset($columns["product_id"])?$values[$columns["product_id"]]:null);
                                    $count = (int)(isset($columns["count"])?$values[$columns["count"]]:"");
                                    $total_count = (int)(isset($columns["total_count"])?$values[$columns["total_count"]]:"0");
                                    if(isset( $_product[$product_id]['data']['price_buy'])){
                                        $product_code = $_product[$product_id]['data']['code'];
                                        $product_title = $_product[$product_id]['data']['title'];
                                    }
                                }
                                $admin_id = isset($columns["admin"])?$values[$columns["admin"]]:"";
                                $_data = [
                                    "order_create_date"=>isset($columns["timeCreate"])?$values[$columns["timeCreate"]]:"",
                                    "company"=>$name,
                                    "session_id"=> isset($columns["session_id"])?$values[$columns["session_id"]]:"",
                                    "admin_id"=>isset($usernames[$admin_id])?$usernames[$admin_id]->id:"",
                                    "fullname"=>isset($columns["fullname"])?preg_replace('/\s+/', ' ', $values[$columns["fullname"]]):"",
                                    "address"=> isset($columns["address"])?preg_replace('/\s+/', '',$values[$columns["address"]] ):"",
                                    "phone"=>isset($columns["phone"])?$values[$columns["phone"]]:"",
                                    "zipcode"=>isset($columns["zipcode"])?$values[$columns["zipcode"]]:"",
                                    "province"=>isset($columns["province"])?preg_replace('/\s+/', '',$values[$columns["province"]] ):"",
                                    "pay_method"=>$pay_method,
                                    "product_id"=>$product_id,
                                    "product_code"=>$product_code,
                                    "product_title"=>$product_title,
                                    "price"=>(int)(isset($columns["price"])?$values[$columns["price"]]:""),
                                    "price_buy"=>(int)(isset($columns["price_buy"])?$values[$columns["price_buy"]]:""),
                                    "total_price"=>(int)(isset($columns["order_total_price"])?$values[$columns["order_total_price"]]:""),
                                    "price_buy_sale"=>(int)(isset($columns["price_buy_sale"])?$values[$columns["price_buy_sale"]]:""),
                                    "total_price_buy"=>(int)(isset($columns["order_total_price_buy"])?$values[$columns["order_total_price_buy"]]:""),
                                    "count"=>$count,
                                    "total_count"=>$total_count,
                                    "order_image"=>$this->base64ToImage(isset($columns["image"])?$values[$columns["image"]]:"",$name),
                                    "order_date"=>isset($columns["order_date"])?$values[$columns["order_date"]]:"",
                                    "order_hours"=>isset($columns["order_hours"])?$values[$columns["order_hours"]]:"",
                                    "order_ship"=>(int) (isset($columns["order_ship"])?$values[$columns["order_ship"]]:""),
                                    "order_price"=>(int) (isset($columns["order_price"])?$values[$columns["order_price"]]:""),
                                    "order_ship_cou"=>(int)(isset($columns["order_ship_cou"])?$values[$columns["order_ship_cou"]]:""),
                                    "order_tracking"=>isset($columns["order_tracking"])?$values[$columns["order_tracking"]]:"",
                                    "order_info"=>isset($columns["order_info"])?$values[$columns["order_info"]]:"",
                                    "order_link"=>isset($columns["order_link"])?$values[$columns["order_link"]]:"",
                                    "updated_at"=>$date_time,
                                    "one_address"=>isset($columns["one_address"])?(int)$values[$columns["one_address"]]:"0",
                                    "public"=> isset($columns["status"])? (int)$values[$columns["status"]]:"0",
                                    "token"=> isset($columns["token"])? $values[$columns["token"]]:"",
                                    "order_index"=> isset($columns["position"])? (int)$values[$columns["position"]]:"0",
                                    "group"=> isset($columns["group"])? $values[$columns["group"]]:"",
                                    "comment"=> isset($columns["comment"])? $values[$columns["comment"]]:"",
                                    "order_product_link"=> isset($columns["order_product_link"])? $values[$columns["order_product_link"]]:"",
                                ];
//                                $_data['order_create_date'] = date('Y-m-d',strtotime($_data['order_create_date']))." ".date('H:i:s');
                                //$_data["sort"] = $model->admin_id * 10000 + ($key+1) * $model->admin_id + $_data["order_index"] + strtotime($_data['order_create_date']);
                                $validator = Validator::make($_data,$check);
                                if (!$validator->fails()) {
                                    $_ = [$values,$_data,$columns];
                                    if(isset($columns["id"]) && !empty($values[$columns["id"]])){
                                        $where = ['id'=>$values[$columns["id"]]];

                                        DB::table('shop_order_excel')->where($where)->update($_data);

                                        $this->log('shop_js:excel:change',"edit",[
                                            'id'=>$values[$columns["id"]],
                                            'company'=>$name,
                                            'username'=>$admin_id,
                                            'create_date'=>$_data['order_create_date'],
                                            'change'=>$order['oldData'][$key],
                                        ]);
                                    }
                                    $_[] = $where;
                                    $logs[$name][] =$_;
                                }
                            }
                        }catch (\Exception $ex){
                            $logs[$name][] = $ex->getMessage() .' '.$ex->getLine();
                        }
                    }
                }
                if (isset($data['id']) && $data['id']!=0 && !empty($data['id'])) {
                    foreach ($datas as $name=>$order){
                        $k = $this->GetToken()."-".Auth::user()->id.':edit:'.$name.':'.$data['id'];
                        Cache::forget($k);
                    }
                }else{
                    foreach ($datas as $name=>$order){
                        $k = $this->GetToken()."-".Auth::user()->id.':create:'.$name.':0';
                        Cache::forget($k);
                    }
                }
                return response()->json([$logs,$datas]);

            }else if($data['act'] == "save"){
                $datas = json_decode($data['datas'],true);

                $lock =  DB::table('shop_order_excel_lock')
                    ->where('action',2)
                    ->where('date',$data['date'])
//                    ->where('date','>=',date('Y-m-d')." 00:00:00")
//                    ->where('date','<=',date('Y-m-d')." 23:59:59")
                    ->limit(1)
                    ->orderBy('updated_at','desc')->get()->all();
                if(isset($lock[0])){
                    if($data['auto'] == false)
                    request()->session()->flash('errors',z_language('Đã khóa nhập file ngày hôm này'));
                    return response()->json(['reload'=>true]);
                }
                $type = 'create';
                $user_admin_id = Auth::user()->id;
                if (isset($data['id']) && $data['id']!=0 && !empty($data['id'])) {
                    $model = OrderExcelModel::find($data['id']);
                    $type = 'edit';
                    $user_admin_id = $model->admin_id;
                } else {
                    $model = new OrderExcelModel();
                    $model->admin_id = Auth::user()->id;
                }

                $this->InitOption();

                $date_time = date('Y-m-d H:i:s');
                $model->date_time = $date_time;

                $model->name =\Illuminate\Support\Str::random(50);

                $model->status =  1;
                $model->token =  rand();

                $logs = [];
                $deletes = [];

                $oke = $model->save();
                $ids = [

                ];
                $ids_sort = [];



                foreach ($datas as $name=>$order){

                    $logs[$name] = [];
                    $deletes[$name] = [];
                    $ids_sort[$name] = [];

                    $check =  [
                        'fullname' => 'required',
                    ];
                    if($name == "FUKUI"){
                        $check =  [
                            'fullname' => 'required',
                        ];
                    }else if($name == "KOGYJA"){
                        $check =  [
                               'count' => 'required|numeric|gt:0',
                        ];
                    }
                    $columns = [];

                    foreach ($order['columns'] as $k=>$v){
                        $columns[$v] = $k;
                    }
                    $this->GetCache('create',0);

                    $_product = $this->data['products'][$name];

                    if($name== "KOGYJA"){
                        try{
                            $dataNew = [];

                            $countsOld = DB::table('shop_order_excel')
                                ->where('company',$name)
                                ->where('admin_id',$model->admin_id)
                                ->where('session_id',$model->id)->get()->keyBy('id')->all();
                            $idsOrder = [

                            ];

                            foreach ($order['data'] as $key=>$values){

                                if($values[$columns["type"]] == "Info"){
                                    $oke = false;
                                    $dateNewRow = [];
                                    $dateNewRow[$key] = $values;
                                    $id = $values[$columns["id"]];
                                    $error = 0;

                                    $_count = (int)(isset($columns["count"]) ? $values[$columns["count"]] : null);

                                    if($_count <= 0 ){
                                        $error++;
                                    }
                                    if(is_numeric($id)){
                                        $idsOrder[$values[$columns["id"]]] = "id";
                                    }else{
                                        $idsOrder[$values[$columns["id"]]] = "key";
                                    }
                                    $footer = false;
                                    foreach ($order['data'] as $key1=>$values1){
                                        if ($key1!=$key && $values1[$columns["token"]] == $values[$columns["token"]]) {
                                            $order['data'][$key1][$columns["status"]] =  $values[$columns["status"]];
                                            $order['data'][$key1][$columns["timeCreate"]] = $values[$columns["timeCreate"]];
                                            $order['data'][$key1][$columns["session_id"]] = $values[$columns["session_id"]];
                                            $order['data'][$key1][$columns["fullname"]] = $values[$columns["fullname"]];
                                            $order['data'][$key1][$columns["address"]] = $values[$columns["address"]];
                                            $order['data'][$key1][$columns["phone"]] = $values[$columns["phone"]];
                                            $order['data'][$key1][$columns["province"]] = $values[$columns["province"]];
                                            $order['data'][$key1][$columns["payMethod"]] = $values[$columns["payMethod"]];
                                            $order['data'][$key1][$columns["zipcode"]] = $values[$columns["zipcode"]];
                                            $order['data'][$key1][$columns["order_date"]] = $values[$columns["order_date"]];

                                            if($values1[$columns["type"]] == "Footer"){
                                                $oke = true;
                                                $order['data'][$key1][$columns["position"]] = (int) $order['data'][$key1][$columns["position"]] - 1;
                                                $dateNewRow[$key1] =  $order['data'][$key1];
                                                $_count = (int)(isset($columns["count"]) ? $values1[$columns["count"]] : null);
                                                if($_count < 0 ){
                                                    $error++;
                                                }
                                                $footer = true;
                                            }else if(isset($columns["type"]) && isset($values1[$columns["type"]]) && !empty(trim($values1[$columns["type"]]))){
                                                $dateNewRow[$key1] = $order['data'][$key1];
                                                $_count = (int)(isset($columns["count"]) ? $values1[$columns["count"]] : null);
                                                if($_count < 0 ){
                                                    $error++;
                                                }
                                            }
                                            $id = $values1[$columns["id"]];
                                            if(is_numeric($id)){
                                                $idsOrder[$values1[$columns["id"]]] = "id";
                                            }else{
                                                $idsOrder[$values1[$columns["id"]]] = "key";
                                            }
                                        }
                                    }
                                    if($footer == false){
                                        $error++;
                                    }
                                    if($error == 0){
                                        $dataNew[$key] = $dateNewRow;
                                    }else{
                                        $logs[$name][] = "Có lỗi sai số lượng";
                                    }
                                }
                            }

                            foreach ($dataNew as $keyNew=>$valuesNew) {
                                foreach ($valuesNew as $key => $values) {
                                    $pay_method = 0;

                                    if ($values[$columns["payMethod"]] == "代金引換") {
                                        $pay_method = 1;
                                    } else if ($values[$columns["payMethod"]] == "銀行振込") {
                                        $pay_method = 2;
                                    } else if ($values[$columns["payMethod"]] == "決済不要") {
                                        $pay_method = 3;
                                    }

                                    foreach ($values as $kkkkk => $valllll) {
                                        $values[$kkkkk] = rtrim(trim($valllll));
                                    }

                                    $product_title = "";
                                    $product_code = "";

                                    $product_id = (isset($columns["product_id"]) ? (int)$values[$columns["product_id"]] : 0);

                                    if (isset($_product[$product_id]['data']['price_buy'])) {
                                        $product_code = $_product[$product_id]['data']['code'];
                                        $product_title = $_product[$product_id]['data']['title'];
                                    }
                                    $_data = [
                                        "form_index"=>$key,
                                        "order_create_date" => $model->key_date,
                                        "company" => $name,
                                        "session_id" => $model->id,
                                        "admin_id" => $user_admin_id,
                                        "fullname" => isset($columns["fullname"]) ? preg_replace('/\s+/', ' ', $values[$columns["fullname"]]) : "",
                                        "address" => isset($columns["address"]) ? preg_replace('/\s+/', '', $values[$columns["address"]]) : "",
                                        "phone" => isset($columns["phone"]) ? $values[$columns["phone"]] : "",
                                        "zipcode" => isset($columns["zipcode"]) ? $values[$columns["zipcode"]] : "",
                                        "province" => isset($columns["province"]) ? preg_replace('/\s+/', '', $values[$columns["province"]]) : "",
                                        "pay_method" => $pay_method,
                                        "product_id" => $product_id,
                                        "product_code" => $product_code,
                                        "product_title" => $product_title,
                                        "price" => (int)(isset($columns["price"]) ? $values[$columns["price"]] : ""),
                                        "price_buy" => (int)(isset($columns["price_buy"]) ? $values[$columns["price_buy"]] : ""),
                                        "total_price" => (int)(isset($columns["order_total_price"]) ? $values[$columns["order_total_price"]] : ""),
                                        "price_buy_sale" => (int)(isset($columns["price_buy_sale"]) ? $values[$columns["price_buy_sale"]] : ""),
                                        "total_price_buy" => (int)(isset($columns["order_total_price_buy"]) ? $values[$columns["order_total_price_buy"]] : ""),
                                        "count" => (int)(isset($columns["count"]) ? $values[$columns["count"]] : null),
                                        "total_count" => (int)(isset($columns["total_count"]) ? $values[$columns["total_count"]] : ""),
                                        "order_image" => $this->base64ToImage(isset($columns["image"]) ? $values[$columns["image"]] : "", $name),
                                        "order_date" => isset($columns["order_date"]) ? $values[$columns["order_date"]] : "",
                                        "order_hours" => isset($columns["order_hours"]) ? $values[$columns["order_hours"]] : "",
                                        "order_ship" => (int)(isset($columns["order_ship"]) ? $values[$columns["order_ship"]] : ""),
                                        "order_price" => (int)(isset($columns["order_price"]) ? $values[$columns["order_price"]] : ""),
                                        "order_ship_cou" => (int)(isset($columns["order_ship_cou"]) ? $values[$columns["order_ship_cou"]] : ""),
                                        "order_tracking" => isset($columns["order_tracking"]) ? $values[$columns["order_tracking"]] : "",
                                        "order_info" => isset($columns["order_info"]) ? $values[$columns["order_info"]] : "",
                                        "order_link" => isset($columns["order_link"]) ? $values[$columns["order_link"]] : "",
                                        "updated_at" => $date_time,
                                        "type" => isset($columns["type"]) && !empty($values[$columns["type"]]) ? $values[$columns["type"]] : "Item",
                                        "one_address" => isset($columns["one_address"]) ? (int)$values[$columns["one_address"]] : "0",
                                        "public" => isset($columns["status"]) ? (int)$values[$columns["status"]] : "0",
                                        "token" => isset($columns["token"]) ? $values[$columns["token"]] : "",
                                        "order_index" => isset($columns["position"]) ? (int)$values[$columns["position"]] : 0,
                                        "rate" => isset($this->data['options'][$name]['rate']) ? (int)$this->data['options'][$name]['rate'] : "0",
                                        "group" => isset($columns["group"]) ? $values[$columns["group"]] : "",
                                        "comment" => isset($columns["comment"]) ? $values[$columns["comment"]] : "",
                                        "order_product_link"=> isset($columns["order_product_link"])? $values[$columns["order_product_link"]]:"",
                                    ];
                                    $_data['order_create_date'] = date('Y-m-d', strtotime($_data['order_create_date'])) . " " . date(' H:i:s');

                                    $_data["sort"] = $user_admin_id * 1000000 + ($key + 1 + $keyNew ) * $user_admin_id + $_data["order_index"] + strtotime($model->key_date);
                                    $ids_sort[$name][$keyNew][$key] = [
                                        $user_admin_id * 1000000 + ($key + 1 + $keyNew ) * $user_admin_id + $_data["order_index"],
                                        ($key + 1 + $keyNew ) * $model->admin_id,
                                        $_data["order_index"],
                                        $_data["sort"]
                                    ];

                                    $validator = Validator::make($_data, $check);

                                    $_ = [$values, $_data, $columns];
                                    if ($product_id > 0 && $_data['type'] == "Item" || $_data['type'] == "Footer" || $_data['type'] == "Info") {
//                                        $logs[$name][] = $_data;
//                                        DB::table('shop_order_excel')->insert($_data);
                                        $where = [];
                                        if (isset($columns["id"]) && !empty($values[$columns["id"]])) {
                                            $id = $values[$columns["id"]];
                                            if (is_numeric($id)) {
                                                $where = ['id' => $values[$columns["id"]]];
                                            } else {
                                                $where = ['key_id' => $values[$columns["id"]]];
                                                $_data['rate'] = isset($this->data['options'][$name]['rate']) ? (int)$this->data['options'][$name]['rate'] : "0";
                                            }
                                        }
//                                        else{
//                                            $where = [
//                                                'session_id' => $_data['session_id'],
//                                                'admin_id' => $_data['admin_id'],
//                                                'fullname'=>$_data['fullname'],
//                                                "company"=>$_data["company"],
//                                                "zipcode"=>$_data["zipcode"],
//                                                "phone"=>$_data["phone"],
//                                                "address"=>$_data["address"],
//                                                "province"=>$_data["province"],
//                                                "pay_method"=>$_data["pay_method"],
//                                                "order_hours"=>$_data["order_hours"],
//                                                "order_date"=>$_data["order_date"],
//                                                "order_index"=>$_data["order_index"],
//                                                "type"=> $_data["type"],
//                                                "sort"=> $_data["sort"],
//                                            ];
//                                            $_data['rate'] = isset($this->data['options'][$name]['rate'])? (int)$this->data['options'][$name]['rate']:"0";
//
//                                        }
                                        $ids[$name][] = [$where, $_data];
                                        $_[] = $where;
                                        if (count($where) > 0) {
                                            DB::table('shop_order_excel')->updateOrInsert($where, $_data);
                                        }
                                    }

                                }
                            }
                                $deletes[$name] = [$idsOrder,$dataNew,$countsOld];
//                                $deletes[$name] =  DB::table('shop_order_excel')
//                                ->where('company',$name)
//                                ->where('admin_id',$model->admin_id)
//                                ->where('session_id',$model->id)
//                                ->where('updated_at','!=',$date_time)->count();

                                if(count($countsOld) > 0){
                                     $id_deletes = [];
                                     foreach ($countsOld as $key=>$value){
                                        if(!isset($idsOrder[$value->id]) && !isset($idsOrder[$value->key_id])){
                                            $deletes[$name]['delete'] = $value;
                                            $id_deletes[] = $value->id;
                                        }
                                     }
                                     DB::table('shop_order_excel')
                                        ->where('company',$name)
                                        ->where('admin_id',$model->admin_id)
                                        ->where('session_id',$model->id)
                                        ->whereIn('id',$id_deletes)
                                        ->delete();
                                }
                        }catch (\Exception $ex){
                            $logs[$name][] = $ex->getMessage() .' '.$ex->getLine();
                        }

                    }else{
                        try{
                            $countsOld = DB::table('shop_order_excel')
                                ->where('company',$name)
                                ->where('admin_id',$model->admin_id)
                                ->where('session_id',$model->id)->get()->keyBy('id')->all();
                            $idsOrder = [

                            ];

                            foreach ($order['data'] as $key=>$values){
                                $pay_method = 0;
                                if($values[$columns["payMethod"]] == "代金引換"){
                                    $pay_method = 1;
                                }else  if($values[$columns["payMethod"]] == "銀行振込"){
                                    $pay_method = 2;
                                }else  if($values[$columns["payMethod"]] == "決済不要"){
                                    $pay_method = 3;
                                }
                                foreach ($values as $kkkkk=>$valllll){
                                    $values[$kkkkk] = rtrim(trim($valllll));
                                }
                                $product_title = "";$product_code = "";$count = 0;
                                if($name== "KURICHIKU" || $name == "BANH_CHUNG"){
                                    $product_id = (isset($columns["product_id"])?$values[$columns["product_id"]]:"");
                                    $count = (isset($columns["count"])?$values[$columns["count"]]:"");
                                    try{
                                        $array_count = json_decode($count,true);
                                    }catch (\Exception $ex) {
                                        $array_count = [];
                                    }
                                    try{
                                        $array_product = explode(";",$product_id);
                                    }catch (\Exception $ex) {
                                        $array_product = [];
                                    }

                                    $product_code = "";$product_title = "";
                                    foreach ($array_product as $pro_id){
                                        if(isset( $_product[$pro_id]['data']['price_buy'])){
                                            $_kg = 0;
                                            if(isset($array_count[$pro_id])){
                                                $_kg = $array_count[$pro_id];
                                            }else{
                                                $_kg = $array_count;
                                            }
                                            $product_code.= $_product[$pro_id]['data']['code'].",";
                                            $product_title.= $_product[$pro_id]['data']['title'].$_kg."kg,";
                                        }
                                    }
                                    $product_code = rtrim($product_code,',');
                                    $product_title = rtrim($product_title,',');

                                    $total_count = isset($columns["total_count"])?(int)$values[$columns["total_count"]]:"1";

//                                    if(is_array($array_count)){
//                                        foreach ($array_count as $pro_id=>$_count){
//                                            if(isset( $_product[$pro_id]['data']['price_buy'])){
//                                                $total_count+=(int)$_count;
//                                            }
//                                        }
//                                    }else{
//                                        $total_count = $count;
//                                    }

                                }else{
                                    $product_id = (int)(isset($columns["product_id"])?$values[$columns["product_id"]]:null);
                                    $count = (int)(isset($columns["count"])?$values[$columns["count"]]:"");
                                    $total_count = (int)(isset($columns["total_count"])?$values[$columns["total_count"]]:"0");
                                    if(isset( $_product[$product_id]['data']['price_buy'])){
                                        $product_code = $_product[$product_id]['data']['code'];
                                        $product_title = $_product[$product_id]['data']['title'];
                                    }
                                }
                                $_data = [
                                    "form_index"=>$key,
                                    "order_create_date"=>$model->key_date,
                                    "company"=>$name,
                                    "session_id"=>$model->id,
                                    "admin_id"=>$user_admin_id,
                                    "fullname"=>isset($columns["fullname"])?preg_replace('/\s+/', ' ', $values[$columns["fullname"]]):"",
                                    "address"=> isset($columns["address"])?preg_replace('/\s+/', '',$values[$columns["address"]] ):"",
                                    "phone"=>isset($columns["phone"])?$values[$columns["phone"]]:"",
                                    "zipcode"=>isset($columns["zipcode"])?$values[$columns["zipcode"]]:"",
                                    "province"=>isset($columns["province"])?preg_replace('/\s+/', '',$values[$columns["province"]] ):"",
                                    "pay_method"=>$pay_method,
                                    "product_id"=>$product_id,
                                    "product_code"=>$product_code,
                                    "product_title"=>$product_title,
                                    "price"=>(int)(isset($columns["price"])?$values[$columns["price"]]:""),
                                    "price_buy"=>(int)(isset($columns["price_buy"])?$values[$columns["price_buy"]]:""),
                                    "total_price"=>(int)(isset($columns["order_total_price"])?$values[$columns["order_total_price"]]:""),
                                    "price_buy_sale"=>(int)(isset($columns["price_buy_sale"])?$values[$columns["price_buy_sale"]]:""),
                                    "total_price_buy"=>(int)(isset($columns["order_total_price_buy"])?$values[$columns["order_total_price_buy"]]:""),
                                    "count"=>$count,
                                    "total_count"=>$total_count,
                                    "order_image"=>$this->base64ToImage(isset($columns["image"])?$values[$columns["image"]]:"",$name),
                                    "order_date"=>isset($columns["order_date"])?$values[$columns["order_date"]]:"",
                                    "order_hours"=>isset($columns["order_hours"])?$values[$columns["order_hours"]]:"",
                                    "order_ship"=>(int) (isset($columns["order_ship"])?$values[$columns["order_ship"]]:""),
                                    "order_price"=>(int) (isset($columns["order_price"])?$values[$columns["order_price"]]:""),
                                    "order_ship_cou"=>(int)(isset($columns["order_ship_cou"])?$values[$columns["order_ship_cou"]]:""),
                                    "order_tracking"=>isset($columns["order_tracking"])?$values[$columns["order_tracking"]]:"",
                                    "order_info"=>isset($columns["order_info"])?$values[$columns["order_info"]]:"",
                                    "order_link"=>isset($columns["order_link"])?$values[$columns["order_link"]]:"",
                                    "updated_at"=>$date_time,
                                    "one_address"=>isset($columns["one_address"])?(int)$values[$columns["one_address"]]:"0",
                                    "public"=> isset($columns["status"])? (int)$values[$columns["status"]]:"0",
                                    "token"=> isset($columns["token"])? $values[$columns["token"]]:"",
                                    "order_index"=> isset($columns["position"])? (int)$values[$columns["position"]]:"0",
                                    "group"=> isset($columns["group"])? $values[$columns["group"]]:"",
                                    "comment"=> isset($columns["comment"])? $values[$columns["comment"]]:"",
                                    "order_product_link"=> isset($columns["order_product_link"])? $values[$columns["order_product_link"]]:"",
                                ];
                                $_data['order_create_date'] = date('Y-m-d',strtotime($_data['order_create_date']))." ".date('H:i:s');
                                $_data["sort"] = $user_admin_id * 1000000 + ($key+1) * $user_admin_id + $_data["order_index"] + strtotime($model->key_date);
                                $validator = Validator::make($_data,$check);
                                if (!$validator->fails()) {

                                    if(isset($columns["id"]) && !empty($values[$columns["id"]])){
                                        $id = $values[$columns["id"]];
                                        if(is_numeric($id)){
                                            $where = ['id'=>$values[$columns["id"]]];
                                        }else{
                                            $where = ['key_id'=>$values[$columns["id"]]];
                                            $_data['rate'] = isset($this->data['options'][$name]['rate'])? (int)$this->data['options'][$name]['rate']:"0";
                                        }
                                        $ids_sort[$id] = $_data["sort"];

                                        if(is_numeric($id)){
                                            $idsOrder[$values[$columns["id"]]] = "id";
                                        }else{
                                            $idsOrder[$values[$columns["id"]]] = "key";
                                        }

                                    }else{
                                        $where = [
                                            'session_id' => $_data['session_id'],
                                            'admin_id' => $_data['admin_id'],
                                            'fullname'=>$_data['fullname'],
                                            "company"=>$_data["company"],
                                            "zipcode"=>$_data["zipcode"],
                                            "phone"=>$_data["phone"],
                                            "province"=>$_data["province"],
                                            "address"=>$_data["address"],
                                            "sort"=> $_data["sort"],
                                            "order_create_date"=> $_data["order_create_date"],
                                        ];
                                        $_data['rate'] = isset($this->data['options'][$name]['rate'])? (int)$this->data['options'][$name]['rate']:"0";
                                    }
                                    $ids[$name][] = [$where,$_data];
                                     DB::table('shop_order_excel')->updateOrInsert($where,$_data);
                                }
                            }
                            $id_deletes = [];
                            if(count($countsOld) > 0){

                                $deletes[$name] = [$idsOrder,$order['data'],$countsOld];
                                foreach ($countsOld as $key=>$value){
                                    if(!isset($idsOrder[$value->id]) && !isset($idsOrder[$value->key_id])){
                                        $deletes[$name]['delete'][] = $value;
                                        $id_deletes[] = $value->id;
                                    }
                                }
                                $deletes[$name]['delete']['id'] = $id_deletes;
                                if(count($id_deletes) > 0){
                                    DB::table('shop_order_excel')
                                        ->where('company',$name)
                                        ->where('admin_id',$model->admin_id)
                                        ->where('session_id',$model->id)
                                        ->whereIn('id',$id_deletes)
                                        ->delete();
                                }
                            }

//                            $deletes[$name] =  DB::table('shop_order_excel')
//                                ->where('company',$name)
//                                ->where('admin_id',$model->admin_id)
//                                ->where('session_id',$model->id)
//                                ->where('updated_at','!=',$date_time)->count();
//
//                             DB::table('shop_order_excel')
//                                ->where('company',$name)
//                                ->where('session_id',$model->id)
//                                ->where('admin_id',$model->admin_id)
//                                ->where('updated_at','!=',$date_time)->delete();
                        }catch (\Exception $ex){

                            $logs[$name][] = $ex->getMessage() .' '.$ex->getLine();
                        }
                    }

                }
                if($oke){
                    if (isset($data['id']) && $data['id']!=0 && !empty($data['id'])) {
                        foreach ($datas as $name=>$order){
                            $k = $this->GetToken()."-".Auth::user()->id.':edit:'.$name.':'.$data['id'];
                            Cache::forget($k);
                        }
                    }else{
                        foreach ($datas as $name=>$order){
                            $k = $this->GetToken()."-".Auth::user()->id.':create:'.$name.':0';
                            Cache::forget($k);
                        }
                    }
                    $this->log('shop_js:orderExcel',$type,['id' => $model->id]);
                    return response()->json(['id'=>$model->id,'url'=>route('backend:shop_ja:order:excel:edit',
                        ['id' => $model->id]),'logs'=>$logs,'deletes'=>$deletes,"ids"=>$ids,'ids_sort'=>$ids_sort]);
                }
                else
                    return response()->json($datas);
            }else if($data['act'] == 'ship'){
                $output = [];
                if(isset($data['term']) || isset($data['data']['id']) || isset($data['lists']) ){
                    if(isset($data['term'])){
                        $results =  DB::table('shop_product')->where('description', 'like', '%' . $data['term'] . '%')->get()->all();
                    }else if(isset($data['data']['id'])){
                        $results =  DB::table('shop_product')->where('id', $data['data']['id'])->get()->all();
                        $key_ids[$data['data']['id']] = $data['data'];
                    }else if(isset($data['lists'])){
                        $ids = [];
                        foreach ($data['lists'] as $k=>$row){
                          $ids[$k] = $row['id'];
                          $key_ids[$row['id']] = $row;
                        }
                        $results =  DB::table('shop_product')->whereIn('id',$ids )->get()->all();
                    }
                    $category = get_category_type('shop-ja:product:category');

                    $category_ship = get_category_type('shop-ja:japan:category:com-ship');
                    $total_price_buy = $data['data']['total_price_buy'];

                    foreach ($results as $key=>$result){
                        $temp_array = array();
                        $temp_array['value'] = $result->description;
                        $count = 1;
                        if(isset($key_ids[$result->id]) && isset($key_ids[$result->id]['count'])){
                            $count =(int) $key_ids[$result->id]['count'];
                        }
                        $ships_category = DB::table('shop_ship')->where('category_id', $result->category_id)->orderBy('value_end', 'ASC')->get()->all();

                        foreach ($ships_category as $k_ship_cate=>$_ship_category){
                            $_config = json_decode($_ship_category->config,true);
                            $ships_category[$k_ship_cate]->config = $_config;
                        }
                        $confShip = [];
                        $price_ship = -1;
                        $price_ship_default = -1;
                        $log = [];

                        foreach ($ships_category as $k_ship_cate=>$_ship_category){
                            $log[] = $_ship_category;
                            $is_IF_Start = $this->IF_Start($count,$_ship_category);
                            $is_IF_End = $this->IF_End($count,$_ship_category);
                            $log[] = $is_IF_Start;
                            $log[] = $is_IF_End;
                            if($is_IF_Start && $is_IF_End){
                                $conf  =  $_ship_category->config;
                                $log[] = $conf;
                                foreach ($conf as $val){
                                    $arr = explode('-',$val['text']);
                                    $log[] = $arr;
                                    $log[] = $data['data']['province'];
                                    foreach ($arr as $v){
                                        if($data['data']['province'] == $v){
                                            $confShip[] = [$_ship_category,$val];
                                            break;
                                        }
                                    }
                                }
                            }
                        }

                        foreach ($confShip as $val){
                          if($val[0]->unit == 0 && $price_ship_default==-1){
                              $price_ship_default =  $val[1]['value'];
                          }else if($val[0]->unit == $result->unit && $price_ship == -1){
                              $price_ship = $val[1]['value'];
                          }
                        }
                        $ship = isset($category[$result->category_id])?isset($category[$result->category_id]->data['ship'])?$category[$result->category_id]->data['ship']:"-1":"-1";
                        $_log_ship_cou = [];
                        $ship_cou = -1;

                        $price_ship =  $price_ship!=-1?$price_ship:$price_ship_default;
                        if($data['data']['sheetName'] == "YAMADA" || $data['data']['sheetName'] =='MISHIMA' || $data['data']['sheetName'] =='GAO_ST25'){
                            if($data['data']['payMethod'] == 1){
                                $total_price_buy =  $total_price_buy + 330;
                            }else{
                                $ship_cou = 0;
                            }
                        }else  if($data['data']['sheetName'] == "OHGA"){
                            if($data['data']['payMethod'] == 1){
                                $total_price_buy =  $total_price_buy + 330;
                                $ship_cou = 330;
                            }else{
                                $ship_cou = 0;
                            }
                        }else  if($data['data']['sheetName'] == "FUKUI"){
                            if($data['data']['payMethod'] == 1){
                                $total_price_buy =  $total_price_buy + 330;
                                $ship_cou = 330;
                            }else{
                                $ship_cou = 0;
                            }
                        }else  if($data['data']['sheetName'] == "KURICHIKU" || $data['data']['sheetName'] == "BANH_CHUNG"){
                            if($data['data']['payMethod'] == 1){
                                $total_price_buy =  $total_price_buy + 330;
                                $ship_cou = 330;
                            }else{
                                $ship_cou = 0;
                            }
                        }

                        if($ship_cou == -1){
                            foreach ($category_ship as $_val){
                                if($ship == $_val->id){
                                  //  $ship = $_val->name;
                                    foreach ($_val->data as $units){
                                        foreach ($units as $unit){
                                            $_log_ship_cou[] = $unit;
                                            $_unit = (object)$unit;
                                            if($_unit){
                                                $is_IF_Start = $this->IF_Start($total_price_buy,$_unit);
                                                $is_IF_End = $this->IF_End($total_price_buy,$_unit);
                                                if($is_IF_Start && $is_IF_End){
                                                    $ship_cou = $unit['value'];
                                                }
                                            }

                                        }
                                        if($ship_cou != -1){
                                            break;
                                        }
                                    }
                                }
                                if($ship_cou != -1){
                                    break;
                                }
                            }
                        }
                        if($data['data']['sheetName'] == "KOGYJA"){
                            $total_price_buy = $total_price_buy + $ship_cou;
                        }
                        $temp_array['data'] = [
                            'id'=>$result->id,
                            'code'=>$result->code,
                            'title'=>$result->title,
                            'description'=>$result->description,
                            'price'=>$result->price,
                            'price_buy'=>$result->price_buy,
                            'unit'=>$result->unit,
                            'company'=>isset($category[$result->category_id])?$category[$result->category_id]->name:"empty",
                            'ship'=>$ship,
                            'ship_cou'=>$ship_cou,
                            'log_ship_cou'=>$_log_ship_cou,
                            'count'=>$count,
                            'image'=>'image',
                            'price_ship'=> $price_ship,
                            'total_price'=>$result->price,
                            'total_price_buy'=>$total_price_buy,
                            'ship_category'=>$ships_category,
                            'confShip'=>$confShip,
                            'price_ship_default'=>$price_ship_default,
                            '_price_ship'=>$price_ship,
                            'log'=>$log,
                        ];
                        $temp_array['hidden'] = [
                            'company'=>isset($category[$result->category_id])?$category[$result->category_id]->id:0,
                            'ship'=>isset($category[$result->category_id])?isset($category[$result->category_id]->data['ship'])?$category[$result->category_id]->data['ship']:"-1":"-1",
                        ];

                        $output[] = $temp_array;
                    }
                  }
                  return response()->json($output);
            }
        }
    }
    public function search(Request $request){

        $fullname = $request->get('fullname','');
        $address = $request->get('address','');
        $zipcode = $request->get('zipcode','');
        $cate_id = $request->get('cate','');
        $model = null;
        $users = [];
        $this->data['category_com'] = config_get("category", "shop-ja:product:category");
        if(!empty($fullname) || !empty($address) || !empty($zipcode) || !empty($cate_id)){
            $company = "";

            if(!empty($cate_id)){
                foreach ($this->data['category_com'] as $cate){
                    if($cate['id'] == $cate_id){
                        $company = $cate['name'];
                        break;
                    }

                }

            }

            $this->GetCache('show',0,$company);

            $model = new OrderExcelModel();

            $datas = $model->searchAll(Auth::user()->id,[
                'fullname'=>$fullname,
                'address'=>$address,
                'company'=>$company,
                'zipcode'=>$zipcode,
            ]);

            $model->detail = $this->GetData($datas,true);
            $users = DB::table('admin')->select('id','name')->get()->keyBy('id')->toArray();

        }
        return $this->render('order-excel.search',[
            'fullname'=>$fullname,
            'address'=>$address,
            'cate'=>$cate_id,
            'zipcode'=>$zipcode,
            'model'=>$model,
            'admin'=>$users
        ]);
    }
    public function list(Request $request){
        $this->getcrumb();
        $first = (int)date("d", strtotime("first day of this month"));
        $last = (int) date("d", strtotime("last day of this month"));

        $next = date('Y-m');
        $admin_id = is_null($request->admin_id)? Auth::user()->id:$request->admin_id;
        $keyCache = 'cacheDate:'.$admin_id.'_'.md5($first.$last);
        $cacheDate = Cache::get($keyCache,[]);

        for($day = $first;$day<=$last;$day++){
            $key_date = $next.'-'.(($day<10)?"0".$day:$day);

            if(!isset($cacheDate[$key_date])){
                if(DB::table("shop_order_excel_session")->where([
                        'admin_id'=>$admin_id,
                        'key_date'=>$key_date
                    ])->count() == 0){
                    DB::table("shop_order_excel_session")->updateOrInsert([
                        'admin_id'=>$admin_id,
                        'key_date'=>$key_date
                    ],[
                        'admin_id'=>$admin_id,
                        'key_date'=>$key_date,
                        'token'=>rand(1000,9999),
                        'date_time'=>$key_date." 00:00:00",
                        'created_at'=>$key_date." 00:00:00",
                        'updated_at'=>$key_date." 00:00:00",
                        'status'=>0,
                        'name'=>\Illuminate\Support\Str::random(50)
                    ]);
                }
                $cacheDate[$key_date] = 1;
            }
        }
        if((int) date('d') >= 25 ){

            $timeNext = strtotime('+1 day',strtotime("last day of this month"));

            for($i = 1;$i<=7;$i++){
                $day = (int)date('d',$timeNext);
                $next =  date('Y-m',$timeNext);

                $key_date = $next.'-'.(($day<10)?"0".$day:$day);

                if(!isset($cacheDate[$key_date])){
                    if(DB::table("shop_order_excel_session")->where([
                        'admin_id'=>$admin_id,
                        'key_date'=>$key_date
                    ])->count() == 0){
                        DB::table("shop_order_excel_session")->updateOrInsert([
                            'admin_id'=>Auth::user()->id,
                            'key_date'=>$key_date
                        ],[
                            'admin_id'=>Auth::user()->id,
                            'key_date'=>$key_date,
                            'token'=>rand(1000,9999),
                            'date_time'=>$key_date." 00:00:00",
                            'created_at'=>$key_date." 00:00:00",
                            'updated_at'=>$key_date." 00:00:00",
                            'status'=>0,
                            'name'=>\Illuminate\Support\Str::random(50)
                        ]);

                    }
                    $cacheDate[$key_date] = 1;
                }
                $timeNext = strtotime('+1 day',strtotime($key_date));
            }
            $item = $last;
        }else{
            $item = $last;
        }

        Cache::put($keyCache,$cacheDate,60);

        $filter = $request->query('filter', []);
        $search = $request->query('search', "");
        $status = $request->query('status', "");
        $date = $request->query('date', "");

        $config = config_get('option', "module:shop_ja:order:excel");


        $models = DB::table('shop_order_excel_session');

        if(isset($filter['fullname'])){
            $models->where('fullname', 'like', '%' . $filter['code']);
        }

        if (!empty($status) || $status != "") {
            $models->where('status', $status);
        }
        $models->where('admin_id', $admin_id);
        $date_start = $request->get('date_start','');
        $date_end = $request->get('date_end','');

        $categorys = config_get("category", "shop-ja:product:category");

        $names  = [];

        $models->orderBy('key_date', 'desc');
        return $this->render('order-excel.list', [
            'models' => $models->paginate($item),
            'callback' => [
                "GetUserName" => function ($model){
                    $rs = DB::table('admin as t')->where('id', $model->admin_id)->get('username');
                    return $rs && count($rs) > 0 ? $rs[0]->username : "Empty";
                },
                "GetName" => function ($model){
                    return date('d-m-Y',strtotime($model->key_date));
                },
                "CountCompany"=> function($model) use($categorys,$admin_id){

                    $table = "<table class='table table-bordered company' style='padding: 0;margin: 0;'><tr>";
                    foreach ($categorys as $k=>$value){
                       if( in_array($value['name'],['SOFTBANK','GTN'])) continue;
                       if($value['name'] == "KOGYJA"){
                           $count = DB::table('shop_order_excel')
                               ->where('company',$value['name'])
                               ->where('admin_id',$admin_id)
                               ->where('type','Info')
                               ->where('fullname','!=','')
                               ->where('order_create_date','>=',$model->key_date.' 00:00:00')
                               ->where('order_create_date','<=',$model->key_date.' 23:59:59')->count();
                       }else{
                           $count = DB::table('shop_order_excel')
                               ->where('company',$value['name'])
                               ->where('admin_id',$admin_id)
                               ->where('fullname','!=','')
                               ->where('order_create_date','>=',$model->key_date.' 00:00:00')
                               ->where('order_create_date','<=',$model->key_date.' 23:59:59')->count();
                       }


                        $table.= "<td style='width: ".(100/count($categorys))."%'><span style='font-size: 15px;padding: 5px;display: inline-block'>".$value['name']."</span><span class=\"badge bg-light-blue\">".$count."</span></td>";
                    }
                    $table.= "</tr></table>";
                    return $table;
                },
                "GetStatus"=>function($model){
                    if($model->status == 0){
                        return z_language('Bản nháp');
                    }else if($model->status == 1){
                        return z_language('Lập đơn');
                    }else if($model->status == 2){
                        return z_language('Đã hoàn thành');
                    }
                },
                "GetButtonEdit"=>function($model){
                    return  $html = "<a href='".route('backend:shop_ja:order:excel:edit',['id' => $model->id])."'><button type=\"button\" class=\"btn btn-primary btn-xs\">".z_language('Sửa')."</button></a>";
                }
            ],
        ]);
    }
    public function export(Request $request){
        $data = $request->all();
        if(isset( $data['date_export'])) {
            $data['date_export'] = explode("/", $data['date_export']);

            $data['date_export'] = $data['date_export'][2] . '-' . $data['date_export'][1] . '-' . $data['date_export'][0];
        }
        if(isset( $data['date'])) {
            $data['date'] = explode("/", $data['date']);

            $data['date'] = $data['date'][2] . '-' . $data['date'][1] . '-' . $data['date'][0];
        }
        $excel = new \ShopJa\Libs\Excel(
            isset($data['date'])?$data['date']:date('Y-m-d'),isset($data['date_export'])?$data['date_export']:0);

        $output = [];
        if(isset($data['name'])){
            if($data['name'] == "KOGYJA"){
                $data['datas'] = json_decode($data['datas'],true);
                $output = $excel->KOGYJA($data);
            }else  if($data['name'] == "YAMADA" || $data['name'] == 'AMAZON'){
                $data['datas'] = json_decode($data['datas'],true);
                $output =$excel->YAMADA($data,$data['name'],'AMAZONの注文分[MONTH]月[DAY]日');

            }else  if($data['name'] == "Cocolala"){
                $data['datas'] = json_decode($data['datas'],true);
                $output =$excel->Cocolala($data,$data['name'],'AMAZONの注文分[MONTH]月[DAY]日');
            }else  if($data['name'] == "OHGA"){
                $data['datas'] = json_decode($data['datas'],true);
                $output =$excel->OHGA($data);

            }else  if($data['name'] == "FUKUI"){
                $data['datas'] = json_decode($data['datas'],true);
                $output =$excel->FUKUI($data);
            }else  if($data['name'] == "KURICHIKU"){
                $data['datas'] = json_decode($data['datas'],true);
                $output =$excel->KURICHIKU($data,$data['name'],'AMAZONの注文分[MONTH]月[DAY]日');
            }else  if($data['name'] == "MISHIMA"){
                $data['datas'] = json_decode($data['datas'],true);
                $output =$excel->MISHIMA($data,$data['name'],'AMAZONの注文分[MONTH]月[DAY]日');
            } else if($data['name'] == "BANH_CHUNG"){
                $data['datas'] = json_decode($data['datas'],true);
                $output =$excel->BANH_CHUNG($data,$data['name'],'AMAZONの注文分[MONTH]月[DAY]日');
            }else  if($data['name'] == "DO_KHO"){
                $data['datas'] = json_decode($data['datas'],true);
                $output =$excel->DO_KHO($data,$data['name'],'AMAZONの注文分[MONTH]月[DAY]日');
            }else  if($data['name'] == "LICH_2022"){
                $data['datas'] = json_decode($data['datas'],true);
                $output =$excel->LICH_2022($data,$data['name'],'AMAZONの注文分[MONTH]月[DAY]日');
            }
            else  if($data['name'] == "HOA_QUA"){
                $data['datas'] = json_decode($data['datas'],true);
                $output =$excel->HOA_QUA($data,$data['name'],'AMAZONの注文分[MONTH]月[DAY]日');
            }
            else  if($data['name'] == "QUAT"){
                $data['datas'] = json_decode($data['datas'],true);
                $output =$excel->QUAT($data,$data['name'],'AMAZONの注文分[MONTH]月[DAY]日');
            }
            else  if($data['name'] == "GAO_ST25"){
                $data['datas'] = json_decode($data['datas'],true);
                $output =$excel->GAO_ST25($data,$data['name'],'株式会社スパイスハウス-様-[MONTH]月[DAY]日注文分');
            }
            if(isset($output['ids']) && isset($data['type']) && $data['type'] != "demo"){
                DB::beginTransaction();
                try{
                    foreach ($output['ids'] as $id=>$val){
                        DB::table("shop_order_excel")->where('id',$id)->update(['export'=>1]);
                    }
                    DB::table("shop_order_excel_exports")->updateOrInsert([
                        'date'=>date('Y-m-d',$excel->date),
                        'company'=>$data['name']
                    ],[
                        'date'=>date('Y-m-d',$excel->date),
                        'company'=>$data['name'],
                        'data'=>json_encode($output['ids']),
                        'create_time'=>date('Y-m-d H:i:s')
                    ]);
                    DB::commit();
                    $output['error'] = false;
                }catch (\Exception $ex){
                    DB::rollBack();
                    $output['error'] = $ex->getMessage();
                }
            }

        }
        return response()->json($output);
    }
    public function tracking_list(Request $request){
        $this->getcrumb();
        $data = $request->all();
        if(isset($data['pk']) && isset($data['name']) && $data['name'] == "save:note"){
            DB::table('shop_order_excel_tracking')->where('id',$data['pk'])->update(['note'=>$data['value']]);
            return '[]';
        }else if(isset($data['act']) && $data['act'] == "updateStatus" && isset($data['id']) && !empty($data['id'])){
            DB::table('shop_order_excel_tracking')->where('id',$data['id'])->update(['status'=>0]);
            return '[]';
        }else if(isset($data['act']) && $data['act'] == "updateStatusCancel" && isset($data['id']) && !empty($data['id'])){
            DB::table('shop_order_excel_tracking')->where('id',$data['id'])->update(['status'=>10]);
            return '[]';
        }else if(isset($data['act']) && $data['act'] == "updateStatusOke" && isset($data['id']) && !empty($data['id'])){
            DB::table('shop_order_excel_tracking')->where('id',$data['id'])->update(['status'=>1]);
            return '[]';
        }
        $filter = $request->query('filter', []);
        $search = $request->query('search', "");
        $status = $request->query('status', "");
        $date = $request->query('date', "");

        $config = config_get('option', "module:shop_ja:tracking");
        $item = isset($config['pagination']['item']) ? $config['pagination']['item'] : 20;

        $models = DB::table('shop_order_excel_tracking');
        if(isset($filter['search'])){
            $search = $filter['search'];
        }else {
            $filter_search = $request->query('filter_search', "");
            if(!empty($filter_search)){
                $search = $filter_search;
            }
        }
//        if(isset($filter['code'])){
//            $models->where('code', 'like', '%' . $filter['code'].'%');
//        }
//        if(isset($filter['cate'])){
//            $models->where('category_id', '=',$filter['cate'] );
//        }
//        if (!empty($search)) {
//            $models->where('title', 'like', '%' . $search.'%');
//        }
//        if(isset($filter['des'])){
//            $models->where('description', 'like', '%' . $filter['des'].'%');
//        }
        $value_status = 1;
        if (!empty($status) || $status != "") {
            $value_status = $status == -1?0:$status;
            $models->where('status', $status == -1?0:$status);
        }else{
            $value_status = 3;
            $models->where('status', 3);
        }
        if($value_status == 3){
            $models->orderBy('created_at', 'asc');
        }else{
            $models->orderBy('updated_at', 'desc');
        }

        $configs = [
            "YAMATO"=>"http://track.kuronekoyamato.co.jp/english/tracking",
            "SAGAWA"=>"http://k2k.sagawa-exp.co.jp/p/sagawa/web/okurijosearcheng.jsp",
            "JAPAN_POST"=>"https://trackings.post.japanpost.jp/services/srv/search/input"
        ];
        $configsredeliver = [
            "YAMATO"=>"https://syuhai.kuronekoyamato.co.jp/simple_saihai/SERVICE_MENU",
            "SAGAWA"=>"https://www.sagawa-exp.co.jp/send/redeliver.html",
            "JAPAN_POST"=>"https://trackings.post.japanpost.jp/delivery/deli/"
        ];
        $caches = new \stdClass();
        $caches->data = [];
        return $this->render('order-excel.tracking_list', [
            'models' => $models->paginate($item),
            'callback' => [
                "get_results" => function ($model){
                    $html = "";
                    $data = json_decode($model->data,true);
                    if($model->status > 0){
                       $html = '<label class="label label-default">'.(isset($data['Date'])?$data['Date']:z_language('Không xác định')).'</label><BR>'.'<label class="label label-default">'.(isset($data['Text'])?$data['Text']:z_language('Không xác định')).'</label>';
                    }

                    return $html;
                },
                "cancelOrder"=>function($model){
                    if($model->status == 3){
                        return '<div class="label-text"><div class="text-center"><a data-tracking="'.$model->tracking_id.'" data-status="'.$model->status.'" data-id="'.$model->id.'" href="javascript:void(0);" onclick="updateStatusCancel(this)"><span class="label label-danger">Hủy</span></a></div></div>'.
                         '<BR><div class="label-text"><div class="text-center"><a data-tracking="'.$model->tracking_id.'" data-status="'.$model->status.'" data-id="'.$model->id.'" href="javascript:void(0);" onclick="updateStatusOke(this)"><span class="label label-primary">Thành công</span></a></div></div><BR>';
                    }else if($model->status == 10){
                        return '<div class="label-text"><div class="text-center"><a data-tracking="'.$model->tracking_id.'" data-status="'.$model->status.'" data-id="'.$model->id.'" href="javascript:void(0);" onclick="updateStatus(this)"><span class="label label-info">Check</span></a></div></div>'.
                            '<BR><div class="label-text"><div class="text-center"><a data-tracking="'.$model->tracking_id.'" data-status="'.$model->status.'" data-id="'.$model->id.'" href="javascript:void(0);" onclick="updateStatusOke(this)"><span class="label label-primary">Thành công</span></a></div></div><BR>';
                    }else if($model->status == 0){
                        return '<div class="label-text"><div class="text-center"><a data-tracking="'.$model->tracking_id.'" data-status="'.$model->status.'" data-id="'.$model->id.'" href="javascript:void(0);" onclick="updateStatusCancel(this)"><span class="label label-danger">Hủy</span></a></div></div>';
                    }
                },
                "GetTimeCheck" => function ($model){
                    if($model->status > 1) {
                        $diff = strtotime("+".(60*24*0.5)." minutes", strtotime($model->updated_at))  - strtotime(date('Y-m-d H:i:s')) ;
                        $years = floor($diff / (365 * 60 * 60 * 24));
                        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
                        $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
                        $minuts = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
                        $seconds = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minuts * 60));
                        return $diff<=0?($model->status == 2?z_language('Đang kiểm tra'):z_language("00:00:00")):($days>0?'['.$days.z_language('ngày').']':"").$hours." : $minuts"." : $seconds";
                    }
                    return z_language("Đợi đến lượt");
                },
                "get_info_create_order"=>function($model) use($configs,$caches){
                    $html = "";
                    if(true) {
                        if(!isset($caches->data[$model->order_id])){
                            $caches->data[$model->order_id] = DB::table('shop_order_excel')->where('id',$model->order_id)->get()->all();
                        }
                        if(isset($caches->data[$model->order_id][0])){
                            return "<label class=\"label label-default\">".date('d-m-Y',strtotime($caches->data[$model->order_id][0]->order_create_date))."</label>".'<BR><label class="label label-default">'.$model->updated_at.'</label>';
                        }
                    }
                    return $html;
                },
                "get_info_fullname"=>function($model) use($configs,$caches){
                    $html = "";
                    if(true) {
                       if(!isset($caches->data[$model->order_id])){
                           $caches->data[$model->order_id] = DB::table('shop_order_excel')->where('id',$model->order_id)->get()->all();
                       }
                       if(isset($caches->data[$model->order_id][0])){
                           return $caches->data[$model->order_id][0]->fullname;
                       }
                    }
                    return $html;
                },
                "get_info_link"=>function($model) use($configs,$caches){
                    $html = "";
                    if(true) {
                        if(!isset($caches->data[$model->order_id])){
                            $caches->data[$model->order_id] = DB::table('shop_order_excel')->where('id',$model->order_id)->get()->all();
                        }
                        if(isset($caches->data[$model->order_id][0])){
                            return '<label class="label label-default"><a target="_blank" href="'.$caches->data[$model->order_id][0]->order_link.'">Fb</a></label>';
                        }
                    }
                    return $html;
                },
                "get_info_check"=>function($model) use($configs,$caches){
                    $html = "";
                    if(true) {
                        if(!isset($caches->data[$model->order_id])){
                            $caches->data[$model->order_id] = DB::table('shop_order_excel')->where('id',$model->order_id)->get()->all();
                        }
                        if(isset($caches->data[$model->order_id][0])){
                            return '<label class="label label-default"><a target="_blank" href="'.(isset($configs[$model->type])?$configs[$model->type]:"#").'">'.$model->company.'</a></label>';
                        }
                    }
                    return $html;
                },
                "get_info_redeliver"=>function($model) use($configsredeliver,$caches){
                    $html = "";
                    if(true) {
                        if(!isset($caches->data[$model->order_id])){
                            $caches->data[$model->order_id] = DB::table('shop_order_excel')->where('id',$model->order_id)->get()->all();
                        }
                        if(isset($caches->data[$model->order_id][0])){
                            return '<label class="label label-default"><a target="_blank" href="'.(isset($configsredeliver[$model->type])?$configsredeliver[$model->type]:"#").'">'.$model->type.'</a></label>';
                        }
                    }
                    return $html;
                },
                "get_note"=>function($model) use($configsredeliver,$caches){
                    return "<a href=\"#\" class='editable' id='editable_".$model->id."'>".$model->note."</a>";
                },
                "get_info_1"=>function($model) use($configs,$configsredeliver){
                    $html = "";

                        $resutls = DB::table('shop_order_excel')->where('id',$model->order_id)->get()->all();
                        if(isset($resutls[0])){
                            $html.='<table class="table table-bordered" style="background: #dedede">';
//                            $html.='<tr>';
//                            $html.='<td>Fb</td>';
//                            $html.='<td>Kiểm tra</td>';
//                            $html.='<td>Gửi lại</td>';
//                            $html.='</tr>';
                            $html.='<tr>';
                            $html.='<td><label class="label label-default"><a target="_blank" href="'.$resutls[0]->order_link.'">Fb</a></label></td>';
                            $html.='<td><label class="label label-default"><a target="_blank" href="'. (isset($configs[$model->type])?$configs[$model->type]:"#").'">'.$model->type.'</a></label></td>';
                            $html.='<td><label class="label label-default"><a target="_blank" href="'. (isset($configsredeliver[$model->type])?$configsredeliver[$model->type]:"#").'">'.$model->company.'</a></label></td>';
                            $html.='</tr>';
                            $html.='</table>';
                        }

                    return $html;
                }
            ]
        ]);
    }
    public function tracking(Request $request){

        $category =  get_category_type("shop-ja:product:category");
        $datas = [];

        foreach ($category as $key=>$value){
            $datas[$value->name][] =
                DB::table('shop_order_excel_tracking')
                    ->where('company',$value->name)
                    ->where('status',2)
                    ->orderBy('updated_at')
                    ->limit(30)
                    ->get()->all();
        }
        foreach ($category as $key=>$value){
            $datas[$value->name][] =
                DB::table('shop_order_excel_tracking')
                    ->where('company',$value->name)
                    ->where('status',3)
                    ->where('updated_at',"<=",date('Y-m-d H:i:s',strtotime('-'.(60*24*0.5).' minutes')))
                    ->orderBy('updated_at')
                    ->limit(30)
                    ->get()->all();
        }
        return $this->render('order-excel.tracking',[
            'datas'=>$datas,
            'callback' => [

            ]
        ]);
    }
    public function imports(Request $request){
        if($request->ajax()){
            $input = $request->all();
            if(isset($input['type'])){
                if($input['type'] == "import"){
                    $lists = isset( $input['lists'])? $input['lists']:[];
                    DB::beginTransaction();
                    $stringDate = explode('/',$input['date']);
                    $date =isset($stringDate[0]) && count($stringDate) > 2? $stringDate[2].'-'.$stringDate[1].'-'.$stringDate[0]:date('Y-m-d');

                    try{
                        foreach ($lists as $list){
                            foreach ($list['checking'] as $key=>$checking){

                                $count = DB::table('shop_order_excel_tracking')->where([
                                    'order_id'=>$list['ids'][$key],
                                    'type'=>$input['ship'],
                                    'company'=>$input['com'],
                                    'status'=> 1
                                ])->count();

                                if($count == 0){
                                    DB::table('shop_order_excel')
                                        ->where("id",$list['ids'][$key])
                                        ->update(['order_tracking'=>preg_replace('/[^0-9]+/', '', $checking)]);
                                    DB::table('shop_order_excel_tracking')
                                        ->updateOrInsert(
                                            [
                                                'order_id'=>$list['ids'][$key],
                                                'type'=>$input['ship'],
                                                'company'=>$input['com'],
                                            ],
                                            [
                                                'data'=>'[]',
                                                'created_at'=>$date.' '.date('H:i:s'),
                                                'tracking_id'=>preg_replace('/[^0-9]+/', '', $checking) ,
                                                'status'=>0,
                                                'updated_at'=>date('Y-m-d H:i:s',strtotime('-1 day',time()))
                                            ]
                                    );
                                }
                            }
                        }
                        DB::commit();
                    }catch (\Exception $ex){
                        DB::rollBack();
                        $input['error'] = $ex->getMessage();
                    }
                }
                return response()->json($input);
            }else{
                $validator = Validator::make($request->all(), [
                    'image' => 'required',
                ]);
                if($validator->passes()){
                    $imageName = date('y-m-d').'.'.request()->image->getClientOriginalExtension();
                    $input['image'] = $imageName;
                    $OriginalName = request()->image->getClientOriginalName();
                    request()->image->move(public_path('uploads/tracking'), $imageName);
                    $date = explode('/',$input['date']);
                    $date = $date[2].'-'.$date[1].'-'.$date[0];
                    $Excel = new \ShopJa\Libs\Excel($date,0);
                    $results = $Excel->Read($OriginalName,public_path('uploads/tracking')."/".$imageName,"Xlsx");
                    return Response()->json(["success"=>"Image Upload Successfully",'html'=>$results]);
                }

                return response()->json(['error'=>$validator->errors()->all()]);
            }
        }
        return $this->render('order-excel.imports');
    }
    public function InitOption(){
        $options = DB::table('shop_admin')->where('admin_id',Auth::id())->get()->all();
        $this->data['options'] = [

        ];
        if(isset($options[0])){
            $this->data['options'] = unserialize($options[0]->data);
        }
    }
    private function GetCache($type,$id,$company = "",$date = ""){
        $this->data['excels_data'] = [

        ];
        $this->data['products'] = [

        ];

        $this->InitOption();

        $categorys = config_get("category", "shop-ja:product:category");
        $names  = [];
        foreach($categorys as $category){
            if(!empty($company) && $company !=$category['name']){
                continue;
            }
            $names[] = $category['name'];

            $shop_products = DB::table('shop_product')->where('category_id',$category['id'])->orderBy('order_index',"desc")->get()->all();

            $this->data['products'][$category['name']] = [];
            $lock =  DB::table('shop_order_excel_lock')->limit(1)->orderBy('updated_at','desc')->get()->all();
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

        foreach ($names as $name){
            $key = $this->GetToken()."-".Auth::guard()->user()->id.':'.$type.':'.$name;
            $k = $key.':'.$id;

            if(Cache::has($k)){
                $_val = json_decode(Cache::get($k),true);
                $val = [];
                if(isset($_val['data'])){
                    $val['data'] =$_val['data'];
                }else{
                    $val['data']  = [];
                }
                if(!isset($_val['token']) || !$_val['token']){
                    $val['token'] = rand();
                }else{
                    $val['token'] = $_val['token'];
                }
            }else{
                $val = ['token'=>rand(),'data'=>[]];
            }

            if(Cache::has($key)){
               $dataKey =  Cache::get($key);
            }else{
               $dataKey = rand();
               Cache::put($key,$dataKey , 60*60*24);
            }
            $this->data['excels_data'][$name] = [
                'data'=>$val,
                'key'=> $dataKey
            ];
        }
        $shop_ship = DB::table('shop_ship')->orderBy('value_end', 'ASC')->get()->all();
        $arr_ship = [];
        foreach ($shop_ship as $k=>$v){
            if(!isset($arr_ship["cate_".$v->category_id])){
                $arr_ship["cate_".$v->category_id] = [];
            }
            $v->config = json_decode($v->config,true);
            $arr_ship["cate_".$v->category_id][] =$v;
        }
        $this->data['ships'] = $arr_ship;
        $this->data['categorys'] = get_category_type('shop-ja:product:category');
        $this->data['daibiki'] = get_category_type('shop-ja:japan:category:com-ship');

        $date_start = date('Y-m-d',strtotime('-5 day',strtotime($date)));
        $date_end = date('Y-m-d',strtotime('+2 day',strtotime($date)));

        $rs  = DB::table('shop_order_excel_exports')->where('date','>=',$date_start)->where('date',"<=",$date_end)->get()->all();
        $this->data['exports'] = new Config();
        foreach ($rs as $value){
            $this->data['exports'] = $this->data['exports']->add(json_decode($value->data,true)) ;
        }
        $config = config_get('config','shop_ja');

        $this->data['status'] = [];
        $number = empty($date)?date('N'):date('N',strtotime($date));
        if(isset($config['company'])){
            foreach ($config['company'] as $key=>$value){

                if(!isset($value['status'])) continue;

                if(isset($value['status']) && $value['status'] == 1){
                    $oke = false;
                    if(isset($value['type']) && $value['type'] == 2){

                        $weeks = isset($value['week'])?$value['week']:-1;
                        if(!is_array($weeks)){
                            $weeks = [$weeks];
                        }
                        foreach ($weeks as $_val){
                            if($number == $_val){
                                $oke = true;
                                break;
                            }
                        }
                    }else if(isset($value['date'])){

                       $date = explode('-',$value['date']);
                       $start = date('Y-m-d',strtotime($date[0]))." 00:00:00";
                       $end = date('Y-m-d',strtotime($date[1]))." 00:00:00";

                       if($start <= date('Y-m-d H:i:s') && $end >= date('Y-m-d H:i:s')){
                           $oke = true;
                       }
                    }
                    if($oke == true){
                        $this->data['status'][$key] = 1;
                    }
                }
            }
        }
        $this->data['hide'] = [];
        if(isset($config['excel'])){
            $this->data['hide'] =$config['excel'];
        }
    }
    public function create(Request $request){
        $this->getCrumb()->breadcrumb(z_language("Tạo mới"), route('backend:shop_ja:order:excel:create'));
        $this->GetCache('create',0,"",date('Y-m-d'));
        $users = DB::table('admin')->select('id','name')->get()->keyBy('id')->toArray();

        return $this->render('order-excel.create',['admin'=>$users]);
    }
    public  function GetData($results,$exportAll){
        $datas = [];

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
                        if($result->pay_method != 3) {
                            if ($total_price_buy == 0)
                                $total_price_buy = $price_buy * $result->count + $result->order_ship + $result->order_ship_cou + $result->price_buy_sale;
                        }
                        if($order_profit == 0){
                            $order_profit = $total_price_buy - $total_price - $result->order_ship - $result->order_ship_cou;
                        }

                        $datas[$result->company][] = [
                            $result->public==1,
                            $result->order_image,
                            $result->order_info,
                            $result->order_create_date,
                            $pay_method,
                            !empty($result->order_date)?date('Y-m-d',strtotime($result->order_date)):"",
                            $result->order_hours,
                            $result->fullname,
                            $result->zipcode,
                            $result->province,
                            $result->address,
                            $result->phone,
                            $result->order_link,
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


                            $result->one_address==1,
                            $result->id,
                            $result->session_id,
                            $result->export == 1,
                            $result->token,
                            $result->admin,
                            $result->group,
                            $result->comment,
                            $result->order_product_link,
                        ];

                    }else  if($result->company == "KOGYJA" || $result->company == "KURICHIKU1"){
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
                            $result->public==1,
                            $result->order_image,
                            $result->order_info,
                            $result->order_create_date,
                            $pay_method,
                            $result->phone,
                            $result->zipcode,
                            $result->province,
                            $result->address,
                            $result->fullname,
                            $result->order_link,
                            $result->product_id,
                            $result->product_id,
                            $result->count,
                            $result->total_count,
                            $price,
                            $price_buy,
                            !empty($result->order_date)?date('Y-m-d',strtotime($result->order_date)):"",
                            $result->order_hours,
                            $result->order_ship,
                            $total_price,
                            $result->price_buy_sale,
                            $total_price_buy,
                            $result->order_ship_cou,
                            $order_profit,
                            $result->type == "Info"?$result->rate:0,
                            $result->order_tracking,


                            $result->one_address==1,
                            $result->id,
                            $result->type,
                            $result->session_id,
                            $result->export == 1,
                            $result->token,
                            $result->order_index,
                            $result->admin,
                            $result->group,
                            $result->comment,
                            $result->order_product_link,
                        ];
                    } else if($result->company == "KURICHIKU" || $result->company == "BANH_CHUNG"){
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

//                        if(isset($_product[$result->product_id]['data']['price_buy'])){
//                            if($price == 0)
//                                $price = $_product[$result->product_id]['data']['price'];
//                            if($price_buy == 0)
//                                $price_buy = $_product[$result->product_id]['data']['price_buy'];
//                        }
//                        if($total_price == 0)
//                            $total_price = $price * $result->count;
//                        if($total_price_buy == 0)
//                            $total_price_buy = $price_buy * $result->count + $result->order_ship + $result->order_ship_cou + $result->price_buy_sale;
//                        if($order_profit == 0){
//                            $order_profit = $total_price_buy - $total_price - $result->order_ship - $result->order_ship_cou;
//                        }

                        $datas[$result->company][] = [
                            $result->public==1,
                            $result->order_image,
                            $result->order_info,
                            $result->order_create_date,
                            $pay_method,
                            $result->phone,
                            $result->zipcode,
                            $result->province,
                            $result->address,
                            $result->fullname,
                            $result->order_link,
                            $result->product_id,
                            $result->product_id,
                            $result->count,
                            $result->total_count,
                            $price,
                            $price_buy,
                            !empty($result->order_date)?date('Y-m-d',strtotime($result->order_date)):"",
                            $result->order_hours,
                            $result->order_ship,
                            $total_price,
                            $result->price_buy_sale,
                            $total_price_buy,
                            $result->order_ship_cou,
                            $order_profit,
                            (int)$result->rate*(int)$result->count+(int)$result->price_buy_sale,
                            $result->order_tracking,


                            $result->one_address==1,
                            $result->id,
                            $result->session_id,
                            $result->export == 1,
                            $result->token,
                            $result->admin,
                            $result->group,
                            $result->comment,
                            $result->order_product_link,
                        ];
                    }else{
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

                        if($result->pay_method != 3){
                            if($total_price_buy == 0)
                                $total_price_buy = $price_buy * $result->count + $result->order_ship + $result->order_ship_cou + $result->price_buy_sale;
                        }
                        if($order_profit == 0){
                            $order_profit = $total_price_buy - $total_price - $result->order_ship - $result->order_ship_cou;
                        }

                        $datas[$result->company][] = [
                            $result->public==1,
                            $result->order_image,
                            $result->order_info,
                            $result->order_create_date,
                            $pay_method,
                            $result->phone,
                            $result->zipcode,
                            $result->province,
                            $result->address,
                            $result->fullname,
                            $result->order_link,
                            $result->product_id,
                            $result->product_id,
                            $result->count,
                            $price,
                            $price_buy,
                            !empty($result->order_date)?date('Y-m-d',strtotime($result->order_date)):"",
                            $result->order_hours,
                            $result->order_ship,
                            $total_price,
                            $result->price_buy_sale,
                            $total_price_buy,
                            $result->order_ship_cou,
                            $order_profit,
                            (int)$result->rate*(int)$result->count+(int)$result->price_buy_sale,
                            $result->order_tracking,


                            $result->one_address==1,
                            $result->id,
                            $result->session_id,
                            $result->export == 1,
                            $result->token,
                            $result->admin,
                            $result->group,
                            $result->comment,
                            $result->order_product_link,
                        ];
                    }
                }
            }
        }

        return $datas;
    }
    public function edit(Request $request){
        $data = $request->all();

        if(isset($data['act'])){
            if($data['act'] == "conflict"){
                $admin_id = Auth::user()->id;
                $datas = [];
                 if(isset($data['data'])){
                     $model = new OrderExcelModel();

                     foreach ($data['data'] as $k=>$v){
                         $dataItem = [];
                         $rs1 = DB::table('shop_order_excel')
                             ->where('order_create_date',">=",date('Y-m-d',
                                 strtotime('-7 day')))->where('order_create_date','<=',date('Y-m-d H:i:s'));
                         $count = 0;
                         if(isset($v['fullname'])){



                             $rs1->where('fullname', preg_replace('/\s+/', ' ',trim($v['fullname'])));
                         }
                         if(isset($v['address'])){
                             $rs1->where('address',preg_replace('/\s+/', '',trim($v['address'])));
                         }
                         if(isset($v['province'])){
                             $rs1->where('province',preg_replace('/\s+/', '',trim($v['province'])));
                         }
                         if(isset($v['id'])){
                             if(is_numeric($v['id'])){
                                 $rs1->where('id',"!=",$v['id']);
                             }else{
                                 $rs1->where('key_id',"!=",$v['id']);
                             }
                         }
                         if($data['company'] == "YAMADA" || $data['company'] == 'FUKUI' || $data['company']  == 'OHGA' ){
                             $rs1->whereIn('company',['YAMADA','FUKUI','OHGA']);
                         }else{
                             $rs1->where('company',$data['company']);
                         }
                         $rs1 = $rs1->orderBy('sort')->get()->all();
                         $dataItem['3'] = $model-> RenderData($rs1,false);
                         $count+=count($rs1);

                         $rs2 = DB::table('shop_order_excel')
                             ->where('order_create_date',">=",date('Y-m-d',strtotime('-7 day')))
                             ->where('order_create_date','<=',date('Y-m-d H:i:s'));
                         if(isset($v['address'])){
                             $rs2->where('address',preg_replace('/\s+/', '',trim($v['address'])));
                         }
                         if(isset($v['province'])){
                             $rs2->where('province',preg_replace('/\s+/', '',trim($v['province'])));
                         }

                         if(isset($v['id'])){
                             if(is_numeric($v['id'])){
                                 $rs2->where('id',"!=",$v['id']);
                             }else{
                                 $rs2->where('key_id',"!=",$v['id']);
                             }
                         }

                         if($data['company'] == "YAMADA" || $data['company'] == 'FUKUI' || $data['company']  == 'OHGA' ){
                             $rs2->whereIn('company',['YAMADA','FUKUI','OHGA']);
                         }else{
                             $rs2->where('company',$data['company']);
                         }

                         $rs2 = $rs2->orderBy('sort')->get()->all();
                         $dataItem['2'] = $model->RenderData($rs2,false);
                         $count+=count($rs2);
                         foreach ($dataItem as $key=>$values){
                            foreach ($values as $_key=>$_val){
                                if($_key == "KOGYJA"){
                                    foreach ($_val as $__k=>$__val){
                                        $dataItem[$key][$_key][$__k]->items =  DB::table('shop_order_excel')
                                            ->where('id',"!=",$__val->id)->where('token',$__val->token)
                                            ->orderBy('order_index','ASC')
                                            ->get()->toArray();
                                    }
                                }
                            }
                         }
                         $datas[$k]['datas'] = $dataItem;
                         $datas[$k]['count'] =$count;
                     }
                 }

                return response()->json(["version"=>$this->data['version'],'lists'=>$datas,'company'=>$data["company"]]);
            }
        }


        $id = $request->id;
        $model = OrderExcelModel::find($id);
        $this->GetCache('edit',$id,"",$model->key_date);
        $results = $model->GetDetails("");
        $this->title = date('d-m-Y',strtotime($model->key_date));
        $model->detail = $this->GetData($results,false);

        $users = DB::table('admin')->select('id','name')->get()->keyBy('id')->toArray();

        return $this->render('order-excel.edit',['model'=>$model,'admin'=>$users]);
    }
    public function show(Request $request){
        $this->getCrumb()->breadcrumb(z_language("Danh sách Xuất"), route('backend:shop_ja:order:excel:show'));

        $date = $request->date;
        $company = $request->company;
        $hour = $request->hour;

        if(empty($date) && empty($company) && empty($hour)){
            if($request->isMethod('post')){
                $data = $request->all();
                $date = explode("/",$data['dateview']);
                $date = $date[2].'-'.$date[1].'-'.$date[0];
                if($data['action']<3){
                    $admin_id = Auth::user()->id;

                    DB::table('shop_order_excel_lock')->updateOrInsert(
                        [
                            'name'=>$data['name'],
                            'date'=>date('Y-m-d',strtotime($date)),
                            'admin_id'=>$admin_id
                        ]
                        ,
                        [
                            'name'=>$data['name'],
                            'admin_id'=>$admin_id,
                            'date'=>date('Y-m-d',strtotime($date)),
                            'hour'=>$data['time'],
                            'action'=>$data['action'],
                            'updated_at'=>date('Y-m-d H:i:s'),
                        ]
                    );
                    //{company?}/{date?}/{hour?}
                }

                if($data['action']!=1)
                    $data = ['link'=>route(
                        'backend:shop_ja:order:excel:show',
                        ['company'=>$data['name'],
                            'date'=>base64_encode($data['dateview']),
                            'hour'=>base64_encode($data['time']),'type'=>$data['action']])];
                return response()->json($data);
            }
            $categorys = config_get("category", "shop-ja:product:category");
            return $this->render('order-excel.show-select',['date'=>"",'compays'=>$categorys]);
        }else{
            $date = base64_decode($date);
            $hour = base64_decode($hour);
            $type = $request->type;

            $date = explode("/",$date);
            $date = $date[2].'-'.$date[1].'-'.$date[0];


            $this->GetCache('show',0,"",$date);

            $this->getCrumb()->breadcrumb(z_language("Xuất :COMPANY",["COMPANY"=>$company]), route('backend:shop_ja:order:excel:show'));
            $model = new OrderExcelModel();

            $datas = $model->ShowAll(Auth::user()->id,$date,$company,$type);

            $model->key_date =$date;
            $model->detail = $this->GetData($datas,true);
            $users = DB::table('admin')->select('id','name')->get()->keyBy('id')->toArray();
            return $this->render('order-excel.show',['hour'=>$hour,'model'=>$model,'date'=>$date,'company'=>$company,'admin'=>$users]);
        }
    }
    public function delete ($id){
        $model = OrderExcelModel::find($id);
        if($model){
            $model->delete();
        }
        return redirect()->route('backend:shop_ja:order:excel:list', []);
    }
}
