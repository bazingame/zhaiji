<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Statistics;
use Illuminate\Http\Request;

class AddressController extends Controller
{

    //添加地址
    public function addAddress(Request $request){
        $request->user_id = $this->getUserId($request);
        $check = $this->checkParam($request,array('name','address','address_detail','phone'),array('-4017','-4018','-4019','-4015'));
        if(!$check[0]){
            return self::setResponse($check[1],400,$check[1]);
        }

        $statistics = Statistics::where('id','=',1)->first();
        $address_count = $statistics['address_count'];

        $address = new Address();
        $address->address_id = "A_".sprintf("%08d",$address_count+1);
        $address->user_id = $request->user_id;
        $address->name = $request->name;
        $address->address = $request->address;
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
        $request->user_id = $this->getUserId($request);
        if($address = Address::where('address_id','=',$request->address_id)->where('user_id','=',$request->user_id)->first()){
            $address->delete();
            //删除后返回新的地址列表数据
            $addressNew = Address::where('user_id','=',$request->user_id)->select('address_id','name','address','address_detail','phone')->get();
            return self::setResponse($addressNew,200,0);
        }else{
            return self::setResponse(null,400,-4021);
        }
    }

    public function reviseAddress(Request $request){
        $request->user_id = $this->getUserId($request);
        if($address = Address::where('address_id','=',$request->address_id)->where('user_id','=',$request->user_id)->first()){
            if($request->has('name')){
                $address->name = $request->name;
            }
            if($request->has('address')){
                $address->address = $request->address;
            }
            if($request->has('address_detail')){
                $address->address_detail = $request->address_detail;
            }
            if($request->has('phone')){
                $address->phone = $request->phone;
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
