<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //绑定数据表
    protected $table = 'category';
    //主键
    protected $primaryKey = 'id';
}
