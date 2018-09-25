<?php

use Illuminate\Database\Seeder;

class addressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('addresses')->insert([
            ['address_id'=>'A_00000001','user_id'=>'U_00000001','province'=>'湖南省','city'=>'湘潭市','town'=>'雨湖区','address_detail'=>'湘潭大学北苑1栋','phone'=>'18670999791'],
            ['address_id'=>'A_00000002','user_id'=>'U_00000001','province'=>'湖南省','city'=>'湘潭市','town'=>'雨湖区','address_detail'=>'湘潭大学北苑2栋','phone'=>'18670999791'],
            ['address_id'=>'A_00000003','user_id'=>'U_00000001','province'=>'湖南省','city'=>'湘潭市','town'=>'雨湖区','address_detail'=>'湘潭大学北苑3栋','phone'=>'18670999791'],
            ['address_id'=>'A_00000004','user_id'=>'U_00000002','province'=>'湖南省','city'=>'湘潭市','town'=>'雨湖区','address_detail'=>'湘潭大学北苑4栋','phone'=>'18670999791'],
            ['address_id'=>'A_00000005','user_id'=>'U_00000002','province'=>'湖南省','city'=>'湘潭市','town'=>'雨湖区','address_detail'=>'湘潭大学北苑5栋','phone'=>'18670999791'],
            ['address_id'=>'A_00000006','user_id'=>'U_00000002','province'=>'湖南省','city'=>'湘潭市','town'=>'雨湖区','address_detail'=>'湘潭大学北苑6栋','phone'=>'18670999791'],
            ['address_id'=>'A_00000007','user_id'=>'U_00000003','province'=>'湖南省','city'=>'湘潭市','town'=>'雨湖区','address_detail'=>'湘潭大学北苑7栋','phone'=>'18670999791'],
            ['address_id'=>'A_00000008','user_id'=>'U_00000003','province'=>'湖南省','city'=>'湘潭市','town'=>'雨湖区','address_detail'=>'湘潭大学北苑8栋','phone'=>'18670999791'],
            ['address_id'=>'A_00000009','user_id'=>'U_00000003','province'=>'湖南省','city'=>'湘潭市','town'=>'雨湖区','address_detail'=>'湘潭大学北苑9栋','phone'=>'18670999791'],
        ]);
    }
}
