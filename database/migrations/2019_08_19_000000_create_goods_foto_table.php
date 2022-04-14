<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsFotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_foto', function (Blueprint $table) {
            $table->id();
            $table->integer('goods_item_id');
            $table->string('img')->nullable();
            $table->string('photo_url')->nullable();
            $table->integer('position')->default(0);
            $table->tinyInteger('active')->default(1);
            $table->date('add_date')->nullable();
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
        Schema::dropIfExists('goods_foto');
    }
}
