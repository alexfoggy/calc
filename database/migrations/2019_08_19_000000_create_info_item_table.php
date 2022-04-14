<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfoItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_item', function (Blueprint $table) {
            $table->id();
            $table->integer('info_item_id');
            $table->tinyInteger('lang_id');
            $table->string('name');
            $table->text('descr');
            $table->text('body');
            $table->string('author');
            $table->string('page_title');
            $table->string('h1_title');
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
        Schema::dropIfExists('info_item');
    }
}
