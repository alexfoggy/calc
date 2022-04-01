<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class GoodsItemId extends Model
{
    protected $table = 'goods_item_id';

    protected $fillable = [
        'goods_subject_id', 'other_goods_subject_id', 'brand_id', 'alias', 'active', 'deleted', 'one_c_code', 'position', 'show_on_main', 'popular_element', 'price', 'price_old', 'in_stoc', 'model', 'youtube_link' ,'youtube_id','tech'
    ];

    static public $lang_id;

    public static $globalLangId;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        self::$globalLangId = !is_null(request()->get('lang')) ? (int)request()->get('lang') : LANG_ID;
    }

    public function oImage(){
        return $this->hasOne('App\Models\GoodsPhoto', 'goods_item_id', 'id')->orderBy('position', 'asc');
    }
    public function allImages(){
        return $this->hasMany('App\Models\GoodsPhoto', 'goods_item_id', 'id')->orderBy('position', 'asc');
    }

    public function getBannerId() {
        return $this->hasOne('App\Models\Brand', 'id', 'brand_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\GoodsSubjectId', 'goods_subject_id', 'id');
    }

	public function getBrand() {
		return $this->hasOne('App\Models\Brand', 'id', 'brand_id');
	}
    public function goodsItemReviews() {
        return $this->hasMany('App\Models\ReviewsGoods', 'goods_id', 'id');
    }

    public function oBrandImage(){
        return $this->hasOne('App\Models\BrandImages', 'brand_id', 'brand_id')->orderBy('position', 'asc');
    }

    public function goodsItem() {
        return $this->hasMany('App\Models\GoodsItem', 'goods_item_id', 'id');
    }

    public function getSubjectId() {
        return $this->hasOne('App\Models\GoodsSubjectId', 'id', 'goods_subject_id');
    }

	public function getGoodsItemColors() {
		return $this->hasOne('App\Models\GoodsItemColors', 'goods_item_id', 'id');
	}

    public function subjectByLang(){
        return $this->hasOne('App\Models\GoodsSubject', 'goods_subject_id', 'goods_subject_id')->where('lang_id', self::$globalLangId);
    }

    public function itemByLang(){
        return $this->hasOne('App\Models\GoodsItem', 'goods_item_id', 'id')->where('lang_id', self::$globalLangId);
    }

}

