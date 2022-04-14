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
            $table->tinyInteger('lang_id');
            $table->string('name');
            $table->text('body');
            $table->string('page_title');
            $table->string('h1_title');
            $table->string('meta_title');
            $table->string('meta_keywords');
            $table->string('meta_description');
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
        Schema::dropIfExists('gallery_subject');
    }
}
