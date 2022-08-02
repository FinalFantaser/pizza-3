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
        Schema::table('products_recommended', function (Blueprint $table) {
            $table->unique('product_id');
            $table->unique('sort');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products_recommended', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropUnique(['product_id']);
            $table->dropUnique(['sort']);
        });

        Schema::table('products_recommended', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });
    }
};
