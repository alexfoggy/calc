<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('basket_id')->nullable();
            $table->integer('front_user_id')->nullable();
            $table->enum('type',['checkout','cancelled','approved','finished'])->default('checkout');
            $table->text('admin_comment')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->tinyInteger('deleted')->default(0);
            $table->tinyInteger('paid')->default(0);
            $table->tinyInteger('fast_order')->default(0);
            $table->enum('delivery_method',['pickup','delivery'])->default('pickup');
            $table->enum('pay_method',['card','cash'])->default('cash');
            $table->string('discount')->nullable();
            $table->tinyInteger('seen')->default(0);
            $table->tinyInteger('was_sent')->default(0);
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
        Schema::dropIfExists('orders');
    }
}
