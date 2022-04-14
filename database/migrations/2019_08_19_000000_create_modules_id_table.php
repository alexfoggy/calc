<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulesIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules_id', function (Blueprint $table) {
            $table->id();
            $table->integer('p_id')->default(0);
            $table->string('alias')->nullable();
            $table->integer('level')->default(1);
            $table->integer('position')->default(0);
            $table->string('controller')->nullable();
            $table->string('models')->nullable();
            $table->string('view')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->tinyInteger('deleted')->default(0);
            $table->tinyInteger('root')->default(0);
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
        Schema::dropIfExists('modules_id');
    }
}
