<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
class Common extends Controller
{
    const appid="wxdc85626268e1bafd";
    const appsecret="9f01b584b02bdf4c3c5121c07ff36feb";
    //获取Access_Token
    public static function Access_Token(){
        $token=Redis::get('Access_Token');
        if(empty($token)) {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . self::appid . "&secret=" . self::appsecret;
            $res = Common::curlGet($url);
            $data = json_decode($res, true);
            $token=$data['access_token'];
            Redis::set('Access_Token',$token,7200 );
        }
        return $token;
    }


  public static  function curlGet($url)
    {
        //初始化： curl_init
        $ch = curl_init();
        //设置	curl_setopt
        curl_setopt($ch, CURLOPT_URL, $url);  //请求地址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //返回数据格式
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        //执行  curl_exec
        $result = curl_exec($ch);
        //关闭（释放）  curl_close
        curl_close($ch);
        return $result;
    }


   public static function curlPost($url,$postData)
    {
        //初始化： curl_init
        $ch = curl_init();
        //设置	curl_setopt
        curl_setopt($ch, CURLOPT_URL, $url);  //请求地址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //返回数据格式
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        //访问https网站 关闭ssl验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        //执行  curl_exec
        $result = curl_exec($ch);
        //关闭（释放）  curl_close
        curl_close($ch);
        return $result;
    }
}