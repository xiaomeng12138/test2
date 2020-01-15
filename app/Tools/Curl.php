<?php


namespace App\Tools;

class Curl{
	public static function Get($url)
	{
		//初始化
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
	public static function Post($url,$postData)
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
	/**
	 * 无限极分类
	 * @param  [type]  $cateInfo  分类数据
	 * @param  integer $parent_id 父类id
	 * @param  integer $level     等级
	 * @return [type]             [description]
	 */
	public static function list_level($cateInfo,$parent_id=0,$level=0)
	{
		static $info=[];//定义静态变量 只占用一个空间
	    foreach($cateInfo as $k=>$v){
	        if($v['parent_id']==$parent_id){
	            // var_dump($v);exit
	            $v['level']=$level;
	            $info[]=$v;
	            self::list_level($cateInfo,$v['power_id'],$level+1);
	        }
	    }
		return $info;
	}

}