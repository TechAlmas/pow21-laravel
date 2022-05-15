<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterLocation extends Model
{
    protected $fillable = [
        'name',
        'address',
        'address2',
        'city',
        'state',
        'zip_code',
        'country',
        'website',
        'email',
        'email2',
        'phone',
        'source',
        'city_id',
        'state_id',
        'logoUrl',
        'description',
        'schedule',
        'license_type',
        'slug',
    ];

    protected $table = 'master_locations';
}