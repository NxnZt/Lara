<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //绑定数据表
    protected $table = 'product';
    //主键
    protected $primaryKey = 'id';
}
