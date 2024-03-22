<?php
namespace Zoe\Facades;
use Illuminate\Support\Facades\Facade;
use Zoe\Hooks\Actions as HAction;
class Actions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return HAction::class;
    }
}