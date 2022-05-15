<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterMass extends Model
{
	//use Sortable;
    protected $fillable = [
        'name','keywords'
    ];

    protected $table = 'master_mass';
	
	public $timestamps = false;
}