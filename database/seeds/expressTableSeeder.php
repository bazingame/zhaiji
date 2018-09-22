<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class expressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('express')->insert([
            ['express_id'=>'Express_zhongtong','name'=>'中通'],
            ['express_id'=>'Express_yuantong','name'=>'圆通'],
            ['express_id'=>'Express_shentong','name'=>'申通'],
            ['express_id'=>'Express_huitong','name'=>'汇通'],
            ['express_id'=>'Express_baishi','name'=>'百世'],
            ['express_id'=>'Express_yunda','name'=>'韵达'],
            ['express_id'=>'Express_youzheng','name'=>'邮政'],
            ['express_id'=>'Express_shunfeng','name'=>'顺丰'],
            ['express_id'=>'Express_tiantian','name'=>'天天'],
            ['express_id'=>'Express_ems','name'=>'EMS'],
            ['express_id'=>'Express_jingdong','name'=>'京东'],
            ['express_id'=>'Express_weipinhui','name'=>'唯品会'],
            ['express_id'=>'Express_yousu','name'=>'优速'],
            ['express_id'=>'Express_anneng','name'=>'安能']
            ]);
    }
}














