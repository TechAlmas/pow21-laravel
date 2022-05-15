<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterCountry extends Model
{
	//use Sortable;
    protected $fillable = [
        'country_code', 'country_name'
    ];

    protected $table = 'master_countries';
}