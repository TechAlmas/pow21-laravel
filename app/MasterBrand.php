<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterBrand extends Model
{
	//use Sortable;
    protected $fillable = [
        'name', 'source'
    ];

    protected $table = 'master_brands';
}