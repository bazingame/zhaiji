<?php

namespace App\Http\Controllers;

use App\Models\Msg;
use App\Models\User;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Self_;
use Qcloud\Sms\SmsSingleSender;

class MsgController extends Controller
{
    private $appid = '1400145345';
    private $appkey = 'dac289a95435448bccd157f738cc4945';
    private $templateId = 205837;
    private $smsSign = '青木信息服务部';
    private $smsErrArr = array('1015'=>'-4044',//手机号在黑名单库
                                '1016'=>'-4045',//手机号格式错误
                                '1022'=>'-4046',//业务短信日下发条数超过设定的上限
                                '1023'=>'-4046',//业务短信日下发条数超过设定的上限
                                '1024'=>'-4046',//业务短信日下发条数超过设定的上限
                                '1025'=>'-4046',//业务短信日下发条数超过设定的上限
                                '1026'=>'-4046',//业务短信日下发条数超过设定的上限
                                '1029'=>'-4047'//营销短信发送时间限制
                                );

    //微信无法使用cookie，所以这里使用数据库进行存储
    public function sendVerifiedCode(Request $request){
        $check = $this->checkParam($request,array('phone','open_id'),array('-4015','-4007'));
        if(!$check[0]){
            return self::setResponse($check[1],400,$check[1]);
        }
        $phone = array($request->phone);
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
            /*{   返回数据
                "result": 0,
                "errmsg": "OK",
                "ext": "",
                "sid": "2019:-6856560551571033852",
                "fee": 2
            }*/

            //发送成功
            if($rsp['result']==0){
                $msg = new Msg();
                $msg->phone = $phone[0];
                $msg->code = $code;
                $msg->created_at = $requestTime;
                $msg->open_id = $open_id;
                $msg->status = 0;
                if($msg->save()){
                    return self::setResponse(null,200,0);
                }else{
                    //短信记录失败
                    return self::setResponse(null,500,-4044);
                }
            }else{
                //短信发送失败 具体值
                $msgErrCode = $rsp['result'];
                $errcode = $this->smsErrArr[$msgErrCode];
                return self::setResponse(null,400,$errcode);
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
            if(strtotime(now()) > $oldTime + 180)//三分钟过期
                return self::setResponse(null,400,-4041);
            if($code==$record->code){
                //顺便注册登录该账户
                //注册
                $addRes = UserController::addUSer($request);
                if($addRes->original['errcode']==0&&$addRes->original['status']==200){
                    //登录
                    $loginRes = LoginController::login($request);
                    return self::setResponse(get_object_vars($loginRes)['original']['data'],200,0);
                }else{
                    return $addRes;
                }

            }else{
                return self::setResponse(null,400,-4042);
            }
        }else{
            return self::setResponse(null,400,-4043);
        }

    }
}
