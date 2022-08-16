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
        Schema::table('order_customer_data', function (Blueprint $table) {
            $table->dropColumn('street');
            $table->dropColumn('house');
        });

        Schema::table('order_customer_data', function (Blueprint $table) {
            $table->string('street', 255)->after('city_id')->nullable();
            $table->string('house', 255)->nullable()->after('street')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_customer_data', function (Blueprint $table) {
            $table->dropColumn('street');
            $table->dropColumn('house');
        });

        Schema::table('order_customer_data', function (Blueprint $table) {
            $table->string('street', 255)->after('city_id');
            $table->string('house', 255)->nullable()->after('street');
        });
    }
};
