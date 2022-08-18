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
        // Schema::table('orders', function (Blueprint $table) {
        //     $table->dropForeign(['delivery_method_id']);
        // });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('delivery_method_id');
            $table->dropColumn('delivery_method_name');
            $table->dropColumn('delivery_method_cost');

            $table->dropColumn('pickup_point_id');
            $table->dropColumn('pickup_point_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('delivery_method_id')->after('customer_data_id')->nullable()->references('id')->on('delivery_methods')->onDelete('CASCADE');
            $table->string('delivery_method_name', 255)->nullable()->after('delivery_method_id');
            $table->integer('delivery_method_cost')->nullable()->after('delivery_method_name');

            $table->unsignedBigInteger('pickup_point_id')->after('delivery_method_cost')->nullable();
            $table->string('pickup_point_address')->after('pickup_point_id')->nullable();
        });
    }
};
