<?php
namespace Zoe\Http;
use App\Http\Kernel as LKernel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;

class Kernel extends LKernel{
    public function __construct(Application $app, Router $router)
    {
        parent::__construct($app, $router);
    }
}