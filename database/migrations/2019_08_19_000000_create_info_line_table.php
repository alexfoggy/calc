<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfoLineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_line', function (Blueprint $table) {
            $table->id();
            $table->integer('info_line_id');
            $table->tinyInteger('lang_id')->nullable();
            $table->string('name')->nullable();
            $table->text('descr')->nullable();
            $table->string('img')->nullable();
            $table->string('page_title')->nullable();
            $table->string('h1_title')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('info_line');
    }
}
