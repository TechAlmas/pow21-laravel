<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebsiteMeta extends Model
{
	protected $table = 'website_meta';
    protected $fillable = [
        'website_id', 'meta_key', 'meta_value'
    ];
}
