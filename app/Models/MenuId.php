<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuId extends Model
{
    protected $table = 'menu_id';

    protected $fillable = [
        'p_id', 'level', 'alias', 'page_type', 'position', 'active', 'deleted', 'img', 'top_menu', 'footer_menu','related_works'
    ];

    public static $globalLangId;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        self::$globalLangId = !is_null(request()->get('lang')) ? (int)request()->get('lang') : LANG_ID;
    }

    public function menu() {
        return $this->hasMany('App\Models\Menu', 'menu_id', 'id');
    }

    public function moduleMultipleImg() {
        return $this->hasMany('App\Models\MenuImages', 'menu_id', 'id')->orderBy('position', 'asc');
    }

    public function oImage()
    {
        return $this->hasOne('App\Models\MenuImages', 'menu_id', 'id')->orderBy('position', 'asc');
    }

    public function children()
    {
        return $this->hasMany('App\Models\MenuId', 'p_id', 'id')->orderBy('position');
    }

    public function itemByLang()
    {
        return $this->hasOne('App\Models\Menu', 'menu_id', 'id')->where('lang_id', self::$globalLangId);
    }
}
