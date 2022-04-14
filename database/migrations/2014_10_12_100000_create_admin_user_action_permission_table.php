<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUserActionPermissionTable extends Migration
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
            $table->integer('new')->default(0);
            $table->integer('save')->default(0);
            $table->integer('active')->default(0);
            $table->integer('del_to_rec')->default(0);
            $table->integer('del_from_rec')->default(0);
            $table->integer('moderate')->default(0);
            $table->integer('modules_id')->default(0);
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
