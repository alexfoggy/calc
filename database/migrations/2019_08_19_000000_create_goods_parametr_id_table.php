<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsParametrIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_parametr_id', function (Blueprint $table) {
            $table->id();
            $table->integer('goods_subject_id');
            $table->enum('measure_type',['no_measure','with_measure','measure_list']);
            $table->integer('goods_measure_id');
            $table->string('alias');
            $table->enum('parametr_type',['input','textarea','select','radio','checkbox']);
            $table->integer('position');
            $table->tinyInteger('active');
            $table->tinyInteger('deleted');
            $table->tinyInteger('show_in_list');
            $table->string('font_for_list');
            $table->tinyInteger('start_open');
            $table->tinyInteger('display_in_line');
            $table->tinyInteger('display_on_list_page');
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
        Schema::dropIfExists('goods_parametr_id');
    }
}
