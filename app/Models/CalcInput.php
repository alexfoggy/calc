<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalcInput extends Model
{
  protected $table = 'calc_input_row';

  protected $fillable = [
    'lang_id','name','calc_input_row_id','before_text','after_text'
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

}
