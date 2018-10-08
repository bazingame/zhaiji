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
            return self::setResponse(array('phone'=>$phone,'name'=>$name,'order_amount'=>$order_amount,'mark'=>$mark),200,0);
        }else{
            return self::setResponse(null,400,-4016);
        }
    }


}
