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
            $table->string('alias')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->tinyInteger('deleted')->default(0);
            $table->integer('position')->default(0);
            $table->tinyInteger('show_on_main')->default(0);
            $table->string('img')->nullable();
            $table->string('youtube_id')->nullable();
            $table->string('youtube_link')->nullable();
            $table->enum('type',['video','photo'])->default('photo');
            $table->integer('oldid')->default(0);
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
        Schema::dropIfExists('gallery_item_id');
    }
}
