<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsBrandIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_brand_id', function (Blueprint $table) {
            $table->id();
            $table->string('alias');
            $table->string('img');
            $table->tinyInteger('active');
            $table->integer('position');
            $table->tinyInteger('deleted');
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
        Schema::dropIfExists('goods_brand_id');
    }
}
