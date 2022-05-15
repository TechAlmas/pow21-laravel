<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterProductName extends Model
{
    protected $fillable = [
        'name','source','product_brand'
    ];

    protected $table = 'master_product_names';
	
	public function product(){
		return $this->hasOne('App\Product','id','product_id');
	}
}