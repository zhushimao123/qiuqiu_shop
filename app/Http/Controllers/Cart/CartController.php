<?php

namespace App\Http\Controllers\Cart;

use DemeterChain\C;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\model\Goods;
use App\model\Cart;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public  function  addCart()
    {
        $id = Auth::id();
        if(!$id){
            echo json_encode('请登录',JSON_UNESCAPED_UNICODE);die;
        }
        $goods_id = $_GET['goods_id'];
        $res = Goods::where(['goods_id'=> $goods_id])->first();
        if(!$goods_id){
            echo json_encode('至少选择一个商品',JSON_UNESCAPED_UNICODE);die;
        }else{
//            echo "<pre>";print_r($res);echo "<pre>";
            //每次添加一件商品
            if($res-> goods_inventory ==0) {
                echo json_encode('库存不足', JSON_UNESCAPED_UNICODE);die;
            }
        }
        //添加至购物车 @修改库存   加商户id
        $data = [
            'user_id'=> $id,
            'goods_id'=> $goods_id,
            'buy_number'=> 1,
            'create_time'=> time(),
            'update_time'=> time(),
            'brand_id'=>$res-> brand_id,
            'price'=> $res-> goods_price
        ];
        //商品一样 数量叠加 @减少库存
        $result = Cart::where(['goods_id'=> $goods_id])-> first();
        if($result){
            $row = Cart::where(['goods_id'=> $goods_id])->update(['buy_number'=> $result-> buy_number+1]);
            $this-> goodsnum($goods_id);
            if($row){
                die(json_encode('添加购物车成功', JSON_UNESCAPED_UNICODE));
            }else{
                die(json_encode('添加购物车失败', JSON_UNESCAPED_UNICODE));
            }

        }else{
            $row = Cart::InsertGetid($data);
            if($row){
                //成功
                $this-> goodsnum($goods_id);
                die(json_encode('添加购物车成功', JSON_UNESCAPED_UNICODE));
            }else{
                //失败
                die(json_encode('添加购物车失败', JSON_UNESCAPED_UNICODE));
            }
        }
    }

    public  function goodsnum($goods_id)
    {
        $res = Goods::where(['goods_id'=> $goods_id])->first();
        $num = Goods::where(['goods_id'=> $goods_id])->update(['goods_inventory' => $res-> goods_inventory-1]);
//        var_dump($num);
        if(!$num){
            die(json_encode('减少库存失败', JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     *  购物车列表
     * @用户删除商品数量 库存增加
     * @商家下架商家   提示用户
     * @商家价格修改购物车 同步 提示用户
     */
    public function CartList()
    {
        $id = Auth::id();
        if(!$id){
            die(json_encode('请登录', JSON_UNESCAPED_UNICODE));
        }
        $res = DB::table('shop_cart')
            ->join('shop_goods','shop_goods.goods_id','=','shop_cart.goods_id')
            ->join('shop_brand','shop_brand.brand_id','=','shop_cart.brand_id')
            ->where(['user_id'=>$id])
            ->orderBy('shop_cart.create_time','desc')
            ->get();
//        echo "<pre>";print_r($res);echo "<pre>";die;
        $ConutPrice = 0;
        foreach ($res as $k=> $v){
            $ConutPrice = $ConutPrice + $v-> buy_number  * $v->goods_price;
        }
        return view('cart.index',['res'=> $res,'ConutPrice'=> $ConutPrice]);
    }

    //购物车总价计算
    public function ConutPrice()
    {
        $user_id = Auth::id();
        $goods_id = $_POST['goods_id'];
        $goods_id = explode(',',$goods_id);
        $cart_info = DB::table('shop_cart')->where(['user_id'=> $user_id])->whereIn('goods_id',$goods_id)->get();
        $goods_info = DB::table('shop_goods')->whereIn('goods_id',$goods_id)->get();

        $ConutPrice = 0;
        foreach ($cart_info as $k=> $v){
            foreach ($goods_info as $key=>$val){
                if($val->goods_id== $v->goods_id)
                {
                    $ConutPrice = $ConutPrice + $v-> buy_number  * $val->goods_price;
                }
            }
        }
        $json = [
            'price'=> $ConutPrice
        ];
        echo  json_encode($json,JSON_UNESCAPED_UNICODE);
    }

    //商品删除
    public function CartDele()
    {
        $cart_id = $_POST['cart_id'];
        $cart_info = Cart::where(['cart_id'=> $cart_id])-> first();
        $buy_number = $cart_info-> buy_number;
        $goods_id = $cart_info-> goods_id;
        $goods_info = Goods::where(['goods_id'=> $goods_id])-> first();
        //库存增加
        $goods_info2 = Goods::where(['goods_id'=> $goods_id])-> update(['goods_inventory'=> $goods_info->goods_inventory +$buy_number ]);

        if(!$goods_info2){
            die(json_encode('修改库存失败',JSON_UNESCAPED_UNICODE));
        }

        $row = Cart::where(['cart_id'=> $cart_id])->delete();
        if($row){
            die(json_encode('ok',JSON_UNESCAPED_UNICODE));
        }else{
            die(json_encode('no',JSON_UNESCAPED_UNICODE));
        }
    }

}
