<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_item', function (Blueprint $table) {
            $table->id();
            $table->integer('goods_item_id');
            $table->tinyInteger('lang_id');
            $table->string('name');
            $table->text('short_descr');
            $table->longText('body');
            $table->longText('page_title');
            $table->longText('h1_title');
            $table->longText('meta_title');
            $table->longText('meta_keywords');
            $table->longText('meta_description');
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
        Schema::dropIfExists('goods_item');
    }
}
