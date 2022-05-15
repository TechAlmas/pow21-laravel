<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $fillable = [
        'website_name', 'slug', 'website_url', 'extra'
    ];
}
