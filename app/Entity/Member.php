<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    //绑定数据表
    protected $table = 'member';
    //主键
    protected $primaryKey = 'id';
}
