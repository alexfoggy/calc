<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class GoodsParametrItemMeasure extends Model
{
    protected $table = 'goods_parametr_item_measure';

    protected $fillable = [
        'goods_parametr_item_id', 'goods_measure_id', 'parametr_value'
    ];

}