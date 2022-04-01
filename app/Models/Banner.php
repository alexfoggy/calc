<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{

    protected $table = 'banner';

    protected $fillable = [
        'banner_id', 'lang_id', 'name', 'body'
    ];

    public function bannerId()
    {
        return $this->hasOne('App\Models\BannerId', 'id', 'banner_id');
    }
}
