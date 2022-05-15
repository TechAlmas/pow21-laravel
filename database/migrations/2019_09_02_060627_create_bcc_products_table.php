<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBccProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bcc_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cat_id')->nullable();
            $table->string('cat_slug')->nullable();
            $table->string('category')->nullable();
            $table->string('website_slug')->nullable();
            $table->text('product_title')->nullable();
            $table->text('slug')->nullable();
            $table->text('url')->nullable();
            $table->string('image')->nullable();
            $table->longText('product_description')->nullable();
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
        Schema::dropIfExists('bcc_products');
    }
}
