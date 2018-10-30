<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLotteryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lottery', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('total_count')->comment('预计抽奖次数');
            $table->string('remain_total_count')->comment('剩余抽奖次数');
            $table->string('award_count')->comment('奖品数量');
            $table->string('notice');
            $table->string('sub_title');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
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
        Schema::dropIfExists('lottery');
    }
}
