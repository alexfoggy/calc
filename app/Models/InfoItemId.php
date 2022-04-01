<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfoItemId extends Model
{
    protected $table = 'info_item_id';

    protected $fillable = [
        'id', 'info_line_id', 'alias', 'is_public', 'active', 'deleted', 'img', 'show_img', 'add_date', 'category', 'pdffile'
    ];
    public static $globalLangId;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        self::$globalLangId = !is_null(request()->get('lang')) ? (int)request()->get('lang') : LANG_ID;
    }

    public function itemByLang()
    {
        return $this->hasOne('App\Models\InfoItem', 'info_item_id', 'id')->where('lang_id', self::$globalLangId);
    }

    public function infoLineId()
    {
        return $this->hasMany('App\Models\InfoLineId', 'id', 'info_line_id');
    }

    public function infoItem()
    {
        return $this->hasMany('App\Models\InfoItem', 'info_item_id', 'id');
    }

    public function moduleMultipleImg() {
        return $this->hasMany('App\Models\InfoLineImages', 'info_item_id', 'id')->orderBy('position', 'asc');
    }

    public function oImage() {
        return $this->hasOne('App\Models\InfoLineImages', 'info_item_id', 'id')->orderBy('position', 'asc');
    }
}
