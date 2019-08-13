<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public  $table = 'shop_cart';
    public  $timestamps =false;
    public  $primaryKey = 'cart_id';
}
