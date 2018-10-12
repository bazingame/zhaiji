<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Deliverer;
use App\Models\Express;
use App\Models\Order;
use App\Models\Statistics;
use App\Models\User;
use Illuminate\Http\Request;
use Mockery\Test\Generator\StringManipulation\Pass\CallTypeHintPassTest;
use phpDocumentor\Reflection\Types\Self_;

class OrderController extends Controller
{
    private $statusArr = array('1'=>'未接单','2'=>'已接单','3'=>'已完成','4'=>'已取消','5'=>'申请取消中','6'=>'取消失败');

    //获取订单列表(支付成功的)
    public function getOrder(Request $request){
        $user_id = $this->getUserId($request);
        if($user_id==null){
            return self::setResponse(null,400,-4050);
        }
        if($orderList = Order::join('addresses',function ($join) {
            $join->on('orders.address_id', '=', 'addresses.address_id');
            })->select('order_id','express_id','express_address','order_time','deliverer_id','status','money','package_id','mark_status','addresses.name','addresses.address','addresses.address_detail','addresses.phone')->where('orders.user_id','=',$user_id)->where('orders.pay_status','=',1)->orderby('order_time','desc')->get()){
            foreach ($orderList as $k => $v){
                $v['express'] = Express::where('express_id','=',$v['express_id'])->select('name')->first()['name'];
                unset($v['express_id']);
                $v['status'] = $this->statusArr[$v['status']];
            }
            return self::setResponse($orderList,200,0);
        }
    }

    //获取订单详细信息
    public function getOneOrder(Request $request){
        $user_id = $this->getUserId($request);
        $order_id = $request->route('order_id');
        //如果是配送员
        if(substr($user_id,0,1)=='D'){
            if($orderDetail = Order::join('addresses',function ($join) {
                $join->on('orders.address_id', '=', 'addresses.address_id');
            })->where('orders.deliverer_id','=',$user_id)->where('orders.order_id','=',$order_id)->first())
            {
                $orderDetail['express'] = Express::where('express_id','=',$orderDetail['express_id'])->select('name')->first()['name'];
                unset($orderDetail['express_id']);
                unset($orderDetail['user_id']);
                $orderDetail['status'] = $this->statusArr[$orderDetail['status']];
                return self::setResponse($orderDetail,200,0);
            }
        //如果是普通用户
        }else{
            if($orderDetail = Order::join('addresses',function ($join) {
                $join->on('orders.address_id', '=', 'addresses.address_id');
            })->where('orders.user_id','=',$user_id)->where('orders.order_id','=',$order_id)->first())
            {
                $orderDetail['express'] = Express::where('express_id','=',$orderDetail['express_id'])->select('name')->first()['name'];
                unset($orderDetail['express_id']);
                unset($orderDetail['user_id']);
                $orderDetail['status'] = $this->statusArr[$orderDetail['status']];

                //如果有申请记录且未退款完成则进行查询更新
                if($orderDetail->refund_apply==1&&$orderDetail->status=='已取消'&&$orderDetail->refund_status=='PROCESSING'){
                    //查看更新退款状态
                    $pay = new PayController();
                    $payRefund = $pay->refundQuery($order_id);
                    if($payRefund['return_code']=='SUCCESS'){

                        $orderOrigin = Order::join('addresses',function ($join) {
                            $join->on('orders.address_id', '=', 'addresses.address_id');
                        })->where('orders.user_id','=',$user_id)->where('orders.order_id','=',$order_id)->first();

                        $orderOrigin->refund_status = $payRefund['refund_status_0'];
                        $orderOrigin->save();
                        $refund_status_ENG = $payRefund['refund_status_0'];
                    }
                }else{
                    $refund_status_ENG = $orderDetail['refund_status'];
                }
                if($orderDetail->refund_apply==1){
                    switch ($refund_status_ENG){
                        case 'SUCCESS':
                            $refund_status_CH = '退款成功';
                            break;
                        case 'REFUNDCLOSE':
                            $refund_status_CH = '退款关闭';
                            break;
                        case 'PROCESSING':
                            $refund_status_CH = '退款处理中';
                            break;
                        case 'CHANGE':
                            $refund_status_CH = '退款异常';
                            break;
                        default:
                            $refund_status_CH = '退款异常';
                            break;
                    }
                    $orderDetail['refund_status'] = $refund_status_CH;
                }
                return self::setResponse($orderDetail,200,0);
            }
        }
    }

    //下单
    public function addOrder(Request $request){
        $request->user_id = $this->getUserId($request);
        $check = $this->checkParam($request,array('address_id','express_id','express_address','package_id','insurance','money','package_size'),array('-4009','-4010','-4058','-4011','-4012','-4031','-4013'));
        if(!$check[0]){
            return self::setResponse(null,400,$check[1]);
        }

        //检查收货地址正确性
        $addressArr = Address::getAddressId($request->user_id);
        if(!in_array($request->address_id,$addressArr)){
            return self::setResponse(null,400,-4009);
        }

        //TODO 检查物流代号

        //存入数据库
        $order = new Order();
        $order->user_id = $request->user_id;
        $order->address_id = $request->address_id;
        $order->express_id = $request->express_id;
        $order->express_address = $request->express_address;
        $order->package_id = $request->package_id;
        $order->insurance = $request->insurance;
        $order->valuation = $request->valuation;
        $order->note = $request->note;
        $order->money = $request->money;
        $order->package_size = $request->package_size;
        $order->status = 1;
        $order->mark_status = 0;
        $order->order_time = Date("Y-m-d H:i:s",time());

        $statistics = Statistics::where('id','=',1)->first();
        $order_count = $statistics['order_count'];
        $order->order_id = "O_".sprintf("%08d",$order_count+1);

        if($order->save()){
            $statistics = Statistics::where('id','=',1)->first();
            $statistics->order_count++;
            $statistics->save();

            //获取用户open_id
            $user = User::where('user_id','=',$request->user_id)->first();
            $open_id = $user->open_id;
            //微信服务器支付请求
            $pay = new PayController();
            $payRes = $pay->payOrder($order->order_id,$request->money,$open_id);

            if(isset($payRes['status'])&&$payRes['status']=='SUCCESS'){
                $payRes['order_id'] = $order->order_id;
                return self::setResponse($payRes,200,0);
            }else{
                return self::setResponse($payRes,400,-4053);
            }
        }else{
            return self::setResponse(null,500,-4022);
        }

    }

    //修改支付状态
    public function revisePayStatus(Request $request){
        $user_id = $this->getUserId($request);
        $order_id = $request->order_id;
        //这里对外统一为guagua参数，代表支付状态
        $status = $request->guagua;
        if($order = Order::where('order_id','=',$order_id)->where('user_id','=',$user_id)->first()){
            //只有状态为未接单才可修改
            //'1'=>'未接单','2'=>'已接单','3'=>'已完成','4'=>'已取消','5'=>'申请取消中','6'=>'取消失败'

            //直接取消//
            if($order->status==1&&$order->pay_status==0){
                //ss代表成功
                if($status=='ss'){
                    $order->pay_status = 1;
                //ss代表失败
                }else if($status=='gg'){
                    $order->pay_status = -1;
                }
                if($order->save()){
                    return self::setResponse(null,200,0);
                }else{
                    return self::setResponse(null,400,-4006);
                }
            }else{
                return self::setResponse(null,400,-4059);
            }
        }else{
            return self::setResponse(null,400,-4025);
        }




    }

    //申请取消
    public function applyCancelOrder(Request $request){
        $user_id = $this->getUserId($request);
        $order_id = $request->order_id;
        if($order = Order::where('order_id','=',$order_id)->where('user_id','=',$user_id)->first()){
            //只有状态为未接单、已接单才可以取消
            //状态未未接单直接取消，状态为已接单申请取消
            //'1'=>'未接单','2'=>'已接单','3'=>'已完成','4'=>'已取消','5'=>'申请取消中','6'=>'取消失败'

            //直接取消//TODO 退款
            if($order->status==1){
                $order->status = 4;
                $order->cancel_reason = $request->cancel_reason;

                //申请退款
                $pay = new PayController();
                $payRefund = $pay->payRefund($order_id,$order->money*100);
                //退款请求为真
                $order->refund_apply = 1;
                if($payRefund['return_code']=='SUCCESS'&&$payRefund['result_code']=='SUCCESS'){
                    $order->refund_status = 'PROCESSING';
                    if($order->save()){
                        return self::setResponse(array('status_code'=>'4','status'=>'已取消'),200,0);
                    }else{
                        return self::setResponse(null,400,-4006);
                    }
                }else{
                    $order->refund_status = $payRefund['err_code'];
                    if($order->save()){
                        return self::setResponse(array('status_code'=>'4','status'=>'已取消'),200,0);
                    }else{
                        return self::setResponse(null,400,-4006);
                    }
                }

            //申请取消，待确认
            }elseif($order->status==2){
                $order->status = 5;
                $order->cancel_reason = $request->cancel_reason;
                if($order->save()){
                    return self::setResponse(array('status_code'=>'5','status'=>'申请取消中'),200,0);
                }else{
                    return self::setResponse(null,400,-4006);
                }
            }else{
                return self::setResponse(null,400,-4028);
            }
        }else{
            return self::setResponse(null,400,-4025);
        }
    }

    //取消订单(快递员操作)
    public function cancelOrder(Request $request){
        $deliverer_id = $this->getUserId($request);
        if(substr($deliverer_id,0,1)!='D'){
            return self::setResponse(null, 400, -4051);
        }
        $order_id = $request->order_id;
        if($order = Order::where('order_id','=',$order_id)->where('deliverer_id','=',$deliverer_id)->first()){
            //只有状态为申请取消中才可以取消
            //'1'=>'未接单','2'=>'已接单','3'=>'已完成','4'=>'已取消','5'=>'申请取消中','6'=>'取消失败'
            //直接取消
            if($order->status==5){
                $order->status = 4;

                //申请退款
                $pay = new PayController();
                $payRefund = $pay->payRefund($order_id,$order->money*100);
                //退款请求为真
                $order->refund_apply = 1;
                if($payRefund['return_code']=='SUCCESS'&&$payRefund['result_code']=='SUCCESS'){
                    $order->refund_status = 'PROCESSING';
                    if($order->save()){
                        return self::setResponse(array('status_code'=>'4','status'=>'已取消'),200,0);
                    }else{
                        return self::setResponse(null,400,-4006);
                    }
                }else{
                    $order->refund_status = $payRefund['err_code'];
                    if($order->save()){
                        return self::setResponse(array('status_code'=>'4','status'=>'已取消'),200,0);
                    }else{
                        return self::setResponse(null,400,-4006);
                    }
                }

            }else{
                return self::setResponse(null,400,-4028);
            }
        }else{
            return self::setResponse(null,400,-4025);
        }
    }

    //拒绝取消订单(快递员操作)
    public function refuseCancelOrder(Request $request){
        $deliverer_id = $this->getUserId($request);
        if(substr($deliverer_id,0,1)!='D'){
            return self::setResponse(null, 400, -4051);
        }
        $order_id = $request->order_id;
        if($order = Order::where('order_id','=',$order_id)->where('deliverer_id','=',$deliverer_id)->first()){
            //只有状态为申请取消中才可以取消
            //'1'=>'未接单','2'=>'已接单','3'=>'已完成','4'=>'已取消','5'=>'申请取消中','6'=>'取消失败'
            //直接取消
            if($order->status==5){
                $order->status = 6;
                if($order->save()){
                    return self::setResponse(array('status_code'=>'6','status'=>'已拒绝'),200,0);
                }else{
                    return self::setResponse(null,400,-4006);
                }
            }else{
                return self::setResponse(null,400,-4052);
            }
        }else{
            return self::setResponse(null,400,-4025);
        }
    }

    //确认订单
    public function confirmOrder(Request $request){
        $deliverer_id = $this->getUserId($request);
        if(substr($deliverer_id,0,1)!='D'){
            return self::setResponse(null, 400, -4051);
        }
        $order_id = $request->route('order_id');
        if($order = Order::where('order_id','=',$order_id)->where('deliverer_id','=',$deliverer_id)->first()){
            //只有是已接单状态的才可以修改
            if($order->status==2){
                $order->status = 3;
                $order->finish = Date("Y-m-d H:i:s",time());

                //更新配送员的个人订单情况
                $deliverer = Deliverer::where('deliverer_id','=',$deliverer_id);
                //总订单+1
                $deliverer->order_count += 1;
                //总收入+money
                $deliverer->order_money += $order->money;
                $update_time = substr($deliverer->update_time,0,10);
                //上一次订单不是今天的  清零 今日订单+1 今日订单+money
                if($update_time!=Date("Y-m-d",time())){
                    $deliverer->order_count_today = 1;
                    $deliverer->order_money_today = $order->money;
                }

                if($order->save()&&$deliverer->save()) {
                    return self::setResponse(array('status_code'=>'3','status'=>'已完成'), 200, 0);
                }else{
                    return self::setResponse(null, 500, -4006);
                }
            }else{
                return self::setResponse(null, 400, -4026);
            }
        }else{
            return self::setResponse(null, 400, -4025);
        }
    }

    //评价订单
    public function markOrder(Request $request){
        $user_id = $this->getUserId($request);
        $order_id = $request->order_id;
        if($order = Order::where('order_id','=',$order_id)->where('user_id','=',$user_id)->first()){
            //只有是已完成状态的才可以修改
            if($order->status==3){
                $order->mark_status = 1;
                $order->mark = $request->mark;
                if($order->save()) {
                    return self::setResponse(null, 200, 0);
                }else{
                    return self::setResponse(null, 500, -4006);
                }
            }else{
                return self::setResponse(null, 400, -4029);
            }
        }else{
            return self::setResponse(null, 400, -4025);
        }
    }

    //接单(快递员操作)
    public function receiveOrder(Request $request){
        $deliverer_id = $this->getUserId($request);
        if(substr($deliverer_id,0,1)!='D'){
            return self::setResponse(null, 400, -4030);
        }
        $order_id = $request->order_id;
        if($order = Order::where('order_id','=',$order_id)->first()){
            //只有是未接单状态的才可以修改
            if($order->status==1){
                $order->status = 2;
                $order->take_order_time = Date("Y-m-d H:i:s",time());
                $order->deliverer_id = $deliverer_id;
                if($order->save()) {
                    return self::setResponse(null, 200, 0);
                }else{
                    return self::setResponse(null, 500, -4006);
                }
            }else{
                return self::setResponse(null, 400, -4027);
            }
        }else{
            return self::setResponse(null, 400, -4025);
        }
    }

    //获取所有未接单
    public function getUnReceivedOrder(Request $request){
        $start = $request->route('start');
        $limit = $request->route('limit');
        $unReceivedOrder = Order::join('addresses',function ($join) {
                    $join->on('orders.address_id', '=', 'addresses.address_id');
                })->select('order_id','express_id','express_address','package_size','addresses.address','addresses.address_detail','note','order_time')->where('status','=','1')->where('pay_status','=',1)->orderBy('order_time','desc')->offset($start)->limit($limit)->get();
        foreach ($unReceivedOrder as $k => $v){
            $v['express'] = Express::where('express_id','=',$v['express_id'])->select('name')->first()['name'];
            unset($v['express_id']);
        }
        return self::setResponse($unReceivedOrder, 200, 0);
    }

    //获取所有自己接的单
    public function getReceivedOrder(Request $request){
        $deliverer_id = $this->getUserId($request);
        if(substr($deliverer_id,0,1)!='D'){
            return self::setResponse(null, 400, -4051);
        }
        $receivedOrder = Order::join('addresses',function ($join) {
            $join->on('orders.address_id', '=', 'addresses.address_id');
        })->select('order_id','express_id','express_address','package_size','package_id','status','money','addresses.address','addresses.address_detail','note','order_time')->where('deliverer_id','=',$deliverer_id)->orderBy('order_time','desc')->get();
        foreach ($receivedOrder as $k => $v){
            $v['express'] = Express::where('express_id','=',$v['express_id'])->select('name')->first()['name'];
            unset($v['express_id']);
            $v['status'] = $this->statusArr[$v['status']];
        }
        return self::setResponse($receivedOrder, 200, 0);
    }


}


