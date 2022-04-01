<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class GoodsParametr extends Model
{
    protected $table = 'goods_parametr';

    protected $fillable = [
        'goods_parametr_id', 'lang_id', 'name', 'body'
    ];

    public function parametrId()
    {
        return $this->hasOne('App\Models\GoodsParametrId', 'id', 'goods_parametr_id');
    }

}


