<?php

namespace ShopJa\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderExcelModel extends Model
{
    protected $table = 'shop_order_excel_session';
    protected $fillable = [];
    public function RenderData($ressult ,$def = true,$sort = true){
        $datas = [

        ];
        $users = DB::table('admin')->select('id','username')->get()->keyBy('id')->toArray();
        foreach ($ressult as $_key=>$_value){
            if(!isset($datas[$_value->company])){
                $datas[$_value->company] = [];
            }
            $_value->admin = isset($users[$_value->admin_id])?$users[$_value->admin_id]->username:"";
            $datas[$_value->company][] = $_value;
        }
        $dataNew = [];

        if(count($datas)>0) {
            foreach ($datas as $key=>$values){
                if($sort){
                    usort($values, function ($a, $b) {
                        return ($a->pay_method) - $b->pay_method;
                    });
                }
                $dataNew[$key] = $values;
            }

            if(isset($dataNew['KOGYJA'])){
                $KOGYJA = [];

                foreach ($dataNew['KOGYJA'] as $k=>$value){
                    if($value->type == "Info"){
                        $row = [$value];
                        foreach ($dataNew['KOGYJA'] as $kk=>$value1){
                            if($k != $kk && $value1->token == $value->token )
                            {
                                $value1->order_create_date = "";
                                $value1->pay_method = "";
                                $value1->phone = "";
                                $value1->order_date = "";
                                $value1->order_hours = "";
                                $value1->admin = "";
                                $value1->fullname = "";
                                $value1->address = "";
                                $value1->zipcode = "";
                                $value1->province = "";

                                if($value1->type =="Item") {
                                    $value1->type = "Item";
                                    $value1->total_count = "";
                                    $value1->order_ship_cou = "";
                                    $value1->rate = "";
                                }else if($value1->type=="Footer"){

                                }
                                $row[] = $value1;
                            }
                        }
                        usort($row, function ($a, $b) {
                            return ($a->order_index) - $b->order_index;
                        });

                        if($def)
                        {
                            $n = count($row);
                            $item_last = $row[$n-1];
                            $item = new \stdClass();
                            foreach ($item_last as $key=>$v){
                                $item->{$key} = "";
                            }
                            $item->order_index =  $item_last->order_index;
                            $item->company =  $item_last->company;
                            $item->type = "Item";
                            $item_last->order_index++;
                            $row[] = $item;
                        }
                        usort($row, function ($a, $b) {
                            return ($a->order_index) - $b->order_index;
                        });

                        foreach ($row as $k=>$v){
                            $KOGYJA[] = $v;
                        }

                    }
                }
                $dataNew['KOGYJA'] = $KOGYJA;
            }
        }
        return $dataNew;
    }
    public function GetDetails($compay){
        $shop_order_excel = DB::table('shop_order_excel')->where('session_id',$this->id)
            ->orderBy('id','ASC')->orderBy('sort','ASC')->get()->all();
        return $this->RenderData($shop_order_excel,true,false);
    }
    public function ShowAll($user_id,$date,$company,$type){
//        $lists = DB::table('shop_order_excel_session')
////            ->where('admin_id',$user_id)
//            ->where('status',1)
//            ->where('created_at','>=',$date." 00:00:00")
//            ->where('created_at','<=',$date." 23:59:59")
//            ->get()->all();




        $date_last = date('Y-m-d',strtotime('-1 day', strtotime($date)));
      //  foreach ($lists as $key=>$value){
           $shop_order_excel =  DB::table('shop_order_excel')
               ->where('public',1)
//               ->where('company', $company )
               ->where('order_create_date','>=',$date." 00:00:00")
               ->where('order_create_date','<=',$date." 23:59:59")->orderBy('sort');

           if($type == 2){
               //$shop_order_excel->where('export',0);
           }

            $shop_order_excel = $shop_order_excel->get()->all();
            $queries = DB::getQueryLog();
            $last_query = end($queries);

        $users = DB::table('admin')->select('id','username')->get()->keyBy('id')->toArray();
        $datas = [

        ];

        foreach ($shop_order_excel as $_key=>$_value){
            if(!isset($datas[$_value->company])){
                $datas[$_value->company] = [];
            }
            $_value->admin = isset($users[$_value->admin_id])?$users[$_value->admin_id]->username:"";
            $datas[$_value->company][] = $_value;
        }

        $dataNew = [];

        if(count($datas)>0) {

            foreach ($datas as $key=>$values){
                usort($values, function ($a, $b) {
                    if($a->pay_method == $b->pay_method){
                        return $a->sort - $b->sort;
                    }else{
                        return $a->pay_method - $b->pay_method;
                    }
                });
                $dataNew[$key] = $values;
            }

            if(isset($dataNew['KOGYJA'])){
                $KOGYJA = [];
                foreach ($dataNew['KOGYJA'] as $k=>$value){
                    if($value->type == "Info"){
                        $value->old_pay_method = $value->pay_method;
                        $row = [$value];
                        foreach ($dataNew['KOGYJA'] as $kk=>$value1){
                            if($k != $kk && $value1->token == $value->token )
                            {
                                $value1->order_create_date = "";
                                $value1->old_pay_method = $value1->pay_method;
                                $value1->pay_method = "";
                                $value1->phone = "";
                                $value1->order_date = "";
                                $value1->order_hours = "";
                                $value1->admin = "";
                                $value1->fullname = "";
                                $value1->address = "";
                                $value1->zipcode = "";
                                $value1->province = "";

                                if($value1->type =="Item") {
                                    $value1->type = "Item";
                                    $value1->total_count = "";
                                    $value1->order_ship_cou = "";
                                    $value1->rate = "";
                                }else if($value1->type=="Footer"){
                                    $value1->product_id = "";
                                    $value1->total_count = "";
                                }

                                $row[] = $value1;
                            }
                        }

                        usort($row, function ($a, $b) {

                            if($a->old_pay_method == $b->old_pay_method){
                                return $a->sort - $b->sort;
                            }else{
                                return $a->old_pay_method - $b->old_pay_method;
                            }
                        });
                        foreach ($row as $k=>$v){
                            $KOGYJA[] = $v;
                        }
                    }
                }
                usort($KOGYJA, function ($a, $b) {
                    return $a->sort - $b->sort;
                });
                $dataNew['KOGYJA'] = $KOGYJA;
            }
        }
        return $dataNew;

//           foreach ($shop_order_excel as $_key=>$_value){
//               $datas[] = $_value;
//           }
//     //   }
//        if(count($datas)>1){
//            usort($datas, function ($a,$b){
//                return $a->pay_method - $b->pay_method;
//            });
//        }
//
//        return $datas;
    }
    public function searchAll($user_id,$par = []){

        $shop_order_excel =  DB::table('shop_order_excel');
        if(!empty($par['company'])){
            $shop_order_excel->where('company',$par['company']);
        }
        if(!empty($par['fullname'])){
            $shop_order_excel->where('fullname', 'like', '%'.$par['fullname'].'%');
        }
        if(!empty($par['address'])){
            $shop_order_excel->where('address', 'like', '%'.$par['address'].'%');
        }
        if(!empty($par['zipcode'])){
            $shop_order_excel->where('zipcode', '=', $par['zipcode']);
        }

        $shop_order_excel  = $shop_order_excel->orderBy('id','desc')->get()->all();
        $users = DB::table('admin')->select('id','username')->get()->keyBy('id')->toArray();
        $datas = [

        ];

        foreach ($shop_order_excel as $_key=>$_value){
            if(!isset($datas[$_value->company])){
                $datas[$_value->company] = [];
            }
            $_value->admin = isset($users[$_value->admin_id])?$users[$_value->admin_id]->username:"";
            $datas[$_value->company][] = $_value;
        }

        $dataNew = [];

        if(count($datas)>0) {

            foreach ($datas as $key=>$values){
                usort($values, function ($a, $b) {
                    return ($a->pay_method) - $b->pay_method;
                });
                $dataNew[$key] = $values;
            }

            if(isset($dataNew['KOGYJA'])){
                $KOGYJA = [];
                foreach ($dataNew['KOGYJA'] as $k=>$value){
                    if($value->type == "Info"){
                        $row = [$value];
                        foreach ($dataNew['KOGYJA'] as $kk=>$value1){
                            if($k != $kk && $value1->token == $value->token )
                            {
                                $value1->order_create_date = "";
                                $value1->pay_method = "";
                                $value1->phone = "";
                                $value1->order_date = "";
                                $value1->order_hours = "";
                                $value1->admin = "";
                                if($value1->type!="Footer") $value1->type = "Item";
                                $row[] = $value1;
                            }
                        }

                        usort($row, function ($a, $b) {
                            return ($a->order_index) - $b->order_index;
                        });
                        foreach ($row as $k=>$v){
                            $KOGYJA[] = $v;
                        }

                    }
                }
                $dataNew['KOGYJA'] = $KOGYJA;
            }
        }

        return $dataNew;

    }
}
