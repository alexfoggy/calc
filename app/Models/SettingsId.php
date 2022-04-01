<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class SettingsId extends Model
{
    protected $table = 'settings_id';

    protected $fillable = [
        'alias', 'set_type'
    ];
    public static $globalLangId;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        self::$globalLangId = !is_null(request()->get('lang')) ? (int)request()->get('lang') : LANG_ID;
    }

    public function itemByLang()
    {
        return $this->hasOne('App\Models\Settings', 'settings_id', 'id')->where('lang_id', self::$globalLangId);
    }

}
