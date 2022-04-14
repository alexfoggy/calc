<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops_id', function (Blueprint $table) {
            $table->id();
            $table->string('alias')->nullable();
            $table->string('phone')->nullable();
            $table->integer('city_id')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('img')->nullable();
            $table->integer('position')->default(0);
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
        Schema::dropIfExists('shops_id');
    }
}
