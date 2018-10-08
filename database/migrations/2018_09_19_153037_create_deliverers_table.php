<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliverersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliverers', function (Blueprint $table) {
            $table->string('open_id');
            $table->string('deliverer_id');
            $table->string('name');
            $table->string('order_amount');
            $table->string('mark');
            $table->string('phone');
            $table->string('order_count');
            $table->string('order_money_count');
            $table->dateTime('register_time');
            $table->primary('deliverer_id');
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
        Schema::dropIfExists('deliverers');
    }
}
