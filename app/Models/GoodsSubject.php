<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class GoodsSubject extends Model
{
    protected $table = 'goods_subject';

    protected $fillable = [
        'goods_subject_id', 'lang_id', 'name', 'body', 'page_title', 'h1_title', 'meta_title', 'meta_keywords', 'meta_description'
    ];

    public function goodsSubjectId(){
        return $this->hasOne('App\Models\GoodsSubjectId', 'id', 'goods_subject_id');
    }

}

