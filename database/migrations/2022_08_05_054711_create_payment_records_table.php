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
        Schema::create('yookassa_payment_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->references('id')->on('orders');
            $table->foreignId('shop_id')->constrained('yookassa_shops');
            $table->boolean('response_received')->default(false); //Указывает, был ли получен ответ от ЮKassa, позволяющий заполнить данные
            $table->string('idempotence_key'); //Ключ идемпотентности, который генерирует приложение (во избежание повторения платежа)
            $table->string('payment_id')->nullable(); //Идентификатор платежа, который присваивает ЮKassa
            $table->string('status')->nullable();
            $table->boolean('paid')->default(false);
            $table->unsignedInteger('amount')->nullable(); //Сумма платежа вместе с коммисией
            $table->json('cancellation_details')->nullable();
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
        Schema::dropIfExists('yookassa_payment_records');
    }
};
