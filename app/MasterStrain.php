<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterStrain extends Model
{
	//use Sortable;
    protected $fillable = [
        'name', 'category','reviews','ratings','source','description','our_description','slug','image'
    ];

    protected $table = 'master_strains';
}