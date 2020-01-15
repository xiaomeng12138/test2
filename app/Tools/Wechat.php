<?php


namespace App\Tools;
use Illuminate\Support\Facades\Cache;
class Wechat{
  const appId="wx06bef3fdfed9701d";
  const appsecret="40fe2138f8ae0bfb19902da91cbede17";
  //延签
  public static function checkSignature()
  {
      $signature = $_GET["signature"];
      $timestamp = $_GET["timestamp"];
      $nonce = $_GET["nonce"];
      $token ="1904";
      $tmpArr = array($token, $timestamp, $nonce);
      sort($tmpArr, SORT_STRING);
      $tmpStr = implode( $tmpArr );
      $tmpStr = sha1( $tmpStr );
      
      if( $tmpStr == $signature ){
          return true;
      }else{
          return false;
      }
  }
  //回复文本消息
     public static function responseText($msg,$xmlObj)
     {
        echo "<xml>
              <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
              <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
              <CreateTime>".time()."</CreateTime>
              <MsgType><![CDATA[text]]></MsgType>
              <Content><![CDATA[".$msg."]]></Content>
              </xml>";
      }
      /**
       * 回复图片
       * @return [type] [description]
       */
      public static function responseImg($media_id,$xmlObj)
      {
          echo "<xml>
                  <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
                  <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
                  <CreateTime>".time()."</CreateTime>
                  <MsgType><![CDATA[image]]></MsgType>
                  <Image>
                      <MediaId><![CDATA[".$media_id."]]></MediaId>
                  </Image>
              </xml>";
      }
      //获取access_token令牌
      public static function getToken()
      {
        Cache::flush();
        //缓存里有数据 直接读缓存
        $access_token=Cache::get("access_token");
          if(empty($access_token)){
            
            $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".Self::appId."&secret=".Self::appsecret;
            
            //发请求
            $data=file_get_contents($url);
            $data=json_decode($data,true);
            $access_token=$data['access_token'];
            //储存2小时
            Cache::put("access_token",$access_token,7200); 
            //var_dump($access_token);die;
          }
          return $access_token;
      } 
      /**
       * 通过openid 获取用户信息
       */
      public static function getUserInfo($openid)
      {
        //获取用户的基本信息 用户管理-获取用户基本信息 (需要用access_token)
            //开始开发access_token
            $access_token=Self::getToken();
            //调用用户管理-获取用户基本信息 接口 k780
            $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
            //发请求
            $userInfo=file_get_contents($url);
            $userInfo=json_decode($userInfo,true);
            return $userInfo;
      }
      /**
       *  获取一周天气
       * @return [type] [description]
       */
      public static function getWeather($city)
      {
            //调用k780 得到json数据 处理一下
            //接口地址
                $url="http://api.k780.com/?app=weather.future&weaid={$city}&&appkey=46476&sign=7e825d2f54d4405c8d096525a2cf4a97&format=json";
                //请求方式 GET POST
                //读取一个文件 url
                $data=file_get_contents($url);
                //转成数组
                $data=json_decode($data,true);
                //var_dump($data);die;
                $msg="";
                foreach($data['result'] as $key=>$val){
                    $msg.=$val['days']." ".$val['citynm']." ".$val['week']." ".$val['temperature']." ".$val['weather']."\r\n";
                }
                return $msg;
      }
      /**
       *  上传临时素材接口
       * @return [type] [description]
       */
      public static function uploadMediaTmp($path,$media_format)
      {
          //获取token
        $access_token=Self::getToken();
        
        // 111;
        //调用上传临时素材接口
        $url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$access_token}&type={$media_format}";
        //var_dump($url);die;
        //当curl方法发送请求 包含文件 需要用CURLFILE处理文件信息
        //$img="/img/5.png";public_psth()."/".imsges/xxx.jpg
        //var_dump($img);die;
        $postData['medir']=new \CURLFile($path);
        //var_dump($postData);die;
        $res=Curl::Post($url,$postData);
        $res=json_decode($res,true);
        //var_dump($res);die;
        $wechat_media_id=$res['media_id'];
        return $wechat_media_id;
      }

        /**
       * 网页授权获取用户openid
       * @return [type] [description]
       */
      public static function getOpenid()
      {
          //先去session里取openid 
          $openid = session('openid');
          //var_dump($openid);die;
          if(!empty($openid)){
              return $openid;
          }
          //微信授权成功后 跳转咱们配置的地址 （回调地址）带一个code参数
          $code = request()->input('code');
          if(empty($code)){
              //没有授权 跳转到微信服务器进行授权
              $host = $_SERVER['HTTP_HOST'];  //域名
              $uri = $_SERVER['REQUEST_URI']; //路由参数
              $redirect_uri = urlencode("http://".$host.$uri);  // ?code=xx
              $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".self::appId."&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
              header("location:".$url);die;
          }else{
              //通过code换取网页授权access_token
              $url =  "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".self::appId."&secret=".self::appsecret."&code={$code}&grant_type=authorization_code";
              $data=Curl::get($url);
              $data = json_decode($data,true);
              $openid = $data['openid'];
              //获取到openid之后  存储到session当中
              session(['openid'=>$openid]);
              return $openid;
              //如果是非静默授权 再通过openid  access_token获取用户信息
          }   
      }
      /**
     * 网页授权获取用户基本信息
     * @return [type] [description]
     */
    public static function getOpenidByUserInfo()
    {
        //先去session里取openid 
        $userInfo = session('userInfo');
        //var_dump($openid);die;
        if(!empty($userInfo)){
            return $userInfo;
        }
        //微信授权成功后 跳转咱们配置的地址 （回调地址）带一个code参数
        $code = request()->input('code');
        if(empty($code)){
            //没有授权 跳转到微信服务器进行授权
            $host = $_SERVER['HTTP_HOST'];  //域名
            $uri = $_SERVER['REQUEST_URI']; //路由参数
            $redirect_uri = urlencode("http://".$host.$uri);  // ?code=xx
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".self::appId."&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
            header("location:".$url);die;
        }else{
            //通过code换取网页授权access_token
            $url =  "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".self::appId."&secret=".self::appsecret."&code={$code}&grant_type=authorization_code";
            $data = file_get_contents($url);
            $data = json_decode($data,true);
            $openid = $data['openid'];
            $access_token = $data['access_token'];
            //获取到openid之后  存储到session当中
            //session(['openid'=>$openid]);
            //return $openid;
            //如果是非静默授权 再通过openid  access_token获取用户信息
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
            $userInfo = file_get_contents($url);
            $userInfo = json_decode($userInfo,true);
            //返回用户信息
            session(['userInfo'=>$userInfo]);
            return $userInfo;
        }   
    }

    }




