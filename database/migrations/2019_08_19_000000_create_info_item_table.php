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
            $table->integer('info_item_id')->default(0);
            $table->tinyInteger('lang_id')->nullable();
            $table->string('name')->nullable();
            $table->text('descr')->nullable();
            $table->text('body')->nullable();
            $table->string('author')->nullable();
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
        Schema::dropIfExists('info_item');
    }
}
