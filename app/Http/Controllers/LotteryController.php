<?php

namespace App\Http\Controllers;

use App\Models\Award;
use App\Models\AwardRecord;
use App\Models\Lottery;
use App\Models\Order;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Array_;

class LotteryController extends Controller
{
    //
    public function getLottery(Order $order)
    {

        //根据次数判断是否有可用的次数类型抽奖
        if ($lottery_list = Lottery::where('end_time', '>', Date('Y-m-d H:i:s', time()))->where('type','=','num')->where('remain_total_count','>',0)->get()) {
            //获取下单次数
            $order_record_num = count(Order::where('user_id','=',$order->user_id)->get());
            $lottery = null;
            foreach ($lottery_list as $key => $val){
                if($order_record_num==$val['order_count_get_chance']){
                    $lottery = $val;
                    break;
                }
            }
            //下单次数没有对应奖品
            if($lottery!=null){
                $remain_total_count = $lottery->remain_total_count;
                $award_list = Award::where('lottery_id', '=', $lottery->id)->get();
                //过滤可用奖品
                $total_probability = 0;
                $avilable_list_count = 0;
                foreach ($award_list as $k => $value) {
                    if ((int)$value['remain_count'] > 0) {
                        $avilable_award[$avilable_list_count]['index'] = (int)$value['award_index'];
                        $avilable_award[$avilable_list_count]['probability'] = ($value['remain_count'] / $remain_total_count) * 100;
                        $total_probability += ($value['remain_count'] / $remain_total_count) * 100;
                        $avilable_list_count++;
                    }
                }
                //计算概率模型
                $randNum = rand(1, $total_probability);
                $lowNum = 1;
                $final_select = 0;
                for ($i = 0; $i < $avilable_list_count; $i++) {
                    $highNum = $lowNum + $avilable_award[$i]['probability'];
                    if ($lowNum <= $randNum && $randNum <= $highNum) {
                        $final_select =  $avilable_award[$i]['index'];
                        break;
                    } else {
                        $lowNum = $highNum;
                    }
                }
                //更新单个奖品和lottery
                $award_confer = Award::where('lottery_id', '=', $lottery->id)->where('award_index', '=', $final_select)->first();
                $award_confer->remain_count = $award_confer->remain_count - 1;
                $award_confer->save();
                $lottery->remain_total_count = $lottery->remain_total_count-1;
                $lottery->save();

                //记录
                $award_record = new AwardRecord();
                $award_record->lottery_id = $lottery->id;
                $award_record->user_id = $order->user_id;
                $award_record->order_id = $order->order_id;
                $award_record->award_index = $final_select;
                $award_record->save();

                $notice = json_decode($lottery->notice,true);
                return array('lottery_type' => 'time', 'award_list' => $award_list, 'award_index' => $final_select,'notice'=>$notice,'sub_title'=>$lottery->sub_title);
            }
        }


        //根据时间判断是否有可用的节日类型抽奖
        if ($lottery = Lottery::where('end_time', '>', Date('Y-m-d H:i:s', time()))->where('type','=','time')->where('remain_total_count','>',0)->first()) {
            $remain_total_count = $lottery->remain_total_count;
            //检查抽奖记录
            //有记录
            if(!$lottery_record = AwardRecord::where('lottery_id','=',$lottery->id)->where('user_id','=',$order->user_id)->first()){
                $award_list = Award::where('lottery_id', '=', $lottery->id)->get();
                //过滤可用奖品
                $total_probability = 0;
                $avilable_list_count = 0;
                foreach ($award_list as $k => $value) {
                    if ((int)$value['remain_count'] > 0) {
                        $avilable_award[$avilable_list_count]['index'] = (int)$value['award_index'];
                        $avilable_award[$avilable_list_count]['probability'] = ($value['remain_count'] / $remain_total_count) * 100;
                        $total_probability += ($value['remain_count'] / $remain_total_count) * 100;
                        $avilable_list_count++;
                    }
                }
                //计算概率模型
                $randNum = rand(1, $total_probability);
                $lowNum = 1;
                $final_select = 0;
                for ($i = 0; $i < $avilable_list_count; $i++) {
                    $highNum = $lowNum + $avilable_award[$i]['probability'];
                    if ($lowNum <= $randNum && $randNum <= $highNum) {
                        $final_select =  $avilable_award[$i]['index'];
                        break;
                    } else {
                        $lowNum = $highNum;
                    }
                }

                //更新单个奖品和lottery
                $award_confer = Award::where('lottery_id', '=', $lottery->id)->where('award_index', '=', $final_select)->first();
                $award_confer->remain_count = $award_confer->remain_count - 1;
                $award_confer->save();
                $lottery->remain_total_count = $lottery->remain_total_count-1;
                $lottery->save();

                //记录
                $award_record = new AwardRecord();
                $award_record->lottery_id = $lottery->id;
                $award_record->user_id = $order->user_id;
                $award_record->order_id = $order->order_id;
                $award_record->award_index = $final_select;
                $award_record->save();

                $notice = json_decode($lottery->notice,true);
                return array('lottery_type' => 'time', 'award_list' => $award_list, 'award_index' => $final_select,'notice'=>$notice,'sub_title'=>$lottery->sub_title);
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function getLotteryList(Request $request){
        $user_id = $this->getUserId($request);
        $award_list = AwardRecord::join('award',function ($join) {
            $join->on('award.lottery_id', '=', 'award_record.lottery_id')->on('award.award_index', '=', 'award_record.award_index');
        })->where('user_id','=',$user_id)->get();
        return self::setResponse($award_list,200,0);
    }

    //获取未使用的优惠券!!只返回一个
    public function getMoneryLottery(Request $request){
        $user_id = 'U_00000036';
        $award_list = AwardRecord::join('award',function ($join) {
            $join->on('award.lottery_id', '=', 'award_record.lottery_id')->on('award.award_index', '=', 'award_record.award_index');
        })->select('award_record.id','money_award_amount')->where('user_id','=',$user_id)->where('award_type','=','coupon')->where('used','=',0)->get();
        if(count($award_list)==0){
            return self::setResponse(array(),200,0);
        }else{
            return self::setResponse(array($award_list[0]),200,0);
        }
    }
}
