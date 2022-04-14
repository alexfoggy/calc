<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGallerySubjectIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_subject_id', function (Blueprint $table) {
            $table->id();
            $table->integer('p_id')->default(0);
            $table->string('alias')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->tinyInteger('deleted')->default(0);
            $table->integer('level')->default(1);
            $table->integer('position')->default(0);
            $table->string('img')->nullable();
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
        Schema::dropIfExists('gallery_subject_id');
    }
}
