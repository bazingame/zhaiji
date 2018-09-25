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
            $table->string('money');
            $table->string('package_size');
            $table->string('status')->comment("1=>未接单,2=>已接单,3=>已完成,4=>已取消");
            $table->string('deliverer_id')->nullable();
            $table->dateTime('order_time');
            $table->dateTime('take_order_time')->nullable();
            $table->dateTime('finish_time')->nullable();
            $table->string('mark')->nullable();
            $table->string('mark_status')->nullable()->comment('0=>未评价,1=>已评价');
            $table->string('note')->nullable();
            $table->string('cancel_reason')->nullable();
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
