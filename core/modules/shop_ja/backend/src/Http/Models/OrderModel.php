<?php

namespace ShopJa\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderModel extends Model
{
    protected $table = 'shop_order';
    protected $fillable = [];
    public function GetDetails(){
        $results = DB::table('shop_order_detail')->where('order_id',$this->id)->get()->all();
        $datas = [];
        foreach ($results as $result){
            $datas[] = ['id'=>$result->product_id,'count'=>$result->count,'image'=>$result->image];
        }
        return $datas;
    }
}
