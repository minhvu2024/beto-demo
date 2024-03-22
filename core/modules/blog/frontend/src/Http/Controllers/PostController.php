<?php

namespace BlogFront\Http\Controllers;
class PostController extends \Zoe\Http\ControllerFront
{
    public function getLists()
    {
        return $this->render('post.lists');
    }
}