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
            $table->tinyInteger('lang_id')->nullable();
            $table->string('name')->nullable();
            $table->text('short_descr')->nullable();
            $table->longText('body')->nullable();
            $table->longText('page_title')->nullable();
            $table->longText('h1_title')->nullable();
            $table->longText('meta_title')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->longText('meta_description')->nullable();
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
