<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeafyDispensary extends Model
{
    protected $fillable = [
       'name'
    ];

    protected $table = 'leafy_dispensaries';
}