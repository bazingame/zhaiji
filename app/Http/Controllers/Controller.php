<?php

namespace App\Http\Controllers;

use http\Env\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function __echo($data,$status=200){
        $response = new Response($data);
        return $response->setStatusCode($status);
    }

    public static function setResponse($data, $status, $errcode)
    {
        $body = array(
            'errcode' => $errcode,
            'status' => $status,
            'errmsg' => self::__getErrMsg($errcode),
            'data' => $data
        );
        $response = new Response($body);
        return $response->setStatusCode($status);
    }

    // 解析路由路径中的参数
    public function route_parameter($name, $default = null)
    {
        $routeInfo = app('request')->route();
        return array_get($routeInfo[2], $name, $default);
    }


    // errcode 对应的 errmsg
    protected static function __getErrMsg($errcode)
    {
        $msgForCode = [
            // 通用部分
            0 => '请求成功',
            -4001 => '缺失参数',
            -4002 => '用户未注册',
            -4003 => '手机号已注册',
            -4004 => '微信号已注册',
            -4005 => '注册失败',
            -4006 => '修改失败',
            -4007 => 'open_id参数错误',
            -4008 => 'user_id参数错误',
            -4009 => 'address_id参数错误',
            -4010 => 'express_id参数错误',
            -4011 => 'package_id参数错误',
            -4012 => 'insurance参数错误',
            -4013 => 'package_size参数错误',
            -4014 => 'status参数错误',
            -4015 => 'phone参数错误',
//            -4016 => 'province参数错误',
            -4017 => 'name参数错误',
            -4018 => 'address参数错误',
            -4019 => 'address_detail参数错误',
            -4020 => '添加失败',
            -4021 => '删除失败',
            -4022 => '下单失败',
            -4023 => '鉴权失败',
            -4024 => '接单失败',
            -4025 => 'order_id参数错误',
            -4026 => '不可确认状态',
            -4027 => '不可接单状态',
            -4028 => '不可取消状态',
            -4029 => '不可评价状态',
            -4030 => '此账户不可接单',
            -4031 => 'money参数错误',
            -4032 => 'code无效',
            -4033 => '频率限制，每个用户每分钟100次',
            -4034 => '系统繁忙，此时请开发者稍候再试',
            -4035 => '未知错误',
            -4036 => 'code参数错误',
            -4037 => 'code已被使用',
            -4038 => '短信发送失败',
            -4039 => '一分钟内只可发送一次',
            -4040 => 'captcha参数错误',
            -4041 => '验证码失效',
            -4042 => '验证码错误',
            -4043 => '无发送记录',
            -4044 => '手机号在黑名单库',
            -4045 => '手机号格式错误',
            -4046 => '短信发送频繁',
            -4047 => '发送时间限制',
            -4048 => '获取地点失败',
            -4049 => '搜索地点失败',

        ];
        return $msgForCode[$errcode];
    }

    //判断请求参数是否合格 有一个不合格就返回
    public function checkParam(\Illuminate\Http\Request $request,$paramArr,$msgCodeArr){
        foreach ($paramArr as $k => $value){
            if(!$request->has($value)||$request->$value==''){
                return array(false,$msgCodeArr[$k]);
            }
        }
        return array(true,null);
    }

    //获取user_id
    public function getUserId(\Illuminate\Http\Request  $request){
        $header = $request->headers->all();
        return decrypt($header['authorization'][0]);
    }
}
