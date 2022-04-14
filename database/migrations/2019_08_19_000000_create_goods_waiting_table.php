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
            $table->integer('front_user_id');
            $table->string('email');
            $table->date('date');
            $table->tinyInteger('message_sent');
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
