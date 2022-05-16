<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableClaimListingsChangeStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('claim_listings', function (Blueprint $table) {
            $table->enum('status', ['Verified', 'Unverified', 'Pending'])->default('Pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('claim_listings', function (Blueprint $table) {
            $table->tinyInteger('status')->length(3)->change();
        });
    }
}
