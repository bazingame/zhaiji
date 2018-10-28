<?php

namespace App\Http\Controllers;


use App\Models\Order;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Self_;

class PayController extends Controller
{
    private $appid = "wxc7e8bfd6875e51ae"; //小程序appid
    private $appsecret = "79ae9c82ee6b20309109b4c93505f352"; //小程序的secret
    private $MCHID = "1516158661"; //商户号id
    private $KEY = "asd59a2as1d89aa1d1a61d89aa1sd8a9"; //商户号key

    /**
     *   发起支付回调支付信息
     */
    public function payOrder($order_id, $money, $openid)
    {
        $total_fee = 100 * $money;//支付金额单位是分的，所以要乘100
//        $openid = 'oU7a05GPfgn_tIZIDsFR6Xm0tUm4';
        $appid = $this->appid;
        $MCHID = $this->MCHID; //商户号
        $KEY = $this->KEY; //商户key

        //支付统一下单
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";

        $data['appid'] = $appid;  //小程序appid
        $data['mch_id'] = $MCHID;    //商户号id
        $data['nonce_str'] = md5($MCHID . time()); //验证的支付
        $data['openid'] = $openid; //用户openid
        $data['body'] = '宅急小程序'; //微信支付对应的商家/公司主体名
        $data['out_trade_no'] = $order_id; //订单号id,用于回调改订单状态
        $data['total_fee'] = $total_fee; //支付金额，单位为分！！
        $data['spbill_create_ip'] = '134.175.42.59'; //验证ip地址，这个不用改随意
        $data['notify_url'] = "https://api.zhaiji.xyz/order/notify_url"; //微信支付成功的回调路径，要写死这个路径，记得要是小程序允许访问的路径
        $data['trade_type'] = "JSAPI"; //小程序支付，所以是JSAPI

        // --------------------以下这一串都不用改--------------------------------
        ksort($data);
        $sign_str = $this->ToUrlParams($data);
        $sign_str = $sign_str . "&key=" . $KEY;
        $data['sign'] = strtoupper(md5($sign_str));
        $xml = $this->arrayToXml($data);
        $r = $this->postXmlCurl($xml, $url, true);
        $result = json_decode($this->xml_to_json($r), true);

        if ($result['return_code'] == 'SUCCESS') {
            $sdata['appId'] = $appid;
            $sdata['timeStamp'] = '' . time();
            $sdata['nonceStr'] = md5(time() . rand() . rand() . $openid);
            $sdata['package'] = "prepay_id=" . $result['prepay_id'];
            $sdata['signType'] = "MD5";

            ksort($sdata);
            $sign_str = $this->ToUrlParams($sdata);
            $sign_str = $sign_str . "&key=" . $KEY;
            $sdata['paySign'] = strtoupper(md5($sign_str));
            $sdata['status'] = 'SUCCESS';
            return $sdata;
        } else {
            return $result;
        }
        // -----------------------都不用改-----------------------------------------------
    }

    /**
     * 【支付成功后回调】
     */
    public function suc_call(Request $request)
    {
        $data = file_get_contents('php://input');

        $msg = (array)simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);

        if ($msg['result_code'] == "SUCCESS") {
            // 支付成功这里要做的操作！
            $sql = "update ....";//可以修改订单的状态之类的
            $result = DB::update($sql);
        }
        echo '<xml>
	      <return_code><![CDATA[SUCCESS]]></return_code>
	      <return_msg><![CDATA[OK]]></return_msg>
	    </xml>';
    }

    /**
     * 【退款的接口】
     */
    public function payRefund($order_id,$total_free)
    {
        $appid = $this->appid;
        $MCHID = $this->MCHID; //商户号
        $KEY = $this->KEY; //商户key
        $url = "https://api.mch.weixin.qq.com/secapi/pay/refund"; //退款url

        $data['appid'] = $appid;  //小程序appid
        $data['mch_id'] = $MCHID;    //商户号id
        $data['nonce_str'] = md5($MCHID . time()); //验证的支付
        $data['out_trade_no'] = $order_id; //商户系统内部订单号
        $data['out_refund_no'] = 'T'.substr($order_id,1,9); //商户退款单号
        $data['total_fee'] = $total_free; //订单金额
        $data['refund_fee'] = $total_free; //退款金额


        ksort($data);
        $sign_str = $this->ToUrlParams($data);
        $sign_str = $sign_str . "&key=" . $KEY;
        $data['sign'] = strtoupper(md5($sign_str));
        $xml = $this->arrayToXml($data);
        $r = $this->postXmlCurl($xml, $url, true);
        $result = json_decode($this->xml_to_json($r), true);
        return $result;
    }

    /**
     * 【查询退款状态】
     */
    public function refundQuery($order_id){
        $appid = $this->appid;
        $MCHID = $this->MCHID; //商户号
        $KEY = $this->KEY; //商户key
        $url = "https://api.mch.weixin.qq.com/pay/refundquery"; //退款url

        $data['appid'] = $appid;  //小程序appid
        $data['mch_id'] = $MCHID;    //商户号id
        $data['nonce_str'] = md5($MCHID . time()); //验证的支付
        $data['out_trade_no'] = $order_id; //商户系统内部订单号

        ksort($data);
        $sign_str = $this->ToUrlParams($data);
        $sign_str = $sign_str . "&key=" . $KEY;
        $data['sign'] = strtoupper(md5($sign_str));
        $xml = $this->arrayToXml($data);
        $r = $this->postXmlCurl($xml, $url, true);
        $result = json_decode($this->xml_to_json($r), true);
        return $result;
    }

    /**
     * 用户post方法请求xml信息用的
     */
    public function postXmlCurl($xml, $url, $useCert = false, $second = 10)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);//严格校验2
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch,CURLOPT_SSLCERT, dirname(__FILE__) . '/cacert/apiclient_cert.pem');
        curl_setopt($ch,CURLOPT_SSLKEY, dirname(__FILE__) . '/cacert/apiclient_key.pem');
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return $error;
        }
    }

    /*
    *   用于微信支付转换认证的信息用的
    */
    public function ToUrlParams($data)
    {
        $buff = "";
        foreach ($data as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /*
    *   微信支付-数组转xml
    */
    public function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /*
    *   微信支付-数组转xml
    */
    public function xml_to_json($xmlstring)
    {
        return json_encode($this->xml_to_array($xmlstring), JSON_UNESCAPED_UNICODE);
    }

    /*
    *   post方法
    */
    public function post_url($post_data, $url)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);//严格校验2

        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);


        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /*
    * 把xml转换成array
    */
    public function xml_to_array($xml)
    {
        //return ((array) simplexml_load_string($xmlstring));
        return simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

        //return json_decode(xml_to_json($xmlstring));
    }

}