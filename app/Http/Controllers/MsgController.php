<?php

namespace App\Http\Controllers;

use App\Models\Msg;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Self_;
use Qcloud\Sms\SmsSingleSender;

class MsgController extends Controller
{
    private $appid = '1400145345';
    private $appkey = 'dac289a95435448bccd157f738cc4945';
    private $templateId = 1234;
    private $smsSign = '宅集送';


    //微信无法使用cookie，所以这里使用数据库进行存储
    public function sendVerifiedCode(Request $request){
        $check = $this->checkParam($request,array('phone','open_id'),array('-4015','-4007'));
        if(!$check[0]){
            return self::setResponse($check[1],400,$check[1]);
        }
        $phone = $request->phone;
        $open_id = $request->open_id;
        $requestTime = time();
        $code = rand(100000,999999);

        //次数限制
        if($oldMsg = Msg::where('open_id','=',$open_id)->where('phone','=',$phone)->orderBy('created_at','DESC')->first()){
            $oldTime = strtotime($oldMsg->created_at);
            if($requestTime < $oldTime +60)
                return self::setResponse(null,400,-4039);
        }

        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);
            $params = [$code];//数组具体的元素个数和模板中变量个数必须一致，例如事例中 templateId:5678对应一个变量，参数数组中元素个数也必须是一个
            $result = $ssender->sendWithParam("86", $phone[0], $this->templateId,
                $params, $this->smsSign, "", "");  // 签名参数未提供或者为空时，会使用默认签名发送短信
            $rsp = json_decode($result,true);
//            return  self::__echo($rsp);

            if(TRUE){
                $msg = new Msg();
                $msg->phone = $phone;
                $msg->code = $code;
                $msg->created_at = $requestTime;
                $msg->openid = $open_id;
                if($msg->save()){
                    return self::setResponse(null,200,0);
                }else{
                    return self::setResponse(null,500,0);
                }
            }else{
                //TODO
                return self::setResponse(null,400,0);
            }
        } catch(\Exception $e) {
            return self::setResponse(null,500,-4038);
        }
    }

    public function verifyVerifiedCode(Request $request){
        $check = $this->checkParam($request,array('phone','open_id','captcha'),array('-4015','-4007','-4040'));
        if(!$check[0]){
            return self::setResponse($check[1],400,$check[1]);
        }
        $phone = $request->phone;
        $open_id = $request->open_id;
        $code = $request->captcha;

        if($record = Msg::where('open_id','=',$open_id)->where('phone','=',$phone)->orderBy('created_at','DESC')->first()){
            $oldTime = strtotime($record->created_at);
            if(strtotime(now()) > $oldTime + 60)
                return self::setResponse(null,400,-4041);
            if($code==$record->code){
                return self::setResponse(null,200,0);
            }else{
                return self::setResponse(null,400,-4042);
            }
        }else{
            return self::setResponse(null,400,-4043);
        }

    }
}
