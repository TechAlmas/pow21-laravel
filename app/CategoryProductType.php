<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Kyslik\ColumnSortable\Sortable;

class CategoryProductType extends Model
{
	//use Sortable;
    protected $fillable = [
        'cat_id', 'product_type_id'
    ];

    protected $table = 'category_product_types';
}