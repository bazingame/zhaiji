<?php

namespace App\Http\Controllers;

use App\Libs\AESMcrypt;
use App\Models\Address;
use App\Models\Deliverer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use phpDocumentor\Reflection\Types\Null_;

class LoginController extends Controller
{

    public static function login(Request $request){
        //TODO 用户判断
        if(!$request->has('open_id')||$request->open_id==''){
           return  self::setResponse(null,400,-4007);
        }
        $open_id = $request->open_id;
        //open_id与user_id都是用来区别用户的唯一字段
        if($user = User::where('open_id','=',$open_id)->first()){
            $address = Address::where('user_id','=',$user->user_id)->select('address_id','name','address','address_detail','phone')->get();
//            $orders = Order::where('user_id','=',$user->user_id)->get();
            $data = array(
                'authorization'=>encrypt($user->user_id),
                'headimg_url'=>$user->headimg_url,
                'addresses'=>$address,
                'phone'=>$user->phone,
                'type'=>'user',
            );
           return self::setResponse($data,200,0);
           //快递员
        }elseif($deliverer = Deliverer::where('open_id','=',$open_id)->first()){
            $data = array(
                'authorization'=>encrypt($deliverer->deliverer_id),
                'type'=>'deliverer',
            );
            return self::setResponse($data,200,0);
            //未注册
        }else{
            return self::setResponse(null,200,-4002);
        }
    }

    public function getOpenId(Request $request){
        $AppID = 'wxc7e8bfd6875e51ae';
        $AppSecret = '79ae9c82ee6b20309109b4c93505f352';
        $check = $this->checkParam($request,array('code'),array('-4036'));
        if(!$check[0]){
            return self::setResponse($check[1],400,$check[1]);
        }
        $code = $request->code;
        $code2SessionUrl = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$AppID.'&secret='.$AppSecret.'&js_code='.$code.'&grant_type=authorization_code';
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$code2SessionUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $res = curl_exec($ch);
        curl_close($ch);
        $resArr = json_decode($res,true);

        if(isset($resArr['openid'])){
            return self::setResponse(array('open_id'=>$resArr['openid']),200,0);
        }else if(isset($resArr['errcode'])&&$resArr['errcode']=='40029'){
            return self::setResponse(null,400,-4032);
        }else if(isset($resArr['errcode'])&&$resArr['errcode']=='45011'){
            return self::setResponse(null,400,-4033);
        }else if(isset($resArr['errcode'])&&$resArr['errcode']=='40163'){
            return self::setResponse(null,400,-4037);
        }else if(isset($resArr['errcode'])&&$resArr['errcode']=='-1'){
            return self::setResponse(null,500,-4034);
        }else{
            return self::setResponse(null,500,-4035);
        }
    }
}
