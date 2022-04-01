<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Compare extends Model
{
    protected $table = 'compare';

    protected $fillable = [
        'compare_id', 'goods_item_id','goods_subject_id','position'
    ];

	public function goodsItemId() {
		return $this->hasOne('App\Models\GoodsItemId', 'id', 'goods_item_id');
	}

	public function oImage() {
		return $this->hasOne('App\Models\GoodsPhoto', 'goods_item_id', 'goods_item_id')->orderBy('position','asc');
	}

	public function rating() {
		return $this->hasOne('App\Models\ReviewsGoods', 'goods_item_id', 'goods_id');
	}

}