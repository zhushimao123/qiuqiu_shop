<?php

namespace App\Http\Controllers\Order;

use App\model\Cart;
use App\model\Goods;
use App\model\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function Order()
    {
        $id = Auth::id();
        if(!$id)
        {
            die(json_encode('请登录',JSON_UNESCAPED_UNICODE));
        }
        if(!$_POST['goods_id'])
        {
            die(json_encode('至少选择一个商品进行结算',JSON_UNESCAPED_UNICODE));
        }
        $goods_id = $_POST['goods_id'];
        $goods_id = explode(',',$goods_id);
        $goods_info = Goods::whereIn('goods_id',$goods_id)->get();
        $cart_info = Cart::where(['user_id'=> $id])->get();
        $countprice = 0;
        foreach ($goods_info as $k=> $v){
            if($v->goods_show ==2)
            {
                die(json_encode('购物车中已有下架商品',JSON_UNESCAPED_UNICODE));
            }
            foreach ($cart_info as $key=> $val)
            {
                if($val-> goods_id == $v-> goods_id)
                {
                    $countprice =  $countprice + $val-> buy_number * $v-> goods_price;
                }
            }
        }
        /*
         *   生成父级订单
         *   生成订单详情表
         *   商家拆分为子订单
         *  订单号统一   金额根据用户所选商品的所在商家分的所额
         * */
        //@1 生成订单父级表
        DB::beginTransaction();
        try{
            //父级
            $orderInfo['order_no'] = $this-> str();
            $orderInfo['pay_type'] = 1;
            $orderInfo['order_amount']= $countprice;
            $orderInfo['user_id'] = $id;
            $orderInfo['create_time'] =time();
            $orderInfo['update_time'] = time();
            $res1 = DB::table('shop_order')-> insert($orderInfo);
//        var_dump($res1);die;

            //子级

            $order_id = DB::getPdo()->lastInsertId($res1);
            Session::put(['order_id'=> $order_id]);
            $ordertable = DB::table('shop_order')->where(['order_id'=>$order_id])->first();
            $cart_Infos = DB::table('shop_cart')->where(['user_id'=> $id])->whereIn('goods_id',$goods_id)->get();
//            var_dump($ordertable);die;
            $order_no = $ordertable-> order_no;
            foreach ($goods_info as $k=> $v)
            {
                foreach ($cart_Infos as $key=> $val)
                {
                    if($val-> goods_id == $v-> goods_id)
                    {
                        $brand_info[$k]['order_no'] = $order_no;
                        $brand_info[$k]['update_time']= time();
                        $brand_info[$k]['create_time']=time();
                        $brand_info[$k]['order_id'] = $order_id;
                        $brand_info[$k]['brand_id'] = $v-> brand_id;
                        $brand_info[$k]['user_id'] = $id;
                        $brand_info[$k]['order_amount'] = $v-> goods_price * $val-> buy_number;
                        $brand_info[$k]['goods_id']= $v-> goods_id;
                    }
                }
            }
            $res2 = DB::table('shop_son_order')->insert($brand_info);

            //订单详情
            $cart_Info = DB::table('shop_cart')
                ->join('shop_goods','shop_goods.goods_id','=','shop_cart.goods_id')
                ->where(['user_id'=> $id])
                ->get();
//            echo "<pre>";print_r($cart_Info);echo "<pre>";die;
            foreach ($cart_Info as $k=> $v)
            {
                $dateil_info[$k]['order_id']= '111';
                $dateil_info[$k]['user_id'] = $id;
                $dateil_info[$k]['goods_id'] = $v-> goods_id;
                $dateil_info[$k]['buy_number'] = $v-> buy_number;
                $dateil_info[$k]['goods_price'] = $v-> goods_price;
                $dateil_info[$k]['goods_name'] = $v-> goods_name;
                $dateil_info[$k]['goods_img'] = $v-> goods_img;
                $dateil_info[$k]['create_time']= time();
                $dateil_info[$k]['update_time'] = time();
            }
            $res3 = DB::table('shop_detail')-> insert($dateil_info);
            if($res3 && $res1 && $res2){
                DB::commit();
                echo json_encode('success',JSON_UNESCAPED_UNICODE);
            }
        }catch (\Exception $e){
            DB::rollBack();
            echo json_encode('error',JSON_UNESCAPED_UNICODE);
        }
    }

    //订单号
    public function str()
    {
        $str = substr(sha1(time() . Str::random(10).'PIKAI_'."_"), 5, 15);
        return $str;
    }

    public function  OrderList()
    {
       $order_id =  Session::get('order_id');
       $res = Order::where(['order_id'=> $order_id])-> first();
       return view('order.orderList',['res'=> $res,'order_id'=> $order_id]);
    }
}
