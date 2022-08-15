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
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained('cities')->cascadeOnDelete();
            $table->string('restaurant_id');
            $table->string('code');
            $table->string('name');
            $table->unsignedInteger('sum_min')->default(0);
            $table->unsignedInteger('time_min')->default(0);
            $table->unsignedInteger('time_max')->default(0);
            $table->unsignedInteger('delivery_price')->default(0);
            $table->unsignedInteger('sum_for_free')->default(0);
            $table->json('coordinates');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_zones');
    }
};
