<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddresesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addreses', function (Blueprint $table) {
            $table->string('address_id');
            $table->string('user_id');
            $table->string('province');
            $table->string('city');
            $table->string('town');
            $table->string('address_detail');
            $table->primary('address_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addreses');
    }
}
