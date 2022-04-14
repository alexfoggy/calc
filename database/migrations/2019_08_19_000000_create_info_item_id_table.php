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
            $table->integer('info_line_id')->default(0);
            $table->string('alais')->nullable();
            $table->string('alais_ro')->nullable();
            $table->tinyInteger('is_public')->default(1);
            $table->tinyInteger('active')->default(1);
            $table->tinyInteger('deleted')->default(0);
            $table->date('add_date')->nullable();
            $table->string('img')->nullable();
            $table->tinyInteger('show_img')->default(1);
            $table->integer('oldid')->default(0);
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
