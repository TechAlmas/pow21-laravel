<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterBrandsStrain extends Model
{
	//use Sortable;
    protected $fillable = [
        'brand_id', 'menu_id','strain_id','location_id'
    ];

    protected $table = 'master_brands_strains';
	public $timestamps = false;
	public function brand()
    {
        return $this->hasOne('App\MasterBrand','id','brand_id');
    }
}