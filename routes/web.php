<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//商品首页
Route::get('/','Index\IndexController@index');
//添加购物车
Route::get('/addCart','Cart\CartController@addCart');
//购物车列表
Route::get('/CartList','Cart\CartController@CartList');
//计算总价
Route::post('/ConutPrice','Cart\CartController@ConutPrice');
//购物车删除商品
Route::post('/CartDele','Cart\CartController@CartDele');
//订单
Route::post('/Order','Order\OrderController@Order');
//订单页面
Route::get('/OrderList','Order\OrderController@OrderList');
//alipay
Route::get('/alipay','alipay\AlipayController@alipay');


