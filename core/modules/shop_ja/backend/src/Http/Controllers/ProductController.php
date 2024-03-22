<?php
namespace ShopJa\Http\Controllers;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use \ShopJa\Http\Models\ProductModel;
class ProductController extends \Zoe\Http\ControllerBackend
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
        $this->breadcrumb(z_language("Quản lý sản phẩm"), route('backend:shop_ja:product:list'));
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

        $models = DB::table('shop_product');
        if(isset($filter['search'])){
            $search = $filter['search'];
        }else {
            $filter_search = $request->query('filter_search', "");
            if(!empty($filter_search)){
                $search = $filter_search;
            }
        }
        if(isset($filter['code'])){
            $models->where('code', 'like', '%' . $filter['code'].'%');
        }
        if(isset($filter['cate'])){
            $models->where('category_id', '=',$filter['cate'] );
        }
        if (!empty($search)) {
            $models->where('title', 'like', '%' . $search.'%');
        }
        if(isset($filter['des'])){
            $models->where('description', 'like', '%' . $filter['des'].'%');
        }
        if (!empty($status) || $status != "") {
            $models->where('status', $status);
        }
        $models->orderBy('order_index', 'desc');

        return $this->render('product.list', [
            'models' => $models->paginate($item),
            'callback' => [
                "GetHtmlConfigShip" => function ($model){
                    $html = "<a href='".route('backend:shop_ja:japan:category:show',['product_id' => $model->id])."'><button type=\"button\" class=\"btn btn-primary btn-xs\">Click</button></a>";
                    return $html;
                },
                "GetButtonEdit"=>function($model){
                    return  $html = "<a href='".route('backend:shop_ja:product:edit',['id' => $model->id])."'><button type=\"button\" class=\"btn btn-primary btn-xs\">".z_language('Sửa')."</button></a>";
                }
            ]
        ]);
    }
    public function create()
    {
        $this->getCrumb()->breadcrumb(z_language("Tạo mới"), route('backend:shop_ja:product:create'));
        return $this->render('product.create', ['item' => []], 'blog');
    }

    public function edit($id)
    {
        $this->getcrumb()->breadcrumb(z_language("Sửa"), false);
        $model = ProductModel::find($id);
        return $this->render('product.edit', ["model" => $model]);
    }

    public function delete($id)
    {
        $model = ProductModel::find($id);
        if($model){
            $model->delete();
        }
        return redirect()->route('backend:shop_ja:product:list', []);
    }

    public function status()
    {

    }

    public function store(Request $request){

        $data = $request->all();
        $validator = Validator::make($data, [
//            'image' => 'required',
            'title' => 'required',
            'category_id' => 'required',
            'price' => 'required|numeric|between:0,9999999.99',
            'price_buy' => 'required|numeric|between:0,9999999.99',
        ], [
//            'image.required' => z_language('Ảnh sản phẩm không được phép bỏ trống.'),
            'title.required' => z_language('Tên sản phẩm không được phép bỏ trống.'),
            'category_id.required' => z_language('Chuyên mục không được phép bỏ trống.'),
            'price.required' => z_language('Giá nhập không được bỏ trống.'),
            'price.numeric' => z_language('Giá nhập phải là giá trị số.'),
            'price_buy.required' => z_language('Giá bán không được bỏ trống.'),
            'price_buy.numeric' => z_language('Giá bán phải là giá trị số.'),
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $type = 'create';
        if (isset($data['id']) && !empty($data['id'])) {
            $model = ProductModel::find($data['id']);
            $type = 'edit';
        } else {
            $model = new ProductModel();
        }
        try {
            $model->title = $data['title'];
            $model->slug = $model->title ;
            $model->description = $data['description'];
            $model->category_id = $data['category_id'];
            $model->image =  $data['image'];
            $model->price = $data['price'];
            $model->code = $data['code'];
            $model->unit = $data['unit'];
            $model->status = $data['status'];
            $model->value = $data['value'];
            $model->link = $data['link'];
            $model->price_buy = $data['price_buy'];
            $model->type_excel = $data['type_excel'];
            $model->order_index = isset($data['order_index'])?$data['order_index']:0;
            $model->save();
            $this->log('shop_js:product',$type,['id'=>$model->id]);
            $request->session()->flash('success',z_language('Cập nhật thông tin thành công'));
            return redirect(route('backend:shop_ja:product:edit', ['id' => $model->id]));
        }catch (\Exception $ex){
            $validator->getMessageBag()->add('id', $ex->getMessage());
        }


        return back();



    }
}
