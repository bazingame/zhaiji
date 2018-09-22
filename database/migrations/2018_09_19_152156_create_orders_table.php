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
            $table->string('order_id');
            $table->string('user_id');
            $table->string('address_id');
            $table->string('express_id');
            $table->string('package_id');
            $table->string('insurance');
            $table->string('package_size');
            $table->string('status');
            $table->string('phone');
            $table->string('deliverer_id');
            $table->dateTime('order_time');
            $table->dateTime('take_order_time');
            $table->dateTime('finish_time');
            $table->string('mark');
            $table->primary('order_id');
            $table->index('order_id');
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
