<?php

namespace App\Http\Controllers;

use App\Models\Statistics;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //注册
    public function addUser(Request $request)
    {
        if (!$request->has("open_id") || !$request->has("phone") ||!$request->has("headimg_url")) {
            return self::setResponse(null,400,-4001);
        }
        //微信号已注册
        if(User::where('open_id','=',$request->open_id)->first()){
            return self::setResponse(null,400,-4004);
        }
        //号码已经注册
        if(User::where('phone','=',$request->phone)->first()){
            return self::setResponse(null,400,-4003);
        }
        //TODO 号码格式验证

        $statistics = Statistics::where('id','=',1)->first();
        $user_count = $statistics['user_count'];

        $user = new User();
        $user->open_id = $request->open_id;
        $user->phone = $request->phone;
        $user->headimg_url = $request->headimg_url;
        $user->user_id = "U_".sprintf("%08d",$user_count+1);
        $user->register_time = time();
        if($user->save()){
            $statistics->user_count++;
            $statistics->save();
            return self::setResponse(null,200,0);
        }else{
            return self::setResponse(null,500,-4020);
        }
    }

    //修改手机号
    public function reviseUser(Request $request){
        $user_id = $request->route('user_id');

        if($user= User::where('user_id','=',$user_id)->first()){
            //修改phone
            if($request->has('phone')){
                if(User::where('phone','=',$request->phone)->first()){
                    return self::setResponse(null,400,-4003);
                }else{
                    //TODO 验证手机格式
                    $user->phone = $request->phone;
                    if($user->save()){
                        return self::setResponse(null,200,0);
                    }else{
                        return self::setResponse(null,400,-4006);
                    }
                }
            }
            //修改其他
        }else{
            return self::setResponse(null,400,-4002);
        }
    }

    public function deleteUSer(Request $request){


    }
}
