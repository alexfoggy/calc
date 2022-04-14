<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_id', function (Blueprint $table) {
            $table->id();
            $table->integer('p_id')->default(0);
            $table->integer('level')->default(1);
            $table->string('alias')->nullable();
            $table->enum('page_type',['page','link'])->default('page');
            $table->integer('position')->default(0);
            $table->tinyInteger('active')->default(1);
            $table->tinyInteger('deleted')->default(0);
            $table->string('img')->nullable();
            $table->tinyInteger('top_menu')->default(1);
            $table->tinyInteger('footer_menu')->default(1);
            $table->tinyInteger('bot_footer_menu')->default(0);
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
        Schema::dropIfExists('menu_id');
    }
}
