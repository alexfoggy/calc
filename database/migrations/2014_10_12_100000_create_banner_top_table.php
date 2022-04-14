<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannerTopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_top', function (Blueprint $table) {
            $table->id();
            $table->integer('banner_top_id')->index();
            $table->tinyInteger('lang_id')->nullable();
            $table->string('img')->nullable();
            $table->string('name')->nullable();
            $table->text('body')->nullable();
            $table->text('link')->nullable();
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
        Schema::dropIfExists('banner_top');
    }
}
