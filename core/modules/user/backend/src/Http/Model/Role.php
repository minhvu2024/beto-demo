<?php
namespace User\Http\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Role extends Model{
    protected $table = 'role';
    public function GetPermissions($role_id){
        return DB::table('permissions')
            ->select('name', 'role_id')->where('role_id',$role_id)->pluck('role_id','name')->toArray();;
    }
    public function SaveData($role_id,$guard,$data){

        $time = date('Y-m-d H:i:s');

        DB::beginTransaction();
        try{
            foreach ($data as $val=>$status){
                DB::table('permissions')->updateOrInsert(['name'=>$val,'role_id'=>$role_id], ['created_at'=>$time,'updated_at'=>$time]);
            }
            DB::table('permissions')->where('role_id',$role_id)->where('updated_at','!=',$time)->delete();
            DB::commit();
            \App\Backend::ResetCacheKey($guard,$role_id);
        }catch (\Exception $ex){
            DB::rollBack();
        }
    }
}
