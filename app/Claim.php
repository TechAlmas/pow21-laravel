<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $table = "claim_listings";

    protected $fillable = ['first_name','last_name','telephone','e_mail','verification_details','files'];
}
