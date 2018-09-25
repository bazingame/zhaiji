<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    const CREATED_AT =  "register_time";
    const UPDATED_AT =  "register_time";
    protected $connection = 'mysql';  // 多数据库操作时最好进行绑定
    protected $table = 'users'; // 指定表
    public $timestamps = true;  // 是否自动维护时间戳
    protected $primaryKey = 'user_id';
    public $incrementing = false;
}
