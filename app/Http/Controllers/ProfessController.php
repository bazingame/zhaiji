<?php

namespace App\Http\Controllers;

use App\Models\Deliverer;
use App\Models\Statistics;
use App\Models\User;
use Illuminate\Http\Request;

class ProfessController extends Controller
{
    public function toDeliverer(Request $request){
        $user_id = $request->route('user_id');
        if($user = User::where('user_id','=',$user_id)->first()){

            $new_deliver = new Deliverer();
            $new_deliver->open_id = $user->open_id;
            $new_deliver->phone = $user->phone;
            $new_deliver->register_time = $user->register_time;
            $new_deliver->name = 'deliverer';//TODO 添加昵称
            $new_deliver->mark = 0;//TODO 添加昵称
            $new_deliver->order_count = 0;
            $new_deliver->order_money = 0;
            $new_deliver->order_money_today = 0;
            $new_deliver->order_count_today = 0;


            $statistics = Statistics::where('id','=',1)->first();
            $deliverer_count = $statistics['deliverer_count'];
            $new_deliver->deliverer_id = "D_".sprintf("%08d",$deliverer_count+1);


            if($new_deliver->save()){
                $statistics->deliverer_count++;
                $statistics->save();

                //删除原有账户
                $user->delete();
                return self::setResponse($new_deliver,200,0);
            }else{
                return self::setResponse(null,500,-4020);
            }
        }else{
            return self::setResponse(null,400,-4008);
        }
    }

    public function toUser(Request $request){
        $deliverer_id = $request->route('deliverer_id');
        if($deliverer = Deliverer::where('deliverer_id','=',$deliverer_id)->first()){

            $new_user = new User();
            $new_user->open_id = $deliverer->open_id;
            $new_user->phone = $deliverer->phone;
            $new_user->register_time = $deliverer->register_time;

            $statistics = Statistics::where('id','=',1)->first();
            $user_count = $statistics['user_count'];
            $new_user->headimg_url = $request->headimg_url;
            $new_user->user_id = "U_".sprintf("%08d",$user_count+1);


            /*暂时不收集用户头像*/
            $new_user->headimg_url = env("APP_URL")."/storage/images/boy.png";


            if($new_user->save()){
                $statistics->user_count++;
                $statistics->save();

                //删除原有账户
                $deliverer->delete();
                return self::setResponse($new_user,200,0);
            }else{
                return self::setResponse(null,500,-4020);
            }
        }else{
            return self::setResponse(null,400,-4016);
        }
    }



}
