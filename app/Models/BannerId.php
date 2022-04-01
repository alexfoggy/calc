<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerId extends Model
{

    protected $table = 'banner_id';

    protected $fillable = [
        'img', 'link', 'active', 'deleted'
    ];

    public static $globalLangId;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        self::$globalLangId = !is_null(request()->get('lang')) ? (int)request()->get('lang') : LANG_ID;
    }

    public function moduleMultipleImg() {
        return $this->hasMany('App\Models\BannerImages', 'banner_id', 'id')->orderBy('position', 'asc');
    }

    public function itemByLang()
    {
        return $this->hasOne('App\Models\Banner', 'banner_id', 'id')->where('lang_id', self::$globalLangId);
    }

}
