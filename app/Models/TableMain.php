<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TableMain extends Model
{
    protected $table = 'table_main';

    protected $fillable = [
        'body', 'table_id', 'city', 'group_name','class'
    ];

//    public function cityIds()
//    {
//        return $this->hasOne('App\Models\TableMain', 'table_id', 'id');
//    }

    /*public function moduleMultipleImg() {
        return $this->hasMany('App\Models\ShopsImages', 'shops_id', 'id')->orderBy('position', 'asc');
    }*/

}