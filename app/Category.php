<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Kyslik\ColumnSortable\Sortable;

class Category extends Model
{
	//use Sortable;
    protected $fillable = [
        'parent_id', 'name', 'slug', 'url', 'image', 'description', 'extra'
    ];

    protected $table = 'bcc_categories';
    public $sortable = ['id','name'];
	
	public function parent()
    {
        return $this->hasOne('App\Category','id','parent_id');
    }
}