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
            $table->integer('p_id')->default(0);
            $table->string('one_c_code')->default(0);
            $table->string('alias')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->tinyInteger('deleted')->default(0);
            $table->integer('level')->default(1);
            $table->integer('position')->default(0);
            $table->tinyInteger('element_relation')->default(0);
            $table->integer('menurow')->default(1);
            $table->string('img')->nullable();
            $table->integer('oldid')->default(0);
            $table->tinyInteger('top_category')->default(0);
            $table->string('svg_name')->nullable();
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
