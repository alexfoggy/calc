<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BasketId extends Model
{

    protected $table = 'basket_id';

    protected $fillable = [
        'user_ip',
    ];

	public function basket() {
		return $this->hasMany('App\Models\Basket', 'basket_id', 'id');
	}
}
