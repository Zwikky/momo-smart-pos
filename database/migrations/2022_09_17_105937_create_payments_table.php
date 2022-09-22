<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payer');
            $table->unsignedBigInteger('card');
            $table->decimal('amount', 5, 2);
            $table->boolean('success');
            $table->unsignedBigInteger('receiver');
            $table->timestamps();

            $table->foreign('payer')->references('id')->on('payers');
            $table->foreign('receiver')->references('id')->on('merchants');
            $table->foreign('card')->references('id')->on('cards');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}