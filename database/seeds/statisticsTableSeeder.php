<?php

use Illuminate\Database\Seeder;

class statisticsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statistics')->insert([
            ['user_count'=>0,'deliverer_count'=>0,'order_count'=>0,'address_count'=>0],
        ]);
    }
}
