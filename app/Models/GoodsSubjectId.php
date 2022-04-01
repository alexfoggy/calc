<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsSubjectId extends Model
{
    protected $table = 'goods_subject_id';

    protected $fillable = [
        'p_id', 'alias', 'active', 'deleted', 'level', 'position', 'img', 'one_c_code', 'menurow', 'img', 'oldid'
    ];

    public static $globalLangId;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        self::$globalLangId = !is_null(request()->get('lang')) ? (int)request()->get('lang') : LANG_ID;
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\GoodsSubjectId', 'p_id', 'id');
    }

    public function children()
    {
        return $this->hasMany('App\Models\GoodsSubjectId', 'p_id', 'id');
    }

    public function itemByLang()
    {
        return $this->hasOne('App\Models\GoodsSubject', 'goods_subject_id', 'id')->where('lang_id', self::$globalLangId);
    }

    public function goodsItemId(){
        return $this->hasMany('App\Models\GoodsItemId', 'goods_subject_id', 'id');
    }

	public function goodsSubject(){
		return $this->hasOne('App\Models\GoodsSubject', 'goods_subject_id', 'id');
	}

    public function moduleMultipleImg() {
        return $this->hasMany('App\Models\GoodsImages', 'goods_subject_id', 'id')->orderBy('position', 'asc');
    }

}