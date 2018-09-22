<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
        $msgForCode = array(
            // 通用部分
            0 => 'Success',                                      // 请求成功
            -4001 => 'Missing parameters',                       // 缺失参数
            -4002 => 'Missing password',                         // 缺失密码

        );
        return $msgForCode[$errcode];
    }

}
