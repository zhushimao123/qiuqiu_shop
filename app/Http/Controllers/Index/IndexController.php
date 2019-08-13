<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use  App\model\Goods;
class IndexController extends Controller
{
    public function index()
    {
        $res = Goods::where(['goods_show'=> 1])->paginate(6);
        return view('goods.index',['res'=> $res]);
    }
}
