<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUserActionPermisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_user_action_permision', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_user_group_id')->index();
            $table->integer('new');
            $table->integer('save');
            $table->integer('active');
            $table->integer('del_to_rec');
            $table->integer('del_from_rec');
            $table->integer('moderate');
            $table->integer('modules_id');
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
        Schema::dropIfExists('admin_user_action_permision');
    }
}
