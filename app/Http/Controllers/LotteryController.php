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
        //根据时间判断是否有可用抽奖
        if ($lottery = Lottery::where('end_time', '>', Date('Y-m-d H:i:s', time()))->first()) {
            $lottery_type = $lottery->type;
            $remain_total_count = $lottery->remain_total_count;
            //时间类型抽奖
            if ($lottery_type == 'time' && $remain_total_count!=0) {
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
                //次数类型抽奖
            } elseif($lottery_type == 'num' && $remain_total_count!=0) {

            }else{
                return 1;
            }
        }else{
            return false;
        }
    }
}
