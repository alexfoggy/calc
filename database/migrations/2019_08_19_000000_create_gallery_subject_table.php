<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGallerySubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_subject', function (Blueprint $table) {
            $table->id();
            $table->integer('gallery_subject_id');
            $table->tinyInteger('lang_id')->nullable();;
            $table->string('name')->nullable();;
            $table->text('body')->nullable();;
            $table->string('page_title')->nullable();;
            $table->string('h1_title')->nullable();;
            $table->string('meta_title')->nullable();;
            $table->string('meta_keywords')->nullable();;
            $table->string('meta_description')->nullable();;
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
        Schema::dropIfExists('gallery_subject');
    }
}
