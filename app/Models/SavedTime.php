<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedTime extends Model
{
    protected $table = 'saved_time';

    protected $fillable = [
        'front_user_id', 'time_row'
    ];

}
