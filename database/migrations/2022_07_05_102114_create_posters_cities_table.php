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
        Schema::create('posters_cities', function (Blueprint $table) {
            $table->unsignedBigInteger('poster_id');
            $table->foreign('poster_id')->references('id')->on('posters')->onDelete('cascade');
            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posters_cities', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropForeign(['poster_id']);
        });

        Schema::dropIfExists('posters_cities');
    }
};
