<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Kyslik\ColumnSortable\Sortable;


class Product extends Model
{
	//use Sortable;
    protected $fillable = [
        'cat_id','cat_slug','category','website_slug','product_title','slug','url', 'image', 'product_description'
    ];

    protected $table = 'bcc_products';
    //public $sortable = ['id','product_title'];
}
