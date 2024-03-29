<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_recommended', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->unsignedInteger('sort')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_recommended');
    }
};
