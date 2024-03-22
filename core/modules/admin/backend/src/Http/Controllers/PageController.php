<?php

namespace Admin\Http\Controllers;

use Admin\Http\Models\PageModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends \Zoe\Http\ControllerBackend
{
    public function getCrumb()
    {
        $this->breadcrumb(z_language("Page"), route('backend:page:list'));
        return $this;
    }

    public function list(Request $request)
    {
        $this->getcrumb();
        $search = $request->query('search', "");
        $status = $request->query('status', "");
        $date = $request->query('date', "");

        $config = config_get('option', "core:page");
        $item = isset($config['pagination']['item']) ? $config['pagination']['item'] : 20;

        $models = DB::table('page');
        if (!empty($search)) {
            $models->where('title', 'like', '%' . $search);
        }
        if (!empty($status) || $status != "") {
            $models->where('status', $status);
        }
        $models->orderBy('id', 'desc');


        return $this->render('page.list', [
            'models' => $models->paginate($item)
        ]);
    }

    public function create(Request $request)
    {
        $this->getcrumb()->breadcrumb("Create Page", false);
        return $this->render('page.create', []);
    }

    public function edit($id)
    {
        $this->getcrumb()->breadcrumb("Edit Page", false);
        $page = PageModel::find($id);
        return $this->render('page.edit', ["page" => $page]);
    }

    public function delete()
    {

    }

    public function status()
    {

    }

    public function store(Request $request)
    {
        $items = $request->all();
        if (isset($items) && isset($items['id'])) {
            $page = PageModel::find($items['id']);
        } else {
            $page = new PageModel();
        }
        $slug = Str::slug($items['title'], '-');
        $page->title = $items['title'];
        $page->slug = $slug;
        $page->description = $items['description'];
        $page->content = htmlspecialchars_decode($items['content']);
        $page->status = $items['status'];
        $page->save();

        $file = new \Illuminate\Filesystem\Filesystem();
        $path = storage_path('app/views/pages/');
        if (!$file->isDirectory($path)) {
            $file->makeDirectory($path);
        }
        $file->put($path . '/' . $slug . '.blade.php', $page->content);
        return back();
    }
}