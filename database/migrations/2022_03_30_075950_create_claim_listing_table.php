<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimListingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_listing', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('listing_id');
            $table->string('first_name',100);
            $table->string('last_name',100);
            $table->string('telephone',20);
            $table->string('e_mail',100)->unique();
            $table->longText('verification_details')->nullable();
            $table->string('files');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claim_listing');
    }
}
