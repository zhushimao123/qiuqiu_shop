<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    public  $table = 'shop_goods';
    public  $timestamps =false;
    public  $primaryKey = 'goods_id';
}
