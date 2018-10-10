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
    Route::put('/',['middleware'=>'user_auth','uses'=>'UserController@reviseUser']);
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
    //申请取消订单
    Route::put('/apply-cancel/{order_id}','OrderController@applyCancelOrder');
    //确认订单
    Route::put('/confirm/{order_id}','OrderController@confirmOrder');
    //评价订单
    Route::put('/mark/{order_id}','OrderController@markOrder');
});
//微信支付回调通知
Route::get('order/notify_url','OrderController@nofifyOrder');

//地址相关
Route::group(['prefix'=>'address','middleware'=>'user_auth'],function (){
    //添加地址
    Route::post('/',"AddressController@addAddress");
    //删除地址
    Route::delete('/{address_id}',"AddressController@deleteAddress");
    //修改地址
    Route::put('/{address_id}',"AddressController@reviseAddress");
});


//快递员相关
Route::group(['prefix'=>'deliverer'],function (){
    //获取快递可接单列表
    Route::get('/order/{start}/{limit}',['middleware'=>'user_auth','uses'=>'OrderController@getUnReceivedOrder']);
    //获取已经接单列表
    Route::get('/received-order/',['middleware'=>'user_auth','uses'=>'OrderController@getReceivedOrder']);
    //添加快递员
    Route::post('/','DelivererController@addDeliverer');
    //修改信息
    Route::put('/{deliverer_id}','DelivererController@reviseDeliverer');
    //删除快递员
    Route::delete('/{deliverer_id}','DelivererController@deleteDeliverer');
    //接单
    Route::post('/receive/{order_id}',['middleware'=>'user_auth','uses'=>'OrderController@receiveOrder']);
    //取消订单
    Route::put('/cancel/{order_id}','OrderController@cancelOrder');
    //拒绝取消订单
    Route::put('/refuse-cancel/{order_id}','OrderController@refuseCancelOrder');
    //获取配送员信息
    Route::get('/info/{deliverer_id}','DelivererController@getDelivererInfo');
    //获取配送员自己的信息
    Route::get('/info_my','DelivererController@getDelivererInfoMy');
    //确认订单
    Route::put('/confirm/{order_id}','OrderController@confirmOrder');
});

Route::group(['prefix'=>'map'],function (){
   Route::post('/getcoder/{latitude}/{longitude}','MapController@getCoder');
   Route::post('/search/{text}','MapController@search');
   Route::post('/distance/{start_latitude}/{start_longitude}/{end_latitude}/{end_longitude}','MapController@distance');
});


//管理员鉴权
Route::group(['prefix'=>'profess'],function (){
    Route::get('/to-deliverer/{user_id}','ProfessController@toDeliverer');
    Route::get('/to-user/{deliverer_id}','ProfessController@toUser');
});
