<?php

namespace Blog\Http\Controllers;

use Blog\Http\Requests\FormDataPostRequest;
use  \Blog\Http\Models\Post\PostModel;
use  \Blog\Http\Models\Post\PostTranslationModel;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class PostController extends \Zoe\Http\ControllerBackend
{

    public function init()
    {
        $this->data['language'] = config('zoe.language');
        $this->data['nestables'] = config_get("category", "blog:category");
        $this->data['configs'] = config_get("config", "blog");
        $this->data['current_language'] = isset($this->data['configs']['post']['language']['default']) ? $this->data['configs']['post']['language']['default'] : "en";
    }

    public function getCrumb()
    {
        $this->breadcrumb("Post", route('backend:blog:post:list'));
        return $this;
    }

    public function list(Request $request)
    {

        $search = $request->query('search', "");
        $status = $request->query('status', "");

        $config = config_get('option', "core:user:list");
        $data = $request->query();

        $page = null;
        if (isset($data['action'])) {
            $page = 1;
        }
        $parameter = $data;

        $route = [];

        $item = isset($config['pagination']['item']) ? $config['pagination']['item'] : 20;
        $item = 1;
        $select = ['post.title', 'post.id', 'post.image', 'post.status', 'post.views', 'post.created_at', 'post.updated_at'];
        $models = DB::table('blog_post as post');

        if (isset($search) && !empty($search) || isset($parameter["filter"]['name']) && !empty($parameter['filter']['name']) && $search = $parameter['filter']['name']) {

            $models->where('t.title', 'like', '%' . $search . '%');
            $select['t.title'] = 't.title';
        }
        if (isset($parameter["filter"]['type']) && !empty($parameter['filter']['type'])) {
            $models->where('type', $parameter['filter']['type']);
        }
        if (!empty($status) || $status != "") {
            $models->where('status', $status);
        }
        if (!isset($parameter['order_by'])) {
            $parameter['order_by']['col'] = 'id';
            $parameter['order_by']['type'] = 'desc';

        } else {
            if (isset($parameter['action'])) {
                $parameter['order_by']['type'] = isset($parameter['order_by']['type']) && $parameter['order_by']['type'] == "desc" ? "asc" : "desc";
            }

        }
        if (isset($parameter['action'])) {
            unset($parameter['action']);
        }
        $lang = $this->data['current_language'];

        if ($parameter['order_by']['col'] == "title") {
            if (!isset($select['t.title'])) {
                $select['t.title'] = 't.title';
            }
            $models->orderBy("t." . $parameter['order_by']['col'], $parameter['order_by']['type']);
        } else {
            $models->orderBy($parameter['order_by']['col'], $parameter['order_by']['type']);
        }
        // $models->join('blog_post_translation as t', 't.post_id', '=', 'post.id');
        // $models->where('t.lang_code', $lang);

        $models->select($select);
        $models = $models->paginate($item, ['*'], 'page', $page);
        $models->appends($parameter);

        return $this->render('post.list', [
            'models' => $models,
            "route" => $route,
            'parameter' => $parameter,
            'callback' => [
//                "GetTitle" => function ($model) use ($lang) {
//                    $rs = DB::table('blog_post_translation as t')->where('post_id', $model->id)->where('lang_code', $lang)->get('title');
//                    return $rs && count($rs) > 0 ? $rs[0]->title : "Empty";
//                }
            ],
        ]);

    }

    public function create()
    {
        $this->getCrumb()->breadcrumb('Post Create', route('backend:blog:post:create'));
        return $this->render('post.create', ['item' => []], 'blog');
    }

    public function edit($id)
    {

        $this->getCrumb()->breadcrumb('Post Edit', route('backend:blog:post:create'));
        $item = PostModel::find($id);
        if (isset($this->data['configs']['post']['language']['multiple'])) {
            $trans = PostTranslationModel::where(['post_id' => $id])->get();

            foreach ($trans as $tran) {
                $item->offsetSet("title_" . $tran->lang_code, $tran->title);
                $item->offsetSet("description_" . $tran->lang_code, $tran->description);
                $item->offsetSet("content_" . $tran->lang_code, $tran->content);
            }
        }
        $item->offsetSet("tag", implode($item->getTag(), ','));
        $item->offsetSet("category", $item->getCategory());

        return $this->render('post.edit', ["item" => $item, "lang_active" => $this->data['current_language']], 'blog');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'image' => 'required',
            'category' => 'required',
        ], [
            'image.required' => 'The Image is Required.',
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        if (isset($data['id']) && !empty($data['id'])) {
            $model = PostModel::find($data['id']);
        } else {
            $model = new PostModel();
        }
        $model->slug = "demo";
        $model->image = $data['image'];
        $model->status = $data['status'];
        $model->user_id = 1;
        $model->featured = $data['featured'];
        $model->title = $data['title'];
        $model->description = $data['description'];
        $model->content = $data['content'];


        DB::beginTransaction();


        try {
            $model->save();

            foreach ($this->data['language'] as $lang => $_language) {
                if (isset($data['title_' . $lang])) {
                    $model->table_translation()->updateOrInsert(
                        [
                            'post_id' => $model->id,
                            'lang_code' => $lang
                        ],
                        [
                            'title' => $data['title_' . $lang],
                            'description' => $data['description_' . $lang],
                            'content' => $data['content_' . $lang]
                        ]
                    );
                }
            }
            \Actions::do_action("tag_add", "blog:post", $model->id, $data['tag'], $model->getTag());
            $category_old = $model->getCategory();
            if (isset($data['category'])) {
                foreach ($data['category'] as $cate) {
                    $model->table_category()->updateOrInsert(
                        [
                            'post_id' => $model->id,
                            'category_id' => $cate,
                        ],
                        [

                        ]
                    );
                    unset($category_old[$cate]);
                }
                foreach ($category_old as $cate => $value) {
                    $model->table_category()->where(['category_id' => $cate, 'post_id' => $model->id])->delete();
                }
            }
            DB::commit();
            return redirect(route('backend:blog:post:edit', ['id' => $model->id]));
        } catch (\Exception $ex) {
            $validator->getMessageBag()->add('id', $ex->getMessage());
            DB::rollBack();
        }
        return back();
    }

    public function delete()
    {

    }
}