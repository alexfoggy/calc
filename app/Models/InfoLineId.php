<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfoLineId extends Model
{
    protected $table = 'info_line_id';

    protected $fillable = [
        'alias', 'active', 'deleted', 'img_m_w', 'img_m_h', 'has_big_img', 'img_b_w', 'img_b_h', 'position'
    ];

    public static $globalLangId;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        self::$globalLangId = !is_null(request()->get('lang')) ? (int)request()->get('lang') : LANG_ID;
    }

    public function itemByLang()
    {
        return $this->hasOne('App\Models\InfoLine', 'info_line_id', 'id')->where('lang_id', self::$globalLangId);
    }

    public function infoItems(){
        return $this->hasmany('App\Models\InfoItemId', 'info_line_id', 'id');
    }
}
