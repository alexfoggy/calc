<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ShopsId extends Model
{
    protected $table = 'shops_id';

    protected $fillable = [
        'alias', 'active', 'phone', 'city_id', 'latitude', 'longitude', 'img'
    ];

    public function cityId()
    {
        return $this->belongsTo('App\Models\City', 'city_id', 'city_id');
    }

    /*public function moduleMultipleImg() {
        return $this->hasMany('App\Models\ShopsImages', 'shops_id', 'id')->orderBy('position', 'asc');
    }*/

}