<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterLocationStrain extends Model
{
	//use Sortable;
    protected $fillable = [
        'strain_id', 'location_id','name','thc','cbd','category_name','image_url'
    ];

    protected $table = 'master_locations_strains';
	
	public $timestamps = false;
}