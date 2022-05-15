<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterPriceHistory extends Model
{
	//use Sortable;
    protected $fillable = [
        'master_prices_id','menu_id', 'strain_id','city_id','state_id','location_id','mass_id','price'
    ];

    protected $table = 'master_prices_history';
	
	//public $timestamps = false;
}