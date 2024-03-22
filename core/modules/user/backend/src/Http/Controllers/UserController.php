<?php

namespace User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use User\Http\Model\Admin;
use Illuminate\Support\Facades\Hash;
class UserController extends \Zoe\Http\ControllerBackend
{
    public function getCrumb()
    {
        $this->breadcrumb("Tài khoản quản trị", route('backend:user:list'));
        return $this;
    }

    public function list(Request $request)
    {
        $this->getcrumb();

        $search = $request->query('search', "");
        $status = $request->query('status', "");

        $config = config_get('option', "core:user:list");
        $data = $request->query();

        $page = null;
        if (isset($data['action'])) {
            $page = 1;
        }
        $parameter = $data;

        $route = [];

        $item = isset($config['pagination']['item']) ? $config['pagination']['item'] : 20;

        $models = DB::table('admin');

        if (isset($search) && !empty($search) || isset($parameter["filter"]['name']) && !empty($parameter['filter']['name']) && $search = $parameter['filter']['name']) {
            $models->where('name', 'like', '%' . $search . '%');

        }
        if (isset($parameter["filter"]['type']) && !empty($parameter['filter']['type'])) {
            $models->where('type', $parameter['filter']['type']);
        }
        if (!empty($status) || $status != "") {
            $models->where('status', $status);
        }
        if (!isset($parameter['order_by'])) {
            $parameter['order_by']['col'] = 'id';
            $parameter['order_by']['type'] = 'desc';
        } else {
            if (isset($parameter['action'])) {
                $parameter['order_by']['type'] = isset($parameter['order_by']['type']) && $parameter['order_by']['type'] == "desc" ? "asc" : "desc";

            }
        }
        if (isset($parameter['action'])) {
            unset($parameter['action']);
        }
        $models->orderBy($parameter['order_by']['col'], $parameter['order_by']['type']);

        $models = $models->paginate($item, ['*'], 'page', $page);
        $models->appends($parameter);
        return $this->render('user.list', [
            'models' => $models,
            "route" => $route,
            'parameter' => $parameter
        ]);
    }

    public function edit($id)
    {
        $roles = DB::table('role')->where('guard_name','backend')->get()->all();
        $this->data['roles'] = [];
        foreach ($roles as $role){
            $this->data['roles'][$role->id] = $role->name;
        }
        $this->getcrumb()->breadcrumb(z_language("Sửa"), false);
        $model = Admin::find($id);

        return $this->render('user.edit', ["model" => $model]);
    }

    public function create()
    {
        $roles = DB::table('role')->where('guard_name','backend')->get()->all();
        $this->data['roles'] = [];
        foreach ($roles as $role){
            $this->data['roles'][$role->id] = $role->name;
        }
        return $this->render('user.create');
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $ref = $request->ref;
        $model = Admin::find($id);
        if($model){
           $model->delete();
        }
        if($ref){
            return redirect($ref);
        }else{
            return redirect(route('backend:user:list', []));
        }
    }
    public function store(Request $request){
        $data = $request->all();
        $filter = [
            'name' => 'required|max:255',
            'role_id' => 'required',
        ];
        $type = 'create';

        if (isset($data['id']) && !empty($data['id'])) {
            $model = Admin::find($data['id']);
            $type = 'edit';
            if(!empty($data['password']))
                $filter['password'] = 'required|min:6';
            if($model->username != $data['username']){
                $filter['username'] = 'required|max:255|unique:admin';
            }
        } else {
            $filter['password'] = 'required|min:6';
            $filter['username'] = 'required|max:255|unique:admin';
            $model = new Admin();
        }
        $validator = Validator::make($data,$filter, [
            'name.required' => z_language('Tên  không được phép bỏ trống.'),
            'username.required' => z_language('Tài khoản không được phép bỏ trống.'),
            'username.unique' => z_language('Tài khoản đã tồn tại.'),
            'password.required' => z_language('Mật khẩu không được phép bỏ trống.'),
            'role_id.required' => z_language('Nhóm quyền không được phép bỏ trống.'),
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $model->name = $data['name'];
            $model->username = $data['username'];
            if(isset($filter['password']))
                $model->password = Hash::make( $data['password']);
            $model->role_id = $data['role_id'];
            $model->save();
            $this->log('user:user',$type,['id'=>$model->id]);
            $request->session()->flash('success',z_language('Cập nhật thông tin thành công'));
            return redirect(route('backend:user:edit', ['id' => $model->id]));
        }catch (\Exception $ex){
            $validator->getMessageBag()->add('name', $ex->getMessage());
        }
        return back()
            ->withErrors($validator)
            ->withInput();
    }
}