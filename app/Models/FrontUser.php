<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class FrontUser extends Authenticatable
{
    protected $table = 'front_user';

    protected $fillable = [
       'first_name', 'userName','last_name', 'email', 'password','active', 'remember_token', 'gift_card', 'recovery_hash', 'phone', 'google_id', 'facebook_id', 'discount'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
