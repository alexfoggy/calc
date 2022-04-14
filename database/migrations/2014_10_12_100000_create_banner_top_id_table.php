<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannerTopIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_top_id', function (Blueprint $table) {
            $table->id();
            $table->integer('position')->default(0);
            $table->tinyInteger('active')->default(1);
            $table->tinyInteger('delete')->default(0);
            $table->integer('number')->nullable();
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
        Schema::dropIfExists('banner_top_id');
    }
}
