<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class WishId extends Model
{
    protected $table = 'wish_id';

    protected $fillable = [
        'user_ip'
    ];

}