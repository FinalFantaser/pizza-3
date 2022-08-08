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
        Schema::create('payment_records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained('orders');
            $table->foreignId('method_id')->constrained('payment_methods');
            $table->string('title');
            // $table->boolean('status')->default(false);
            $table->unsignedInteger('sum'); //Сумма платежа
            $table->unsignedInteger('change_sum')->default(0); //Сумма, с которой нужно дать сдачу
            $table->string('payer'); //Плательщик (клиент или администратор)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_records');
    }
};
