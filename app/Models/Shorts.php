<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Shorts extends Model
{
    protected $table = 'shorts';

    protected $fillable = [
        'table_id', 'head_shorts', 'body_shorts'
    ];

//    public function getUser()
//    {
//        return $this->hasOne('App\Models\FrontUser', 'id', 'front_user_id');
//    }
//    public function getBody()
//    {
//        return $this->hasOne('App\Models\TableMain', 'table_id', 'id');
//    }

    /*public function moduleMultipleImg() {
        return $this->hasMany('App\Models\ShopsImages', 'shops_id', 'id')->orderBy('position', 'asc');
    }*/

}