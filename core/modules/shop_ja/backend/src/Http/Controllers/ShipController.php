<?php
namespace ShopJa\Http\Controllers;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use ShopJa\Http\Models\ShipModel;
class ShipController extends \Zoe\Http\ControllerBackend
{
    public function init()
    {
        $this->data['language'] = config('zoe.language');
        $this->data['nestables'] = config_get("category", "shop-ja:product:category");
        $this->data['configs'] = config_get("config", "shopja");
        $this->data['current_language'] = isset($this->data['configs']['shopja']['language']['default']) ? $this->data['configs']['shopja']['language']['default'] : "en";

    }
    public function getCrumb()
    {
        $this->breadcrumb(z_language("Quản lý tiền ship"), route('backend:shop_ja:ship:list'));
        return $this;
    }
    public function list(Request $request)
    {
        $this->getcrumb();

        $filter = $request->query('filter', []);

        $search = $request->query('search', "");
        $status = $request->query('status', "");
        $date = $request->query('date', "");

        $config = config_get('option', "module:shop_ja:product");
        $item = isset($config['pagination']['item']) ? $config['pagination']['item'] : 20;

        $models = DB::table('shop_ship');
        if(isset($filter['search'])){
            $search = $filter['search'];
        }
//        if(isset($filter['code'])){
//            $models->where('code', 'like', '%' . $filter['code']);
//        }
//        if (!empty($search)) {
//            $models->where('title', 'like', '%' . $search);
//        }
//        if (!empty($status) || $status != "") {
//            $models->where('status', $status);
//        }
//        $models->orderBy('value_end', 'asc');
        $models->orderBy('category_id','asc');
        $category =  get_category_type('shop-ja:product:category');
        $units = config('shop_ja.configs.lists_uint');
        return $this->render('ship.lists', [
            'models' => $models->paginate($item),
            'callback' => [
                "GetNameCategory" => function ($model) use($category,$units){
                    $html =" all";
                    if(isset($units[$model->unit])){
                        $html = " ".$units[$model->unit];
                    }
                    $html = isset($category[$model->category_id])?$category[$model->category_id]->name." - (".$model->value_start."-".$model->value_end.")".$html:"Không xác định";

                    return $html;
                },
                "GetEqual"=>function($model) use($units){
                    $html =" all";
                    if(isset($units[$model->unit])){
                        $html =" ".$units[$model->unit];
                    }
                    return "([SL] ".$model->equal_start.$model->value_start.' và[SL]'.$model->equal_end.' '.$model->value_end.") ".$html;
                },
                'GetUnit'=>function($model) use($units){
                    $html = "Tất cả";
                    if(isset($units[$model->unit])){
                        $html = $units[$model->unit];
                    }
                    return $html;
                }
            ]
        ]);
    }
    public function create(){
       return $this->render('ship.create', ['item' => []],'shop_ja');
    }
    public function edit($id)
    {
        $this->getcrumb()->breadcrumb(z_language("Sửa"), false);
        $model = ShipModel::find($id);
        return $this->render('ship.edit', ["model" => $model,'act'=>'edit']);
    }
    public function copy($id){
        $this->getcrumb()->breadcrumb(z_language("Thêm"), false);
        $model = ShipModel::find($id);
        return $this->render('ship.edit', ["model" => $model,'act'=>'copy']);
    }
    public function store(Request $request){

        $data = $request->all();

        $validator = Validator::make($data, [
            'category_id' => 'required',
            'value_start' => 'required',
            'value_end' => 'required',
        ], []);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $type = 'create';
        if (isset($data['id']) && !empty($data['id'])) {
            $model = ShipModel::find($data['id']);
            $type = 'edit';
        } else {
            $model = new ShipModel();
        }
       $category_city  =  config_get('shop_ja','category:city',[]);
        try {
            $arrs_config = json_decode($data['config'],true);
            foreach ($arrs_config as $k=>$value){
               $arr = explode("-",$value['text']);
               foreach ($arr as $_k=>$_v){
                   $category_city[$_v] = 1;
               }
            }
            config_set('shop_ja','category:city',['data'=>$category_city]);

            $model->category_id = $data['category_id'];
            $model->value_start= $data['value_start'];
            $model->value_end= $data['value_end'];
            $model->equal_start = $data['equal_start'];
            $model->equal_end = $data['equal_end'];
            $model->config = $data['config'];

            $model->unit = $data['unit'];
            $model->save();
            $this->log('shop_js:ship',$type,['id' => $model->id]);
            $request->session()->flash('success',z_language('Cập nhật thông tin thành công'));

            return redirect(route('backend:shop_ja:ship:edit', ['id' => $model->id]));
        }catch (\Exception $ex){
            $validator->getMessageBag()->add('id', $ex->getMessage());
        }
        return back()->withErrors($validator)
            ->withInput();
    }
}
