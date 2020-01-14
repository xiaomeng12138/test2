<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Common;
class LoginController extends Controller
{
    function index(Request $request){
        echo $request->echostr;die;
    }

    function GetQrcode($scene_id){
        $token=Common::Access_Token();
        $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$token}";
        $data='{"action_name": "QR_STR_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}';
        $res=Common::curlPost($url,$data);
        $ticket_info=json_decode($res,true);
        $ticket=$ticket_info['ticket'];
        return 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket;
    }
}
