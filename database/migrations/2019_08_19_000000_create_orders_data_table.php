<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_data', function (Blueprint $table) {
            $table->id();
            $table->integer('orders_id');
            $table->float('total_price')->nullable();
            $table->integer('total_count')->nullable();
            $table->integer('total_discount')->nullable();
            $table->integer('delivery_cost')->nullable();
            $table->integer('gift_card_id')->nullable();
            $table->string('gift_card_code')->nullable();
            $table->float('gift_card_sum')->nullable();
            $table->string('maib_trans_id')->nullable();
            $table->enum('maib_status',['unassigned','gone','paid','notpaid','failed'])->default('unassigned');
            $table->tinyInteger('lang_id')->nullable();
            $table->tinyInteger('email_sent')->default(0);
            $table->tinyInteger('money_were_returned')->default(0);
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
        Schema::dropIfExists('orders_data');
    }
}
