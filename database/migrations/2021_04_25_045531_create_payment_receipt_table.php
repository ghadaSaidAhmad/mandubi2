<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentReceiptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_receipt', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->string('title')->nullable();
            $table->string('receipt_image')->nullable();
            $table->integer('mandub_id')->nullable();
            $table->integer('payment_method_id')->nullable();

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
        Schema::dropIfExists('payment_receipt');
    }
}
