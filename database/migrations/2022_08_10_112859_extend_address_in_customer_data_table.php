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
            $table->dropColumn('email');
            $table->dropColumn('address');
        });

        Schema::table('order_customer_data', function (Blueprint $table) {
            $table->string('street', 255)->after('city_id');
            $table->string('house', 255)->nullable()->after('street');
            $table->string('room', 255)->nullable()->after('house');
            $table->string('entrance', 255)->nullable()->after('room');
            $table->string('intercom', 255)->nullable()->after('entrance');
            $table->string('floor', 255)->nullable()->after('intercom');
            $table->string('corp', 255)->nullable()->after('floor');
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
            $table->dropColumn('room');
            $table->dropColumn('entrance');
            $table->dropColumn('intercom');
            $table->dropColumn('floor');
            $table->dropColumn('corp');
        });

        Schema::table('order_customer_data', function (Blueprint $table) {
            $table->string('email', 255)->nullable()->after('name');
            $table->string('address', 255)->after('city_id');
        });
    }
};
