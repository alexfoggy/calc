<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TrackNumber extends Model
{
    protected $table = 'track_number';

    protected $fillable = [
        'id', 'user_id', 'track_number', 'is_active'
    ];
}