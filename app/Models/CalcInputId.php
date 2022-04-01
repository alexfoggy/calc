<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalcInputId extends Model
{
  protected $table = 'calc_input_row_id';

  protected $fillable = [
    'calc_id','variable'
  ];

  public static $globalLangId;

  public function __construct($attributes = [])
  {
    parent::__construct($attributes);

    self::$globalLangId = !is_null(request()->get('lang')) ? (int)request()->get('lang') : LANG_ID;
  }


  public function itemByLang()
  {
      return $this->hasOne('App\Models\CalcInput', 'calc_input_row_id', 'id')->where('lang_id', self::$globalLangId);
  }

}
