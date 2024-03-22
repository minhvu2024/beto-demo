<?php
namespace Zoe\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
class PermissionMiddleware
{
    public $_permission;
    public function __construct()
    {
     //   $this->_permission = app()->make('tigon:app')->config("modules")->packages["permission"];
    }

    public function handle($request, Closure $next, $permission = "")
    {
        $permissions = explode("-",$permission);
        if(isset($permissions[0]) && isset($permissions[1])){
            if (Auth::guard($permissions[0])->check()) {
                if(!Auth::guard($permissions[0])->user()->IsAcl($permissions[1])){
                    if(isset($this->_permission[$permissions[0]]['error'])){
                        return redirect(route($this->_permission[$permissions[0]]['error']));
                    }else{
                        return redirect(route('backend:user:role:error:error'));
                    }
                }
            }
        }
        return $next($request);
    }
}
