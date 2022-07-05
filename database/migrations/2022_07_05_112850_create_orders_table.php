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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_data_id')->nullable()->references('id')->on('order_customer_data')->onDelete('CASCADE');
            $table->integer('delivery_method_id')->nullable()->references('id')->on('delivery_methods')->onDelete('CASCADE');
            $table->timestamps();
            $table->string('delivery_method_name', 255)->nullable();
            $table->integer('delivery_method_cost')->nullable();
            $table->integer('cost');
            $table->string('note', 500)->nullable();
            $table->string('current_status', 255);
            $table->string('cancel_reason')->nullable();
        });

        Schema::create('order_customer_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->string('email', 255)->nullable();
            $table->string('phone', 255);
            $table->unsignedBigInteger('city_id')->references('id')->on('cities')->onDelete('cascade'); 
            $table->string('address', 255);
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id')->references('id')->on('orders')->onDelete('CASCADE');
            $table->integer('product_id')->references('id')->on('products');
            $table->string('product_name');
            $table->integer('product_price');
            $table->integer('product_quantity')->nullable();
            $table->integer('total_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('order_customer_data');
        Schema::dropIfExists('orders');
    }
};
