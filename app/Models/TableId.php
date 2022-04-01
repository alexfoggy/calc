<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TableId extends Model
{
    protected $table = 'table_id';

    protected $fillable = [
        'alias', 'active', 'front_user_id', 'agreed','key_detect','privat','privat_pass'
    ];

    public function getUser()
    {
        return $this->hasOne('App\Models\FrontUser', 'id', 'front_user_id');
    }
public function getBody()
    {
        return $this->hasOne('App\Models\TableMain', 'table_id', 'id');
    }
public function shorts()
    {
        return $this->hasMany('App\Models\Shorts', 'table_id', 'id');
    }

    /*public function moduleMultipleImg() {
        return $this->hasMany('App\Models\ShopsImages', 'shops_id', 'id')->orderBy('position', 'asc');
    }*/

}