<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class BrandId extends Model
{
    protected $table = 'goods_brand_id';

    protected $fillable = [
        'img', 'alias', 'active', 'deleted', 'link', 'position'
    ];

    public static $globalLangId;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        self::$globalLangId = !is_null(request()->get('lang')) ? (int)request()->get('lang') : LANG_ID;
    }
    public function itemByLang()
    {
        return $this->hasOne('App\Models\Brand', 'goods_brand_id', 'id')->where('lang_id', self::$globalLangId);
    }

    public function goodsItemId() {
        return $this->hasOne('App\Models\GoodsItemId', 'brand_id', 'id');
    }

    public function moduleMultipleImg() {
        return $this->hasMany('App\Models\BrandImages', 'goods_brand_id', 'id')->orderBy('position', 'asc');
    }
	public function oImage() {
		return $this->hasOne('App\Models\BrandImages', 'goods_brand_id', 'id')->orderBy('position', 'asc');
	}

}