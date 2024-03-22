<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use User\Http\Model\Permission;
use User\Http\Model\Role;

class DashboardController extends \Zoe\Http\ControllerBackend
{
    public function list(Request $request)
    {
        return $this->render('dashboard.list');
    }
    public function media(){

        return $this->render('dashboard.media');
    }
    public function option(Request $request)
    {
        $items = $request->all();
        if (isset($items['act'])) {
            if ($items['act'] == 'get') {
                return view('backend::configs.option', ['configs' => $items['configs']]);
            } else if ($items['act'] == 'save') {
                DB::table('config')->updateOrInsert(
                    [
                        'name' => $items['name'],
                        'type' => 'option'
                    ],
                    ['data' => serialize($items['data'])]);
            }
        }
    }


    public function router(Request $request)
    {
        $controllers = [];
        $results = getDirContents(base_path('core'), '/frontend(.*?)Controller\.php$/', $results);
        foreach ($results as $value) {
            $pathinfo = pathinfo($value);
            $namespace = extract_namespace($value);
            $clazz = $namespace . '\\' . $pathinfo['filename'];
            $methodParent = get_class_methods(get_parent_class($clazz));
            $methodClass = get_class_methods($clazz);
            foreach ($methodClass as $method){
                if(!in_array($method,$methodParent) && (substr($method, 0, 3) == 'get' || substr($method, 0, 4) == 'post')){
                    $controllers[$clazz.'@'.$method] = $namespace;
                }
            }
        }
        $data = $request->all();
        if ($request->isMethod('post')) {
            config_set('router', 'frontend', ['data' => $data]);
        }
        $listsRole = Role::where('guard_name', 'web')->get();
        $listsRolePremission = [];
        foreach ($listsRole as $item) {
            $listsRolePremission[$item->id] = Permission::where('role_id', $item->id)->get();
        }
        $layouts = \Admin\Http\Models\Layout::where('type_group', 'theme')->where('type', 'layout')->orderBy("updated_at", "desc")->get();

        return $this->render('dashboard.router', [
            'listsRolePremission' => $listsRolePremission,
            'layouts' => $layouts,
            'datas' => config_get('router', 'frontend'),
            'controllers' => $controllers
        ]);
    }
}
