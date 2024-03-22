<?php
namespace ShopJa\Http\Controllers;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use \ShopJa\Http\Models\ProductModel;
class CategoryController extends \Admin\Http\Controllers\CategoryController
{

    public function ajax(Request $request){
        $post = $request->all();
        $respon = parent::ajax($request);
        if ($post['act'] == "edit") {
            $data = $respon->getData()->data;
            $data->data  = [];
            $shop_product = DB::table('shop_product')->where('id',$post['data']['pro_id'])->get()->all();
            $category = DB::table('shop_ship_category')->where('category_id',$data->id)->where('product_id',$post['data']['pro_id'])->get()->all();

            $data->info = $data->name . " - Sản phẩm : " .$shop_product[0]->title . " - " .$shop_product[0]->description;

            if(isset($category[0])){
                $data->data = unserialize($category[0]->data);
            }
            return response()->json(['data' => $data]);
        }
        if ($post['act'] == "info") {
            $dataJson = $respon->getData();
            if (property_exists($dataJson, 'success') && property_exists($dataJson->success, 'product_id') && !is_null( $dataJson->success->product_id)) {
                $data = isset($post["data"]['data']) && is_array($post["data"]['data']) ? serialize($post["data"]['data']) : serialize([]);
                DB::table('shop_ship_category')->
                updateOrInsert(['category_id' => $dataJson->success->id, 'product_id' => $dataJson->success->product_id], ['data' => $data, 'updated_at' => date('Y-m-d H:i:s')]);
                if(isset($post['cates'])){
                    foreach ($post['cates'] as $cate){
                        if($cate != $dataJson->success->id){
                            DB::table('shop_ship_category')->
                            updateOrInsert(['category_id' =>$cate, 'product_id' => $dataJson->success->product_id], ['data' => $data, 'updated_at' => date('Y-m-d H:i:s')]);
                        }
                    }
                }
            }
        }
        return $respon;
    }
    public function ajaxComShip(Request $request){
        return $respon = parent::ajax($request);
    }
    public function show(Request $request)
    {
        if(isset($request->route()->parameters['product_id'])){
            $this->data["product_id"] = $request->route()->parameters['product_id'];
        }else{
            $this->data["product_id"]  = "";
        }
       return parent::show($request);
    }

}
