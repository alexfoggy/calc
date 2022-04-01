<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewsGoods extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'front_user_id', 'goods_id', 'body' , 'rating' , 'active'
    ];


    public function userInfo()
    {
        return $this->hasOne('App\Models\FrontUser', 'id', 'front_user_id');
    }
}
