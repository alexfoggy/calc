<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerImages extends Model
{
    protected $table = 'banners_images';

    protected $fillable = [
        'banner_id', 'img', 'active', 'position'
    ];


}
