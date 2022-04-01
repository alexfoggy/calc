<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalcSubject extends Model
{
    protected $table = 'calc_subject';

    protected $fillable = [
        'calc_subject_id','lang_id','name','body','meta_title','meta_keywords','meta_description'
    ];

}
