<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WeedmapsDispensary extends Model
{
    protected $fillable = [
        'name'
    ];

    protected $table = 'weedmaps_dispensaries';
}