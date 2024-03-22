<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use User\Http\Model\Permission;
use User\Http\Model\Role;

class DatabaseController extends \Zoe\Http\ControllerBackend
{
    public function list()
    {
        return $this->render('database.list');
    }
}