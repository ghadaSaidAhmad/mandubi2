<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMandubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mandubs', function (Blueprint $table) {
            
            $table->id();
            $table->string('name');
            $table->string('profile_image')->nullable();
            $table->string('national_id_front_image')->nullable();
            $table->string('national_id_back_image')->nullable();
            $table->string('whats_number');
            $table->string('phone');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('gender')->nullable();
            $table->foreignId('shipping_type_id')->constrained();
            $table->foreignId('governorate_id')->constrained();
            $table->integer('location_lang')->nullable();
            $table->integer('location_lat')->nullable();
            $table->integer('active_now')->nullable();
            $table->integer('admin_agree')->nullable();//1 if admin verfied the account
            $table->string('verification_code')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->integer('complete_register')->nullable();; //1 if client complete all data
            $table->integer('payment_type')->nullable();
            $table->integer('shipping_method')->nullable();
            $table->integer('balance')->nullable();
            $table->string('fcm_token')->nullable();


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
        Schema::dropIfExists('mandubs');
    }
}
