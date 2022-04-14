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
            $table->string('other_goods_subject_id')->nullable();
            $table->integer('brand_id')->nullable();
            $table->string('one_c_code')->nullable();
            $table->string('alias')->nullable();
            $table->float('price')->nullable();
            $table->float('price_old')->nullable();
            $table->float('price_club')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->tinyInteger('deleted')->default(0);
            $table->string('model')->nullable();
            $table->tinyInteger('in_stoc')->default(1);
            $table->text('youtube_link')->nullable();
            $table->string('youtube_id')->nullable();
            $table->integer('position')->default(0);
            $table->tinyInteger('show_on_main')->default(0);
            $table->tinyInteger('popular_element')->default(0);
            $table->string('tech')->nullable();

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
        Schema::dropIfExists('goods_item_id');
    }
}
