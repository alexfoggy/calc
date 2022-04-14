<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrontUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('front_user', function (Blueprint $table) {
            $table->id();
            $table->string('facebook_id')->nullable();
            $table->string('google_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('userName')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->integer('discount')->default(0);
            $table->string('password')->nullable();
            $table->string('gift_card')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->rememberToken();
            $table->string('recovery_hash')->nullable();
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
        Schema::dropIfExists('front_user');
    }
}
