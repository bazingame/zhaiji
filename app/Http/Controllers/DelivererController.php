<?php

namespace App\Http\Controllers;

use App\Models\Deliverer;
use Illuminate\Http\Request;

class DelivererController extends Controller
{
    public function addDeliverer(Request $request){

    }

    public function reviseDeliverer(Request $request){

    }

    public function deleteDeliverer(Request $request){

    }

    //获取快递员信息
    public function getDelivererInfo(Request $request){
        $deliverer_id = $request->route('deliverer_id');
        if($deliverer_id==''){
            return self::setResponse(null,400,-4016);
        }
        if($deliverer = Deliverer::where('deliverer_id','=',$deliverer_id)->first()){
            $phone = $deliverer->phone;
            $name = $deliverer->name;
            $order_amount= $deliverer->order_amount;
            $mark= $deliverer->mark;

            //总收入
            $order_money = $deliverer->order_money;
            //总订单
            $order_count = $deliverer->order_count;
            //判断是否今日最新
            $update_time = substr($deliverer->update_time,0,10);
            if($update_time!=Date("Y-m-d",time())) {
                //今日收入
                $order_money_today = 0;
                //今日订单
                $order_count_today = 0;
            }else{
                //今日收入
                $order_money_today = $deliverer->order_money_today;
                //今日订单
                $order_count_today = $deliverer->order_count_today;
            }

            return self::setResponse(array('phone'=>$phone,'name'=>$name,'order_amount'=>$order_amount,'mark'=>$mark,'order_money'=>$order_money,'order_count'=>$order_count,'order_money_today'=>$order_money_today,'order_count_today'=>$order_count_today),200,0);
        }else{
            return self::setResponse(null,400,-4016);
        }
    }

    //快递员获取自己的信息
    public function getDelivererInfoMy(Request $request){
        $deliverer_id = self::getUserId($request);
        if($deliverer_id==''){
            return self::setResponse(null,400,-4016);
        }
        if($deliverer = Deliverer::where('deliverer_id','=',$deliverer_id)->first()){
            $phone = $deliverer->phone;
            $name = $deliverer->name;
            $order_amount= $deliverer->order_amount;
            $mark= $deliverer->mark;

            //总收入
            $order_money = $deliverer->order_money;
            //总订单
            $order_count = $deliverer->order_count;
            //今日收入
            $order_money_today = $deliverer->order_money_today;
            //今日订单
            $order_count_today = $deliverer->order_count_today;

            return self::setResponse(array('phone'=>$phone,'name'=>$name,'order_amount'=>$order_amount,'mark'=>$mark,'order_money'=>$order_money,'order_count'=>$order_count,'order_money_today'=>$order_money_today,'order_count_today'=>$order_count_today),200,0);
        }else{
            return self::setResponse(null,400,-4016);
        }
    }



}
