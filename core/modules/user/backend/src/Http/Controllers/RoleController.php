<?php

namespace User\Http\Controllers;

use Illuminate\Http\Request;
use \User\Http\Model\Role;
use Validator;
use Illuminate\Support\Facades\DB;
class RoleController extends \Zoe\Http\ControllerBackend
{
    public function getCrumb()
    {
        $this->breadcrumb("Tài khoản quản trị", route('backend:user:list'));
        return $this;
    }
    public function list()
    {
        $allRoles = Role::all();
        return $this->render('role.list', [
            'lists' => $allRoles
        ], 'user');
    }
    public function permission($id,$guard,Request $request)
    {
        $modelRole = new Role();

        if($request->isXmlHttpRequest()){
            $post = $request->all();
            $this->log('role','permission',['guard'=>$guard]);
            $modelRole->SaveData($id,$guard,isset($post['data'])?$post['data']:[]);
            return $post;
        }

        return $this->render('role.premission', [
            'user_permissions' => $modelRole->GetPermissions($id),
            'global_permissions' => app()->getPermissions(),
            'id'=>$id,
            'guard'=>$guard,
            'acl_static'=>acl_all_key()
        ], 'user');
    }
    public function error(){
        return $this->render('role.error');
    }
    public function create(){
        return $this->render('role.create');
    }
    public function edit($id){
        $roles = DB::table('role')->where('guard_name','backend')->get()->all();
        $this->data['roles'] = [];
        foreach ($roles as $role){
            $this->data['roles'][$role->id] = $role->name;
        }
        $this->getcrumb()->breadcrumb(z_language("Sửa"), false);
        $model = Role::find($id);
        return $this->render('role.edit',["model" => $model]);
    }
    public function store(Request $request){
        $data = $request->all();
        $filter = [

        ];
        $type = 'create';

        if (isset($data['id']) && !empty($data['id'])) {

            $model = Role::find($data['id']);
            $type = 'edit';
            $filter['name'] = 'required|max:255';
            if($model->name != $data['name']){
                $filter['name'] = $filter['name'].'|unique:role';
            }
        }else{
            $filter['name'] = 'required|max:255|unique:role';
            $model = new Role();
        }

        $validator = Validator::make($data,$filter, [
            'name.required' => z_language('Tên nhóm quyền không được phép bỏ trống.'),
            'name.unique' => z_language('Tên nhóm quyền đã tồn tại.'),
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $model->name = $data['name'];
            $model->guard_name = $data['guard_name'];
            $model->status = $data['status'];

            $model->save();

            $this->log('user:user',$type,['id'=>$model->id]);

            $request->session()->flash('success',z_language('Cập nhật thông tin thành công'));

            return redirect(route('backend:user:role:edit', ['id' => $model->id]));
        }catch (\Exception $ex){
            $validator->getMessageBag()->add('name', $ex->getMessage());
        }
        return back()
            ->withErrors($validator)
            ->withInput();
    }
}

