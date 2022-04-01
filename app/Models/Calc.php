<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calc extends Model
{
    protected $table = 'calc';

    protected $fillable = [
        'calc_id','lang_id','name','body','meta_title','meta_keywords','meta_description'
    ];



}
