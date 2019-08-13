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

    <div class="content">
        <div class="title m-b-md">
            <table border="1" width="550">
                <tr>
                    <th>排序</th>
                    <th>商品名称</th>
                    <th>商品价格</th>
                    <th>商品图片</th>
                    <th>操作</th>
                </tr>
                @foreach ($res as $v)
                <tr>
                    <td id="goods_id">{{$v-> goods_id}}</td>
                    <td><a href="">{{$v-> goods_name}}</a></td>
                    <td>{{$v-> goods_price }}</td>
                    <td><a href=""><img src="http://test.shop.com/uploads/goodsimg/{{$v-> goods_img}} " width="50" height="50"></a></td>
                    <td goods_id="{{$v->goods_id}}"><span style="color: red" class="car">加入购物车</span></td>
                </tr>
                @endforeach
            </table>
            {{ $res->links() }}
        </div>
    </div>
</div>
</body>
<script>
    $(function () {
        $('.car').click(function () {
            var _this = $(this);
            var goods_id = _this.parent('td').attr('goods_id');
            $.ajax({
                    url:'addCart',
                dataType:'json',
                data:{goods_id:goods_id},
                type:'get',
                    success:function (res) {
                        console.log(res);
                       if(res == '请登录'){
                           alert('请先登陆');
                       }else if(res == '库存不足'){
                           alert('库存不足');
                       }else if(res == '添加购物车成功'){
                           alert('添加购物车成功');
                           location.href ='http://test.shop.com/CartList';
                       }else{
                           alert('添加购物车失败');
                       }
                    }
            })
        });
    })

</script>
</html>
