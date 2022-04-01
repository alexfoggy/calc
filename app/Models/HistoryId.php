<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryId extends Model
{
    protected $table = 'history_id';

    protected $fillable = [
       'front_user_id'
    ];

    public function Data()
    {
        return $this->hasOne('App\Models\History', 'id', 'history_id');
    }
}