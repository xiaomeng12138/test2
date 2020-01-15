<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Common;
use Illuminate\Support\Facades\Redis;
class LoginController extends Controller
{
    function index(Request $request){
        // if(!empty($request->echostr)){
        //  echo $request->echostr;die;  //介入接口
        // }
        $xmlstr=file_get_contents("php://input");  //接收xml数据
        $xmlobj=simplexml_load_string($xmlstr);  //将xml数据转换成对象
        //判断用户是否关注
        if($xmlobj->MsgType == 'event'  && $xmlobj->Event == 'subscribe'){
            //用户关注  获取用户openid  和扫描码带参数二维码的参数
            $openid=(string)$xmlobj->FromUserName;
            //获取二维码参数  未关注
            
            $key=(string)$xmlobj->EventKey;
            if($key){
                $key=ltrim($key,'qrscene_');
                Redis::set($key,$openid,20);
                Common::responseText($xmlobj,'正在登陆请稍后');die;
            }
        }

        //用户关注后 扫描带参数二维码
        if($xmlobj->MsgType == 'event'  && $xmlobj->Event == 'SCAN'){
            //用户关注  获取用户openid  和扫描码带参数二维码的参数
            $openid=(string)$xmlobj->FromUserName;
            //获取二维码参数  未关注
            
            $key=(string)$xmlobj->EventKey;
            if($key){
                Redis::set($key,$openid,20);
                Common::responseText($xmlobj,'正在登陆请稍后');die;
            }
        }


    }

   
}
