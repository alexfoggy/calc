<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class LabelsId extends Model
{
    protected $table = 'labels_id';

    protected $fillable = [
        'id'
    ];

    public static $globalLangId;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        self::$globalLangId = !is_null(request()->get('lang')) ? (int)request()->get('lang') : LANG_ID;
    }
    public function itemByLang()
    {
        return $this->hasOne('App\Models\Labels', 'labels_id', 'id')->where('lang_id', self::$globalLangId);
    }
}
