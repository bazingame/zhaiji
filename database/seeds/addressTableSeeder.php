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
            ['address_id'=>'A_00000001','user_id'=>'U_00000001','province'=>'A','city'=>'B','town'=>'C','address_detail'=>'DDDDD','phone'=>'18670999791'],
            ['address_id'=>'A_00000002','user_id'=>'U_00000001','province'=>'A','city'=>'B','town'=>'C','address_detail'=>'DDDDD','phone'=>'18670999791'],
            ['address_id'=>'A_00000003','user_id'=>'U_00000001','province'=>'A','city'=>'B','town'=>'C','address_detail'=>'DDDDD','phone'=>'18670999791'],
            ['address_id'=>'A_00000004','user_id'=>'U_00000002','province'=>'A','city'=>'B','town'=>'C','address_detail'=>'DDDDD','phone'=>'18670999791'],
            ['address_id'=>'A_00000005','user_id'=>'U_00000002','province'=>'A','city'=>'B','town'=>'C','address_detail'=>'DDDDD','phone'=>'18670999791'],
            ['address_id'=>'A_00000006','user_id'=>'U_00000002','province'=>'A','city'=>'B','town'=>'C','address_detail'=>'DDDDD','phone'=>'18670999791'],
            ['address_id'=>'A_00000007','user_id'=>'U_00000003','province'=>'A','city'=>'B','town'=>'C','address_detail'=>'DDDDD','phone'=>'18670999791'],
            ['address_id'=>'A_00000008','user_id'=>'U_00000003','province'=>'A','city'=>'B','town'=>'C','address_detail'=>'DDDDD','phone'=>'18670999791'],
            ['address_id'=>'A_00000009','user_id'=>'U_00000003','province'=>'A','city'=>'B','town'=>'C','address_detail'=>'DDDDD','phone'=>'18670999791'],
        ]);
    }
}
