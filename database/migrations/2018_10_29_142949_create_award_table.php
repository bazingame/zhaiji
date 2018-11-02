<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAwardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('award', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lottery_id');
            $table->string('award_name');
            $table->string('award_index');
            $table->string('award_type')->nullable();
            $table->integer('money_award_amount')->nullable();
            $table->string('count');
            $table->string('remain_count');
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
        Schema::dropIfExists('award');
    }
}
