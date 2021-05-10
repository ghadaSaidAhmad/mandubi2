<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('profile_image')->nullable();
            $table->string('national_id_front_image')->nullable();
            $table->string('national_id_front_image')->nullable();
            $table->string('fcm_token')->nullable();
            $table->string('national_id_back_image')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone');
            $table->integer('gender')->nullable();; //1 if male 2 fmale 3 company
            $table->string('verification_code')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->integer('complete_register')->nullable(); //1 if client complete all data
            $table->integer('admin_agree')->nullable();; //1 if admin verfied the account
            $table->rememberToken();
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
        Schema::dropIfExists('clients');
    }
}
