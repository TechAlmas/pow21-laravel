<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductMeta extends Model
{
    protected $table 	= 'bcc_product_meta';
    protected $fillable = ['product_id','meta_key','meta_value'];
}
