<?php

/**
 * 
 * JSAPI支付实现类
 * 该类实现了从微信公众平台获取code、通过code获取openid和access_token、
 * 生成jsapi支付js接口所需的参数、生成获取共享收货地址所需的参数
 * 
 * 该类是微信支付提供的样例程序，商户可根据自己的需求修改，或者使用lib中的api自行开发
 * 
 * @author widy
 *
 */
class weixin_class
{
	public $appid=WxPayConfig::APPID;
	public $appsercet=WxPayConfig::APPSECRET;

	//构造函数，获取access_token
	public function __construct($appid=NULL,$appsercet=NULL)
	{
		if($appid && $appsercet){
			$this->appid=$appid;
			$this->appsercet=$appsercet;
		}

		//hardcode
		$this->lasttime=time();
		$this->access_token='';

		if(time() > ($this->lasttime+7200)){
			$url="https://api.weixin.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid.'&sercret='.$this->appsercet;
			$res=$this->http_request($url);
			$result=json_decode($res,true);
			//重新保存
			$this->access_token=$result['access_token'];
			$this->lasttime=time();

			//var_dump($this->lasttime);
			//var_dump($this->access_token);

		}
	}

	//发送模板消息
	public function send_templete_message($data){
		$url="https://api.weixin.qq.com/cgi-bin/message/templete/send?access_token=".$this->access_token;
		$res=$this->http_request($url,$data);
		return json_decode($res,true);
	}

	//https请求
	public function http_request($url,$data=null){
		$curl=curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,FALSE);
		if(!empty($data)){
			curl_setopt($curl,CURLOPT_POST,1);
			curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
		}
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		$output=curl_exec($curl);
		curl_close($curl);
		return $output;
	}
}