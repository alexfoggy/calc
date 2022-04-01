<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class GoodsImages extends Model
{
    protected $table = 'goods_images';

    protected $fillable = [
        'goods_subject_id', 'img', 'active', 'position'
    ];

}

