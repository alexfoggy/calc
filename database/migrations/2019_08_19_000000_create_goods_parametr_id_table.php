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
            $table->enum('measure_type',['no_measure','with_measure','measure_list'])->default('nu_measure');
            $table->integer('goods_measure_id')->nullable();
            $table->string('alias')->nullable();
            $table->enum('parametr_type',['input','textarea','select','radio','checkbox'])->default('input');
            $table->integer('position')->default(0);
            $table->tinyInteger('active')->default(1);
            $table->tinyInteger('deleted')->default(0);
            $table->tinyInteger('show_in_list')->default(0);
            $table->string('font_for_list')->nullable();
            $table->tinyInteger('start_open')->default(0);
            $table->tinyInteger('display_in_line')->default(0);
            $table->tinyInteger('display_on_list_page')->default(1);
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
