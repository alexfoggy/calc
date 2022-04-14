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
            $table->string('img')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->integer('position')->default(0);
            $table->tinyInteger('deleted')->default(0);
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
