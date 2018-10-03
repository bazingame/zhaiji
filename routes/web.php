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
//});

Route::get('/test',function(){
    return 'test fine!';
});
//换取用户唯一标识 OpenID
Route::post('code','LoginController@getOpenID');

//验证码
Route::group(['prefix'=>'msg'],function(){
   Route::post('/send','MsgController@sendVerifiedCode');
   Route::post('/verify','MsgController@verifyVerifiedCode');
});

//登录
Route::post('login',"LoginController@login");

//用户相关
Route::group(['prefix'=>'user'],function (){
    //注册
    Route::post('/','UserController@addUser');
    //修改信息
    Route::patch('/',['middleware'=>'user_auth','uses'=>'UserController@reviseUser']);
    //删除用户
    Route::delete('/{user_id}','UserController@deleteUser');
});

//订单相关
Route::group(['prefix'=>'order','middleware'=>'user_auth'],function(){
    //获取本人订单列表
    Route::get('/',"OrderController@getOrder");
    //获取订单信息
    Route::get('/{order_id}','OrderController@getOneOrder');
    //下单
    Route::post('/','OrderController@addOrder');
    //取消订单
    Route::patch('/cancel/{order_id}','OrderController@cancelOrder');
    //确认订单
    Route::patch('/confirm/{order_id}','OrderController@confirmOrder');
    //评价订单
    Route::patch('/mark/{order_id}','OrderController@markOrder');
});


//地址相关
Route::group(['prefix'=>'address','middleware'=>'user_auth'],function (){
    //添加地址
    Route::post('/',"AddressController@addAddress");
    //删除地址
    Route::delete('/{address_id}',"AddressController@deleteAddress");
    //修改地址
    Route::patch('/{address_id}',"AddressController@reviseAddress");
});


//快递员相关
Route::group(['prefix'=>'deliverer'],function (){
    //获取快递可接单列表
    Route::get('/order/{start}/{limit}',['middleware'=>'user_auth','uses'=>'OrderController@getUnReceivedOrder']);
    //添加快递员
    Route::post('/','DelivererController@addDeliverer');
    //修改信息
    Route::patch('/{deliverer_id}','DelivererController@reviseDeliverer');
    //删除快递员
    Route::delete('/{deliverer_id}','DelivererController@deleteDeliverer');
    //接单
    Route::post('/receive/{order_id}',['middleware'=>'user_auth','uses'=>'OrderController@receiveOrder']);
});

Route::group(['prefix'=>'map'],function (){
   Route::post('/getcoder','MapController@getCoder');
   Route::post('/search','MapController@search');
});
