<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Filesystem\FilesystemAdapter;

class ElfinderController extends \Zoe\Http\ControllerBackend
{
    protected $layout = 'backend::layout.elfinder';

    public function list()
    {
        return $this->render('elfinder.list', ['dir' => 'module/admin/assets/elfinder', 'locale' => app()->getLocale()]);
    }

    public function tinymce4()
    {
        return $this->render('elfinder.tinymce4', ['dir' => 'module/admin/assets/elfinder', 'locale' => app()->getLocale()]);
    }

    private $app;

    public function showConnector()
    {
//        $this->app = app();
//        $roots = $this->app->config->get('elfinder.roots', []);
//        if (empty($roots)) {
//            $dirs = (array)$this->app['config']->get('elfinder.dir', []);
//            foreach ($dirs as $dir) {
//                $roots[] = [
//                    'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
//                    'path' => public_path($dir), // path to files (REQUIRED)
//                    'URL' => url($dir), // URL to files (REQUIRED)
//                    'accessControl' => $this->app->config->get('elfinder.access') // filter callback (OPTIONAL)
//                ];
//            }
//            $disks = (array)$this->app['config']->get('elfinder.disks', []);
//            foreach ($disks as $key => $root) {
//                if (is_string($root)) {
//                    $key = $root;
//                    $root = [];
//                }
//                $disk = app('filesystem')->disk($key);
//                if ($disk instanceof FilesystemAdapter) {
//                    $defaults = [
//                        'driver' => 'Flysystem',
//                        'filesystem' => $disk->getDriver(),
//                        'alias' => $key,
//                    ];
//                    $roots[] = array_merge($defaults, $root);
//                }
//            }
//        }
//        $rootOptions = $this->app->config->get('elfinder.root_options', array());
//
//        foreach ($roots as $key => $root) {
//            $roots[$key] = array_merge($rootOptions, $root);
//        }
//        $opts = $this->app->config->get('elfinder.options', array());


        function access($attr, $path, $data, $volume, $isDir, $relpath)
        {
            $basename = basename($path);
            return $basename[0] === '.'                  // if file/folder begins with '.' (dot)
            && strlen($relpath) !== 1           // but with out volume root
                ? !($attr == 'read' || $attr == 'write') // set read+write to false, other (locked+hidden) set to true
                : null;                                 // else elFinder decide it itself
        }

        $opts = array(
            // 'debug' => true,
            'roots' => array(
                // Items volume
                array(
                    'driver' => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
                    'path' => base_path('public/uploads'),                 // path to files (REQUIRED)
                    'URL' => '/uploads/', // URL to files (REQUIRED)
//                    'trashHash' => 't1_Lw',                     // elFinder's hash of trash folder
                    'winHashFix' => DIRECTORY_SEPARATOR !== '/', // to make hash same to Linux one on windows too
                    'uploadDeny' => array('all'),                // All Mimetypes not allowed to upload
                    'uploadAllow' => array('image/x-ms-bmp', 'image/gif', 'image/jpeg', 'image/png', 'image/x-icon', 'text/plain'), // Mimetype `image` and `text/plain` allowed to upload
                    'uploadOrder' => array('deny', 'allow'),      // allowed Mimetype `image` and `text/plain` only
                    'accessControl' => 'access'                     // disable and hide dot starting files (OPTIONAL)
                ),

            )
        );

        $connector = new \elFinderConnector(new \elFinder($opts));
        $connector->run();
    }

}
