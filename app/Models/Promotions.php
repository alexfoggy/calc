<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotions extends Model
{

    protected $table = 'promotions';

    protected $fillable = [
        'promotions_id', 'lang_id', 'name'
    ];

    public function PromotionsId()
    {
        return $this->hasOne('App\Models\PromotionsId', 'id', 'promotions_id');
    }

}
