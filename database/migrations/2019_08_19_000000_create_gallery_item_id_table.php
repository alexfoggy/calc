<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleryItemIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_item_id', function (Blueprint $table) {
            $table->id();
            $table->integer('gallery_subject_id');
            $table->string('alias');
            $table->tinyInteger('active');
            $table->tinyInteger('deleted');
            $table->integer('position');
            $table->tinyInteger('show_on_main');
            $table->string('img');
            $table->string('youtube_id');
            $table->string('youtube_link');
            $table->enum('type',['video','photo']);
            $table->integer('oldid');
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
        Schema::dropIfExists('gallery_item_id');
    }
}
