<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_subject', function (Blueprint $table) {
            $table->id();
            $table->integer('goods_subject_id');
            $table->tinyInteger('lang_id');
            $table->string('name');
            $table->text('body');
            $table->text('page_title');
            $table->text('h1_title');
            $table->string('meta_title');
            $table->text('meta_keywords');
            $table->text('meta_description');
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
        Schema::dropIfExists('goods_subject');
    }
}
