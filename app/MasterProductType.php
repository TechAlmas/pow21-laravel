<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterProductType extends Model
{
    protected $fillable = [
        'name','source','product_id'
    ];

    protected $table = 'master_product_types';
}