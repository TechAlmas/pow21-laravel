<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterCity extends Model
{
    protected $fillable = [
        'city', 'state'
    ];

    protected $table = 'master_cities';
}