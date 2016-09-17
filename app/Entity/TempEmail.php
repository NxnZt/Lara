<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class TempEmail extends Model
{
    //绑定数据表
    protected $table = 'temp_email';
    //主键
    protected $primaryKey = 'id';
    //取消时间戳
    public $timestamps = false;
}
