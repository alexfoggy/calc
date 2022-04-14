<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basket', function (Blueprint $table) {
            $table->id();
            $table->integer('basket_id');
            $table->string('alias_item');
            $table->integer('goods_item_id');
            $table->integer('items_count');
            $table->string('goods_name');
            $table->float('goods_price');
            $table->string('goods_one_c_code');
            $table->string('goods_model');
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
        Schema::dropIfExists('basket');
    }
}
