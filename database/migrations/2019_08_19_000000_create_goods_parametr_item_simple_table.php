<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsParametrItemSimpleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_parametr_item_simple', function (Blueprint $table) {
            $table->id();
            $table->integer('goods_measure_id');
            $table->tinyInteger('lang_id')->nullable();
            $table->text('parametr_value')->nullable();
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
        Schema::dropIfExists('goods_parametr_item_simple');
    }
}
