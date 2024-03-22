<?php

namespace Blog\Http\Models\Post;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PostModel extends Model
{
    protected $table = 'blog_post';
    protected $fillable = ['views'];

    public $table_translation = "blog_post_translation";
    public $table_tag = "blog_post_translation";
    public $table_category = "blog_post_translation";

    public function table_translation()
    {
        return DB::table($this->table . "_translation");
    }

    public function table_category()
    {
        return DB::table($this->table . "_category");
    }

    public function table_tag()
    {
        return DB::table("tag_item");
    }

    public function getTag()
    {
        $lists = $this->table_tag()->join('tag','tag.id','=','tag_item.tag_id')->where("item_id", $this->id)->get(['name','slug']);
        $tag = [];
        foreach ($lists as $list) {
            $tag[$list->slug] = $list->name;
        }
        return $tag;
    }
    function getCategory(){
        $categorys = DB::table('blog_post_category')->where(['post_id'=>$this->id])->get();
        $arr = [];
        foreach ($categorys as $list) {
            $arr[$list->category_id] = 1;
        }
        return $arr;
    }
}