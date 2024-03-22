<?php

namespace Admin\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ComponentController extends \Zoe\Http\ControllerBackend
{
    public function list(Request $request)
    {
        $item = 20;
        $models = DB::table('component');
        $models->orderBy('id', 'desc');
        return $this->render('component.list', [
            'models' => $models->paginate($item)
        ]);
    }
}