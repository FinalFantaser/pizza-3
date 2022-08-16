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
        Schema::table('payment_records', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['method_id']);
        });

        Schema::table('payment_records', function (Blueprint $table) {
            $table->dropColumn('order_id');
            $table->dropColumn('method_id');
        });

        Schema::table('payment_records', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->after('id');
            $table->foreignId('method_id')->constrained('payment_methods')->cascadeOnDelete()->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_records', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['method_id']);
        });

        Schema::table('payment_records', function (Blueprint $table) {
            $table->dropColumn('order_id');
            $table->dropColumn('method_id');
        });

        Schema::table('payment_records', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained('orders')->after('id');
            $table->foreignId('method_id')->constrained('payment_methods')->after('order_id');
        });
    }
};
