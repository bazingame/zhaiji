<?php

use Illuminate\Database\Seeder;

class userTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            ['open_id'=>"test_open_id_1",'user_id'=>"U_00000001",'headimg_url'=>'http://sasdas.com/aaa.jpg','phone'=>"18670999999",'register_time'=>'2018-09-24 16:42:22'],
            ['open_id'=>"test_open_id_2",'user_id'=>"U_00000002",'headimg_url'=>'http://sasdas.com/aaa.jpg','phone'=>"18670999992",'register_time'=>'2018-09-24 16:42:22'],
            ['open_id'=>"test_open_id_3",'user_id'=>"U_00000003",'headimg_url'=>'http://sasdas.com/aaa.jpg','phone'=>"18670999993",'register_time'=>'2018-09-24 16:42:22'],
            ['open_id'=>"test_open_id_4",'user_id'=>"U_00000004",'headimg_url'=>'http://sasdas.com/aaa.jpg','phone'=>"18670999994",'register_time'=>'2018-09-24 16:42:22'],
            ['open_id'=>"test_open_id_5",'user_id'=>"U_00000005",'headimg_url'=>'http://sasdas.com/aaa.jpg','phone'=>"18670999995",'register_time'=>'2018-09-24 16:42:22'],
        ]);
    }
}
