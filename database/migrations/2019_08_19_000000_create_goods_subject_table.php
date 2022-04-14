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
            $table->tinyInteger('lang_id')->nullable();
            $table->string('name')->nullable();
            $table->text('body')->nullable();
            $table->text('page_title')->nullable();
            $table->text('h1_title')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
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
