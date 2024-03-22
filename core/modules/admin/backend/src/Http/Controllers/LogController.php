<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Admin\Http\Models\Menu;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class LogController extends \Zoe\Http\ControllerBackend
{
    public function getCrumb()
    {
        $this->breadcrumb("Log", route('backend:menu:list'));
        return $this;
    }

    public function init()
    {
        parent::init();
    }
    public function list(Request $request)
    {
        $this->getcrumb();
        $search = $request->query('search', "");
        $status = $request->query('status', "");
        $date = $request->query('date', "");

        $config = config_get('option', "core:page");
        $item = isset($config['pagination']['item']) ? $config['pagination']['item'] : 20;

        $models = DB::table('log');
        if (!empty($search)) {
            $models->where('admin', 'like', '%' . $search);
        }

        $models->orderBy('id', 'desc');

        $admins = new \stdClass();

        return $this->render('log.list', [
            'models' => $models->paginate($item),
            'callback' => [
                "getAdmin" => function ($model) use ($admins){
                   if($model->admin_id == 0)
                    return 'Guest';
                   if(property_exists($admins,$model->admin_id)){
                        return $admins->{$model->admin_id};
                   }
                   $results  = DB::table('admin')->where('id',$model->admin_id)->get()->all();
                   if(isset($results[0])){
                       $admins->{$model->admin_id} =  $results[0]->username;
                       return $admins->{$model->admin_id};
                   }
                },
                "getData"=>function($model){
                    if($model->name == "shop_js:excel:change"){
                        $datas = json_decode($model->datas,true) ;
                            $html = "<table class='table-bordered table-bordered' style=\" width: 100%;\">";

                            $html.="<tr>";
                                $html.="<td style=\"vertical-align: middle;\">".$datas["create_date"]."|</td>";
                                $html.="<td style=\"vertical-align: middle;\">".$datas["id"]."|</td>";
                                $html.="<td style=\"vertical-align: middle;\">".$datas["username"]."|</td>";
                                $html.="<td style=\"vertical-align: middle;\">".$datas["company"]."</td>";
                                $html.="<td>";
                                    $html.= "<table class='table-bordered table-bordered' style=\" width: 100%;\">";
                                        $html.="<tr><th>Dữ liệu cũ</th><th>Dữ liệu mới</th><th>Cột</th></tr>";

                                            foreach ($datas['change'] as $key=>$value){
                                                $html.="<tr>";
                                                $html.="<td>".$value[0]."</td>";
                                                $html.="<td>".$value[1]."</td>";
                                                $html.="<td>".$value[2]."</td>";
                                                $html.="</tr>";
                                            }

                                    $html.="</table>";
                                $html.="</td>";
                            $html.="</tr>";
                        $html.="</table>";

                        return $html;
                    }else{
                        return "<pre>".$model->datas."</pre>";
                    }
                }
            ]
        ]);
    }
}
