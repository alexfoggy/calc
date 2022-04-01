<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'history';

    protected $fillable = [
        'history_row_inputs','result','history_id'
    ];

    /*    public function infoItemId()
        {
            return $this->hasOne('App\Models\InfoItemId', 'id', 'info_item_id');
        }*/
}