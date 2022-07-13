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
        Schema::create('option_records', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('option_id')->constrained('options')->cascadeOnDelete();
            $table->json('items');

            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('option_records', function (Blueprint $table) {
            $table->dropForeign(['option_id']);
        });

        Schema::dropIfExists('option_records');
    }
};
