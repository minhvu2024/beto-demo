<?php
namespace UserFront\Http\Controllers;
class UserController extends \Zoe\Http\ControllerFront
{
    public function getInfo()
    {
        return view('user_front::controller.user.info');
    }
}