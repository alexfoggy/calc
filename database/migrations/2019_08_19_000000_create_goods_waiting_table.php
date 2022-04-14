<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsWaitingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_waiting', function (Blueprint $table) {
            $table->id();
            $table->integer('goods_item_id');
            $table->integer('front_user_id')->default(0);
            $table->string('email')->nullable();
            $table->date('date')->default(0);
            $table->tinyInteger('message_sent')->default(0);
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
        Schema::dropIfExists('goods_waiting');
    }
}
