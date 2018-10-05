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

    //获取快递员电话
    public function getDelivererPhone(Request $request){
        $deliverer_id = $request->route('deliverer_id');
        if($deliverer_id==''){
            return self::setResponse(null,400,-4016);
        }
        if($deliverer = Deliverer::where('deliverer_id','=',$deliverer_id)->first()){
            $phone = $deliverer->phone;
            return self::setResponse(array('phone'=>$phone),200,0);
        }else{
            return self::setResponse(null,400,-4016);
        }
    }
}
