<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class FeedForm extends Model
{
    protected $table = 'feedform';

    protected $fillable = [
        'name','ip', 'active', 'comment', 'phone', 'subject', 'agree', 'active', 'seen', 'email'
    ];

}
