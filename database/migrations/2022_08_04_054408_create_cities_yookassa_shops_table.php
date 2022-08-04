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
        Schema::create('cities_yookassa_shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained('cities');
            $table->foreignId('shop_id')->constrained('yookassa_shops');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cities_yookassa_shops', function(Blueprint $table){
            $table->dropForeign(['city_id']);
            $table->dropForeign(['shop_id']);
        });

        Schema::dropIfExists('cities_yookassa_shops');
    }
};
