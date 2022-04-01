<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class GoodsMeasureId extends Model
{
    protected $table = 'goods_measure_id';

    protected $fillable = [
        'active', 'position'
    ];

}

