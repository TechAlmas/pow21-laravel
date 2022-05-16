<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Facades\DB;

class UpdateClaimListingsTable extends Migration
{
    public function __construct()
    {
        if (! Type::hasType('enum')) {
            Type::addType('enum', StringType::class);
        }
        // For point types
        // DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('point', 'string');
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }
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
