<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Statistics;
use Illuminate\Http\Request;

class AddressController extends Controller
{

    //添加地址
    public function addAddress(Request $request){
        $check = $this->checkParam($request,array('user_id','province','city','town','address_detail','phone'),array('-4008','-4016','-4017','-4018','-4019','-4015'));
        if(!$check[0]){
            return self::setResponse($check[1],400,$check[1]);
        }

        $statistics = Statistics::where('id','=',1)->first();
        $address_count = $statistics['address_count'];

        $address = new Address();
        $address->address_id = "A_".sprintf("%08d",$address_count+1);
        $address->user_id = $request->user_id;
        $address->province = $request->province;
        $address->city = $request->city;
        $address->town = $request->town;
        $address->address_detail = $request->address_detail;
        $address->phone = $request->phone;
        //TODO 检查phone正确性
        if($address->save()){
            $statistics->address_count++;
            $statistics->save();
            return self::setResponse(null,200,0);
        }else{
            return self::setResponse(null,400,-4020);
        }
    }

    public function deleteAddress(Request $request){
        if($address = Address::where('address_id','=',$request->address_id)->first()){
            $address->delete();
            return self::setResponse(null,200,0);
        }else{
            return self::setResponse(null,400,-4021);
        }
    }

    public function reviseAddress(Request $request){
        if($address = Address::where('address_id','=',$request->address_id)->first()){
            if($request->has('province'))
                $address->province = $request->province;
            if($request->has('city')){
                $address->city = $request->city;
            }
            if($request->has('town')){
                $address->town = $request->town;
            }
            if($request->has('address_detail')){
                $address->address_detail = $request->address_detail;
            }

            if($address->save()){
                return self::setResponse(null,200,0);
            }else{
                return self::setResponse(null,400,-4006);
            }
        }else{
            return self::setResponse(null,400,-4009);
        }
    }

}
