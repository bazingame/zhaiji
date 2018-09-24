<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    //获取订单列表
    public function getOrder(Request $request){

    }
    //获取订单详细信息
    public function getOneOrder(Request $request){

    }
    //下单
    public function addOrder(Request $request){

        $check = $this->checkParam($request,array('user_id','address_id','express_id','package_id','insurance','package_size','status','phone'),array('-4008','-4009','-4010','-4011','-4012','-4013','-4014','-4015'));
        if(!$check[0]){
            return self::setResponse(null,400,$check[1]);
        }

        //检查数据正确性
//        if()

    }
    //更改订单状态
    public function reviseOrder(Request $request){

    }
    //评价订单
    public function markOrder(Request $request){

    }

}

