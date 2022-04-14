<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfoItemIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_item_id', function (Blueprint $table) {
            $table->id();
            $table->integer('info_line_id');
            $table->string('alais');
            $table->string('alais_ro');
            $table->tinyInteger('is_public');
            $table->tinyInteger('active');
            $table->tinyInteger('deleted');
            $table->date('add_date');
            $table->string('img');
            $table->tinyInteger('show_img');
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
        Schema::dropIfExists('info_item_id');
    }
}
