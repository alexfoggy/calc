<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalcSubjectId extends Model
{
    protected $table = 'calc_subject_id';

    protected $fillable = [
        'active','deleted','alias'
    ];

    public static $globalLangId;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        self::$globalLangId = !is_null(request()->get('lang')) ? (int)request()->get('lang') : LANG_ID;
    }


    public function itemByLang()
    {
        return $this->hasOne('App\Models\CalcSubject', 'calc_subject_id', 'id')->where('lang_id', self::$globalLangId);
    }
    public function children()
    {
        return $this->hasMany('App\Models\CalcId', 'calc_subject_id', 'id')/*->where('lang_id', self::$globalLangId)*/;
    }

}
