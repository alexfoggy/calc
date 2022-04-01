<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopsImages extends Model
{
    protected $table = 'shops_images';

    protected $fillable = [
        'shops_id', 'img', 'active', 'position'
    ];


}
