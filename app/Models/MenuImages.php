<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuImages extends Model
{
    protected $table = 'menu_images';

    protected $fillable = [
        'menu_id', 'img', 'active', 'position'
    ];


}
