<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Kyslik\ColumnSortable\Sortable;

class ProductNameProductType extends Model
{
	//use Sortable;
    protected $fillable = [
        'product_name_id', 'product_type_id'
    ];

    protected $table = 'product_name_product_type';
}