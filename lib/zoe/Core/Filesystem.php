<?php
namespace Zoe\Core;
class Filesystem extends \Illuminate\Filesystem\Filesystem {
    public function __construct()
    {

    }
    public function put($path, $contents, $lock = false)
    {
        return file_put_contents($path, $contents, $lock ? LOCK_SH : 0);
    }

}