<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
    <script src="js/jquery-1.11.2.min.js"></script>
</head>
<body>
<div class="flex-center position-ref full-height">
    <h3>购物车</h3>
    <table border="1">
            <tr>
                <th><input type="checkbox" id="check"></th>
                <th>商品名称</th>
                <th>商品图片</th>
                <th>商品价格</th>
                <th>购买数量</th>
                <th>所属商铺</th>
                <th>操作</th>
            </tr>
        @foreach($res as $k=>$v)
                <tr>
                    <td><input type="checkbox" class="box" goods_id="{{$v-> goods_id}}"></td>
                    <td>{{$v-> goods_name}}</td>
                    <td><img src="http://test.shop.com/uploads/goodsimg/{{$v-> goods_img}} " width="50" height="50"></td>
                    @if($v-> price != $v-> goods_price)
                        <td>
                            @if($v-> price > $v-> goods_price)
                                {{$v-> price}}<br>
                                <font style="color: #2ea8e5">比加入购物车之前降低了{{$v-> price - $v-> goods_price}}元 </font><br>
                                {{$v-> goods_price}}
                            @elseif($v-> price < $v-> goods_price)
                                {{$v-> price}}<br>
                                <font style="color: #2ea8e5">比加入购物车之前增加了{{$v-> goods_price - $v-> price}}元 </font><br>
                                {{$v-> goods_price}}
                            @endif
                        </td>
                    @else
                        <td>{{$v-> goods_price}}</td>
                    @endif
                    <td>{{$v-> buy_number}}</td>
                    <td>{{$v-> brand_name}}</td>
                    <td><span><font style="color: #0d6aad"><b class="del" cart_id = "{{$v-> cart_id}}">删除</b></font></span></td>
                </tr>
        @endforeach
    </table>

    _____________________________________________________________________________________________________________________________
    <br>
    <br>
    <div id="div">

        @foreach($res as $k=>$v)
            @if($v-> goods_show ==2)
                <h5><font style="color: #00a65a">购物车中已下商品已经下架是否移出</font></h5>
                <tr>
                    <td>{{$v-> goods_name}}</td>
                    <td><img src="http://test.shop.com/uploads/goodsimg/{{$v-> goods_img}} " width="50" height="50"></td>
                    <td>{{$v-> goods_price}}</td>
                    <td>{{$v-> buy_number}}</td>
                    <td>{{$v-> brand_name}}</td>
                    <td><span><font style="color: #0d6aad" class="aoto" goods_id="{{$v-> goods_id}}">移出</font></span></td>
                </tr>
                <br>
            @endif
        @endforeach
    </div>
    <br>


    <h5>总价：<font style="color: red" id="price">{{$ConutPrice}}</font></h5>
    <input type="button" name="" id="Settlement" value="结算">
</div>
</body>
<script>
    $(function () {
        //点击全选
        $('#check').click(function () {
            var check = $('.box').prop('checked');
            var _this = $(this);
            var box = _this.prop('checked');
            $('.box').prop('checked',box);
            getConutPrice();
        });
        //计算总价
        $('.box').click(function () {
            getConutPrice();
        });

        function getConutPrice() {
            var goods_id ='';
            var box = $('.box');
            box.each(function (index) {
                if($(this).prop('checked')==true){
                    goods_id += $(this).attr('goods_id') + ',';
                }
            })
            goods_id = goods_id.substr(0,goods_id.length-1);
            $.ajax({
                url:'/ConutPrice',
                dataType:'json',
                data:{goods_id:goods_id},
                type:'post',
                success:function (res) {
                    // console.log(res.price);
                    $('#price').text(res.price);
                }
            })
        }

        //删除
        $('.del').click(function () {
           var _this = $(this);
           var cart_id  = _this.attr('cart_id')
           $.ajax({
               url:"/CartDele",
               data:{cart_id:cart_id},
               dataType: 'json',
               type: 'post',
               success:function (res) {
                   if(res =='ok'){
                       alert('删除成功');
                       history.go(0)
                   }else{
                       alert('删除失败');
                   }
               }
           });
        })

        //移出
        $('.aoto').click(function () {
            $('#div').html('');

        })

        //结算
        $('#Settlement').click(function () {
            var goods_id ='';
            var box = $('.box');
            box.each(function (index) {
                if($(this).prop('checked')==true){
                    goods_id += $(this).attr('goods_id') + ',';
                }
            })
            goods_id = goods_id.substr(0,goods_id.length-1);
            // console.log(goods_id);
            $.ajax({
                url:'/Order',
                dataType:'json',
                data:{goods_id:goods_id},
                type:'post',
                success:function (res) {
                    if(res == '购物车中已有下架商品')
                    {
                        alert('购物车中已有下架商品');
                    }else if(res =='success'){
                        location.href = 'http://test.shop.com/OrderList';
                    }
                }
            })
        })
    })
</script>
</html>
