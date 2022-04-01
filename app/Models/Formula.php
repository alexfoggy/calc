<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Formula extends Model
{
    protected $table = 'formula';

    protected $fillable = [
        'formula_id','name','lang_id','dime','dime_text'
    ];

}
