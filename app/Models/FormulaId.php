<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class FormulaId extends Model
{
    protected $table = 'formula_id';

    protected $fillable = [
        'formula','calc_id','p_id'
    ];


    static public $lang_id;

    public static $globalLangId;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        self::$globalLangId = !is_null(request()->get('lang')) ? (int)request()->get('lang') : LANG_ID;
    }

    public function itemByLang()
    {
        return $this->hasOne('App\Models\Formula', 'formula_id', 'id')->where('lang_id', self::$globalLangId);
    }

}
