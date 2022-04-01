<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Tech extends Model
{
    protected $table = 'tech_id';

    protected $fillable = [
        'name','info_tech'
    ];

//    public function cityIds()
//    {
//        return $this->hasOne('App\Models\TableMain', 'table_id', 'id');
//    }

    /*public function moduleMultipleImg() {
        return $this->hasMany('App\Models\ShopsImages', 'shops_id', 'id')->orderBy('position', 'asc');
    }*/

}