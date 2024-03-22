<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
class Backend extends Authenticatable
{
    use Notifiable;
    public $guard = 'backend';
    protected $table = 'admin';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'password',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function IsAclCheck($permission,$userfull = 'admin'){
        return $this->IsAcl($permission,$userfull);
    }
    public function IsAcl($permission,$userfull = 'admin'){

        if(empty($permission) || $this->username == $userfull){
            return true;
        }
        Cache::remember('role:'.$this->guard, 60, function()
        {
            return DB::table('role')
                ->select()->where('guard_name',$this->guard)->get();
        });
        $permissions = Cache::remember('permissions:'.$this->guard.":".$this->role_id, 5, function()
        {
            return DB::table('permissions')
                ->select()->where('role_id',$this->role_id)->get();
        });
        $permissions_user = Cache::remember('permissions:user:'.$this->guard, 5, function()
        {
            $rs = DB::table('permissions_user')
                ->select()->where('guard_name',$this->guard)->get();
            $arr = [];
            foreach ($rs as $value){
                $arr[$value->user_id] = $value;
            }
            return $arr;
        });

        $bool = $permissions->contains("name",$permission);
        if(isset($permissions_user[$this->id])){
            return $bool?($permissions_user[$this->id]->status==0?false:true):($permissions_user[$this->id]->status==1?true:false);
        }
        return $bool;
    }
    public function keyCache(){
        return $this->guard.":".$this->role_id;
    }
    public static function ResetCacheKey($guard,$role_id){
        Cache::forget('role:'.$guard);
        Cache::forget('permissions:'.$guard.":".$role_id);

    }
}
