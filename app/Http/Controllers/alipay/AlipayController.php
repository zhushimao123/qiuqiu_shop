<?php

namespace App\Http\Controllers\alipay;

use App\model\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AlipayController extends Controller
{
    public function alipay()
    {
        if(!$_GET['order_id'])
        {
            die(json_encode('没有此订单',JSON_UNESCAPED_UNICODE));
        }
        $order_id = $_GET['order_id'];
        $oreder_info = Order::where(['order_no'=> $order_id])->first();
        //商品总价
        $order_amount = $oreder_info-> order_amount;
//        echo  date('Y-m-d H:i:s',time());
        //请求参数
        $bizcont = [
            'out_trade_no'=> $order_id,
            'product_code'=> 'FAST_INSTANT_TRADE_PAY',
            'total_amount'=> $order_amount /100,
            'subject'=> '球球买东西',
        ];
        //公共请求参数
        $data = [
            'app_id'=> '2016092700608889',
            'method'=> 'alipay.trade.wap.pay',
            //'return_url'=> '同步回调',
            'format'   => 'JSON',
            'charset'=> 'utf-8',
            'sign_type'=> 'RSA2',
            'timestamp'=> date('Y-m-d H:i:s',time()),
            'version'=> '1.0',
            //'notify_url'=> ''
            'biz_content'=> json_encode($bizcont)

        ];
        //拼接参数
        //@1 字典序排序
        ksort($data);
        //拼接为key=val & kay=val
        $str = '';
        foreach ($data as $k=> $v)
        {
            $str .= $k .'='.$v .'&';
        }
        $str1 = rtrim($str,'&');
        //echo $str1;
        //@2 生成签名  私钥加密
        $private_key = openssl_get_privatekey('file://'.storage_path('app/key/private.pem'));
//               var_dump($private_key);
//        $a = openssl_error_string();
//        var_dump($a);die;

        openssl_sign($str1,$sign,$private_key,OPENSSL_ALGO_SHA256);
//        var_dump($sign);
        $data['sign']= $sign;
        //@3拼接成url格式
        $str2 = '?';
        foreach ($data as $key=> $val)
        {
            $str2 .= $key .'=' .urlencode($val) .'&';
        }
//        echo $str2;
        $str3 = trim($str2,'&');
//        echo $str3;die;
        //请求地址
        $url = 'https://openapi.alipaydev.com/gateway.do'.$str3;
//        echo $url;die;sdsd
        header('refresh:2;url='.$url);
    }
}
