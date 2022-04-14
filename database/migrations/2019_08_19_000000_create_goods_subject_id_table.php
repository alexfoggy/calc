<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsSubjectIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_subject_id', function (Blueprint $table) {
            $table->id();
            $table->integer('p_id');
            $table->string('one_c_code');
            $table->string('alias');
            $table->tinyInteger('active');
            $table->tinyInteger('deleted');
            $table->integer('level');
            $table->integer('position');
            $table->tinyInteger('element_relation');
            $table->integer('menurow');
            $table->string('img');
            $table->integer('oldid');
            $table->tinyInteger('top_category');
            $table->string('svg_name');
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
        Schema::dropIfExists('goods_subject_id');
    }
}
