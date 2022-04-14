<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuImagesTable extends Migration
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
            $table->integer('menu_id');
            $table->tinyInteger('active')->default(1);
            $table->string('img')->nullable();
            $table->integer('position')->default(0);
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
