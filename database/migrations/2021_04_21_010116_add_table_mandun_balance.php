<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableMandunBalance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mandub_balance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->foreignId('mandub_id')->constrained();
            $table->integer('commission')->nullable();
            $table->integer('net_profit')->nullable();
            $table->date('date')->nullable();
            $table->string('sender')->nullable();
            $table->string('location_from')->nullable();
            $table->string('location_to')->nullable();
            $table->integer('product_price')->nullable();
            $table->integer('paid')->nullable();
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
        Schema::dropIfExists('mandub_balance');
    }
}
