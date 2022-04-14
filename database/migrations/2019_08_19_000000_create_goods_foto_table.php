<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsFotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_foto', function (Blueprint $table) {
            $table->id();
            $table->integer('goods_item_id');
            $table->string('img');
            $table->string('photo_url');
            $table->integer('position');
            $table->tinyInteger('active');
            $table->date('add_date');
            $table->integer('oldid');
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
        Schema::dropIfExists('goods_foto');
    }
}
