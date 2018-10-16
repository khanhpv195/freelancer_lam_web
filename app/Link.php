<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $table = 'links';
    protected $fillable = [
        'title',
        'content',
        'price',
        'user_id',
        'active'
    ];
}
