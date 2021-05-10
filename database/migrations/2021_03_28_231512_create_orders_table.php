<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->foreignId('mandub_id')->nullable()->constrained();
            $table->foreignId('shipping_type_id')->constrained();
            $table->integer('mandub_gender')->nullable();; // 1 male 2 fmale // 1 null
            $table->integer('payment_type')->nullable();
            $table->string('description')->nullable();
            $table->integer('order_weight')->nullable();
            $table->integer('order_count')->nullable();
            $table->string('extra')->nullable();

            $table->string('from_lang');
            $table->string('from_lat');
            $table->string('from_title');

            $table->string('to_lang');
            $table->string('to_lat');
            $table->string('to_title');
            $table->integer('price')->nullable();
            $table->integer('product_price')->nullable();
            $table->string('code')->nullable();
            $table->date('order_date')->nullable();;
            $table->integer('order_state')->nullable(); // [1-10]
            $table->integer('arrived_code')->nullable(); // [1-10]
            $table->integer('delivery_code')->nullable(); // [1-10]
            $table->integer('has_deliverd')->nullable(); // 6 if deliverd 7 if canceld if deliverd order state must be >
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
        Schema::dropIfExists('orders');
    }
}
