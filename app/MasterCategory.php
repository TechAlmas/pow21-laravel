<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterCategory extends Model
{
    protected $fillable = [
        'website_slug','parent_id', 'name', 'slug', 'url'
    ];

    protected $table = 'master_categories';
}