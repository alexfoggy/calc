<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerTopId extends Model
{

    protected $table = 'banner_top_id';

    protected $fillable = [
        'position', 'active', 'deleted'
    ];

    public static $globalLangId;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        self::$globalLangId = !is_null(request()->get('lang')) ? (int)request()->get('lang') : LANG_ID;
    }

    public function bannerTop()
    {
        return $this->hasOne('App\Models\BannerTop', 'banner_top_id', 'id');
    }

    public function itemByLang()
    {
        return $this->hasOne('App\Models\BannerTop', 'banner_top_id', 'id')->where('lang_id', self::$globalLangId);
    }
}
