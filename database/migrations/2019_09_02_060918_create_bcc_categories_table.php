<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBccCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bcc_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('website_slug')->nullable();
            $table->integer('parent_id')->nullable();
            $table->text('name')->nullable();
            $table->text('slug')->nullable();
            $table->text('url')->nullable();
            $table->text('image')->nullable();
            $table->longText('description')->nullable();
            $table->longText('extra')->nullable();
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
        Schema::dropIfExists('bcc_categories');
    }
}
