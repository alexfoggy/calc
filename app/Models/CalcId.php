<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalcId extends Model
{
    protected $table = 'calc_id';

    protected $fillable = [
        'calc_subject_id','active','deleted','formula','alias','type_calc'
    ];

    public static $globalLangId;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        self::$globalLangId = !is_null(request()->get('lang')) ? (int)request()->get('lang') : LANG_ID;
    }

//    public function menu() {
//        return $this->hasMany('App\Models\Menu', 'menu_id', 'id');
//    }
//
//    public function moduleMultipleImg() {
//        return $this->hasMany('App\Models\MenuImages', 'menu_id', 'id')->orderBy('position', 'asc');
//    }
//
//    public function oImage()
//    {
//        return $this->hasOne('App\Models\MenuImages', 'menu_id', 'id')->orderBy('position', 'asc');
//    }
//
//    public function children()
//    {
//        return $this->hasMany('App\Models\MenuId', 'p_id', 'id')->orderBy('position');
//    }

    public function itemByLang()
    {
        return $this->hasOne('App\Models\Calc', 'calc_id', 'id')->where('lang_id', self::$globalLangId);
    }
    public function parent()
    {
        return $this->hasOne('App\Models\CalcSubjectId', 'id', 'calc_subject_id');
    }

}
