<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CityId extends Model
{
    protected $table = 'city_id';

    protected $fillable = [
        'alias', 'active', 'position'
    ];

}