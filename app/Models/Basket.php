<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    protected $table = 'basket';

	protected $fillable = [

		'basket_id','alias_item', 'goods_item_id', 'items_count', 'goods_name', 'goods_price', 'goods_one_c_code', 'goods_model',
	];

	public function basketId()
	{
		return $this->hasOne('App\Models\BasketId', 'id', 'basket_id');
	}

	public function goodsItemId()
	{
		return $this->hasOne('App\Models\GoodsItemId', 'id', 'goods_item_id');
	}

	public function goodsItem()
	{
		return $this->hasOne('App\Models\GoodsItem', 'goods_item_id', 'goods_item_id');
	}

	public function oImage() {
		return $this->hasOne('App\Models\GoodsPhoto', 'goods_item_id', 'goods_item_id')->where('active', '1')->orderBy('position', 'asc');
	}

}
