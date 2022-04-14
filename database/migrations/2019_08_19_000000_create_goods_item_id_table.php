<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsItemIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_item_id', function (Blueprint $table) {
            $table->id();
            $table->integer('goods_subject_id');
            $table->string('other_goods_subject_id');
            $table->integer('brand_id');
            $table->string('one_c_code');
            $table->string('alias');
            $table->float('price');
            $table->float('price_old');
            $table->float('price_club');
            $table->tinyInteger('active');
            $table->tinyInteger('deleted');
            $table->string('model');
            $table->tinyInteger('in_stoc');
            $table->text('youtube_link');
            $table->string('youtube_id');
            $table->integer('position');
            $table->tinyInteger('show_on_main');
            $table->tinyInteger('popular_element');
            $table->string('tech');

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
        Schema::dropIfExists('goods_item_id');
    }
}
