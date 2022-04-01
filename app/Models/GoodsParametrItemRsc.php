<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class GoodsParametrItemRsc extends Model
{
    protected $table = 'goods_parametr_item_rsc';

    protected $fillable = [
        'goods_parametr_item_id', 'goods_parametr_value_id'
    ];

}

