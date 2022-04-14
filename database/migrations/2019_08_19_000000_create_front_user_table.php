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
            $table->string('facebook_id');
            $table->string('google_id');
            $table->string('first_name');
            $table->string('userName');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->integer('discount');
            $table->string('password');
            $table->string('gift_card');
            $table->tinyInteger('active');
            $table->rememberToken();
            $table->string('recovery_hash');
            $table->timestamp();
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
