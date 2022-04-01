<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Wish extends Model
{
    protected $table = 'wish';

    protected $fillable = [
        'wish_id', 'goods_item_id','position'
    ];

	public function GoodsItemId() {
		return $this->hasOne('App\Models\GoodsItemId', 'id', 'goods_item_id');
	}

	public function GoodsPhoto() {
		return $this->hasOne('App\Models\GoodsPhoto', 'goods_item_id', 'goods_item_id')->orderBy('position','asc');
	}

}