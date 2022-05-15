<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterPrice extends Model
{
	//use Sortable;
    protected $fillable = [
        'menu_id', 'strain_id','city_id','state_id','location_id','mass_id','price'
    ];

    protected $table = 'master_prices';
	
	//public $timestamps = false;
}