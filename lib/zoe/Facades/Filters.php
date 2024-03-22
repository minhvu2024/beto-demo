<?php
namespace Zoe\Facades;
use Illuminate\Support\Facades\Facade;
use Zoe\Hooks\Actions as HFilters;
class Filters extends Facade
{
    protected static function getFacadeAccessor()
    {
        return HFilters::class;
    }
}