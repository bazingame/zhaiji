<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Express extends Model
{
    protected $connection = 'mysql';  // 多数据库操作时最好进行绑定
    protected $table = 'express'; // 指定表
    public $timestamps = true;  // 是否自动维护时间戳
    protected $primaryKey = 'express_id';
    public $incrementing = false;
}
