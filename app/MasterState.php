<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterState extends Model
{
	//use Sortable;
    protected $fillable = [
        'state', 'state_code','country_code'
    ];

    protected $table = 'master_states';
}