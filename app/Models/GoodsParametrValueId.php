<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class GoodsParametrValueId extends Model
{
    protected $table = 'goods_parametr_value_id';

    protected $fillable = [
        'goods_parametr_id', 'position', 'active'
    ];

    public static $globalLangId;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        self::$globalLangId = !is_null(request()->get('lang')) ? (int)request()->get('lang') : LANG_ID;
    }

    public function itemByLang(){
        return $this->hasOne('App\Models\GoodsParametrValue', 'goods_parametr_value_id', 'id')->where('lang_id', self::$globalLangId);
    }

	public function parametrValue()
	{
		return $this->hasOne('App\Models\GoodsParametrValue', 'goods_parametr_value_id', 'id');
	}

}