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
            $table->string('alias_item')->nullable();
            $table->integer('goods_item_id')->nullable();
            $table->integer('items_count')->default(1);
            $table->string('goods_name')->nullable();
            $table->float('goods_price')->nullable();
            $table->string('goods_one_c_code')->nullable();
            $table->string('goods_model')->nullable();
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
