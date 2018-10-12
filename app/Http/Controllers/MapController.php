<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MapController extends Controller
{
    private $AK = 'BW9kcaWhp7UAffh8EOSpG9fkjmLds75Z';
    public function getCoder(Request $request){
        $latitude = $request->route('latitude');
        $longitude = $request->route('longitude');
        $getCoderUrl = 'http://api.map.baidu.com/geocoder/v2/?ak='.$this->AK.'&coordtype=gcj02ll&location='.$latitude.','.$longitude.'&output=json&pois=0';
        $res = file_get_contents($getCoderUrl);
        $resArr = json_decode($res,true);
        if($resArr['status']==0){
            return self::setResponse($resArr['result'],200,0);
        }else{
            return self::setResponse(null,500,-4048);
        }
    }

    public function search(Request $request){
        $text = $request->route('text');
        $searchUrl = 'http://api.map.baidu.com/place/v2/search?query='.$text.'&page_size=20&page_num=0&scope=2&region=湘潭&output=json&ak='.$this->AK;
        $res = file_get_contents($searchUrl);
        $resArr = json_decode($res,true);
        if($resArr['status']==0){
            return self::setResponse($resArr['results'],200,0);
        }else{
            return self::setResponse(null,500,-4049);
        }
    }

    public function distance(Request $request){
        $start_latitude = $request->route('start_latitude');
        $start_longitude = $request->route('start_longitude');
        $end_latitude = $request->route('end_latitude');
        $end_longitude = $request->route('end_longitude');
        $getCoderUrl = 'http://api.map.baidu.com/direction/v2/riding?origin='.$start_latitude.','.$start_longitude.'&destination='.$end_latitude.','.$end_longitude.'&ak='.$this->AK;
        $res = file_get_contents($getCoderUrl);
        $resArr = json_decode($res,true);

        if($resArr['status']==0){
            //没有路线
            if(count($resArr['result']['routes'])!=0){
                $distance  = $resArr['result']['routes'][0]['distance'];
                $duration  = $resArr['result']['routes'][0]['duration'];
                //计算价格
                $money = $distance/1000*0.5;
                $money = (float)sprintf("%.1f",$money);
                return self::setResponse(array('distance'=>$distance,'duration'=>$duration,'distance_money'=>$money),200,0);
            }else{
                return self::setResponse(array('distance'=>0,'duration'=>0,'distance_money'=>0),200,0);
            }

        }else{
            return self::setResponse(null,500,-4056);
        }
    }

}
