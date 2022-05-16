<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DispensoryUser extends Model
{
    protected $table = "dispensaries_users";
    public $timestamps = false;
    protected $fillable = ['user_id','dispansary_id'];
}
