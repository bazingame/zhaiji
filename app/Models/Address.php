<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $connection = 'mysql';  // 多数据库操作时最好进行绑定
    protected $table = 'addresses'; // 指定表
    public $timestamps = true;  // 是否自动维护时间戳
    protected $primaryKey = 'address_id';
    public $incrementing = false;

    public static function getAddressId($user_id){
        $addressArr =  self::where('user_id','=',$user_id)->get();
        $addressIdArr = array();
        foreach($addressArr as $k => $value){
            $addressIdArr[] = $value['address_id'];
        }
        return $addressIdArr;
    }
}
